<?php
/**
 * Default Catalog Data and Initial Setup for Prime Time TV Mounting
 */

if (!defined('ABSPATH')) {
    exit;
}

function pttm_get_default_settings() {
    return array(
        'company_name' => 'Prime Time TV Mounting',
        'company_phone' => '(800) 555-0199',
        'company_email' => 'service@primetimetvmounting.com',
        'company_hours' => 'Mon - Sun: 7:00 AM - 9:00 PM',
        'logo_image_url' => '',
        'hero_image_url' => '',
        'tv_preview_image_url' => '',
        'hero_title' => "How many TVs\nwould you like mounted?",
        'step1_helper_title' => 'You can always adjust this later',
        'step1_helper_text' => 'It\'s easy to make changes before checkout or when we call you to confirm.',
        'step4_title' => 'Anything else you need?',
        'step4_subtitle' => 'Add additional services to your order. These services are $75 per hour.',
        'step4_call_guarantee_title' => 'We\'ll call you within minutes!',
        'step4_call_guarantee_text' => 'A member of our team will review your order and give you a quick call to confirm the details.',
        'confirm_title' => 'Order Received',
        'confirm_lead' => 'We\'ve got your request.',
        'confirm_subtext' => 'A member of our team will call you within minutes to confirm the details, answer any questions, and find a time that works for you.',
        'badge_insurance' => '$1M Liability Insurance',
        'badge_experience' => '10+ Years Experience',
        'badge_reviews' => 'Hundreds of 5-Star Reviews',
        'google_maps_api_key' => '',
        'enable_square_payments' => 0,
        'square_mode' => 'sandbox',
        'square_app_id' => '',
        'square_access_token' => '',
        'square_location_id' => '',
        'square_deposit_amount' => '50.00',
        'business_history' => "Founded by master installers with over a decade of hands-on experience, Prime Time TV Mounting was built on a simple premise: home technology installation should be flawless, clean, and completely stress-free.\n\nFrom luxury wall mounts and concealed wiring in high-end living spaces to complex commercial AV setups, we treat every wall as if it were in our own home. Fully licensed, bonded, and insured with $1M coverage.",
        'faqs' => array(
            array(
                'question' => 'How does the ordering process work?',
                'answer' => 'Select your TV quantity, pick your sizes and desired add-ons, and submit your request. We will give you a call within minutes to review the details, verify wall type (drywall, brick, concrete), answer any questions, and confirm a schedule that works for you.'
            ),
            array(
                'question' => 'Do you provide the TV wall brackets and hardware?',
                'answer' => 'Yes! We carry premium commercial-grade Fixed, Tilt, and Full Motion mounts, heavy-duty stud anchors, concealed cable plates, and electrical bridge kits directly in our service trucks.'
            ),
            array(
                'question' => 'Can you hide the wires inside the wall?',
                'answer' => 'Absolutely. We offer low-voltage in-wall cable concealment plates for HDMI/audio cables, as well as code-compliant in-wall power bridge kits to relocate power directly behind the TV with zero visible wires.'
            ),
            array(
                'question' => 'Can you mount on brick, stone, or above a fireplace?',
                'answer' => 'Yes! Our technicians specialize in challenging surfaces including brick, stone, concrete, tile, and over-the-fireplace installations with heat-safe placement.'
            ),
            array(
                'question' => 'Is there any commitment when submitting an order request?',
                'answer' => 'None at all. Your request is an intake inquiry so we know your exact setup. We confirm everything by phone and finalize the scope before any work begins.'
            )
        ),
        'reviews' => array(
            array(
                'author' => 'Marcus Sterling',
                'rating' => 5,
                'location' => 'Verified Homeowner',
                'text' => 'Fastest and cleanest TV mounting I have ever experienced. They mounted three 75-inch TVs with completely hidden wiring in under 3 hours. True professionals.'
            ),
            array(
                'author' => 'Elena Rostova',
                'rating' => 5,
                'location' => 'Downtown Loft',
                'text' => 'The online ordering flow was as smooth as Apple or Domino’s. They called 5 minutes later, showed up the next morning on time, and did an immaculate job with the Samsung Frame box.'
            ),
            array(
                'author' => 'David & Sarah Jenkins',
                'rating' => 5,
                'location' => 'Suburban Residence',
                'text' => 'Super friendly technicians, took extra care to center everything perfectly, and even helped assemble our new entertainment console. 10/10 recommendation!'
            )
        )
    );
}

function pttm_get_default_tv_sizes() {
    return array(
        array('id' => 'under_43', 'label' => 'Under 43"', 'description' => 'Compact TVs for bedrooms, kitchens, and offices', 'base_price' => 75),
        array('id' => '43_55', 'label' => '43" – 55"', 'description' => 'Standard living room and media sizes', 'base_price' => 95),
        array('id' => '56_70', 'label' => '56" – 70"', 'description' => 'Large screen displays and theater rooms', 'base_price' => 125),
        array('id' => '71_plus', 'label' => '71"+', 'description' => 'Extra large, flagship, and theater displays', 'base_price' => 165)
    );
}

