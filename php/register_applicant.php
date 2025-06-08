<?php
include "../configuration/config.php";
include "../application/SystemLog.php";

// Load SweetAlert2 script
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely with null coalescing and trim whitespace
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $university_email = trim($_POST['university_email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $applicant_password = $_POST['applicant_password'] ?? '';
    $privacy_notice_accepted = isset($_POST['privacy_notice_accepted']) ? 1 : 0;

    // Validate required fields
    if ($first_name === '' || $last_name === '' || $university_email === '' || $username === '' || $applicant_password === '') {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Missing Fields',
                text: 'Please fill in all required fields.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
        exit;
    }

    $password = password_hash($applicant_password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_query = "SELECT * FROM tbl_applicant_registration WHERE university_email = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("s", $university_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Already Registered',
                text: 'This email is already registered!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '../index.php';
            });
        </script>";
    } else {
        // Insert new applicant
        $query = "INSERT INTO tbl_applicant_registration 
                  (first_name, middle_name, last_name, university_email, username, applicant_password, privacy_notice_accepted) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $university_email, $username, $password, $privacy_notice_accepted);

        if ($stmt->execute()) {
            echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: 'You may now log in using your credentials.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '../index.php';
                });
            </script>";
        } else {
            echo "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: 'Something went wrong. Please try again.',
                    confirmButtonText: 'Retry'
                }).then(() => {
                    window.location.href = '../index.php';
                });
            </script>";
        }
    }

    $stmt->close();
} else {
    // Optionally, handle non-POST access here or redirect
    header("Location: ../index.php");
    exit;
}
?>
