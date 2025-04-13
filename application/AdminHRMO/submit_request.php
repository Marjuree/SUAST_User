<?php
require_once "../../configuration/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $full_name = $_POST['full_name'];
    $faculty = $_POST['faculty'];
    $purpose = $_POST['purpose'];
    $contact = $_POST['contact'];

    // Generate a unique reference number
    $reference_number = "REQ" . time();

    $query = "INSERT INTO tblservice_requests (reference_number, employee_id, full_name, faculty, purpose, contact, status) 
              VALUES ('$reference_number', '$employee_id', '$full_name', '$faculty', '$purpose', '$contact', 'Pending')";

    if (mysqli_query($conn, $query)) {
        header("Location: confirmation.php?ref=$reference_number");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
