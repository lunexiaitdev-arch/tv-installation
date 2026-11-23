<?php
/**
 * Generic Post Template
 */
get_header();
?>
<main class="pttm-standard-page">
    <div class="pttm-standard-container">
        <?php while (have_posts()): the_post(); ?>
            <header class="pttm-page-header">
                <h1 class="pttm-page-title"><?php the_title(); ?></h1>
                <div class="pttm-page-meta"><?php echo get_the_date(); ?></div>
            </header>
            <div class="pttm-page-content pttm-prose">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php
get_footer();
