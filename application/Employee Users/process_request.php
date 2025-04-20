<?php
require_once "../../configuration/config.php"; // Ensure this file contains your $con database connection
session_start(); // Start the session to access session variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Service Record";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $reason = $_POST['reason'];

    // Check if employee_id is stored in session
    if (isset($_SESSION['employee_id'])) {
        $employee_id = $_SESSION['employee_id'];
    } else {
        // Show SweetAlert2 for session error
        echo "
        <html>
        <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Session Error',
                    text: 'Employee ID not found. Please log in again.',
                    confirmButtonText: 'Login'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            </script>
        </body>
        </html>";
        exit();
    }

    // Validate inputs
    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $message = "All fields are required!";
        $success = false;
    } else {
        // Insert into database
        $sql = "INSERT INTO tbl_service_requests (request_type, date_request, name, faculty, reason, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $request_type, $date_request, $name, $faculty, $reason, $employee_id);

        if ($stmt->execute()) {
            $message = "Request submitted successfully!";
            $success = true;
        } else {
            $message = "Error submitting request.";
            $success = false;
        }

        $stmt->close();
    }

    $con->close();
}

// Show SweetAlert2 popup
if (isset($message)) {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '" . ($success ? "success" : "error") . "',
                title: '" . ($success ? "Success!" : "Oops...") . "',
                text: '$message',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'EmployeeDashboard.php?success=login';
            });
        </script>
    </body>
    </html>";
}
?>
