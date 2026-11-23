<?php
/**
 * Footer Template for Prime Time TV Mounting
 */
if (!defined('ABSPATH')) {
    exit;
}
$settings = get_option('pttm_settings', pttm_get_default_settings());
?>

<footer class="pttm-site-footer">
    <div class="pttm-footer-container">
        <div class="pttm-footer-grid">
            <div class="pttm-footer-col">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.svg'); ?>" alt="Prime Time TV Mounting" width="160" height="52" class="pttm-footer-logo">
                <p class="pttm-footer-tagline">White-glove residential and commercial TV mounting, soundbar installation, and wire concealment specialists.</p>
                <div class="pttm-footer-trust-pills">
                    <span><?php echo esc_html($settings['badge_insurance'] ?? '$1M Liability Insurance'); ?></span>
                    <span><?php echo esc_html($settings['badge_experience'] ?? '10+ Years Experience'); ?></span>
                </div>
            </div>

            <div class="pttm-footer-col">
                <h4>Quick Links</h4>
                <ul class="pttm-footer-links">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Order Configurator</a></li>
                    <li><button type="button" class="pttm-footer-btn-link" data-modal="history">Business History</button></li>
                    <li><button type="button" class="pttm-footer-btn-link" data-modal="faq">Frequently Asked Questions</button></li>
                    <li><button type="button" class="pttm-footer-btn-link" data-modal="reviews">Customer Reviews</button></li>
                    <li><a href="<?php echo esc_url(home_url('/tips-tricks/')); ?>">Tips & Tricks (YouTube Hub)</a></li>
                </ul>
            </div>

            <div class="pttm-footer-col">
                <h4>Contact & Coverage</h4>
                <p><strong>Phone:</strong> <a href="tel:<?php echo esc_attr($settings['company_phone'] ?? ''); ?>"><?php echo esc_html($settings['company_phone'] ?? '(800) 555-0199'); ?></a></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($settings['company_email'] ?? ''); ?>"><?php echo esc_html($settings['company_email'] ?? 'service@primetimetvmounting.com'); ?></a></p>
                <p><strong>Hours:</strong> <?php echo esc_html($settings['company_hours'] ?? 'Mon - Sun: 7:00 AM - 9:00 PM'); ?></p>
                <p class="pttm-footer-subnote">Orders are confirmed by phone within minutes of submission.</p>
            </div>
        </div>

        <div class="pttm-footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html($settings['company_name'] ?? 'Prime Time TV Mounting'); ?>. All Rights Reserved.</p>
            <p class="pttm-footer-disclaimer">Order requests are non-binding estimates. Scope and final pricing are confirmed prior to commencing work.</p>
        </div>
    </div>
</footer>

