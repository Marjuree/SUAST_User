<?php
session_start();
require_once "../../configuration/config.php";

if (!isset($_SESSION['role'])) {
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Access Denied',
            text: 'Please login to access this page.',
            confirmButtonText: 'Login'
        }).then(() => {
            window.location.href = '../../php/error.php?welcome=Please login to access this page';
        });
    </script>";
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
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Action',
                text: 'Invalid action received for the request.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.location.href = 'dashboard.php?error=Invalid action';
            });
        </script>";
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
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                text: 'The status was successfully updated.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'StudentDashboard.php?success=Status updated successfully';
            });
        </script>";
    } else {
        error_log("Update failed for ID: $id: " . $stmt->error);
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'Failed to update the status. Please try again.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.location.href = 'dashboard.php?error=Failed to update status';
            });
        </script>";
    }

    $stmt->close();
    $con->close();
}
?>
