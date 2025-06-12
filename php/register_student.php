<?php
require_once "../configuration/config.php";
require_once "../application/SystemLog.php";

// Load SweetAlert2 JS
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if (isset($_POST['register_student'])) {
    $full_name = $_POST['student_name'];
    $email = $_POST['student_email'];
    $school_id = $_POST['student_school_id'];
    $username = $_POST['student_username'];
    $password = $_POST['student_password'];
    $confirm_password = $_POST['student_confirm_password'];
    $faculty = $_POST['student_faculty'];
    $year_level = $_POST['student_year_level'];

    if ($password !== $confirm_password) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Passwords do not match.',
                confirmButtonText: 'Try Again'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the INSERT statement to include faculty and year_level
    $sql = "INSERT INTO tbl_student_users (full_name, email, school_id, username, password, faculty, year_level) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssss", $full_name, $email, $school_id, $username, $hashed_password, $faculty, $year_level);

    try {
        if ($stmt->execute()) {
            header("Location: landing_page.php?register_success=" . urlencode("You can now log in with your credentials."));
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // Duplicate entry found
            header("Location: landing_page.php?register_error=" . urlencode("Username, email, or school ID already exists. Please use different credentials."));
            exit();
        } else {
            // Other errors
            header("Location: landing_page.php?register_error=" . urlencode("An unexpected error occurred. Please try again."));
            exit();
        }
    }

    $stmt->close();
    $con->close();
}
?>
