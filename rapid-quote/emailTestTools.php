<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // $name = isset($_POST['name']) ? $_POST['name'] : '';
    $recipientEmail = isset($_POST['userEmail']) ? $_POST['userEmail'] : '';
    // Append additional recipient emails
    $recipientEmail .= ', sales@gwstoolgroup.com';
    $companyName = isset($_POST['companyName']) ? $_POST['companyName'] : '';
    $companyContact = isset($_POST['companyContact']) ? $_POST['companyContact'] : '';
    $shippingAddress = isset($_POST['shippingAddress']) ? $_POST['shippingAddress'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    $userName = isset($_POST['userName']) ? $_POST['userName'] : '';
    
    
    // Table HTML content
    $tableHTML = isset($_POST['tableHTML']) ? $_POST['tableHTML'] : '';

    // Construct the email content
    $emailContent = <<<EOD
        <p style="font-size: 16px; margin: 2px 0px 20px; font-weight: 700;">New Test Tools Request</p>
        <p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Requested By: </strong></span>{$userName}</p>
        <p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Company: </strong></span>{$companyName}</p>
        <p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Company Contact: </strong></span>{$companyContact}</p>
        <p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Shipping Address: </strong></span><br>{$shippingAddress}</p>
        <p style="font-size: 14px;  margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Message: </strong></span><br>{$message}</p>
        <p style="font-size: 14px; text-transform: uppercase; border-bottom: 2px solid #222222;"><strong>Requested Tools</strong></p>
        {$tableHTML}
        EOD;

    // Email headers
    $headers = "From: rapidquote@gwstoolgroup.com\r\n";
    $headers .= "Reply-To: replyto@gwstoolgroup.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "BCC: billy.roberts@gwstoolgroup.com\r\n"; //uncomment when pushed to live

    // Send the email
    $mailSent = mail($recipientEmail, "Test Tools Request", $emailContent, $headers);

    // Check if mail is sent successfully
    if ($mailSent) {
        echo json_encode(["message" => "Email sent successfully."]);
    } else {
        echo json_encode(["error" => "Email sending failed."]);
    }
}

?>
