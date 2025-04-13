<?php
session_start();
require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    
    if (isset($_POST['approve'])) {
        $status = "Approved";
    } elseif (isset($_POST['reject'])) {
        $status = "Rejected";
    } else {
        header("Location: request_permit.php?error=Invalid request");
        exit();
    }

    $query = "UPDATE tbl_permits SET status='$status' WHERE id=$id";
    
    if ($con->query($query) === TRUE) {
        header("Location: dashboard.php?success=Status updated successfully");
    } else {
        header("Location: dashboard.php?error=Failed to update status");
    }
    exit();
}
?>
