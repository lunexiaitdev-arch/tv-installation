<?php
/**
 * Prime Time TV Mounting - Executive SaaS Admin Control Center
 * Full dynamic management of Add-ons, Services, TV sizes, Images, Content, Gateways, FAQs, and Analytics.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Admin Menu Pages
 */
function pttm_register_admin_menu() {
    add_menu_page(
        'PTTM Executive Dashboard',
        'PTTM Platform',
        'manage_options',
        'pttm-settings',
        'pttm_render_settings_page',
        'dashicons-screenoptions',
        25
    );

    add_submenu_page(
        'pttm-settings',
        'Dashboard & Catalog',
        'Dashboard & Catalog',
        'manage_options',
        'pttm-settings',
        'pttm_render_settings_page'
    );
}
add_action('admin_menu', 'pttm_register_admin_menu');

/**
 * Enqueue Admin Assets
 */
function pttm_enqueue_admin_assets($hook) {
    if (strpos($hook, 'pttm') === false && strpos($hook, 'pttm_order') === false) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_style('pttm-admin-css', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.3.0');
    wp_enqueue_script('pttm-admin-js', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), '1.3.0', true);

    wp_localize_script('pttm-admin-js', 'pttmAdminData', array(
        'defaultImg' => get_template_directory_uri() . '/assets/images/addon-fixed-mount.svg'
    ));
}
add_action('admin_enqueue_scripts', 'pttm_enqueue_admin_assets');

/**
 * Handle Settings Form Submissions
 */
