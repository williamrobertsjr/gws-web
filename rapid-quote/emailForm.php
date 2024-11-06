<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assign variables for form fields
    $name = isset($_POST['userName']) ? $_POST['userName'] : '';
    $recipientEmail = isset($_POST['userEmail']) ? $_POST['userEmail'] : '';
    $recipientEmail .= ', sales@gwstoolgroup.com'; // Add additional emails
    $company = isset($_POST['userCompany']) ? $_POST['userCompany'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    // Table HTML content
    $tableHTML = isset($_POST['tableHTML']) ? $_POST['tableHTML'] : '';

    
    // $emailContent = '<p style="font-size: 18px; text-transform: uppercase;"><strong>Name: </strong></p>' . $name . "\n<br>";
    // $emailContent .= '<p style="font-size: 18px; text-transform: uppercase;"><strong>Message: </strong></p> ' . $message . "\n\n <br>";
    // $emailContent .= $tableHTML;


    // Construct the email content
    $emailContent = <<<EOD
        <p style="font-size: 16px; margin: 2px 0px 20px; font-weight: 700;">New Rapid Quote Request</p>
        <p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Name: </strong></span>{$name}</p>
        <p style="font-size: 14px; margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Company: </strong></span>{$company}</p>
        <p style="font-size: 14px;  margin: 2px 0px;"><span style="text-transform: uppercase;"><strong>Message: </strong></span><br>{$message}</p>
        <p style="font-size: 14px; text-transform: uppercase; border-bottom: 2px solid #222222;"><strong>Requested Tools</strong></p>
        {$tableHTML}
        EOD;


    // Email headers
    $headers = "From: rapidquote@gwstoolgroup.com\r\n";
    $headers .= "Reply-To: replyto@gwstoolgroup.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "BCC: billy.roberts@gwstoolgroup.com\r\n"; //uncomment when pushed to live

    // Send the email
    $mailSent = mail($recipientEmail, "New Rapid Quote Request", $emailContent, $headers);

    // Check if mail is sent successfully
    if ($mailSent) {
        echo json_encode(["message" => "Email sent successfully."]);
    } else {
        echo json_encode(["error" => "Email sending failed."]);
    }
}

