<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Order_Wizard_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_order_wizard'; }
    public function get_title() { return __('TV Mounting Order Flow', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-shopping-cart'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('content_section', array(
            'label' => __('Wizard Settings', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control('info_notice', array(
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw'  => __('Renders the complete interactive Prime Time TV Mounting configurator.', 'primetime-tv-mounting'),
        ));
        $this->end_controls_section();
    }

    protected function render() {
        echo do_shortcode('[pttm_order_wizard]');
    }
}
