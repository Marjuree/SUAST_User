<?php

require_once "../../configuration/config.php";// Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Certification";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $reason = $_POST['reason'];

    // Validate inputs
    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $message = "All fields are required!";
    } else {
        // Insert into database
        $sql = "INSERT INTO tbl_certification_requests (request_type, date_request, name, faculty, reason) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssss", $request_type, $date_request, $name, $faculty, $reason);

        if ($stmt->execute()) {
            $message = "Certification request submitted successfully!";
        } else {
            $message = "Error submitting certification request.";
        }

        $stmt->close();
    }
    $con->close();
}
?>

<!-- Redirect back to the form with a message -->
<?php
if (isset($message)) {
    echo "<script>alert('$message'); window.location.href='dashboard.php?success=login';</script>";
}
?>
