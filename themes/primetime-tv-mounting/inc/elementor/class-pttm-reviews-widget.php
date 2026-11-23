<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_Reviews_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_reviews'; }
    public function get_title() { return __('PTTM Customer Reviews', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-star-rating'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_reviews', array(
            'label' => __('Reviews List', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('text', array(
            'label'   => __('Review Text', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __('Amazing service!', 'primetime-tv-mounting'),
        ));
        $repeater->add_control('author', array(
            'label'   => __('Author', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Happy Customer',
        ));
        $repeater->add_control('location', array(
            'label'   => __('Location / Date', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'Verified Customer',
        ));
        $this->add_control('review_items', array(
            'label'       => __('Customer Testimonials', 'primetime-tv-mounting'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ author }}}',
        ));
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['review_items'])) {
            echo '<div class="pttm-reviews-grid">';
            foreach ($settings['review_items'] as $item) {
                echo '<div class="pttm-review-card">';
                echo '<div class="pttm-review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>';
                echo '<p class="pttm-review-text">&ldquo;' . esc_html($item['text']) . '&rdquo;</p>';
                echo '<div class="pttm-review-meta"><strong>' . esc_html($item['author']) . '</strong><span>' . esc_html($item['location']) . '</span></div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo do_shortcode('[pttm_reviews_grid]');
        }
    }
}
