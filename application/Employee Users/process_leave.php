<?php

require_once "../../configuration/config.php";// Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Leave Processing";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $leave_dates = $_POST['leave_dates'];

    // File Upload Handling
    $upload_dir = "../AdminHRMO/leave_forms/"; // Directory to store uploaded files
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
    }

    $file_name = basename($_FILES["leave_form"]["name"]);
    $target_file = $upload_dir . time() . "_" . $file_name; // Rename file with timestamp to avoid duplicates
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file type is valid
    if ($file_type != "pdf" && $file_type != "doc" && $file_type != "docx") {
        echo "<script>alert('Invalid file type! Only PDF, DOC, and DOCX allowed.'); window.location.href='request_form.php';</script>";
        exit();
    }

    if (move_uploaded_file($_FILES["leave_form"]["tmp_name"], $target_file)) {
        // Insert into database
        $sql = "INSERT INTO tbl_leave_requests (request_type, date_request, name, faculty, leave_dates, leave_form) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $request_type, $date_request, $name, $faculty, $leave_dates, $target_file);

        if ($stmt->execute()) {
            echo "<script>alert('Leave request submitted successfully!'); window.location.href='dashboard.php?success=login';</script>";
        } else {
            echo "<script>alert('Error submitting leave request.'); window.location.href='dashboard.php?success=login';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error uploading file. Please try again.'); window.location.href='dashboard.php?success=login';</script>";
    }

    $con->close();
}
?>
