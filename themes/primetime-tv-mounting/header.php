<?php
/**
 * Header Template for Prime Time TV Mounting
 */
if (!defined('ABSPATH')) {
    exit;
}
$settings = get_option('pttm_settings', pttm_get_default_settings());
$phone = $settings['company_phone'] ?? '(800) 555-0199';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="pttm-site-header" id="site-header">
    <div class="pttm-header-container">
        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="pttm-brand-logo" aria-label="Prime Time TV Mounting Home">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.svg'); ?>" alt="Prime Time TV Mounting" width="180" height="58">
        </a>

        <!-- Desktop Navigation -->
        <nav class="pttm-desktop-nav" aria-label="Primary Navigation">
            <ul class="pttm-nav-list">
                <li>
                    <button type="button" class="pttm-nav-btn" data-modal="history">
                        <svg class="pttm-nav-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span>Business History</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="pttm-nav-btn" data-modal="faq">
                        <svg class="pttm-nav-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>FAQ</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="pttm-nav-btn" data-modal="reviews">
                        <svg class="pttm-nav-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        <span>Read Reviews</span>
                    </button>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/tips-tricks/')); ?>" class="pttm-nav-btn pttm-nav-link">
                        <svg class="pttm-nav-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                        <span>Tips & Tricks</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Header CTA Actions -->
        <div class="pttm-header-actions">
            <button type="button" class="pttm-btn-contact-pill" data-modal="contact">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.44-5.15-3.75-6.59-6.59l1.97-1.57c.28-.28.36-.67.25-1.02A11.36 11.36 0 018.57 3.99c.07-.55-.38-1-1-1H4.01c-.55 0-1 .45-1 1C3.01 13.44 10.56 21 19.99 21c.55 0 1-.45 1-1v-3.62c0-.55-.45-1-.98-1z"/></svg>
                <span>Contact Now</span>
            </button>
            <button type="button" class="pttm-mobile-menu-toggle" id="pttmMobileToggle" aria-label="Open Navigation Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>
