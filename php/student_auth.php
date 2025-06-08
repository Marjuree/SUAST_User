<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php";

$maxAttempts = 3;
$banDuration = 300; // 5 minutes in seconds

if (isset($_POST['btn_student'])) {
    $username = trim(htmlspecialchars($_POST['student_username']));
    $password = $_POST['student_password'];

    // Initialize session arrays if not already set or not arrays
    if (!isset($_SESSION['login_attempts']) || !is_array($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }
    if (!isset($_SESSION['last_attempt_time']) || !is_array($_SESSION['last_attempt_time'])) {
        $_SESSION['last_attempt_time'] = [];
    }

    // Initialize attempt counters for this username if not set
    if (!isset($_SESSION['login_attempts'][$username])) {
        $_SESSION['login_attempts'][$username] = 0;
        $_SESSION['last_attempt_time'][$username] = 0;
    }

    $timeSinceLastAttempt = time() - $_SESSION['last_attempt_time'][$username];

    // Check if user is currently banned
    if ($_SESSION['login_attempts'][$username] >= $maxAttempts && $timeSinceLastAttempt < $banDuration) {
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
                    color: '#8B0000'
                }).then(() => {
                    window.location.href = 'landing_page.php';
                });
                </script>
              </body></html>";
        exit();
    }

    // Reset attempts if ban duration passed
    if ($timeSinceLastAttempt >= $banDuration) {
        $_SESSION['login_attempts'][$username] = 0;
        $_SESSION['last_attempt_time'][$username] = 0;
    }

    $sql = "SELECT id, full_name, password FROM tbl_student_users WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    echo "<!DOCTYPE html><html><head>
            <meta charset='UTF-8'>
            <title>Student Login</title>
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

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Successful login — reset attempts
            $_SESSION['login_attempts'][$username] = 0;
            $_SESSION['last_attempt_time'][$username] = 0;

            $_SESSION['student_id'] = $id;
            $_SESSION['student_name'] = $full_name;
            $_SESSION['role'] = 'Student';

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful!',
                    html: '<b>Welcome, {$full_name}!</b>',
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
                    window.location.href = '../application/Student Users/StudentDashboard.php?success=login';
                });
            </script>";
        } else {
            // Failed login — increment attempts
            $_SESSION['login_attempts'][$username]++;
            $_SESSION['last_attempt_time'][$username] = time();

            $attemptsLeft = $maxAttempts - $_SESSION['login_attempts'][$username];

            if ($_SESSION['login_attempts'][$username] >= $maxAttempts) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password!',
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
                        title: 'Invalid Password!',
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
                title: 'Account Not Found!',
                html: '<b>No student account exists with that username.</b>',
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
