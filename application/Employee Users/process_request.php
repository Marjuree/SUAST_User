<?php

require_once "../../configuration/config.php"; // Ensure this file contains your $conn database connection

// Assuming the user is logged in and the employee_id is stored in the session
session_start(); // Start the session to access session variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Service Record";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $reason = $_POST['reason'];

    // Check if employee_id is stored in session
    if (isset($_SESSION['employee_id'])) {
        $employee_id = $_SESSION['employee_id']; // Get employee_id from session
    } else {
        // If employee_id is not set in session, show an error message
        $message = "Error: Employee ID not found. Please log in again.";
        echo "<script>alert('$message'); window.location.href='login.php';</script>";
        exit();
    }

    // Validate inputs
    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $message = "All fields are required!";
    } else {
        // Insert into database with employee_id
        $sql = "INSERT INTO tbl_service_requests (request_type, date_request, name, faculty, reason, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $request_type, $date_request, $name, $faculty, $reason, $employee_id);

        if ($stmt->execute()) {
            $message = "Request submitted successfully!";
        } else {
            $message = "Error submitting request.";
        }

        $stmt->close();
    }
    $con->close();
}

?>

<!-- Redirect back to the form with a message -->
<?php
if (isset($message)) {
    echo "<script>alert('$message'); window.location.href='EmployeeDashboard.php?success=login';</script>";
}
?>
