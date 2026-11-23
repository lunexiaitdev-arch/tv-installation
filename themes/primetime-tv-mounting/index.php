<?php
/**
 * Index Fallback Template
 */
get_header();
?>
<main class="pttm-standard-page">
    <div class="pttm-standard-container">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <article class="pttm-post-preview">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="pttm-prose"><?php the_excerpt(); ?></div>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>
<?php
get_footer();
