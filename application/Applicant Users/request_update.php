<?php
require_once "../../configuration/config.php";
session_start();

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'message' => 'Invalid request.'
];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reservation_id"])) {
    $reservation_id = intval($_POST["reservation_id"]);

    $stmt = $con->prepare("UPDATE tbl_reservation SET user_requested_update = 1 WHERE id = ?");

    if ($stmt === false) {
        $response['message'] = 'MySQL prepare error: ' . $con->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = '✅ Update request sent to admin.';
    } else {
        $response['message'] = '❌ Failed to send update request.';
    }

    $stmt->close();
}

echo json_encode($response);
