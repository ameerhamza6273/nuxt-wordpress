<?php
/**
 * Snippet Name: Orders & Customer Tracking
 *
 * Adds order tracking on top of the existing custom catalog (wp_custom_products /
 * wp_custom_product_fitment / wp_custom_product_images) and the custom app-login
 * table (`w84_custom_app_users` - id, username, email, password, created_at -
 * NOT WordPress's own wp_users, this project has its own login system).
 *
 * Two new tables (created on first admin visit to
 * ?run_orders_setup=1, same one-off pattern as extract-ebay-fitment-data.php):
 *
 *   wp_custom_orders       - one row per order
 *   wp_custom_order_items  - one row per line item in an order
 *
 * INTEGRATION STEP STILL NEEDED (not done yet - waiting on that snippet's
 * source): the checkout flow's `custom/v1/process-square-payment` callback
 * needs to call custom_record_order() below, right after the Square charge
 * succeeds, passing the same billingDetails/itemsOrdered/amountCents/userType
 * fields the Nuxt checkout page already sends it, plus the Square payment id
 * it gets back from the charge. Until that one line is added there, orders
 * placed on the live site won't show up in these new tables yet.
 */

define('ORDERS_TABLE', 'wp_custom_orders');
define('ORDER_ITEMS_TABLE', 'wp_custom_order_items');
define('APP_USERS_TABLE', 'w84_custom_app_users');

// ---------------------------------------------------------------------
// One-off table setup - visit any wp-admin page with ?run_orders_setup=1
// ---------------------------------------------------------------------
if (is_admin()) {
    add_action('admin_init', 'orders_run_table_setup');
}

function orders_run_table_setup() {
    if (!isset($_GET['run_orders_setup']) || $_GET['run_orders_setup'] !== '1') {
        return;
    }

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $wpdb->query("CREATE TABLE IF NOT EXISTS `" . ORDERS_TABLE . "` (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_number VARCHAR(20) NOT NULL UNIQUE,
        app_user_id INT UNSIGNED NULL,
        customer_name VARCHAR(191) NOT NULL DEFAULT '',
        customer_email VARCHAR(191) NOT NULL DEFAULT '',
        customer_phone VARCHAR(50) NOT NULL DEFAULT '',
        customer_postcode VARCHAR(20) NOT NULL DEFAULT '',
        total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
        currency VARCHAR(10) NOT NULL DEFAULT 'USD',
        status VARCHAR(20) NOT NULL DEFAULT 'paid',
        payment_method VARCHAR(20) NOT NULL DEFAULT 'square',
        square_payment_id VARCHAR(100) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY app_user_id (app_user_id),
        KEY customer_email (customer_email)
    ) $charset_collate;");

    $wpdb->query("CREATE TABLE IF NOT EXISTS `" . ORDER_ITEMS_TABLE . "` (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        order_id INT UNSIGNED NOT NULL,
        product_id INT UNSIGNED NULL,
        sku VARCHAR(100) NOT NULL DEFAULT '',
        title VARCHAR(255) NOT NULL DEFAULT '',
        price DECIMAL(10,2) NOT NULL DEFAULT 0,
        quantity INT UNSIGNED NOT NULL DEFAULT 1,
        vin VARCHAR(50) NOT NULL DEFAULT '',
        KEY order_id (order_id),
        KEY product_id (product_id)
    ) $charset_collate;");

    wp_die("Orders tables ready: " . ORDERS_TABLE . " and " . ORDER_ITEMS_TABLE . ".");
}

// ---------------------------------------------------------------------
// Shared secret check - reuses the BULK_IMPORT_SECRET constant defined in
// bulk-import-endpoint.php. That snippet must stay active alongside this
// one (constants are defined when each snippet's top-level code runs at
// WP boot, well before any REST request is dispatched, so load order
// between the two snippet files doesn't matter - only that both are active).
// ---------------------------------------------------------------------
function orders_send_cors_headers() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
}

