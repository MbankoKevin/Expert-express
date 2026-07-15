        <?php
use PHPMailer\PHPMailer\PHPMailer;
$msg = '';
//Don't run this unless we're handling a form submission
if (array_key_exists('save', $_POST)) {
    date_default_timezone_set('Etc/UTC');
    require 'autoload.php';
    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP - requires a local mail server
    //Faster and safer than using mail()
    $mail->isSMTP();
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
//Whether to use SMTP authentication
    $mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "cornellekacy4@gmail.com";
//Password to use for SMTP authentication
    $mail->Password = "cornellekacy456";
    //Use a fixed address in your own domain as the from address
    //**DO NOT** use the submitter's address here as it will be forgery
    //and will cause your messages to fail SPF checks
    $mail->setFrom($email, 'USPC Logistics');
    //Send the message to yourself, or whoever should receive contact for submissions
    $mail->addAddress($_POST['email'], 'USPC Tracking Number');
    //Put the submitter's address in a reply-to header
    //This will fail if the address provided is invalid,
    //in which case we should ignore the whole request
    if ($mail->addReplyTo($_POST['email'], $_POST['myname'])) {
        $mail->Subject = 'USPC Logistics';
        //Keep it simple - don't use HTML
        $mail->isHTML(false);
        //Build a simple message body
        $mail->Body = <<<EOT
        sender name: {$_POST['myname']}
        sender Email: {$_POST['myemail']}
        receiver's name: {$_POST['jkname']}
        receiver's name: {$_POST['email']}
        Tracking Number: {$_POST['tracking']}
        Message: 'HELLO {$_POST['jkname']}, IF YOU ARE RECEIVING THIS MASSAGE, IT MEANS A SHIPMENT WAS SUCCESSFULY PLACES IN OUR AGENCY USING YOUR DETAILS. COPY THE TRACKING NUMBER ABOVE AND GO TO uspclogistics.net/tracking.php TO TRACK YOUR SHIPMENT'
        EOT;
        //Send the message, check for errors
        if (!$mail->send()) {
            //The reason for failing to send will be in $mail->ErrorInfo
            //but you shouldn't display errors to users - process the error, log it on your server.
            $msg = 'Sorry, something went wrong. Please try again later.'. $mail->ErrorInfo;
        } else {
                        echo "<script>alert('Tracking Number Successfully Mailed To Jk.');
            window.location.href = 'mail.php';
            </script>";
        }
    } else {
        $msg = 'Invalid email address, message ignored.';
    }
}
?>