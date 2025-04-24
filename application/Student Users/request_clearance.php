<?php
// Enable error reporting to display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../../configuration/config.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $school_id = $_POST['school_id']; // Get school ID from POST
    $date_requested = date("Y-m-d H:i:s"); // Get current timestamp
    $status = 'Pending'; // Default status

    // Prepare the SQL query to insert the clearance request into the database
    $sql = "INSERT INTO tbl_clearance_requests (student_id, status, date_requested) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);

    // Bind the parameters to the prepared statement
    $stmt->bind_param("sss", $school_id, $status, $date_requested);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        // Send a JSON response with success status and message
        echo json_encode([
            'success' => true,
            'message' => 'Clearance request submitted successfully.'
        ]);
    } else {
        // Send a JSON response with error status and message
        echo json_encode([
            'success' => false,
            'message' => 'Error submitting request. Please try again.'
        ]);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    // If the request is not POST, send an invalid request response
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
