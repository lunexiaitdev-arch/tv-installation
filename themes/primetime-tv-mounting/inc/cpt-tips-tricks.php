<?php
/**
 * Prime Time TV Mounting - Tips & Tricks (YouTube Tutorials & SEO Blog)
 */

if (!defined('ABSPATH')) {
    exit;
}

function pttm_register_tips_tricks_cpt() {
    $labels = array(
        'name'                  => 'Tips & Tricks',
        'singular_name'         => 'Tip & Trick',
        'menu_name'             => 'Tips & Tricks',
        'name_admin_bar'        => 'Tip / Tutorial',
        'all_items'             => 'All Tutorials',
        'add_new_item'          => 'Add New Tutorial',
        'add_new'               => 'Add Tutorial',
        'new_item'              => 'New Tutorial',
        'edit_item'             => 'Edit Tutorial',
        'update_item'           => 'Update Tutorial',
        'view_item'             => 'View Tutorial',
        'search_items'          => 'Search Tutorials'
    );

    $args = array(
        'label'                 => 'Tips & Tricks',
        'description'           => 'Weekly YouTube TV mounting tutorials and educational guides',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
        'taxonomies'            => array('tips_category'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 27,
        'menu_icon'             => 'dashicons-video-alt3',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'rewrite'               => array('slug' => 'tips'),
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true
    );
    register_post_type('tips_tricks', $args);

    // Register Category Taxonomy
    register_taxonomy('tips_category', array('tips_tricks'), array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'          => 'Tutorial Categories',
            'singular_name' => 'Tutorial Category',
            'search_items'  => 'Search Categories',
            'all_items'     => 'All Categories',
            'edit_item'     => 'Edit Category',
            'add_new_item'  => 'Add New Category'
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tutorial-category'),
        'show_in_rest'      => true
    ));
}
add_action('init', 'pttm_register_tips_tricks_cpt');

/**
 * Meta Box for YouTube Video Integration
 */
