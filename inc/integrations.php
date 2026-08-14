<?php
/**
 * FacetWP indexing filter and Gravity Forms notification customisations.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


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

// ─────────────────────────────────────────────────────────────
// Render Quote Table (Field 18) as real HTML in Form 46 notifications
// ─────────────────────────────────────────────────────────────
add_filter('gform_pre_send_email', function($email, $message_format, $notification, $entry) {
    // Only apply to your Quote Form (ID 46)
    if ((int) rgar($entry, 'form_id') !== 46) {
        return $email;
    }

    // Get the HTML from hidden field 18 (input_18)
    $quote_html = rgar($entry, '18');
    if (!$quote_html) {
        return $email;
    }

    // Decode entities so <table> renders properly
    $quote_html = html_entity_decode($quote_html);

    // Optional: strip out stray <br> tags that may appear
    /* $quote_html = preg_replace('/<br\s*\/?>/i', '', $quote_html); */

    // Ensure this email sends as HTML
    $email['content_type'] = 'text/html';

    // Replace any known merge tags in the message if present
    $replaced = false;
    foreach (['{Quote Table:18}', '{Field:18}', '{Products:18}', '{Field ID:18}'] as $tag) {
        if (strpos($email['message'], $tag) !== false) {
            $email['message'] = str_replace($tag, $quote_html, $email['message']);
            $replaced = true;
        }
    }

    // If no tag was found, append the quote table automatically
    if (!$replaced) {
        $email['message'] .= '<hr style="margin:16px 0;border:none;border-top:1px solid #ddd;">'
                           . '<h3 style="margin:0 0 10px;font-family:Helvetica,Arial,sans-serif;">Quote Table</h3>'
                           . $quote_html;
    }

    return $email;
}, 10, 4);
