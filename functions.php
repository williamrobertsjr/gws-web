<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

// Load Composer dependencies.
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/StarterSite.php';

require_once get_template_directory() . '/lib/part-pricing.php';

add_action('init', function() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        $test_part = 'XYZ123'; // Replace with a real part number in your DB
        $price = gws_get_part_list_price($test_part);
        error_log("Price for part {$test_part}: " . $price);
    }
});

// Load discounts logic for WooCommerce and distributor tiers sitewide
require_once get_template_directory() . '/views/woo/discounts.php';

add_filter('timber/twig/environment/options', function ($options) {
    $options['debug'] = true;
    return $options;
});


Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = [ 'templates', 'views' ];

new StarterSite();

if ( ! class_exists( 'Timber' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
    } );

    return;
}

Timber::$dirname = array( 'views', 'templates' );

function enqueue_tailwind_output_styles() {
    wp_enqueue_style( 'tailwind-output', get_template_directory_uri() . '/output.css', array(), filemtime( get_template_directory() . '/output.css' ) );
    wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style.css', array('tailwind-output'), filemtime( get_template_directory() . '/style.css' ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_tailwind_output_styles' );

// Woocommerce integration with Timber
function theme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'theme_add_woocommerce_support');

function timber_set_product($post)
{
    global $product;

    if (is_woocommerce()) {
        $product = wc_get_product($post->ID);
    }
}

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');

// Set Global Options conetext from ACF Pro options page
add_filter('timber/context', function($context) {
    $context['options'] = get_fields('option');
    return $context;
});

add_filter( 'timber/twig', function( $twig ) {
    $twig->addFilter( new \Twig\TwigFilter( 'custom_excerpt', function( $text, $length = 20 ) {
        return wp_trim_words( $text, $length );
    } ) );
    return $twig;
} );

add_filter( 'wpseo_metabox_prio', 'lower_yoast_metabox_priority' );

/**
 * Lowers the metabox priority to 'core' for Yoast SEO's metabox.
 *
 * @param string $priority The current priority.
 *
 * @return string $priority The potentially altered priority.
 */
function lower_yoast_metabox_priority( $priority ) {
  return 'low';
}


// ─────────────────────────────────────────────────────────────
// Rewrite /series-slug → index.php?pagename=series&series_id=slug
// ─────────────────────────────────────────────────────────────
add_action('init', function () {
  add_rewrite_rule(
    '^series-([^/]+)/?$',
    'index.php?pagename=series&series_id=$matches[1]',
    'top'
  );
});

// Register the custom query variable "series_id"
add_filter('query_vars', function ($vars) {
  $vars[] = 'series_id';
  return $vars;
});


// ─────────────────────────────────────────────────────────────
// Redirect legacy ?series_id=Smart%20Cut URLs to /series-smart-cut
// ─────────────────────────────────────────────────────────────
add_action('template_redirect', function () {
  if (is_page('series') && isset($_GET['series_id'])) {
    $raw = $_GET['series_id'];

    // If URL contains spaces (e.g. from %20), redirect to proper hyphenated slug
    if (strpos($raw, ' ') !== false) {
      $hyphenated = str_replace(' ', '-', $raw);
      $redirect_url = home_url("/series-$hyphenated");
      wp_redirect($redirect_url, 301);
      exit;
    }
  }
});

// Function to modify permalink structure for sub type custom post types
function tooltype_permalink_structure($post_link, $post, $leavename) {
    if (strpos($post_link, '%tool_type%') === FALSE) return $post_link;

    // Get taxonomy terms
    $terms = wp_get_object_terms($post->ID, 'tool_type');
    if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
        $taxonomy_slug = $terms[0]->slug;
    } else {
        $taxonomy_slug = 'general';  // default slug if no term is found
    }

    return str_replace('%tool_type%', $taxonomy_slug, $post_link);
}
add_filter('post_type_link', 'tooltype_permalink_structure', 1, 3);



