<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

// Load Composer dependencies.
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/StarterSite.php';

Timber\Timber::init();

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = [ 'templates', 'views' ];

new StarterSite();


// functions.php

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


/*  ☐  Add the rewrite       ───────────────────────────────────────────── */
add_action( 'init', function () {
    // /series-smart-cut  →  series_slug = smart-cut
    add_rewrite_rule(
        '^series-([A-Za-z0-9-]+)/?$',
        'index.php?pagename=series&series_slug=$matches[1]',
        'top'
    );
});

/*  ☐  Register the query-var ───────────────────────────────────────────── */
add_filter( 'query_vars', function ( $vars ) {
    $vars[] = 'series_slug';
    return $vars;
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

function register_custom_menus() {
    register_nav_menus(array(
        'header' => __('Header Menu'), // Existing location
        'new_main' => __('New Main Menu') // Add a new location
    ));
}
add_action('init', 'register_custom_menus');



// Function to add Max Mega Menu plugin to base.twig with conditional support
function get_my_menu() {
    if (is_page('test-page')) { // Replace 'test-page' with your actual slug or use is_page(ID)
        return wp_nav_menu(array(
            'theme_location' => 'new_main', // Use the custom "New Main" theme location
            'echo' => false
        ));
    }
    return wp_nav_menu(array(
        'theme_location' => 'max_mega_menu_1', // Default menu controlled by Max Mega Menu
        'echo' => false
    ));
}

add_filter('timber/context', function ($context) {
    $context['my_menu'] = get_my_menu();
    return $context;
});


add_filter('timber/context', function ($context) {
    $context['header_menu'] = wp_nav_menu(array(
        'theme_location' => 'header',
        'echo' => false
    ));

    $context['new_main_menu'] = wp_nav_menu(array(
        'theme_location' => 'new_main',
        'echo' => false
    ));

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
        'Harvey Tool',
        'Ewie',
        'EGC - Ewie',
        'MSC Industrial Direct',
    ];

    $is_exempt = in_array($user_company, $exempt_companies) ? 'true' : 'false';

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
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $GLOBALS['pagenow'] === 'wp-login.php' && $_GET['action'] === 'lostpassword') {
        wp_redirect(home_url('/lost-password/'));
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

// Create a default title passed in html-header.twig to give customization to page titles for dynamic pages
add_filter('timber/context', function ($context) {
    $context['wp_title'] = wp_get_document_title();
    return $context;
});

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

