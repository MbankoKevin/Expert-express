<?php
// 1. Core Logic & Session Management (Must be at the absolute top)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Redirect: If session exists, bypass authentication portal immediately
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

require_once "conn.php";
$error_message = "";

// Process Authentication Payload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password_input = $_POST['password'];

    if (!empty($username) && !empty($password_input)) {
        // Prepare statement to eliminate SQL injection vectors
        $sql = "SELECT username, password FROM users WHERE username = ? LIMIT 1";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    /**
                     * Security Note: Your legacy database utilizes md5 hashes.
                     * For production environments, migrate to password_hash() and password_verify().
                     */
                    if (md5($password_input) === $row['password']) {
                        // Bind state tokens and re-route safely
                        $_SESSION['username'] = $row['username'];
                        header("Location: home.php");
                        exit();
                    }
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Generic failure message to prevent user enumeration
    $error_message = "Invalid administrative credentials. Please verify your entries.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Meridian Logistics Group Administrative Authentication Terminal">
    <title>Meridian Logistics Group - Admin Login</title>
    
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../Logo-rmbg.png">
    
    <!-- Core UI Framework Elements -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Modern Interface Style Extensions -->
    <style>
        body {
            background-color: #0f172a !important;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .unix-login {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modern-login-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .login-form h4 {
            color: #0f172a;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .form-control {
            border: 1px solid #cbd5e1;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .btn-submit-modern {
            background: #dc3545;
            border: none;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 8px;
            transition: background 0.2s ease;
            width: 100%;
            color: #ffffff;
        }
        .btn-submit-modern:hover {
            background: #bd2130;
        }
    </style>
</head>

<body>
    
    <!-- Unified Core Preloader System -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    
    <!-- Base App Wrapper Grid Container -->
    <div id="main-wrapper">
        <div class="unix-login">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-7 col-sm-9">
                        <div class="login-content card modern-login-card p-4 m-3">
                            <div class="login-form">
                                <div class="text-center mb-4">
                                    <img src="../Logo-rmbg.png" alt="Meridian Logistics Group Engine" height="180" style="max-height: 50px;">
                                </div>
                                
                                <h4 class="text-center mb-3">Admin Portal Authentication</h4>
                                <p class="text-muted text-center small mb-4">Provide tracking infrastructure credentials to establish a secure session context.</p>
                                
                                <?php if (!empty($error_message)): ?>
                                    <div class="alert alert-danger border-0 rounded-3 text-center small py-2.5 mb-4" role="alert">
                                        <strong>Sign-in Failed:</strong> <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="form-group mb-3">
                                        <label for="username">User Name</label>
                                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter administrative username" required autocomplete="username">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-submit-modern shadow-sm">Authorize Session</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Javascript Requirements -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/scripts.js"></script>

</body>

</html>