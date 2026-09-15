<?php
/**
 * Rapid Quote REST endpoint (/rapid-quote/v1/submit-quote).
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */

require_once __DIR__ . '/part-number.php';

// Rapid Quote handle quote submission
function handle_quote_submission( WP_REST_Request $request ) {
    global $wpdb;

    $parts = $request->get_param('part');
    $partsArray = explode("\n", $parts);

    $partsArray = array_filter($partsArray, function($value) { return trim($value) !== ''; });
    $partsArray = array_map('trim', $partsArray);

    if (empty($partsArray)) {
        return new WP_REST_Response(array('error' => 'No parts provided'), 400);
    }

    // Match case- and dash-insensitively (e.g. "450-100781a" and "450100781A"
    // both match "450-100781A"), but always return/display the canonical PN
    // as stored in rapid_quote.
    $normalizedInputs = array_map('gws_normalize_part_number', $partsArray);

    $placeholders = implode(', ', array_fill(0, count($normalizedInputs), '%s'));
    $query = $wpdb->prepare("SELECT * FROM `rapid_quote` WHERE REPLACE(REPLACE(UPPER(PN), '-', ''), ' ', '') IN ($placeholders) ORDER BY PN", $normalizedInputs);
    $results = $wpdb->get_results($query);

    // Normalized set of PNs that were found, to compare against normalized inputs
    $foundNormalized = array_map(function($item) {
        return gws_normalize_part_number($item->PN);
    }, $results);

    // Identify missing parts, reporting back the user's original typed value
    $missingParts = array();
    foreach ($partsArray as $index => $original) {
        if (!in_array($normalizedInputs[$index], $foundNormalized, true)) {
            $missingParts[] = $original;
        }
    }
    // Convert the missing parts array to a string with spaces after commas
    $missingPartsString = implode(', ', $missingParts);
    // Prepare the response
    $response = array(
        'found_parts' => $results, // Parts found in the database
        'missing_parts' => $missingPartsString // List of missing parts
    );

    return new WP_REST_Response($response, 200);
}

add_action('rest_api_init', function () {
    register_rest_route('rapid-quote/v1', '/submit-quote', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'handle_quote_submission',
        'permission_callback' => '__return_true'
    ));
});
