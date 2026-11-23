const fs = require('fs');
const path = require('path');

const imgDir = '/Users/pranto/Local Sites/mockup/app/public/wp-content/themes/primetime-tv-mounting/assets/images';

const services = {
  'service-art-mounting.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Frame 1 Left -->
    <rect x="22" y="20" width="34" height="52" rx="2" fill="#fff" stroke="#8d6e63" stroke-width="2.5" filter="drop-shadow(0 4px 6px rgba(0,0,0,0.08))"/>
    <rect x="26" y="24" width="26" height="44" fill="#faf8f5"/>
    <circle cx="39" cy="40" r="8" fill="#d7ccc8"/>
    
    <!-- Frame 2 Center -->
    <rect x="62" y="16" width="36" height="56" rx="2" fill="#fff" stroke="#3e2723" stroke-width="2.5" filter="drop-shadow(0 6px 8px rgba(0,0,0,0.12))"/>
    <rect x="66" y="20" width="28" height="48" fill="#faf8f5"/>
    <!-- Leaf / Botanical Line art -->
    <path d="M80 60C80 44 80 32 80 26M80 34C84 32 87 35 87 38C87 41 82 43 80 43M80 42C76 40 73 43 73 46C73 49 78 51 80 51M80 49C84 47 87 50 87 53" stroke="#262626" stroke-width="1.5" stroke-linecap="round"/>

    <!-- Frame 3 Right -->
    <rect x="104" y="22" width="34" height="50" rx="2" fill="#fff" stroke="#8d6e63" stroke-width="2.5" filter="drop-shadow(0 4px 6px rgba(0,0,0,0.08))"/>
    <rect x="108" y="26" width="26" height="42" fill="#faf8f5"/>
    <path d="M112 56L121 44L128 52L132 48" stroke="#a1887f" stroke-width="2" stroke-linecap="round"/>
  </svg>`,

  'service-furniture-assembly.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Dresser Cabinet Body -->
    <rect x="24" y="24" width="112" height="50" rx="3" fill="#d7b899" stroke="#b08d68" stroke-width="2" filter="drop-shadow(0 8px 12px rgba(0,0,0,0.12))"/>
    <!-- Drawers Grid -->
    <line x1="80" y1="24" x2="80" y2="74" stroke="#b08d68" stroke-width="1.5"/>
    <line x1="24" y1="40" x2="136" y2="40" stroke="#b08d68" stroke-width="1.5"/>
    <line x1="24" y1="57" x2="136" y2="57" stroke="#b08d68" stroke-width="1.5"/>
    <!-- Handles -->
    <line x1="44" y1="32" x2="60" y2="32" stroke="#1c1917" stroke-width="2" stroke-linecap="round"/>
    <line x1="100" y1="32" x2="116" y2="32" stroke="#1c1917" stroke-width="2" stroke-linecap="round"/>
    <line x1="44" y1="48" x2="60" y2="48" stroke="#1c1917" stroke-width="2" stroke-linecap="round"/>
    <line x1="100" y1="48" x2="116" y2="48" stroke="#1c1917" stroke-width="2" stroke-linecap="round"/>
    <line x1="44" y1="65" x2="60" y2="65" stroke="#1c1917" stroke-width="2" stroke-linecap="round"/>
    <line x1="100" y1="65" x2="116" y2="65" stroke="#1c1917" stroke-width="2" stroke-linecap="round"/>
    <!-- Legs -->
    <rect x="28" y="74" width="4" height="10" fill="#1c1917"/>
    <rect x="128" y="74" width="4" height="10" fill="#1c1917"/>
    <rect x="78" y="74" width="4" height="10" fill="#1c1917"/>
  </svg>`,

  'service-moving-pickup.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Pickup Truck -->
    <path d="M22 56L26 44L44 42L66 32L106 32L116 44L142 46C144 46 146 48 146 50L144 68L22 68Z" fill="#ffffff" stroke="#262626" stroke-width="2" filter="drop-shadow(0 6px 8px rgba(0,0,0,0.15))"/>
    <!-- Windows -->
    <path d="M68 36L92 36L90 44L68 44Z" fill="#38bdf8" fill-opacity="0.5"/>
    <path d="M96 36L112 44H94V36Z" fill="#38bdf8" fill-opacity="0.5"/>
    <!-- Truck Bed Opening -->
    <line x1="28" y1="44" x2="64" y2="44" stroke="#171717" stroke-width="2"/>
    <!-- Wheels -->
    <circle cx="48" cy="68" r="12" fill="#1c1917"/>
    <circle cx="48" cy="68" r="5" fill="#a8a29e"/>
    <circle cx="122" cy="68" r="12" fill="#1c1917"/>
    <circle cx="122" cy="68" r="5" fill="#a8a29e"/>
    <!-- Headlight -->
    <path d="M142 50L145 52L144 58H140V50Z" fill="#facc15"/>
  </svg>`,

  'service-moving-uhaul.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- U-Haul Box Truck -->
    <!-- Box Body -->
    <rect x="20" y="24" width="84" height="42" rx="2" fill="#ffffff" stroke="#262626" stroke-width="2" filter="drop-shadow(0 6px 8px rgba(0,0,0,0.15))"/>
    <!-- U-Haul Orange Banner -->
    <rect x="21" y="32" width="82" height="12" fill="#ea580c"/>
    <text x="62" y="41" text-anchor="middle" font-family="sans-serif" font-weight="900" font-size="9" fill="#ffffff" letter-spacing="1">U-HAUL</text>
    <!-- Cab -->
    <path d="M104 38L122 38L134 50L138 52V66H104V38Z" fill="#ffffff" stroke="#262626" stroke-width="2"/>
    <!-- Cab Window -->
    <path d="M110 42H122L128 50H110V42Z" fill="#38bdf8" fill-opacity="0.5"/>
    <!-- Wheels -->
    <circle cx="42" cy="66" r="10" fill="#1c1917"/>
    <circle cx="42" cy="66" r="4" fill="#a8a29e"/>
    <circle cx="120" cy="66" r="10" fill="#1c1917"/>
    <circle cx="120" cy="66" r="4" fill="#a8a29e"/>
  </svg>`,

  'service-ceiling-fan.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Downrod & Canopy -->
    <rect x="77" y="12" width="6" height="24" fill="#1c1917"/>
    <path d="M72 12H88V16H72Z" fill="#1c1917"/>
    <!-- Fan Blades (Wood finish) -->
    <path d="M80 36L20 48L24 56L80 40Z" fill="#78350f" stroke="#451a03" stroke-width="1.5"/>
    <path d="M80 36L140 48L136 56L80 40Z" fill="#78350f" stroke="#451a03" stroke-width="1.5"/>
    <path d="M80 36L44 26L48 20L80 34Z" fill="#92400e" stroke="#451a03" stroke-width="1.5"/>
    <path d="M80 36L116 26L112 20L80 34Z" fill="#92400e" stroke="#451a03" stroke-width="1.5"/>
    <!-- Center Motor Housing -->
    <circle cx="80" cy="38" r="14" fill="#262626"/>
    <!-- Light Dome -->
    <ellipse cx="80" cy="44" rx="10" ry="7" fill="#fef08a" stroke="#ca8a04" stroke-width="1"/>
  </svg>`,

  'service-curtain-rods.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Window Frame -->
    <rect x="42" y="32" width="76" height="56" fill="#f5f5f5" stroke="#e5e5e5" stroke-width="1.5"/>
    <!-- Curtain Rod & Finials -->
    <line x1="24" y1="26" x2="136" y2="26" stroke="#1c1917" stroke-width="4" stroke-linecap="round"/>
    <circle cx="24" cy="26" r="4.5" fill="#1c1917"/>
    <circle cx="136" cy="26" r="4.5" fill="#1c1917"/>
    <rect x="38" y="24" width="4" height="10" fill="#1c1917"/>
    <rect x="118" y="24" width="4" height="10" fill="#1c1917"/>
    <!-- Hanging Drapes (Soft beige fabric ripples) -->
    <path d="M34 26C34 26 38 48 36 84H56C54 48 58 26 58 26Z" fill="#e7dfd5" stroke="#d5c8ba" stroke-width="1"/>
    <path d="M102 26C102 26 106 48 104 84H124C122 48 126 26 126 26Z" fill="#e7dfd5" stroke="#d5c8ba" stroke-width="1"/>
  </svg>`,

  'service-other-items.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Top Shelf -->
    <rect x="36" y="36" width="88" height="6" rx="2" fill="#a16207" filter="drop-shadow(0 4px 6px rgba(0,0,0,0.1))"/>
    <!-- Potted Plant on top shelf -->
    <rect x="48" y="22" width="14" height="14" rx="2" fill="#e5e5e5" stroke="#d4d4d4"/>
    <path d="M55 22C50 14 42 16 46 8C52 14 55 22 55 22Z" fill="#15803d"/>
    <path d="M55 22C60 14 68 16 64 8C58 14 55 22 55 22Z" fill="#16a34a"/>
    <!-- Mini Frame on top shelf -->
    <rect x="74" y="18" width="16" height="18" rx="1" fill="#fff" stroke="#44403c" stroke-width="1.5"/>

    <!-- Bottom Shelf -->
    <rect x="44" y="68" width="76" height="6" rx="2" fill="#a16207" filter="drop-shadow(0 4px 6px rgba(0,0,0,0.1))"/>
    <!-- Books on bottom shelf -->
    <rect x="56" y="52" width="6" height="16" fill="#0369a1"/>
    <rect x="63" y="54" width="5" height="14" fill="#b91c1c"/>
    <rect x="69" y="50" width="6" height="18" fill="#4d7c0f"/>
    <!-- Ceramic Vase on bottom shelf -->
    <path d="M96 68C92 68 90 62 94 56H102C106 62 104 68 100 68Z" fill="#fed7aa"/>
  </svg>`
};

Object.entries(services).forEach(([filename, content]) => {
  fs.writeFileSync(path.join(imgDir, filename), content.trim());
  console.log('Wrote ' + filename);
});
