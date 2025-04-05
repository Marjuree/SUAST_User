<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    
    // Delete the reservation
    $deleteQuery = "DELETE FROM tbl_reservation WHERE id = '$id'";
    
    if (mysqli_query($con, $deleteQuery)) {
        $_SESSION['message'] = "Reservation deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting reservation: " . mysqli_error($con);
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "warning";
}

header("Location: manage_reservations.php");
exit();
?>  