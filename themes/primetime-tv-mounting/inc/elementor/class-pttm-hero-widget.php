<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Hero_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_hero_selector'; }
    public function get_title() { return __('Hero TV Quantity Selector', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-banner'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_hero', array(
            'label' => __('Hero Content', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $this->add_control('hero_title', array(
            'label'   => __('Hero Title', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => "How many TVs\nwould you like mounted?",
        ));
        $this->add_control('badge_insurance', array(
            'label'   => __('Badge 1', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '$1M LIABILITY INSURANCE',
        ));
        $this->add_control('badge_experience', array(
            'label'   => __('Badge 2', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '10+ YEARS EXPERIENCE',
        ));
        $this->add_control('badge_reviews', array(
            'label'   => __('Badge 3', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'HUNDREDS OF 5-STAR REVIEWS',
        ));
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[pttm_hero_selector title="' . esc_attr($settings['hero_title']) . '" badge_insurance="' . esc_attr($settings['badge_insurance']) . '" badge_experience="' . esc_attr($settings['badge_experience']) . '" badge_reviews="' . esc_attr($settings['badge_reviews']) . '"]');
    }
}
