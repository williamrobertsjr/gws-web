<?php

/**
 * Parts & Pricing data-download page
 * - Server-side DataTables feed
 * - XLSX export
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
 * gws_calculate_discounted_price() (inc/discounts.php).
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
 * (inc/discounts.php), the gws_selected_tier cookie override is only
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
 * Shared query builder for both the DataTables feed and the XLSX export.
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
        $is_call_for_price = $row->list_price === null || (float) $row->list_price <= 0;
        $format = function ($value) use ($is_call_for_price) {
            if ($is_call_for_price) return 'Call for Price';
            return $value !== null ? number_format((float) $value, 2) : '-';
        };

        $data[] = [
            $row->part,
            $row->series,
            $row->tool_type,
            $row->family,
            $row->brand,
            $row->full_description,
            $format($row->list_price),
            $format($row->net_price),
            $format($row->qty_1),
            $format($row->qty_2),
            $format($row->qty_3),
            $format($row->qty_6),
            $format($row->qty_9),
            $format($row->qty_12),
            $format($row->qty_24),
            $format($row->qty_48),
        ];
    }

    wp_send_json([
        'draw'            => (int) ($_GET['draw'] ?? 1),
        'recordsTotal'    => $total,
        'recordsFiltered' => $total,
        'data'            => $data,
    ]);
}

/**
 * Cached XLSX exports.
 *
 * Each tier's file is built once, streamed to disk with OpenSpout (flat memory,
 * unlike PhpSpreadsheet which held ~512MB for the ~30k-row catalog), and reused
 * until the price table changes. The cache key includes the table's updated_at
 * from gws_data_table_updates, so editing master_price_data_{period} in the DB
 * (triggers fire even for direct MySQL edits) makes the next download rebuild —
 * no manual re-upload of per-tier spreadsheets.
 */
function gws_dd_export_dir() {
    $dir = trailingslashit(wp_upload_dir()['basedir']) . 'gws-price-exports';
    if (!is_dir($dir)) {
        wp_mkdir_p($dir);
        file_put_contents($dir . '/index.php', "<?php // Silence is golden.\n");
    }
    return $dir;
}

/**
 * Cache file path for a tier. The name carries an HMAC so files can't be fetched
 * by guessing a URL under uploads/ — downloads must go through the handler.
 * Returns [path, prefix]; prefix is shared by all versions of this tier's file.
 */
function gws_dd_export_path($tier) {
    $stamp  = (string) gws_dd_get_last_updated_raw();
    $prefix = 'prices-' . sanitize_file_name($tier) . '-' . substr(hash_hmac('sha256', $tier, wp_salt('auth')), 0, 16);
    $key    = substr(md5(GWS_PRICE_PERIOD . '|' . $stamp), 0, 12);
    return [gws_dd_export_dir() . "/{$prefix}-{$key}.xlsx", $prefix];
}

function gws_dd_get_last_updated_raw() {
    global $wpdb;
    $table = 'master_price_data_' . GWS_PRICE_PERIOD;

    // Tracking table + triggers may not exist in every environment (production
    // doesn't have them yet) — probe quietly instead of logging a DB error.
    $suppress = $wpdb->suppress_errors(true);
    $stamp = $wpdb->get_var($wpdb->prepare(
        "SELECT updated_at FROM gws_data_table_updates WHERE table_name = %s",
        $table
    ));
    $wpdb->suppress_errors($suppress);
    if ($stamp) return $stamp;

    // No tracking row: fingerprint the table contents instead, so the cache
    // still invalidates when prices change (a constant key would serve stale
    // files forever). CHECKSUM TABLE on ~30k rows is a fast scan.
    $row = $wpdb->get_row("CHECKSUM TABLE `{$table}`", ARRAY_N);
    return $row ? 'cs' . $row[1] : 'none';
}

/**
 * Write the tier's workbook to $path, fetching rows in chunks so neither the
 * result set nor the writer holds the whole catalog in memory.
 */
