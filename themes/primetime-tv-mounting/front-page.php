<?php
/**
 * Front Page Template - Prime Time TV Mounting
 * Implements the Apple x Domino's visual order configurator flow
 */
get_header();

$settings = get_option('pttm_settings', pttm_get_default_settings());
$tv_sizes = get_option('pttm_tv_sizes', pttm_get_default_tv_sizes());
$addons   = get_option('pttm_addons', pttm_get_default_addons());
$services = get_option('pttm_services', pttm_get_default_services());
?>

<main id="primary" class="pttm-main-content">
    
    <!-- STAGE 1: Quantity Selection Hero (Screen 4) -->
    <section class="pttm-hero-section is-active" id="pttmStageQuantity">
        <div class="pttm-hero-bg-wrapper">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-bg.jpg'); ?>" alt="Modern Luxury Living Room" class="pttm-hero-bg-img" loading="eager">
            <div class="pttm-hero-overlay"></div>
        </div>

        <div class="pttm-hero-content-container">
            <div class="pttm-hero-header-text">
                <h1 class="pttm-hero-title">How many TVs<br>would you like mounted?</h1>
            </div>

            <!-- TV Quantity Card Selectors -->
            <div class="pttm-qty-cards-grid" role="radiogroup" aria-label="Select TV Quantity">
                <button type="button" class="pttm-qty-card" data-qty="1" role="radio" aria-checked="false">
                    <span class="pttm-qty-card-count">1</span>
                    <span class="pttm-qty-card-label">TV</span>
                    <div class="pttm-qty-card-visual">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/tv-1.svg'); ?>" alt="1 TV" width="120" height="75">
                    </div>
                </button>

                <button type="button" class="pttm-qty-card" data-qty="2" role="radio" aria-checked="false">
                    <span class="pttm-qty-card-count">2</span>
                    <span class="pttm-qty-card-label">TVs</span>
                    <div class="pttm-qty-card-visual">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/tv-2.svg'); ?>" alt="2 TVs" width="120" height="75">
                    </div>
                </button>

                <button type="button" class="pttm-qty-card" data-qty="3" role="radio" aria-checked="false">
                    <span class="pttm-qty-card-count">3</span>
                    <span class="pttm-qty-card-label">TVs</span>
                    <div class="pttm-qty-card-visual">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/tv-3.svg'); ?>" alt="3 TVs" width="120" height="75">
                    </div>
                </button>

                <button type="button" class="pttm-qty-card" data-qty="4" role="radio" aria-checked="false">
                    <span class="pttm-qty-card-count">4+</span>
                    <span class="pttm-qty-card-label">TVs</span>
                    <div class="pttm-qty-card-visual">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/tv-4plus.svg'); ?>" alt="4 or more TVs" width="120" height="75">
                    </div>
                </button>
            </div>

            <!-- Trust Proof Badges (Screen 4 Bottom) -->
            <div class="pttm-hero-trust-bar">
                <div class="pttm-trust-item">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span><?php echo esc_html($settings['badge_insurance'] ?? '$1M LIABILITY INSURANCE'); ?></span>
                </div>
                <div class="pttm-trust-divider"></div>
                <div class="pttm-trust-item">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span><?php echo esc_html($settings['badge_experience'] ?? '10+ YEARS EXPERIENCE'); ?></span>
                </div>
                <div class="pttm-trust-divider"></div>
                <div class="pttm-trust-item">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span><?php echo esc_html($settings['badge_reviews'] ?? 'HUNDREDS OF 5-STAR REVIEWS'); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- STAGE 2: TV Configuration Step-by-Step Flow (Screen 1) -->
    <section class="pttm-wizard-section" id="pttmStageConfig" style="display:none;">
        <div class="pttm-wizard-container">
            
            <div class="pttm-wizard-layout">
                <!-- Left Column: TV Preview & Helper Callout -->
                <div class="pttm-wizard-preview-col">
                    <div class="pttm-preview-header">
                        <h2 class="pttm-preview-title" id="pttmCurrentTvLabel">TV 1 <span class="pttm-text-muted" id="pttmCurrentTvOfTotal">of 3</span></h2>
                    </div>

                    <div class="pttm-preview-image-card">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/tv-preview.jpg'); ?>" alt="Mounted TV Preview" class="pttm-preview-img" id="pttmPreviewImg">
                        <div class="pttm-preview-overlay-tag" id="pttmPreviewTag">Standard Wall Mount</div>
                    </div>

                    <div class="pttm-tip-callout">
                        <div class="pttm-tip-icon">&#128161;</div>
                        <div>
                            <strong>You can always adjust this later</strong>
                            <p>It's easy to make changes before checkout or when we call you to confirm.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Size Selector & Add-on Checkbox Cards -->
                <div class="pttm-wizard-options-col">
                    
                    <!-- 1. What size is your TV? -->
                    <div class="pttm-option-group">
                        <h3 class="pttm-group-title"><span class="pttm-step-num">1.</span> What size is your TV?</h3>
                        <div class="pttm-size-buttons-row" role="radiogroup" aria-label="TV Size Selection" id="pttmSizeButtonsContainer">
                            <?php foreach ($tv_sizes as $sIndex => $size): ?>
                                <button type="button" class="pttm-size-btn <?php echo $sIndex === 1 ? 'is-selected' : ''; ?>" data-size-id="<?php echo esc_attr($size['id']); ?>" data-size-label="<?php echo esc_attr($size['label']); ?>" data-size-price="<?php echo esc_attr($size['base_price'] ?? 0); ?>" role="radio" aria-checked="<?php echo $sIndex === 1 ? 'true' : 'false'; ?>">
                                    <?php echo esc_html($size['label']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- 2. Add-ons (select any that apply) -->
                    <div class="pttm-option-group">
                        <h3 class="pttm-group-title"><span class="pttm-step-num">2.</span> Add-ons <span class="pttm-group-subtitle">(select any that apply)</span></h3>
                        <div class="pttm-addons-grid" id="pttmAddonsGrid">
                            <?php foreach ($addons as $addon): ?>
                                <?php if (empty($addon['enabled'])) continue; ?>
                                <div class="pttm-addon-card" data-addon-id="<?php echo esc_attr($addon['id']); ?>" data-addon-price="<?php echo esc_attr($addon['price']); ?>" data-addon-title="<?php echo esc_attr($addon['title']); ?>" data-addon-subtitle="<?php echo esc_attr($addon['subtitle']); ?>" role="checkbox" aria-checked="false" tabindex="0">
                                    <div class="pttm-card-checkbox">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                    <div class="pttm-addon-thumb">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . $addon['image']); ?>" alt="<?php echo esc_attr($addon['title']); ?>">
                                    </div>
                                    <div class="pttm-addon-info">
                                        <h4 class="pttm-addon-title"><?php echo esc_html($addon['title']); ?></h4>
                                        <?php if (!empty($addon['subtitle'])): ?>
                                            <span class="pttm-addon-subtitle"><?php echo esc_html($addon['subtitle']); ?></span>
                                        <?php endif; ?>
                                        <div class="pttm-addon-price">$<?php echo esc_html($addon['price']); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bottom Progress Stepper and Next Button -->
            <div class="pttm-wizard-footer-bar">
                <button type="button" class="pttm-btn-back-link" id="pttmConfigBackBtn">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Back</span>
                </button>

                <div class="pttm-stepper-dots" id="pttmStepperDots">
                    <!-- Injected dynamically by JS (1 TV 1, 2 TV 2, 3 TV 3) -->
                </div>

                <div class="pttm-footer-actions-group">
                    <div class="pttm-live-price-pill" id="pttmLivePricePill" title="Live Running Total">
                        <span class="pttm-live-price-label">Est. Total:</span>
                        <strong class="pttm-live-price-amount" id="pttmLivePriceVal">$95.00</strong>
                    </div>

                    <button type="button" class="pttm-btn-primary-pill" id="pttmConfigNextBtn">
                        <span id="pttmConfigNextLabel">Next: TV 2</span>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- STAGE 3: Additional Services & Customer Information (Screen 2) -->
    <section class="pttm-services-section" id="pttmStageServices" style="display:none;">
        <div class="pttm-services-container">
            
            <!-- Top Navigation Bar (Screen 2) -->
            <div class="pttm-services-top-bar">
                <button type="button" class="pttm-btn-back-link" id="pttmServicesBackBtn">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Back</span>
                </button>
                <div class="pttm-step-counter-pill" id="pttmServicesStepPill">Step 4 of 4</div>
            </div>

            <!-- Header Title -->
            <div class="pttm-services-heading">
                <h2 class="pttm-services-title">Anything else you need?</h2>
                <p class="pttm-services-subtitle">Add additional services to your order. These services are $75 per hour.</p>
            </div>

            <!-- Additional Services Cards Grid (Screen 2) -->
            <div class="pttm-services-grid" id="pttmServicesGrid">
                <?php foreach ($services as $srv): ?>
                    <?php if (empty($srv['enabled'])) continue; ?>
                    <div class="pttm-service-card" data-service-id="<?php echo esc_attr($srv['id']); ?>" data-service-title="<?php echo esc_attr($srv['title']); ?>" data-service-subtitle="<?php echo esc_attr($srv['subtitle']); ?>" data-service-rate="<?php echo esc_attr($srv['price_rate']); ?>" data-service-price="<?php echo esc_attr($srv['price_num'] ?? 75); ?>" role="checkbox" aria-checked="false" tabindex="0">
                        <div class="pttm-card-checkbox">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </div>
                        <div class="pttm-service-thumb">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . $srv['image']); ?>" alt="<?php echo esc_attr($srv['title']); ?>">
                        </div>
                        <div class="pttm-service-info">
                            <h4 class="pttm-service-title"><?php echo esc_html($srv['title']); ?></h4>
                            <p class="pttm-service-desc"><?php echo esc_html($srv['subtitle']); ?></p>
                            <div class="pttm-service-rate"><?php echo esc_html($srv['price_rate']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Customer Information Form Section (Screen 2 Bottom) -->
            <div class="pttm-customer-form-card">
                <div class="pttm-form-header">
                    <h3 class="pttm-form-title">Your Information</h3>
                    <p class="pttm-form-subtitle">Please enter your details so we can confirm your order.</p>
                </div>

                <form id="pttmOrderIntakeForm" class="pttm-intake-form" novalidate>
                    <div class="pttm-form-row">
                        <!-- Full Name -->
                        <div class="pttm-input-group">
                            <div class="pttm-input-icon">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                            <input type="text" id="pttmCustName" name="customer_name" class="pttm-input-field" placeholder="Full Name" required autocomplete="name">
                        </div>

                        <!-- Phone Number -->
                        <div class="pttm-input-group">
                            <div class="pttm-input-icon">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                            <input type="tel" id="pttmCustPhone" name="customer_phone" class="pttm-input-field" placeholder="Phone Number" required autocomplete="tel">
                        </div>
                    </div>

                    <div class="pttm-form-row">
                        <!-- Email Address -->
                        <div class="pttm-input-group">
                            <div class="pttm-input-icon">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </div>
                            <input type="email" id="pttmCustEmail" name="customer_email" class="pttm-input-field" placeholder="Email Address" autocomplete="email">
                        </div>

                        <!-- Installation Address with Google Autocomplete -->
                        <div class="pttm-input-group">
                            <div class="pttm-input-icon">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <input type="text" id="pttmCustAddress" name="customer_address" class="pttm-input-field" placeholder="Installation Address / City" autocomplete="street-address">
                        </div>
                    </div>

                    <!-- Live Calculated Breakdown Card -->
                    <div class="pttm-live-price-ledger-card" id="pttmStep4Ledger">
                        <div class="pttm-ledger-header">
                            <h4>Estimated Order Breakdown</h4>
                            <span class="pttm-ledger-badge" id="pttmStep4ItemsBadge">1 TV</span>
                        </div>
                        <div class="pttm-ledger-row">
                            <span>TV Mounting & Add-ons:</span>
                            <strong id="pttmStep4TvSubtotal">$95.00</strong>
                        </div>
                        <div class="pttm-ledger-row">
                            <span>Additional Hourly Services:</span>
                            <strong id="pttmStep4SrvSubtotal">$0.00</strong>
                        </div>
                        <div class="pttm-ledger-row pttm-ledger-total">
                            <span>Total Estimated Investment:</span>
                            <strong id="pttmStep4GrandTotal">$95.00</strong>
                        </div>
                    </div>

                    <!-- Finish Order CTA Button (Screen 2) -->
                    <div class="pttm-form-submit-row">
                        <button type="submit" class="pttm-btn-primary-pill pttm-btn-finish" id="pttmFinishOrderBtn">
                            <span>Finish Order</span>
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>

                    <!-- Call Reassurance Callout (Screen 2 Bottom) -->
                    <div class="pttm-call-guarantee-banner">
                        <div class="pttm-call-banner-icon">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 00-1.01.24l-1.57 1.97c-2.83-1.44-5.15-3.75-6.59-6.59l1.97-1.57c.28-.28.36-.67.25-1.02A11.36 11.36 0 018.57 3.99c.07-.55-.38-1-1-1H4.01c-.55 0-1 .45-1 1C3.01 13.44 10.56 21 19.99 21c.55 0 1-.45 1-1v-3.62c0-.55-.45-1-.98-1z"/></svg>
                        </div>
                        <div>
                            <strong>We'll call you within minutes!</strong>
                            <p>A member of our team will review your order and give you a quick call to confirm the details.</p>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>

    <!-- STAGE 4: Order Received Confirmation (Screen 3) -->
    <section class="pttm-confirmation-section" id="pttmStageConfirmation" style="display:none;">
        <div class="pttm-confirmation-container">
            
            <!-- Checkmark Header (Screen 3) -->
            <div class="pttm-confirmation-hero">
                <div class="pttm-checkmark-circle">
                    <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h1 class="pttm-confirm-title">Order Received</h1>
                <p class="pttm-confirm-lead">We've got your request.</p>
                <p class="pttm-confirm-subtext">A member of our team will call you within minutes to confirm the details, answer any questions, and find a time that works for you.</p>
            </div>

            <!-- "Your Order" Summary Card (Screen 3) -->
            <div class="pttm-order-summary-card">
                <div class="pttm-summary-header">
                    <h2>Your Order</h2>
                    <span class="pttm-summary-tally" id="pttmConfirmTally">3 TVs &bull; 3 items total</span>
                </div>

                <!-- TV by TV Itemized List -->
                <div class="pttm-summary-list" id="pttmConfirmItemsList">
                    <!-- Populated dynamically via JS matching Screen 3 -->
                </div>
            </div>

            <!-- "What happens next?" 4-Step Roadmap (Screen 3) -->
            <div class="pttm-next-steps-section">
                <h3 class="pttm-next-steps-title">What happens next?</h3>
                
                <div class="pttm-next-steps-grid">
                    <!-- Step 1 -->
                    <div class="pttm-next-step-item">
                        <div class="pttm-step-icon-circle">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <h4>1. Phone Call</h4>
                        <p>We'll call you within minutes.</p>
                    </div>

                    <div class="pttm-step-arrow">&#8250;</div>

                    <!-- Step 2 -->
                    <div class="pttm-next-step-item">
                        <div class="pttm-step-icon-circle">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        </div>
                        <h4>2. Confirm Details</h4>
                        <p>We'll review your order and answer any questions.</p>
                    </div>

                    <div class="pttm-step-arrow">&#8250;</div>

                    <!-- Step 3 -->
                    <div class="pttm-next-step-item">
                        <div class="pttm-step-icon-circle">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <h4>3. Schedule</h4>
                        <p>We'll find a date and time that works for you.</p>
                    </div>

                    <div class="pttm-step-arrow">&#8250;</div>

                    <!-- Step 4 -->
                    <div class="pttm-next-step-item">
                        <div class="pttm-step-icon-circle">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        </div>
                        <h4>4. We Get It Done</h4>
                        <p>Sit back and relax. We'll handle the rest.</p>
                    </div>
                </div>
            </div>

            <!-- Trust Badges Bar (Screen 3) -->
            <div class="pttm-confirm-trust-bar">
                <div class="pttm-trust-item">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span><?php echo esc_html($settings['badge_insurance'] ?? '$1M Insured'); ?></span>
                </div>
                <div class="pttm-trust-item">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    <span><?php echo esc_html($settings['badge_experience'] ?? '10+ Years Experience'); ?></span>
                </div>
                <div class="pttm-trust-item">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span><?php echo esc_html($settings['badge_reviews'] ?? 'Hundreds of 5-Star Reviews'); ?></span>
                </div>
            </div>

            <!-- Back to Home Action Button -->
            <div class="pttm-confirm-actions">
                <button type="button" class="pttm-btn-primary-pill" id="pttmConfirmHomeBtn">
                    <span>Back to Home</span>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
                <p class="pttm-confirm-footer-note">Questions? We'll be in touch shortly!</p>
            </div>

        </div>
    </section>

</main>

<?php
get_footer();
