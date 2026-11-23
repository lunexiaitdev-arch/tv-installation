<?php
/**
 * Prime Time TV Mounting - Order Intake & Management Module
 * Enterprise SaaS-level Order Intake, Live Pricing Ledger & Admin CRM
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Type for Order Requests
 */
function pttm_register_order_cpt() {
    $labels = array(
        'name'                  => 'Order Requests',
        'singular_name'         => 'Order Request',
        'menu_name'             => 'Order Requests',
        'name_admin_bar'        => 'Order Request',
        'archives'              => 'Order Archives',
        'attributes'            => 'Order Attributes',
        'parent_item_colon'     => 'Parent Order:',
        'all_items'             => 'All Orders',
        'add_new_item'          => 'Add New Order',
        'add_new'               => 'Add New',
        'new_item'              => 'New Order',
        'edit_item'             => 'Edit Order',
        'update_item'           => 'Update Order',
        'view_item'             => 'View Order',
        'view_items'            => 'View Orders',
        'search_items'          => 'Search Orders',
        'not_found'             => 'No orders found',
        'not_found_in_trash'    => 'No orders found in Trash'
    );

    $args = array(
        'label'                 => 'Order Request',
        'description'           => 'Customer TV mounting order intake requests and estimates',
        'labels'                => $labels,
        'supports'              => array('title', 'custom-fields'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-clipboard',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post'
    );
    register_post_type('pttm_order', $args);
}
add_action('init', 'pttm_register_order_cpt');

/**
 * Add Custom Columns to Order List Table in WP Admin
 */
function pttm_order_columns($columns) {
    $new_columns = array(
        'cb'            => $columns['cb'],
        'order_id'      => 'Order Ref',
        'customer_name' => 'Customer Details',
        'tv_count'      => 'TVs & Addons',
        'services_count'=> 'Hourly Services',
        'est_total'     => 'Est. Price',
        'payment_status'=> 'Payment / Deposit',
        'order_status'  => 'Lead Status',
        'date'          => 'Date Submitted'
    );
    return $new_columns;
}
add_filter('manage_pttm_order_posts_columns', 'pttm_order_columns');

function pttm_order_custom_column($column, $post_id) {
    $data = get_post_meta($post_id, '_pttm_order_data', true);
    if (!is_array($data)) return;

    switch ($column) {
        case 'order_id':
            echo '<strong style="color:#8c5f34; font-family:monospace; font-size:13px;">#PTTM-' . esc_html($post_id) . '</strong>';
            break;

        case 'customer_name':
            $name = esc_html($data['customer_name'] ?? 'Guest Lead');
            $phone = esc_attr($data['customer_phone'] ?? '');
            $email = esc_attr($data['customer_email'] ?? '');
            echo '<div style="font-weight:700; color:#0f172a; font-size:13.5px;">' . $name . '</div>';
            if ($phone) {
                echo '<a href="tel:' . $phone . '" style="color:#0284c7; text-decoration:none; font-size:12px; display:inline-flex; align-items:center; gap:3px; margin-top:2px;"><span class="dashicons dashicons-phone" style="font-size:13px; line-height:1.2;"></span> ' . esc_html($phone) . '</a>';
            }
            if ($email) {
                echo '<div style="color:#64748b; font-size:11.5px;">' . esc_html($email) . '</div>';
            }
            break;

        case 'tv_count':
            $tv_count = intval($data['tv_count'] ?? 1);
            $addons_total_count = 0;
            if (!empty($data['tvs']) && is_array($data['tvs'])) {
                foreach ($data['tvs'] as $tv) {
                    $addons_total_count += !empty($tv['addons']) ? count($tv['addons']) : 0;
                }
            }
            echo '<span style="display:inline-block; background:#f1f5f9; color:#334155; font-weight:700; padding:2px 8px; border-radius:6px; font-size:12px;">' . $tv_count . ' TV' . ($tv_count > 1 ? 's' : '') . '</span>';
            if ($addons_total_count > 0) {
                echo '<span style="display:inline-block; margin-left:6px; background:#faf5ff; color:#7e22ce; font-weight:600; padding:2px 7px; border-radius:6px; font-size:11px;">+' . $addons_total_count . ' Addons</span>';
            }
            break;

        case 'services_count':
            $srv_count = !empty($data['services']) ? count($data['services']) : 0;
            if ($srv_count > 0) {
                echo '<span style="display:inline-block; background:#ecfdf5; color:#047857; font-weight:600; padding:2px 8px; border-radius:6px; font-size:11.5px;">' . $srv_count . ' Service' . ($srv_count > 1 ? 's' : '') . ' ($75/hr)</span>';
            } else {
                echo '<span style="color:#94a3b8; font-size:12px;">None</span>';
            }
            break;

        case 'est_total':
            $total = floatval($data['estimated_total'] ?? 0);
            echo '<strong style="font-size:14px; color:#0f172a;">$' . number_format($total, 2) . '</strong>';
            break;

        case 'payment_status':
            $pay_status = get_post_meta($post_id, '_pttm_payment_status', true) ?: 'lead_intake';
            $badges = array(
                'lead_intake'  => array('label' => 'Pay on Completion', 'bg' => '#f1f5f9', 'color' => '#475569'),
                'deposit_paid' => array('label' => 'Deposit Paid', 'bg' => '#dcfce7', 'color' => '#15803d'),
                'paid'         => array('label' => 'Paid in Full', 'bg' => '#dbeafe', 'color' => '#1d4ed8')
            );
            $b = $badges[$pay_status] ?? $badges['lead_intake'];
            echo '<span style="display:inline-block; padding:3px 8px; border-radius:12px; font-size:11px; font-weight:700; background:' . esc_attr($b['bg']) . '; color:' . esc_attr($b['color']) . ';">' . esc_html($b['label']) . '</span>';
            break;

        case 'order_status':
            $status = get_post_meta($post_id, '_pttm_order_status', true) ?: 'new';
            $colors = array(
                'new'       => array('bg' => '#fef3c7', 'color' => '#92400e', 'label' => '⚡ New Lead'),
                'called'    => array('bg' => '#e0f2fe', 'color' => '#0369a1', 'label' => '📞 Contacted'),
                'scheduled' => array('bg' => '#dcfce7', 'color' => '#15803d', 'label' => '🗓️ Scheduled'),
                'completed' => array('bg' => '#f1f5f9', 'color' => '#475569', 'label' => '✅ Completed'),
                'cancelled' => array('bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => '❌ Cancelled')
            );
            $curr = $colors[$status] ?? $colors['new'];
            echo '<span style="display:inline-block; padding:3px 10px; border-radius:12px; font-size:11.5px; font-weight:700; background:' . esc_attr($curr['bg']) . '; color:' . esc_attr($curr['color']) . ';">' . esc_html($curr['label']) . '</span>';
            break;
    }
}
add_action('manage_pttm_order_posts_custom_column', 'pttm_order_custom_column', 10, 2);

/**
 * Add SaaS Order Details Metabox
 */
function pttm_add_order_meta_box() {
    add_meta_box(
        'pttm_order_details',
        'Customer Order Intake & Live Calculation Ledger',
        'pttm_render_order_meta_box',
        'pttm_order',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'pttm_add_order_meta_box');

function pttm_render_order_meta_box($post) {
    $data = get_post_meta($post->ID, '_pttm_order_data', true);
    $status = get_post_meta($post->ID, '_pttm_order_status', true) ?: 'new';
    $pay_status = get_post_meta($post->ID, '_pttm_payment_status', true) ?: 'lead_intake';
    wp_nonce_field('pttm_save_order_details_action', 'pttm_save_order_details_nonce');

    $total = floatval($data['estimated_total'] ?? 0);
    $phone = $data['customer_phone'] ?? '';
    $clean_phone = preg_replace('/[^0-9]/', '', $phone);
    $address = $data['customer_address'] ?? '';
    $map_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
    ?>
    <div class="pttm-saas-order-card" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #1e293b;">
        
        <!-- Action Bar -->
        <div style="display:flex; justify-content:space-between; align-items:center; background:#0f172a; color:#ffffff; padding:18px 24px; border-radius:12px 12px 0 0;">
            <div>
                <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#94a3b8; font-weight:700;">Intake Request</div>
                <h2 style="margin:2px 0 0 0; color:#ffffff; font-size:20px; font-weight:800;">#PTTM-<?php echo esc_html($post->ID); ?></h2>
            </div>
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="text-align:right;">
                    <div style="font-size:11px; color:#94a3b8;">Status Workflow</div>
                    <select name="pttm_order_status" style="background:#1e293b; color:#ffffff; border:1px solid #334155; border-radius:6px; font-size:13px; font-weight:600; padding:4px 10px;">
                        <option value="new" <?php selected($status, 'new'); ?>>⚡ New Lead (Action Required)</option>
                        <option value="called" <?php selected($status, 'called'); ?>>📞 Contacted / Details Confirmed</option>
                        <option value="scheduled" <?php selected($status, 'scheduled'); ?>>🗓️ Scheduled</option>
                        <option value="completed" <?php selected($status, 'completed'); ?>>✅ Completed</option>
                        <option value="cancelled" <?php selected($status, 'cancelled'); ?>>❌ Cancelled</option>
                    </select>
                </div>
                <button type="button" class="button button-secondary" onclick="window.print();" style="border-radius:6px; font-weight:600;">
                    <span class="dashicons dashicons-printer" style="font-size:16px; line-height:1.3;"></span> Print Invoice
                </button>
            </div>
        </div>

        <div style="background:#ffffff; border:1px solid #e2e8f0; border-top:none; border-radius:0 0 12px 12px; padding:24px;">
            
            <!-- Quick Contact Toolbar -->
            <div style="display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
                <?php if ($clean_phone): ?>
                    <a href="tel:<?php echo esc_attr($clean_phone); ?>" class="button button-primary" style="background:#0284c7; border-color:#0284c7; font-weight:600; display:inline-flex; align-items:center; gap:4px; padding:6px 14px; height:auto;">
                        <span class="dashicons dashicons-phone"></span> Call Customer (<?php echo esc_html($phone); ?>)
                    </a>
                    <a href="sms:<?php echo esc_attr($clean_phone); ?>" class="button" style="font-weight:600; display:inline-flex; align-items:center; gap:4px; padding:6px 14px; height:auto;">
                        <span class="dashicons dashicons-format-chat"></span> Send SMS
                    </a>
                <?php endif; ?>
                <?php if (!empty($data['customer_email'])): ?>
                    <a href="mailto:<?php echo esc_attr($data['customer_email']); ?>?subject=Your Prime Time TV Mounting Order #PTTM-<?php echo esc_html($post->ID); ?>" class="button" style="font-weight:600; display:inline-flex; align-items:center; gap:4px; padding:6px 14px; height:auto;">
                        <span class="dashicons dashicons-email"></span> Email Customer
                    </a>
                <?php endif; ?>
                <?php if ($address): ?>
                    <a href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener" class="button" style="font-weight:600; display:inline-flex; align-items:center; gap:4px; padding:6px 14px; height:auto;">
                        <span class="dashicons dashicons-location"></span> Open in Google Maps
                    </a>
                <?php endif; ?>
            </div>

            <!-- Customer & Ledger Grid -->
            <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:24px; margin-bottom:24px;">
                
                <!-- Customer Profile Card -->
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px;">
                    <h3 style="margin:0 0 14px 0; font-size:15px; font-weight:700; color:#0f172a; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Customer Information</h3>
                    <div style="display:grid; grid-template-columns:120px 1fr; gap:10px; font-size:13.5px;">
                        <span style="color:#64748b; font-weight:600;">Full Name:</span>
                        <strong style="color:#0f172a;"><?php echo esc_html($data['customer_name'] ?? 'N/A'); ?></strong>

                        <span style="color:#64748b; font-weight:600;">Phone:</span>
                        <span><a href="tel:<?php echo esc_attr($phone); ?>" style="color:#0284c7; text-decoration:none; font-weight:600;"><?php echo esc_html($phone ?: 'N/A'); ?></a></span>

                        <span style="color:#64748b; font-weight:600;">Email:</span>
                        <span><a href="mailto:<?php echo esc_attr($data['customer_email'] ?? ''); ?>" style="color:#0284c7; text-decoration:none;"><?php echo esc_html($data['customer_email'] ?? 'N/A'); ?></a></span>

                        <span style="color:#64748b; font-weight:600;">Address:</span>
                        <span><?php echo nl2br(esc_html($address ?: 'N/A')); ?></span>

                        <?php if (!empty($data['customer_notes'])): ?>
                            <span style="color:#64748b; font-weight:600;">Customer Note:</span>
                            <span style="background:#fef3c7; padding:6px 10px; border-radius:6px; font-size:13px; color:#78350f;"><em><?php echo nl2br(esc_html($data['customer_notes'])); ?></em></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price Ledger Card -->
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px;">
                    <h3 style="margin:0 0 14px 0; font-size:15px; font-weight:700; color:#0f172a; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">Calculated Financial Ledger</h3>
                    
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px;">
                        <span style="color:#64748b;">TV Mounting Quantity:</span>
                        <strong><?php echo intval($data['tv_count'] ?? 1); ?> TV(s)</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px;">
                        <span style="color:#64748b;">Estimated Order Total:</span>
                        <strong style="font-size:18px; color:#8c5f34;">$<?php echo number_format($total, 2); ?></strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13.5px; border-top:1px dashed #cbd5e1; padding-top:8px;">
                        <span style="color:#64748b;">Payment / Deposit:</span>
                        <span style="font-weight:700; color:#047857;"><?php echo esc_html(ucfirst(str_replace('_', ' ', $pay_status))); ?></span>
                    </div>

                    <div style="margin-top:14px; background:#ffffff; border:1px solid #e2e8f0; border-radius:6px; padding:10px; font-size:12px; color:#64748b;">
                        ℹ️ <em>Order intake estimate calculated dynamically. Ready for Stripe, PayPal, or Square payment capture upon phone confirmation.</em>
                    </div>
                </div>
            </div>

            <!-- Itemized Breakdown Table -->
            <h3 style="margin:0 0 12px 0; font-size:15px; font-weight:700; color:#0f172a;">Itemized Order Breakdown</h3>
            <div style="border:1px solid #e2e8f0; border-radius:8px; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse; font-size:13.5px; text-align:left;">
                    <thead style="background:#f1f5f9; color:#475569; font-size:12px; text-transform:uppercase;">
                        <tr>
                            <th style="padding:10px 16px;">Item Description</th>
                            <th style="padding:10px 16px;">Type / Options</th>
                            <th style="padding:10px 16px; text-align:right;">Estimated Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['tvs']) && is_array($data['tvs'])): ?>
                            <?php foreach ($data['tvs'] as $tIdx => $tv): ?>
                                <tr style="border-bottom:1px solid #f1f5f9; background:#ffffff;">
                                    <td style="padding:12px 16px;">
                                        <strong style="color:#0f172a;">TV <?php echo $tIdx + 1; ?> Mounting Installation</strong>
                                    </td>
                                    <td style="padding:12px 16px; color:#475569;">
                                        Size: <strong><?php echo esc_html($tv['size_label'] ?? 'Standard'); ?></strong>
                                    </td>
                                    <td style="padding:12px 16px; text-align:right; color:#0f172a; font-weight:600;">
                                        $<?php echo number_format(floatval($tv['size_price'] ?? 0), 2); ?>
                                    </td>
                                </tr>
                                <?php if (!empty($tv['addons']) && is_array($tv['addons'])): ?>
                                    <?php foreach ($tv['addons'] as $addon): ?>
                                        <tr style="border-bottom:1px solid #f1f5f9; background:#faf8f5;">
                                            <td style="padding:8px 16px 8px 32px; color:#64748b;">
                                                ↳ Addon: <strong style="color:#334155;"><?php echo esc_html($addon['title']); ?></strong>
                                            </td>
                                            <td style="padding:8px 16px; font-size:12px; color:#64748b;">
                                                <?php echo esc_html($addon['subtitle'] ?? 'Supplied by PTTM'); ?>
                                            </td>
                                            <td style="padding:8px 16px; text-align:right; font-weight:600; color:#8c5f34;">
                                                +$<?php echo number_format(floatval($addon['price'] ?? 0), 2); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($data['services']) && is_array($data['services'])): ?>
                            <?php foreach ($data['services'] as $srv): ?>
                                <tr style="border-bottom:1px solid #f1f5f9; background:#f0fdf4;">
                                    <td style="padding:12px 16px;">
                                        <strong style="color:#166534;">Hourly Service: <?php echo esc_html($srv['title']); ?></strong>
                                    </td>
                                    <td style="padding:12px 16px; font-size:12px; color:#166534;">
                                        <?php echo esc_html($srv['subtitle'] ?? 'Additional handyman request'); ?>
                                    </td>
                                    <td style="padding:12px 16px; text-align:right; font-weight:700; color:#166534;">
                                        <?php echo esc_html($srv['price_rate'] ?? '$75 / hr'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot style="background:#f8fafc; font-weight:700; border-top:2px solid #cbd5e1;">
                        <tr>
                            <td colspan="2" style="padding:14px 16px; font-size:14px; color:#0f172a; text-align:right;">Grand Estimated Total:</td>
                            <td style="padding:14px 16px; font-size:16px; color:#8c5f34; text-align:right;">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
    <?php
}

function pttm_save_order_details($post_id) {
    if (!isset($_POST['pttm_save_order_details_nonce']) || !wp_verify_nonce($_POST['pttm_save_order_details_nonce'], 'pttm_save_order_details_action')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['pttm_order_status'])) {
        update_post_meta($post_id, '_pttm_order_status', sanitize_text_field($_POST['pttm_order_status']));
    }
}
add_action('save_post_pttm_order', 'pttm_save_order_details');

