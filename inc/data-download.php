<?php

/**
 * Parts & Pricing data-download page
 * - Server-side DataTables feed
 * - CSV export
 *
 * Data source: master_price_data_{GWS_PRICE_PERIOD} and the discount_XX_{GWS_PRICE_PERIOD}
 * views built on top of it (net price + qty-break pricing per discount tier).
 */

if (!defined('ABSPATH')) exit;

define('GWS_PRICE_PERIOD', 'jun_2026');

// Matches the "Price list effective" date shown on the dashboard (page-dashboard.twig)
// for this same period — bump together when GWS_PRICE_PERIOD changes. Used as a
// fallback if gws_dd_get_last_updated() finds no tracking row yet.
define('GWS_PRICE_PERIOD_EFFECTIVE_DATE', '06/01/2026');

/**
 * Real "last updated" timestamp for the active price table, maintained by DB
 * triggers on master_price_data_{period} (see gws_data_table_updates) that fire
 * on any insert/update/delete — including edits made directly in MySQL Workbench,
 * not just through this app.
 */
function gws_dd_get_last_updated() {
    global $wpdb;

    $table = "master_price_data_" . GWS_PRICE_PERIOD;
    $updated_at = $wpdb->get_var($wpdb->prepare(
        "SELECT updated_at FROM gws_data_table_updates WHERE table_name = %s",
        $table
    ));

    if (!$updated_at) return GWS_PRICE_PERIOD_EFFECTIVE_DATE;

    return date('m/d/Y', strtotime($updated_at));
}

/**
 * Map of tier code (WP role, or gws_selected_tier cookie value for sales/admin)
 * to the discount view that holds its net/qty pricing. Null means "no discount
 * view — show list price only". Percentages verified against the rate switch in
 * gws_calculate_discounted_price() (views/woo/discounts.php).
 */
function gws_dd_tier_view_map() {
    $period = GWS_PRICE_PERIOD;
    return [
        't1'         => "discount_55_{$period}",
        't2'         => "discount_52_5_{$period}",
        't3'         => "discount_50_{$period}",
        '57_5'       => "discount_57_5_{$period}",
        '57'         => "discount_57_{$period}",
        'pl_55'      => "discount_55_{$period}",
        'pl_57'      => "discount_57_{$period}",
        'ploem_60'   => "discount_60_{$period}",
        'ploem_65'   => "discount_65_{$period}",
        'direct_30'  => "discount_direct_{$period}",
        // No discount_42_5_{period} view yet — falls back to master_price_data (list price only)
        // until the DB view for this tier exists.
        'direct_42_5' => null,
        'exemptPlus' => "discount_55_25_{$period}",
        'tier_57'    => "discount_57_{$period}",
        'customer'   => null,
        'default'    => null,
        'none'       => null,
    ];
}

/**
 * Resolve the tier to price this request with. Unlike gws_get_user_tier()
 * (views/woo/discounts.php), the gws_selected_tier cookie override is only
 * honored for administrator/sales — everyone else always gets their own
 * role's tier, since this feeds a downloadable price sheet rather than a
 * live cart display.
 */
function gws_dd_resolve_tier() {
    $user = wp_get_current_user();
    if (!$user || !$user->exists()) return null;

    $roles = (array) $user->roles;
    $role  = $roles[0] ?? 'none';
    $map   = gws_dd_tier_view_map();

    $is_privileged = in_array($role, ['administrator', 'sales'], true);

    if ($is_privileged && !empty($_COOKIE['gws_selected_tier'])) {
        $candidate = sanitize_text_field($_COOKIE['gws_selected_tier']);
        if ($candidate === 'default') return 'default';
        if (array_key_exists($candidate, $map)) return $candidate;
    }

    return array_key_exists($role, $map) ? $role : 'default';
}

/**
 * Same as gws_dd_resolve_tier(), but also allows administrator/sales to pass an
 * explicit ?tier= to grab a specific tier's list directly (e.g. the dashboard's
 * per-tier Quick Links buttons) without first switching the header tier selector.
 * Non-privileged roles always fall back to gws_dd_resolve_tier() — the tier param
 * is ignored for them, same as the cookie.
 */
