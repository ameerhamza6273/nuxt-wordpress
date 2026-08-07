<?php
/**
 * Plugin Name: Bulk Product & Fitment Importer
 *
 * Two endpoints the client's admin/import page posts CSV files to:
 *   POST custom/v1/import-products  (file field: "file") -> wp_custom_products + wp_custom_product_images
 *   POST custom/v1/import-fitment   (file field: "file") -> wp_custom_product_fitment
 *
 * If the uploaded file has a column that doesn't exist yet on the target table,
 * it's added automatically (as TEXT) instead of failing the import. Existing
 * columns are never altered or dropped.
 *
 * Unlike the other custom/v1|v2 read-only routes on this site, these two WRITE
 * to the database and can alter its schema, so they require a shared secret
 * instead of permission_callback => __return_true. Set BULK_IMPORT_SECRET below
 * to a long random value and keep it out of chat/version control, same as the
 * eBay API keys already in config.php.
 */

define('BULK_IMPORT_SECRET', '268d12bb24abaa5a41649d7655d4efcce1a55a8ad2cb319d938e5130df673999');

// Needed for the image-localizing endpoint below (download_url, wp_handle_sideload,
// wp_generate_attachment_metadata). Safe to require here even though
// admin-upload-image.php also requires them - require_once is idempotent.
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

// One-off column setup - visit any wp-admin page with ?run_stock_status_setup=1.
// Adds wp_custom_products.stock_status (VARCHAR, NOT NULL DEFAULT 'in_stock') so
// every existing row is backfilled to 'in_stock' automatically and no read
// endpoint can ever see NULL/empty here - same one-off pattern as orders-endpoint.php.
if (is_admin()) {
    add_action('admin_init', 'bulk_import_run_stock_status_setup');
}

function bulk_import_run_stock_status_setup() {
    if (!isset($_GET['run_stock_status_setup']) || $_GET['run_stock_status_setup'] !== '1') {
        return;
    }

    global $wpdb;
    $products_table = 'wp_custom_products';
    $existing = array_map('strtolower', $wpdb->get_col("SHOW COLUMNS FROM `$products_table`"));

    if (!in_array('stock_status', $existing, true)) {
        $wpdb->query("ALTER TABLE `$products_table` ADD COLUMN stock_status VARCHAR(20) NOT NULL DEFAULT 'in_stock'");
    }

    wp_die("stock_status column ready on $products_table.");
}

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/import-products', array(
        'methods'             => 'POST',
        'callback'            => 'bulk_import_products',
        'permission_callback' => 'bulk_import_check_secret',
    ));

    register_rest_route('custom/v1', '/import-fitment', array(
        'methods'             => 'POST',
        'callback'            => 'bulk_import_fitment',
        'permission_callback' => 'bulk_import_check_secret',
    ));

    register_rest_route('custom/v1', '/admin-products', array(
        'methods'             => 'GET',
        'callback'            => 'admin_list_products',
        'permission_callback' => 'bulk_import_check_secret',
    ));

    register_rest_route('custom/v1', '/admin-product-save', array(
        'methods'             => 'POST',
        'callback'            => 'admin_save_product',
        'permission_callback' => 'bulk_import_check_secret',
    ));

    register_rest_route('custom/v1', '/admin-product-delete', array(
        'methods'             => 'POST',
        'callback'            => 'admin_delete_product',
        'permission_callback' => 'bulk_import_check_secret',
    ));

    register_rest_route('custom/v1', '/localize-product-images', array(
        'methods'             => 'POST',
        'callback'            => 'bulk_import_localize_pending_images',
        'permission_callback' => 'bulk_import_check_secret',
    ));
});

function bulk_import_send_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
}

function bulk_import_check_secret($request) {
    bulk_import_send_cors_headers();

    // Browser CORS preflight never sends custom headers - let it through untouched.
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        return true;
    }

    $sent = $request->get_header('x-import-secret');
    return !empty($sent) && hash_equals(BULK_IMPORT_SECRET, $sent);
}

