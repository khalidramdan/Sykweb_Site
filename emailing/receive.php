<?php

/**
 * This example shows how to send via Google's Gmail servers using XOAUTH2 authentication.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
//Alias the League Google OAuth2 provider class
use League\OAuth2\Client\Provider\Google;

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Africa/Casablanca');

//Load dependencies from composer
//If this causes an error, run 'composer install'
require 'vendor/autoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
//SMTP::DEBUG_OFF = off (for production use)
//SMTP::DEBUG_CLIENT = client messages
//SMTP::DEBUG_SERVER = client and server messages
$mail->SMTPDebug = SMTP::DEBUG_OFF;

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;

//Set the encryption mechanism to use - STARTTLS or SMTPS
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Set AuthType to use XOAUTH2
$mail->AuthType = 'XOAUTH2';

//Fill in authentication details here
//Either the gmail account owner, or the user that gave consent
$email = 'sykweb10@gmail.com';
$clientId = '681275209486-5c6o8ivaualja3himmhqpc0mtqn72ko3.apps.googleusercontent.com';
$clientSecret = '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';

//Obtained by configuring and running get_oauth_token.php
//after setting up an app in Google Developer Console.
$refreshToken = '1//03K9SdnP3wdwjCgYIARAAGAMSNwF-L9IrlOE-0hCVMqWvN01kpkCoCizVE85dnvLiYabzAEBHdcFtPSSGq5ERlLYh0tv46vqN1lg';

//Create a new OAuth2 provider instance
$provider = new Google(
    [
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
    ]
);

//Pass the OAuth provider instance to PHPMailer
$mail->setOAuth(
    new OAuth(
        [
            'provider' => $provider,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName' => $email,
        ]
    )
);

//Set who the message is to be sent from
//For gmail, this generally needs to be the same as the user you logged in as
$mail->setFrom($_POST['email'], $_POST['name']);

//Set who the message is to be sent to
$mail->addAddress($email);

// if you want to send email to multiple users, then add the email addresses you which you want to send.
//$mail->addAddress('reciver2@gmail.com');
//$mail->addAddress('reciver3@gmail.com');

$mail->isHTML(true);

//Set the subject line
$mail->Subject = 'from: ' . $_POST['email'];

$mail->Body    = nl2br('Phone Number: ' . $_POST['phone'] . "\r" . 'Message: ' . $_POST['message']);

//Replace the plain text body with one created manually
// $mail->AltBody = 'This is a plain-text message body';

//For Attachments
//$mail->addAttachment('/var/tmp/file.tar.gz');  // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // You can specify the file name

//send the message, check for errors
if (!$mail->send()) {
    echo '<script>
    setTimeout(function(){
        alert("Mailer Error: ' . $mail->ErrorInfo . '");
    }, 7000);
    </script>';
} else {
    echo '<script>
    setTimeout(function(){
        alert("Message sent!");
    }, 7000);
    </script>';
}
