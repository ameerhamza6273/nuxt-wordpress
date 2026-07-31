<?php
/**
 * Snippet Name: Nuxt Vehicle Attributes Endpoint
 * Plugin Name: Ultimate Single Year Fitment Engine with Category Facets
 *
 * This is WP snippet ID 33196 in the live Snippets admin - pasted here
 * 2026-07-31 after being external/unseen for the whole project up to this
 * point (see CLAUDE.md). Filename/header intentionally kept matching the
 * live snippet's own name ("Nuxt Vehicle Attributes Endpoint"), even though
 * neither that name nor the Plugin Name line above reflects what it actually
 * does - matching names on both sides avoids any mismatch/confusion between
 * this repo and the live WP snippet editor.
 *
 * Registers custom/v1/products-filter (Year/Make/Model/Submodel/Engine
 * results grid on pages/products.vue) and custom/v1/product-detail (single
 * product page).
 *
 * Bug fixed 2026-07-31: get_filtered_vehicles_products() never selected the
 * product's real `brand` column and instead hardcoded the response's
 * `brand` field to ucfirst($make) - the vehicle make being searched (e.g.
 * "BMW"), not the product's actual manufacturer brand (e.g. "Pagid").
 * Confirmed live via curl: products-filter?make=BMW returned "brand":"BMW"
 * for item 2624, while that item's real brand column is "Pagid" (per
 * admin-products, which does read the real column). get_single_custom_
 * product_detail() already read the real column correctly (SELECT * +
 * $product['brand']) - only the list endpoint had this bug.
 */

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/products-filter', array(
        'methods'             => 'GET',
        'callback'            => 'get_filtered_vehicles_products',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('custom/v1', '/product-detail', array(
        'methods'             => 'GET',
        'callback'            => 'get_single_custom_product_detail',
        'permission_callback' => '__return_true'
    ));
});

function fitment_send_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET");
}

function fitment_get_products_cached() {
    $cache_key = 'fitment_products_cache_v1';
    $cached = get_transient($cache_key);
    if ($cached !== false) {
        return $cached;
    }
    global $wpdb;
    $products = $wpdb->get_results("SELECT item_id FROM wp_custom_products");
    set_transient($cache_key, $products, 15 * MINUTE_IN_SECONDS);
    return $products;
}

add_action('save_post_product', function () { delete_transient('fitment_products_cache_v1'); });
add_action('before_delete_post', function ($post_id) {
    if (get_post_type($post_id) === 'product') { delete_transient('fitment_products_cache_v1'); }
});

function parse_years_from_title($title) {
    $clean_title = strtolower($title);
    $start_year = 0;
    $end_year = 0;

    if (preg_match('/\b(\d{2})-(\d{2})\b/', $clean_title, $match)) {
        $yr1 = (int)$match[1];
        $yr2 = (int)$match[2];
        $start_year = ($yr1 >= 50) ? (1900 + $yr1) : (2000 + $yr1);
        $end_year   = ($yr2 >= 50) ? (1900 + $yr2) : (2000 + $yr2);
    } elseif (preg_match('/\b(19\d{2}|20\d{2})\b/', $clean_title, $match)) {
        $start_year = (int)$match[1];
        $end_year   = $start_year;
    }

    if ($start_year < 1962 || $start_year > 2035 || $end_year < 1962 || $end_year > 2035 || $end_year < $start_year) {
        return array('start' => 0, 'end' => 0);
    }

    return array('start' => $start_year, 'end' => $end_year);
}

function fitment_title_matches_model($clean_title, $model) {
    if (empty($model)) return true;
    return (bool) preg_match('/\b' . preg_quote($model, '/') . '\b/i', $clean_title);
}

