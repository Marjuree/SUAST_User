<?php
session_start();
require_once "../configuration/config.php";

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['usernameOrEmail'] ?? '';

    // Query employee by username or email
    $stmt = $con->prepare("SELECT employee_id, email FROM tbl_employee_registration WHERE username = ? OR email = ?");
    if (!$stmt) {
        die("<script>alert('Prepare failed: " . $con->error . "'); window.history.back();</script>");
    }
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        $expiry = time() + 300; // OTP valid for 5 minutes

        // Store OTP and expiry in session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = $expiry;
        $_SESSION['reset_user_id'] = $user['employee_id'];

        // PHPMailer to send email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'delaposchristian3@gmail.com';
            $mail->Password   = 'vtjz bvme bzfy ueyd';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('delaposchristian3@gmail.com', 'UniRevers');
            $mail->addAddress($user['email']);

            $mail->isHTML(false);
            $mail->Subject = "Your OTP Code";
            $mail->Body    = "Your OTP code is: $otp. It will expire in 5 minutes.";

            $mail->send();

            echo "<script>
                alert('OTP sent to your email.');
                window.location.href = 'verify_otp_employee.php';
            </script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send OTP. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username or Email not found.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
