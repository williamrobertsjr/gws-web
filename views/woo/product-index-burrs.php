<?php
// Burrs stands alone as its own top-level category (pulled out of Miscellaneous), but the print
// catalog's burr shapes (Cylinder, Ball, Oval/Egg, Tree, Flame, Cone, Taper) are a real, useful
// single-level filter -- master_series_data.tool_sub_type doesn't distinguish them (every burr
// series is tool_sub_type='Burrs'), so this mapping is hand-built the same way as the other
// categories. 'flat' => true means the menu is one level deep: clicking a shape filters straight
// to its tiles. The 'items' below exist only so the controller can build the series lookup --
// flat categories don't render item-level buttons, so multi-variant shapes (Cylinder, Tree, Cone)
// still show as one shape button covering all their variants' tiles.
// Used by page-products.php. Built once from the print catalog; not read from it at runtime.
return [
    'category' => 'Burrs',
    'category_slug' => 'burrs',
    'description' => 'GWS Tool Group burrs cover a full range of shapes and cuts for deburring, deflashing, and weld prep across ferrous and non-ferrous materials.',
    'flat' => true,
    'materials' => [
        [
            'material' => 'Cylinder Shape',
            'material_slug' => 'cylinder-shape',
            'items' => [
                [
                    'label' => 'No End Cut',
                    'slug' => 'no-end-cut',
                    'series' => ['310A'],
                ],
                [
                    'label' => 'End Cut',
                    'slug' => 'end-cut',
                    'series' => ['310B'],
                ],
                [
                    'label' => 'Radius End',
                    'slug' => 'radius-end',
                    'series' => ['310C'],
                ],
            ],
        ],
        [
            'material' => 'Ball Shape',
            'material_slug' => 'ball-shape',
            'items' => [
                [
                    'label' => 'Ball Shape',
                    'slug' => 'ball-shape',
                    'series' => ['310D'],
                ],
            ],
        ],
        [
            'material' => 'Oval/Egg Shape',
            'material_slug' => 'oval-egg-shape',
            'items' => [
                [
                    'label' => 'Oval/Egg Shape',
                    'slug' => 'oval-egg-shape-item',
                    'series' => ['310E'],
                ],
            ],
        ],
        [
            'material' => 'Tree Shape',
            'material_slug' => 'tree-shape',
            'items' => [
                [
                    'label' => 'Radius',
                    'slug' => 'radius',
                    'series' => ['310F'],
                ],
                [
                    'label' => 'Pointed',
                    'slug' => 'pointed',
                    'series' => ['310G'],
                ],
            ],
        ],
        [
            'material' => 'Flame Shape',
            'material_slug' => 'flame-shape',
            'items' => [
                [
                    'label' => 'Flame Shape',
                    'slug' => 'flame-shape-item',
                    'series' => ['310H'],
                ],
            ],
        ],
        [
            'material' => 'Cone Shape',
            'material_slug' => 'cone-shape',
            'items' => [
                [
                    'label' => '60°',
                    'slug' => '60-degree',
                    'series' => ['310J'],
                ],
                [
                    'label' => '90°',
                    'slug' => '90-degree',
                    'series' => ['310K'],
                ],
                [
                    'label' => 'Inverted',
                    'slug' => 'inverted',
                    'series' => ['310N'],
                ],
                [
                    'label' => 'Pointed',
                    'slug' => 'cone-pointed',
                    'series' => ['310M'],
                ],
            ],
        ],
        [
            'material' => 'Taper Shape',
            'material_slug' => 'taper-shape',
            'items' => [
                [
                    'label' => 'Taper Shape',
                    'slug' => 'taper-shape-item',
                    'series' => ['310L'],
                ],
            ],
        ],
    ],
];
