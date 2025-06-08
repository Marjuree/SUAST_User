<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_regenerate_id(true);

require_once "../configuration/config.php";

$maxAttempts = 3;
$lockoutDuration = 300; // 5 minutes in seconds

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['applicant_password'];

    // Initialize attempts/session variables if not set
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    if (!isset($_SESSION['lockout_time'])) {
        $_SESSION['lockout_time'] = 0;
    }

    $lockoutTime = (int)$_SESSION['lockout_time'];
    $timeSinceLockout = time() - $lockoutTime;

    // Check if user is currently locked out
    if ($_SESSION['login_attempts'] >= $maxAttempts && $timeSinceLockout < $lockoutDuration) {
        $timeLeft = $lockoutDuration - $timeSinceLockout;
        $minutesLeft = ceil($timeLeft / 60);

        echo "<!DOCTYPE html><html><head>
                <meta charset='UTF-8'>
                <title>Login Locked</title>
                <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'/>
                <style>
                    body {
                        background-color: #002B5B;
                        font-family: 'Poppins', sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                </style>
              </head>
              <body>
              <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Too Many Attempts',
                    html: '<b>You have exceeded the maximum login attempts.</b><br>Please try again in $minutesLeft minute(s).',
                    background: '#fff0f0',
                    color: '#8B0000',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut'
                    }
                }).then(() => {
                    window.location.href='landing_page.php';
                });
              </script>
              </body></html>";
        exit;
    }

    // Reset attempts if lockout time expired
    if ($timeSinceLockout >= $lockoutDuration) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = 0;
    }

    $query = "SELECT * FROM tbl_applicant_registration WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<!DOCTYPE html><html><head>
            <meta charset='UTF-8'>
            <title>Login Status</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'/>
            <style>
                body {
                    background-color: #002B5B;
                    font-family: 'Poppins', sans-serif;
                    margin: 0;
                    padding: 0;
                }
            </style>
          </head>
          <body>";

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['applicant_password'])) {
            // Successful login: reset attempts
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = 0;

            $_SESSION['applicant_id'] = $user['applicant_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['university_email'] = $user['university_email'];
            $_SESSION['role'] = 'Applicant';

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome back!',
                    html: '<b>Login Successful</b><br>Redirecting to your dashboard...',
                    background: '#f0f8ff',
                    color: '#002B5B',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    backdrop: `
                        rgba(0,43,91,0.6)
                        url('https://media.tenor.com/VWFPuE_F3cMAAAAC/check-mark-verified.gif')
                        center left
                        no-repeat
                    `,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then(() => {
                    window.location.href='../application/Applicant Users/dashboard.php?success=login';
                });
            </script>";
        } else {
            // Wrong password - increase attempts
            $_SESSION['login_attempts']++;

            if ($_SESSION['login_attempts'] >= $maxAttempts) {
                $_SESSION['lockout_time'] = time(); // start lockout timer
            }

            $attemptsLeft = $maxAttempts - $_SESSION['login_attempts'];

            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    html: '<b>Please check your password</b><br>and try again.<br>Attempts left: $attemptsLeft',
                    background: '#fff0f0',
                    color: '#8B0000',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut'
                    }
                }).then(() => {
                    window.location.href='landing_page.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Account Not Found',
                html: '<b>The username you entered does not exist.</b>',
                background: '#fffbe6',
                color: '#856404',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                }
            }).then(() => {
                window.location.href='landing_page.php';
            });
        </script>";
    }

    echo "</body></html>";

    $stmt->close();
    $con->close();
}
?>
