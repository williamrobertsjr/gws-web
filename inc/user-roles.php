<?php
/**
 * Current-user role helper and the role / pricing-exemption flags printed to the page.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


function get_current_user_role() {
    if(is_user_logged_in()) {
        $user = wp_get_current_user();
        $role = (array) $user->roles;
        return $role[0];
    } 
    else {
      return false;
    }
 }

// Add a script to the footer to set the specialCompanyExempt variable based on user meta
// This will be used in rapid-quote.js to determine if the user is exempt from the 7% price increase
// The user meta key is 'company' and the values are compared against a predefined list of exempt companies
// The script will set window.specialCompanyExempt to true or false based on the user's company

add_action('wp_footer', function () {
    if (!is_user_logged_in()) {
        echo "<script>window.specialCompanyExempt = false;</script>";
        return;
    }

    $user_id = get_current_user_id();
    $user_company = get_user_meta($user_id, 'company', true); // <-- FIXED
    
    // shared with inc/discounts.php -- see inc/pricing-exempt-companies.php
    $is_exempt   = in_array($user_company, gws_exempt_companies(), true) ? 'true' : 'false';
    $exempt_plus = in_array($user_company, gws_plus_25_companies(), true) ? 'true' : 'false';
    echo "<script>window.specialCompanyExemptPlus = {$exempt_plus};</script>";
    echo "<script>window.specialCompanyExempt = {$is_exempt};</script>";
});

add_action('wp_head', 'print_user_role');
function print_user_role() {
   $user_role = get_current_user_role();
   if ($user_role !== false) {
       echo "<script>var userRole = '$user_role';</script>";
   }
}
