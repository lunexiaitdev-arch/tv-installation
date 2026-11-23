<?php
/**
 * Template Name: Tips & Tricks Hub
 */
get_header();

$args = array(
    'post_type'      => 'tips_tricks',
    'posts_per_page' => 12,
    'paged'          => get_query_var('paged') ? get_query_var('paged') : 1
);
$query = new WP_Query($args);
?>

<main class="pttm-tips-archive-page">
    <!-- Hero Banner -->
    <section class="pttm-tips-hero">
        <div class="pttm-tips-hero-container">
            <span class="pttm-tag-pill">Educational Hub & Tutorials</span>
            <h1 class="pttm-tips-hero-title">Tips & Tricks for TV Mounting</h1>
            <p class="pttm-tips-hero-sub">Weekly video tutorials, cable concealment hacks, and professional mounting advice directly from our master installers.</p>
        </div>
    </section>

    <!-- Video Tutorials Grid -->
    <section class="pttm-tips-grid-section">
        <div class="pttm-tips-grid-container">
            
            <?php if ($query->have_posts()): ?>
                <div class="pttm-video-cards-grid">
                    <?php while ($query->have_posts()): $query->the_post(); 
                        $video_url = get_post_meta(get_the_ID(), '_pttm_youtube_url', true);
                        $yt_id = pttm_extract_youtube_id($video_url);
                        $duration = get_post_meta(get_the_ID(), '_pttm_video_duration', true) ?: '6:30';
                        $difficulty = get_post_meta(get_the_ID(), '_pttm_difficulty', true) ?: 'Beginner';
                        $categories = get_the_terms(get_the_ID(), 'tips_category');
                        $cat_name = !empty($categories) && !is_wp_error($categories) ? $categories[0]->name : 'Tutorial';
                    ?>
                        <article class="pttm-video-card">
                            <a href="<?php the_permalink(); ?>" class="pttm-video-thumb-link">
                                <div class="pttm-video-thumbnail">
                                    <?php if ($yt_id): ?>
                                        <img src="https://img.youtube.com/vi/<?php echo esc_attr($yt_id); ?>/hqdefault.jpg" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                    <?php elseif (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('medium_large'); ?>
                                    <?php else: ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/tv-preview.jpg'); ?>" alt="">
                                    <?php endif; ?>
                                    
                                    <div class="pttm-video-play-badge">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                    </div>
                                    <span class="pttm-video-duration"><?php echo esc_html($duration); ?></span>
                                </div>
                            </a>

                            <div class="pttm-video-card-body">
                                <div class="pttm-video-meta-tags">
                                    <span class="pttm-tag-cat"><?php echo esc_html($cat_name); ?></span>
                                    <span class="pttm-tag-diff"><?php echo esc_html($difficulty); ?></span>
                                </div>
                                <h3 class="pttm-video-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="pttm-video-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                                <a href="<?php the_permalink(); ?>" class="pttm-video-watch-link">
                                    <span>Watch Tutorial</span>
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else: ?>
                <div class="pttm-no-posts">
                    <p>New video tutorials are posted every week. Check back shortly!</p>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section class="pttm-tips-cta-section">
        <div class="pttm-tips-cta-box">
            <h2>Rather have a certified pro handle it?</h2>
            <p>Our white-glove technicians mount TVs in under an hour with fully concealed wires and lifetime alignment warranty.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="pttm-btn-primary-pill">
                <span>Start Your Order Request</span>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>
    </section>
</main>

<?php
get_footer();
