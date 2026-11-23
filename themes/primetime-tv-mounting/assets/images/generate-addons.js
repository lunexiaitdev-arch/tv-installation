const fs = require('fs');
const path = require('path');

const imgDir = '/Users/pranto/Local Sites/mockup/app/public/wp-content/themes/primetime-tv-mounting/assets/images';

const svgs = {
  'addon-fixed-mount.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Fixed Wall Plate -->
    <rect x="25" y="32" width="110" height="36" rx="3" fill="#1c1917" stroke="#44403c" stroke-width="2"/>
    <rect x="35" y="42" width="30" height="16" rx="2" fill="#0c0a09"/>
    <rect x="75" y="42" width="20" height="16" rx="2" fill="#0c0a09"/>
    <rect x="105" y="42" width="20" height="16" rx="2" fill="#0c0a09"/>
    <!-- Vertical Arms -->
    <rect x="46" y="20" width="14" height="60" rx="2" fill="#292524" stroke="#57534e" stroke-width="1.5"/>
    <circle cx="53" cy="28" r="2.5" fill="#a8a29e"/>
    <circle cx="53" cy="72" r="2.5" fill="#a8a29e"/>
    <rect x="100" y="20" width="14" height="60" rx="2" fill="#292524" stroke="#57534e" stroke-width="1.5"/>
    <circle cx="107" cy="28" r="2.5" fill="#a8a29e"/>
    <circle cx="107" cy="72" r="2.5" fill="#a8a29e"/>
  </svg>`,

  'addon-tilt-mount.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Tilt Base Plate -->
    <rect x="35" y="30" width="90" height="40" rx="3" fill="#1c1917" stroke="#44403c" stroke-width="2"/>
    <rect x="45" y="42" width="70" height="16" rx="2" fill="#0c0a09"/>
    <!-- Tilt Bracket Arms with tilt angle -->
    <g transform="rotate(-6 55 50)">
      <rect x="48" y="16" width="16" height="68" rx="2" fill="#292524" stroke="#57534e" stroke-width="1.5"/>
      <circle cx="56" cy="26" r="3" fill="#d6d3d1"/>
      <circle cx="56" cy="74" r="3" fill="#d6d3d1"/>
      <rect x="52" y="44" width="8" height="12" rx="1" fill="#78716c"/>
    </g>
    <g transform="rotate(-6 105 50)">
      <rect x="96" y="16" width="16" height="68" rx="2" fill="#292524" stroke="#57534e" stroke-width="1.5"/>
      <circle cx="104" cy="26" r="3" fill="#d6d3d1"/>
      <circle cx="104" cy="74" r="3" fill="#d6d3d1"/>
      <rect x="100" y="44" width="8" height="12" rx="1" fill="#78716c"/>
    </g>
  </svg>`,

  'addon-full-motion-mount.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Wall Base -->
    <rect x="20" y="28" width="22" height="44" rx="2" fill="#1c1917" stroke="#44403c" stroke-width="2"/>
    <!-- Articulating Arms -->
    <path d="M42 36L78 24L108 36" stroke="#292524" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M42 64L78 76L108 64" stroke="#292524" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
    <circle cx="78" cy="24" r="5" fill="#57534e"/>
    <circle cx="78" cy="76" r="5" fill="#57534e"/>
    <!-- Front Plate -->
    <rect x="106" y="20" width="34" height="60" rx="3" fill="#1c1917" stroke="#44403c" stroke-width="2"/>
    <circle cx="123" cy="50" r="6" fill="#78716c"/>
    <path d="M110 32H136M110 68H136" stroke="#57534e" stroke-width="3" stroke-linecap="round"/>
  </svg>`,

  'addon-cable-plates.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Upper Plate -->
    <rect x="36" y="16" width="38" height="68" rx="5" fill="#ffffff" stroke="#e5e5e5" stroke-width="2" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.06))"/>
    <rect x="47" y="32" width="16" height="36" rx="3" fill="#262626"/>
    <path d="M51 35V65M55 35V65M59 35V65" stroke="#404040" stroke-width="1.5"/>
    <circle cx="55" cy="24" r="2" fill="#d4d4d4"/>
    <circle cx="55" cy="76" r="2" fill="#d4d4d4"/>
    
    <!-- Lower Plate -->
    <rect x="86" y="16" width="38" height="68" rx="5" fill="#ffffff" stroke="#e5e5e5" stroke-width="2" filter="drop-shadow(0 2px 4px rgba(0,0,0,0.06))"/>
    <rect x="97" y="32" width="16" height="36" rx="3" fill="#262626"/>
    <path d="M101 35V65M105 35V65M109 35V65" stroke="#404040" stroke-width="1.5"/>
    <circle cx="105" cy="24" r="2" fill="#d4d4d4"/>
    <circle cx="105" cy="76" r="2" fill="#d4d4d4"/>
  </svg>`,

  'addon-power-outlet-kit.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- In-Wall Power Bridge Kit -->
    <rect x="38" y="16" width="46" height="68" rx="5" fill="#ffffff" stroke="#e5e5e5" stroke-width="2" filter="drop-shadow(0 4px 6px rgba(0,0,0,0.08))"/>
    <!-- Receptacles -->
    <rect x="49" y="26" width="24" height="20" rx="3" fill="#f5f5f5" stroke="#d4d4d4" stroke-width="1.5"/>
    <rect x="55" y="31" width="3" height="6" fill="#404040"/>
    <rect x="64" y="31" width="3" height="6" fill="#404040"/>
    <circle cx="61" cy="41" r="2" fill="#404040"/>
    
    <rect x="49" y="52" width="24" height="20" rx="3" fill="#f5f5f5" stroke="#d4d4d4" stroke-width="1.5"/>
    <rect x="55" y="57" width="3" height="6" fill="#404040"/>
    <rect x="64" y="57" width="3" height="6" fill="#404040"/>
    <circle cx="61" cy="67" r="2" fill="#404040"/>
    
    <!-- Companion Inlet Module -->
    <rect x="94" y="24" width="34" height="52" rx="4" fill="#fafafa" stroke="#e5e5e5" stroke-width="2"/>
    <rect x="103" y="38" width="16" height="24" rx="2" fill="#e5e5e5"/>
    <circle cx="111" cy="50" r="4" fill="#a3a3a3"/>
  </svg>`,

  'addon-new-outlet.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Standard Single Gang Duplex Wall Plate -->
    <rect x="58" y="12" width="44" height="76" rx="5" fill="#ffffff" stroke="#e5e5e5" stroke-width="2" filter="drop-shadow(0 3px 6px rgba(0,0,0,0.08))"/>
    <!-- Top Outlet -->
    <rect x="68" y="24" width="24" height="24" rx="4" fill="#fafafa" stroke="#d4d4d4" stroke-width="1.5"/>
    <rect x="74" y="30" width="3" height="7" fill="#262626"/>
    <rect x="83" y="30" width="3" height="7" fill="#262626"/>
    <path d="M78 41H82V43H78Z" fill="#262626"/>
    <!-- Center Screw -->
    <circle cx="80" cy="50" r="2" fill="#d4d4d4" stroke="#a3a3a3" stroke-width="0.5"/>
    <!-- Bottom Outlet -->
    <rect x="68" y="54" width="24" height="24" rx="4" fill="#fafafa" stroke="#d4d4d4" stroke-width="1.5"/>
    <rect x="74" y="60" width="3" height="7" fill="#262626"/>
    <rect x="83" y="60" width="3" height="7" fill="#262626"/>
    <path d="M78 71H82V73H78Z" fill="#262626"/>
  </svg>`,

  'addon-soundbar-mount.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Soundbar body -->
    <rect x="18" y="38" width="124" height="24" rx="6" fill="#171717" filter="drop-shadow(0 4px 8px rgba(0,0,0,0.3))"/>
    <rect x="22" y="41" width="116" height="18" rx="4" fill="#262626"/>
    <!-- Mesh Texture Lines -->
    <line x1="30" y1="46" x2="30" y2="54" stroke="#404040" stroke-width="1.5"/>
    <line x1="36" y1="46" x2="36" y2="54" stroke="#404040" stroke-width="1.5"/>
    <line x1="124" y1="46" x2="124" y2="54" stroke="#404040" stroke-width="1.5"/>
    <line x1="130" y1="46" x2="130" y2="54" stroke="#404040" stroke-width="1.5"/>
    <!-- Under TV Mounting Brackets -->
    <rect x="46" y="24" width="6" height="16" fill="#525252"/>
    <rect x="108" y="24" width="6" height="16" fill="#525252"/>
    <!-- Status LED -->
    <circle cx="80" cy="50" r="1.5" fill="#38bdf8"/>
  </svg>`,

  'addon-led-backlights.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Glow Effect -->
    <ellipse cx="80" cy="50" rx="55" ry="25" fill="url(#led-glow)" opacity="0.6"/>
    <!-- Strip loop ribbon -->
    <path d="M28 50C28 36 60 28 80 28C100 28 132 36 132 50C132 64 100 72 80 72C60 72 28 64 28 50Z" stroke="#60a5fa" stroke-width="4" fill="none"/>
    <path d="M28 50C28 36 60 28 80 28C100 28 132 36 132 50C132 64 100 72 80 72C60 72 28 64 28 50Z" stroke="#c084fc" stroke-width="2" fill="none"/>
    <!-- LED Diodes -->
    <circle cx="45" cy="42" r="2" fill="#ffffff"/>
    <circle cx="65" cy="32" r="2" fill="#ffffff"/>
    <circle cx="95" cy="32" r="2" fill="#ffffff"/>
    <circle cx="115" cy="42" r="2" fill="#ffffff"/>
    <circle cx="115" cy="58" r="2" fill="#ffffff"/>
    <circle cx="95" cy="68" r="2" fill="#ffffff"/>
    <circle cx="65" cy="68" r="2" fill="#ffffff"/>
    <circle cx="45" cy="58" r="2" fill="#ffffff"/>
    <defs>
      <radialGradient id="led-glow" cx="80" cy="50" r="55" gradientUnits="userSpaceOnUse">
        <stop stop-color="#38bdf8" stop-opacity="0.8"/>
        <stop offset="0.6" stop-color="#818cf8" stop-opacity="0.4"/>
        <stop offset="1" stop-color="#c084fc" stop-opacity="0"/>
      </radialGradient>
    </defs>
  </svg>`,

  'addon-floating-shelf.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Floating Dark Wood Shelf Isometric -->
    <path d="M24 46L116 34L136 44L44 56Z" fill="#3e2723" filter="drop-shadow(0 8px 12px rgba(0,0,0,0.25))"/>
    <path d="M24 46L44 56V66L24 56Z" fill="#2d1d19"/>
    <path d="M44 56L136 44V54L44 66Z" fill="#1b120f"/>
    <!-- Wood grain subtle sheen -->
    <path d="M36 48L112 38" stroke="#5d4037" stroke-width="1.5" opacity="0.6"/>
    <path d="M52 51L124 42" stroke="#5d4037" stroke-width="1.5" opacity="0.6"/>
  </svg>`,

  'addon-samsung-frame-box.svg': `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 100" width="160" height="100" fill="none">
    <!-- Samsung One Connect Frame Box -->
    <rect x="42" y="32" width="76" height="38" rx="8" fill="#f5f5f5" stroke="#e5e5e5" stroke-width="2" filter="drop-shadow(0 6px 12px rgba(0,0,0,0.1))"/>
    <rect x="46" y="35" width="68" height="32" rx="6" fill="#ffffff"/>
    <rect x="58" y="48" width="12" height="6" rx="2" fill="#e5e5e5"/>
    <rect x="74" y="48" width="12" height="6" rx="2" fill="#e5e5e5"/>
    <rect x="90" y="48" width="12" height="6" rx="2" fill="#e5e5e5"/>
    <circle cx="106" cy="40" r="1.5" fill="#3b82f6"/>
  </svg>`
};

Object.entries(svgs).forEach(([filename, content]) => {
  fs.writeFileSync(path.join(imgDir, filename), content.trim());
  console.log('Wrote ' + filename);
});
