<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php";
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

    if ($reg_password === $confirm_password) {
        $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);
        $query = "INSERT INTO tbl_users_management (name, email, school_id, username, password, role) 
                  VALUES ('$reg_name', '$reg_email', '$reg_school_id', '$reg_username', '$hashed_password', '$reg_role')";

        if (mysqli_query($con, $query)) {
            exit;
        } else {
            header("Location: register.php?error=registration_failed");
            exit;
        }
    } else {
        header("Location: register.php?error=password_mismatch");
        exit;
    }
}

// Login Handler
if (isset($_POST['btn_login'])) {
    $username = mysqli_real_escape_string($con, $_POST['txt_username']);
    $password = $_POST['txt_password'];
    $role = mysqli_real_escape_string($con, $_POST['select_role']);

    $query = "SELECT * FROM tbl_users_management WHERE username = '$username' AND role = '$role'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['role'] = $row['role'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            logMessage("INFO", "Login Success", "Admin '$username' logged in successfully.");

            switch ($row['role']) {
                case "SUAST":
                    header("Location: ../application/AdminSUAST/AdminSUAST.php?success=login");
                    break;
                case "Accounting":
                    header("Location: ../application/AdminAccounting/Accountingdashboard.php?success=login");
                    break;
                case "HRMO":
                    header("Location: ../application/AdminHRMO/HRMODashboard.php?success=login");
                    break;
                default:
            }
            exit;
        } else {
            logMessage("WARNING", "Login Failed", "Invalid password for user '$username'.");
            exit;
        }
    } else {
        logMessage("WARNING", "Login Failed", "Invalid role or user '$username' does not exist.");
        exit;
    }
}
?>
