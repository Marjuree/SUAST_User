<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../configuration/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['employee_id'])) {
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

    $employee_id = $_SESSION['employee_id'];
    $request_type = "Leave Processing";
    $date_request = $_POST['leave_date'] ?? '';
    $leave_end_date = $_POST['leave_end_date'] ?? '';

    // Fetch full name and faculty
    $infoQuery = "SELECT CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, faculty 
                  FROM tbl_employee_registration 
                  WHERE employee_id = ?";
    $infoStmt = $con->prepare($infoQuery);

    if (!$infoStmt) {
        die("Error preparing statement: " . $con->error);
    }

    $infoStmt->bind_param("i", $employee_id);
    $infoStmt->execute();
    $infoResult = $infoStmt->get_result();
    $infoRow = $infoResult->fetch_assoc();
    $infoStmt->close();

    $name = $infoRow['full_name'] ?? 'Unknown';
    $faculty = $infoRow['faculty'] ?? 'Unknown';

    // Validate fields
    if (empty($date_request) || empty($leave_end_date)) {
        $message = "All fields are required!";
        $success = false;
    } else {
        $file_name = null;
        $leave_form_data = null;

        // File upload handling
        if (isset($_FILES['leave_form']) && $_FILES['leave_form']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['leave_form']['tmp_name'];
            $file_name = basename($_FILES['leave_form']['name']);
            $leave_form_data = file_get_contents($file_tmp);
        }

        // Insert request into the database
        $sql = "INSERT INTO tbl_leave_requests 
            (request_type, date_request, name, faculty, leave_end_date, leave_form, file_name, employee_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if (!$stmt = $con->prepare($sql)) {
            die("Error preparing statement: " . $con->error);
        }

        $stmt->bind_param(
            "sssssbsi",
            $request_type,
            $date_request,
            $name,
            $faculty,
            $leave_end_date,
            $leave_form_data,
            $file_name,
            $employee_id
        );

        // If there's attachment data, send it
        if ($leave_form_data !== null) {
            $stmt->send_long_data(5, $leave_form_data); // Index 5 = leave_form
        }

        if ($stmt->execute()) {
            $message = "Leave request submitted successfully!";
            $success = true;
        } else {
            $message = "Error submitting leave request: " . $stmt->error;
            $success = false;
        }

        $stmt->close();
    }

    $con->close();
} else {
    $message = "No POST request received.";
    $success = false;
}

// Final message display
if (isset($message)) {
    echo "
    <html>
    <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
    <body>
        <script>
            Swal.fire({
                icon: '" . ($success ? "success" : "error") . "',
                title: '" . ($success ? "Success!" : "Oops...") . "',
                text: '$message',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'leave_requests.php?success=login';
            });
        </script>
    </body>
    </html>";
}
?>