<!-- Non-Disruptive Popup Modals / Drawers Container -->
<div class="pttm-modal-overlay" id="pttmModalOverlay" aria-hidden="true">
    <div class="pttm-modal-wrapper" role="dialog" aria-modal="true">
        <!-- Close Button -->
        <button type="button" class="pttm-modal-close" id="pttmModalClose" aria-label="Close dialog">&times;</button>
        
        <!-- History Modal Content -->
        <div class="pttm-modal-pane" id="modal-pane-history">
            <div class="pttm-modal-header">
                <h2>Our Business History</h2>
                <p class="pttm-modal-subtitle">Decade-long legacy of meticulous craftsmanship and customer care</p>
            </div>
            <div class="pttm-modal-body pttm-prose">
                <?php echo wpautop(esc_html($settings['business_history'] ?? '')); ?>
                <div class="pttm-modal-badge-row">
                    <div class="pttm-modal-badge">
                        <strong>10,000+</strong>
                        <span>TVs Mounted</span>
                    </div>
                    <div class="pttm-modal-badge">
                        <strong>100%</strong>
                        <span>Insured & Bonded</span>
                    </div>
                    <div class="pttm-modal-badge">
                        <strong>5.0 &#9733;</strong>
                        <span>Customer Rating</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Modal Content -->
        <div class="pttm-modal-pane" id="modal-pane-faq">
            <div class="pttm-modal-header">
                <h2>Frequently Asked Questions</h2>
                <p class="pttm-modal-subtitle">Everything you need to know about our TV mounting service</p>
            </div>
            <div class="pttm-modal-body">
                <div class="pttm-faq-accordion">
                    <?php if (!empty($settings['faqs']) && is_array($settings['faqs'])): ?>
                        <?php foreach ($settings['faqs'] as $i => $faq): ?>
                            <div class="pttm-faq-accordion-item <?php echo $i === 0 ? 'is-open' : ''; ?>">
                                <button type="button" class="pttm-faq-question">
                                    <span><?php echo esc_html($faq['question']); ?></span>
                                    <span class="pttm-faq-chevron">&#9662;</span>
                                </button>
                                <div class="pttm-faq-answer">
                                    <p><?php echo nl2br(esc_html($faq['answer'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Reviews Modal Content -->
        <div class="pttm-modal-pane" id="modal-pane-reviews">
            <div class="pttm-modal-header">
                <h2>Customer Reviews</h2>
                <p class="pttm-modal-subtitle">Verified feedback from real homeowners and business clients</p>
            </div>
            <div class="pttm-modal-body">
                <div class="pttm-reviews-grid">
                    <?php if (!empty($settings['reviews']) && is_array($settings['reviews'])): ?>
                        <?php foreach ($settings['reviews'] as $rev): ?>
                            <div class="pttm-review-card">
                                <div class="pttm-review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                                <p class="pttm-review-text">&ldquo;<?php echo esc_html($rev['text']); ?>&rdquo;</p>
                                <div class="pttm-review-meta">
                                    <strong><?php echo esc_html($rev['author']); ?></strong>
                                    <span><?php echo esc_html($rev['location']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Contact Modal Content -->
        <div class="pttm-modal-pane" id="modal-pane-contact">
            <div class="pttm-modal-header">
                <h2>Direct Contact & Dispatch</h2>
                <p class="pttm-modal-subtitle">Need immediate assistance or have questions before ordering?</p>
            </div>
            <div class="pttm-modal-body">
                <div class="pttm-contact-quick-cards">
                    <a href="tel:<?php echo esc_attr($settings['company_phone'] ?? ''); ?>" class="pttm-contact-card">
                        <div class="pttm-contact-icon">&#128222;</div>
                        <div>
                            <strong>Call Us Directly</strong>
                            <p><?php echo esc_html($settings['company_phone'] ?? '(800) 555-0199'); ?></p>
                            <small>Available 7 days a week</small>
                        </div>
                    </a>
                    <a href="mailto:<?php echo esc_attr($settings['company_email'] ?? ''); ?>" class="pttm-contact-card">
                        <div class="pttm-contact-icon">&#9993;</div>
                        <div>
                            <strong>Email Support</strong>
                            <p><?php echo esc_html($settings['company_email'] ?? 'service@primetimetvmounting.com'); ?></p>
                            <small>Replies within 15 minutes</small>
                        </div>
                    </a>
                </div>
                <div class="pttm-contact-note">
                    <p>💡 <em>Tip: The fastest way to schedule service is by selecting your TVs and finishing the order request on the homepage. Our dispatch team will call you within minutes!</em></p>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Pane -->
        <div class="pttm-modal-pane" id="modal-pane-mobile-menu">
            <div class="pttm-modal-header">
                <h2>Navigation</h2>
            </div>
            <div class="pttm-modal-body">
                <div class="pttm-mobile-nav-stack">
                    <button type="button" class="pttm-mobile-nav-btn" data-modal="history">Business History</button>
                    <button type="button" class="pttm-mobile-nav-btn" data-modal="faq">FAQ</button>
                    <button type="button" class="pttm-mobile-nav-btn" data-modal="reviews">Read Reviews</button>
                    <a href="<?php echo esc_url(home_url('/tips-tricks/')); ?>" class="pttm-mobile-nav-btn">Tips & Tricks (YouTube Blog)</a>
                    <button type="button" class="pttm-mobile-nav-btn pttm-mobile-btn-highlight" data-modal="contact">Contact Now</button>
                </div>
            </div>
        </div>

    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
