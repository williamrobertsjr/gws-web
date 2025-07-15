<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign variables for form fields
    $name = isset($_POST['userName']) ? htmlspecialchars($_POST['userName']) : '';
    $recipientEmail = isset($_POST['userEmail']) ? htmlspecialchars($_POST['userEmail']) : '';
    $company = isset($_POST['userCompany']) ? htmlspecialchars($_POST['userCompany']) : '';
    $message = isset($_POST['message']) ? nl2br(htmlspecialchars($_POST['message'])) : '';
    $quoteTotal = isset($_POST['quoteTotal']) ? number_format((float)$_POST['quoteTotal'], 2) : '0.00';
    $tableHTML = isset($_POST['tableHTML']) ? $_POST['tableHTML'] : '';

    // Construct the email content with concatenation
    $emailContent = '<p style="font-size: 16px; margin: 2px 0px 20px; font-weight: 700;">New VIP PROMO Order</p>';
    $emailContent .= '<p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Name: </strong></span>' . $name . '</p>';
    $emailContent .= '<p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Company: </strong></span>' . $company . '</p>';
    $emailContent .= '<p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Message: </strong></span><br>' . $message . '</p>';
    $emailContent .= '<p style="font-size: 14px; text-transform: uppercase; border-bottom: 2px solid #222222;"><strong>Requested Tools</strong></p>';
    $emailContent .= $tableHTML;

    // Email headers
    $headers = "From: rapidquote@gwstoolgroup.com\r\n";
    $headers .= "Reply-To: replyto@gwstoolgroup.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "BCC: billy.roberts@gwstoolgroup.com\r\n"; // Uncomment when ready for production

    // Send the email
    $mailSent = mail($recipientEmail, "New VIP Promo Order", $emailContent, $headers);

    // Check if mail is sent successfully
    if ($mailSent) {
        echo json_encode(["message" => "Email sent successfully."]);
    } else {
        echo json_encode(["error" => "Email sending failed."]);
    }
}
