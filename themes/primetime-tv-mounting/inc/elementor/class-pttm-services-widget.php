<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Services_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_services_grid'; }
    public function get_title() { return __('Additional Services ($75/hr)', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-gallery-grid'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_srv', array(
            'label' => __('Services Settings', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control('title', array(
            'label'   => __('Section Title', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Additional Handyman & Mounting Services',
        ));
        $this->add_control('subtitle', array(
            'label'   => __('Subtitle', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Available on request at $75 per hour.',
        ));
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[pttm_services_grid title="' . esc_attr($settings['title']) . '" subtitle="' . esc_attr($settings['subtitle']) . '"]');
    }
}