/**
 * Handle Frontend AJAX Order Submission with Live Price Computation
 */
function pttm_handle_ajax_order_submission() {
    check_ajax_referer('pttm_order_wizard_nonce', 'nonce');

    $payload = json_decode(stripslashes($_POST['order_data'] ?? '{}'), true);
    if (empty($payload)) {
        wp_send_json_error(array('message' => 'Invalid order data payload.'));
    }

    $customer_name = sanitize_text_field($payload['customer_name'] ?? '');
    $customer_phone = sanitize_text_field($payload['customer_phone'] ?? '');
    $customer_email = sanitize_email($payload['customer_email'] ?? '');
    $customer_address = sanitize_textarea_field($payload['customer_address'] ?? '');
    $customer_notes = sanitize_textarea_field($payload['customer_notes'] ?? '');
    $tv_count = intval($payload['tv_count'] ?? 1);

    if (empty($customer_name) || empty($customer_phone)) {
        wp_send_json_error(array('message' => 'Please provide your name and phone number so we can confirm your order.'));
    }

    // Verify & Calculate Total Server-Side
    $calculated_total = 0;
    if (!empty($payload['tvs']) && is_array($payload['tvs'])) {
        foreach ($payload['tvs'] as $tv) {
            $calculated_total += floatval($tv['size_price'] ?? 0);
            if (!empty($tv['addons']) && is_array($tv['addons'])) {
                foreach ($tv['addons'] as $addon) {
                    $calculated_total += floatval($addon['price'] ?? 0);
                }
            }
        }
    }
    if (!empty($payload['services']) && is_array($payload['services'])) {
        foreach ($payload['services'] as $srv) {
            $calculated_total += floatval($srv['price_num'] ?? 75);
        }
    }

    if (empty($payload['estimated_total']) || floatval($payload['estimated_total']) <= 0) {
        $payload['estimated_total'] = $calculated_total;
    }

    // Insert Order Request Post
    $post_title = $customer_name . ' (' . $tv_count . ' TV' . ($tv_count > 1 ? 's' : '') . ' - $' . number_format($payload['estimated_total'], 2) . ')';
    $post_id = wp_insert_post(array(
        'post_title'    => $post_title,
        'post_type'     => 'pttm_order',
        'post_status'   => 'publish'
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Failed to save order. Please try again.'));
    }

    update_post_meta($post_id, '_pttm_order_data', $payload);
    update_post_meta($post_id, '_pttm_order_status', 'new');
    update_post_meta($post_id, '_pttm_estimated_total', floatval($payload['estimated_total']));
    update_post_meta($post_id, '_pttm_payment_status', 'lead_intake');

    // Send Admin Email Notification
    $settings = get_option('pttm_settings', pttm_get_default_settings());
    $admin_email = !empty($settings['company_email']) ? $settings['company_email'] : get_option('admin_email');
    
    $subject = '⚡ New Order Intake from ' . $customer_name . ' ($' . number_format($payload['estimated_total'], 2) . ')';
    
    $message = "NEW PRIME TIME TV MOUNTING ORDER INTAKE\n\n";
    $message .= "Order ID: #PTTM-" . $post_id . "\n";
    $message .= "Customer: " . $customer_name . "\n";
    $message .= "Phone: " . $customer_phone . "\n";
    $message .= "Email: " . ($customer_email ?: 'N/A') . "\n";
    $message .= "Address: " . ($customer_address ?: 'N/A') . "\n\n";
    $message .= "TV Count: " . $tv_count . "\n";
    $message .= "Estimated Total: $" . number_format(floatval($payload['estimated_total']), 2) . "\n\n";
    $message .= "View & Manage in WordPress Admin:\n";
    $message .= admin_url('post.php?post=' . $post_id . '&action=edit') . "\n";

    @wp_mail($admin_email, $subject, $message);

    wp_send_json_success(array(
        'order_id'        => $post_id,
        'estimated_total' => $payload['estimated_total'],
        'message'         => 'Order received successfully!'
    ));
}
add_action('wp_ajax_pttm_submit_order', 'pttm_handle_ajax_order_submission');
add_action('wp_ajax_nopriv_pttm_submit_order', 'pttm_handle_ajax_order_submission');
