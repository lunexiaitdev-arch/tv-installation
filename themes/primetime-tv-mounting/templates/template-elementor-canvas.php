<?php
/**
 * Template Name: Elementor / Page Builder (Canvas - Blank)
 * Template Post Type: page, post
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main id="primary" class="pttm-builder-canvas">
    <?php
    while (have_posts()) : the_post();
        the_content();
    endwhile;
    ?>
</main>
<?php wp_footer(); ?>
</body>
</html>
