<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Set security headers to prevent attacks
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

// Restrict access to PHP files directly
if (basename($_SERVER['PHP_SELF']) === 'controller.php') {
    http_response_code(403);
    die("Access denied.");
}
?>
