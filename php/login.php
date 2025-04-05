<?php

session_start();

require_once "../configuration/config.php"; // Ensure this file does not have whitespace or output
require_once "../application/SystemLog.php";



// Registration Handler
if (isset($_POST['btn_register'])) {
    $reg_name = mysqli_real_escape_string($con, $_POST['reg_name']);
    $reg_email = mysqli_real_escape_string($con, $_POST['reg_email']);
    $reg_school_id = mysqli_real_escape_string($con, $_POST['reg_school_id']);
    $reg_username = mysqli_real_escape_string($con, $_POST['reg_username']);
    $reg_password = mysqli_real_escape_string($con, $_POST['reg_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    $reg_role = mysqli_real_escape_string($con, $_POST['reg_role']);

    // Check if passwords match
    if ($reg_password === $confirm_password) {
        // Hash the password before storing
        $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);

        // Insert query
        $query = "INSERT INTO tbl_users_management (name, email, school_id, username, password, role) 
                  VALUES ('$reg_name', '$reg_email', '$reg_school_id', '$reg_username', '$hashed_password', '$reg_role')";

        if (mysqli_query($con, $query)) {
            echo "<script>alert('Registration successful!'); window.location.href='success.html';</script>";
        } else {
            echo "<script>alert('Error: Could not register user.');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match.');</script>";
    }
}


// Login Handler
if (isset($_POST['btn_login'])) { 
    $username = mysqli_real_escape_string($con, $_POST['txt_username']);
    $password = $_POST['txt_password']; // Do not escape passwords, we'll verify them later
    $role = mysqli_real_escape_string($con, $_POST['select_role']);

    // Fetch user details
    $query = "SELECT * FROM tbl_users_management WHERE username = '$username' AND role = '$role'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify password using password_verify()
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true); // Prevent session fixation attacks

            $_SESSION['role'] = $row['role'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
             // ✅ Log successful login
            logMessage("INFO", "Login Success", "Admin '$username' logged in successfully.");

            // Redirect based on role
            switch ($row['role']) {
                case "SUAST":
                    header("location: ../application/AdminSUAST/AdminSUAST.php?success=login");
                    
                    break;
                case "Accounting":
                    header("location: ../application/AdminAccounting/Accountingdashboard.php?success=login");
                    break;
                    
                case "HRMO":
                    header("location: ../application/AdminHRMO/HRMODashboard.php?success=login");
                    break;
                default:
                    header("location: invalid.html?error=invalid_role");
            }   
            exit;
        } else {
             // ❌ Log failed login attempt
            logMessage("WARNING", "Login Failed", "Admin Office Failed login attempt for user '$username'.");
            echo "<script>alert('Invalid Password!'); window.location.href='invalid.html';</script>";
        }
    } else {
         // ❌ Log failed login attempt
         logMessage("WARNING", "Login Failed", "Admin Invalid Role Please Select OFFICE!  '$username'.");
        echo "<script>alert('Invalid Role Please Select OFFICE! '); window.location.href='invalid.html';</script>";
    }
}

