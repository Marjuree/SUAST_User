<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../configuration/config.php";
include "../application/SystemLog.php";

// Your existing validations...

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $university_email = trim($_POST['university_email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $applicant_password = $_POST['applicant_password'] ?? '';
    $privacy_notice_accepted = isset($_POST['privacy_notice_accepted']) ? 1 : 0;

    if ($first_name === '' || $last_name === '' || $university_email === '' || $username === '' || $applicant_password === '') {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Missing Fields',
            'text' => 'Please fill in all required fields.'
        ];
        header("Location: ../index.php");
        exit;
    }

    $password = password_hash($applicant_password, PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM tbl_applicant_registration WHERE university_email = ?";
    $stmt = $con->prepare($check_query);
    $stmt->bind_param("s", $university_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Already Registered',
            'text' => 'This email is already registered!'
        ];
        header("Location: ../index.php");
        exit;
    } else {
        $query = "INSERT INTO tbl_applicant_registration 
                  (first_name, middle_name, last_name, university_email, username, applicant_password, privacy_notice_accepted) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $university_email, $username, $password, $privacy_notice_accepted);

        if ($stmt->execute()) {
            $_SESSION['swal'] = [
                'icon' => 'success',
                'title' => 'Registration Successful',
                'text' => 'You may now log in using your credentials.'
            ];
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION['swal'] = [
                'icon' => 'error',
                'title' => 'Registration Failed',
                'text' => 'Something went wrong. Please try again.'
            ];
            header("Location: ../index.php");
            exit;
        }
    }
}
?>
