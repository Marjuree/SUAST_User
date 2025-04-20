<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include configuration and logging files
require_once "../configuration/config.php";
require_once "../application/SystemLog.php";

// Include SweetAlert2 for use
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if (isset($_POST['register_employee'])) {
    // Retrieve form values
    $first_name = $_POST['employee_first_name'];
    $middle_name = $_POST['employee_middle_name'];
    $last_name = $_POST['employee_last_name'];
    $email = $_POST['employee_email'];
    $username = $_POST['employee_username'];
    $password = $_POST['employee_password'];
    $confirm_password = $_POST['employee_confirm_password'];

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

    // Insert the employee data into the database
    $sql = "INSERT INTO tbl_employee_registration (first_name, middle_name, last_name, email, username, employee_password) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);

    // Check for errors in preparing the statement
    if (!$stmt) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: 'Failed to prepare statement. Please contact admin.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        exit();
    }

    $stmt->bind_param("ssssss", $first_name, $middle_name, $last_name, $email, $username, $hashed_password);

    if ($stmt->execute()) {
        // ✅ Registration success
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
        // ❌ Likely duplicate username/email
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: 'Username or email may already exist.',
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