function pttm_handle_settings_save() {
    if (!isset($_POST['pttm_save_settings_nonce']) || !wp_verify_nonce($_POST['pttm_save_settings_nonce'], 'pttm_save_settings_action')) {
        return;
    }
    if (!current_user_can('manage_options')) {
        return;
    }

    $tab = isset($_POST['active_tab']) ? sanitize_text_field($_POST['active_tab']) : 'overview';

    // 1. Save Add-ons (support empty array if all deleted)
    if (isset($_POST['pttm_addons']) && is_array($_POST['pttm_addons'])) {
        $addons = array();
        foreach ($_POST['pttm_addons'] as $item) {
            if (empty($item['title'])) continue;
            $addons[] = array(
                'id' => sanitize_title($item['title']),
                'title' => sanitize_text_field($item['title']),
                'subtitle' => sanitize_text_field($item['subtitle']),
                'price' => floatval($item['price']),
                'image' => sanitize_text_field($item['image']),
                'description' => sanitize_textarea_field($item['description'] ?? ''),
                'enabled' => isset($item['enabled']) ? 1 : 0
            );
        }
        update_option('pttm_addons', $addons);
    }

    // 2. Save Additional Services
    if (isset($_POST['pttm_services']) && is_array($_POST['pttm_services'])) {
        $services = array();
        foreach ($_POST['pttm_services'] as $item) {
            if (empty($item['title'])) continue;
            $services[] = array(
                'id' => sanitize_title($item['title']),
                'title' => sanitize_text_field($item['title']),
                'subtitle' => sanitize_text_field($item['subtitle']),
                'price_rate' => sanitize_text_field($item['price_rate']),
                'price_num' => floatval($item['price_num'] ?? 75),
                'image' => sanitize_text_field($item['image']),
                'enabled' => isset($item['enabled']) ? 1 : 0
            );
        }
        update_option('pttm_services', $services);
    }

    // 3. Save TV Sizes
    if (isset($_POST['pttm_tv_sizes']) && is_array($_POST['pttm_tv_sizes'])) {
        $sizes = array();
        foreach ($_POST['pttm_tv_sizes'] as $item) {
            if (empty($item['label'])) continue;
            $sizes[] = array(
                'id' => sanitize_title($item['label']),
                'label' => sanitize_text_field($item['label']),
                'base_price' => floatval($item['base_price'] ?? 95)
            );
        }
        update_option('pttm_tv_sizes', $sizes);
    }

    // 4. Save General Settings
    if (isset($_POST['pttm_settings']) && is_array($_POST['pttm_settings'])) {
        $curr = get_option('pttm_settings', pttm_get_default_settings());
        $new_settings = array_merge($curr, $_POST['pttm_settings']);

        if (isset($_POST['pttm_faqs']) && is_array($_POST['pttm_faqs'])) {
            $new_settings['faqs'] = array_values($_POST['pttm_faqs']);
        }
        if (isset($_POST['pttm_reviews']) && is_array($_POST['pttm_reviews'])) {
            $new_settings['reviews'] = array_values($_POST['pttm_reviews']);
        }

        update_option('pttm_settings', $new_settings);
    }

    // 5. Save Payment Gateways Settings
    if (isset($_POST['pttm_payment_settings']) && is_array($_POST['pttm_payment_settings'])) {
        $pay_data = $_POST['pttm_payment_settings'];
        $clean_pay = array(
            'payment_mode'          => sanitize_text_field($pay_data['payment_mode'] ?? 'intake_only'),
            'deposit_type'          => sanitize_text_field($pay_data['deposit_type'] ?? 'fixed'),
            'deposit_amount'        => floatval($pay_data['deposit_amount'] ?? 50),
            'currency'              => sanitize_text_field($pay_data['currency'] ?? 'USD'),
            'stripe_enabled'        => isset($pay_data['stripe_enabled']) ? 1 : 0,
            'stripe_mode'           => sanitize_text_field($pay_data['stripe_mode'] ?? 'test'),
            'stripe_publishable_key'=> sanitize_text_field($pay_data['stripe_publishable_key'] ?? ''),
            'stripe_secret_key'     => sanitize_text_field($pay_data['stripe_secret_key'] ?? ''),
            'paypal_enabled'        => isset($pay_data['paypal_enabled']) ? 1 : 0,
            'paypal_mode'           => sanitize_text_field($pay_data['paypal_mode'] ?? 'sandbox'),
            'paypal_client_id'      => sanitize_text_field($pay_data['paypal_client_id'] ?? ''),
            'paypal_secret'         => sanitize_text_field($pay_data['paypal_secret'] ?? ''),
            'square_enabled'        => isset($pay_data['square_enabled']) ? 1 : 0,
            'square_environment'    => sanitize_text_field($pay_data['square_environment'] ?? 'sandbox'),
            'square_app_id'         => sanitize_text_field($pay_data['square_app_id'] ?? ''),
            'square_access_token'   => sanitize_text_field($pay_data['square_access_token'] ?? ''),
            'square_location_id'    => sanitize_text_field($pay_data['square_location_id'] ?? '')
        );
        update_option('pttm_payment_settings', $clean_pay);
    }

    add_settings_error('pttm_messages', 'pttm_message', __('All changes saved successfully.', 'primetime-tv-mounting'), 'updated');
}
add_action('admin_init', 'pttm_handle_settings_save');

/**
 * Render SaaS-Level Admin Dashboard
 */
