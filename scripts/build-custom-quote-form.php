<?php
// Builds the "Custom Quote" Gravity Form (/custom-quote) as six steps.
// Option lists (tool types, series, coatings, materials) and field IDs come from
// inc/custom-quote.php, which reads master_product_data / master_series_data.
// Rerun after catalog changes so the form only offers what we sell:
//   wp eval-file wp-content/themes/gws-web/scripts/build-custom-quote-form.php dry update=55
//   wp eval-file wp-content/themes/gws-web/scripts/build-custom-quote-form.php update=55
// Without update=<id> it creates a new form, copying settings from form 53.

$dry       = in_array('dry', $args ?? [], true);
$update_id = 0;
foreach ($args ?? [] as $a) {
    if (strpos($a, 'update=') === 0) {
        $update_id = (int) substr($a, 7);
    }
}
if (!function_exists('gws_cq_categories')) {
    WP_CLI::error('inc/custom-quote.php is not loaded');
}

const EXPORT_TEXT = 'This submission includes ITAR, CUI, or other export-controlled technical data';

function choices(array $map) {
    $out = [];
    foreach ($map as $value => $text) {
        $out[] = ['text' => $text, 'value' => is_string($value) ? $value : $text, 'isSelected' => false, 'price' => ''];
    }
    return $out;
}
function rule($field_id, $value, $op = 'is') {
    return ['fieldId' => (string) $field_id, 'operator' => $op, 'value' => $value];
}
function logic(array $rules, $type = 'all') {
    return ['actionType' => 'show', 'logicType' => $type, 'rules' => $rules];
}
function f($id, $type, $label, $step, array $extra = []) {
    $props = array_merge(['id' => $id, 'type' => $type, 'label' => $label, 'isRequired' => false], $extra);
    $props['cssClass'] = trim('cq-step-' . $step . ' ' . ($extra['cssClass'] ?? ''));
    // GF falls back to a choice's text when its value is empty, so a blank "Select"
    // choice would pass required validation. Use GF's own select placeholder instead.
    if ($type === 'select' && !isset($props['placeholder'])) {
        $props['placeholder'] = 'Select';
    }
    if (isset($props['choices']) && !isset($props['choices'][0]['text'])) {
        $props['choices'] = choices($props['choices']);
    }
    if ($type === 'checkbox') {
        $props['inputs'] = [];
        foreach ($props['choices'] as $i => $c) {
            $props['inputs'][] = ['id' => $id . '.' . ($i + 1), 'label' => $c['text']];
        }
    }
    return $props;
}

$cats   = gws_cq_categories();
$ids    = gws_cq_field_ids();
$CAT    = $ids['category'];
$any_cat = logic(array_map(fn($c) => rule($CAT, $c), array_keys($cats)), 'any');
// Field shown for one category (plus any extra rules).
$in = fn($cat, array $extra = [], array $more = []) => array_merge($extra, ['conditionalLogic' => logic(array_merge([rule($CAT, $cat)], $more))]);
$opt = fn(array $list) => array_combine($list, $list);
$sec = fn($id, $label, $step, $desc = '', $cond = null) => f($id, 'section', $label, $step, array_filter(['cssClass' => 'cq-step-head', 'description' => $desc, 'conditionalLogic' => $cond]));

$cat_choices = [];
foreach ($cats as $value => $c) {
    $cat_choices[$value] = $c[0];
}
$cat_choices['print-only'] = 'Not sure / I have a print';

$materials = [];
foreach (gws_cq_materials() as $code => [$label]) {
    $materials[$code] = $code === 'Other' ? $label : $code . ' – ' . $label;
}