function orders_check_secret($request) {
    orders_send_cors_headers();
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        return true;
    }
    $sent = $request->get_header('x-import-secret');
    return !empty($sent) && hash_equals(BULK_IMPORT_SECRET, $sent);
}

// ---------------------------------------------------------------------
// custom_record_order() - the actual "create an order" function.
// Call this from process-square-payment's success branch (see note above).
//
// $data shape (matches what the Nuxt checkout page already POSTs):
//   [
//     'billingDetails'   => ['firstName' => ..., 'email' => ..., 'phone' => ..., 'postcode' => ...],
//     'itemsOrdered'     => [ ['sku'=>..,'title'=>..,'price'=>..,'quantity'=>..,'id'=>..,'vin'=>..], ... ],
//     (field names match composables/useCart.js's cart item shape exactly -
//     it stores the product's item_id under the key 'id', not 'item_id')
//     'amountCents'      => 105000,
//     'userType'         => 'guest' | 'logged_in',
//     'squarePaymentId'  => 'the Square charge/payment id',
//   ]
// ---------------------------------------------------------------------
function custom_record_order($data) {
    global $wpdb;

    $billing = isset($data['billingDetails']) ? $data['billingDetails'] : array();
    $items   = isset($data['itemsOrdered']) && is_array($data['itemsOrdered']) ? $data['itemsOrdered'] : array();
    $email   = isset($billing['email']) ? trim($billing['email']) : '';

    // Resolve app_user_id by email if this app has a matching account - optional,
    // orders still work fine for guests/unmatched emails (app_user_id stays NULL).
    $app_user_id = null;
    if ($email !== '') {
        $app_user_id = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM `" . APP_USERS_TABLE . "` WHERE email = %s LIMIT 1", $email
        ));
        $app_user_id = $app_user_id !== null ? (int) $app_user_id : null;
    }

    $wpdb->insert(ORDERS_TABLE, array(
        'order_number'      => 'PENDING', // replaced right after insert, once we have the id
        'app_user_id'       => $app_user_id,
        'customer_name'     => isset($billing['firstName']) ? $billing['firstName'] : '',
        'customer_email'    => $email,
        'customer_phone'    => isset($billing['phone']) ? $billing['phone'] : '',
        'customer_postcode' => isset($billing['postcode']) ? $billing['postcode'] : '',
        'total_amount'      => isset($data['amountCents']) ? round($data['amountCents'] / 100, 2) : 0,
        'payment_method'    => 'square',
        'square_payment_id' => isset($data['squarePaymentId']) ? $data['squarePaymentId'] : '',
        'status'            => 'paid',
    ));

    $order_id = (int) $wpdb->insert_id;
    $order_number = 'LAP-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
    $wpdb->update(ORDERS_TABLE, array('order_number' => $order_number), array('id' => $order_id));

    foreach ($items as $item) {
        $wpdb->insert(ORDER_ITEMS_TABLE, array(
            'order_id'   => $order_id,
            'product_id' => isset($item['id']) ? (int) $item['id'] : null,
            'sku'        => isset($item['sku']) ? $item['sku'] : '',
            'title'      => isset($item['title']) ? $item['title'] : '',
            'price'      => isset($item['price']) ? (float) preg_replace('/[^0-9.]/', '', (string) $item['price']) : 0,
            'quantity'   => isset($item['quantity']) ? (int) $item['quantity'] : 1,
            'vin'        => isset($item['vin']) ? $item['vin'] : '',
        ));
    }

    return array('order_id' => $order_id, 'order_number' => $order_number);
}