/**
 * Only lowercase letters/numbers/underscore, must start with a letter or underscore.
 * Anything else is rejected rather than coerced, because this value gets interpolated
 * directly into ALTER TABLE / column-list SQL - identifiers can't be passed through
 * $wpdb->prepare() placeholders the way values can.
 */
function bulk_import_safe_column_name($raw) {
    $name = strtolower(trim($raw));
    $name = preg_replace('/[^a-z0-9_]/', '_', $name);
    $name = preg_replace('/_+/', '_', $name);
    $name = trim($name, '_');
    if ($name === '' || !preg_match('/^[a-z_][a-z0-9_]{0,63}$/', $name)) {
        return false;
    }
    return $name;
}

/**
 * Reads a CSV from $_FILES[$file_field]. Returns an array with sanitized headers,
 * parsed rows (assoc arrays keyed by sanitized header), and any headers that were
 * rejected outright (surfaced back to the caller instead of silently dropped).
 */
function bulk_import_read_csv($file_field) {
    if (empty($_FILES[$file_field]) || !is_uploaded_file($_FILES[$file_field]['tmp_name'])) {
        return new WP_Error('no_file', "No file uploaded under field '$file_field'");
    }

    $handle = fopen($_FILES[$file_field]['tmp_name'], 'r');
    if (!$handle) {
        return new WP_Error('read_fail', 'Could not open uploaded file');
    }

    $raw_headers = fgetcsv($handle);
    if (!$raw_headers) {
        fclose($handle);
        return new WP_Error('empty_file', 'File has no header row');
    }

    $headers = array();
    $skipped_headers = array();
    foreach ($raw_headers as $i => $h) {
        $safe = bulk_import_safe_column_name($h);
        if ($safe === false) {
            $skipped_headers[] = $h;
            $headers[$i] = null;
        } else {
            $headers[$i] = $safe;
        }
    }

    $rows = array();
    while (($line = fgetcsv($handle)) !== false) {
        $row = array();
        foreach ($headers as $i => $col) {
            if ($col === null) continue;
            $row[$col] = isset($line[$i]) ? trim($line[$i]) : '';
        }
        if (!empty(array_filter($row, function ($v) { return $v !== ''; }))) {
            $rows[] = $row;
        }
    }
    fclose($handle);

    return array(
        'headers'         => array_values(array_filter($headers, function ($h) { return $h !== null; })),
        'rows'            => $rows,
        'skipped_headers' => $skipped_headers,
    );
}

/**
 * Adds any column in $needed_columns that doesn't already exist on $table, as TEXT NULL.
 * Never touches or drops an existing column. Columns in $reserved are assumed to already
 * exist (primary/foreign keys) and are skipped even if listed in $needed_columns.
 */
function bulk_import_ensure_columns($table, array $needed_columns, array $reserved_columns) {
    global $wpdb;
    $existing = array_map('strtolower', $wpdb->get_col("SHOW COLUMNS FROM `$table`"));

    $added = array();
    foreach (array_unique($needed_columns) as $col) {
        if (in_array($col, $reserved_columns, true)) continue;
        if (in_array($col, $existing, true)) continue;

        // $col was already validated by bulk_import_safe_column_name() before reaching here.
        $ok = $wpdb->query("ALTER TABLE `$table` ADD COLUMN `$col` TEXT NULL");
        if ($ok !== false) {
            $added[] = $col;
        }
    }
    return $added;
}

