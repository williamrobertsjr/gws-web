<?php
// inc/distributor-autocomplete.php

function gws_register_distributor_routes() {
    register_rest_route('gws/v1', '/distributors', [
        'methods'             => 'GET',
        'callback'            => 'gws_get_distributors',
        'permission_callback' => '__return_true',
        'args'                => [
            's' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function($value) {
                    return strlen(trim($value)) >= 3;
                },
            ],
        ],
    ]);

    register_rest_route('gws/v1', '/distributors/validate', [
        'methods'             => 'POST',
        'callback'            => 'gws_validate_distributor',
        'permission_callback' => '__return_true',
        'args'                => [
            'company_name' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);
}
add_action('rest_api_init', 'gws_register_distributor_routes');


function gws_get_distributors(WP_REST_Request $request) {
    global $wpdb;

    $search = '%' . $wpdb->esc_like($request->get_param('s')) . '%';

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, company_name 
             FROM gws_distributors 
             WHERE status = 'active' 
             AND company_name LIKE %s 
             ORDER BY company_name ASC 
             LIMIT 15",
            $search
        )
    );

    return rest_ensure_response($results);
}


function gws_validate_distributor(WP_REST_Request $request) {
    global $wpdb;

    $company_name = $request->get_param('company_name');

    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM gws_distributors 
             WHERE company_name = %s AND status = 'active'",
            $company_name
        )
    );

    if ($exists) {
        return rest_ensure_response(['valid' => true]);
    }

    return new WP_Error(
        'invalid_distributor',
        'Company not found in approved distributor list.',
        ['status' => 422]
    );
}


// Server-side gatekeeper — runs at form submission, form 32 / field 11
add_filter('gform_field_validation_32_11', 'gws_validate_distributor_field', 10, 4);

function gws_validate_distributor_field($result, $value, $form, $field) {
    if (empty(trim($value))) {
        return $result;
    }

    global $wpdb;

    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM gws_distributors 
             WHERE company_name = %s AND status = 'active'",
            trim($value)
        )
    );

    if (!$exists) {
        $result['is_valid'] = false;
        $result['message'] = 'Please select a valid company from the list. If your company isn\'t listed, email sales@gwstoolgroup.com to have it added.';
    }

    return $result;
}