function pttm_render_settings_page() {
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';

    $settings  = get_option('pttm_settings', pttm_get_default_settings());
    $addons    = get_option('pttm_addons', pttm_get_default_addons());
    $services  = get_option('pttm_services', pttm_get_default_services());
    $tv_sizes  = get_option('pttm_tv_sizes', pttm_get_default_tv_sizes());
    $payments  = pttm_get_payment_settings();

    // Calculate Live SaaS Stats
    $orders = get_posts(array('post_type' => 'pttm_order', 'post_status' => 'any', 'numberposts' => -1));
    $total_orders = count($orders);
    $new_leads = 0;
    $completed_orders = 0;
    $pipeline_revenue = 0.0;

    foreach ($orders as $ord) {
        $status = get_post_meta($ord->ID, '_pttm_order_status', true) ?: 'new';
        $est = floatval(get_post_meta($ord->ID, '_pttm_estimated_total', true) ?: 0);
        $pipeline_revenue += $est;
        if ($status === 'new') $new_leads++;
        if ($status === 'completed') $completed_orders++;
    }
    ?>
    <div class="wrap pttm-admin-wrap">
        
        <!-- SaaS Executive Header -->
        <div class="pttm-saas-header">
            <div class="pttm-saas-header-main">
                <div class="pttm-saas-brand-mark">
                    <span class="dashicons dashicons-video-alt3"></span>
                </div>
                <div>
                    <h1 class="pttm-saas-title">Prime Time TV Mounting &bull; Control Center</h1>
                    <p class="pttm-saas-desc">Manage catalog items, pricing, payment gateways, and order intakes seamlessly.</p>
                </div>
            </div>
            <div class="pttm-saas-header-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" class="button button-secondary pttm-saas-btn">
                    <span class="dashicons dashicons-external"></span> View Live Website
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=pttm_order'); ?>" class="button button-primary pttm-saas-btn-gold">
                    <span class="dashicons dashicons-clipboard"></span> Manage Orders (<?php echo $total_orders; ?>)
                </a>
            </div>
        </div>

        <!-- SaaS Key Metrics Grid -->
        <div class="pttm-metrics-grid">
            <div class="pttm-metric-card">
                <div class="pttm-metric-icon pttm-icon-orders"><span class="dashicons dashicons-cart"></span></div>
                <div class="pttm-metric-info">
                    <span class="pttm-metric-label">Total Intakes</span>
                    <strong class="pttm-metric-value"><?php echo $total_orders; ?></strong>
                </div>
            </div>
            <div class="pttm-metric-card">
                <div class="pttm-metric-icon pttm-icon-leads"><span class="dashicons dashicons-bell"></span></div>
                <div class="pttm-metric-info">
                    <span class="pttm-metric-label">New Leads to Call</span>
                    <strong class="pttm-metric-value" style="color:#d97706;"><?php echo $new_leads; ?></strong>
                </div>
            </div>
            <div class="pttm-metric-card">
                <div class="pttm-metric-icon pttm-icon-revenue"><span class="dashicons dashicons-chart-line"></span></div>
                <div class="pttm-metric-info">
                    <span class="pttm-metric-label">Pipeline Value</span>
                    <strong class="pttm-metric-value" style="color:#059669;">$<?php echo number_format($pipeline_revenue, 2); ?></strong>
                </div>
            </div>
            <div class="pttm-metric-card">
                <div class="pttm-metric-icon pttm-icon-completed"><span class="dashicons dashicons-yes-alt"></span></div>
                <div class="pttm-metric-info">
                    <span class="pttm-metric-label">Completed Jobs</span>
                    <strong class="pttm-metric-value"><?php echo $completed_orders; ?></strong>
                </div>
            </div>
        </div>

        <?php settings_errors('pttm_messages'); ?>

        <!-- SaaS Tabs Navigation -->
        <nav class="nav-tab-wrapper pttm-nav-tabs">
            <a href="?page=pttm-settings&tab=overview" class="nav-tab <?php echo $active_tab === 'overview' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-dashboard"></span> Overview & Shortcodes
            </a>
            <a href="?page=pttm-settings&tab=addons" class="nav-tab <?php echo $active_tab === 'addons' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-plus-alt2"></span> TV Mounts & Add-ons (<?php echo count($addons); ?>)
            </a>
            <a href="?page=pttm-settings&tab=services" class="nav-tab <?php echo $active_tab === 'services' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-hammer"></span> Hourly Services ($75/hr) (<?php echo count($services); ?>)
            </a>
            <a href="?page=pttm-settings&tab=sizes" class="nav-tab <?php echo $active_tab === 'sizes' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-desktop"></span> TV Sizes & Base Pricing
            </a>
            <a href="?page=pttm-settings&tab=payments" class="nav-tab <?php echo $active_tab === 'payments' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-money-alt"></span> Payment Gateways (Stripe/PayPal/Square)
            </a>
            <a href="?page=pttm-settings&tab=content" class="nav-tab <?php echo $active_tab === 'content' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-edit"></span> Content & Imagery
            </a>
            <a href="?page=pttm-settings&tab=faqs_reviews" class="nav-tab <?php echo $active_tab === 'faqs_reviews' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-testimonial"></span> FAQs & Customer Reviews
            </a>
        </nav>

        <form method="post" action="" enctype="multipart/form-data" class="pttm-admin-form">
            <?php wp_nonce_field('pttm_save_settings_action', 'pttm_save_settings_nonce'); ?>
            <input type="hidden" name="active_tab" value="<?php echo esc_attr($active_tab); ?>">

            <!-- TAB 1: OVERVIEW & SHORTCODES -->
            <?php if ($active_tab === 'overview'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro">
                        <h2>System Overview & Page Builder Modules</h2>
                        <p>All pages and components can be edited visually or embedded anywhere via Elementor Widgets and Gutenberg Shortcodes.</p>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px;">
                        <div class="pttm-card-box">
                            <h3>🚀 Interactive TV Configurator</h3>
                            <p>Embed the complete 4-step ordering flow into any page or post.</p>
                            <code>[pttm_order_wizard]</code>
                        </div>

                        <div class="pttm-card-box">
                            <h3>🎬 Tips & Tricks Video Hub</h3>
                            <p>Display your YouTube tutorials grid with interactive filter tags.</p>
                            <code>[pttm_tips_hub count="6"]</code>
                        </div>

                        <div class="pttm-card-box">
                            <h3>❓ FAQ Accordion</h3>
                            <p>Display your frequently asked questions accordion.</p>
                            <code>[pttm_faq_accordion]</code>
                        </div>

                        <div class="pttm-card-box">
                            <h3>⭐ Verified 5-Star Reviews</h3>
                            <p>Display verified customer testimonial cards.</p>
                            <code>[pttm_reviews_grid]</code>
                        </div>

                        <div class="pttm-card-box">
                            <h3>🛠️ $75/hr Additional Handyman Services</h3>
                            <p>Display the hourly services catalog grid.</p>
                            <code>[pttm_services_grid]</code>
                        </div>

                        <div class="pttm-card-box">
                            <h3>🏆 Business History & Insurance</h3>
                            <p>Display company story and insurance certifications.</p>
                            <code>[pttm_business_history]</code>
                        </div>
                    </div>
                </div>

            <!-- TAB 2: TV MOUNTS & ADD-ONS -->
            <?php elseif ($active_tab === 'addons'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro" style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h2>TV Mounts & Hardware Add-ons</h2>
                            <p>Manage hardware items, custom pricing, and visual artwork displayed in Step 1 of the TV Configurator.</p>
                        </div>
                        <button type="button" class="button button-primary pttm-saas-btn-gold" id="pttmAddAddonBtn">
                            <span class="dashicons dashicons-plus-alt"></span> Add New Add-on
                        </button>
                    </div>

                    <div class="pttm-cards-list" id="pttmAddonsList">
                        <?php foreach ($addons as $i => $item): 
                            $img_src = (strpos($item['image'], 'http') === 0) ? $item['image'] : get_template_directory_uri() . '/assets/images/' . $item['image'];
                        ?>
                            <div class="pttm-item-card">
                                <div class="pttm-card-thumb">
                                    <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($item['title']); ?>" id="pttm_addon_preview_<?php echo $i; ?>">
                                </div>
                                <div class="pttm-card-fields" style="flex:1; display:grid; grid-template-columns: 2fr 2fr 1fr 2fr; gap:12px;">
                                    <div>
                                        <label><strong>Title:</strong></label>
                                        <input type="text" name="pttm_addons[<?php echo $i; ?>][title]" value="<?php echo esc_attr($item['title']); ?>" class="widefat" required>
                                    </div>
                                    <div>
                                        <label><strong>Subtitle / Source:</strong></label>
                                        <input type="text" name="pttm_addons[<?php echo $i; ?>][subtitle]" value="<?php echo esc_attr($item['subtitle']); ?>" class="widefat">
                                    </div>
                                    <div>
                                        <label><strong>Price ($):</strong></label>
                                        <input type="number" step="0.01" name="pttm_addons[<?php echo $i; ?>][price]" value="<?php echo esc_attr($item['price']); ?>" class="widefat" required>
                                    </div>
                                    <div>
                                        <label><strong>Image / Icon URL:</strong></label>
                                        <div style="display:flex; gap:6px;">
                                            <input type="text" name="pttm_addons[<?php echo $i; ?>][image]" value="<?php echo esc_attr($item['image']); ?>" class="widefat pttm-img-input" id="pttm_addon_img_<?php echo $i; ?>">
                                            <button type="button" class="button pttm-media-upload-btn" data-target="pttm_addon_img_<?php echo $i; ?>" data-preview="pttm_addon_preview_<?php echo $i; ?>">Upload</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="pttm-card-toggle" style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                                    <label>
                                        <input type="checkbox" name="pttm_addons[<?php echo $i; ?>][enabled]" value="1" <?php checked(!empty($item['enabled'])); ?>>
                                        Active
                                    </label>
                                    <button type="button" class="button button-link-delete pttm-remove-item-btn" title="Delete this add-on"><span class="dashicons dashicons-trash"></span> Remove</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <!-- TAB 3: HOURLY SERVICES -->
            <?php elseif ($active_tab === 'services'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro" style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h2>Additional Handyman & Mounting Services ($75/hr)</h2>
                            <p>Manage secondary services shown on Step 4 of the wizard (art mounting, furniture assembly, ceiling fans, moving pickup, etc.).</p>
                        </div>
                        <button type="button" class="button button-primary pttm-saas-btn-gold" id="pttmAddServiceBtn">
                            <span class="dashicons dashicons-plus-alt"></span> Add New Service
                        </button>
                    </div>

                    <div class="pttm-cards-list" id="pttmServicesList">
                        <?php foreach ($services as $i => $item): 
                            $img_src = (strpos($item['image'], 'http') === 0) ? $item['image'] : get_template_directory_uri() . '/assets/images/' . $item['image'];
                        ?>
                            <div class="pttm-item-card">
                                <div class="pttm-card-thumb">
                                    <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($item['title']); ?>" id="pttm_srv_preview_<?php echo $i; ?>">
                                </div>
                                <div class="pttm-card-fields" style="flex:1; display:grid; grid-template-columns: 2fr 3fr 1fr 2fr; gap:12px;">
                                    <div>
                                        <label><strong>Service Name:</strong></label>
                                        <input type="text" name="pttm_services[<?php echo $i; ?>][title]" value="<?php echo esc_attr($item['title']); ?>" class="widefat" required>
                                    </div>
                                    <div>
                                        <label><strong>Description / Scope:</strong></label>
                                        <input type="text" name="pttm_services[<?php echo $i; ?>][subtitle]" value="<?php echo esc_attr($item['subtitle']); ?>" class="widefat">
                                    </div>
                                    <div>
                                        <label><strong>Hourly Rate Text:</strong></label>
                                        <input type="text" name="pttm_services[<?php echo $i; ?>][price_rate]" value="<?php echo esc_attr($item['price_rate']); ?>" class="widefat">
                                    </div>
                                    <div>
                                        <label><strong>Image / Icon URL:</strong></label>
                                        <div style="display:flex; gap:6px;">
                                            <input type="text" name="pttm_services[<?php echo $i; ?>][image]" value="<?php echo esc_attr($item['image']); ?>" class="widefat pttm-img-input" id="pttm_srv_img_<?php echo $i; ?>">
                                            <button type="button" class="button pttm-media-upload-btn" data-target="pttm_srv_img_<?php echo $i; ?>" data-preview="pttm_srv_preview_<?php echo $i; ?>">Upload</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="pttm-card-toggle" style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                                    <label>
                                        <input type="checkbox" name="pttm_services[<?php echo $i; ?>][enabled]" value="1" <?php checked(!empty($item['enabled'])); ?>>
                                        Active
                                    </label>
                                    <button type="button" class="button button-link-delete pttm-remove-item-btn" title="Delete this service"><span class="dashicons dashicons-trash"></span> Remove</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <!-- TAB 4: TV SIZES & BASE PRICING -->
            <?php elseif ($active_tab === 'sizes'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro" style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h2>TV Screen Sizes & Base Installation Pricing</h2>
                            <p>Configure screen size brackets and their base installation rates.</p>
                        </div>
                        <button type="button" class="button button-primary pttm-saas-btn-gold" id="pttmAddSizeBtn">
                            <span class="dashicons dashicons-plus-alt"></span> Add Size Bracket
                        </button>
                    </div>

                    <table class="widefat striped" style="max-width:760px;">
                        <thead>
                            <tr>
                                <th>Size Range Bracket</th>
                                <th>Base Installation Price ($)</th>
                                <th style="width:60px; text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="pttmSizesTableBody">
                            <?php foreach ($tv_sizes as $i => $sz): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="pttm_tv_sizes[<?php echo $i; ?>][label]" value="<?php echo esc_attr($sz['label']); ?>" class="widefat" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="pttm_tv_sizes[<?php echo $i; ?>][base_price]" value="<?php echo esc_attr($sz['base_price'] ?? 95); ?>" class="widefat" required>
                                    </td>
                                    <td style="text-align:center;">
                                        <button type="button" class="button button-link-delete pttm-remove-item-btn"><span class="dashicons dashicons-trash"></span></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <!-- TAB 5: PAYMENT GATEWAYS & DEPOSITS -->
            <?php elseif ($active_tab === 'payments'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro">
                        <h2>Payment Gateway Configuration</h2>
                        <p>Configure online deposit or full payment gateways via Stripe, PayPal, or Square. By default, the system runs in <strong>Lead Intake Mode (Pay upon completion)</strong>.</p>
                    </div>

                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:24px;">
                        <h3 style="margin-top:0;">Payment Flow Strategy</h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label>Checkout Mode</label></th>
                                <td>
                                    <select name="pttm_payment_settings[payment_mode]" style="font-size:14px; font-weight:600; padding:4px 10px;">
                                        <option value="intake_only" <?php selected($payments['payment_mode'], 'intake_only'); ?>>Lead Intake Only (Confirm & Pay On Completion)</option>
                                        <option value="deposit" <?php selected($payments['payment_mode'], 'deposit'); ?>>Require Online Deposit to Book</option>
                                        <option value="full_payment" <?php selected($payments['payment_mode'], 'full_payment'); ?>>Require 100% Full Payment Upfront</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label>Deposit Calculation</label></th>
                                <td>
                                    <select name="pttm_payment_settings[deposit_type]">
                                        <option value="fixed" <?php selected($payments['deposit_type'], 'fixed'); ?>>Fixed Dollar Amount ($)</option>
                                        <option value="percentage" <?php selected($payments['deposit_type'], 'percentage'); ?>>Percentage of Order Total (%)</option>
                                    </select>
                                    <input type="number" step="1" name="pttm_payment_settings[deposit_amount]" value="<?php echo esc_attr($payments['deposit_amount']); ?>" style="width:100px;">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Stripe Configuration Box -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:20px;">
                        <h3 style="margin-top:0; display:flex; align-items:center; gap:8px;">
                            <span class="dashicons dashicons-credit-card"></span> Stripe Payment Gateway
                        </h3>
                        <table class="form-table">
                            <tr>
                                <th>Enable Stripe</th>
                                <td><label><input type="checkbox" name="pttm_payment_settings[stripe_enabled]" value="1" <?php checked(!empty($payments['stripe_enabled'])); ?>> Enable Stripe Online Processing</label></td>
                            </tr>
                            <tr>
                                <th>Stripe Mode</th>
                                <td>
                                    <select name="pttm_payment_settings[stripe_mode]">
                                        <option value="test" <?php selected($payments['stripe_mode'], 'test'); ?>>Test / Sandbox</option>
                                        <option value="live" <?php selected($payments['stripe_mode'], 'live'); ?>>Live Production</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Publishable Key</th>
                                <td><input type="text" name="pttm_payment_settings[stripe_publishable_key]" value="<?php echo esc_attr($payments['stripe_publishable_key']); ?>" class="large-text" placeholder="pk_test_..."></td>
                            </tr>
                            <tr>
                                <th>Secret Key</th>
                                <td><input type="password" name="pttm_payment_settings[stripe_secret_key]" value="<?php echo esc_attr($payments['stripe_secret_key']); ?>" class="large-text" placeholder="sk_test_..."></td>
                            </tr>
                        </table>
                    </div>

                    <!-- PayPal Configuration Box -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:20px;">
                        <h3 style="margin-top:0; display:flex; align-items:center; gap:8px;">
                            <span class="dashicons dashicons-money"></span> PayPal Checkout Integration
                        </h3>
                        <table class="form-table">
                            <tr>
                                <th>Enable PayPal</th>
                                <td><label><input type="checkbox" name="pttm_payment_settings[paypal_enabled]" value="1" <?php checked(!empty($payments['paypal_enabled'])); ?>> Enable PayPal Smart Buttons</label></td>
                            </tr>
                            <tr>
                                <th>Environment</th>
                                <td>
                                    <select name="pttm_payment_settings[paypal_mode]">
                                        <option value="sandbox" <?php selected($payments['paypal_mode'], 'sandbox'); ?>>Sandbox</option>
                                        <option value="live" <?php selected($payments['paypal_mode'], 'live'); ?>>Live</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Client ID</th>
                                <td><input type="text" name="pttm_payment_settings[paypal_client_id]" value="<?php echo esc_attr($payments['paypal_client_id']); ?>" class="large-text"></td>
                            </tr>
                            <tr>
                                <th>Client Secret</th>
                                <td><input type="password" name="pttm_payment_settings[paypal_secret]" value="<?php echo esc_attr($payments['paypal_secret']); ?>" class="large-text"></td>
                            </tr>
                        </table>
                    </div>

                    <!-- Square Configuration Box -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px;">
                        <h3 style="margin-top:0; display:flex; align-items:center; gap:8px;">
                            <span class="dashicons dashicons-store"></span> Square Web Payments
                        </h3>
                        <table class="form-table">
                            <tr>
                                <th>Enable Square</th>
                                <td><label><input type="checkbox" name="pttm_payment_settings[square_enabled]" value="1" <?php checked(!empty($payments['square_enabled'])); ?>> Enable Square Terminal & In-App Checkout</label></td>
                            </tr>
                            <tr>
                                <th>Application ID</th>
                                <td><input type="text" name="pttm_payment_settings[square_app_id]" value="<?php echo esc_attr($payments['square_app_id']); ?>" class="large-text" placeholder="sq0idp-..."></td>
                            </tr>
                            <tr>
                                <th>Access Token</th>
                                <td><input type="password" name="pttm_payment_settings[square_access_token]" value="<?php echo esc_attr($payments['square_access_token']); ?>" class="large-text"></td>
                            </tr>
                            <tr>
                                <th>Location ID</th>
                                <td><input type="text" name="pttm_payment_settings[square_location_id]" value="<?php echo esc_attr($payments['square_location_id']); ?>" class="regular-text"></td>
                            </tr>
                        </table>
                    </div>
                </div>

            <!-- TAB 6: CONTENT & IMAGES -->
            <?php elseif ($active_tab === 'content'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro">
                        <h2>Content Copy & Hero Imagery</h2>
                        <p>Customize hero headlines, living room background images, and company phone details.</p>
                    </div>

                    <table class="form-table">
                        <tr>
                            <th scope="row"><label>Hero Background Image</label></th>
                            <td>
                                <div style="display:flex; align-items:center; gap:16px;">
                                    <img src="<?php echo esc_url(!empty($settings['hero_image_url']) ? $settings['hero_image_url'] : get_template_directory_uri() . '/assets/images/hero-bg.jpg'); ?>" style="width:160px; height:90px; object-fit:cover; border-radius:8px; border:1px solid #ccc;" id="pttm_hero_bg_preview">
                                    <div>
                                        <input type="text" name="pttm_settings[hero_image_url]" value="<?php echo esc_attr($settings['hero_image_url'] ?? ''); ?>" class="large-text pttm-img-input" id="pttm_hero_bg_input" placeholder="Default: assets/images/hero-bg.jpg">
                                        <button type="button" class="button pttm-media-upload-btn" data-target="pttm_hero_bg_input" data-preview="pttm_hero_bg_preview" style="margin-top:6px;">Select Media Image</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Hero Title</label></th>
                            <td>
                                <textarea name="pttm_settings[hero_title]" rows="2" class="large-text"><?php echo esc_textarea($settings['hero_title'] ?? "How many TVs\nwould you like mounted?"); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Company Phone Number</label></th>
                            <td>
                                <input type="text" name="pttm_settings[company_phone]" value="<?php echo esc_attr($settings['company_phone'] ?? '(800) 555-0199'); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label>Company Email</label></th>
                            <td>
                                <input type="email" name="pttm_settings[company_email]" value="<?php echo esc_attr($settings['company_email'] ?? 'service@primetimetvmounting.com'); ?>" class="regular-text">
                            </td>
                        </tr>
                    </table>
                </div>

            <!-- TAB 7: FAQS & REVIEWS -->
            <?php elseif ($active_tab === 'faqs_reviews'): ?>
                <div class="pttm-tab-content">
                    <div class="pttm-tab-intro" style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h2>Frequently Asked Questions & Customer Reviews</h2>
                            <p>Manage the FAQ questions and verified customer testimonials displayed in popups and page templates.</p>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="button" class="button button-primary pttm-saas-btn-gold" id="pttmAddFaqBtn">
                                <span class="dashicons dashicons-plus-alt"></span> Add FAQ
                            </button>
                            <button type="button" class="button button-primary pttm-saas-btn-gold" id="pttmAddReviewBtn">
                                <span class="dashicons dashicons-plus-alt"></span> Add Review
                            </button>
                        </div>
                    </div>

                    <h3>Frequently Asked Questions</h3>
                    <div class="pttm-cards-list" id="pttmFaqsList" style="margin-bottom:30px;">
                        <?php foreach ($settings['faqs'] as $i => $faq): ?>
                            <div class="pttm-item-card" style="display:block;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                    <label><strong>Question:</strong></label>
                                    <button type="button" class="button button-link-delete pttm-remove-item-btn"><span class="dashicons dashicons-trash"></span> Remove</button>
                                </div>
                                <div style="margin-bottom:8px;">
                                    <input type="text" name="pttm_faqs[<?php echo $i; ?>][question]" value="<?php echo esc_attr($faq['question']); ?>" class="widefat">
                                </div>
                                <div>
                                    <label><strong>Answer:</strong></label>
                                    <textarea name="pttm_faqs[<?php echo $i; ?>][answer]" rows="3" class="widefat"><?php echo esc_textarea($faq['answer']); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h3>Customer Reviews</h3>
                    <div class="pttm-cards-list" id="pttmReviewsList">
                        <?php foreach ($settings['reviews'] as $i => $rev): ?>
                            <div class="pttm-item-card" style="display:block;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                    <label><strong>Testimonial Text:</strong></label>
                                    <button type="button" class="button button-link-delete pttm-remove-item-btn"><span class="dashicons dashicons-trash"></span> Remove</button>
                                </div>
                                <div style="margin-bottom:8px;">
                                    <textarea name="pttm_reviews[<?php echo $i; ?>][text]" rows="2" class="widefat"><?php echo esc_textarea($rev['text']); ?></textarea>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                    <div>
                                        <label>Author Name:</label>
                                        <input type="text" name="pttm_reviews[<?php echo $i; ?>][author]" value="<?php echo esc_attr($rev['author']); ?>" class="widefat">
                                    </div>
                                    <div>
                                        <label>Location / Badge:</label>
                                        <input type="text" name="pttm_reviews[<?php echo $i; ?>][location]" value="<?php echo esc_attr($rev['location']); ?>" class="widefat">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="pttm-form-actions" style="margin-top:20px; padding:16px 0; border-top:1px solid #ccd0d4;">
                <button type="submit" class="button button-primary button-hero" style="background:#8c5f34; border-color:#8c5f34;">
                    Save All Platform Changes
                </button>
            </div>
        </form>

    </div>
    <?php
}