$fields = [
    // 01 Start
    $sec(200, 'Start', 1),
    f($CAT, 'radio', 'Tool Category', 1, ['isRequired' => true, 'choices' => $cat_choices, 'allowsPrepopulate' => true, 'inputName' => 'category', 'cssClass' => 'quote-category-choice']),
    f($ids['mode'], 'radio', 'How do you want to start?', 1, ['choices' => ['modify' => 'Modify a standard tool', 'design' => 'Design a special'], 'cssClass' => 'quote-mode-choice', 'conditionalLogic' => $any_cat]),
    f($ids['edp'], 'text', 'Standard Part Number (EDP)', 1, ['allowsPrepopulate' => true, 'inputName' => 'edp', 'placeholder' => 'e.g. 100333', 'description' => "We'll bring in its dimensions so you can change what you need.", 'conditionalLogic' => logic([rule($ids['mode'], 'modify')])]),
];
foreach ($cats as $cat => $c) {
    $label = $cat === 'inserts' ? 'Insert Grade' : 'Tool Type';
    $fields[] = f($ids['type'][$cat], 'select', $label, 1, $in($cat, ['choices' => $opt(gws_cq_tool_type_choices($cat)), 'placeholder' => 'Choose a tool type']));
}
foreach ($cats as $cat => $c) {
    // Built as explicit pairs: series codes like 1050 would be renumbered as array keys.
    $series = [];
    foreach (gws_cq_series($cat) as $s) {
        $series[] = ['text' => gws_cq_series_label($s), 'value' => $s['series'], 'isSelected' => false, 'price' => ''];
    }
    $fields[] = f($ids['series'][$cat], 'select', 'Base Series', 1, $in($cat, ['choices' => $series, 'placeholder' => 'Choose a series (optional)', 'description' => 'The standard series closest to what you need.', 'cssClass' => 'quote-series-select']));
}
$fields[] = f($ids['changes'], 'hidden', 'Changes From Original', 1);

