<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Contact_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_contact_info'; }
    public function get_title() { return __('Contact & Dispatch Info', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-tel-field'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_contact', array(
            'label' => __('Contact Card Settings', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control('info_notice', array(
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw'  => __('Displays company phone number, hours, email, and dispatch guarantee.', 'primetime-tv-mounting'),
        ));
        $this->end_controls_section();
    }

    protected function render() {
        echo do_shortcode('[pttm_contact_info]');
    }
}
