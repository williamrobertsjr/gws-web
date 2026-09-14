<?php
$context = Timber::context();

if (!is_user_logged_in()) {
    $current_url = home_url( $_SERVER['REQUEST_URI'] );
    wp_redirect( esc_url_raw( add_query_arg( 'redirect_to', $current_url, home_url( '/sign-in' ) ) ) );
    exit;
}

$role = get_current_user_role();

$context['userRole']     = $role;
$context['isPrivileged'] = in_array($role, ['administrator', 'sales'], true);
$context['tierLabel']    = get_user_role_display($role);
$context['priceEffectiveDate'] = gws_dd_get_last_updated();

Timber::render('page-pricing.twig', $context);
