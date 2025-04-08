<?php
require_once "../configuration/config.php";
require_once "../application/SystemLog.php";

if (isset($_POST['register_student'])) {
    $full_name = $_POST['student_name'];
    $email = $_POST['student_email'];
    $school_id = $_POST['student_school_id'];
    $username = $_POST['student_username'];
    $password = $_POST['student_password'];
    $confirm_password = $_POST['student_confirm_password'];

    if ($password !== $confirm_password) {
        header("Location: ../index.php?register_error=Passwords do not match!");
        exit();
    }

    // Check if username or email already exists
    $check_sql = "SELECT id FROM tbl_student_users WHERE username = ? OR email = ?";
    $check_stmt = $con->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        header("Location: ../index.php?register_error=Username or email already exists!");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO tbl_student_users (full_name, email, school_id, username, password) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $full_name, $email, $school_id, $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: ../index.php?register_success=Registration successful! Please login.");
    } else {
        header("Location: ../index.php?register_error=Registration failed. Please try again.");
    }

    $stmt->close();
    $check_stmt->close();
    $con->close();
}
?>
