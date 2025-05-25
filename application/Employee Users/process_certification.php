<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../configuration/config.php";
session_start();

header('Content-Type: application/json'); // Respond with JSON!

$response = ['success' => false, 'message' => 'Unknown error.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Certification";
    $date_request = $_POST['date_request'] ?? '';
    $name = $_POST['name'] ?? '';
    $faculty = $_POST['faculty'] ?? '';
    $reason = $_POST['reason'] ?? '';

    if (!isset($_SESSION['employee_id'])) {
        $response['message'] = "Employee ID not found. Please log in again.";
        echo json_encode($response);
        exit();
    }

    $employee_id = $_SESSION['employee_id'];

    // Validate fields
    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $response['message'] = "All fields are required!";
    } else {
        $file_name = null;
        $attachment_data = null;

        // File upload handling
        if (isset($_FILES['certification_file']) && $_FILES['certification_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['certification_file']['tmp_name'];
            $file_name = basename($_FILES['certification_file']['name']);
            $attachment_data = file_get_contents($file_tmp);
        }

        // Insert request into the database
        $sql = "INSERT INTO tbl_certification_requests 
            (request_type, date_request, name, faculty, reason, employee_id, certification_file, file_name)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if (!$stmt = $con->prepare($sql)) {
            $response['message'] = "Error preparing statement: " . $con->error;
            echo json_encode($response);
            exit();
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

        if ($attachment_data !== null) {
            $stmt->send_long_data(6, $attachment_data);
        }

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Certification request submitted successfully!";
        } else {
            $response['message'] = "Error submitting request: " . $stmt->error;
        }

        $stmt->close();
    }

    $con->close();
} else {
    $response['message'] = "No POST request received.";
}

echo json_encode($response);
?>
