<?php
/**
 * Prime Time TV Mounting - Unified Payment Gateways Architecture
 * Modular support for Square, Stripe, PayPal, and On-Site Pay-on-Completion.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Payment Gateway Configuration Options
 */
function pttm_get_payment_settings() {
    $defaults = array(
        'payment_mode'          => 'intake_only', // 'intake_only', 'deposit', 'full_payment'
        'deposit_type'          => 'fixed',       // 'fixed', 'percentage'
        'deposit_amount'        => 50,            // $50 or 20%
        'currency'              => 'USD',
        
        // Stripe
        'stripe_enabled'        => 0,
        'stripe_mode'           => 'test',        // 'test', 'live'
        'stripe_publishable_key'=> '',
        'stripe_secret_key'     => '',
        'stripe_webhook_secret' => '',
        
        // PayPal
        'paypal_enabled'        => 0,
        'paypal_mode'           => 'sandbox',     // 'sandbox', 'live'
        'paypal_client_id'      => '',
        'paypal_secret'         => '',
        
        // Square
        'square_enabled'        => 0,
        'square_environment'    => 'sandbox',     // 'sandbox', 'production'
        'square_app_id'         => '',
        'square_access_token'   => '',
        'square_location_id'    => ''
    );

    $settings = get_option('pttm_payment_settings', array());
    return wp_parse_args($settings, $defaults);
}

/**
 * Calculate Required Deposit / Payment for an Order Total
 */
function pttm_calculate_due_amount($total, $settings = null) {
    if (!$settings) {
        $settings = pttm_get_payment_settings();
    }
    
    $mode = $settings['payment_mode'] ?? 'intake_only';
    $total = floatval($total);

    if ($mode === 'intake_only') {
        return 0.00;
    }

    if ($mode === 'full_payment') {
        return $total;
    }

    if ($mode === 'deposit') {
        $type = $settings['deposit_type'] ?? 'fixed';
        $amt  = floatval($settings['deposit_amount'] ?? 50);

        if ($type === 'percentage') {
            return round(($total * ($amt / 100)), 2);
        }
        return min($amt, $total);
    }

    return 0.00;
}

/**
 * AJAX Handler: Create Stripe Payment Intent (Ready for Stripe Elements)
 */
function pttm_ajax_create_stripe_intent() {
    check_ajax_referer('pttm_order_nonce', 'nonce');

    $order_id = intval($_POST['order_id'] ?? 0);
    $settings = pttm_get_payment_settings();

    if (empty($settings['stripe_enabled']) || empty($settings['stripe_secret_key'])) {
        wp_send_json_error(array('message' => 'Stripe is not configured.'));
    }

    $order_data = get_post_meta($order_id, '_pttm_order_data', true);
    $total = floatval($order_data['estimated_total'] ?? 0);
    $amount_due = pttm_calculate_due_amount($total, $settings);
    $cents = intval(round($amount_due * 100));

    if ($cents <= 0) {
        wp_send_json_error(array('message' => 'No payment required for this order intake.'));
    }

    // Call Stripe API
    $response = wp_remote_post('https://api.stripe.com/v1/payment_intents', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . trim($settings['stripe_secret_key']),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'amount'   => $cents,
            'currency' => strtolower($settings['currency'] ?? 'usd'),
            'description' => 'PTTM Order #' . $order_id . ' - ' . ($order_data['customer_name'] ?? 'Guest'),
            'metadata' => array(
                'order_id' => $order_id,
                'customer_phone' => $order_data['customer_phone'] ?? ''
            )
        ),
        'timeout' => 20
    ));

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => $response->get_error_message()));
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($body['client_secret'])) {
        update_post_meta($order_id, '_pttm_stripe_intent_id', $body['id']);
        wp_send_json_success(array(
            'client_secret' => $body['client_secret'],
            'amount'        => $amount_due,
            'currency'      => $settings['currency']
        ));
    } else {
        wp_send_json_error(array('message' => $body['error']['message'] ?? 'Unable to create Stripe Intent.'));
    }
}
add_action('wp_ajax_pttm_create_stripe_intent', 'pttm_ajax_create_stripe_intent');
add_action('wp_ajax_nopriv_pttm_create_stripe_intent', 'pttm_ajax_create_stripe_intent');

/**
 * AJAX Handler: Create Square Payment (Ready for Square Web Payments SDK)
 */
function pttm_ajax_process_square_payment() {
    check_ajax_referer('pttm_order_nonce', 'nonce');

    $order_id = intval($_POST['order_id'] ?? 0);
    $source_id = sanitize_text_field($_POST['source_id'] ?? '');
    $settings = pttm_get_payment_settings();

    if (empty($settings['square_enabled']) || empty($settings['square_access_token'])) {
        wp_send_json_error(array('message' => 'Square payment gateway is not enabled.'));
    }

    $order_data = get_post_meta($order_id, '_pttm_order_data', true);
    $total = floatval($order_data['estimated_total'] ?? 0);
    $amount_due = pttm_calculate_due_amount($total, $settings);
    $cents = intval(round($amount_due * 100));

    $host = ($settings['square_environment'] === 'production') 
        ? 'https://connect.squareup.com' 
        : 'https://connect.squareupsandbox.com';

    $body_payload = array(
        'source_id'      => $source_id,
        'idempotency_key'=> wp_generate_uuid4(),
        'amount_money'   => array(
            'amount'   => $cents,
            'currency' => strtoupper($settings['currency'] ?? 'USD')
        ),
        'location_id'    => $settings['square_location_id'] ?? '',
        'reference_id'   => 'PTTM-' . $order_id,
        'note'           => 'Prime Time TV Mounting Order #' . $order_id
    );

    $response = wp_remote_post($host . '/v2/payments', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . trim($settings['square_access_token']),
            'Content-Type'  => 'application/json',
            'Square-Version'=> '2023-12-13'
        ),
        'body'    => wp_json_encode($body_payload),
        'timeout' => 25
    ));

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => $response->get_error_message()));
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($body['payment']) && $body['payment']['status'] === 'COMPLETED') {
        update_post_meta($order_id, '_pttm_payment_status', 'paid');
        update_post_meta($order_id, '_pttm_square_payment_id', $body['payment']['id']);
        wp_send_json_success(array('message' => 'Payment authorized successfully!', 'payment_id' => $body['payment']['id']));
    } else {
        $err = !empty($body['errors'][0]['detail']) ? $body['errors'][0]['detail'] : 'Payment transaction failed.';
        wp_send_json_error(array('message' => $err));
    }
}
add_action('wp_ajax_pttm_process_square_payment', 'pttm_ajax_process_square_payment');
add_action('wp_ajax_nopriv_pttm_process_square_payment', 'pttm_ajax_process_square_payment');
