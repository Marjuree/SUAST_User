<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php";

if (isset($_POST['btn_student'])) {
    $username = trim(htmlspecialchars($_POST['student_username']));
    $password = $_POST['student_password'];

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
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password!',
                    html: '<b>Please try again.</b>',
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
                    window.location.href = 'landing_page.php';
                });
            </script>";
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
