<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Company_Story_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_company_story'; }
    public function get_title() { return __('Business History & Story', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-history'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_story', array(
            'label' => __('Story Settings', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control('info_notice', array(
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw'  => __('Displays the 10+ years business history, insurance badge, and track record.', 'primetime-tv-mounting'),
        ));
        $this->end_controls_section();
    }

    protected function render() {
        echo do_shortcode('[pttm_business_history]');
    }
}