function bulk_import_products($request) {
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';
    $reserved       = array('item_id');

    $parsed = bulk_import_read_csv('file');
    if (is_wp_error($parsed)) {
        return array('success' => false, 'message' => $parsed->get_error_message());
    }

    $file_columns  = array_diff($parsed['headers'], array('images'));
    $added_columns = bulk_import_ensure_columns($products_table, $file_columns, $reserved);

    $inserted = 0;
    $updated  = 0;
    $errors   = array();

    foreach ($parsed['rows'] as $row) {
        $sku = isset($row['sku']) ? trim($row['sku']) : '';
        if ($sku === '') {
            $errors[] = 'Skipped a row with no SKU';
            continue;
        }

        $images_raw = isset($row['images']) ? $row['images'] : '';
        unset($row['images'], $row['item_id']); // item_id is assigned explicitly below, never taken from a file

        // ORDER BY item_id DESC + strict "!== null" check: a stray item_id=0 row exists in
        // this table (from a past bad insert, before item_id was assigned explicitly on every
        // insert below) and "0" is falsy in PHP, so a plain `if ($existing_id)` truthiness
        // check treated a legitimately-found row as "not found" whenever it matched that row -
        // confirmed 2026-07-21 while debugging this exact endpoint.
        $existing_id = $wpdb->get_var($wpdb->prepare(
            "SELECT item_id FROM `$products_table` WHERE sku = %s ORDER BY item_id DESC LIMIT 1", $sku
        ));

        if ($existing_id !== null) {
            $ok = $wpdb->update($products_table, $row, array('item_id' => $existing_id));
            if ($ok === false) {
                $errors[] = "Update failed for sku $sku: " . $wpdb->last_error;
                continue;
            }
            $product_id = (int) $existing_id;
            $updated++;
        } else {
            // item_id is NOT an AUTO_INCREMENT column on this table, so the next id has to be
            // computed and supplied explicitly rather than left for MySQL to assign.
            $next_id = (int) $wpdb->get_var("SELECT MAX(item_id) FROM `$products_table`") + 1;
            $row['item_id'] = $next_id;

            $ok = $wpdb->insert($products_table, $row);
            if ($ok === false) {
                $errors[] = "Insert failed for sku $sku: " . $wpdb->last_error;
                continue;
            }
            $product_id = $next_id;
            $inserted++;
        }

        if ($images_raw !== '') {
            $urls = array_values(array_filter(array_map('trim', explode('|', $images_raw))));
            $wpdb->delete($images_table, array('product_id' => $product_id));
            foreach ($urls as $url) {
                $wpdb->insert($images_table, array(
                    'product_id'  => $product_id,
                    'picture_url' => $url,
                ));
            }
        }
    }

    $wpdb->query('COMMIT');

    return array(
        'success'         => true,
        'rows_seen'       => count($parsed['rows']),
        'inserted'        => $inserted,
        'updated'         => $updated,
        'columns_added'   => $added_columns,
        'skipped_headers' => $parsed['skipped_headers'],
        'errors'          => $errors,
    );
}

function bulk_import_fitment($request) {
    global $wpdb;
    $fitment_table  = 'wp_custom_product_fitment';
    $products_table = 'wp_custom_products';
    $reserved       = array('id', 'product_id');

    $parsed = bulk_import_read_csv('file');
    if (is_wp_error($parsed)) {
        return array('success' => false, 'message' => $parsed->get_error_message());
    }

    $file_columns  = array_diff($parsed['headers'], array('sku'));
    $added_columns = bulk_import_ensure_columns($fitment_table, $file_columns, $reserved);

    $by_sku = array();
    foreach ($parsed['rows'] as $row) {
        $sku = isset($row['sku']) ? trim($row['sku']) : '';
        if ($sku === '') continue;
        unset($row['sku'], $row['id'], $row['product_id']);
        $by_sku[$sku][] = $row;
    }

    $inserted     = 0;
    $unknown_skus = array();

    foreach ($by_sku as $sku => $rows) {
        $product_id = $wpdb->get_var($wpdb->prepare(
            "SELECT item_id FROM `$products_table` WHERE sku = %s ORDER BY item_id DESC LIMIT 1", $sku
        ));
        if ($product_id === null) {
            $unknown_skus[] = $sku;
            continue;
        }

        // Replace this product's fitment set wholesale rather than appending, so
        // re-uploading a corrected file doesn't pile up duplicate/stale rows.
        $wpdb->delete($fitment_table, array('product_id' => $product_id));
        foreach ($rows as $row) {
            $row['product_id'] = (int) $product_id;
            $wpdb->insert($fitment_table, $row);
            $inserted++;
        }
    }

    $wpdb->query('COMMIT');

    return array(
        'success'               => true,
        'skus_processed'        => count($by_sku),
        'fitment_rows_inserted' => $inserted,
        'columns_added'         => $added_columns,
        'skipped_headers'       => $parsed['skipped_headers'],
        'unknown_skus'          => $unknown_skus,
    );
}

