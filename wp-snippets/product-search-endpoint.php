<?php
/**
 * Snippet Name: Product Search Endpoint (SKU / Part Number)
 *
 * Public (permission_callback => '__return_true') REST route that searches
 * wp_custom_products by SKU / manufacturer part number / interchange part
 * number / other part number / title - the real "search by part" the client
 * confirmed should be the storefront's primary, fastest search input
 * (replacing PageNavbar's old make-only search box, which had no backend
 * route to search by anything except vehicle make).
 *
 * Built as an independent, from-scratch endpoint (same reasoning as
 * brand-search-endpoint.php) rather than extending custom/v1/products-filter,
 * since that route's source was never pasted into this repo.
 *
 *   GET custom/v1/product-search?q=355040911&page=1&per_page=20&sort=relevance
 *     -> { data: [...], total_items, total_pages, current_page }
 *        Same response shape as products-filter / products-by-brand, so the
 *        storefront renders results the same way regardless of search mode.
 *        Exact SKU / part-number matches are ranked before partial matches.
 */

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/product-search', array(
        'methods'             => 'GET',
        'callback'            => 'product_search_run',
        'permission_callback' => '__return_true',
    ));
});

function product_search_send_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, OPTIONS");
}

function product_search_run($request) {
    product_search_send_cors_headers();
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';

    $q = trim((string) $request->get_param('q'));
    if ($q === '') {
        return new WP_Error('missing_query', 'q parameter is required', array('status' => 400));
    }

    $page     = max(1, (int) ($request->get_param('page') ?: 1));
    $per_page = max(1, min(50, (int) ($request->get_param('per_page') ?: 20)));
    $offset   = ($page - 1) * $per_page;

    $like = '%' . $wpdb->esc_like($q) . '%';

    $match_where = "(p.sku LIKE %s OR p.manufacturer_part_number LIKE %s OR p.interchange_part_number LIKE %s OR p.other_part_number LIKE %s OR p.title LIKE %s)";
    $match_args  = array($like, $like, $like, $like, $like);

    $total = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM `$products_table` p WHERE $match_where",
        $match_args
    ));

    // Exact SKU / part-number matches first, then partial matches, then newest.
    $sort     = (string) ($request->get_param('sort') ?: 'relevance');
    $order_by = "(CASE WHEN p.sku = %s OR p.manufacturer_part_number = %s OR p.interchange_part_number = %s OR p.other_part_number = %s THEN 0 ELSE 1 END) ASC, p.item_id DESC";
    if ($sort === 'price_low')  $order_by = "(CASE WHEN p.sku = %s OR p.manufacturer_part_number = %s OR p.interchange_part_number = %s OR p.other_part_number = %s THEN 0 ELSE 1 END) ASC, p.price ASC";
    if ($sort === 'price_high') $order_by = "(CASE WHEN p.sku = %s OR p.manufacturer_part_number = %s OR p.interchange_part_number = %s OR p.other_part_number = %s THEN 0 ELSE 1 END) ASC, p.price DESC";

    $list_sql = "SELECT p.item_id AS id, p.sku, p.title, p.price, p.brand, p.stock_status, p.description AS short_description,
                        (SELECT img.picture_url FROM `$images_table` img
                         WHERE img.product_id = p.item_id ORDER BY img.id ASC LIMIT 1) AS image
                 FROM `$products_table` p
                 WHERE $match_where
                 ORDER BY $order_by
                 LIMIT %d OFFSET %d";

    $args = array_merge($match_args, array($q, $q, $q, $q), array($per_page, $offset));
    $rows = $wpdb->get_results($wpdb->prepare($list_sql, $args), ARRAY_A);

    return array(
        'data'         => $rows ?: array(),
        'total_items'  => $total,
        'total_pages'  => max(1, (int) ceil($total / $per_page)),
        'current_page' => $page,
    );
}
