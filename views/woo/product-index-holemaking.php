<?php
// Maps Category > Material (single) > Line Item, decomposed from the printed catalog's
// Product Index (which groups by compound material combos, e.g. "Steel, Cast Iron, HRSA").
// A line item appears under every single material its original catalog grouping covered;
// items are merged only when they share both label AND master_series_data.tool_sub_type, so
// generically-named items in different catalog sub-sections (e.g. "1 Flute Carbide" under
// Countersinks vs. under Reamers) never collapse into one button.
// Used by page-product-index.php. Built once from the print catalog; not read from it at runtime.
return [
    'category' => 'Holemaking',
    'category_slug' => 'holemaking',
    'description' => 'Holemaking tools drill, ream, and countersink to the tight tolerances aerospace, medical, and defense manufacturing demand. GWS Tool Group\'s holemaking line spans standard jobber drills through aircraft-specific tooling built for exacting applications.',
    'materials' => [
        [
            'material' => 'Steel',
            'material_slug' => 'steel',
            'items' => [
                [
                    'label' => '2 Flute Carbide Spot',
                    'slug' => '2-flute-carbide-spot',
                    'series' => [
                        '402',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '331',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide',
                    'slug' => '3-flute-carbide',
                    'series' => [
                        '333',
                    ],
                ],
                [
                    'label' => '4 Flute Carbide',
                    'slug' => '4-flute-carbide',
                    'series' => [
                        '334',
                    ],
                ],
                [
                    'label' => '6 Flute Carbide',
                    'slug' => '6-flute-carbide',
                    'series' => [
                        '336',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Screw Machine',
                    'slug' => '2-flute-carbide-screw-machine',
                    'series' => [
                        '460',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Spade',
                    'slug' => '2-flute-carbide-spade',
                    'series' => [
                        '400',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Straight',
                    'slug' => '2-flute-carbide-straight',
                    'series' => [
                        '470',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Twist',
                    'slug' => '2-flute-carbide-twist',
                    'series' => [
                        '450',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide Twist',
                    'slug' => '3-flute-carbide-twist',
                    'series' => [
                        '453',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '300',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '4001',
                        '4005',
                        '4105',
                        '4060',
                        '114',
                        '118',
                        '293',
                        '294',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '4050',
                    ],
                ],
                [
                    'label' => 'Lock-Down Flat',
                    'slug' => 'lock-down-flat',
                    'series' => [
                        '601',
                    ],
                ],
                [
                    'label' => '4-6 Flute Slow Spiral',
                    'slug' => '4-6-flute-slow-spiral',
                    'series' => [
                        '550',
                    ],
                ],
                [
                    'label' => '4-6 Flute Straight',
                    'slug' => '4-6-flute-straight',
                    'series' => [
                        '500',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Stainless',
            'material_slug' => 'stainless',
            'items' => [
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '4001',
                        '4005',
                        '4105',
                        '4060',
                        '114',
                        '118',
                        '293',
                        '294',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '4050',
                    ],
                ],
                [
                    'label' => 'Lock-Down Flat',
                    'slug' => 'lock-down-flat',
                    'series' => [
                        '601',
                    ],
                ],
                [
                    'label' => '4-6 Flute Slow Spiral',
                    'slug' => '4-6-flute-slow-spiral',
                    'series' => [
                        '550',
                    ],
                ],
                [
                    'label' => '4-6 Flute Straight',
                    'slug' => '4-6-flute-straight',
                    'series' => [
                        '500',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Cast Iron',
            'material_slug' => 'cast-iron',
            'items' => [
                [
                    'label' => '2 Flute Carbide Spot',
                    'slug' => '2-flute-carbide-spot',
                    'series' => [
                        '402',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '331',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide',
                    'slug' => '3-flute-carbide',
                    'series' => [
                        '333',
                    ],
                ],
                [
                    'label' => '4 Flute Carbide',
                    'slug' => '4-flute-carbide',
                    'series' => [
                        '334',
                    ],
                ],
                [
                    'label' => '6 Flute Carbide',
                    'slug' => '6-flute-carbide',
                    'series' => [
                        '336',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Screw Machine',
                    'slug' => '2-flute-carbide-screw-machine',
                    'series' => [
                        '460',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Spade',
                    'slug' => '2-flute-carbide-spade',
                    'series' => [
                        '400',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Straight',
                    'slug' => '2-flute-carbide-straight',
                    'series' => [
                        '470',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Twist',
                    'slug' => '2-flute-carbide-twist',
                    'series' => [
                        '450',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide Twist',
                    'slug' => '3-flute-carbide-twist',
                    'series' => [
                        '453',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '300',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '4001',
                        '4005',
                        '4105',
                        '4060',
                        '114',
                        '118',
                        '293',
                        '294',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '4050',
                    ],
                ],
                [
                    'label' => 'Lock-Down Flat',
                    'slug' => 'lock-down-flat',
                    'series' => [
                        '601',
                    ],
                ],
                [
                    'label' => '4-6 Flute Slow Spiral',
                    'slug' => '4-6-flute-slow-spiral',
                    'series' => [
                        '550',
                    ],
                ],
                [
                    'label' => '4-6 Flute Straight',
                    'slug' => '4-6-flute-straight',
                    'series' => [
                        '500',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Non-Ferrous',
            'material_slug' => 'non-ferrous',
            'items' => [
                [
                    'label' => 'Piloted',
                    'slug' => 'piloted',
                    'series' => [
                        '4215',
                    ],
                ],
                [
                    'label' => '2 Flute 8-Facet',
                    'slug' => '2-flute-8-facet',
                    'series' => [
                        '4221',
                    ],
                ],
                [
                    'label' => '1 Flute Dagger',
                    'slug' => '1-flute-dagger',
                    'series' => [
                        '4205',
                        '4206',
                    ],
                ],
                [
                    'label' => '2 Flute 4-Facet',
                    'slug' => '2-flute-4-facet',
                    'series' => [
                        '4210',
                        '4211',
                    ],
                ],
                [
                    'label' => '4 Flute Dreamer',
                    'slug' => '4-flute-dreamer',
                    'series' => [
                        '4207',
                        '4208',
                    ],
                ],
                [
                    'label' => 'Carbide Body, HSS Pilots',
                    'slug' => 'carbide-body-hss-pilots',
                    'series' => [
                        '4220',
                    ],
                ],
                [
                    'label' => 'HSS Body, HSS Pilots',
                    'slug' => 'hss-body-hss-pilots',
                    'series' => [
                        '4219',
                    ],
                ],
                [
                    'label' => 'Brazed Carbide',
                    'slug' => 'brazed-carbide',
                    'series' => [
                        '4218',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Spot',
                    'slug' => '2-flute-carbide-spot',
                    'series' => [
                        '402',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '331',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide',
                    'slug' => '3-flute-carbide',
                    'series' => [
                        '333',
                    ],
                ],
                [
                    'label' => '4 Flute Carbide',
                    'slug' => '4-flute-carbide',
                    'series' => [
                        '334',
                    ],
                ],
                [
                    'label' => '6 Flute Carbide',
                    'slug' => '6-flute-carbide',
                    'series' => [
                        '336',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Screw Machine',
                    'slug' => '2-flute-carbide-screw-machine',
                    'series' => [
                        '460',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Spade',
                    'slug' => '2-flute-carbide-spade',
                    'series' => [
                        '400',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Straight',
                    'slug' => '2-flute-carbide-straight',
                    'series' => [
                        '470',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Twist',
                    'slug' => '2-flute-carbide-twist',
                    'series' => [
                        '450',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide Twist',
                    'slug' => '3-flute-carbide-twist',
                    'series' => [
                        '453',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '300',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '4005',
                        '4105',
                        '114',
                        '118',
                        '293',
                        '294',
                    ],
                ],
                [
                    'label' => 'Lock-Down Flat',
                    'slug' => 'lock-down-flat',
                    'series' => [
                        '601',
                    ],
                ],
                [
                    'label' => '4-6 Flute Slow Spiral',
                    'slug' => '4-6-flute-slow-spiral',
                    'series' => [
                        '550',
                    ],
                ],
                [
                    'label' => '4-6 Flute Straight',
                    'slug' => '4-6-flute-straight',
                    'series' => [
                        '500',
                    ],
                ],
            ],
        ],
        [
            'material' => 'HRSA',
            'material_slug' => 'hrsa',
            'items' => [
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '4060',
                        '114',
                        '118',
                        '293',
                        '294',
                    ],
                ],
                [
                    'label' => 'Lock-Down Flat',
                    'slug' => 'lock-down-flat',
                    'series' => [
                        '601',
                    ],
                ],
                [
                    'label' => '4-6 Flute Slow Spiral',
                    'slug' => '4-6-flute-slow-spiral',
                    'series' => [
                        '550',
                    ],
                ],
                [
                    'label' => '4-6 Flute Straight',
                    'slug' => '4-6-flute-straight',
                    'series' => [
                        '500',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Hardened Steel',
            'material_slug' => 'hardened-steel',
            'items' => [
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '331',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide',
                    'slug' => '3-flute-carbide',
                    'series' => [
                        '333',
                    ],
                ],
                [
                    'label' => '4 Flute Carbide',
                    'slug' => '4-flute-carbide',
                    'series' => [
                        '334',
                    ],
                ],
                [
                    'label' => '6 Flute Carbide',
                    'slug' => '6-flute-carbide',
                    'series' => [
                        '336',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Screw Machine',
                    'slug' => '2-flute-carbide-screw-machine',
                    'series' => [
                        '460',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Spade',
                    'slug' => '2-flute-carbide-spade',
                    'series' => [
                        '400',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Straight',
                    'slug' => '2-flute-carbide-straight',
                    'series' => [
                        '470',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide Twist',
                    'slug' => '2-flute-carbide-twist',
                    'series' => [
                        '450',
                    ],
                ],
                [
                    'label' => '3 Flute Carbide Twist',
                    'slug' => '3-flute-carbide-twist',
                    'series' => [
                        '453',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '300',
                    ],
                ],
                [
                    'label' => '2 Flute Carbide',
                    'slug' => '2-flute-carbide',
                    'series' => [
                        '4001',
                        '4060',
                        '114',
                        '118',
                        '293',
                        '294',
                    ],
                ],
                [
                    'label' => '1 Flute Carbide',
                    'slug' => '1-flute-carbide',
                    'series' => [
                        '4050',
                    ],
                ],
                [
                    'label' => 'Lock-Down Flat',
                    'slug' => 'lock-down-flat',
                    'series' => [
                        '601',
                    ],
                ],
                [
                    'label' => '4-6 Flute Slow Spiral',
                    'slug' => '4-6-flute-slow-spiral',
                    'series' => [
                        '550',
                    ],
                ],
                [
                    'label' => '4-6 Flute Straight',
                    'slug' => '4-6-flute-straight',
                    'series' => [
                        '500',
                    ],
                ],
            ],
        ],
    ],
];
