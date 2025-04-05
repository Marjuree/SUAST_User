<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php"; // Ensure no output in this file
require_once "../application/SystemLog.php"; // Ensure this file does not start a session

if (isset($_POST['btn_student'])) {
    $username = trim(htmlspecialchars($_POST['student_username'])); // Sanitize input
    $password = $_POST['student_password'];

    $sql = "SELECT id, full_name, password FROM tbl_student_users WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true); // Security: Prevent session fixation

            $_SESSION['student_id'] = $id;
            $_SESSION['student_name'] = $full_name;
            $_SESSION['role'] = 'Student'; // Store role for session-based access control

            // ✅ Log successful login
            logMessage("INFO", "Login Success", "Student '$full_name' logged in successfully.");
            header("Location: ../application/Student Users/StudentDashboard.php?success=login");
            exit();
        } else {
            // ❌ Log failed login attempt
            logMessage("WARNING", "Login Failed", "Invalid password for user '$full_name'.");
            echo "<script>alert('Invalid password!'); window.location.href='landing_page.php';</script>";
            exit();
        }
    } else {
        // ❌ Log failed login attempt (No user found)
        logMessage("WARNING", "Login Failed", "Student User '$username' not found.");
        echo "<script>alert('No account found with this username!'); window.location.href='landing_page.php';</script>";
        exit();
    }

    $stmt->close();
    $con->close();
}
?>
