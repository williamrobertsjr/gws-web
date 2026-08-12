<?php
// Maps Category > Material (single) > Line Item, decomposed from the printed catalog's
// Product Index (which groups by compound material combos, e.g. "Steel, Cast Iron, HRSA").
// A line item appears under every single material its original catalog grouping covered;
// items are merged only when they share both label AND master_series_data.tool_sub_type, so
// generically-named items in different catalog sub-sections never collapse into one button.
// Threading also has line items with no material at all in the print catalog (Plug and Ring
// Gages, Tap Extensions, Tapping Fluid) -- these live under a synthetic "General" material.
// Used by page-product-index.php. Built once from the print catalog; not read from it at runtime.
return [
    'category' => 'Threading',
    'category_slug' => 'threading',
    'description' => 'Threading tools cut, form, and gage precision threads across materials from soft aluminum to hardened steel. GWS Tool Group\'s threading line covers taps, thread mills, dies, and the gages and extensions that support them.',
    'materials' => [
        [
            'material' => 'Steel',
            'material_slug' => 'steel',
            'items' => [
                [
                    'label' => 'Carbide Inserted',
                    'slug' => 'carbide-inserted',
                    'series' => [
                        'BXCI',
                        'BXCID',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap (Advanced Performance)',
                    'slug' => 'hss-forming-tap-advanced-performance',
                    'series' => [
                        'BXS',
                        'BXHP',
                        'BXDC',
                        'BXSS',
                        'BXDIN',
                    ],
                ],
                [
                    'label' => 'Cleanout Tap',
                    'slug' => 'cleanout-tap',
                    'series' => [
                        'BX800',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap',
                    'slug' => 'hss-forming-tap',
                    'series' => [
                        'BXM',
                        'BXPT',
                        'BXPS',
                        'BXB',
                        'BXOTL',
                        'BXSTI',
                        'BXIL',
                        'BX3',
                        'BX4',
                        'BXP',
                        'BX6',
                        '104',
                        '104M',
                    ],
                ],
                [
                    'label' => 'HSS Pipe Tap',
                    'slug' => 'hss-pipe-tap',
                    'series' => [
                        'BX710',
                        '131',
                        '131F',
                        '131I',
                        '132',
                        '133',
                        '134',
                        '135',
                        '136',
                        '136P',
                        '137',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Flute Tap',
                    'slug' => 'hss-spiral-flute-tap',
                    'series' => [
                        'BX300',
                        'BX220',
                        '107',
                        '107M',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Point Tap',
                    'slug' => 'hss-spiral-point-tap',
                    'series' => [
                        'BX100',
                        'BX170',
                        'BX200',
                        '165',
                        '167',
                        '105',
                        '105L',
                        '105M',
                        '109M',
                    ],
                ],
                [
                    'label' => 'Multi-Flute HSS',
                    'slug' => 'multi-flute-hss',
                    'series' => [
                        '156',
                        '156M',
                        '157',
                        '158',
                        '158M',
                    ],
                ],
                [
                    'label' => 'HSS Round',
                    'slug' => 'hss-round',
                    'series' => [
                        '141',
                        '141P',
                        '141M',
                        '142',
                        '142M',
                        '143',
                    ],
                ],
                [
                    'label' => 'Carbide Hand Tap',
                    'slug' => 'carbide-hand-tap',
                    'series' => [
                        '126',
                        '126M',
                    ],
                ],
                [
                    'label' => 'Carbide Pipe Tap',
                    'slug' => 'carbide-pipe-tap',
                    'series' => [
                        '127',
                    ],
                ],
                [
                    'label' => 'Carbide Spiral Point Tap',
                    'slug' => 'carbide-spiral-point-tap',
                    'series' => [
                        '128',
                        '128M',
                    ],
                ],
                [
                    'label' => 'HSS Conduit Tap',
                    'slug' => 'hss-conduit-tap',
                    'series' => [
                        '170C',
                    ],
                ],
                [
                    'label' => 'HSS Hand Tap',
                    'slug' => 'hss-hand-tap',
                    'series' => [
                        '101',
                        '101M',
                        '101L',
                        '103',
                        '164',
                        '164M',
                        '166',
                        '168',
                        '168T',
                        '169',
                        '169T',
                        '108M',
                    ],
                ],
                [
                    'label' => 'Single Point',
                    'slug' => 'single-point',
                    'series' => [
                        '187',
                    ],
                ],
                [
                    'label' => 'LHC/LHS',
                    'slug' => 'lhc-lhs',
                    'series' => [
                        '189',
                        '189M',
                    ],
                ],
                [
                    'label' => 'Helical Flute',
                    'slug' => 'helical-flute',
                    'series' => [
                        '180',
                        '180M',
                        '182',
                        '181',
                        '181M',
                        '184',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Stainless',
            'material_slug' => 'stainless',
            'items' => [
                [
                    'label' => 'Carbide Inserted',
                    'slug' => 'carbide-inserted',
                    'series' => [
                        'BXCI',
                        'BXCID',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap (Advanced Performance)',
                    'slug' => 'hss-forming-tap-advanced-performance',
                    'series' => [
                        'BXS',
                        'BXHP',
                        'BXDC',
                        'BXSS',
                        'BXDIN',
                    ],
                ],
                [
                    'label' => 'Cleanout Tap',
                    'slug' => 'cleanout-tap',
                    'series' => [
                        'BX800',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap',
                    'slug' => 'hss-forming-tap',
                    'series' => [
                        'BXM',
                        'BXPT',
                        'BXPS',
                        'BXB',
                        'BXOTL',
                        'BXSTI',
                        'BXIL',
                        'BX3',
                        'BX4',
                        'BXP',
                        'BX6',
                    ],
                ],
                [
                    'label' => 'HSS Pipe Tap',
                    'slug' => 'hss-pipe-tap',
                    'series' => [
                        'BX710',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Flute Tap',
                    'slug' => 'hss-spiral-flute-tap',
                    'series' => [
                        'BX300',
                        'BX220',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Point Tap',
                    'slug' => 'hss-spiral-point-tap',
                    'series' => [
                        'BX100',
                        'BX170',
                        'BX200',
                    ],
                ],
                [
                    'label' => 'Single Point',
                    'slug' => 'single-point',
                    'series' => [
                        '187',
                    ],
                ],
                [
                    'label' => 'LHC/LHS',
                    'slug' => 'lhc-lhs',
                    'series' => [
                        '189',
                        '189M',
                    ],
                ],
                [
                    'label' => 'Helical Flute',
                    'slug' => 'helical-flute',
                    'series' => [
                        '180',
                        '180M',
                        '182',
                        '181',
                        '181M',
                        '184',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Cast Iron',
            'material_slug' => 'cast-iron',
            'items' => [
                [
                    'label' => 'HSS Pipe Tap',
                    'slug' => 'hss-pipe-tap',
                    'series' => [
                        'BX700',
                        '131',
                        '131F',
                        '131I',
                        '132',
                        '133',
                        '134',
                        '135',
                        '136',
                        '136P',
                        '137',
                    ],
                ],
                [
                    'label' => 'HSS Straight Flute Tap',
                    'slug' => 'hss-straight-flute-tap',
                    'series' => [
                        'BX600',
                    ],
                ],
                [
                    'label' => 'Multi-Flute HSS',
                    'slug' => 'multi-flute-hss',
                    'series' => [
                        '156',
                        '156M',
                        '157',
                        '158',
                        '158M',
                    ],
                ],
                [
                    'label' => 'HSS Round',
                    'slug' => 'hss-round',
                    'series' => [
                        '141',
                        '141P',
                        '141M',
                        '142',
                        '142M',
                        '143',
                    ],
                ],
                [
                    'label' => 'Carbide Hand Tap',
                    'slug' => 'carbide-hand-tap',
                    'series' => [
                        '126',
                        '126M',
                    ],
                ],
                [
                    'label' => 'Carbide Pipe Tap',
                    'slug' => 'carbide-pipe-tap',
                    'series' => [
                        '127',
                    ],
                ],
                [
                    'label' => 'Carbide Spiral Point Tap',
                    'slug' => 'carbide-spiral-point-tap',
                    'series' => [
                        '128',
                        '128M',
                    ],
                ],
                [
                    'label' => 'HSS Conduit Tap',
                    'slug' => 'hss-conduit-tap',
                    'series' => [
                        '170C',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap',
                    'slug' => 'hss-forming-tap',
                    'series' => [
                        '104',
                        '104M',
                    ],
                ],
                [
                    'label' => 'HSS Hand Tap',
                    'slug' => 'hss-hand-tap',
                    'series' => [
                        '101',
                        '101M',
                        '101L',
                        '103',
                        '164',
                        '164M',
                        '166',
                        '168',
                        '168T',
                        '169',
                        '169T',
                        '108M',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Flute Tap',
                    'slug' => 'hss-spiral-flute-tap',
                    'series' => [
                        '107',
                        '107M',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Point Tap',
                    'slug' => 'hss-spiral-point-tap',
                    'series' => [
                        '165',
                        '167',
                        '105',
                        '105L',
                        '105M',
                        '109M',
                    ],
                ],
                [
                    'label' => 'Single Point',
                    'slug' => 'single-point',
                    'series' => [
                        '187',
                    ],
                ],
                [
                    'label' => 'LHC/LHS',
                    'slug' => 'lhc-lhs',
                    'series' => [
                        '189',
                        '189M',
                    ],
                ],
                [
                    'label' => 'Helical Flute',
                    'slug' => 'helical-flute',
                    'series' => [
                        '180',
                        '180M',
                        '182',
                        '181',
                        '181M',
                        '184',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Non-Ferrous',
            'material_slug' => 'non-ferrous',
            'items' => [
                [
                    'label' => 'Helical Flute',
                    'slug' => 'helical-flute',
                    'series' => [
                        '185',
                        '186',
                        '186M',
                        '180',
                        '180M',
                        '182',
                        '181',
                        '181M',
                        '184',
                    ],
                ],
                [
                    'label' => 'HSS Pipe Tap',
                    'slug' => 'hss-pipe-tap',
                    'series' => [
                        'BX700',
                        'BX710',
                        '131',
                        '131F',
                        '131I',
                        '132',
                        '133',
                        '134',
                        '135',
                        '136',
                        '136P',
                        '137',
                    ],
                ],
                [
                    'label' => 'HSS Straight Flute Tap',
                    'slug' => 'hss-straight-flute-tap',
                    'series' => [
                        'BX600',
                    ],
                ],
                [
                    'label' => 'Carbide Inserted',
                    'slug' => 'carbide-inserted',
                    'series' => [
                        'BXCI',
                        'BXCID',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap (Advanced Performance)',
                    'slug' => 'hss-forming-tap-advanced-performance',
                    'series' => [
                        'BXS',
                        'BXHP',
                        'BXDC',
                        'BXSS',
                        'BXDIN',
                    ],
                ],
                [
                    'label' => 'Cleanout Tap',
                    'slug' => 'cleanout-tap',
                    'series' => [
                        'BX800',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap',
                    'slug' => 'hss-forming-tap',
                    'series' => [
                        'BXM',
                        'BXPT',
                        'BXPS',
                        'BXB',
                        'BXOTL',
                        'BXSTI',
                        'BXIL',
                        'BX3',
                        'BX4',
                        'BXP',
                        'BX6',
                        '104',
                        '104M',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Flute Tap',
                    'slug' => 'hss-spiral-flute-tap',
                    'series' => [
                        'BX300',
                        'BX220',
                        '107',
                        '107M',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Point Tap',
                    'slug' => 'hss-spiral-point-tap',
                    'series' => [
                        'BX100',
                        'BX170',
                        'BX200',
                        '165',
                        '167',
                        '105',
                        '105L',
                        '105M',
                        '109M',
                    ],
                ],
                [
                    'label' => 'Multi-Flute HSS',
                    'slug' => 'multi-flute-hss',
                    'series' => [
                        '156',
                        '156M',
                        '157',
                        '158',
                        '158M',
                    ],
                ],
                [
                    'label' => 'HSS Round',
                    'slug' => 'hss-round',
                    'series' => [
                        '141',
                        '141P',
                        '141M',
                        '142',
                        '142M',
                        '143',
                    ],
                ],
                [
                    'label' => 'Carbide Hand Tap',
                    'slug' => 'carbide-hand-tap',
                    'series' => [
                        '126',
                        '126M',
                    ],
                ],
                [
                    'label' => 'Carbide Pipe Tap',
                    'slug' => 'carbide-pipe-tap',
                    'series' => [
                        '127',
                    ],
                ],
                [
                    'label' => 'Carbide Spiral Point Tap',
                    'slug' => 'carbide-spiral-point-tap',
                    'series' => [
                        '128',
                        '128M',
                    ],
                ],
                [
                    'label' => 'HSS Conduit Tap',
                    'slug' => 'hss-conduit-tap',
                    'series' => [
                        '170C',
                    ],
                ],
                [
                    'label' => 'HSS Hand Tap',
                    'slug' => 'hss-hand-tap',
                    'series' => [
                        '101',
                        '101M',
                        '101L',
                        '103',
                        '164',
                        '164M',
                        '166',
                        '168',
                        '168T',
                        '169',
                        '169T',
                        '108M',
                    ],
                ],
            ],
        ],
        [
            'material' => 'HRSA',
            'material_slug' => 'hrsa',
            'items' => [
                [
                    'label' => 'LHC/LHS',
                    'slug' => 'lhc-lhs',
                    'series' => [
                        '189',
                        '189M',
                    ],
                ],
                [
                    'label' => 'Helical Flute',
                    'slug' => 'helical-flute',
                    'series' => [
                        '181',
                        '181M',
                        '184',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Hardened Steel',
            'material_slug' => 'hardened-steel',
            'items' => [
                [
                    'label' => 'Multi-Flute HSS',
                    'slug' => 'multi-flute-hss',
                    'series' => [
                        '156',
                        '156M',
                        '157',
                        '158',
                        '158M',
                    ],
                ],
                [
                    'label' => 'HSS Round',
                    'slug' => 'hss-round',
                    'series' => [
                        '141',
                        '141P',
                        '141M',
                        '142',
                        '142M',
                        '143',
                    ],
                ],
                [
                    'label' => 'Carbide Hand Tap',
                    'slug' => 'carbide-hand-tap',
                    'series' => [
                        '126',
                        '126M',
                    ],
                ],
                [
                    'label' => 'Carbide Pipe Tap',
                    'slug' => 'carbide-pipe-tap',
                    'series' => [
                        '127',
                    ],
                ],
                [
                    'label' => 'Carbide Spiral Point Tap',
                    'slug' => 'carbide-spiral-point-tap',
                    'series' => [
                        '128',
                        '128M',
                    ],
                ],
                [
                    'label' => 'HSS Conduit Tap',
                    'slug' => 'hss-conduit-tap',
                    'series' => [
                        '170C',
                    ],
                ],
                [
                    'label' => 'HSS Forming Tap',
                    'slug' => 'hss-forming-tap',
                    'series' => [
                        '104',
                        '104M',
                    ],
                ],
                [
                    'label' => 'HSS Hand Tap',
                    'slug' => 'hss-hand-tap',
                    'series' => [
                        '101',
                        '101M',
                        '101L',
                        '103',
                        '164',
                        '164M',
                        '166',
                        '168',
                        '168T',
                        '169',
                        '169T',
                        '108M',
                    ],
                ],
                [
                    'label' => 'HSS Pipe Tap',
                    'slug' => 'hss-pipe-tap',
                    'series' => [
                        '131',
                        '131F',
                        '131I',
                        '132',
                        '133',
                        '134',
                        '135',
                        '136',
                        '136P',
                        '137',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Flute Tap',
                    'slug' => 'hss-spiral-flute-tap',
                    'series' => [
                        '107',
                        '107M',
                    ],
                ],
                [
                    'label' => 'HSS Spiral Point Tap',
                    'slug' => 'hss-spiral-point-tap',
                    'series' => [
                        '165',
                        '167',
                        '105',
                        '105L',
                        '105M',
                        '109M',
                    ],
                ],
                [
                    'label' => 'Single Point',
                    'slug' => 'single-point',
                    'series' => [
                        '187',
                    ],
                ],
                [
                    'label' => 'LHC/LHS',
                    'slug' => 'lhc-lhs',
                    'series' => [
                        '189',
                        '189M',
                    ],
                ],
                [
                    'label' => 'Helical Flute',
                    'slug' => 'helical-flute',
                    'series' => [
                        '180',
                        '180M',
                        '182',
                        '181',
                        '181M',
                        '184',
                    ],
                ],
            ],
        ],
        [
            'material' => 'General',
            'material_slug' => 'general',
            'items' => [
                [
                    'label' => 'HSS Thread Plug Gage',
                    'slug' => 'hss-thread-plug-gage',
                    'series' => [
                        '195',
                        '195M',
                        '190',
                        '190M',
                        '191',
                        '192',
                        '194',
                        'GO',
                        '2BNG',
                        '3BNG',
                        '2BSET',
                        '3BSET',
                        'GO-M',
                        '6HNG',
                        '4HNG',
                        '6HSET',
                        '4HSET',
                        'GSTI',
                        '2BSTING',
                        '2BSTISET',
                        '3BSTISET',
                        '3BSTING',
                        'MING',
                        'MINNG',
                        'MINSET',
                        'PPG',
                        'PPNG',
                        'PPSET',
                        'GSTI-M',
                        '4HSTING',
                        '4HSTISET',
                        'NPTSET',
                        'NPTELE',
                    ],
                ],
                [
                    'label' => 'HSS Thread Ring Gage',
                    'slug' => 'hss-thread-ring-gage',
                    'series' => [
                        '196',
                        '196M',
                        '197',
                        '193P',
                    ],
                ],
                [
                    'label' => 'HSS ANSI',
                    'slug' => 'hss-ansi',
                    'series' => [
                        '117',
                        '117H',
                        '119',
                        '119H',
                    ],
                ],
                [
                    'label' => 'HSS DIN',
                    'slug' => 'hss-din',
                    'series' => [
                        '118S',
                        '118H',
                    ],
                ],
                [
                    'label' => 'SmartCut Tapping Oil',
                    'slug' => 'smartcut-tapping-oil',
                    'series' => [
                        'Smart Cut',
                    ],
                ],
                [
                    'label' => 'Bal-Tap Tapping Oil',
                    'slug' => 'bal-tap-tapping-oil',
                    'series' => [
                        'Bal-Tap',
                    ],
                ],
            ],
        ],
    ],
];
