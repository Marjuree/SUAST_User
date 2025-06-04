<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        http_response_code(400);
        echo "Missing ID";
        exit;
    }

    $id = intval($_POST['id']);

    // Optional: Add authorization check here

    $stmt = $con->prepare("DELETE FROM tbl_leave_requests WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Deleted successfully";
    } else {
        http_response_code(500);
        echo "Failed to delete";
    }

    $stmt->close();
} else {
    http_response_code(405); // Method not allowed
    echo "Invalid request method";
}
?>