/**
 * Paginated product listing for the admin dashboard's Products page.
 * Supports an optional ?search= against title/sku, and ?page=/?per_page=.
 */
function admin_list_products($request) {
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';

    $search   = trim((string) $request->get_param('search'));
    $page     = max(1, (int) ($request->get_param('page') ?: 1));
    $per_page = max(1, min(100, (int) ($request->get_param('per_page') ?: 20)));
    $offset   = ($page - 1) * $per_page;

    $where  = '';
    $params = array();
    if ($search !== '') {
        $where  = "WHERE p.title LIKE %s OR p.sku LIKE %s";
        $like   = '%' . $wpdb->esc_like($search) . '%';
        $params = array($like, $like);
    }

    $total_sql = "SELECT COUNT(*) FROM `$products_table` p $where";
    $total = $params ? $wpdb->get_var($wpdb->prepare($total_sql, $params)) : $wpdb->get_var($total_sql);

    $list_sql = "SELECT p.item_id, p.sku, p.title, p.price, p.brand, p.stock_status,
                        (SELECT img.picture_url FROM `$images_table` img
                         WHERE img.product_id = p.item_id ORDER BY img.id ASC LIMIT 1) AS thumbnail
                 FROM `$products_table` p
                 $where
                 ORDER BY p.item_id DESC
                 LIMIT %d OFFSET %d";
    $rows = $wpdb->get_results(
        $wpdb->prepare($list_sql, array_merge($params, array($per_page, $offset))),
        ARRAY_A
    );

    return array(
        'success'     => true,
        'items'       => $rows,
        'total'       => (int) $total,
        'page'        => $page,
        'per_page'    => $per_page,
        'total_pages' => max(1, (int) ceil($total / $per_page)),
    );
}

/**
 * Create or update a single product from the admin add/edit form. Pass item_id
 * to update an existing product; omit it (or send 0) to create a new one.
 * images/fitment, if present, wholesale-replace that product's existing rows -
 * same "replace, don't append" behaviour as the CSV import endpoints.
 */