// 02 Geometry
$fields[] = $sec(201, 'Geometry', 2, 'All optional - leave blank for standard. Have a print? Upload it under Application and skip this.', $any_cat);
$fields[] = f($ids['units'], 'radio', 'Units', 2, ['choices' => [['text' => 'Imperial (in)', 'value' => 'Imperial', 'isSelected' => true, 'price' => ''], ['text' => 'Metric (mm)', 'value' => 'Metric', 'isSelected' => false, 'price' => '']], 'cssClass' => 'quote-units-choice', 'conditionalLogic' => $any_cat]);
$geometry = [
    'milling' => [
        [112, 'text', 'Cutting Diameter (D1)'], [113, 'text', 'Corner Radius (R)', [], [rule($ids['type']['milling'], 'Corner Radius')]],
        [114, 'text', 'Length of Cut (L1)'], [116, 'text', 'Overall Length (L)'], [117, 'text', 'Shank Diameter (D)'],
        [115, 'text', 'Reach / Neck Diameter', ['description' => 'Reduced-neck tools only.']], [118, 'text', 'Number of Flutes'], [124, 'text', 'Helix Angle'],
    ],
    'holemaking' => [
        [131, 'text', 'Cutting Diameter (D1)'], [136, 'text', 'Flute Length (L1)'], [135, 'text', 'Overall Length (L)'], [137, 'text', 'Shank Diameter (D)'],
        [138, 'text', 'Point Angle'], [134, 'textarea', 'Step Diameters & Lengths', [], [rule($ids['type']['holemaking'], 'Drills' . GWS_CQ_SEP . 'Step Drills')]],
        [132, 'text', 'Hole Depth'], [133, 'radio', 'Hole Type', ['choices' => ['Thru', 'Blind']]],
    ],
    'threading' => [
        [151, 'text', 'Thread Size & Pitch / TPI', ['placeholder' => 'e.g. 1/4-20 UNC, M8x1.25']], [152, 'text', 'Class of Fit / Gage Limits', ['placeholder' => 'e.g. 2B, 6H, H3']],
        [153, 'radio', 'Hole Type', ['choices' => ['Thru', 'Blind']]], [154, 'text', 'Thread Depth'], [155, 'text', 'Drilled Hole Diameter'], [156, 'text', 'Drilled Depth'],
    ],
    'inserts' => [
        [171, 'text', 'Insert Designation', ['placeholder' => 'ISO code, e.g. CNGA 432']], [172, 'text', 'Edge Prep'],
    ],
    'specialty' => [
        [181, 'text', 'Diameter (D1)'], [182, 'text', 'Overall Length (L)'], [183, 'text', 'Shank Diameter (D)'], [185, 'textarea', 'Key Dimensions & Requirements'],
    ],
];
$finish = [
    'milling' => [
        [111, 'select', 'Construction', ['choices' => $opt(['Solid Carbide', 'Brazed Carbide', 'Indexable', 'HSS/HSCO', 'Not sure'])]],
        [119, 'select', 'Coating', ['choices' => $opt(gws_cq_coating_choices('milling'))]],
        [120, 'radio', 'Coolant-Thru', ['choices' => ['Yes', 'No']]],
        [121, 'select', 'Shank Modification', ['choices' => $opt(['None', 'Weldon Flat', 'Other'])]],
        [123, 'textarea', 'Required Tolerances'],
    ],
    'holemaking' => [
        [139, 'select', 'Tool Material', ['choices' => $opt(['Solid Carbide', 'HSS/HSCO', 'Tipped', 'Carbide Head on Steel Body', 'Not sure'])]],
        [140, 'select', 'Coating', ['choices' => $opt(gws_cq_coating_choices('holemaking'))]],
        [141, 'radio', 'Coolant-Thru', ['choices' => ['Yes', 'No']]],
        [142, 'textarea', 'Tolerances / GD&T', ['description' => 'Diameter and length tolerances, runout, datums.']],
    ],
    'threading' => [
        [158, 'select', 'Coating', ['choices' => $opt(gws_cq_coating_choices('threading'))]],
        [157, 'radio', 'Coolant-Thru', ['choices' => ['None', 'Axial', 'Radial']]],
    ],
    'inserts' => [
        [176, 'textarea', 'Form / Tolerance Requirements'],
    ],
    'specialty' => [
        [184, 'select', 'Coating / Finish', ['choices' => $opt(gws_cq_coating_choices('specialty'))]],
    ],
];
$application = [
    'milling'    => [[122, 'radio', 'Operation', ['choices' => gws_cq_operations()['milling'] + ['mixed' => 'Mixed / several']]]],
    'holemaking' => [[143, 'textarea', 'Machine / Setup Notes', ['description' => 'Machine, max RPM, rotating or fixed tool, coolant pressure - if relevant.']]],
    'threading'  => [[159, 'textarea', 'Current Problem With Existing Tool', ['description' => 'e.g. breakage, poor tool life, oversize threads.']]],
    'inserts'    => [[173, 'radio', 'Operation', ['choices' => ['Turning', 'Milling']]], [174, 'radio', 'Roughing or Finishing', ['choices' => ['Roughing', 'Finishing', 'Both']]], [175, 'text', 'Machine Type']],
    'specialty'  => [],
];
$add_block = function (array $block, $step) use (&$fields, $in) {
    foreach ($block as $cat => $defs) {
        foreach ($defs as $d) {
            $fields[] = f($d[0], $d[1], $d[2], $step, $in($cat, $d[3] ?? [], $d[4] ?? []));
        }
    }
};
$add_block($geometry, 2);

// 03 Finish
$fields[] = $sec(202, 'Finish', 3, '', $any_cat);
$add_block($finish, 3);

// 04 Application
$fields[] = $sec(203, 'Application', 4);
$fields[] = f($ids['material'], 'select', 'Work Material', 4, ['isRequired' => true, 'choices' => $materials, 'placeholder' => 'What are you machining?', 'cssClass' => 'quote-material-select']);
$fields[] = f(19, 'text', 'Material Grade / Condition', 4, ['placeholder' => 'e.g. 4140 Q&T, 6061-T6, Inconel 718']);
$fields[] = f(13, 'text', 'Material Hardness', 4, ['placeholder' => "e.g. 32 HRC, or \"don't know\""]);
$add_block($application, 4);
$fields[] = f(15, 'fileupload', 'Upload Files (drawings, prints, CAD models, etc.)', 4, [
    'multipleFiles'        => true,
    'maxFiles'             => 5,
    'allowedExtensions'    => 'pdf,dwg,dxf,step,stp,iges,igs,jpg,jpeg,png,doc,docx,xls,xlsx',
    'description'          => "Do not upload files here if this request involves ITAR, CUI, or other export-controlled technical data - check the box below instead and we'll contact you to arrange a secure exchange.",
    'descriptionPlacement' => 'below',
    'storageType'          => 'json',
    'conditionalLogic'     => ['actionType' => 'hide', 'logicType' => 'all', 'rules' => [rule('16.1', EXPORT_TEXT)]],
]);
$fields[] = f(16, 'checkbox', 'Export Control', 4, ['choices' => [EXPORT_TEXT]]);

