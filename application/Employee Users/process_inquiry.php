<?php

require_once "../../configuration/config.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Personnel Inquiry";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $question = $_POST['question'];

    // Insert into database
    $sql = "INSERT INTO tbl_personnel_inquiries (request_type, date_request, name, faculty, question) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssss", $request_type, $date_request, $name, $faculty, $question);

    if ($stmt->execute()) {
        echo "<script>alert('Personnel inquiry submitted successfully!'); window.location.href='dashboard.php?success=login';</script>";
    } else {
        echo "<script>alert('Error submitting inquiry.'); window.location.href='dashboard.php?success=login';</script>";
    }

    $stmt->close();
    $con->close();
}
?>