function gws_dd_resolve_tier_for_export() {
    $user = wp_get_current_user();
    if (!$user || !$user->exists()) return null;

    $roles = (array) $user->roles;
    $role  = $roles[0] ?? 'none';
    $map   = gws_dd_tier_view_map();
    $is_privileged = in_array($role, ['administrator', 'sales'], true);

    if ($is_privileged && !empty($_GET['tier'])) {
        $candidate = sanitize_text_field($_GET['tier']);
        if ($candidate === 'default' || array_key_exists($candidate, $map)) {
            return $candidate;
        }
    }

    return gws_dd_resolve_tier();
}

/**
 * Whitelisted sortable columns -> underlying SQL column.
 */
function gws_dd_sortable_columns() {
    return [
        'part'        => 'part',
        'series'      => 'series',
        'family'      => 'family',
        'brand'       => 'brand',
        'list_price'  => 'list_price',
        'net_price'   => 'net_price',
    ];
}

/**
 * Shared query builder for both the DataTables feed and the CSV export.
 * $limit = null means "no limit" (used for export).
 */
function gws_dd_query($tier, $search, $order_col, $order_dir, $limit = null, $offset = 0) {
    global $wpdb;

    $map    = gws_dd_tier_view_map();
    $view   = $map[$tier] ?? null;
    $period = GWS_PRICE_PERIOD;

    // Views/tables define their passthrough columns in UPPERCASE and their computed
    // columns in lowercase (see SHOW CREATE VIEW) — MySQL returns result columns
    // named exactly as defined when no alias is given, so every column must be
    // explicitly aliased to a known-lowercase name here.
    if ($view) {
        $select = "SELECT part AS part, SERIES AS series, TOOL_TYPE AS tool_type, FAMILY AS family,
                    BRAND AS brand, FULL_DESCRIPTION AS full_description, LIST_PRICE AS list_price,
                    net_price AS net_price, qty_1 AS qty_1, qty_2 AS qty_2, qty_3 AS qty_3, qty_6 AS qty_6,
                    qty_9 AS qty_9, qty_12 AS qty_12, qty_24 AS qty_24, qty_48 AS qty_48
                    FROM `{$view}`";
        $count_select = "SELECT COUNT(*) FROM `{$view}`";
    } else {
        $table = "master_price_data_{$period}";
        $select = "SELECT part AS part, SERIES AS series, TOOL_TYPE AS tool_type, FAMILY AS family,
                    BRAND AS brand, FULL_DESCRIPTION AS full_description, LIST_PRICE AS list_price,
                    LIST_PRICE AS net_price,
                    LIST_QTY_1 AS qty_1, LIST_QTY_2 AS qty_2, LIST_QTY_3 AS qty_3, LIST_QTY_6 AS qty_6,
                    LIST_QTY_9 AS qty_9, LIST_QTY_12 AS qty_12, LIST_QTY_24 AS qty_24, LIST_QTY_48 AS qty_48
                    FROM `{$table}`";
        $count_select = "SELECT COUNT(*) FROM `{$table}`";
    }

    $where = '';
    $args  = [];
    if ($search !== '') {
        $like   = '%' . $wpdb->esc_like($search) . '%';
        $where  = " WHERE (part LIKE %s OR full_description LIKE %s OR series LIKE %s OR family LIKE %s OR brand LIKE %s)";
        $args   = [$like, $like, $like, $like, $like];
    }

    $total = (int) ($args
        ? $wpdb->get_var($wpdb->prepare($count_select . $where, $args))
        : $wpdb->get_var($count_select . $where));

    $sortable = gws_dd_sortable_columns();
    $order_col = $sortable[$order_col] ?? 'part';
    $order_dir = strtolower($order_dir) === 'desc' ? 'DESC' : 'ASC';

    $sql = $select . $where . " ORDER BY `{$order_col}` {$order_dir}";
    if ($limit !== null) {
        $sql .= $wpdb->prepare(' LIMIT %d OFFSET %d', $limit, $offset);
    }

    $rows = $args
        ? $wpdb->get_results($wpdb->prepare($sql, $args))
        : $wpdb->get_results($sql);

    return [$rows, $total];
}