function pttm_get_default_addons() {
    return array(
        array(
            'id' => 'fixed_mount',
            'title' => 'Fixed Mount',
            'subtitle' => 'Supplied by PTTM',
            'price' => 30,
            'image' => 'addon-fixed-mount.svg',
            'description' => 'Ultra-slim low-profile mount keeping TV flush against the wall.',
            'enabled' => 1
        ),
        array(
            'id' => 'tilt_mount',
            'title' => 'Tilt Mount',
            'subtitle' => 'Supplied by PTTM',
            'price' => 50,
            'image' => 'addon-tilt-mount.svg',
            'description' => 'Allows up to 15 degrees downward tilt to eliminate glare and optimize view.',
            'enabled' => 1
        ),
        array(
            'id' => 'full_motion_mount',
            'title' => 'Full Motion Mount',
            'subtitle' => 'Supplied by PTTM',
            'price' => 80,
            'image' => 'addon-full-motion-mount.svg',
            'description' => 'Dual articulating arms for full extension, swivel, and multi-angle viewing.',
            'enabled' => 1
        ),
        array(
            'id' => 'cable_plates',
            'title' => 'Low Voltage Cable Plates',
            'subtitle' => 'Behind + below TV',
            'price' => 75,
            'image' => 'addon-cable-plates.svg',
            'description' => 'In-wall brush pass-through plates to hide HDMI and audio cables.',
            'enabled' => 1
        ),
        array(
            'id' => 'power_outlet_kit',
            'title' => 'Power Outlet Kit',
            'subtitle' => 'Relocates power behind TV',
            'price' => 125,
            'image' => 'addon-power-outlet-kit.svg',
            'description' => 'Code-compliant in-wall power bridge to put a clean outlet behind the TV without electrical rewiring.',
            'enabled' => 1
        ),
        array(
            'id' => 'new_outlet',
            'title' => 'New Outlet Behind TV',
            'subtitle' => 'Installed by electrician',
            'price' => 199,
            'image' => 'addon-new-outlet.svg',
            'description' => 'Dedicated new electrical duplex receptacle wired directly into home circuit.',
            'enabled' => 1
        ),
        array(
            'id' => 'soundbar_mount',
            'title' => 'Soundbar Mount',
            'subtitle' => 'Supplied by PTTM',
            'price' => 95,
            'image' => 'addon-soundbar-mount.svg',
            'description' => 'Precision bracket anchoring soundbar directly underneath the mounted TV.',
            'enabled' => 1
        ),
        array(
            'id' => 'led_backlights',
            'title' => 'LED Backlights',
            'subtitle' => 'Supplied by PTTM',
            'price' => 45,
            'image' => 'addon-led-backlights.svg',
            'description' => 'Ambient bias backlighting strip with USB power and color remote control.',
            'enabled' => 1
        ),
        array(
            'id' => 'floating_shelf',
            'title' => 'Floating Media Shelf',
            'subtitle' => 'Supplied by PTTM',
            'price' => 60,
            'image' => 'addon-floating-shelf.svg',
            'description' => 'Minimalist floating shelf mounted under TV for Apple TV, consoles, or remotes.',
            'enabled' => 1
        ),
        array(
            'id' => 'samsung_frame_box',
            'title' => 'Samsung Frame Media Box',
            'subtitle' => 'Supplied by PTTM',
            'price' => 150,
            'image' => 'addon-samsung-frame-box.svg',
            'description' => 'Recessed in-wall enclosure tailored specifically for Samsung One Connect box.',
            'enabled' => 1
        )
    );
}

function pttm_get_default_services() {
    return array(
        array(
            'id' => 'art_mounting',
            'title' => 'Art Mounting',
            'subtitle' => 'Frames, canvas, mirrors and more.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-art-mounting.svg',
            'enabled' => 1
        ),
        array(
            'id' => 'furniture_assembly',
            'title' => 'Furniture Assembly',
            'subtitle' => 'Beds, dressers, tables, shelves and more.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-furniture-assembly.svg',
            'enabled' => 1
        ),
        array(
            'id' => 'moving_pickup',
            'title' => 'Moving Services (Pickup)',
            'subtitle' => 'Move, load or deliver using my pickup truck.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-moving-pickup.svg',
            'enabled' => 1
        ),
        array(
            'id' => 'moving_uhaul',
            'title' => 'Moving Services (U-Haul Truck)',
            'subtitle' => 'Help with your U-Haul truck for a smooth move.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-moving-uhaul.svg',
            'enabled' => 1
        ),
        array(
            'id' => 'ceiling_fan',
            'title' => 'Mount Ceiling Fan',
            'subtitle' => 'Installation and secure wiring.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-ceiling-fan.svg',
            'enabled' => 1
        ),
        array(
            'id' => 'curtain_rods',
            'title' => 'Mount Curtain Rods',
            'subtitle' => 'Single or multiple rods, any room.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-curtain-rods.svg',
            'enabled' => 1
        ),
        array(
            'id' => 'other_items',
            'title' => 'Mount Other Items on Wall',
            'subtitle' => 'Shelves, TVs, boards, racks and more.',
            'price_rate' => '$75 / hour',
            'price_num' => 75,
            'image' => 'service-other-items.svg',
            'enabled' => 1
        )
    );
}

/**
 * Seed initial options if they don't exist
 */
function pttm_seed_initial_data() {
    if (!get_option('pttm_settings')) {
        update_option('pttm_settings', pttm_get_default_settings());
    }
    if (!get_option('pttm_tv_sizes')) {
        update_option('pttm_tv_sizes', pttm_get_default_tv_sizes());
    }
    if (!get_option('pttm_addons')) {
        update_option('pttm_addons', pttm_get_default_addons());
    }
    if (!get_option('pttm_services')) {
        update_option('pttm_services', pttm_get_default_services());
    }
}
add_action('after_switch_theme', 'pttm_seed_initial_data');
pttm_seed_initial_data(); // Also run on file load to ensure defaults are populated immediately
