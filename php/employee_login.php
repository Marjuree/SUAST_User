<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../configuration/config.php"; // Ensure no output or whitespace
require_once "../application/SystemLog.php"; // Logging system

// Login Handler for Employee
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['employee_password'];

    // Fetch user from tbl_employee_registration
    $query = "SELECT * FROM tbl_employee_registration WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['employee_password'])) {
            session_regenerate_id(true); // Prevent session fixation

            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'Employee'; // ✅ Role assigned

            // ✅ Log successful login
            logMessage("INFO", "Login Success", "Employee '$username' logged in successfully.");

            echo "<script>alert('Login Successful!'); window.location.href='../application/Employee Users/EmployeeDashboard.php?success=login';</script>";
            exit();
        } else {
            // ❌ Log failed login attempt
            logMessage("WARNING", "Login Failed", "Employee Invalid Password! '$username'.");

            echo "<script>alert('Invalid Password!'); window.location.href='landing_page.php';</script>";
            exit();
        }
    } else {
        // ❌ Log failed login attempt
        logMessage("WARNING", "Login Failed", "No account found with this username! '$username'.");

        echo "<script>alert('No account found with this username!'); window.location.href='landing_page.php';</script>";
        exit();
    }

    $stmt->close();
    $con->close();
}
?>
