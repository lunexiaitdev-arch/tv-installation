<?php
if (!defined('ABSPATH')) exit;

class PTTM_Elementor_FAQ_Widget extends \Elementor\Widget_Base {
    public function get_name() { return 'pttm_faq'; }
    public function get_title() { return __('PTTM FAQ Accordion', 'primetime-tv-mounting'); }
    public function get_icon() { return 'eicon-accordion'; }
    public function get_categories() { return array('primetime-widgets'); }

    protected function register_controls() {
        $this->start_controls_section('section_faq', array(
            'label' => __('FAQ Items', 'primetime-tv-mounting'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ));
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('question', array(
            'label'   => __('Question', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __('Question here...', 'primetime-tv-mounting'),
        ));
        $repeater->add_control('answer', array(
            'label'   => __('Answer', 'primetime-tv-mounting'),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __('Answer here...', 'primetime-tv-mounting'),
        ));
        $this->add_control('faq_items', array(
            'label'       => __('FAQ List', 'primetime-tv-mounting'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{{ question }}}',
        ));
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['faq_items'])) {
            echo '<div class="pttm-faq-accordion">';
            foreach ($settings['faq_items'] as $i => $item) {
                $open = ($i === 0) ? 'is-open' : '';
                echo '<div class="pttm-faq-accordion-item ' . $open . '">';
                echo '<button type="button" class="pttm-faq-question"><span>' . esc_html($item['question']) . '</span><span class="pttm-faq-chevron">&#9662;</span></button>';
                echo '<div class="pttm-faq-answer"><p>' . nl2br(esc_html($item['answer'])) . '</p></div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo do_shortcode('[pttm_faq_accordion]');
        }
    }
}
