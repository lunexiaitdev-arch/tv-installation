<?php
/**
 * Template Name: Elementor / Page Builder (Full Width)
 * Template Post Type: page, post
 */
get_header();
?>
<main id="primary" class="pttm-builder-content-container" style="width:100%; min-height:80vh;">
    <?php
    while (have_posts()) : the_post();
        the_content();
    endwhile;
    ?>
</main>
<?php
get_footer();
