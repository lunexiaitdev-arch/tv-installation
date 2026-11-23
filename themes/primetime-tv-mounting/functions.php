<?php
/**
 * Prime Time TV Mounting - Functions & Theme Setup
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load Modules
require_once get_template_directory() . '/inc/default-data.php';
require_once get_template_directory() . '/inc/admin-settings.php';
require_once get_template_directory() . '/inc/order-management.php';
require_once get_template_directory() . '/inc/cpt-tips-tricks.php';
require_once get_template_directory() . '/inc/square-gateway.php';
require_once get_template_directory() . '/inc/page-builders-shortcodes.php';
require_once get_template_directory() . '/inc/elementor-widgets.php';
require_once get_template_directory() . '/inc/payment-gateways.php';

/**
 * Theme Setup
 */
function pttm_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(array(
        'primary_menu' => 'Header Primary Menu',
        'footer_menu'  => 'Footer Links'
    ));
    
    // Register CPT and flush
    pttm_register_tips_tricks_cpt();
    flush_rewrite_rules(false);
}
add_action('after_setup_theme', 'pttm_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function pttm_enqueue_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'pttm-google-fonts',
        'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    // Main CSS
    wp_enqueue_style(
        'pttm-main-css',
        get_template_directory_uri() . '/assets/css/main.css',
        array('pttm-google-fonts'),
        '1.0.0'
    );

    $settings = get_option('pttm_settings', pttm_get_default_settings());
    $addons = get_option('pttm_addons', pttm_get_default_addons());
    $services = get_option('pttm_services', pttm_get_default_services());
    $tv_sizes = get_option('pttm_tv_sizes', pttm_get_default_tv_sizes());

    // Google Maps API script if key is present
    if (!empty($settings['google_maps_api_key'])) {
        wp_enqueue_script(
            'google-places-api',
            'https://maps.googleapis.com/maps/api/js?key=' . esc_attr($settings['google_maps_api_key']) . '&libraries=places',
            array(),
            null,
            true
        );
    }

    // Square Web Payments SDK if enabled
    if (PTTM_Square_Gateway::is_enabled()) {
        $square_js = ($settings['square_mode'] === 'production') 
            ? 'https://web.squarecdn.com/v1/square.js' 
            : 'https://sandbox.web.squarecdn.com/v1/square.js';
        wp_enqueue_script('square-sdk', $square_js, array(), null, true);
    }

    // Order Wizard JS
    wp_enqueue_script(
        'pttm-order-wizard',
        get_template_directory_uri() . '/assets/js/order-wizard.js',
        array(),
        '1.0.0',
        true
    );

    // Localize Data for Frontend JavaScript Configurator
    wp_localize_script('pttm-order-wizard', 'PTTM_DATA', array(
        'ajax_url'      => admin_url('admin-ajax.php'),
        'nonce'         => wp_create_nonce('pttm_order_wizard_nonce'),
        'theme_url'     => get_template_directory_uri(),
        'site_url'      => home_url('/'),
        'tv_sizes'      => $tv_sizes,
        'addons'        => array_values(array_filter($addons, function($a) { return !empty($a['enabled']); })),
        'services'      => array_values(array_filter($services, function($s) { return !empty($s['enabled']); })),
        'settings'      => $settings,
        'square_enabled'=> PTTM_Square_Gateway::is_enabled()
    ));
}
add_action('wp_enqueue_scripts', 'pttm_enqueue_scripts');

/**
 * Filter document title separator
 */
function pttm_document_title_separator($sep) {
    return '|';
}
add_filter('document_title_separator', 'pttm_document_title_separator');
