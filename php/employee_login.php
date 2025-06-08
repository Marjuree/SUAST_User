<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php";

$maxAttempts = 3;
$banDuration = 300; // 5 minutes in seconds

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['employee_password'];

    // Initialize attempts/session variables if not set
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    if (!isset($_SESSION['last_attempt_time'])) {
        $_SESSION['last_attempt_time'] = 0;
    }

    // Make sure last_attempt_time is an integer
    $lastAttemptTime = (int)$_SESSION['last_attempt_time'];
    $timeSinceLastAttempt = time() - $lastAttemptTime;

    // Check if user is banned
    if ($_SESSION['login_attempts'] >= $maxAttempts && $timeSinceLastAttempt < $banDuration) {
        $timeLeft = $banDuration - $timeSinceLastAttempt;

        echo "<!DOCTYPE html><html><head>
                <meta charset='UTF-8'>
                <title>Login Banned</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                </head><body>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Too Many Failed Attempts',
                    html: 'You are temporarily locked out. Please try again after <b>$timeLeft seconds</b>.',
                    confirmButtonText: 'OK',
                    background: '#fff0f0',
                    color: '#8B0000',
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = 'landing_page.php';
                });
                </script>
                </body></html>";
        exit();
    }

    // Reset attempts if ban time has passed
    if ($timeSinceLastAttempt >= $banDuration) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = 0;
    }

    $query = "SELECT * FROM tbl_employee_registration WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<!DOCTYPE html><html><head>
            <meta charset='UTF-8'>
            <title>Employee Login</title>
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

        if (password_verify($password, $user['employee_password'])) {
            // Successful login — reset attempts
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;

            session_regenerate_id(true);

            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'Employee';

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful!',
                    html: '<b>Welcome, {$user['first_name']}!</b><br>Redirecting to your dashboard...',
                    background: '#e0f7fa',
                    color: '#004d40',
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
                    window.location.href = '../application/Employee Users/EmployeeDashboard.php?success=login';
                });
            </script>";
        } else {
            // Failed login — increment attempts
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();

            $attemptsLeft = $maxAttempts - $_SESSION['login_attempts'];

            if ($_SESSION['login_attempts'] >= $maxAttempts) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Incorrect Password!',
                        html: 'You have reached the maximum login attempts. Please wait 5 minutes before trying again.',
                        background: '#fff0f0',
                        color: '#8B0000',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'landing_page.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Incorrect Password!',
                        html: 'Incorrect password. You have <b>$attemptsLeft</b> attempt(s) left.',
                        background: '#fff0f0',
                        color: '#8B0000',
                        timer: 3000,
                        timerProgressBar: true,
                        showClass: {
                            popup: 'animate__animated animate__shakeX'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOut'
                        }
                    }).then(() => {
                        window.location.href = 'landing_page.php';
                    });
                </script>";
            }
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'User Not Found!',
                html: '<b>No employee account exists with that username.</b>',
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
                window.location.href = 'landing_page.php';
            });
        </script>";
    }

    echo "</body></html>";

    $stmt->close();
    $con->close();
}
?>
