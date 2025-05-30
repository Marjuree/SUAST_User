<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_regenerate_id(true); // Prevent session fixation

require_once "../configuration/config.php"; // Ensure this file does not have whitespace or output

// Login Handler for Applicant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['applicant_password'];
    echo ".";

    // Fetch user from tbl_applicant_registration
    $query = "SELECT * FROM tbl_applicant_registration WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['applicant_password'])) {

            $_SESSION['applicant_id'] = $user['applicant_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['university_email'] = $user['university_email'];
            $_SESSION['role'] = 'Applicant'; // ✅ Set user role

            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome back!',
                        confirmButtonText: 'Proceed'
                    }).then(() => {
                        window.location.href='../application/Applicant Users/dashboard.php?success=login';
                    });
                  </script>";
            exit();
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Password',
                        text: 'Please check your password and try again.',
                        confirmButtonText: 'Retry'
                    }).then(() => {
                        window.location.href='landing_page.php';
                    });
                  </script>";
            exit();
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'No Account Found',
                    text: 'The username you entered does not exist.',
                    confirmButtonText: 'Go Back'
                }).then(() => {
                    window.location.href='landing_page.php';
                });
              </script>";
        exit();
    }

    $stmt->close();
    $con->close();
}
?>
