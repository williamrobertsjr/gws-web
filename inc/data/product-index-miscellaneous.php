<?php
// Unlike Milling/Holemaking/Threading, Specialty has no material breakdown in the print
// catalog at all -- so here "material" is really the catalog's sub-category (Blanks, Engraving,
// ID Grinding, Routers). 'flat' => true tells page-product-index.php to skip the shape-grouping
// step entirely, so the menu is one level deep: sub-category > item.
// Burrs and Armory Tools were pulled out into their own top-level tabs (see
// page-product-index.php's gws_product_index_flat_category calls) since each is really just one
// undifferentiated bucket of tiles on its own -- no reason to bury them inside Miscellaneous.
// Tool Sets (kit SKUs with no matching DB series) are intentionally left out for now.
// Used by page-product-index.php. Built once from the print catalog; not read from it at runtime.
return [
    'category' => 'Miscellaneous',
    'category_slug' => 'miscellaneous',
    'description' => 'GWS Tool Group\'s miscellaneous line covers specialty tooling that falls outside standard milling, holemaking, threading, and inserts.',
    'flat' => true,
    'materials' => [
        [
            'material' => 'Blanks',
            'material_slug' => 'blanks',
            'items' => [
                [
                    'label' => 'Carbide Strips',
                    'slug' => 'carbide-strips',
                    'series' => [
                        '777',
                    ],
                ],
                [
                    'label' => 'Center Tip',
                    'slug' => 'center-tip',
                    'series' => [
                        '716C',
                    ],
                ],
                [
                    'label' => 'Split End',
                    'slug' => 'split-end',
                    'series' => [
                        '710',
                    ],
                ],
                [
                    'label' => 'Square',
                    'slug' => 'square',
                    'series' => [
                        '1000',
                    ],
                ],
                [
                    'label' => 'Radius',
                    'slug' => 'radius',
                    'series' => [
                        '2000',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Engraving',
            'material_slug' => 'engraving',
            'items' => [
                [
                    'label' => '1 Flute Single End/Double End',
                    'slug' => '1-flute-single-end-double-end',
                    'series' => [
                        '713',
                    ],
                ],
            ],
        ],
        [
            'material' => 'ID Grinding',
            'material_slug' => 'id-grinding',
            'items' => [
                [
                    'label' => 'Internal Grind Tool',
                    'slug' => 'internal-grind-tool',
                    'series' => [
                        '312',
                    ],
                ],
                [
                    'label' => 'Piloted Die Trimmer',
                    'slug' => 'piloted-die-trimmer',
                    'series' => [
                        '312P',
                    ],
                ],
            ],
        ],
        [
            'material' => 'Routers',
            'material_slug' => 'routers',
            'items' => [
                [
                    'label' => 'Style A-F Diamond Cut',
                    'slug' => 'style-a-f-diamond-cut',
                    'series' => [
                        '780',
                    ],
                ],
                [
                    'label' => 'Multi-Flute Straight Flute',
                    'slug' => 'multi-flute-straight-flute',
                    'series' => [
                        '787',
                    ],
                ],
            ],
        ],
    ],
];
