<?php
session_start();
require_once "../../configuration/config.php"; // Database connection

// Redirect if not logged in
if (!isset($_SESSION['applicant_id'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

// Get form inputs
$applicant_id = $_SESSION['applicant_id'];
$name = mysqli_real_escape_string($con, $_POST['name']);
$exam_time = mysqli_real_escape_string($con, $_POST['exam_time']);
$room = mysqli_real_escape_string($con, $_POST['room']);
$venue = "AB Building, DORSU"; // Fixed venue

// Check if room is full (max 30 slots)
$check_query = "SELECT COUNT(*) as total FROM tbl_reservation WHERE exam_time = ? AND room = ?";
$stmt = mysqli_prepare($con, $check_query);
mysqli_stmt_bind_param($stmt, "ss", $exam_time, $room);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$existing_count = $row['total'];

if ($existing_count >= 30) {
    echo "<script>alert('This room is fully booked at the selected time. Choose another time or room.'); window.history.back();</script>";
    exit();
}

// Insert reservation
$insert_query = "INSERT INTO tbl_reservation (applicant_id, name, exam_time, room, venue) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $insert_query);
mysqli_stmt_bind_param($stmt, "issss", $applicant_id, $name, $exam_time, $room, $venue);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Reservation successful!'); window.location.href='exam_schedule.php';</script>";
} else {
    echo "<script>alert('Error: Could not complete reservation.'); window.history.back();</script>";
}

mysqli_close($con);
?>
