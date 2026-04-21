<?php
/**
 * GWS Quote API (REST + SendGrid + DB)
 * Requires: logged-in user
 * Email: to current user, BCC billy.roberts@gwstoolgroup.com
 * Storage: wp_gws_quotes, wp_gws_quote_items
 */

/** --- DEPENDENCIES --- **/

require_once get_template_directory() . '/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// ---------------------------------------------------------------
// DB: Create tables on init if not exist
// ---------------------------------------------------------------
add_action('init', function () {
    global $wpdb;
    $quotes_table = $wpdb->prefix . 'gws_quotes';
    $items_table  = $wpdb->prefix . 'gws_quote_items';
    $flag         = 'gws_quotes_table_installed';

    if (!get_option($flag)) {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset = $wpdb->get_charset_collate();

        $sql_quotes = "CREATE TABLE {$quotes_table} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quote_id VARCHAR(50),
            customer_email VARCHAR(255),
            customer_name VARCHAR(255),
            customer_company VARCHAR(255),
            shipping_address TEXT,
            shipping_method VARCHAR(50),
            comments TEXT,
            test_tools TINYINT(1),
            role_label VARCHAR(100),
            original_total VARCHAR(50),
            discounted_total VARCHAR(50),
            sales_name VARCHAR(255),
            sales_email VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_created_at (created_at),
            INDEX idx_customer_email (customer_email),
            INDEX idx_quote_id (quote_id)
        ) {$charset};";

        $sql_items = "CREATE TABLE {$items_table} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quote_id VARCHAR(50),
            part VARCHAR(255),
            description TEXT,
            list_price VARCHAR(50),
            net_price VARCHAR(50),
            stock VARCHAR(100),
            qty INT,
            subtotal VARCHAR(50),
            INDEX idx_quote_id (quote_id)
        ) {$charset};";

        dbDelta($sql_quotes);
        dbDelta($sql_items);
        add_option($flag, 1);
    }
});

