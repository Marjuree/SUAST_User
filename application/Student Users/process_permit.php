<?php
session_start();
require_once "../../configuration/config.php"; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_permit'])) {
    $student_name = mysqli_real_escape_string($con, $_POST['student_name']);
    $purpose_name = mysqli_real_escape_string($con, $_POST['purpose_name']);
    $course_year = mysqli_real_escape_string($con, $_POST['course_year']);
    $type_of_permit = mysqli_real_escape_string($con, $_POST['type_of_permit']);
    
    $query = "INSERT INTO tbl_permits (student_name, purpose_name, course_year, type_of_permit) 
              VALUES ('$student_name', '$purpose_name', '$course_year', '$type_of_permit')";

    if ($con->query($query) === TRUE) {
        header("Location: StudentDashboard.php?success=Request submitted successfully");
    } else {
        header("Location: StudentDashboard.php?error=Error submitting request");
    }
    exit();
}
?>
