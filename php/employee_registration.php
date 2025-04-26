<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include configuration and logging files
require_once "../configuration/config.php";
require_once "../application/SystemLog.php";

// Include SweetAlert2
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if (isset($_POST['register_employee'])) {
    echo ".";
    // Retrieve form values
    $first_name = trim($_POST['employee_first_name']);
    $middle_name = trim($_POST['employee_middle_name']);
    $last_name = trim($_POST['employee_last_name']);
    $email = trim($_POST['employee_email']);
    $username = trim($_POST['employee_username']);
    $password = $_POST['employee_password'];
    $confirm_password = $_POST['employee_confirm_password'];
    $faculty = trim($_POST['employee_faculty']); // ✅ Added this line

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Passwords do not match!',
                confirmButtonText: 'Try Again'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for existing username or email
    $checkSql = "SELECT 1 FROM tbl_employee_registration WHERE username = ? OR email = ?";
    $stmtCheck = $con->prepare($checkSql);
    $stmtCheck->bind_param("ss", $username, $email);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Username or email already exists.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        $stmtCheck->close();
        exit();
    }
    $stmtCheck->close();

    // Insert into DB with faculty included
    $sql = "INSERT INTO tbl_employee_registration 
            (first_name, middle_name, last_name, email, username, employee_password, faculty) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: 'Failed to prepare SQL statement.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        exit();
    }

    $stmt->bind_param("sssssss", $first_name, $middle_name, $last_name, $email, $username, $hashed_password, $faculty);

    if ($stmt->execute()) {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: 'Please login to continue.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
    } else {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'An unexpected error occurred. Please try again.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
    }

    $stmt->close();
    $con->close();
}
?>
