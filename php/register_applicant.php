<?php
include "../configuration/config.php"; // Ensure database connection
include "../application/SystemLog.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? NULL; // Optional
    $last_name = $_POST['last_name'];
    $university_email = $_POST['university_email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['applicant_password'], PASSWORD_DEFAULT); // Secure hash
    $privacy_notice_accepted = isset($_POST['privacy_notice_accepted']) ? 1 : 0; // Checkbox handling

    // Check if email already exists
    $check_query = "SELECT * FROM tbl_applicant_registration WHERE university_email = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("s", $university_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered!'); window.location.href='../index.php';</script>";
    } else {
        // Insert new applicant
        $query = "INSERT INTO tbl_applicant_registration 
                  (first_name, middle_name, last_name, university_email, username, applicant_password, privacy_notice_accepted) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $university_email, $username, $password, $privacy_notice_accepted);

        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful!'); window.location.href='../index.php';</script>";
        } else {
            echo "<script>alert('Registration Failed! Try Again.'); window.location.href='../index.php';</script>";
        }
    }
    $stmt->close();
}
?>