function get_filtered_vehicles_products($request) {
    fitment_send_cors_headers();
    global $wpdb;

    $selected_year = !empty($request->get_param('year')) ? (int) $request->get_param('year') : 0;
    $make          = !empty($request->get_param('make')) ? trim($request->get_param('make')) : '';
    $model         = !empty($request->get_param('model')) ? trim($request->get_param('model')) : '';
    $submodel      = !empty($request->get_param('submodel')) ? trim($request->get_param('submodel')) : '';
    $engine        = !empty($request->get_param('engine')) ? trim($request->get_param('engine')) : '';
    $category_slug = !empty($request->get_param('category')) ? trim($request->get_param('category')) : '';

    $page     = $request->get_param('page') ? max(1, (int) $request->get_param('page')) : 1;
    $per_page = $request->get_param('per_page') ? max(1, (int) $request->get_param('per_page')) : 12;

    if (empty($make)) {
        return array('data' => array(), 'total_items' => 0, 'total_pages' => 1, 'current_page' => 1, 'per_page' => $per_page);
    }

    $where  = array("LOWER(f.make) = LOWER(%s)");
    $params = array($make);

    if ($selected_year > 0) {
        $where[] = "f.year = %d";
        $params[] = $selected_year;
    }
    if (!empty($model)) {
        $where[] = "LOWER(f.model) = LOWER(%s)";
        $params[] = $model;
    }
    if (!empty($submodel)) {
        if (strtolower($submodel) === 'base') {
            $where[] = "(f.submodel IS NULL OR f.submodel = '' OR LOWER(f.submodel) = 'base')";
        } else {
            $where[] = "LOWER(f.submodel) = LOWER(%s)";
            $params[] = $submodel;
        }
    }
    if (!empty($engine)) {
        $where[] = "LOWER(f.engine) = LOWER(%s)";
        $params[] = $engine;
    }

    $sql = "SELECT DISTINCT f.product_id FROM wp_custom_product_fitment f WHERE " . implode(' AND ', $where);
    $product_ids = $wpdb->get_col($wpdb->prepare($sql, $params));

    if (empty($product_ids)) {
        return array('data' => array(), 'total_items' => 0, 'total_pages' => 1, 'current_page' => 1, 'per_page' => $per_page);
    }

    if (!empty($category_slug)) {
        $product_ids = array_values(array_filter($product_ids, function ($pid) use ($category_slug) {
            return has_term($category_slug, 'product_cat', $pid);
        }));
    }

    $verified_ids = array();
    if (!empty($product_ids)) {
        $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));
        $verified_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT item_id FROM wp_custom_products WHERE item_id IN ($placeholders)",
            $product_ids
        ));
    }

    $total_items = count($verified_ids);
    $total_pages = max(1, (int) ceil($total_items / $per_page));
    $page        = min($page, $total_pages);
    $offset      = ($page - 1) * $per_page;
    $page_ids    = array_slice($verified_ids, $offset, $per_page);

    $filtered_products = array();

    if (!empty($page_ids)) {
        $page_placeholders = implode(',', array_fill(0, count($page_ids), '%d'));

        $select_sql = "SELECT p.item_id, p.title, p.sku, p.price, p.description, p.brand, p.stock_status, img.picture_url
                       FROM wp_custom_products p
                       LEFT JOIN wp_custom_product_images img ON p.item_id = img.product_id
                       WHERE p.item_id IN ($page_placeholders)
                       GROUP BY p.item_id";

        $rows = $wpdb->get_results($wpdb->prepare($select_sql, $page_ids), ARRAY_A);

        foreach ($rows as $row) {
            $p_id  = (int) $row['item_id'];
            $price = !empty($row['price']) ? (float)$row['price'] : 0.00;
            $image = !empty($row['picture_url']) ? $row['picture_url'] : 'https://via.placeholder.com/150';

            $filtered_products[] = array(
                'id'                => $p_id,
                'title'             => !empty($row['title']) ? $row['title'] : 'Premium Part #' . $p_id,
                'sku'               => !empty($row['sku']) ? $row['sku'] : 'N/A',
                'price'             => $price > 0 ? '$' . number_format($price, 2) : '$0.00',
                'image'             => $image,
                'image_url'         => $image,
                'permalink'         => '#',
                'description'       => !empty($row['description']) ? wp_strip_all_tags(substr($row['description'], 0, 150)) . '...' : 'Premium replacement part.',
                'short_description' => !empty($row['description']) ? substr(wp_strip_all_tags($row['description']), 0, 150) . '...' : 'Premium replacement part.',
                'brand'             => !empty($row['brand']) ? $row['brand'] : 'Premium OE',
                'stock_status'      => ($row['stock_status'] === 'out_of_stock') ? 'out_of_stock' : 'in_stock'
            );
        }
    }

    return array(
        'data'         => $filtered_products,
        'total_items'  => $total_items,
        'total_pages'  => $total_pages,
        'current_page' => $page,
        'per_page'     => $per_page,
    );
}

function get_single_custom_product_detail($request) {
    fitment_send_cors_headers();
    global $wpdb;

    $product_id = (int) $request->get_param('id');
    if (empty($product_id)) {
        return array('success' => false, 'message' => 'Product id is required');
    }

    $product = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM wp_custom_products WHERE item_id = %d",
        $product_id
    ), ARRAY_A);

    if (!$product) {
        return array('success' => false, 'message' => 'Product not found');
    }

    $images = $wpdb->get_col($wpdb->prepare(
    "SELECT picture_url FROM wp_custom_product_images
     WHERE product_id = %d AND picture_url IS NOT NULL AND picture_url != ''
     ORDER BY id ASC",
    $product_id
));

    $fitment_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT `year`, `make`, `model`, `submodel`, `engine`
         FROM wp_custom_product_fitment
         WHERE product_id = %d
         ORDER BY `year` ASC, `make` ASC, `model` ASC",
        $product_id
    ), ARRAY_A);

    $price = !empty($product['price']) ? (float) $product['price'] : 0.00;

    return array(
        'success'                  => true,
        'id'                       => (int) $product_id,
        'title'                    => !empty($product['title']) ? $product['title'] : '',
        'sku'                      => !empty($product['sku']) ? $product['sku'] : 'N/A',
        'price'                    => $price > 0 ? '$' . number_format($price, 2) : '$0.00',
        'description'              => !empty($product['description']) ? $product['description'] : '',
        'brand'                    => !empty($product['brand']) ? $product['brand'] : 'Premium OE',
        'stock_status'             => (isset($product['stock_status']) && $product['stock_status'] === 'out_of_stock') ? 'out_of_stock' : 'in_stock',
        'placement_on_vehicle'     => !empty($product['placement_on_vehicle']) ? $product['placement_on_vehicle'] : '',
        'manufacturer_part_number' => !empty($product['manufacturer_part_number']) ? $product['manufacturer_part_number'] : '',
        'interchange_part_number'  => !empty($product['interchange_part_number']) ? $product['interchange_part_number'] : '',
        'images'                   => !empty($images) ? array_values($images) : array('https://via.placeholder.com/600'),
        'fitment_notes'            => !empty($product['fitment_notes']) ? $product['fitment_notes'] : '',
        'vin_required_message'     => !empty($product['vin_required_message']) ? $product['vin_required_message'] : '',
        'fitment'                  => $fitment_rows
    );
}
