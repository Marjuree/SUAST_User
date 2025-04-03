<?php
require_once "../configuration/config.php"; // Ensure this file does not have whitespace or output
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
        header("Location: ../index.php?register_error=Registration failed! Username or email may already exist.");
    }

    $stmt->close();
    $con->close();
}
?>
