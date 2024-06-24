<?php 
$context = Timber::context();
// Obtain the current user's ID
$user_id = get_current_user_id(); // Define $user_id for the current user
// First, get the current user role
$userRole = get_current_user_role();

// Check if the user role is 'none' and redirect
if ( !is_user_logged_in()) {
    wp_redirect('https://www.gwstoolgroup.com/sign-in');
    exit; // Always call exit after wp_redirect
}

// Connect to the Database
include 'db_connection.php';

// Fetch all series data
$stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE catalog = 'Y' AND series IN ('1010', 'BXPS', 'BX300') ORDER BY `order`");
$stmt->execute();
$result = $stmt->get_result();

$allSeries = [];
while($row = $result->fetch_assoc()) {
    $allSeries[] = $row;
}

$series_list = array_filter($allSeries, function($series) {
    return in_array($series['series'], ['1010', 'BXPS', 'BX300']);
});





// do_shortcode function to get the map content
$map_content = do_shortcode('[display-map id="132298"]');
// Adding user role to the context
$context['userRole'] = get_current_user_role();
$context['map_content'] = $map_content;
$context['series_list'] = $series_list;
$context['new_tier'] = get_field('new_tier', 'user_'.$user_id);
Timber::render('page-dashboard.twig', $context); 

?>