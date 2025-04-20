<?php
require_once "../../configuration/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_type = "Certification";
    $date_request = $_POST['date_request'];
    $name = $_POST['name'];
    $faculty = $_POST['faculty'];
    $reason = $_POST['reason'];

    $employee_id = $_SESSION['employee_id'];

    if (empty($date_request) || empty($name) || empty($faculty) || empty($reason)) {
        $message = "All fields are required!";
        $success = false;
    } else {
        $sql = "INSERT INTO tbl_certification_requests (request_type, date_request, name, faculty, reason, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssi", $request_type, $date_request, $name, $faculty, $reason, $employee_id);

        if ($stmt->execute()) {
            $message = "Certification request submitted successfully!";
            $success = true;
        } else {
            $message = "Error submitting certification request.";
            $success = false;
        }

        $stmt->close();
    }

    $con->close();
}

// Output SweetAlert2 popup
if (isset($message)) {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '" . ($success ? "success" : "error") . "',
                title: '" . ($success ? "Success!" : "Oops...") . "',
                text: '$message',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'EmployeeDashboard.php?success=login';
            });
        </script>
    </body>
    </html>
    ";
}
?>
