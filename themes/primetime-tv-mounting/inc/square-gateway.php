<?php
/**
 * Prime Time TV Mounting - Square Payments Gateway Module
 */

if (!defined('ABSPATH')) {
    exit;
}

class PTTM_Square_Gateway {
    
    public static function is_enabled() {
        $settings = get_option('pttm_settings', array());
        return !empty($settings['enable_square_payments']) && !empty($settings['square_access_token']) && !empty($settings['square_app_id']);
    }

    public static function get_api_endpoint() {
        $settings = get_option('pttm_settings', array());
        $mode = $settings['square_mode'] ?? 'sandbox';
        return ($mode === 'production') ? 'https://connect.squareup.com' : 'https://connect.squareupsandbox.com';
    }

    public static function create_payment($source_id, $amount, $currency = 'USD', $order_id = 0, $customer_email = '') {
        if (!self::is_enabled()) {
            return new WP_Error('square_disabled', 'Square payments are not currently enabled.');
        }

        $settings = get_option('pttm_settings', array());
        $access_token = $settings['square_access_token'];
        $location_id = $settings['square_location_id'];

        $body = array(
            'source_id' => $source_id,
            'idempotency_key' => wp_generate_uuid4(),
            'amount_money' => array(
                'amount' => intval(round($amount * 100)), // Cents
                'currency' => $currency
            ),
            'location_id' => $location_id,
            'reference_id' => 'PTTM-' . $order_id,
            'note' => 'Prime Time TV Mounting Order #' . $order_id
        );

        if (!empty($customer_email)) {
            $body['buyer_email_address'] = $customer_email;
        }

        $response = wp_remote_post(self::get_api_endpoint() . '/v2/payments', array(
            'headers' => array(
                'Square-Version' => '2023-10-18',
                'Authorization' => 'Bearer ' . $access_token,
                'Content-Type' => 'application/json'
            ),
            'body' => wp_json_encode($body),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code($response);
        $data = json_decode(wp_remote_retrieve_body($response), true);

        if ($code >= 200 && $code < 300 && isset($data['payment'])) {
            return $data['payment'];
        }

        $error_msg = 'Payment failed.';
        if (!empty($data['errors'][0]['detail'])) {
            $error_msg = $data['errors'][0]['detail'];
        }

        return new WP_Error('square_api_error', $error_msg, $data);
    }
}
