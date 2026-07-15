
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if we are on a protected view before redirecting
if (!defined('BYPASS_AUTH') && !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Cargo Express Administrative Routing Hub Engine">
    <title>Cargo Express - Admin Panel</title>
    
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../fav1.png">
    
    <!-- Core UI Framework Elements -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    
    <!-- Core Platform Styling Overrides -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="css/lib/html5-editor/bootstrap-wysihtml5.css" />

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- SaaS Application Workspace Header Injector -->
    <style>
        .top-navbar-modern {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .modern-nav-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.15s ease-in-out;
        }
        .modern-nav-link:hover {
            background-color: #f1f5f9;
            color: #0f172a !important;
        }
        .btn-modern-action {
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .btn-modern-logout {
            background-color: #fee2e2;
            color: #991b1b !important;
        }
        .btn-modern-logout:hover {
            background-color: #fca5a5;
            color: #7f1d1d !important;
        }
    </style>
</head>

<body class="fix-header fix-sidebar">
    
    <!-- Unified Core Preloader System -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>
    
    <!-- Base App Wrapper Grid Container -->
    <div id="main-wrapper">
        
        <!-- Top Application Header Gateway -->
        <div class="header">
            <nav class="navbar top-navbar-modern navbar-expand-md navbar-light fixed-top w-100">
                <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
                    
                    <!-- Left Brand Matrix Alignment -->
                    <div class="navbar-header d-flex align-items-center">
                        <a class="navbar-brand d-flex align-items-center me-4" href="home.php">
                            <b class="me-2"><img src="images/logo.png" alt="Cargo Express Core" class="dark-logo" style="height: 28px;" /></b>
                            <span><img src="images/logo-text.png" alt="Cargo Express Engine" class="dark-logo" style="height: 20px;" /></span>
                        </a>
                    </div>
                    
                    <!-- Dynamic Navigation Content Gate -->
                    <div class="d-flex align-items-center">
                        <ul class="navbar-nav flex-row align-items-center gap-2">
                            <!-- Public Portal Relays -->
                            <li class="nav-item">
                                <a class="nav-link modern-nav-link" href="../index.php">🌐 Home Website</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link modern-nav-link" href="../track.php">📦 Track Portal</a>
                            </li>
                            
                            <!-- Administrative Action Links -->
                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link modern-nav-link" href="new_tracking.php">➕ New Manifest</a>
                            </li>
                            <li class="nav-item d-none d-md-block border-end pe-2 border-light-subtle">
                                <a class="nav-link modern-nav-link" href="editmap.php">🗺️ Live Map</a>
                            </li>
                            
                            <!-- Session Termination Guard -->
                            <li class="nav-item ms-2">
                                <a class="nav-link btn-modern-action btn-modern-logout" href="logout.php">
                                    <i class="fa fa-power-off me-1.5"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </nav>
        </div>