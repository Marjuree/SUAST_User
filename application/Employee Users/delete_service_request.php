<?php
// delete_service_request.php

session_start();
header('Content-Type: application/json');

require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get ID
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
        exit;
    }

    // Optional: Check if the request belongs to the logged-in user
    // $employee_id = $_SESSION['employee_id'];
    // $stmt = $con->prepare("SELECT id FROM tbl_service_requests WHERE id = ? AND employee_id = ?");
    // $stmt->bind_param("ii", $id, $employee_id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // if ($result->num_rows === 0) {
    //     echo json_encode(['success' => false, 'message' => 'Request not found or not allowed.']);
    //     exit;
    // }

    // Delete the record
    $stmt = $con->prepare("DELETE FROM tbl_service_requests WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $con->error]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
