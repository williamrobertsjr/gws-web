<?php
/**
 * Login, logout, lost-password and profile-field handling.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */



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
            <th><label for="company">Company</label></th>
            <td>
                <input type="text" name="company" id="company" value="<?php echo esc_attr(get_the_author_meta('company', $user->ID)); ?>" class="regular-text" /><br />
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

    // 'company' is the key the registration form, discount logic and quote API all use.
    // this field wrote 'user_company' until Aug 2026, so edits here never reached pricing.
    if (isset($_POST['company'])) {
        update_user_meta($user_id, 'company', sanitize_text_field(wp_unslash($_POST['company'])));
    }
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
