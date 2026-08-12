<?php
// Maps Category > Material (single) > Line Item, decomposed from the printed catalog's
// Product Index (which groups by compound material combos, e.g. "Steel, Cast Iron, HRSA").
// A line item appears under every single material its original catalog grouping covered;
// duplicate line-item labels within the same material are merged (their series lists combined).
// Used by page-product-index.php. Built once from the print catalog; not read from it at runtime.
return [
    'category' => 'Milling',
    'category_slug' => 'milling',
    'description' => 'Milling is at the heart of modern precision manufacturing — shaping the components that go into aircraft structures, surgical instruments, and defense systems. GWS Tool Group offers a full range of carbide milling tools built for the materials and tolerances these industries demand.',
    'materials' => [
        [
            'material' => 'Steel',
            'material_slug' => 'steel',
            'items' => [
                [
                    'label' => '3-5 Flute Corner Chamfer',
                    'slug' => '3-5-flute-corner-chamfer',
                    'series' => [
                        '2135',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose',
                    'slug' => '2-flute-ball-nose',
                    'series' => [
                        '219',
                        '219M',
                        '219D',
                        '1050',
                    ],
                ],
                [
                    'label' => '3 Flute Ball Nose',
                    'slug' => '3-flute-ball-nose',
                    'series' => [
                        '222',
                        '2143',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose',
                    'slug' => '4-flute-ball-nose',
                    'series' => [
                        '220',
                        '220M',
                        '220D',
                        '2115',
                        '1032',
                        '2141',
                    ],
                ],
                [
                    'label' => '2,4 Flute Chamfer Mill',
                    'slug' => '2-4-flute-chamfer-mill',
                    'series' => [
                        '209',
                        '209D',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius',
                    'slug' => '2-flute-corner-radius',
                    'series' => [
                        '204',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius',
                    'slug' => '4-flute-corner-radius',
                    'series' => [
                        '206',
                    ],
                ],
                [
                    'label' => '2 Flute Drill/Mill',
                    'slug' => '2-flute-drill-mill',
                    'series' => [
                        '207',
                    ],
                ],
                [
                    'label' => '4 Flute Drill/Mill',
                    'slug' => '4-flute-drill-mill',
                    'series' => [
                        '208',
                    ],
                ],
                [
                    'label' => '2 Flute Square',
                    'slug' => '2-flute-square',
                    'series' => [
                        '202',
                        '202M',
                        '202D',
                    ],
                ],
                [
                    'label' => '3 Flute Square',
                    'slug' => '3-flute-square',
                    'series' => [
                        '205',
                        '205D',
                    ],
                ],
                [
                    'label' => '4 Flute Square',
                    'slug' => '4-flute-square',
                    'series' => [
                        '203',
                        '203M',
                        '203D',
                        '1031',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose (Micro End Mills)',
                    'slug' => '2-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '252',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose (Micro End Mills)',
                    'slug' => '4-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '256',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius (Micro End Mills)',
                    'slug' => '2-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '251',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius (Micro End Mills)',
                    'slug' => '4-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '255',
                    ],
                ],
                [
                    'label' => '2 Flute Square (Micro End Mills)',
                    'slug' => '2-flute-square-micro-end-mills',
                    'series' => [
                        '250',
                    ],
                ],
                [
                    'label' => '4 Flute Square (Micro End Mills)',
                    'slug' => '4-flute-square-micro-end-mills',
                    'series' => [
                        '254',
                    ],
                ],
                [
                    'label' => '4 Flute Radius',
                    'slug' => '4-flute-radius',
                    'series' => [
                        '2004',
                        '2004R',
                        '2117',
                        '2140',
                    ],
                ],
                [
                    'label' => '5 Flute Radius',
                    'slug' => '5-flute-radius',
                    'series' => [
                        '2005',
                        '2005R',
                    ],
                ],
                [
                    'label' => '6 Flute Radius',
                    'slug' => '6-flute-radius',
                    'series' => [
                        '2006',
                    ],
                ],
                [
                    'label' => '4 Flute Square and Radius',
                    'slug' => '4-flute-square-and-radius',
                    'series' => [
                        '1130',
                        '2100',
                        '2105',
                        '1030',
                        '1034',
                    ],
                ],
                [
                    'label' => '5 Flute Square and Radius',
                    'slug' => '5-flute-square-and-radius',
                    'series' => [
                        '2205',
                        '2213',
                        '1035',
                    ],
                ],
                [
                    'label' => '7 Flute Square and Radius',
                    'slug' => '7-flute-square-and-radius',
                    'series' => [
                        '2215',
                        '1040',
                    ],
                ],
                [
                    'label' => '4-5 Flute Corner Chamfer',
                    'slug' => '4-5-flute-corner-chamfer',
                    'series' => [
                        '2134',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square (Micro End Mills)',
                    'slug' => 'multi-flute-square-micro-end-mills',
                    'series' => [
                        '2150',
                    ],
                ],
                [
                    'label' => '3 Flute Radius',
                    'slug' => '3-flute-radius',
                    'series' => [
                        '2142',
                    ],
                ],
                [
                    'label' => '3 Flute Chamfer (Micro End Mills)',
                    'slug' => '3-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1103',
                    ],
                ],
                [
                    'label' => '4 Flute Chamfer (Micro End Mills)',
                    'slug' => '4-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1104',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Stainless',
            'material_slug' => 'stainless',
            'items' => [
                [
                    'label' => '5-7 Flute Corner Radius',
                    'slug' => '5-7-flute-corner-radius',
                    'series' => [
                        '2052',
                        '2053',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square and Radius',
                    'slug' => 'multi-flute-square-and-radius',
                    'series' => [
                        '280',
                    ],
                ],
                [
                    'label' => '4 Flute Radius',
                    'slug' => '4-flute-radius',
                    'series' => [
                        '2004',
                        '2004R',
                        '2117',
                        '2140',
                    ],
                ],
                [
                    'label' => '5 Flute Radius',
                    'slug' => '5-flute-radius',
                    'series' => [
                        '2005',
                        '2005R',
                    ],
                ],
                [
                    'label' => '6 Flute Radius',
                    'slug' => '6-flute-radius',
                    'series' => [
                        '2006',
                    ],
                ],
                [
                    'label' => '4 Flute Square and Radius',
                    'slug' => '4-flute-square-and-radius',
                    'series' => [
                        '1130',
                        '2100',
                        '2105',
                        '1030',
                        '1034',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose',
                    'slug' => '4-flute-ball-nose',
                    'series' => [
                        '2115',
                        '1032',
                        '2141',
                    ],
                ],
                [
                    'label' => '5 Flute Square and Radius',
                    'slug' => '5-flute-square-and-radius',
                    'series' => [
                        '2205',
                        '2213',
                        '1035',
                    ],
                ],
                [
                    'label' => '7 Flute Square and Radius',
                    'slug' => '7-flute-square-and-radius',
                    'series' => [
                        '2215',
                        '1040',
                    ],
                ],
                [
                    'label' => '4-5 Flute Corner Chamfer',
                    'slug' => '4-5-flute-corner-chamfer',
                    'series' => [
                        '2134',
                    ],
                ],
                [
                    'label' => '4 Flute Square',
                    'slug' => '4-flute-square',
                    'series' => [
                        '1031',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square (Micro End Mills)',
                    'slug' => 'multi-flute-square-micro-end-mills',
                    'series' => [
                        '2150',
                    ],
                ],
                [
                    'label' => '3 Flute Ball Nose',
                    'slug' => '3-flute-ball-nose',
                    'series' => [
                        '2143',
                    ],
                ],
                [
                    'label' => '3 Flute Radius',
                    'slug' => '3-flute-radius',
                    'series' => [
                        '2142',
                    ],
                ],
                [
                    'label' => '3 Flute Chamfer (Micro End Mills)',
                    'slug' => '3-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1103',
                    ],
                ],
                [
                    'label' => '4 Flute Chamfer (Micro End Mills)',
                    'slug' => '4-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1104',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Cast Iron',
            'material_slug' => 'cast-iron',
            'items' => [
                [
                    'label' => 'Multi-Flute Square and Radius',
                    'slug' => 'multi-flute-square-and-radius',
                    'series' => [
                        '270',
                    ],
                ],
                [
                    'label' => '3-5 Flute Corner Chamfer',
                    'slug' => '3-5-flute-corner-chamfer',
                    'series' => [
                        '2135',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose',
                    'slug' => '2-flute-ball-nose',
                    'series' => [
                        '219',
                        '219M',
                        '219D',
                        '1050',
                    ],
                ],
                [
                    'label' => '3 Flute Ball Nose',
                    'slug' => '3-flute-ball-nose',
                    'series' => [
                        '222',
                        '2143',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose',
                    'slug' => '4-flute-ball-nose',
                    'series' => [
                        '220',
                        '220M',
                        '220D',
                        '2115',
                        '1032',
                        '2141',
                    ],
                ],
                [
                    'label' => '2,4 Flute Chamfer Mill',
                    'slug' => '2-4-flute-chamfer-mill',
                    'series' => [
                        '209',
                        '209D',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius',
                    'slug' => '2-flute-corner-radius',
                    'series' => [
                        '204',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius',
                    'slug' => '4-flute-corner-radius',
                    'series' => [
                        '206',
                    ],
                ],
                [
                    'label' => '2 Flute Drill/Mill',
                    'slug' => '2-flute-drill-mill',
                    'series' => [
                        '207',
                    ],
                ],
                [
                    'label' => '4 Flute Drill/Mill',
                    'slug' => '4-flute-drill-mill',
                    'series' => [
                        '208',
                    ],
                ],
                [
                    'label' => '2 Flute Square',
                    'slug' => '2-flute-square',
                    'series' => [
                        '202',
                        '202M',
                        '202D',
                    ],
                ],
                [
                    'label' => '3 Flute Square',
                    'slug' => '3-flute-square',
                    'series' => [
                        '205',
                        '205D',
                    ],
                ],
                [
                    'label' => '4 Flute Square',
                    'slug' => '4-flute-square',
                    'series' => [
                        '203',
                        '203M',
                        '203D',
                        '1031',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose (Micro End Mills)',
                    'slug' => '2-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '252',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose (Micro End Mills)',
                    'slug' => '4-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '256',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius (Micro End Mills)',
                    'slug' => '2-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '251',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius (Micro End Mills)',
                    'slug' => '4-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '255',
                    ],
                ],
                [
                    'label' => '2 Flute Square (Micro End Mills)',
                    'slug' => '2-flute-square-micro-end-mills',
                    'series' => [
                        '250',
                    ],
                ],
                [
                    'label' => '4 Flute Square (Micro End Mills)',
                    'slug' => '4-flute-square-micro-end-mills',
                    'series' => [
                        '254',
                    ],
                ],
                [
                    'label' => '4 Flute Radius',
                    'slug' => '4-flute-radius',
                    'series' => [
                        '2004',
                        '2004R',
                        '2117',
                        '2140',
                    ],
                ],
                [
                    'label' => '5 Flute Radius',
                    'slug' => '5-flute-radius',
                    'series' => [
                        '2005',
                        '2005R',
                    ],
                ],
                [
                    'label' => '6 Flute Radius',
                    'slug' => '6-flute-radius',
                    'series' => [
                        '2006',
                    ],
                ],
                [
                    'label' => '4 Flute Square and Radius',
                    'slug' => '4-flute-square-and-radius',
                    'series' => [
                        '1130',
                        '2100',
                        '2105',
                        '1030',
                        '1034',
                    ],
                ],
                [
                    'label' => '5 Flute Square and Radius',
                    'slug' => '5-flute-square-and-radius',
                    'series' => [
                        '2205',
                        '2213',
                        '1035',
                    ],
                ],
                [
                    'label' => '7 Flute Square and Radius',
                    'slug' => '7-flute-square-and-radius',
                    'series' => [
                        '2215',
                        '1040',
                    ],
                ],
                [
                    'label' => '4-5 Flute Corner Chamfer',
                    'slug' => '4-5-flute-corner-chamfer',
                    'series' => [
                        '2134',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square (Micro End Mills)',
                    'slug' => 'multi-flute-square-micro-end-mills',
                    'series' => [
                        '2150',
                    ],
                ],
                [
                    'label' => '3 Flute Radius',
                    'slug' => '3-flute-radius',
                    'series' => [
                        '2142',
                    ],
                ],
                [
                    'label' => '3 Flute Chamfer (Micro End Mills)',
                    'slug' => '3-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1103',
                    ],
                ],
                [
                    'label' => '4 Flute Chamfer (Micro End Mills)',
                    'slug' => '4-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1104',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Non-Ferrous',
            'material_slug' => 'non-ferrous',
            'items' => [
                [
                    'label' => '2 Flute Ball Nose',
                    'slug' => '2-flute-ball-nose',
                    'series' => [
                        '2014',
                        '2015',
                        '219',
                        '219M',
                        '219D',
                    ],
                ],
                [
                    'label' => '3 Flute Ball Nose',
                    'slug' => '3-flute-ball-nose',
                    'series' => [
                        '2045',
                        '2047',
                        '222',
                        '2143',
                    ],
                ],
                [
                    'label' => '3 Flute Corner Chamfer',
                    'slug' => '3-flute-corner-chamfer',
                    'series' => [
                        '2133',
                    ],
                ],
                [
                    'label' => '3 Flute Radius',
                    'slug' => '3-flute-radius',
                    'series' => [
                        '1010',
                        '2142',
                    ],
                ],
                [
                    'label' => '3 Flute Square',
                    'slug' => '3-flute-square',
                    'series' => [
                        '1025',
                        '1026',
                        '2031',
                        '205',
                        '205D',
                    ],
                ],
                [
                    'label' => '2 Flute Square and Radius',
                    'slug' => '2-flute-square-and-radius',
                    'series' => [
                        '2010',
                        '2012',
                    ],
                ],
                [
                    'label' => '3 Flute Square and Radius',
                    'slug' => '3-flute-square-and-radius',
                    'series' => [
                        '1015',
                        '1020',
                        '2030',
                        '2032',
                    ],
                ],
                [
                    'label' => '5 Flute Square and Radius',
                    'slug' => '5-flute-square-and-radius',
                    'series' => [
                        '1500',
                        '1502',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose',
                    'slug' => '4-flute-ball-nose',
                    'series' => [
                        '220',
                        '220M',
                        '220D',
                        '2141',
                    ],
                ],
                [
                    'label' => '2,4 Flute Chamfer Mill',
                    'slug' => '2-4-flute-chamfer-mill',
                    'series' => [
                        '209',
                        '209D',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius',
                    'slug' => '2-flute-corner-radius',
                    'series' => [
                        '204',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius',
                    'slug' => '4-flute-corner-radius',
                    'series' => [
                        '206',
                    ],
                ],
                [
                    'label' => '2 Flute Drill/Mill',
                    'slug' => '2-flute-drill-mill',
                    'series' => [
                        '207',
                    ],
                ],
                [
                    'label' => '4 Flute Drill/Mill',
                    'slug' => '4-flute-drill-mill',
                    'series' => [
                        '208',
                    ],
                ],
                [
                    'label' => '2 Flute Square',
                    'slug' => '2-flute-square',
                    'series' => [
                        '202',
                        '202M',
                        '202D',
                    ],
                ],
                [
                    'label' => '4 Flute Square',
                    'slug' => '4-flute-square',
                    'series' => [
                        '203',
                        '203M',
                        '203D',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose (Micro End Mills)',
                    'slug' => '2-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '252',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose (Micro End Mills)',
                    'slug' => '4-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '256',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius (Micro End Mills)',
                    'slug' => '2-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '251',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius (Micro End Mills)',
                    'slug' => '4-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '255',
                    ],
                ],
                [
                    'label' => '2 Flute Square (Micro End Mills)',
                    'slug' => '2-flute-square-micro-end-mills',
                    'series' => [
                        '250',
                    ],
                ],
                [
                    'label' => '4 Flute Square (Micro End Mills)',
                    'slug' => '4-flute-square-micro-end-mills',
                    'series' => [
                        '254',
                    ],
                ],
                [
                    'label' => '4 Flute Radius',
                    'slug' => '4-flute-radius',
                    'series' => [
                        '2140',
                    ],
                ],
                [
                    'label' => '4 Flute Square and Radius',
                    'slug' => '4-flute-square-and-radius',
                    'series' => [
                        '1034',
                    ],
                ],
                [
                    'label' => '3 Flute Chamfer (Micro End Mills)',
                    'slug' => '3-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1103',
                    ],
                ],
                [
                    'label' => '4 Flute Chamfer (Micro End Mills)',
                    'slug' => '4-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1104',
                    ],
                ],
            ],
        ],
        [
            'material' => 'HRSA',
            'material_slug' => 'hrsa',
            'items' => [
                [
                    'label' => '5-7 Flute Corner Radius',
                    'slug' => '5-7-flute-corner-radius',
                    'series' => [
                        '2052',
                        '2053',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square and Radius',
                    'slug' => 'multi-flute-square-and-radius',
                    'series' => [
                        '280',
                        '270',
                    ],
                ],
                [
                    'label' => '3-5 Flute Corner Chamfer',
                    'slug' => '3-5-flute-corner-chamfer',
                    'series' => [
                        '2135',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose',
                    'slug' => '2-flute-ball-nose',
                    'series' => [
                        '1050',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose',
                    'slug' => '4-flute-ball-nose',
                    'series' => [
                        '2115',
                        '1032',
                        '2141',
                    ],
                ],
                [
                    'label' => '4 Flute Radius',
                    'slug' => '4-flute-radius',
                    'series' => [
                        '2117',
                        '2140',
                    ],
                ],
                [
                    'label' => '4 Flute Square and Radius',
                    'slug' => '4-flute-square-and-radius',
                    'series' => [
                        '2100',
                        '2105',
                        '1030',
                        '1034',
                    ],
                ],
                [
                    'label' => '5 Flute Square and Radius',
                    'slug' => '5-flute-square-and-radius',
                    'series' => [
                        '2205',
                        '2213',
                        '1035',
                    ],
                ],
                [
                    'label' => '7 Flute Square and Radius',
                    'slug' => '7-flute-square-and-radius',
                    'series' => [
                        '2215',
                        '1040',
                    ],
                ],
                [
                    'label' => '4-5 Flute Corner Chamfer',
                    'slug' => '4-5-flute-corner-chamfer',
                    'series' => [
                        '2134',
                    ],
                ],
                [
                    'label' => '4 Flute Square',
                    'slug' => '4-flute-square',
                    'series' => [
                        '1031',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square (Micro End Mills)',
                    'slug' => 'multi-flute-square-micro-end-mills',
                    'series' => [
                        '2150',
                    ],
                ],
                [
                    'label' => '3 Flute Ball Nose',
                    'slug' => '3-flute-ball-nose',
                    'series' => [
                        '2143',
                    ],
                ],
                [
                    'label' => '3 Flute Radius',
                    'slug' => '3-flute-radius',
                    'series' => [
                        '2142',
                    ],
                ],
                [
                    'label' => '3 Flute Chamfer (Micro End Mills)',
                    'slug' => '3-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1103',
                    ],
                ],
                [
                    'label' => '4 Flute Chamfer (Micro End Mills)',
                    'slug' => '4-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1104',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Hardened Steel',
            'material_slug' => 'hardened-steel',
            'items' => [
                [
                    'label' => 'Multi-Flute Square and Radius',
                    'slug' => 'multi-flute-square-and-radius',
                    'series' => [
                        '270',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose',
                    'slug' => '2-flute-ball-nose',
                    'series' => [
                        '219',
                        '219M',
                        '219D',
                        '1050',
                    ],
                ],
                [
                    'label' => '3 Flute Ball Nose',
                    'slug' => '3-flute-ball-nose',
                    'series' => [
                        '222',
                        '2143',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose',
                    'slug' => '4-flute-ball-nose',
                    'series' => [
                        '220',
                        '220M',
                        '220D',
                        '1032',
                        '2141',
                    ],
                ],
                [
                    'label' => '2,4 Flute Chamfer Mill',
                    'slug' => '2-4-flute-chamfer-mill',
                    'series' => [
                        '209',
                        '209D',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius',
                    'slug' => '2-flute-corner-radius',
                    'series' => [
                        '204',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius',
                    'slug' => '4-flute-corner-radius',
                    'series' => [
                        '206',
                    ],
                ],
                [
                    'label' => '2 Flute Drill/Mill',
                    'slug' => '2-flute-drill-mill',
                    'series' => [
                        '207',
                    ],
                ],
                [
                    'label' => '4 Flute Drill/Mill',
                    'slug' => '4-flute-drill-mill',
                    'series' => [
                        '208',
                    ],
                ],
                [
                    'label' => '2 Flute Square',
                    'slug' => '2-flute-square',
                    'series' => [
                        '202',
                        '202M',
                        '202D',
                    ],
                ],
                [
                    'label' => '3 Flute Square',
                    'slug' => '3-flute-square',
                    'series' => [
                        '205',
                        '205D',
                    ],
                ],
                [
                    'label' => '4 Flute Square',
                    'slug' => '4-flute-square',
                    'series' => [
                        '203',
                        '203M',
                        '203D',
                        '1031',
                    ],
                ],
                [
                    'label' => '2 Flute Ball Nose (Micro End Mills)',
                    'slug' => '2-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '252',
                    ],
                ],
                [
                    'label' => '4 Flute Ball Nose (Micro End Mills)',
                    'slug' => '4-flute-ball-nose-micro-end-mills',
                    'series' => [
                        '256',
                    ],
                ],
                [
                    'label' => '2 Flute Corner Radius (Micro End Mills)',
                    'slug' => '2-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '251',
                    ],
                ],
                [
                    'label' => '4 Flute Corner Radius (Micro End Mills)',
                    'slug' => '4-flute-corner-radius-micro-end-mills',
                    'series' => [
                        '255',
                    ],
                ],
                [
                    'label' => '2 Flute Square (Micro End Mills)',
                    'slug' => '2-flute-square-micro-end-mills',
                    'series' => [
                        '250',
                    ],
                ],
                [
                    'label' => '4 Flute Square (Micro End Mills)',
                    'slug' => '4-flute-square-micro-end-mills',
                    'series' => [
                        '254',
                    ],
                ],
                [
                    'label' => '4 Flute Radius',
                    'slug' => '4-flute-radius',
                    'series' => [
                        '2004',
                        '2004R',
                        '2140',
                    ],
                ],
                [
                    'label' => '5 Flute Radius',
                    'slug' => '5-flute-radius',
                    'series' => [
                        '2005',
                        '2005R',
                    ],
                ],
                [
                    'label' => '6 Flute Radius',
                    'slug' => '6-flute-radius',
                    'series' => [
                        '2006',
                    ],
                ],
                [
                    'label' => '4 Flute Square and Radius',
                    'slug' => '4-flute-square-and-radius',
                    'series' => [
                        '1130',
                        '1030',
                        '1034',
                    ],
                ],
                [
                    'label' => '4-5 Flute Corner Chamfer',
                    'slug' => '4-5-flute-corner-chamfer',
                    'series' => [
                        '2134',
                    ],
                ],
                [
                    'label' => '5 Flute Square and Radius',
                    'slug' => '5-flute-square-and-radius',
                    'series' => [
                        '1035',
                    ],
                ],
                [
                    'label' => '7 Flute Square and Radius',
                    'slug' => '7-flute-square-and-radius',
                    'series' => [
                        '1040',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Square (Micro End Mills)',
                    'slug' => 'multi-flute-square-micro-end-mills',
                    'series' => [
                        '2150',
                    ],
                ],
                [
                    'label' => '3 Flute Radius',
                    'slug' => '3-flute-radius',
                    'series' => [
                        '2142',
                    ],
                ],
                [
                    'label' => '3 Flute Chamfer (Micro End Mills)',
                    'slug' => '3-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1103',
                    ],
                ],
                [
                    'label' => '4 Flute Chamfer (Micro End Mills)',
                    'slug' => '4-flute-chamfer-micro-end-mills',
                    'series' => [
                        '1104',
                    ],
                ],
            ],
        ],
    ],
];
