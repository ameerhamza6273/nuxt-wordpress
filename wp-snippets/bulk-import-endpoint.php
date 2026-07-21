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

    $list_sql = "SELECT p.item_id, p.sku, p.title, p.price, p.brand,
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
        'fitment_notes', 'vin_required_message',
    );
    $row = array();
    foreach ($fields as $f) {
        $row[$f] = isset($body[$f]) ? $body[$f] : '';
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

