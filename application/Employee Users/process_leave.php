<?php
session_start();
require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Leave Processing";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $leave_dates = $_POST['leave_dates'];
    $employee_id = $_SESSION['employee_id']; // Get from session securely

    // File Upload Handling
    $upload_dir = "../AdminHRMO/leave_forms/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = basename($_FILES["leave_form"]["name"]);
    $target_file = $upload_dir . time() . "_" . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!in_array($file_type, ['pdf', 'doc', 'docx'])) {
        echo "<script>alert('Invalid file type! Only PDF, DOC, and DOCX allowed.'); window.location.href='request_form.php';</script>";
        exit();
    }

    if (move_uploaded_file($_FILES["leave_form"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO tbl_leave_requests (request_type, date_request, name, faculty, leave_dates, leave_form, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssi", $request_type, $date_request, $name, $faculty, $leave_dates, $target_file, $employee_id);

        if ($stmt->execute()) {
            echo "<script>alert('Leave request submitted successfully!'); window.location.href='EmployeeDashboard.php?success=login';</script>";
        } else {
            echo "<script>alert('Error submitting leave request.'); window.location.href='EmployeeDashboard.php?success=login';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error uploading file. Please try again.'); window.location.href='EmployeeDashboard.php?success=login';</script>";
    }

    $con->close();
}
?>
