<?php
/**
 * Snippet Name: Export Products
 *
 * Registers custom/v1/export-products and custom/v1/export-fitment (both
 * GET) - downloadable CSV exports of the current catalog, in the exact same
 * column format bulk-import-endpoint.php's import-products/import-fitment
 * expect. Two purposes: (1) gives the client a real template to model their
 * CRM export on, (2) the export/import pair is round-trippable - exporting
 * then re-importing should recreate the same data.
 *
 * Gated by the same X-Import-Secret as the rest of the admin panel
 * (BULK_IMPORT_SECRET, defined in bulk-import-endpoint.php - that snippet
 * must stay active alongside this one).
 */

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/export-products', array(
        'methods'             => 'GET',
        'callback'            => 'export_products_handler',
        'permission_callback' => 'export_products_check_secret',
    ));

    register_rest_route('custom/v1', '/export-fitment', array(
        'methods'             => 'GET',
        'callback'            => 'export_fitment_handler',
        'permission_callback' => 'export_products_check_secret',
    ));
});

function export_products_check_secret($request) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, OPTIONS");

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        return true;
    }

    $sent = $request->get_header('x-import-secret');
    return !empty($sent) && hash_equals(BULK_IMPORT_SECRET, $sent);
}

/**
 * Writes $rows (array of assoc arrays, all sharing $columns as keys) as CSV
 * straight to the response, sets download headers, and ends the request -
 * bypasses WP REST's normal JSON response serialization entirely, since we
 * need a raw text/csv body instead.
 */
function export_products_send_csv($filename, $columns, $rows) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $out = fopen('php://output', 'w');
    fputcsv($out, $columns);
    foreach ($rows as $row) {
        $line = array();
        foreach ($columns as $col) {
            $line[] = isset($row[$col]) ? $row[$col] : '';
        }
        fputcsv($out, $line);
    }
    fclose($out);
    exit;
}

function export_products_handler($request) {
    global $wpdb;
    $products_table = 'wp_custom_products';
    $images_table   = 'wp_custom_product_images';

    $columns = array(
        'sku', 'title', 'price', 'description', 'brand', 'placement_on_vehicle',
        'manufacturer_part_number', 'interchange_part_number', 'other_part_number',
        'fitment_notes', 'vin_required_message', 'images',
    );

    $products = $wpdb->get_results("SELECT * FROM `$products_table` ORDER BY item_id ASC", ARRAY_A);

    $rows = array();
    foreach ($products as $p) {
        $image_urls = $wpdb->get_col($wpdb->prepare(
            "SELECT picture_url FROM `$images_table` WHERE product_id = %d ORDER BY id ASC", $p['item_id']
        ));
        $p['images'] = implode('|', $image_urls);
        $rows[] = $p;
    }

    export_products_send_csv('products-export.csv', $columns, $rows);
}

function export_fitment_handler($request) {
    global $wpdb;
    $fitment_table  = 'wp_custom_product_fitment';
    $products_table = 'wp_custom_products';

    $columns = array('sku', 'year', 'make', 'model', 'submodel', 'engine');

    $rows = $wpdb->get_results(
        "SELECT p.sku, f.year, f.make, f.model, f.submodel, f.engine
         FROM `$fitment_table` f
         JOIN `$products_table` p ON p.item_id = f.product_id
         ORDER BY p.sku ASC, f.year ASC",
        ARRAY_A
    );

    export_products_send_csv('fitment-export.csv', $columns, $rows);
}