// 05 Quantities & Details
$fields[] = $sec(204, 'Quantities & Details', 5);
$fields[] = f(10, 'text', 'Quantities To Quote', 5, ['placeholder' => 'e.g. 5, 10, 25']);
$fields[] = f(11, 'text', 'Estimated Annual Usage', 5);
$fields[] = f(9, 'date', 'Desired Date', 5, ['dateType' => 'datepicker', 'calendarIconType' => 'none', 'dateFormat' => 'mdy', 'description' => "When you'd like to receive your quote. We'll confirm timing - this isn't a guaranteed date."]);
$fields[] = f(14, 'text', 'Existing Tool / Similar To', 5, ['placeholder' => 'Competitor part # or the tool you use today']);
$fields[] = f(8, 'text', 'Project / Part Number', 5);
$fields[] = f(99, 'textarea', 'Notes', 5, ['description' => 'Anything else we should know - application, current problems, special features.']);

// 06 Contact
$distributor = logic([rule(5, 'Distributor')]);
$fields[] = $sec(205, 'Contact', 6);
$fields[] = f(1, 'text', 'Your Name', 6, ['isRequired' => true]);
$fields[] = f(2, 'text', 'Company', 6, ['isRequired' => true]);
$fields[] = f(3, 'email', 'Email', 6, ['isRequired' => true]);
$fields[] = f(4, 'phone', 'Phone', 6, ['phoneFormat' => 'standard']);
$fields[] = f(5, 'radio', 'I am a', 6, ['isRequired' => true, 'choices' => ['Distributor', 'End user']]);
$fields[] = f(6, 'text', 'End User Company', 6, ['description' => 'Optional - who the tool is for, if you know.', 'conditionalLogic' => $distributor]);
$fields[] = f(7, 'text', 'End User Contact', 6, ['conditionalLogic' => $distributor]);

$form = GFAPI::get_form($update_id ?: 53);
if (!$form) {
    WP_CLI::error('Source form not found');
}
$form['title']       = 'Custom Quote';
$form['description'] = '';
foreach ($form['notifications'] as &$n) {
    $n['subject'] = 'New Custom Quote Request - {Tool Category:' . $CAT . '}';
}
unset($n);

$objs = [];
foreach ($fields as $props) {
    $props['formId'] = $update_id;
    $objs[] = GF_Fields::create($props);
}
$fids = array_map(fn($fl) => (string) $fl->id, $objs);
if (count($fids) !== count(array_unique($fids))) {
    WP_CLI::error('Duplicate field IDs: ' . implode(',', array_diff_assoc($fids, array_unique($fids))));
}
$form['fields'] = $objs;

$inputs = array_filter($fields, fn($p) => !in_array($p['type'], ['section', 'hidden'], true));
WP_CLI::log(sprintf('Fields: %d total, %d inputs, %d material options', count($fields), count($inputs), count($materials)));
foreach (array_keys($cats) as $cat) {
    WP_CLI::log(sprintf('  %-10s types=%d series=%d coatings=%s', $cat, count(gws_cq_tool_type_choices($cat)), count(gws_cq_series($cat)), $cat === 'inserts' ? '-' : implode('|', gws_cq_coating_choices($cat))));
}
if ($dry) {
    WP_CLI::success('Dry run OK');
    return;
}
if ($update_id) {
    $result = GFAPI::update_form($form);
    $new_id = $update_id;
} else {
    unset($form['id']);
    $result = $new_id = GFAPI::add_form($form);
}
if (is_wp_error($result)) {
    WP_CLI::error($result->get_error_message());
}
delete_transient('gws_cq_client_data');
WP_CLI::success("Form $new_id saved");