function admin_save_product($request) {
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';
    $fitment_table  = 'wp_custom_product_fitment';

    $body    = $request->get_json_params();
    $item_id = !empty($body['item_id']) ? (int) $body['item_id'] : 0;
    $sku     = isset($body['sku']) ? trim($body['sku']) : '';

    if ($sku === '') {
        return array('success' => false, 'message' => 'SKU is required');
    }

    $fields = array(
        'sku', 'title', 'price', 'description', 'brand', 'placement_on_vehicle',
        'manufacturer_part_number', 'interchange_part_number', 'other_part_number',
        'fitment_notes', 'vin_required_message', 'stock_status',
    );
    $row = array();
    foreach ($fields as $f) {
        $row[$f] = isset($body[$f]) ? $body[$f] : '';
    }
    if ($row['stock_status'] !== 'out_of_stock') {
        $row['stock_status'] = 'in_stock';
    }

    if ($item_id > 0) {
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT item_id FROM `$products_table` WHERE item_id = %d", $item_id
        ));
        if ($existing === null) {
            return array('success' => false, 'message' => 'Product not found');
        }
        $ok = $wpdb->update($products_table, $row, array('item_id' => $item_id));
        if ($ok === false) {
            return array('success' => false, 'message' => $wpdb->last_error);
        }
    } else {
        // item_id is NOT an AUTO_INCREMENT column on this table, so the next id has to be
        // computed and supplied explicitly rather than left for MySQL to assign.
        $item_id = (int) $wpdb->get_var("SELECT MAX(item_id) FROM `$products_table`") + 1;
        $row['item_id'] = $item_id;
        $ok = $wpdb->insert($products_table, $row);
        if ($ok === false) {
            return array('success' => false, 'message' => $wpdb->last_error);
        }
    }

    if (isset($body['images']) && is_array($body['images'])) {
        $wpdb->delete($images_table, array('product_id' => $item_id));
        foreach ($body['images'] as $url) {
            $url = trim((string) $url);
            if ($url === '') continue;
            $wpdb->insert($images_table, array('product_id' => $item_id, 'picture_url' => $url));
        }
    }

    if (isset($body['fitment']) && is_array($body['fitment'])) {
        $wpdb->delete($fitment_table, array('product_id' => $item_id));
        foreach ($body['fitment'] as $fit) {
            $wpdb->insert($fitment_table, array(
                'product_id' => $item_id,
                'year'       => isset($fit['year']) ? $fit['year'] : '',
                'make'       => isset($fit['make']) ? $fit['make'] : '',
                'model'      => isset($fit['model']) ? $fit['model'] : '',
                'submodel'   => isset($fit['submodel']) ? $fit['submodel'] : '',
                'engine'     => isset($fit['engine']) ? $fit['engine'] : '',
            ));
        }
    }

    $wpdb->query('COMMIT');

    return array('success' => true, 'item_id' => $item_id);
}

/**
 * Downloads a single external image URL (e.g. a PA/manufacturer/CRM link),
 * compresses it the same way admin-upload-image.php does for manual uploads
 * (re-encoded to JPEG, quality stepped down until under ADMIN_IMAGE_MAX_BYTES),
 * and registers it as a real WordPress media library attachment. Returns the
 * new local URL on success, or a WP_Error on failure (bad URL, host down,
 * not an image, etc.) - callers should leave the original URL in place on
 * error rather than losing the image entirely.
 *
 * Depends on admin_compress_image_to_limit() from admin-upload-image.php for
 * the compression step - that snippet must stay active alongside this one
 * (same dependency direction as the other admin panel features). If it isn't
 * active for some reason, the image is still downloaded and hosted on our
 * own server, just without the 500KB compression pass.
 */
function bulk_import_localize_image($external_url) {
    $tmp_file = download_url($external_url, 20); // 20s timeout, one image at a time
    if (is_wp_error($tmp_file)) {
        return $tmp_file;
    }

    $name_guess = sanitize_file_name(basename((string) parse_url($external_url, PHP_URL_PATH)));
    if ($name_guess === '' || strpos($name_guess, '.') === false) {
        $name_guess = 'product-image-' . wp_generate_password(8, false) . '.jpg';
    }

    // wp_handle_sideload()'s first parameter is declared by-reference in WordPress
    // core, so it must be a variable here - passing an array literal directly fails
    // with "could not be passed by reference" (confirmed live 2026-08-07).
    $file_array = array('name' => $name_guess, 'tmp_name' => $tmp_file);
    $overrides  = array('test_form' => false);
    $sideloaded = wp_handle_sideload($file_array, $overrides);

    if (isset($sideloaded['error'])) {
        @unlink($tmp_file);
        return new WP_Error('sideload_failed', $sideloaded['error']);
    }

    $file_path = $sideloaded['file'];
    $max_bytes = defined('ADMIN_IMAGE_MAX_BYTES') ? ADMIN_IMAGE_MAX_BYTES : 500 * 1024;

    if (function_exists('admin_compress_image_to_limit')) {
        admin_compress_image_to_limit($file_path, $max_bytes); // best-effort; not fatal if it can't hit the target
    }

    // Compression (when it runs) always re-encodes to JPEG regardless of the
    // original format, so make sure the stored filename/url/mime-type agree.
    $path_info = pathinfo($file_path);
    $ext = strtolower($path_info['extension']);
    if ($ext !== 'jpg' && $ext !== 'jpeg') {
        $new_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.jpg';
        if (@rename($file_path, $new_path)) {
            $file_path = $new_path;
            $sideloaded['url'] = preg_replace('/\.' . preg_quote($ext, '/') . '$/i', '.jpg', $sideloaded['url']);
        }
    }

    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => 'image/jpeg',
        'post_title'     => sanitize_file_name(pathinfo($file_path, PATHINFO_FILENAME)),
        'post_status'    => 'inherit',
    ), $file_path);

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $file_path);
    wp_update_attachment_metadata($attachment_id, $metadata);

    return wp_get_attachment_url($attachment_id);
}