// ---------------------------------------------------------------
// REST: Register endpoints
// ---------------------------------------------------------------
add_action('rest_api_init', function () {
    register_rest_route('gws/v1', '/send-quote', [
        'methods'             => 'POST',
        'callback'            => 'gws_handle_send_quote',
        'permission_callback' => '__return_true',
        'args' => [
            'quote_id'         => ['required' => false],
            'name'             => ['required' => false],
            'company'          => ['required' => false],
            'email'            => ['required' => false],
            'sales_email'      => ['required' => false],
            'sales_name'       => ['required' => false],
            'comments'         => ['required' => false],
            'customer_message' => ['required' => false],
            'test_tools'       => ['required' => false],
            'testToolsContact' => ['required' => false],
            'testToolsAddress' => ['required' => false],
            'testToolsCompany' => ['required' => false],
            'testToolsShipping' => ['required' => false],
            'role_label'       => ['required' => false],
            'items'            => ['required' => false],
            'totals'           => ['required' => false],
            'table_html'       => ['required' => false],
        ],
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('gws/v1', '/print-quote', [
        'methods'             => 'POST',
        'callback'            => 'gws_handle_print_quote',
        'permission_callback' => '__return_true',
        'args' => [
            'name'             => ['required' => false],
            'company'          => ['required' => false],
            'email'            => ['required' => false],
            'sales_email'      => ['required' => false],
            'sales_name'       => ['required' => false],
            'comments'         => ['required' => false],
            'customer_message' => ['required' => false],
            'test_tools'       => ['required' => false],
            'testToolsContact' => ['required' => false],
            'testToolsAddress' => ['required' => false],
            'testToolsCompany' => ['required' => false],
            'role_label'       => ['required' => false],
            'items'            => ['required' => false],
            'totals'           => ['required' => false],
        ],
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('gws/v1', '/test-dompdf', [
        'methods'             => 'GET',
        'callback'            => 'gws_test_pdf',
        'permission_callback' => '__return_true',
    ]);
});

// ---------------------------------------------------------------
// Helper: Test Dompdf installation
// ---------------------------------------------------------------
function gws_test_pdf() {
    $options = new Options();
    $options->set('isHTML5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    $html = '<html><body style="font-family: Arial;">
        <h1>Test PDF</h1>
        <p>If you can see this, Dompdf is working!</p>
        <table border="1" style="border-collapse: collapse; width: 100%;">
            <tr><td>Cell 1</td><td>Cell 2</td></tr>
            <tr><td>Cell 3</td><td>Cell 4</td></tr>
        </table>
    </body></html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="test.pdf"');
    echo $dompdf->output();
    exit;
}

// ---------------------------------------------------------------
// Helper: Generate sequential quote ID (GWS-YYMMDD-NN)
// ---------------------------------------------------------------
function generate_quote_id() {
    global $wpdb;

    $date_part   = current_time('ymd');
    $today_start = current_time('Y-m-d 00:00:00');
    $today_end   = current_time('Y-m-d 23:59:59');

    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}gws_quotes
        WHERE created_at >= %s AND created_at <= %s",
        $today_start,
        $today_end
    ));

    $counter = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
    return "GWS-{$date_part}-{$counter}";
}

// ---------------------------------------------------------------
// Helper: Generate PDF from quote data
// ---------------------------------------------------------------
function gws_generate_quote_pdf($quote_id, $name, $email, $comments, $test_tools, $table_html, $role_label = '', $test_tools_info = [], $company = '', $sales_name = '', $sales_email = '') {
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf      = new Dompdf($options);
    $date_issued = current_time('m-d-Y');

    $html = '<html>
    <head>
      <style>
        body  { margin: 20px; font-family: Helvetica, Arial, sans-serif; }
        h3    { margin-bottom: 10px; }
        p     { margin: 2px 0; font-size: 13px; }
        hr    { border: none; border-top: 1px solid #ddd; margin: 15px 0; }
      </style>
    </head>
    <body>
        <img src="https://www.gwstoolgroup.com/wp-content/uploads/2024/12/gws_email_logo.png" alt="GWS Tool Group">
        <h3>Quote ID: ' . esc_html($quote_id) . '</h3>
        <p><strong>Date Issued:</strong> ' . esc_html($date_issued) . '</p>'
        . ($role_label ? '<p><strong>Discount:</strong> ' . esc_html($role_label) . '</p>' : '')
        . ($company    ? '<p><strong>Company:</strong> '  . esc_html($company)    . '</p>' : '')
        . '<p><strong>Name:</strong> '  . esc_html($name)  . '</p>'
        . '<p><strong>Email:</strong> ' . esc_html($email) . '</p>'
        . ($sales_name ? '<p><strong>Quoted By:</strong> ' . esc_html($sales_name) . '</p>' : '')
        . ($comments   ? '<p><strong>Comments:</strong><br>' . $comments . '</p>' : '') . '
        <hr>
        ' . $table_html . '
        <p style="font-size:12px; font-style:italic;">This quote is good for 15 days from the date issued.</p>
        <hr>
        <p style="font-size:12px; color:#999;">GWS Tool Group | (877) 497-8665 | sales@gwstoolgroup.com | gwstoolgroup.com</p>
    </body>
    </html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return $dompdf->output();
}

// ---------------------------------------------------------------
// Helper: Build HTML table from items/totals
// ---------------------------------------------------------------
function gws_build_quote_table_html($items = [], $totals = [], $test_tools = false) {
    // Fallback: pull from WooCommerce cart if no items passed
    if (empty($items) && function_exists('WC') && WC()->cart) {
        $items = [];
        foreach (WC()->cart->get_cart() as $cart_item) {
            $p          = $cart_item['data'];
            $qty        = (int) $cart_item['quantity'];
            $name       = $p ? $p->get_name() : '';
            $list       = $p ? wc_price((float) $p->get_meta('_regular_price', true)) : '';
            $line_total = isset($cart_item['line_total']) ? (float) $cart_item['line_total'] : 0.0;
            $net        = $qty ? wc_price($line_total / $qty) : wc_price(0);
            $subtotal   = wc_price($line_total);
            $items[]    = [
                'part'     => $name,
                'desc'     => '',
                'list'     => $list,
                'net'      => $net,
                'stock'    => '',
                'qty'      => $qty,
                'subtotal' => $subtotal,
            ];
        }
        if (empty($totals)) {
            $totals = [
                'original'   => wc_price(WC()->cart->get_subtotal()),
                'discounted' => wc_price(WC()->cart->get_total('edit')),
            ];
        }
    }

    ob_start(); ?>
    <table border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse;width:100%;font-family:Helvetica,Arial,sans-serif;font-size:13px;border:1px solid #ddd;">
      <thead>
        <tr style="background:#f7f7f7;font-weight:bold;">
          <th>Part</th>
          <th>Description</th>
          <th>List</th>
          <?php if (!$test_tools): ?>
            <th>Net</th>
          <?php endif; ?>
          <th>Stock</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <?php
          if ($test_tools) {
              $listPrice          = floatval(preg_replace('/[^0-9.]/', '', $it['list'] ?? '0'));
              $qty                = floatval($it['qty'] ?? 0);
              $calculatedSubtotal = wc_price($listPrice * $qty);
          } else {
              $calculatedSubtotal = $it['subtotal'] ?? '';
          }
          ?>
          <tr>
            <td><?= esc_html($it['part']  ?? '') ?></td>
            <td><?= esc_html($it['desc']  ?? '') ?></td>
            <td><?= wp_kses_post($it['list'] ?? '') ?></td>
            <?php if (!$test_tools): ?>
              <td><?= wp_kses_post($it['net'] ?? '') ?></td>
            <?php endif; ?>
            <td><?= esc_html($it['stock'] ?? '') ?></td>
            <td><?= esc_html($it['qty']   ?? '') ?></td>
            <td><?= wp_kses_post($calculatedSubtotal) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <?php if (!empty($totals)): ?>
        <tfoot>
          <?php
          $colspan       = $test_tools ? 5 : 6;
          $display_total = $test_tools
              ? ($totals['original']   ?? '')
              : ($totals['discounted'] ?? '');
          ?>
          <tr>
            <td colspan="<?= $colspan ?>" style="text-align:right;font-weight:bold;">Total:</td>
            <td><?= wp_kses_post($display_total) ?></td>
          </tr>
        </tfoot>
      <?php endif; ?>
    </table>
    <?php
    return trim(ob_get_clean());
}

// ---------------------------------------------------------------
// Handler: Print quote (returns PDF directly to browser)
// ---------------------------------------------------------------
function gws_handle_print_quote(WP_REST_Request $req) {
    $user = wp_get_current_user();

    $test_tools = (bool) ($req['test_tools'] ?? false);

    if ($test_tools) {
        return new WP_REST_Response([
            'success' => false,
            'error'   => 'Test tools requests cannot be printed. Please use email instead.',
        ], 400);
    }

    $email            = sanitize_email($req['email'] ?: $user->user_email);
    $name             = sanitize_text_field($req['name'] ?: $user->display_name);
    $company          = sanitize_text_field($req['company'] ?: get_user_meta($user->ID, 'company', true));
    $comments         = nl2br(wp_kses_post($req['comments']));
    $role_label       = sanitize_text_field($req['role_label']);
    $shipping_address = sanitize_text_field($req['testToolsAddress'] ?? '');

    $items      = $req['items'] ?? [];
    $totals     = $req['totals'] ?? [];
    $table_html = gws_build_quote_table_html($items, $totals, false);
    $quote_id   = generate_quote_id();

    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'gws_quotes',
        [
            'quote_id'         => $quote_id,
            'customer_email'   => $email,
            'customer_name'    => $name,
            'customer_company' => $company,
            'shipping_address' => $shipping_address,
            'comments'         => strip_tags($comments),
            'test_tools'       => 0,
            'role_label'       => $role_label,
            'original_total'   => $totals['original']   ?? '',
            'discounted_total' => $totals['discounted'] ?? '',
            'sales_name'       => '',
            'sales_email'      => '',
            'created_at'       => current_time('mysql'),
        ]
    );

    foreach ($items as $item) {
        $wpdb->insert(
            $wpdb->prefix . 'gws_quote_items',
            [
                'quote_id'    => $quote_id,
                'part'        => sanitize_text_field($item['part']    ?? ''),
                'description' => sanitize_text_field($item['desc']    ?? ''),
                'list_price'  => sanitize_text_field($item['list']    ?? ''),
                'net_price'   => sanitize_text_field($item['net']     ?? ''),
                'stock'       => sanitize_text_field($item['stock']   ?? ''),
                'qty'         => intval($item['qty'] ?? 0),
                'subtotal'    => sanitize_text_field($item['subtotal'] ?? ''),
            ]
        );
    }

    $pdf_output = gws_generate_quote_pdf($quote_id, $name, $email, $comments, false, $table_html, $role_label, [], $company, '', '');

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="quote-' . $quote_id . '.pdf"');
    header('Content-Length: ' . strlen($pdf_output));
    echo $pdf_output;
    exit;
}

// ---------------------------------------------------------------
// Handler: Send quote via email (SendGrid)
// ---------------------------------------------------------------
function gws_handle_send_quote(WP_REST_Request $req) {
    $user = wp_get_current_user();

    // Basic info
    $test_tools       = (bool) ($req['test_tools'] ?? false);
    $to_email         = sanitize_email($req['email'] ?: $user->user_email);
    $name             = sanitize_text_field($req['name'] ?: $user->display_name);
    $company          = sanitize_text_field($req['company'] ?: get_user_meta($user->ID, 'company', true));
    $comments         = nl2br(wp_kses_post($req['comments']));
    $customer_message = sanitize_textarea_field($req['customer_message'] ?? '');
    $role_label       = sanitize_text_field($req['role_label']);
    $testToolsAddress = nl2br(esc_html($req['testToolsAddress'] ?? ''));
    $shipping_address = $testToolsAddress;
    $shipping_method = sanitize_text_field($req['testToolsShipping'] ?? '');
    $sales_email      = sanitize_email($req['sales_email'] ?? '');
    $sales_name       = sanitize_text_field($req['sales_name'] ?? '');

    // Build table
    $items      = $req['items'] ?? [];
    $totals     = $req['totals'] ?? [];
    $table_html = gws_build_quote_table_html($items, $totals, $test_tools);

    // Test tools fields
    $test_tools_info = [
        'contact' => $req['testToolsContact'] ?? '',
        'company' => $req['testToolsCompany'] ?? '',
        'address' => $req['testToolsAddress'] ?? '',
    ];

    $quote_id    = generate_quote_id();
    $date_issued = current_time('m-d-Y');

    // Save quote to DB
    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix . 'gws_quotes',
        [
            'quote_id'         => $quote_id,
            'customer_email'   => $test_tools ? '' : $to_email,
            'customer_name'    => $test_tools ? sanitize_text_field($test_tools_info['contact']) : $name,
            'customer_company' => $test_tools ? sanitize_text_field($test_tools_info['company']) : $company,
            'shipping_address' => $shipping_address,
            'shipping_method' => $test_tools ? $shipping_method : '',
            'comments'         => strip_tags($comments),
            'test_tools'       => $test_tools ? 1 : 0,
            'role_label'       => $role_label,
            'original_total'   => $totals['original']   ?? '',
            'discounted_total' => $test_tools ? '' : ($totals['discounted'] ?? ''),
            'sales_name'       => $test_tools ? $sales_name : ($customer_message ? $sales_name : ''),
            'sales_email'      => $test_tools ? $sales_email : ($customer_message ? $sales_email : ''),
            'created_at'       => current_time('mysql'),
        ]
    );

    // Save line items to DB
    foreach ($items as $item) {
        $wpdb->insert(
            $wpdb->prefix . 'gws_quote_items',
            [
                'quote_id'    => $quote_id,
                'part'        => sanitize_text_field($item['part']     ?? ''),
                'description' => sanitize_text_field($item['desc']     ?? ''),
                'list_price'  => sanitize_text_field($item['list']     ?? ''),
                'net_price'   => sanitize_text_field($item['net']      ?? ''),
                'stock'       => sanitize_text_field($item['stock']    ?? ''),
                'qty'         => intval($item['qty'] ?? 0),
                'subtotal'    => sanitize_text_field($item['subtotal'] ?? ''),
            ]
        );
    }

    // ---------------------------------------------------------------
    // Build email payload based on quote type
    // ---------------------------------------------------------------
    if ($test_tools) {
        // === TEST TOOLS REQUEST (no PDF, goes to sales team) ===
        $subject = "New Test Tools Request from {$name} - {$quote_id}";
        $body = '<html>
        <head>
        <style>
            body { margin: 20px; font-family: Helvetica, Arial, sans-serif; }
            p    { margin: 2px 0; font-size: 13px; }
            hr   { border: none; border-top: 1px solid #ddd; margin: 15px 0; }
        </style>
        </head>
        <body>
        <p style="font-size:15px; margin-bottom:15px;">
            <strong>' . esc_html($sales_name) . '</strong> has submitted a test tools request (<strong>' . esc_html($quote_id) . '</strong>).
            To <strong>approve</strong>, forward this email to <a href="mailto:sales@gwstoolgroup.com">sales@gwstoolgroup.com</a>. To <strong>deny</strong>, reply to this email with your reason.
        </p>
        <p style="font-weight:bold; font-size:14px; border-bottom:1px solid #ddd; padding-bottom:5px; margin:15px 0 10px;">Ship To</p>
        <p style="font-size:13px; margin:2px 0;"><span style="color:#666;">Contact:</span> ' . esc_html($test_tools_info['contact']) . '</p>
        <p style="font-size:13px; margin:2px 0;"><span style="color:#666;">Company:</span> ' . esc_html($test_tools_info['company']) . '</p>
        <p style="font-size:13px; margin:2px 0;"><span style="color:#666;">Address:</span> ' . $testToolsAddress . '</p>
        <p style="font-size:13px; margin:2px 0;"><span style="color:#666;">Shipping:</span> ' . esc_html($shipping_method) . '</p>'
        . ($comments ? '<p style="font-size:13px; margin:2px 0;"><span style="color:#666;">Comments:</span> ' . $comments . '</p>' : '') . '
        <hr>
        ' . $table_html . '
        <hr>
        <p style="font-size:12px; color:#999;">GWS Tool Group | (877) 497-8665 | sales@gwstoolgroup.com | gwstoolgroup.com</p>
        </body>
        </html>';

        // For test tools requests, we want emails routed first to sales VPs for approval then to sales@ once approved.
        $east_region_emails = ['amie.greene@gwstoolgroup.com','scott.cross@gwstoolgroup.com','travis.coomer@gwstoolgroup.com','brian.mroz@gwstoolgroup.com','alex.meerovich@gwstoolgroup.com','cody.vancamp@gwstoolgroup.com','justin.phelps@gwstoolgroup.com','mike.littlejohn@gwstoolgroup.com','robert.redden@gwstoolgroup.com','lawrence.stenger@gwstoolgroup.com'];
        $west_region_emails = ['taylor.smale@gwstoolgroup.com','kent.carlsen@gwstoolgroup.com','brian.villa@gwstoolgroup.com','philip.fossee@gwstoolgroup.com','cesar.vazquez_c@gwstoolgroup.com','modesto.morales_c@gwstoolgroup.com','jarrett.stair@gwstoolgroup.com','jacob.furnace@gwstoolgroup.com','ricardo.corral@gwstoolgroup.com','phil.saltness@gwstoolgroup.com','jim.fite@gwstoolgroup.com','thad.riesenbeck@gwstoolgroup.com'];

        // Determine recipient based on sales person's region
        if (in_array(strtolower($sales_email), $east_region_emails)) {
            $to_vp = [['email' => 'justin.verburg@gwstoolgroup.com', 'name' => 'Justin Verburg']];
            // $to_vp = [['email' => 'billy.roberts@gwstoolgroup.com', 'name' => 'Billy Roberts']]; // testing
        } elseif (in_array(strtolower($sales_email), $west_region_emails)) {
            $to_vp = [['email' => 'greg.gundrum@gwstoolgroup.com', 'name' => 'Greg Gundrum']];
            // $to_vp = [['email' => 'billy.roberts@gwstoolgroup.com', 'name' => 'Billy Roberts']]; // testing
        } else {
            $to_vp = [['email' => 'billy.roberts@gwstoolgroup.com', 'name' => 'Billy Roberts']];
        }

        $test_tools_personalization = [
            'to'      => $to_vp,
            'subject' => $subject,
        ];
        // CC sales rep only if they're not already the VP recipient
        $vp_email = strtolower($to_vp[0]['email']);
        if ($sales_email && strtolower($sales_email) !== $vp_email) {
            $test_tools_personalization['cc'] = [['email' => $sales_email, 'name' => $sales_name]];
        }

        // BCC Billy on all test tools requests unless he's already receiving as VP or sales
        $billy               = 'billy.roberts@gwstoolgroup.com';
        $already_included_tt = [
            strtolower('sales@gwstoolgroup.com'),
            strtolower($sales_email),
            strtolower($to_vp[0]['email'])  // add this
        ];
        if (!in_array(strtolower($billy), $already_included_tt)) {
            $test_tools_personalization['bcc'] = [['email' => $billy]];
        }
        // For test tools requests, we send a single email to the VP with details in the body and no PDF attachment
        $payload = [
            'personalizations' => [$test_tools_personalization],
            'from'             => ['email' => 'no-reply@gwstoolgroup.com', 'name' => 'GWS Tool Group'],
            'reply_to'         => ['email' => $sales_email ?: 'sales@gwstoolgroup.com', 'name' => $sales_name],
            'content'          => [['type' => 'text/html', 'value' => $body]],
        ];

    } else {
        // === REGULAR QUOTE (PDF attached) ===

        $pdf_output = gws_generate_quote_pdf($quote_id, $name, $to_email, $comments, false, $table_html, $role_label, $test_tools_info, $company, $customer_message ? $sales_name : '', $sales_email);
        $pdf_base64 = base64_encode($pdf_output);
        $subject    = "GWS Tool Group Quote: {$quote_id}";

        // --- Customer email body ---
        if ($customer_message) {
            $customer_body = '<div style="font-family:Helvetica,Arial,sans-serif;">'
                . "<p style='font-size:14px; margin:5px 0px 10px;'>" . nl2br($customer_message) . "</p>"
                . "<hr>" 
                . "<p style='font-size:14px; margin:5px 0px 2px;'>Your quote ({$quote_id}) is attached. If you have further questions or would like to put in a purchase order for this quote, please contact our sales team at sales@gwstoolgroup.com.</p>"
                . "<p style='font-size:14px; margin:5px 0px 20px;'>Thank you,<br>GWS Tool Group</p>"
                . "<img src='https://www.gwstoolgroup.com/wp-content/uploads/2025/03/GWS-Signature-2.0.png' alt='GWS Tool Group' style='margin-bottom:20px;'>"
                . '</div>';
        } else {
            $customer_body = '<div style="font-family:Helvetica,Arial,sans-serif;">'
                . "<p style='font-size:14px; margin:5px 0px 2px;'>Hello {$name},</p>"
                . "<p style='font-size:14px; margin:5px 0px 2px;'>Your quote ({$quote_id}) is attached. If you have further questions or would like to put in a purchase order for this quote, please contact our sales team at sales@gwstoolgroup.com.</p>"
                . "<p style='font-size:14px; margin:5px 0px 20px;'>Thank you,<br>GWS Tool Group</p>"
                . "<img src='https://www.gwstoolgroup.com/wp-content/uploads/2025/03/GWS-Signature-2.0.png' alt='GWS Tool Group' style='margin-bottom:20px;'>"
                . '</div>';
        }

        // --- Sales notification body ---
        $sales_body = '<div style="font-family:Helvetica,Arial,sans-serif;">'
            . "<p style='font-size:14px; margin:5px 0px 2px;'>A quote has been sent to <strong>{$name}</strong>"
            . ($company ? " from <strong>{$company}</strong>" : '') . ".</p>"
            . "<p style='font-weight:bold; margin-bottom:0px; text-decoration:underline;'>Quote Details:</p>"
            . "<p style='font-size:13px; margin:0px;'><strong>Quote ID:</strong> {$quote_id}</p>"
            . "<p style='font-size:13px; margin:0px;'><strong>Customer Name:</strong> {$name}</p>"
            . "<p style='font-size:13px; margin:0px;'><strong>Customer Email:</strong> {$to_email}</p>"
            . ($company ? "<p style='font-size:13px; margin:0px;'><strong>Company:</strong> {$company}</p>" : '')
            . "<p style='font-size:13px; margin:0px;'><strong>Discount:</strong> {$role_label}</p>"
            . "<p style='font-size:13px; margin:0px;'><strong>Total:</strong> " . ($totals['discounted'] ?? '') . "</p>"
            . ($comments ? "<p style='margin:10px 0px; font-size:13px;'><strong>Comments:</strong><br>{$comments}</p>" : '')
            . ($sales_name && $sales_email ? "<p style='font-size:13px; margin:10px 0px 0px;'><strong>Quoted By:</strong> {$sales_name}</p>" : '')
            . '</div>';

        // --- Customer payload ---
        $billy = 'billy.roberts@gwstoolgroup.com';
        $customer_personalization = [
            'to'      => [['email' => $to_email, 'name' => $name]],
            'subject' => $subject,
        ];
        if (strtolower($to_email) !== strtolower($billy)) {
            $customer_personalization['bcc'] = [['email' => $billy]];
        }
        $payload = [
            'personalizations' => [$customer_personalization],
            'from'             => ['email' => 'no-reply@gwstoolgroup.com', 'name' => 'GWS Tool Group'],
            'reply_to'         => ['email' => 'sales@gwstoolgroup.com'],
            'content'          => [['type' => 'text/html', 'value' => $customer_body]],
            'attachments'      => [[
                'content'     => $pdf_base64,
                'filename'    => "quote-{$quote_id}.pdf",
                'type'        => 'application/pdf',
                'disposition' => 'attachment',
            ]],
        ];

        // --- Sales notification payload ---
        $sales_cc_email = $customer_message && $sales_email ? $sales_email : 'sales@gwstoolgroup.com';
        $sales_cc_name  = $customer_message && $sales_name  ? $sales_name  : 'GWS Sales Team';

        $already_included = [strtolower($sales_cc_email)];

        $sales_personalization = [
            'to'      => [['email' => $sales_cc_email, 'name' => $sales_cc_name]],
            'subject' => "Quote Sent: {$quote_id} — {$name}" . ($company ? " ({$company})" : ''),
        ];

        if (!in_array(strtolower($billy), $already_included)) {
            $sales_personalization['bcc'] = [['email' => $billy]];
        }

        $sales_payload = [
            'personalizations' => [$sales_personalization],
            'from'             => ['email' => 'no-reply@gwstoolgroup.com', 'name' => 'GWS Tool Group'],
            'reply_to'         => ['email' => 'sales@gwstoolgroup.com'],
            'content'          => [['type' => 'text/html', 'value' => $sales_body]],
            'attachments'      => [[
                'content'     => $pdf_base64,
                'filename'    => "quote-{$quote_id}.pdf",
                'type'        => 'application/pdf',
                'disposition' => 'attachment',
            ]],
        ];
    }

    // ---------------------------------------------------------------
    // Send via SendGrid
    // ---------------------------------------------------------------
    $sendgrid_key = defined('SENDGRID_API_KEY') ? SENDGRID_API_KEY : '';
    if (!$sendgrid_key) {
        return new WP_REST_Response(['success' => false, 'error' => 'missing_key'], 500);
    }

    // Send customer / test-tools email
    $res = wp_remote_post('https://api.sendgrid.com/v3/mail/send', [
        'timeout' => 15,
        'headers' => [
            'Authorization' => 'Bearer ' . $sendgrid_key,
            'Content-Type'  => 'application/json',
        ],
        'body' => wp_json_encode($payload),
    ]);

    if (is_wp_error($res)) {
        return new WP_REST_Response(['success' => false, 'error' => $res->get_error_message()], 500);
    }

    $code = wp_remote_retrieve_response_code($res);
    if ($code >= 300) {
        error_log('SendGrid customer email error (' . $code . '): ' . wp_remote_retrieve_body($res));
        return new WP_REST_Response(['success' => false, 'error' => 'SendGrid customer email error: ' . $code], 500);
    }

    // Send sales notification (regular quotes only)
    if (!$test_tools) {
        $res_sales  = wp_remote_post('https://api.sendgrid.com/v3/mail/send', [
            'timeout' => 15,
            'headers' => [
                'Authorization' => 'Bearer ' . $sendgrid_key,
                'Content-Type'  => 'application/json',
            ],
            'body' => wp_json_encode($sales_payload),
        ]);
        $sales_code = wp_remote_retrieve_response_code($res_sales);
        if ($sales_code >= 300) {
            error_log('SendGrid sales notification error (' . $sales_code . '): ' . wp_remote_retrieve_body($res_sales));
        }
    }

    return new WP_REST_Response(['success' => true, 'quote_id' => $quote_id], 200);
}