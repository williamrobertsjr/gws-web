<?php
/**
 * Plugin Name: GWS Live Stock (rapid_quote → read-only)
 * Description: Displays live stock from the rapid_quote table by SKU (no writes to Woo). Adds shortcode, REST, and PDP hook.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) exit;

final class GWS_Live_Stock {
    /**
     * ==== CONFIG ====
     * If you already have a single "available" column, set AVAILABLE_COL to that name (e.g., 'available')
     * and set USE_AVAILABLE_DIRECT = true. Otherwise it will compute: qty_on_hand - COALESCE(qty_allocated, 0).
     */
    const TABLE_NAME             = 'rapid_quote';   // same table name
    const KEY_COL                = 'PN';            // SKU column in your table
    const QTY_ON_HAND_COL        = 'QTY_ON_HAND';   // available stock
    const QTY_ALLOCATED_COL      = '';              // none
    const AVAILABLE_COL          = '';              // none
    const USE_AVAILABLE_DIRECT   = false;           // compute from QTY_ON_HAND

    // Cache results in transients (seconds). Daily manual updates → 24h is fine; set lower if you want faster reflection.
    const CACHE_TTL              = 86400; // 24 hours

    // Where to auto-render on PDP (position 25 is after price/title area).
    const PDP_HOOK_PRIORITY      = 25;

    // public static function bootstrap() {
    //     add_action('init', [__CLASS__, 'register_shortcode']);
    //     add_action('rest_api_init', [__CLASS__, 'register_rest']);
    //     add_action('woocommerce_single_product_summary', [__CLASS__, 'render_on_pdp'], self::PDP_HOOK_PRIORITY);
    //     add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_js']);
    // }

    public static function bootstrap() {
        add_action('init', [__CLASS__, 'register_shortcode']); // ✅ gws_live_stock shortcode
        add_action('init', [__CLASS__, 'register_stocking_status_shortcode']); // ✅ stocking_status shortcode
        add_action('rest_api_init', [__CLASS__, 'register_rest']);
        add_action('woocommerce_single_product_summary', [__CLASS__, 'render_on_pdp'], self::PDP_HOOK_PRIORITY);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_js']);
    }

    /** Basic identifier whitelist for column/table names (letters, numbers, underscore). */
    private static function ident(string $raw, string $fallback = ''): string {
        $id = preg_replace('/[^A-Za-z0-9_]/', '', $raw ?? '');
        return $id !== '' ? $id : $fallback;
    }

    /** Build a fully-qualified table name. If your table has a prefix (e.g., wp_rapid_quote), change TABLE_NAME accordingly. */
    private static function table_name(): string {
        global $wpdb;
        // If your table is in the same DB without prefix, this is fine. If you actually use "{$wpdb->prefix}rapid_quote", set TABLE_NAME to that.
        return self::ident(self::TABLE_NAME);
    }

    /** Core lookup (with transient cache). Returns int|null (null = not found). */
    public static function get_available_by_sku(string $sku): ?int {
        $sku = trim($sku);
        if ($sku === '') return null;

        $cache_key = 'gws_live_stock_' . md5($sku);
        $cached = get_transient($cache_key);
        if ($cached !== false) {
            return $cached === 'na' ? null : (int)$cached;
        }

        global $wpdb;

        $table   = self::table_name();
        $keyCol  = self::ident(self::KEY_COL, 'PN');

        // Choose expression
        if (self::USE_AVAILABLE_DIRECT && self::AVAILABLE_COL !== '') {
            $availableCol = self::ident(self::AVAILABLE_COL, 'available');
            $sql = "SELECT `$availableCol` AS available
                    FROM `$table`
                    WHERE CONVERT(`$keyCol` USING utf8mb4) COLLATE utf8mb4_unicode_520_ci = %s
                    LIMIT 1";
        } else {
            $onCol   = self::ident(self::QTY_ON_HAND_COL, 'qty_on_hand');
            $allocCol= self::ident(self::QTY_ALLOCATED_COL ?: '', '');
            $expr = $allocCol !== ''
                ? "GREATEST(`$onCol` - COALESCE(`$allocCol`, 0), 0)"
                : "GREATEST(`$onCol`, 0)";
            $sql = "SELECT $expr AS available
                    FROM `$table`
                    WHERE `$keyCol` = %s
                    LIMIT 1";
        }

        $row = $wpdb->get_row($wpdb->prepare($sql, $sku), ARRAY_A);

        $available = $row ? max(0, (int)$row['available']) : null;

        set_transient($cache_key, $available === null ? 'na' : $available, self::CACHE_TTL);
        return $available;
    }

    /** Core lookup for both QTY_ON_HAND and DURRIE_QTY_ON_HAND by SKU. */
    public static function get_full_stock_by_sku(string $sku): ?array {
        global $wpdb;
        $sku = trim($sku);
        if ($sku === '') return null;

        $table   = self::table_name();
        $keyCol  = self::ident(self::KEY_COL, 'PN');

        $sql = "SELECT QTY_ON_HAND, DURRIE_QTY_ON_HAND
                FROM `$table`
                WHERE `$keyCol` = %s
                LIMIT 1";

        $row = $wpdb->get_row($wpdb->prepare($sql, $sku), ARRAY_A);

        if (!$row) return null;

        return [
            'QTY_ON_HAND' => (int)($row['QTY_ON_HAND'] ?? 0),
            'DURRIE_QTY_ON_HAND' => isset($row['DURRIE_QTY_ON_HAND']) ? (int)$row['DURRIE_QTY_ON_HAND'] : null,
            'TOTAL_STOCK' => isset($row['DURRIE_QTY_ON_HAND'])
                ? (int)$row['QTY_ON_HAND'] + (int)$row['DURRIE_QTY_ON_HAND']
                : (int)$row['QTY_ON_HAND'],
        ];
    }

    /** Shortcode: [gws_live_stock sku="ABC" show_label="1" id="foo" wrap="1"] */
    public static function register_shortcode() {
        add_shortcode('gws_live_stock', function($atts){
            $atts = shortcode_atts([
                'sku'        => '',
                'show_label' => '1',
                'wrap'       => '1',
                'id'         => '',
            ], $atts, 'gws_live_stock');

            $sku = $atts['sku'];
            if ($sku === '' && is_product()) {
                $product = wc_get_product(get_the_ID());
                if ($product) $sku = (string)$product->get_sku();
            }
            if ($sku === '') return '';

            $available = self::get_available_by_sku($sku);
            // $label = $atts['show_label'] === '1' ? 'In stock: ' : '';
            $idAttr = $atts['id'] ? ' id="'.esc_attr($atts['id']).'"' : '';

            $content = $available === null ? '—' : (string)(int)$available;
            $html = '<span class="gws-live-stock" data-gws-sku="'.esc_attr($sku).'"'.$idAttr.'>'.
                    esc_html($content) . '</span>';

            return $atts['wrap'] === '1' ? '<div class="gws-live-stock-wrap">'.$html.'</div>' : $html;
        });

        add_shortcode('gws_full_stock', function($atts){
            $atts = shortcode_atts([
                'sku' => '',
            ], $atts, 'gws_full_stock');

            $sku = $atts['sku'];
            if ($sku === '' && is_product()) {
                $product = wc_get_product(get_the_ID());
                if ($product) $sku = (string)$product->get_sku();
            }
            if ($sku === '') return '';

            $stock = GWS_Live_Stock::get_full_stock_by_sku($sku);

            if (!$stock) return '<span class="gws-full-stock">—</span>';

            $html = '<div class="gws-full-stock">';
            $html .= '<p class="mb-0"><span class="font-bold product-stock mb-2">Stock</span>: ' . number_format($stock['QTY_ON_HAND']) . '</p>';
            $html .= '<div class="flex gap-x-2">';
            $html .= '<p class="text-sm"><span class="font-medium">CA</span>: ' . number_format($stock['QTY_ON_HAND']) . '</p>';
            if ($stock['DURRIE_QTY_ON_HAND'] !== null) {
                $html .= '<p class="text-sm"><span class="font-medium">IL</span>: ' . number_format($stock['DURRIE_QTY_ON_HAND']) . '</p>';
            }
            $html .= '</div></div>';

            return $html;
        });
    }

    /** REST: /wp-json/gws/v1/stock/{sku} → { sku, available, found } */
    public static function register_rest() {
        register_rest_route('gws/v1', '/stock/(?P<sku>[^/]+)', [
            'methods'  => 'GET',
            'permission_callback' => '__return_true',
            'callback' => function($req){
                $sku = sanitize_text_field($req['sku']);
                $avail = self::get_available_by_sku($sku);
                return [
                    'sku' => $sku,
                    'available' => (int)($avail ?? 0),
                    'found' => $avail !== null,
                ];
            }
        ]);
    }

    /** Auto-render on PDP under price; variation-safe via JS update when a variation is selected. */
    public static function render_on_pdp() {
        if (!function_exists('is_product') || !is_product()) return;
        global $product;
        if (!$product instanceof WC_Product) return;

        // If variable product, show placeholder that updates when variation chosen.
        $sku = $product->is_type('variable') ? '' : (string)$product->get_sku();
        $initial = $sku ? self::get_available_by_sku($sku) : null;

        echo '<div class="gws-live-stock-block" style="margin-top:.5rem">';
        echo '<strong>Stock:</strong> ';
        // Use a stable ID so our JS can update it.
        echo do_shortcode('[gws_live_stock sku="'.esc_attr($sku).'" show_label="0" wrap="0" id="gws-live-stock"]');
        if (!$sku) {
            echo '<span id="gws-live-stock">'.($initial === null ? '—' : (int)$initial).'</span>';
        }
        echo '</div>';
    }

    /** Tiny JS to update when a variation is selected. */
    public static function enqueue_js() {
        if (!is_product()) return;
        // Attach after Woo's variation script so 'found_variation' exists.
        wp_add_inline_script('wc-add-to-cart-variation', "
            (function(){
              var form = document.querySelector('.variations_form');
              if (!form) return;
              form.addEventListener('found_variation', function(e){
                try {
                  var v = e.detail && e.detail[0] ? e.detail[0] : (e.detail || window.jQuery && window.jQuery(e.currentTarget).data('product_variations'));
                } catch(_) { var v = null; }
              });
              // jQuery fallback (Woo still triggers via jQuery on many setups)
              if (window.jQuery) {
                jQuery('.variations_form').on('found_variation', function(evt, variation){
                  var sku = variation && variation.sku ? variation.sku : '';
                  var el  = document.getElementById('gws-live-stock');
                  if (!sku || !el) return;
                  fetch('/wp-json/gws/v1/stock/' + encodeURIComponent(sku))
                    .then(function(r){ return r.json(); })
                    .then(function(j){ el.textContent = j.found ? j.available : '—'; })
                    .catch(function(){ el.textContent = '—'; });
                });
              }
            })();
        ");
 
    }
    
    public static function get_stocking_status_by_sku(string $sku): ?string {
        global $wpdb;
        $sku = trim($sku);
        if ($sku === '') return null;

        $sql = "SELECT stocking_status 
                FROM stocking_status 
                WHERE part = %s 
                LIMIT 1";
        return $wpdb->get_var($wpdb->prepare($sql, $sku));
    }

    public static function register_stocking_status_shortcode() {
        add_shortcode('gws_stocking_status', function($atts) {
            $atts = shortcode_atts([
                'sku' => '',
                'show_label' => '1',
                'wrap' => '1',
            ], $atts, 'gws_stocking_status');

            $sku = $atts['sku'];
            if ($sku === '' && is_product()) {
                $product = wc_get_product(get_the_ID());
                if ($product) $sku = $product->get_sku();
            }
            if ($sku === '') return '';

            // ✅ Fetch stock and stocking status
            $stock = GWS_Live_Stock::get_available_by_sku($sku);
            $status = GWS_Live_Stock::get_stocking_status_by_sku($sku);

            // ✅ Only show a message if stock < 1
            if ($stock >= 1) {
                return ''; // nothing shown if there’s at least one in stock
            }
            // $status = GWS_Live_Stock::get_stocking_status_by_sku($sku);

            $message_map = [
                'Standard' => '<span class=" font-medium">Part stocked regularly</span>',
                'Pull & Mod' => '<span class=" font-medium">Minor modification required. 2-3 days to ship.</span>',
                'Non-stock Standard' => '<span class=" font-medium">Estimated Leadtime: 2-5 wks. Made to order.</span>',
            ];

            $label = $atts['show_label'] === '1' ? '<strong>Status:</strong> ' : '';
            $display = $message_map[$status] ?? '<span class="text-gray-500">—</span>';

            $html = '<span class="gws-stocking-status" data-gws-sku="'.esc_attr($sku).'">' .
                    $label . $display . '</span>';

            return $atts['wrap'] === '1'
                ? '<div class="gws-stocking-status-wrap">'.$html.'</div>'
                : $html;
        });
    }

}

GWS_Live_Stock::bootstrap();