/**
 * Batch-processes wp_custom_product_images rows whose picture_url still
 * points somewhere other than our own uploads folder (i.e. images that came
 * in as raw external URLs via CSV import or admin_save_product, and haven't
 * been downloaded/hosted locally yet).
 *
 * Deliberately does a small batch per call (?limit=, default 20, max 50)
 * instead of processing everything in one request - a 500-product import can
 * mean 1000+ images, and downloading/compressing all of them synchronously
 * would blow past PHP's execution time limit on shared hosting. The admin
 * panel calls this endpoint repeatedly (polling until remaining=0) to show a
 * progress bar without ever risking a timed-out request. Safe to call again
 * later too (e.g. after adding more products) - it only ever touches rows
 * that aren't already hosted locally.
 */
function bulk_import_localize_pending_images($request) {
    global $wpdb;
    $images_table = 'wp_custom_product_images';

    $limit = (int) $request->get_param('limit');
    $limit = $limit > 0 ? min($limit, 50) : 20;

    $uploads_base = $wpdb->esc_like(wp_upload_dir()['baseurl']);

    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT id, picture_url FROM `$images_table` WHERE picture_url NOT LIKE %s ORDER BY id ASC LIMIT %d",
        $uploads_base . '%', $limit
    ), ARRAY_A);

    $succeeded = 0;
    $failed    = array();

    foreach ($rows as $row) {
        $new_url = bulk_import_localize_image($row['picture_url']);

        if (is_wp_error($new_url)) {
            $failed[] = array('id' => (int) $row['id'], 'url' => $row['picture_url'], 'reason' => $new_url->get_error_message());
            continue;
        }

        $wpdb->update($images_table, array('picture_url' => $new_url), array('id' => $row['id']));
        $succeeded++;
    }

    $remaining = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM `$images_table` WHERE picture_url NOT LIKE %s",
        $uploads_base . '%'
    ));

    return array(
        'success'   => true,
        'processed' => count($rows),
        'succeeded' => $succeeded,
        'failed'    => $failed,
        'remaining' => $remaining,
    );
}

/**
 * Deletes a product and its dependent fitment/image rows.
 */
function admin_delete_product($request) {
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';
    $fitment_table  = 'wp_custom_product_fitment';

    $body    = $request->get_json_params();
    $item_id = !empty($body['item_id']) ? (int) $body['item_id'] : 0;
    if ($item_id <= 0) {
        return array('success' => false, 'message' => 'item_id is required');
    }

    $wpdb->delete($fitment_table, array('product_id' => $item_id));
    $wpdb->delete($images_table, array('product_id' => $item_id));
    $wpdb->delete($products_table, array('item_id' => $item_id));

    $wpdb->query('COMMIT');

    return array('success' => true);
}


