<?php
session_start();
require_once "../../configuration/config.php";

if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Set the correct status based on the button clicked
    if (isset($_POST['approve'])) {
        $status = "Approved";
    } elseif (isset($_POST['disapprove'])) {
        $status = "Rejected"; // ✅ Fixed: Changed from 'Disapproved' to 'Rejected'
    } else {
        error_log("Invalid action received for ID: " . $id);
        header("Location: dashboard.php?error=Invalid action");
        exit();
    }

    // Debugging: Log the status update attempt
    error_log("Updating ID $id to status: $status");

    // Update the status in the database
    $query = "UPDATE tbl_clearance_requests SET status = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        error_log("Update successful for ID: $id");
        header("Location: StudentDashboard.php?success=Status updated successfully");
        exit();
    } else {
        error_log("Update failed for ID $id: " . $stmt->error);
        header("Location: dashboard.php?error=Failed to update status");
        exit();
    }

    $stmt->close();
    $con->close();
} else {
    error_log("Invalid request detected");
    header("Location: dashboard.php?error=Invalid request");
    exit();
}


 