<?php
session_start();
require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Leave Processing";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $leave_dates = $_POST['leave_dates'];
    $employee_id = $_SESSION['employee_id'];

    // File Upload Handling
    $upload_dir = "../AdminHRMO/leave_forms/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = basename($_FILES["leave_form"]["name"]);
    $target_file = $upload_dir . time() . "_" . $file_name;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Output SweetAlert2 directly if file is invalid
    if (!in_array($file_type, ['pdf', 'doc', 'docx'])) {
        echo "
        <html>
        <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type!',
                    text: 'Only PDF, DOC, and DOCX files are allowed.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'request_form.php';
                });
            </script>
        </body>
        </html>";
        exit();
    }

    if (move_uploaded_file($_FILES["leave_form"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO tbl_leave_requests (request_type, date_request, name, faculty, leave_dates, leave_form, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssssi", $request_type, $date_request, $name, $faculty, $leave_dates, $target_file, $employee_id);

        if ($stmt->execute()) {
            $message = "Leave request submitted successfully!";
            $success = true;
        } else {
            $message = "Error submitting leave request.";
            $success = false;
        }
        $stmt->close();
    } else {
        $message = "Error uploading file. Please try again.";
        $success = false;
    }

    $con->close();
}

// Show SweetAlert2
if (isset($message)) {
    echo "
    <html>
    <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
    <body>
        <script>
            Swal.fire({
                icon: '" . ($success ? "success" : "error") . "',
                title: '" . ($success ? "Success!" : "Oops..." ) . "',
                text: '$message',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'EmployeeDashboard.php?success=login';
            });
        </script>
    </body>
    </html>";
}
?>
