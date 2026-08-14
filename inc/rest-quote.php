<?php
/**
 * Rapid Quote REST endpoint (/rapid-quote/v1/submit-quote).
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


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

    $placeholders = implode(', ', array_fill(0, count($partsArray), '%s'));
    $query = $wpdb->prepare("SELECT * FROM `rapid_quote` WHERE PN IN ($placeholders) ORDER BY PN", $partsArray);
    $results = $wpdb->get_results($query);

    // Create an array of found parts
    $foundParts = array_map(function($item) {
        return $item->PN; // Assuming 'PN' is the part number in your results
    }, $results);

    // Identify missing parts
    $missingParts = array_diff($partsArray, $foundParts);
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
