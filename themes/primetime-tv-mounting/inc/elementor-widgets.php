<?php
/**
 * Prime Time TV Mounting - Elementor Custom Widgets Integration
 * Registers native Elementor widgets and category for full visual drag-and-drop page building.
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. ORDER WIZARD WIDGET
add_action('elementor/widgets/register', 'pttm_register_elementor_widgets');
add_action('elementor/widgets/widgets_registered', 'pttm_register_elementor_widgets_legacy');
add_action('elementor/elements/categories_registered', 'pttm_register_elementor_category');

function pttm_register_elementor_category($elements_manager) {
    $elements_manager->add_category(
        'primetime-widgets',
        array(
            'title' => __('Prime Time TV Mounting', 'primetime-tv-mounting'),
            'icon'  => 'fa fa-tv',
        )
    );
}

function pttm_register_elementor_widgets($widgets_manager) {
    if (!class_exists('\Elementor\Widget_Base')) return;

    require_once __DIR__ . '/elementor/class-pttm-order-wizard-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-hero-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-tips-hub-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-faq-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-reviews-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-services-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-company-story-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-contact-widget.php';

    $widgets_manager->register(new \PTTM_Elementor_Order_Wizard_Widget());
    $widgets_manager->register(new \PTTM_Elementor_Hero_Widget());
    $widgets_manager->register(new \PTTM_Elementor_Tips_Hub_Widget());
    $widgets_manager->register(new \PTTM_Elementor_FAQ_Widget());
    $widgets_manager->register(new \PTTM_Elementor_Reviews_Widget());
    $widgets_manager->register(new \PTTM_Elementor_Services_Widget());
    $widgets_manager->register(new \PTTM_Elementor_Company_Story_Widget());
    $widgets_manager->register(new \PTTM_Elementor_Contact_Widget());
}

function pttm_register_elementor_widgets_legacy() {
    if (!class_exists('\Elementor\Plugin') || !class_exists('\Elementor\Widget_Base')) return;

    require_once __DIR__ . '/elementor/class-pttm-order-wizard-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-hero-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-tips-hub-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-faq-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-reviews-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-services-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-company-story-widget.php';
    require_once __DIR__ . '/elementor/class-pttm-contact-widget.php';

    $wm = \Elementor\Plugin::instance()->widgets_manager;
    $wm->register_widget_type(new \PTTM_Elementor_Order_Wizard_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_Hero_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_Tips_Hub_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_FAQ_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_Reviews_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_Services_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_Company_Story_Widget());
    $wm->register_widget_type(new \PTTM_Elementor_Contact_Widget());
}
