<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include configuration and logging files
require_once "../configuration/config.php";  // Ensure this file does not have whitespace or output
require_once "../application/SystemLog.php"; // Log file for system logs

 

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
        $_SESSION['register_error'] = "Passwords do not match!";
        header("Location: ../index.php");
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
        $_SESSION['register_error'] = "Failed to prepare statement: " . $con->error;
        header("Location: ../index.php");
        exit();
    }

    $stmt->bind_param("ssssss", $first_name, $middle_name, $last_name, $email, $username, $hashed_password);

    if ($stmt->execute()) {
        // Successful registration
        $_SESSION['register_success'] = "Registration successful! Please login.";
        header("Location: ../index.php");
    } else {
        // Registration failed, likely due to existing username or email
        $_SESSION['register_error'] = "Registration failed! Username or email may already exist.";
        header("Location: ../index.php");
    }

    $stmt->close();
    $con->close();
}
?>
