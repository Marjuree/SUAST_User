<?php
session_start();
require_once "../../configuration/config.php"; // Ensure this includes a valid $con connection

if (!isset($_SESSION['username'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
    $status = isset($_POST['approve']) ? 'Approved' : 'Disapproved';

    $query = "UPDATE tbl_leave_requests SET request_status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $status, $request_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Leave request has been $status successfully.";
    } else {
        $_SESSION['error'] = "Failed to update leave request.";
    }

    $stmt->close();
    $con->close();
}

header("Location: leave_request.php"); // Redirect back to the leave requests page
exit();
