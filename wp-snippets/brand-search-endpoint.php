<?php
/**
 * Snippet Name: Brand Search Endpoint
 *
 * Public (permission_callback => '__return_true') REST routes that filter
 * wp_custom_products by the `brand` column (manufacturer brand, e.g. Brembo,
 * DFC, Pagid, Textar, Arnott - NOT vehicle make, which is a separate concept
 * already served by custom/v2/vehicle).
 *
 * Built as an independent, from-scratch endpoint rather than extending
 * custom/v1/products-filter, because that route's source was never pasted
 * into this repo (see CLAUDE.md "external routes" note) - this file doesn't
 * touch or depend on it.
 *
 *   GET custom/v1/brand-list
 *     -> [{ brand, product_count }, ...] for every distinct non-empty brand,
 *        ordered by product_count desc. Used to decide which manufacturer
 *        logos are worth showing (only brands that actually have products).
 *
 *   GET custom/v1/products-by-brand?brand=Brembo&page=1&per_page=20&sort=relevance
 *     -> { data: [...], total_items, total_pages, current_page }
 *        Same shape as custom/v1/products-filter's response so the storefront
 *        products page can consume either interchangeably.
 */

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/brand-list', array(
        'methods'             => 'GET',
        'callback'            => 'brand_search_list_brands',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('custom/v1', '/products-by-brand', array(
        'methods'             => 'GET',
        'callback'            => 'brand_search_products_by_brand',
        'permission_callback' => '__return_true',
    ));
});

function brand_search_send_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, OPTIONS");
}

function brand_search_list_brands($request) {
    brand_search_send_cors_headers();
    global $wpdb;
    $products_table = 'wp_custom_products';

    $rows = $wpdb->get_results(
        "SELECT brand, COUNT(*) AS product_count
         FROM `$products_table`
         WHERE brand IS NOT NULL AND brand <> ''
         GROUP BY brand
         ORDER BY product_count DESC, brand ASC",
        ARRAY_A
    );

    return array_map(function ($row) {
        return array(
            'brand'         => $row['brand'],
            'product_count' => (int) $row['product_count'],
        );
    }, $rows ?: array());
}

function brand_search_products_by_brand($request) {
    brand_search_send_cors_headers();
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';

    $brand = trim((string) $request->get_param('brand'));
    if ($brand === '') {
        return new WP_Error('missing_brand', 'brand parameter is required', array('status' => 400));
    }

    $page     = max(1, (int) ($request->get_param('page') ?: 1));
    $per_page = max(1, min(50, (int) ($request->get_param('per_page') ?: 20)));
    $offset   = ($page - 1) * $per_page;

    $sort     = (string) ($request->get_param('sort') ?: 'relevance');
    $order_by = 'p.item_id DESC';
    if ($sort === 'price_low')  $order_by = 'p.price ASC';
    if ($sort === 'price_high') $order_by = 'p.price DESC';

    $total = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM `$products_table` p WHERE p.brand = %s",
        $brand
    ));

    $list_sql = "SELECT p.item_id AS id, p.sku, p.title, p.price, p.brand, p.description AS short_description,
                        (SELECT img.picture_url FROM `$images_table` img
                         WHERE img.product_id = p.item_id ORDER BY img.id ASC LIMIT 1) AS image
                 FROM `$products_table` p
                 WHERE p.brand = %s
                 ORDER BY $order_by
                 LIMIT %d OFFSET %d";
    $rows = $wpdb->get_results(
        $wpdb->prepare($list_sql, $brand, $per_page, $offset),
        ARRAY_A
    );

    $total = (int) $total;
    return array(
        'data'         => $rows ?: array(),
        'total_items'  => $total,
        'total_pages'  => max(1, (int) ceil($total / $per_page)),
        'current_page' => $page,
    );
}
