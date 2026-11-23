<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Tips_Hub_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_tips_hub'; }
    public function get_title() { return __('Tips & Tricks Video Hub', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-youtube'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_tips', array(
            'label' => __('Video Grid Settings', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control('count', array(
            'label'   => __('Number of Videos', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
            'max'     => 24,
        ));
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[pttm_tips_hub count="' . intval($settings['count']) . '"]');
    }
}
