<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../configuration/config.php"; // Ensure the database connection is correct

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Service Record";
    $date_request = $_POST['date_request'] ?? '';
    $name = $_POST['name'] ?? '';
    $faculty = $_POST['faculty'] ?? '';
    $reason = $_POST['reason'] ?? '';

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

    // Validate fields
    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $message = "All fields are required!";
        $success = false;
    } else {
        $file_name = null;
        $attachment_data = null;

        // File upload handling
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['attachment']['tmp_name'];
            $file_name = basename($_FILES['attachment']['name']);
            $attachment_data = file_get_contents($file_tmp);
        }

        // Insert request into the database
        $sql = "INSERT INTO tbl_service_requests 
            (request_type, date_request, name, faculty, reason, employee_id, attachment, file_name)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if (!$stmt = $con->prepare($sql)) {
            die("Error preparing statement: " . $con->error);
        }

        $stmt->bind_param(
            "sssssiss",
            $request_type,
            $date_request,
            $name,
            $faculty,
            $reason,
            $employee_id,
            $attachment_data,
            $file_name
        );

        // If there's attachment data, send it
        if ($attachment_data !== null) {
            $stmt->send_long_data(6, $attachment_data);
        }

        if ($stmt->execute()) {
            $message = "Request submitted successfully!";
            $success = true;
        } else {
            $message = "Error submitting request: " . $stmt->error;
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
                window.location.href = 'service_requests.php?success=login';
            });
        </script>
    </body>
    </html>";
}
?>
