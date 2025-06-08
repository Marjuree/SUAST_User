<?php
session_start();
require_once "../configuration/config.php";

header('Content-Type: application/json'); // Tell JS this is JSON

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // OTP checks
    if (!isset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['reset_user_id'])) {
        $response = [
            'status' => 'warning',
            'title' => 'Session Expired',
            'message' => 'OTP session expired. Please request a new one.'
        ];
        echo json_encode($response);
        exit;
    }

    if (time() > $_SESSION['otp_expiry']) {
        session_unset();
        session_destroy();
        $response = [
            'status' => 'error',
            'title' => 'OTP Expired',
            'message' => 'Your OTP has expired. Please request a new one.'
        ];
        echo json_encode($response);
        exit;
    }

    if ($entered_otp != $_SESSION['otp']) {
        $response = [
            'status' => 'error',
            'title' => 'Invalid OTP',
            'message' => 'The OTP you entered is incorrect.'
        ];
        echo json_encode($response);
        exit;
    }

    if ($new_password !== $confirm_password) {
        $response = [
            'status' => 'warning',
            'title' => 'Password Mismatch',
            'message' => 'New password and confirmation do not match.'
        ];
        echo json_encode($response);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update DB: changed table and fields accordingly
    $stmt = $con->prepare("UPDATE tbl_employee_registration SET employee_password = ? WHERE employee_id = ?");
    $stmt->bind_param("si", $hashed_password, $_SESSION['reset_user_id']);

    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        $response = [
            'status' => 'success',
            'title' => 'Success!',
            'message' => 'Password reset successful. You can now log in.',
            'redirect' => 'landing_page.php'
        ];
    } else {
        $response = [
            'status' => 'error',
            'title' => 'Database Error',
            'message' => 'Failed to update password. Please try again later.'
        ];
    }

    echo json_encode($response);
}
?>
