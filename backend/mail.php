<?php 
include 'header.php'; 
include 'sidebar.php'; 

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader once at the top
require 'autoload.php';

$errorMsg = "";

if (array_key_exists('email', $_POST)) {
    date_default_timezone_set('Etc/UTC');
    
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        // Tell PHPMailer to use SMTP
        $mail->isSMTP();
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'sitestarter2.xyz';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        
        // Credentials
        $mail->Username = "send@sitestarter2.xyz";
        $mail->Password = "Cornellekacy45";
        
        // Sender and Recipient settings
        $mail->setFrom('send@sitestarter2.xyz', 'Happy Tails Movers Inc');
        $mail->addAddress($_POST['email'], 'Happy Tails Movers Inc');
        
        // Safely add reply-to
        if ($mail->addReplyTo($_POST['email'], $_POST['jkname'])) {
            $mail->Subject = 'Happy Tails Movers Inc';
            $mail->isHTML(true);
            
            // Attach inline images
            $mail->addEmbeddedImage('bar.png', 'logoimg', 'bar.png');
            $mail->addEmbeddedImage('logo.png', 'logoimg1', 'logo.png');
            
            // Format dynamic form data
            $jk   = htmlspecialchars($_POST['jkname'], ENT_QUOTES, 'UTF-8');
            $jkt  = htmlspecialchars($_POST['tracking'], ENT_QUOTES, 'UTF-8');
            $jktg = nl2br(htmlspecialchars($_POST['gram'], ENT_QUOTES, 'UTF-8'));
            
            $mail->Body = "
                <img src=\"cid:logoimg1\" /><br><br>
                <h3><strong style='color: rgb(255,153,0);'>HELLO</strong> <strong style='text-transform: capitalize; color: rgb(255,153,0);'>$jk</strong></h3>
                <p>$jktg</p>
                <br>
                <h3>Tracking No : $jkt</h3>  
                <img src=\"cid:logoimg\" />
                <br><br>
                <a href='https://happytailsmovers.info/track.php'>https://happytailsmovers.info/track.php</a>
                <br><br><br><br>
                <p style='font-size: 11px; color: #555;'>This invoice is processed by Happy Tails Movers Logistics Inc. 1289 Franklin Street<br>
                Greensboro AL, USA. If you need more information, please contact info@happytailsmovers.info</p>
                <p style='font-size: 11px; color: #555;'>By using our services, you agree to happytailsmovers.info Privacy Notice and Conditions of Use.</p>
                <p style='font-size: 11px; color: #555;'>This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message</p>
            ";
            
            // Send email
            $mail->send();
            
            echo "<script>
                alert('Message Successfully Sent! We will get back to you shortly.');
                window.location.href = 'mail.php';
            </script>";
            exit;
        } else {
            $errorMsg = 'Invalid email address configuration, message ignored.';
        }
    } catch (Exception $e) {
        $errorMsg = 'Sorry, something went wrong. Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>

<!-- Page wrapper -->
<div class="page-wrapper" style="min-height: 100vh; padding-top: 20px; background-color: #f8fafc;">
    <!-- Bread crumb -->
    <div class="row page-titles mx-0 py-3 bg-white border-bottom border-light-subtle">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary fw-bold mb-0">Dashboard</h3> 
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb m-0 justify-content-md-end bg-transparent p-0 small">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active text-muted">Dashboard</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    
    <!-- Container fluid -->
    <div class="container-fluid py-4">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-md-8 mx-auto">
                
                <?php if (!empty($errorMsg)): ?>
                    <div class="alert alert-danger shadow-sm rounded-3">
                        <strong>Error:</strong> <?php echo $errorMsg; ?>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom border-light-subtle py-3 px-4">
                        <h5 class="mb-0 fw-bold text-dark"><i class="fa fa-envelope text-primary me-2"></i> Mail Tracking Information</h5>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Jk Name</label>
                                <input type="text" name="jkname" class="form-control rounded-2 border-light-subtle" placeholder="Enter recipient name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Jk Email</label>
                                <input type="email" name="email" class="form-control rounded-2 border-light-subtle" placeholder="Enter recipient email address" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small">Description / Body Message</label>
                                <textarea class="form-control rounded-2 border-light-subtle" name="gram" rows="6" placeholder="Type your email body details here..." required></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary small">Tracking Number</label>
                                <input type="text" name="tracking" class="form-control rounded-2 border-light-subtle" placeholder="e.g. HT-784019-US" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2.5 fw-semibold rounded-2 shadow-sm">
                                    <i class="fa fa-paper-plane me-1.5"></i> Dispatch Waybill Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <!-- End Page Content -->
    </div>
    <!-- End Container fluid -->
    
    <?php include 'footer.php'; ?>
</div>
<!-- End Page wrapper -->