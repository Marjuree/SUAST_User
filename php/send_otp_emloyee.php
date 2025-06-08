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
    // Get and sanitize the email
    $email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';

    if (empty($email)) {
        echo "<script>alert('Please enter your email address.'); window.history.back();</script>";
        exit;
    }

    // Query the employee table by email
    $stmt = $con->prepare("SELECT employee_id, email FROM tbl_employee_registration WHERE LOWER(email) = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        $expiry = time() + 300; // 5 minutes

        // Store OTP and user ID in session
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = $expiry;
        $_SESSION['reset_user_id'] = $user['employee_id'];

        // Send OTP using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'unireserve777@gmail.com';
            $mail->Password   = 'qtji jxze qvwb vkwm';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email details
            $mail->setFrom('unireserve777@gmail.com', 'UniReserve');
            $mail->addAddress($user['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';

            // Embed logo (adjust the path if necessary)
            $logoPath = '../img/uni.png';
            if (file_exists($logoPath)) {
                $mail->AddEmbeddedImage($logoPath, 'logoimg');
            }

            $mail->Body = "
                <div style='font-family: Arial, sans-serif;'>
                    " . (file_exists($logoPath) ? "<img src='cid:logoimg' alt='Logo' style='width: 120px; margin-bottom: 15px;'>" : "") . "
                    <h3>Hello,</h3>
                    <p>Your OTP code is: <strong>$otp</strong></p>
                    <p>This code will expire in 5 minutes.</p>
                    <br>
                    <p>Regards,<br><strong>UniReserve Team</strong></p>
                </div>
            ";

            $mail->send();

            echo "<script>
                alert('OTP has been sent to your email.');
                window.location.href = 'verify_otp_employee.php';
            </script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send OTP. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email not found.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