// ---------------------------------------------------------------------
// Admin endpoints (gated by X-Import-Secret, same as the admin product panel)
// ---------------------------------------------------------------------
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/admin-orders', array(
        'methods'             => 'GET',
        'callback'            => 'orders_admin_list',
        'permission_callback' => 'orders_check_secret',
    ));

    register_rest_route('custom/v1', '/admin-order-detail', array(
        'methods'             => 'GET',
        'callback'            => 'orders_admin_detail',
        'permission_callback' => 'orders_check_secret',
    ));

    register_rest_route('custom/v1', '/admin-order-update-status', array(
        'methods'             => 'POST',
        'callback'            => 'orders_admin_update_status',
        'permission_callback' => 'orders_check_secret',
    ));

    // Customer-facing order history - public by email, same "public read by
    // email" pattern already used by get-user-billing in
    // temporary-csv-fitment-parser.php (same caveat: no ownership/token check,
    // anyone who knows the email can see that email's orders).
    register_rest_route('custom/v1', '/my-orders', array(
        'methods'             => 'GET',
        'callback'            => 'orders_my_orders',
        'permission_callback' => '__return_true',
    ));
});

function orders_admin_list($request) {
    global $wpdb;

    $search   = trim((string) $request->get_param('search'));
    $status   = trim((string) $request->get_param('status'));
    $page     = max(1, (int) ($request->get_param('page') ?: 1));
    $per_page = max(1, min(100, (int) ($request->get_param('per_page') ?: 20)));
    $offset   = ($page - 1) * $per_page;

    $where  = array();
    $params = array();
    if ($search !== '') {
        $where[]  = '(order_number LIKE %s OR customer_email LIKE %s OR customer_name LIKE %s)';
        $like     = '%' . $wpdb->esc_like($search) . '%';
        $params   = array_merge($params, array($like, $like, $like));
    }
    if ($status !== '') {
        $where[]  = 'status = %s';
        $params[] = $status;
    }
    $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $total_sql = "SELECT COUNT(*) FROM `" . ORDERS_TABLE . "` $where_sql";
    $total = $params ? $wpdb->get_var($wpdb->prepare($total_sql, $params)) : $wpdb->get_var($total_sql);

    $list_sql = "SELECT id, order_number, customer_name, customer_email, total_amount, status, created_at
                 FROM `" . ORDERS_TABLE . "` $where_sql
                 ORDER BY id DESC
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

function orders_admin_detail($request) {
    global $wpdb;
    $id = (int) $request->get_param('id');
    if ($id <= 0) {
        return array('success' => false, 'message' => 'id is required');
    }

    $order = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM `" . ORDERS_TABLE . "` WHERE id = %d", $id
    ), ARRAY_A);

    if (!$order) {
        return array('success' => false, 'message' => 'Order not found');
    }

    $items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM `" . ORDER_ITEMS_TABLE . "` WHERE order_id = %d", $id
    ), ARRAY_A);

    $order['items'] = $items;
    return array('success' => true, 'order' => $order);
}

function orders_admin_update_status($request) {
    global $wpdb;
    $body   = $request->get_json_params();
    $id     = !empty($body['id']) ? (int) $body['id'] : 0;
    $status = isset($body['status']) ? trim($body['status']) : '';

    $allowed = array('pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded');
    if ($id <= 0 || !in_array($status, $allowed, true)) {
        return array('success' => false, 'message' => 'Valid id and status are required');
    }

    $ok = $wpdb->update(ORDERS_TABLE, array('status' => $status), array('id' => $id));
    if ($ok === false) {
        return array('success' => false, 'message' => $wpdb->last_error);
    }

    return array('success' => true);
}

function orders_my_orders($request) {
    global $wpdb;
    orders_send_cors_headers();

    $email = isset($request['email']) ? trim($request['email']) : '';
    if ($email === '') {
        return array('success' => false, 'message' => 'Email parameter is missing');
    }

    $orders = $wpdb->get_results($wpdb->prepare(
        "SELECT id, order_number, total_amount, status, created_at
         FROM `" . ORDERS_TABLE . "` WHERE customer_email = %s ORDER BY id DESC", $email
    ), ARRAY_A);

    foreach ($orders as &$order) {
        $order['items'] = $wpdb->get_results($wpdb->prepare(
            "SELECT sku, title, price, quantity FROM `" . ORDER_ITEMS_TABLE . "` WHERE order_id = %d", $order['id']
        ), ARRAY_A);
    }

    return array('success' => true, 'orders' => $orders);
}