function pttm_add_tips_video_meta_box() {
    add_meta_box(
        'pttm_tips_video_details',
        'YouTube Video & Tutorial Details',
        'pttm_render_tips_video_meta_box',
        'tips_tricks',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pttm_add_tips_video_meta_box');

function pttm_render_tips_video_meta_box($post) {
    wp_nonce_field('pttm_save_tips_video_action', 'pttm_save_tips_video_nonce');
    $video_url = get_post_meta($post->ID, '_pttm_youtube_url', true);
    $duration = get_post_meta($post->ID, '_pttm_video_duration', true);
    $difficulty = get_post_meta($post->ID, '_pttm_difficulty', true) ?: 'Beginner';
    ?>
    <table class="form-table">
        <tr>
            <th scope="row"><label for="pttm_youtube_url">YouTube Video URL / Embed ID</label></th>
            <td>
                <input type="url" name="pttm_youtube_url" id="pttm_youtube_url" value="<?php echo esc_attr($video_url); ?>" class="large-text" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
                <p class="description">Paste any YouTube URL or Video ID. It will automatically embed a responsive HD video player.</p>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="pttm_video_duration">Video Duration</label></th>
            <td>
                <input type="text" name="pttm_video_duration" id="pttm_video_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" placeholder="e.g. 8:45">
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="pttm_difficulty">Installation Level</label></th>
            <td>
                <select name="pttm_difficulty" id="pttm_difficulty">
                    <option value="Beginner" <?php selected($difficulty, 'Beginner'); ?>>Beginner Friendly</option>
                    <option value="Intermediate" <?php selected($difficulty, 'Intermediate'); ?>>Intermediate</option>
                    <option value="Advanced / Pro" <?php selected($difficulty, 'Advanced / Pro'); ?>>Advanced / Pro Tip</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

function pttm_save_tips_video_meta($post_id) {
    if (!isset($_POST['pttm_save_tips_video_nonce']) || !wp_verify_nonce($_POST['pttm_save_tips_video_nonce'], 'pttm_save_tips_video_action')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['pttm_youtube_url'])) {
        update_post_meta($post_id, '_pttm_youtube_url', esc_url_raw($_POST['pttm_youtube_url']));
    }
    if (isset($_POST['pttm_video_duration'])) {
        update_post_meta($post_id, '_pttm_video_duration', sanitize_text_field($_POST['pttm_video_duration']));
    }
    if (isset($_POST['pttm_difficulty'])) {
        update_post_meta($post_id, '_pttm_difficulty', sanitize_text_field($_POST['pttm_difficulty']));
    }
}
add_action('save_post_tips_tricks', 'pttm_save_tips_video_meta');

/**
 * Helper to extract YouTube ID
 */
function pttm_extract_youtube_id($url) {
    if (empty($url)) return false;
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
        return $match[1];
    }
    return strlen($url) === 11 ? $url : false;
}

/**
 * Seed sample Tips & Tricks posts if none exist
 */
function pttm_seed_sample_tips_posts() {
    $existing = get_posts(array('post_type' => 'tips_tricks', 'numberposts' => 1));
    if (!empty($existing)) return;

    $sample_posts = array(
        array(
            'title' => 'How to Conceal TV Cables Inside Drywall (Step-by-Step)',
            'content' => '<p>Learn the clean, code-compliant way to pass HDMI cables and hide power cords using in-wall brush plates and power bridge kits. In this tutorial, we demonstrate how to locate wall studs, cut precision openings, fish cables through insulation, and secure your wall plate without damaging drywall.</p><h3>Key Tools Needed:</h3><ul><li>Stud finder & drywall jab saw</li><li>Low-voltage mounting brackets</li><li>High-speed in-wall rated HDMI 2.1 cables</li><li>Fish tape or magnetic wire puller</li></ul><p>Prefer a professional installation? Prime Time TV Mounting completes in-wall concealed cable installations in under 45 minutes with zero mess.</p>',
            'excerpt' => 'A comprehensive guide to running HDMI and power cables inside drywall for a completely wire-free floating TV look.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '7:30',
            'difficulty' => 'Beginner',
            'category' => 'Cable Concealment'
        ),
        array(
            'title' => 'Samsung The Frame TV: In-Wall One Connect Box Installation Guide',
            'content' => '<p>Samsung Frame TVs look breathtaking when mounted flush against the wall like a museum painting. However, many homeowners struggle with the One Connect box and the specialized optical invisible connection cable.</p><p>In this video, we walk through installing a recessed in-wall media enclosure box directly behind or below the Frame TV to house the One Connect box, Apple TV, and power transformer seamlessly.</p>',
            'excerpt' => 'How to properly mount a Samsung Frame TV flush against the wall and cleanly house the One Connect box inside a recessed media enclosure.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '11:15',
            'difficulty' => 'Intermediate',
            'category' => 'Samsung Frame TV'
        ),
        array(
            'title' => 'Fixed vs Tilt vs Full Motion Mounts: Which TV Bracket Do You Need?',
            'content' => '<p>Choosing the right TV wall bracket can make or break your home theater experience. In this breakdown, we compare Fixed Low-Profile mounts, Tilting mounts (ideal for high placements over fireplaces), and Full Motion Dual-Articulating mounts for corner placements or multi-angle living rooms.</p>',
            'excerpt' => 'Understand the pros, cons, and best use cases for Fixed, Tilt, and Full Motion TV wall brackets before ordering.',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'duration' => '6:45',
            'difficulty' => 'Beginner',
            'category' => 'Mount Selection'
        )
    );

    foreach ($sample_posts as $p) {
        $post_id = wp_insert_post(array(
            'post_title'    => $p['title'],
            'post_content'  => $p['content'],
            'post_excerpt'  => $p['excerpt'],
            'post_type'     => 'tips_tricks',
            'post_status'   => 'publish'
        ));
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_pttm_youtube_url', $p['video_url']);
            update_post_meta($post_id, '_pttm_video_duration', $p['duration']);
            update_post_meta($post_id, '_pttm_difficulty', $p['difficulty']);
            wp_set_object_terms($post_id, $p['category'], 'tips_category');
        }
    }
}
add_action('after_switch_theme', 'pttm_seed_sample_tips_posts');
add_action('init', 'pttm_seed_sample_tips_posts');