// Function to add Max Mega Menu plugin to base.twig
function get_my_menu() {
    return wp_nav_menu(array(
        'theme_location' => 'max_mega_menu_1',
        'echo' => false
    ));
}
add_filter('timber/context', function ($context) {
    $context['my_menu'] = get_my_menu();
    return $context;
});

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
    
    $exempt_companies = [
        'Grainger',
        'US Tool Group',
        'Ewie',
        'EGC - Ewie',
    ];
    // Add any additional companies that should be exempt from the 7% price increase plus 20%
    $exempt_20 = [
        'Ewie',
        'EGC - Ewie',
    ];
    
    $is_exempt = in_array($user_company, $exempt_companies) ? 'true' : 'false';
    $exempt_plus = in_array($user_company, $exempt_20) ? 'true' : 'false';
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

// make user role column sortable in the users admin dashboard
function add_role_column( $columns ) {
    $columns['role'] = 'Role';
    return $columns;
}
add_filter( 'manage_users_columns', 'add_role_column' );
function display_role_column_content( $value, $column_name, $user_id ) {
    $user = get_userdata( $user_id );
    $role = $user->roles ? array_shift( $user->roles ) : '';
    return $column_name === 'role' ? $role : $value;
}
add_action( 'manage_users_custom_column', 'display_role_column_content', 10, 3 );
function make_role_column_sortable( $columns ) {
    $columns['role'] = 'role';
    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'make_role_column_sortable' );


function redirect_lostpassword_page() {
    // Avoid undefined index notices on PHP 8.0+
    $is_get      = ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET' );
    $is_login    = ( isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php' );
    $action      = isset($_GET['action']) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';

    if ( $is_get && $is_login && $action === 'lostpassword' ) {
        wp_redirect( home_url( '/lost-password/' ) );
        exit;
    }
}
add_action('init', 'redirect_lostpassword_page');

function custom_logout_redirect() {
    // Get the home URL dynamically
    $home_url = home_url();
    
    // Define the redirect URL, e.g., to the homepage or any specific path
    $redirect_url = $home_url; // Redirect to the homepage
    
    wp_redirect($redirect_url);
    exit();
}
add_action('wp_logout', 'custom_logout_redirect');

// Add company to user profile meta
add_action('show_user_profile', 'custom_user_profile_fields');
add_action('edit_user_profile', 'custom_user_profile_fields');

function custom_user_profile_fields($user) {
    ?>
    <h3>Additional Profile Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_company">Company</label></th>
            <td>
                <input type="text" name="user_company" id="user_company" value="<?php echo esc_attr(get_the_author_meta('user_company', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description">Please enter your company name.</span>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'save_custom_user_profile_fields');

function save_custom_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'user_company', $_POST['user_company']);
}


function custom_login_redirect_role_based( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        // Check for admins
        if ( in_array( 'administrator', $user->roles ) ) {
            return home_url('/dashboard');
        } else {
            // Non-admin users
            return home_url('/dashboard/');
        }
    } else {
        return $redirect_to;
    }
}
add_filter('login_redirect', 'custom_login_redirect_role_based', 10, 3);

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
      exit;
   }
}

// Customize the password reset confirmation page
function custom_password_reset_confirmation() {
    if ( isset( $_GET['checkemail'] ) && $_GET['checkemail'] === 'confirm' ) {
        // Custom content for password reset confirmation
        echo '<img src="/wp-content/uploads/2024/01/GWS-Logo-White-Small-Small.png" alt="Your Logo">';
        echo '<div class="custom-password-reset-message">';
        echo '<h2 class="text-pale-blue">Password Reset Confirmation</h2>';
        echo '<p class="text-white">A password reset link has been sent to your email address. Please check your inbox and follow the instructions provided.</p>';
        
        echo '</div>';

        // Use custom styles
        wp_enqueue_style( 'custom-password-reset-styles', get_stylesheet_directory_uri() . '/style.css' );
    }
}
add_action( 'login_message', 'custom_password_reset_confirmation' );

// Force FacetWP to ignore the archive query, and use the custom query instead
add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
    if ( $query->is_archive() && $query->is_main_query() ) {
      $is_main_query = false;
    }
    return $is_main_query;
  }, 10, 2 );

add_action( 'after_setup_theme', function() {
    add_theme_support( 'woocommerce' );
} );

add_filter( 'facetwp_render_output', function( $output ) {
    $output['settings']['milling_types']['showSearch'] = false;
    return $output;
  });

