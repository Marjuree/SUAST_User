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
    echo ".";

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['student_id'] = $id;
            $_SESSION['student_name'] = $full_name;
            $_SESSION['role'] = 'Student';

            // ✅ Logging removed
            header("Location: ../application/Student Users/StudentDashboard.php?success=login");
            exit();
        } else {
            // Using SweetAlert2 for error message
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid password!',
                        text: 'The password you entered is incorrect.',
                        confirmButtonText: 'Try Again'
                    }).then(() => {
                        window.location.href = 'landing_page.php'; // Redirect back to the landing page
                    });
                  </script>";
            exit();
        }
    } else {
        // Using SweetAlert2 for error message
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'No account found!',
                    text: 'There is no account associated with this username.',
                    confirmButtonText: 'Try Again'
                }).then(() => {
                    window.location.href = 'landing_page.php'; // Redirect back to the landing page
                });
              </script>";
        exit();
    }

    $stmt->close();
    $con->close();
}
?>
