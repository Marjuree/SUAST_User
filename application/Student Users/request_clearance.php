<?php
// Enable error reporting to display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../../configuration/config.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Check if the request method is POST
    echo ".";

    $student_id = $_POST['student_id']; // Get student ID from form
    $date_requested = date("Y-m-d H:i:s"); // Get current timestamp
    $status = 'Pending'; // Default status

    // Debug: Check values before inserting
    // echo "Step 2: Values - Student ID: $student_id, Date Requested: $date_requested, Status: $status<br>";

    // Insert request into the database
    $sql = "INSERT INTO tbl_clearance_requests (student_id, status, date_requested) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);

    // Debug: Check if the statement was prepared
    // echo "Step 3: SQL Query prepared.<br>";

    $stmt->bind_param("sss", $student_id, $status, $date_requested);

    // Include SweetAlert2 JavaScript
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

    if ($stmt->execute()) {
        // Success message and redirect
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Request Submitted',
                    text: 'Clearance request submitted successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'StudentDashboard.php?success=request'; // Redirect to dashboard
                });
              </script>";
    } else {
        // Error message
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error submitting request. Please try again.',
                    confirmButtonText: 'Try Again'
                }).then(() => {
                    window.history.back(); // Go back to the previous page if there is an error
                });
              </script>";
    }

    $stmt->close();
    $con->close();
} else {
    // Invalid request error
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Request',
                text: 'The request was not valid.',
                confirmButtonText: 'Back'
            }).then(() => {
                window.history.back(); // Go back to the previous page if the request is invalid
            });
          </script>";
}
?>
