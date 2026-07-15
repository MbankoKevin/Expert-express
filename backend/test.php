<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'autoload.php';

$mail = new PHPMailer;
$mail->setFrom('cornellekacy4@gmail.com', 'First Last');
$mail->addAddress("cornellekacy4@gmail.com");
$mail->addReplyTo("xxxx@domainname.com", "Reply");
$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}

?>