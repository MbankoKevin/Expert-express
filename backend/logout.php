<?php
// Force the browser NOT to cache this execution page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear, un-set, and destroy everything
$_SESSION = array();
session_unset();
session_destroy();

// Redirect cleanly
header("Location: index.php");
exit();
?>