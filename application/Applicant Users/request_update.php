<?php
require_once "../../configuration/config.php";

// Start the session to access session variables
session_start();

// Check if the reservation ID is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reservation_id"])) {
    $reservation_id = intval($_POST["reservation_id"]);

    // Prepare the SQL statement
    $stmt = $con->prepare("UPDATE tbl_reservation SET user_requested_update = 1 WHERE id = ?");

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('MySQL prepare error: ' . $con->error);
    }

    // Bind the parameter
    $stmt->bind_param("i", $reservation_id);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Update request sent to aaa.";  // Success message
        $_SESSION['alert_type'] = "success";  // Alert type for SweetAlert2
    } else {
        $_SESSION['message'] = "❌ Failed to send update request.";  // Error message
        $_SESSION['alert_type'] = "error";  // Alert type for SweetAlert2
    }

    // Close the statement
    $stmt->close();
} else {
    $_SESSION['message'] = "❌ Invalid request.";  // Error message for invalid request
    $_SESSION['alert_type'] = "error";  // Alert type for SweetAlert2
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Request Status</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Show the SweetAlert based on the session message and alert type
        Swal.fire({
            title: '<?php echo ucfirst($_SESSION['alert_type']); ?>',  // Capitalize the alert type
            text: '<?php echo $_SESSION['message']; ?>',
            icon: '<?php echo $_SESSION['alert_type']; ?>',
            confirmButtonText: 'Okay'
        }).then(() => {
            // Redirect to the exam schedule page after the alert
            window.location.href = 'exam_schedule.php';
        });
    </script>

    <?php
    // Clear session variables after displaying the alert
    unset($_SESSION['message']);
    unset($_SESSION['alert_type']);
    ?>
</body>
</html>