//   temporarily disable SearchWP integration with WP All Import
add_filter( 'searchwp\integration\wp_all_import\enabled', '__return_false' );

//  Only index 'product' post type for facetwp to speed up indexing and improve performance
add_filter( 'facetwp_indexer_query_args', function( $args ) {
  $args['post_type'] = ['product']; // Index only these post types
  return $args;
});

// // delete extra skus from woocommerce products
// if ( defined( 'WP_CLI' ) && WP_CLI ) {
//     require_once ABSPATH . 'wp-content/delete-duplicate-skus.php';
// }


add_action('wp_ajax_update_cart_item', 'ajax_update_cart_item');
add_action('wp_ajax_nopriv_update_cart_item', 'ajax_update_cart_item');

function ajax_update_cart_item() {
    $key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = max(0, intval($_POST['quantity']));

    if ($quantity === 0) {
        WC()->cart->remove_cart_item($key);
    } else {
        WC()->cart->set_quantity($key, $quantity, true);
    }

    WC()->cart->calculate_totals();

    // Prepare updated totals for JS
    $item = WC()->cart->get_cart_item($key);

    $totals = [];
    if ($item) {
        $totals[$key] = [
            'subtotal' => wc_price($item['line_subtotal']),
        ];
    }

    $totals['cart_subtotal'] = WC()->cart->get_cart_subtotal();
    ob_start();
    wc_cart_totals_order_total_html();
    $totals['cart_total'] = ob_get_clean();

    wp_send_json_success([
        'cart_items' => $totals,
        'original_total_html' => $totals['cart_subtotal'],
        'discounted_total_html' => $totals['cart_total'],
    ]);
}

add_action('wp_ajax_remove_cart_item', 'ajax_remove_cart_item');
add_action('wp_ajax_nopriv_remove_cart_item', 'ajax_remove_cart_item');

function ajax_remove_cart_item() {
    $key = sanitize_text_field($_POST['cart_item_key']);
    WC()->cart->remove_cart_item($key);

    wp_send_json_success();
}

// Custom logic section

/**
 * Get discounted price by tier.
 *
 * @param float $price
 * @param string $tier
 * @return float
 */
function gws_get_discounted_price($price, $tier) {
    switch ($tier) {
        case 't1':
            return $price * 0.90;
        case 't2':
            return $price * 0.75;
        case 't3':
            return $price * 0.60;
        default:
            return $price;
    }
}

// Discounted product prices by tier AJAX handler
add_action('wp_ajax_get_discounted_product_prices_by_tier', 'get_discounted_product_prices_by_tier');
add_action('wp_ajax_nopriv_get_discounted_product_prices_by_tier', 'get_discounted_product_prices_by_tier');

function get_discounted_product_prices_by_tier() {
    if (empty($_GET['tier']) || empty($_GET['product_ids'])) {
        wp_send_json_error('Missing tier or product_ids', 400);
    }

    $tier = sanitize_text_field($_GET['tier']);
    $product_ids = array_map('intval', explode(',', $_GET['product_ids']));

    $discounted_prices = [];

    foreach ($product_ids as $product_id) {
        $product = wc_get_product($product_id);
        if (!$product) {
            continue;
        }

        $price = (float) $product->get_price();
        $discounted_price = gws_get_discounted_price($price, $tier);

        $discounted_prices[$product_id] = [
            'discounted_price_html' => wc_price($discounted_price),
        ];
    }

    wp_send_json_success(['discounted_prices' => $discounted_prices]);
}

add_action('wp_enqueue_scripts', function () {
    if (is_cart()) {
        wp_enqueue_script('custom-cart-ajax', get_template_directory_uri() . '/js/cart-ajax.js', ['jquery'], null, true);
        wp_localize_script('custom-cart-ajax', 'wc_cart_params', ['ajax_url' => admin_url('admin-ajax.php')]);
    }
});

// Clear cart via AJAX
add_action('wp_ajax_clear_cart', 'gws_clear_cart');
add_action('wp_ajax_nopriv_clear_cart', 'gws_clear_cart');

function gws_clear_cart() {
    WC()->cart->empty_cart();
    wp_send_json_success('Cart cleared');
}

