<?php
session_start();

// Get the user's role before destroying the session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';

// Destroy session
session_unset();
session_destroy();

// Redirect based on role
switch ($role) {
    case "SUAST":
        header("Location: ././notification/SUAST.html?logout=success");
        break;
    case "Accounting":
        header("Location: ././notification/Accounting.html?logout=success");
        break;
    case "HRMO":
        header("Location: ././notification/HRMO.html?logout=success");
        break;
    case "Student":
        header("Location: ././notification/Student.html?logout=success");
        break;
    case "Employee":
        header("Location: ././notification/Employee.html?logout=success");
        break;
    default:
        header("Location: ././notification/Applicant.html?logout=success");
}
exit();








 