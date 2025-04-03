<?php
session_start();
ob_start();

// Check if the user is logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

// Include configuration file
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $total_balance = $_POST['total_balance'];
    $last_payment = $_POST['last_payment'];
    $due_date = $_POST['due_date'];

    $query = "INSERT INTO tbl_student_balances (student_id, student_name, total_balance, last_payment, due_date) 
              VALUES ('$student_id', '$student_name', '$total_balance', '$last_payment', '$due_date')";

    if (mysqli_query($con, $query)) {
        // Display an alert and redirect
        echo "<script>
                alert('Successfully added!');
                window.location.href = 'student_balances.php?successfully=added';
              </script>";
    } else {
        // Display an alert for error
        echo "<script>
                alert('Error: " . mysqli_error($con) . "');
              </script>";
    }
}
?>
