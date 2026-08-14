<?php
/**
 * Bulk add-to-cart AJAX handler used by the multipart cart form.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


// Bulk add to cart via AJAX (multipart add)
add_action('wp_ajax_bulk_add_to_cart', 'gws_bulk_add_to_cart');
add_action('wp_ajax_nopriv_bulk_add_to_cart', 'gws_bulk_add_to_cart');

function gws_bulk_add_to_cart() {
    $start = microtime(true);

    if (empty($_POST['parts'])) {
        wp_send_json_error(['message' => 'Missing parts parameter'], 400);
    }

    $raw = $_POST['parts'];

    // Handle both JSON array and newline-delimited strings
    if (is_array($raw)) {
        $skus = $raw;
    } elseif (is_string($raw)) {
        $decoded = json_decode(stripslashes($raw), true);
        if (is_array($decoded)) {
            $skus = $decoded;
        } else {
            $lines = preg_split('/\r\n|\r|\n/', $raw);
            $skus = array_filter(array_map('trim', $lines));
        }
    } else {
        wp_send_json_error(['message' => 'Invalid parts format'], 400);
    }

    if (empty($skus)) {
        wp_send_json_error(['message' => 'No valid SKUs provided'], 400);
    }

    $skus = array_unique($skus);
    if (count($skus) > 50) {
        error_log('⚠️ bulk_add_to_cart aborted — too many SKUs: ' . count($skus));
        wp_send_json_error(['message' => 'Too many parts. Please limit to 50 at a time.'], 400);
    }

    global $wpdb;
    $placeholders = implode(',', array_fill(0, count($skus), '%s'));
    $query = "
        SELECT meta_value AS sku, post_id
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_sku'
        AND meta_value IN ($placeholders)
    ";
    $prepared = $wpdb->prepare($query, $skus);
    $results = $wpdb->get_results($prepared);

    $sku_map = [];
    foreach ($results as $row) {
        $sku_map[$row->sku] = (int) $row->post_id;
    }

    $added = [];
    $not_found = [];

    foreach ($skus as $sku) {
        if (isset($sku_map[$sku])) {
            WC()->cart->add_to_cart($sku_map[$sku], 1);
            $added[] = $sku;
        } else {
            $not_found[] = $sku;
        }
    }

    WC()->cart->calculate_totals();
    $elapsed = round(microtime(true) - $start, 3);

    // Build a clear message for the UI
    if (!empty($added) && !empty($not_found)) {
        $message = sprintf(
            'Added %d part(s), but the following were not found: %s',
            count($added),
            implode(', ', $not_found)
        );
    } elseif (!empty($added)) {
        $message = sprintf('All %d part(s) added successfully.', count($added));
    } else {
        $message = 'No matching parts were found — nothing added.';
    }

    error_log("bulk_add_to_cart completed in {$elapsed}s — added " . count($added) . ", missing " . count($not_found));

    wp_send_json_success([
        'added'       => $added,
        'not_found'   => $not_found,
        'message'     => $message,
        'elapsed'     => $elapsed,
    ]);
}
