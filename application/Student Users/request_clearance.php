<?php
require_once "../../configuration/config.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id']; // Get student ID from form
    $date_requested = date("Y-m-d H:i:s"); // Get current timestamp
    $status = 'Pending'; // Default status

    // Insert request into the database
    $sql = "INSERT INTO tbl_clearance_requests (student_id, status, date_requested) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $student_id, $status, $date_requested);

    if ($stmt->execute()) {
        echo "<script>
                alert('Clearance request submitted successfully.');
                window.location.href = 'StudentDashboard.php?success=request'; // Redirect to homepage or dashboard
              </script>";
    } else {
        echo "<script>
                alert('Error submitting request. Please try again.');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $con->close();
} else {
    echo "<script>
            alert('Invalid request.');
            window.history.back();
          </script>";
}
?>