add_action('wp_ajax_gws_parts_price_dt', 'gws_dd_handle_datatables_request');
function gws_dd_handle_datatables_request() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Not logged in');
    }

    $tier      = gws_dd_resolve_tier();
    $start     = (int) ($_GET['start'] ?? 0);
    $length    = max(1, min(100, (int) ($_GET['length'] ?? 25)));
    $search    = sanitize_text_field($_GET['search']['value'] ?? '');
    $order_idx = (int) ($_GET['order'][0]['column'] ?? 0);
    $order_dir = sanitize_text_field($_GET['order'][0]['dir'] ?? 'asc');

    $columns    = array_keys(gws_dd_sortable_columns());
    $col_lookup = ['part', 'series', 'family', 'brand', 'list_price', 'net_price'];
    $order_col  = $col_lookup[$order_idx] ?? 'part';

    [$rows, $total] = gws_dd_query($tier, $search, $order_col, $order_dir, $length, $start);

    $data = [];
    foreach ($rows as $row) {
        $data[] = [
            $row->part,
            $row->series,
            $row->tool_type,
            $row->family,
            $row->brand,
            $row->full_description,
            $row->list_price !== null ? number_format((float) $row->list_price, 2) : '-',
            $row->net_price !== null ? number_format((float) $row->net_price, 2) : '-',
            $row->qty_1 !== null ? number_format((float) $row->qty_1, 2) : '-',
            $row->qty_2 !== null ? number_format((float) $row->qty_2, 2) : '-',
            $row->qty_3 !== null ? number_format((float) $row->qty_3, 2) : '-',
            $row->qty_6 !== null ? number_format((float) $row->qty_6, 2) : '-',
            $row->qty_9 !== null ? number_format((float) $row->qty_9, 2) : '-',
            $row->qty_12 !== null ? number_format((float) $row->qty_12, 2) : '-',
            $row->qty_24 !== null ? number_format((float) $row->qty_24, 2) : '-',
            $row->qty_48 !== null ? number_format((float) $row->qty_48, 2) : '-',
        ];
    }

    wp_send_json([
        'draw'            => (int) ($_GET['draw'] ?? 1),
        'recordsTotal'    => $total,
        'recordsFiltered' => $total,
        'data'            => $data,
    ]);
}

add_action('admin_post_gws_parts_price_export', 'gws_dd_handle_csv_export');
function gws_dd_handle_csv_export() {
    if (!is_user_logged_in()) {
        wp_redirect('https://staging.gwstoolgroup.com/sign-in');
        exit;
    }

    $tier   = gws_dd_resolve_tier_for_export();
    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    [$rows,] = gws_dd_query($tier, $search, 'part', 'asc', null, 0);

    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="gws-parts-pricing-' . sanitize_file_name($tier) . '-' . date('Y-m-d') . '.csv"');

    $out = fopen('php://output', 'w');
    fputcsv($out, [
        'Part', 'Series', 'Tool Type', 'Family', 'Brand', 'Description',
        'List Price', 'Net Price', 'Qty 1+', 'Qty 2+', 'Qty 3+', 'Qty 6+', 'Qty 9+', 'Qty 12+', 'Qty 24+', 'Qty 48+',
    ]);

    foreach ($rows as $row) {
        fputcsv($out, [
            $row->part,
            $row->series,
            $row->tool_type,
            $row->family,
            $row->brand,
            $row->full_description,
            $row->list_price,
            $row->net_price,
            $row->qty_1,
            $row->qty_2,
            $row->qty_3,
            $row->qty_6,
            $row->qty_9,
            $row->qty_12,
            $row->qty_24,
            $row->qty_48,
        ]);
    }

    fclose($out);
    exit;
}