function gws_enqueue_tier_scripts() {
    // Make sure wc_cart_params is available site-wide
    if (function_exists('wc_enqueue_js')) {
        wp_enqueue_script('wc-cart-fragments'); // ensures wc_cart_params is defined
    }

    wp_enqueue_script(
        'tier-selector',
        get_template_directory_uri() . '/js/tier-selector.js',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'cart-pricing',
        get_template_directory_uri() . '/js/cart-pricing.js',
        ['tier-selector'], // depends on tier-selector
        null,
        true
    );

    // Ensure wc_cart_params is available in JS
    wp_localize_script('cart-pricing', 'wc_cart_params', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'gws_enqueue_tier_scripts');

add_filter('timber/context', function ($context) {
    $context['userRole'] = '';

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $context['userRole'] = $user->roles[0] ?? '';
    }

    return $context;
});

// Disable WooCommerce cart fragments for performance
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_script('wc-cart-fragments');
    wp_deregister_script('wc-cart-fragments');
});

// Bulk add to cart via AJAX
add_action('wp_ajax_bulk_add_to_cart', 'gws_bulk_add_to_cart');
add_action('wp_ajax_nopriv_bulk_add_to_cart', 'gws_bulk_add_to_cart');

function gws_bulk_add_to_cart() {
    $start = microtime(true); // move this to the top to measure time properly

    if (empty($_POST['parts'])) {
        wp_send_json_error(['message' => 'Missing parts parameter'], 400);
    }

    $raw = $_POST['parts'];

    // Handle both JSON array and newline-delimited strings
    if (is_array($raw)) {
        $skus = $raw;
    } elseif (is_string($raw)) {
        // Try decoding JSON first
        $decoded = json_decode(stripslashes($raw), true);
        if (is_array($decoded)) {
            $skus = $decoded;
        } else {
            // Fallback: treat as newline/CSV string
            $lines = preg_split('/\r\n|\r|\n/', $raw);
            $skus = array_filter(array_map('trim', $lines));
        }
    } else {
        wp_send_json_error(['message' => 'Invalid parts format'], 400);
    }

    if (empty($skus)) {
        wp_send_json_error(['message' => 'No valid SKUs provided'], 400);
    }

    // Deduplicate and sanitize
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

    // Only calculate once
    WC()->cart->calculate_totals();

    error_log('bulk_add_to_cart completed in ' . round(microtime(true) - $start, 3) . 's');

    wp_send_json_success([
        'added' => $added,
        'not_found' => $not_found,
    ]);
}

// Exclude 'NULL' values from FacetWP indexing
add_filter( 'facetwp_index_row', function( $row, $args ) {
    if ( isset( $row['facet_value'] ) && strtoupper( trim( $row['facet_value'] ) ) === 'NULL' ) {
        return false; // skip only the 'NULL' value
    }
    return $row;
}, 10, 2 );

// Signature Maker function for conditional logic
add_filter( 'gform_notification_5', 'customize_notification_content', 10, 3 ); // Change '1' to your form ID

function customize_notification_content( $notification, $form, $entry ) {
    // Get field values
    $mobile = rgar( $entry, '3' ); // field ID for mobile number
    $office_ext = rgar( $entry, '13' ); // field ID for office extension

    // Build the dynamic parts of the notification message
    $mobile_html = !empty($mobile) ? "<p style='margin: 3px 0px; font-size: 14px; line-height: 110%; font-family: Arial, sans-serif; color: #222; background-color: transparent;'><strong>Mobile:</strong> $mobile</p>" : '';
    $office_html = !empty($office_ext) ? "<p style='margin: 3px 0px; font-size: 14px; line-height: 110%; font-family: Arial, sans-serif; color: #222; background-color: transparent;'><strong>Office:</strong> (877) 497-8665 x$office_ext</p>" : "<p style='margin: 3px 0px; font-size: 14px; line-height: 110%; font-family: Arial, sans-serif; color: #222; background-color: transparent;'><strong>Office:</strong> (877) 497-8665</p>";

    // Insert these into the notification message where appropriate
    $notification['message'] = str_replace('{dynamic_mobile}', $mobile_html, $notification['message']);
    $notification['message'] = str_replace('{dynamic_office}', $office_html, $notification['message']);

    return $notification;
}