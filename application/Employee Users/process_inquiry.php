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
        $message = "Personnel inquiry submitted successfully!";
        $success = true;
    } else {
        $message = "Error submitting inquiry.";
        $success = false;
    }

    $stmt->close();
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