function gws_dd_write_export($tier, $search, $path) {
    $options = new \OpenSpout\Writer\XLSX\Options();
    // Part, Series, Tool Type, Family, Brand, Description
    foreach ([12, 10, 12, 20, 14, 45] as $i => $w) {
        $options->setColumnWidth($w, $i + 1);
    }
    $options->setColumnWidthForRange(11, 7, 16);
    $writer = new \OpenSpout\Writer\XLSX\Writer($options);
    $writer->openToFile($path);
    $writer->getCurrentSheet()->setName('Parts & Pricing');

    $bold = (new \OpenSpout\Common\Entity\Style\Style())->withFontBold(true);
    $num  = (new \OpenSpout\Common\Entity\Style\Style())->withFormat('0.00');

    $writer->addRow(\OpenSpout\Common\Entity\Row::fromValuesWithStyle([
        'Part', 'Series', 'Tool Type', 'Family', 'Brand', 'Description',
        'List Price', 'Net Price', 'Qty 1+', 'Qty 2+', 'Qty 3+', 'Qty 6+', 'Qty 9+', 'Qty 12+', 'Qty 24+', 'Qty 48+',
    ], $bold));

    $chunk  = 2000;
    $offset = 0;
    do {
        [$rows,] = gws_dd_query($tier, $search, 'part', 'asc', $chunk, $offset);

        foreach ($rows as $row) {
            $is_call_for_price = $row->list_price === null || (float) $row->list_price <= 0;
            $price = function ($value) use ($is_call_for_price, $num) {
                if ($is_call_for_price) return \OpenSpout\Common\Entity\Cell::fromValue('Call for Price');
                if ($value === null)    return \OpenSpout\Common\Entity\Cell::fromValue(null);
                return \OpenSpout\Common\Entity\Cell::fromValue((float) $value, $num);
            };

            // Part is the primary lookup key and often has leading zeros ("00002") —
            // pass it as an explicit string cell so they survive.
            $writer->addRow(new \OpenSpout\Common\Entity\Row([
                new \OpenSpout\Common\Entity\Cell\StringCell((string) $row->part),
                \OpenSpout\Common\Entity\Cell::fromValue($row->series),
                \OpenSpout\Common\Entity\Cell::fromValue($row->tool_type),
                \OpenSpout\Common\Entity\Cell::fromValue($row->family),
                \OpenSpout\Common\Entity\Cell::fromValue($row->brand),
                \OpenSpout\Common\Entity\Cell::fromValue($row->full_description),
                $price($row->list_price), $price($row->net_price),
                $price($row->qty_1), $price($row->qty_2), $price($row->qty_3), $price($row->qty_6),
                $price($row->qty_9), $price($row->qty_12), $price($row->qty_24), $price($row->qty_48),
            ]));
        }

        $offset += $chunk;
        $got = count($rows);
        unset($rows);
    } while ($got === $chunk);

    $writer->close();
}

/**
 * Return the cached file for $tier, building it first if the price table has
 * changed since the last build. Builds to a temp file and renames, so a
 * concurrent download never serves a half-written workbook.
 */
function gws_dd_ensure_export($tier) {
    [$path, $prefix] = gws_dd_export_path($tier);
    if (is_file($path)) return $path;

    @set_time_limit(300);
    $tmp = $path . '.' . wp_generate_password(8, false) . '.tmp';
    gws_dd_write_export($tier, '', $tmp);
    rename($tmp, $path);

    // Drop superseded versions of this tier's file.
    foreach (glob(gws_dd_export_dir() . "/{$prefix}-*.xlsx") ?: [] as $old) {
        if ($old !== $path) @unlink($old);
    }
    return $path;
}

function gws_dd_send_file($path, $tier) {
    nocache_headers();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="gws-parts-pricing-' . sanitize_file_name($tier) . '-' . date('Y-m-d') . '.xlsx"');
    header('Content-Length: ' . filesize($path));
    while (ob_get_level()) ob_end_clean();
    readfile($path);
}

add_action('admin_post_gws_parts_price_export', 'gws_dd_handle_price_export');
function gws_dd_handle_price_export() {
    if (!is_user_logged_in()) {
        $sign_in_url = home_url('/sign-in');
        $return_url  = wp_get_referer();
        if ( $return_url ) {
            $sign_in_url = add_query_arg( 'redirect_to', $return_url, $sign_in_url );
        }
        wp_redirect( esc_url_raw( $sign_in_url ) );
        exit;
    }

    $tier   = gws_dd_resolve_tier_for_export();
    $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

    if ($search === '') {
        gws_dd_send_file(gws_dd_ensure_export($tier), $tier);
        exit;
    }

    // Filtered exports aren't cached (unbounded set of search terms).
    $tmp = wp_tempnam('gws-export');
    gws_dd_write_export($tier, $search, $tmp);
    gws_dd_send_file($tmp, $tier);
    @unlink($tmp);
    exit;
}

/**
 * wp gws price-export warm — pre-build every tier's file so the first download
 * after a price update isn't the one that waits. Safe to re-run; up-to-date
 * files are skipped.
 */
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::add_command('gws price-export warm', function () {
        foreach (array_keys(gws_dd_tier_view_map()) as $tier) {
            $t = microtime(true);
            gws_dd_ensure_export($tier);
            WP_CLI::log(sprintf('%-12s ok (%.1fs)', $tier, microtime(true) - $t));
        }
        WP_CLI::success('Price exports are current.');
    });
}
