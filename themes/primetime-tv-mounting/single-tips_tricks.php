<?php
/**
 * Single Post Template for Tips & Tricks
 */
get_header();

while (have_posts()): the_post();
    $video_url = get_post_meta(get_the_ID(), '_pttm_youtube_url', true);
    $yt_id = pttm_extract_youtube_id($video_url);
    $duration = get_post_meta(get_the_ID(), '_pttm_video_duration', true);
    $difficulty = get_post_meta(get_the_ID(), '_pttm_difficulty', true) ?: 'Beginner';
    $categories = get_the_terms(get_the_ID(), 'tips_category');
    $cat_name = !empty($categories) && !is_wp_error($categories) ? $categories[0]->name : 'Tutorial';
?>

<main class="pttm-single-tips-page">
    <div class="pttm-single-tips-container">
        
        <!-- Breadcrumbs / Back Link -->
        <div class="pttm-breadcrumbs">
            <a href="<?php echo esc_url(home_url('/tips-tricks/')); ?>">&larr; Back to all Tips & Tricks</a>
        </div>

        <header class="pttm-article-header">
            <div class="pttm-video-meta-tags">
                <span class="pttm-tag-cat"><?php echo esc_html($cat_name); ?></span>
                <span class="pttm-tag-diff"><?php echo esc_html($difficulty); ?></span>
                <?php if ($duration): ?>
                    <span class="pttm-tag-duration">&#9201; <?php echo esc_html($duration); ?></span>
                <?php endif; ?>
            </div>
            <h1 class="pttm-article-title"><?php the_title(); ?></h1>
            <div class="pttm-article-date">Published by Prime Time TV Mounting &bull; <?php echo get_the_date('F j, Y'); ?></div>
        </header>

        <!-- Video Embed Player Container -->
        <?php if ($yt_id): ?>
            <div class="pttm-video-player-wrapper">
                <iframe 
                    src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr($yt_id); ?>?rel=0&modestbranding=1" 
                    title="<?php the_title_attribute(); ?>" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        <?php endif; ?>

        <!-- Written Article & Guide Content -->
        <div class="pttm-article-content pttm-prose">
            <?php the_content(); ?>
        </div>

        <!-- Sticky / Integrated Book Mounting Callout -->
        <div class="pttm-article-book-banner">
            <div class="pttm-book-banner-content">
                <h3>Want us to do the heavy lifting?</h3>
                <p>Get your TV mounted professionally with $1M insurance coverage and same-day availability.</p>
            </div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="pttm-btn-primary-pill">
                <span>Configure Order</span>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
        </div>

    </div>
</main>

<?php
endwhile;
get_footer();
