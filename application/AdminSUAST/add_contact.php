<?php
session_start();
require_once "../../configuration/config.php"; // Ensure database connection

// Redirect if not logged in
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);

    // Insert into the database
    $query = "INSERT INTO tbl_contact (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Contact added successfully!";
        header("Location: contact.php"); // Redirect to contact page
        exit();
    } else {
        $_SESSION['error'] = "Error adding contact: " . mysqli_error($con);
        header("Location: contact.php"); // Redirect back with error
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: contact.php");
    exit();
}
?>
