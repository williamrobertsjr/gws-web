<?php
// Content for the Ceramic Tool Holders for Turning landing page (page-ceramic-tool-holders-turning.php).
// Copy follows the Figma design "Ceramic Toolholders – Landing Page v2". Images are paths relative to
// wp-content/uploads/ and are resolved by gws_cth_upload_image(), so they come from the Media Library
// uploads folder rather than the theme. A missing file renders without its image instead of a broken
// <img>.
//
// Families: 'shape' drives the All / Round / Rhombic / Square filter. 'series' lists the
// master_series_data series each family's holders belong to (checked against master_product_data:
// CD = flat top clamp, CX = dimple top clamp, DN = top clamp for a hole); the first one is the
// card's main "View products" link.
return [
    'hero_image' => '2026/11/hero-ceramic-holders.png',
    'inserts_image' => '2026/11/mixed-inserts-scaled.jpg',

    'clamping_systems' => [
        [
            'series' => 'DN',
            'name' => 'Double-Clamp',
            'tagline' => 'For ceramic inserts with a hole',
            'description' => 'The clamp seats in the insert hole and draws it back into the pocket.',
            'holders' => ['DCLN 95° -AN', 'DDJN 93° -AN', 'DSDN 45° -AN'],
            'image' => '2026/11/clamp-double-clamp.png',
        ],
        [
            'series' => 'CD',
            'name' => 'Clamp-On',
            'tagline' => 'For flat top ceramic inserts',
            'description' => 'A top clamp with a large chip breaker plate holds flat top inserts.',
            'holders' => ['CRDN', 'CRGN', 'CRDC', 'CDJN 93°', 'CSDN 45°', 'CCKN 75°'],
            'image' => '2026/11/clamp-clamp-on.png',
        ],
        [
            'series' => 'CX',
            'name' => 'Dimple-Clamp',
            'tagline' => 'For dimple top ceramic inserts',
            'description' => 'The clamp works with traditional dimple insert designs.',
            'holders' => ['CDJN 93°', 'CSDN 45°', 'CCKN 75°'],
            'image' => '2026/11/clamp-dimple-clamp.png',
        ],
    ],

    'filters' => [
        ['slug' => 'round', 'label' => 'Round'],
        ['slug' => 'rhombic', 'label' => 'Rhombic'],
        ['slug' => 'square', 'label' => 'Square'],
    ],

    'families' => [
        [
            'code' => 'CRDN',
            'angle' => '',
            'description' => 'Profiling with round negative inserts.',
            'shape' => 'round',
            'chips' => ['Round', 'Clamp-On'],
            'coolant' => false,
            'inserts' => 'RNGN',
            'series' => ['CD'],
            'image' => '2026/11/CRDNN_tile.png',
        ],
        [
            'code' => 'CRGN',
            'angle' => '',
            'description' => 'Profiling with round inserts. Right- and left-hand models.',
            'shape' => 'round',
            'chips' => ['Round', 'Clamp-On'],
            'coolant' => false,
            'inserts' => 'RNGN',
            'series' => ['CD'],
            'image' => '2026/11/CRGNR_tile.png',
        ],
        [
            'code' => 'CRDC',
            'angle' => '',
            'description' => 'Profiling with round positive inserts.',
            'shape' => 'round',
            'chips' => ['Round', 'Clamp-On'],
            'coolant' => false,
            'inserts' => 'RCGX / RPGX',
            'series' => ['CD'],
            'image' => '2026/11/CRDCN_tile.png',
        ],
        [
            'code' => 'DCLN',
            'angle' => '95° -AN',
            'description' => 'Turning and facing with 80° rhombic inserts. Dimple lock, coolant through.',
            'shape' => 'rhombic',
            'chips' => ['80° Rhombic', 'Double-Clamp'],
            'coolant' => true,
            'inserts' => 'CNGA',
            'series' => ['DN'],
            'image' => '2026/11/DCLNR_tile.png',
        ],
        [
            'code' => 'CDJN',
            'angle' => '93°',
            'description' => 'Turning and profiling with 55° rhombic inserts.',
            'shape' => 'rhombic',
            'chips' => ['55° Rhombic', 'Clamp-On / Dimple'],
            'coolant' => false,
            'inserts' => 'DNGN / DNGX',
            'series' => ['CD', 'CX'],
            'image' => '2026/11/CDJNR_tile.png',
        ],
        [
            'code' => 'DDJN',
            'angle' => '93° -AN',
            'description' => 'Turning and profiling with 55° rhombic inserts. Dimple lock, coolant through.',
            'shape' => 'rhombic',
            'chips' => ['55° Rhombic', 'Double-Clamp'],
            'coolant' => true,
            'inserts' => 'DNGA',
            'series' => ['DN'],
            'image' => '2026/11/DDJNR_tile.png',
        ],
        [
            'code' => 'CSDN',
            'angle' => '45°',
            'description' => 'Turning and chamfering with square inserts.',
            'shape' => 'square',
            'chips' => ['Square', 'Clamp-On / Dimple'],
            'coolant' => false,
            'inserts' => 'SNGN / SNGX',
            'series' => ['CD', 'CX'],
            'image' => '2026/11/CSDNN_tile.png',
        ],
        [
            'code' => 'DSDN',
            'angle' => '45° -AN',
            'description' => 'Turning and chamfering with square inserts. Dimple lock, coolant through.',
            'shape' => 'square',
            'chips' => ['Square', 'Double-Clamp'],
            'coolant' => true,
            'inserts' => 'SNGA',
            'series' => ['DN'],
            'image' => '2026/11/DSDNNDAN_tile.png',
        ],
        [
            // CNGN/CNGX inserts are 80° rhombic (ISO "C"), so CCKN filters under Rhombic to match
            // its chip and insert code. The Figma description says "square inserts"; kept as designed.
            'code' => 'CCKN',
            'angle' => '75°',
            'description' => 'Face turning with square inserts.',
            'shape' => 'rhombic',
            'chips' => ['80° Rhombic', 'Clamp-On / Dimple'],
            'coolant' => false,
            'inserts' => 'CNGN / CNGX',
            'series' => ['CD', 'CX'],
            'image' => '2026/11/CCKNR_tile.png',
        ],
    ],

    'industries' => [
        [
            'title' => 'Aerospace engines',
            'description' => 'Turning nickel-based alloys for engine components.',
            'image' => '2026/11/aerospace_engine.jpg',
        ],
        [
            'title' => 'Space',
            'description' => 'Turning heat-resistant alloys for propulsion hardware.',
            'image' => '2026/11/space.jpg',
        ],
        [
            'title' => 'Automotive',
            'description' => 'Turning cast iron parts such as brake disks.',
            'image' => '2026/11/automotive.jpg',
        ],
        [
            'title' => 'Forging and casting',
            'description' => 'Removing scale and casting skin from forged and cast parts.',
            'image' => '2026/11/forging_casting.jpg',
        ],
        [
            'title' => 'Steel mills, roll turning and heavy industry',
            'description' => 'Turning and redressing mill rolls.',
            'image' => '2026/11/heavy_industry.jpg',
        ],
        [
            'title' => 'Energy',
            'description' => 'Turning heat-resistant alloys for turbine components.',
            'image' => '2026/11/energy.jpg',
        ],
    ],
];
