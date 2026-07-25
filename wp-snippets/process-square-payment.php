<?php
/**
 * Snippet Name: Process Square Payment
 *
 * Registers custom/v1/process-square-payment (POST) - the checkout endpoint
 * pages/checkout.vue posts to after tokenizing the card with Square's Web
 * Payments SDK client-side. This snippet takes that token, charges it via
 * Square's server-side Payments API, and - on success - records the order
 * into wp_custom_orders / wp_custom_order_items via custom_record_order()
 * (defined in orders-endpoint.php, which must stay active alongside this one).
 *
 * REQUIRED SETUP BEFORE THIS WILL WORK:
 * Set SQUARE_ACCESS_TOKEN below to your Square **Sandbox Access Token**
 * (Square Developer Dashboard -> your app -> Sandbox -> "Sandbox Access
 * Token"). This is DIFFERENT from the Application ID / Location ID that are
 * already public in pages/checkout.vue (APPLICATION_ID, LOCATION_ID) - the
 * Access Token is a secret and must never be exposed client-side, which is
 * why it lives here on the server instead of in the Nuxt app.
 *
 * Currency is hardcoded to USD below (matching the site's displayed prices)
 * - this fixes the "This merchant can only process payments in USD, but
 * amount was provided in GBP" error confirmed live on 2026-07-25.
 */

define('SQUARE_ACCESS_TOKEN', 'EAAAEKfwZly2qKQZdlIimbHu3TklnMsCvo7uik3RjZ_o9lcIk7xzD9VbUVbSBmf0');
define('SQUARE_LOCATION_ID', 'ZY8JDY1RVQWKV'); // same LOCATION_ID as pages/checkout.vue
define('SQUARE_API_BASE', 'https://connect.squareupsandbox.com'); // sandbox - switch to https://connect.squareup.com for real/live payments later
define('SQUARE_API_VERSION', '2024-01-18');

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/process-square-payment', array(
        'methods'             => 'POST',
        'callback'            => 'process_square_payment_handler',
        'permission_callback' => 'process_square_payment_permission',
    ));
});

function process_square_payment_permission($request) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    return true; // public - guest checkout must be able to call this with no login
}

function process_square_payment_handler($request) {
    $body = $request->get_json_params();

    $source_id      = isset($body['sourceId']) ? trim($body['sourceId']) : '';
    $amount_cents   = isset($body['amountCents']) ? (int) $body['amountCents'] : 0;
    $billing        = isset($body['billingDetails']) ? $body['billingDetails'] : array();
    $items_ordered  = isset($body['itemsOrdered']) ? $body['itemsOrdered'] : array();
    $user_type      = isset($body['userType']) ? $body['userType'] : 'guest';

    if ($source_id === '' || $amount_cents <= 0) {
        return array('success' => false, 'message' => 'Missing payment token or invalid amount.');
    }

    $square_body = array(
        'source_id'       => $source_id,
        'idempotency_key' => wp_generate_uuid4(),
        'amount_money'    => array(
            'amount'   => $amount_cents, // cents, e.g. 105000 = $1050.00
            'currency' => 'USD',
        ),
        'location_id' => SQUARE_LOCATION_ID,
    );

    $response = wp_remote_post(SQUARE_API_BASE . '/v2/payments', array(
        'timeout' => 20,
        'headers' => array(
            'Square-Version' => SQUARE_API_VERSION,
            'Authorization'  => 'Bearer ' . SQUARE_ACCESS_TOKEN,
            'Content-Type'   => 'application/json',
        ),
        'body' => wp_json_encode($square_body),
    ));

    if (is_wp_error($response)) {
        return array('success' => false, 'message' => 'Could not reach Square: ' . $response->get_error_message());
    }

    $status = wp_remote_retrieve_response_code($response);
    $data   = json_decode(wp_remote_retrieve_body($response), true);

    if ($status < 200 || $status >= 300 || empty($data['payment'])) {
        $message = !empty($data['errors'][0]['detail']) ? $data['errors'][0]['detail'] : 'Payment failed.';
        return array('success' => false, 'message' => $message);
    }

    $payment = $data['payment'];
    $square_payment_id = isset($payment['id']) ? $payment['id'] : '';

    // Payment succeeded - record the order. custom_record_order() is defined
    // in orders-endpoint.php and must be active for this call to work.
    $order = function_exists('custom_record_order') ? custom_record_order(array(
        'billingDetails'  => $billing,
        'itemsOrdered'    => $items_ordered,
        'amountCents'     => $amount_cents,
        'userType'        => $user_type,
        'squarePaymentId' => $square_payment_id,
    )) : null;

    return array(
        'success'         => true,
        'paymentId'       => $square_payment_id,
        'orderNumber'     => $order ? $order['order_number'] : null,
    );
}
