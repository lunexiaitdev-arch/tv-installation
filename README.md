# Prime Time TV Mounting (PTTM)

Premium, minimalist WordPress website & order intake configurator for **Prime Time TV Mounting**. Engineered with an *"Apple meets Domino’s"* frictionless user experience: clean, visual, real-time pricing calculation, and high conversion.

---

## 🌟 Key Features

1. **Interactive TV Quantity & Configuration Flow**:
   - **Hero Quantity Selector**: 1, 2, 3, 4+ TVs with luxury silhouette artwork.
   - **Step-by-Step TV Configurator**: Screen size selection with dynamic base pricing.
   - **Visual Hardware Add-ons**: Fixed Mount ($30), Tilt Mount ($50), Full Motion Mount ($80), In-Wall Cable Plates ($75), Power Outlet Kit ($125), New Electrical Outlet ($199), Soundbar Mount ($95), LED Backlights ($45), Floating Shelf ($60), Samsung Frame Media Box ($150).
   - **Additional Hourly Services ($75/hr)**: Art mounting, furniture assembly, moving pickup, ceiling fan installation, curtain rods, and custom wall items.
   - **Customer Information Intake**: With Google Places address autocomplete support.
   - **Order Confirmation**: Clean, itemized receipt breakdown with next-steps roadmap.

2. **Real-time Live Dynamic Pricing Engine**:
   - Live pricing calculation across every stage of the configurator.
   - Floating running total pill (`Est. Total: $XXX.00`).
   - Step 4 itemized financial ledger before submission.

3. **Executive SaaS Admin Control Center**:
   - Accessible via **WordPress Admin → PTTM Platform**.
   - **Live KPI Metrics**: Total Intakes, New Leads pending follow-up, Pipeline Revenue ($), and Completed Jobs.
   - **Dynamic Repeaters**: Add, edit, price, toggle, and remove TV Add-ons, Hourly Services, TV Sizes, FAQs, and Customer Reviews with 1 click.
   - **WordPress Media Library Integration**: Direct image uploaders for all cards.

4. **CRM & Order Intake Management (`pttm_order` CPT)**:
   - One-click **Call Customer** (`tel:`), **SMS** (`sms:`), and **Google Maps Navigation**.
   - Status workflow: `⚡ New Lead` → `📞 Contacted` → `🗓️ Scheduled` → `✅ Completed` → `❌ Cancelled`.
   - Print-ready invoice generation.

5. **Modular Payment Architecture**:
   - Supports **Lead Intake (Pay upon completion)**, **Required Online Deposit**, or **Full Upfront Payment**.
   - Ready for **Stripe** (PaymentIntents API), **PayPal** (Smart Buttons), **Square** (Web Payments SDK), and offline on-site terminal payments.

6. **Page Builder & Elementor Compatibility**:
   - 8 custom Elementor widgets under the **"Prime Time TV Mounting"** category.
   - Full shortcode support (`[pttm_order_wizard]`, `[pttm_tips_hub]`, `[pttm_faq_accordion]`, `[pttm_reviews_grid]`, `[pttm_services_grid]`, `[pttm_business_history]`, `[pttm_contact_info]`).

7. **Proportional Mobile & Tablet Responsiveness**:
   - Tailored typographic and element scaling for Desktop (1440px+), Tablet (768px - 1024px), and Mobile (< 580px).

---

## 📁 Repository Structure

```
wp-content/
├── deliverables/
│   └── primetime-tv-mounting.zip     # Installable WordPress theme package
├── sql/
│   └── local.sql                     # Complete WordPress database export with sample pages & configuration
├── themes/
│   └── primetime-tv-mounting/        # Prime Time TV Mounting custom theme
│       ├── assets/
│       │   ├── css/
│       │   │   ├── main.css          # Frontend responsive styles
│       │   │   └── admin.css         # SaaS dashboard styling
│       │   ├── js/
│       │   │   ├── order-wizard.js   # Dynamic wizard & pricing controller
│       │   │   └── admin.js          # Admin repeater & media uploaders
│       │   └── images/               # SVGs, mockups, badges & preview graphics
│       ├── inc/
│       │   ├── admin-settings.php    # SaaS control center & catalog manager
│       │   ├── cpt-tips-tricks.php   # Tips & Tricks YouTube hub CPT
│       │   ├── default-data.php      # Default hardware catalog & services data
│       │   ├── elementor-widgets.php # Elementor custom widgets suite
│       │   ├── elementor/            # Individual Elementor widget classes
│       │   ├── order-management.php  # CRM intake post type & AJAX submission
│       │   ├── page-builders-shortcodes.php # Page builder shortcode integrations
│       │   ├── payment-gateways.php  # Stripe, PayPal, Square modular handlers
│       │   └── square-gateway.php    # Square Web Payments integration
│       ├── templates/
│       │   ├── template-elementor-fullwidth.php
│       │   └── template-elementor-canvas.php
│       ├── front-page.php            # Homepage TV order wizard
│       ├── page-tips-tricks.php      # YouTube video tutorials hub
│       ├── single-tips_tricks.php    # Single tutorial view
│       ├── functions.php             # Core setup & assets loader
│       ├── header.php / footer.php   # Non-disruptive navigation modals
│       └── style.css                 # Theme definition
├── .gitignore
└── README.md
```

---

## 🚀 Quick Start / Installation

### Option 1: WordPress Admin Theme Upload
1. Download `deliverables/primetime-tv-mounting.zip`.
2. In WordPress Admin, navigate to **Appearance → Themes → Add New → Upload Theme**.
3. Choose `primetime-tv-mounting.zip` and click **Install Now** → **Activate**.

### Option 2: Full Repository Clone
1. Clone this repository into your WordPress installation:
   ```bash
   git clone https://github.com/lunexiaitdev-arch/tv-installation.git wp-content
   ```
2. Activate the **Prime Time TV Mounting** theme in WordPress Admin or via WP-CLI:
   ```bash
   wp theme activate primetime-tv-mounting
   ```
3. (Optional) Import the full database state from `sql/local.sql` using WP-CLI or phpMyAdmin:
   ```bash
   wp db import sql/local.sql
   ```

---

## 📄 Pre-Configured WordPress Pages

| Page | URL Path | Description |
|---|---|---|
| **Home (Configurator)** | `/` | Interactive TV mounting configurator |
| **Tips & Tricks** | `/tips-tricks/` | YouTube tutorials hub & SEO educational blog |
| **About Us** | `/about-us/` | Business history, certifications & $1M insurance guarantee |
| **FAQ** | `/faq/` | Interactive accordion answering customer questions |
| **Customer Reviews** | `/reviews/` | Verified 5-star customer reviews & ratings |
| **Additional Services** | `/services/` | Standalone $75/hr handyman & mounting services |
| **Contact & Dispatch** | `/contact/` | Phone, hours, coverage area, and dispatch guarantee |
| **Order Confirmation** | `/order-confirmation/` | Itemized order confirmation template |

---

## 🛠️ Tech Stack

- **Platform**: WordPress 6.x
- **Theme**: Prime Time TV Mounting (Custom, no bloat)
- **Styling**: Modern Vanilla CSS (Zero heavy frameworks, ultra-fast load speed)
- **JavaScript**: Modular vanilla ES6+ with DOM caching and responsive event handling
- **Page Builders**: Native Elementor Widgets + Gutenberg Block / Shortcode Support
- **Database**: MySQL / MariaDB
