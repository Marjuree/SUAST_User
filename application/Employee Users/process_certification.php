<?php

require_once "../../configuration/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Certification";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $reason = $_POST['reason'];

    // Get employee ID from session
    $employee_id = $_SESSION['employee_id'];

    // Validate inputs
    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $message = "All fields are required!";
    } else {
        // Insert with employee_id
        $sql = "INSERT INTO tbl_certification_requests (request_type, date_request, name, faculty, reason, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $request_type, $date_request, $name, $faculty, $reason, $employee_id);

        if ($stmt->execute()) {
            $message = "Certification request submitted successfully!";
        } else {
            $message = "Error submitting certification request.";
        }

        $stmt->close();
    }

    $con->close();
}

// Redirect with message
if (isset($message)) {
    echo "<script>alert('$message'); window.location.href='EmployeeDashboard.php?success=login';</script>";
}
?>
