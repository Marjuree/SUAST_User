<?php
include "../configuration/config.php";
include "../application/SystemLog.php";

// Load SweetAlert2 script
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo ".";
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? NULL; // Optional
    $last_name = $_POST['last_name'];
    $university_email = $_POST['university_email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['applicant_password'], PASSWORD_DEFAULT);
    $privacy_notice_accepted = isset($_POST['privacy_notice_accepted']) ? 1 : 0;

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
}
?>
