<?php
session_start();
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

// Get form data
$applicant_id = $_POST['applicant_id'];
$name = $_POST['name'];
$room = $_POST['room'];
$venue = $_POST['venue'];

// Handle exam_time (make sure it's either NULL or valid datetime)
$exam_time = !empty($_POST['exam_time']) ? $_POST['exam_time'] : NULL;  // If empty, set to NULL

// Step 1: Check available slot limit using prepared statements to prevent SQL injection
$query = "SELECT slot_limit FROM tbl_exam_schedule WHERE room = ? AND venue = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $room, $venue);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if query failed, if so redirect with error
if (!$result) {
    header("Location: exam_schedule.php?error=An error occurred while checking available slots.");
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($row) {
    $slot_limit = $row['slot_limit'];

    // Step 2: Check if there are available slots
    if ($slot_limit > 0) {
        // Step 3: Insert the reservation into tbl_reservation using prepared statements
        $insert_query = "INSERT INTO tbl_reservation (applicant_id, name, exam_time, room, venue) 
                         VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "issss", $applicant_id, $name, $exam_time, $room, $venue);
        $insert_result = mysqli_stmt_execute($stmt);

        // Check if the insert query failed, if so redirect with error
        if (!$insert_result) {
            header("Location: exam_schedule.php?error=Failed to insert reservation. Please try again.");
            exit();
        }

        // Step 4: Update the slot_limit in tbl_exam_schedule using prepared statements
        $new_slot_limit = $slot_limit - 1;
        $update_query = "UPDATE tbl_exam_schedule SET slot_limit = ? WHERE room = ? AND venue = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "iss", $new_slot_limit, $room, $venue);
        $update_result = mysqli_stmt_execute($stmt);

        // Check if the update query failed, if so redirect with error
        if (!$update_result) {
            header("Location: exam_schedule.php?error=Failed to update available slots.");
            exit();
        }

        // Reservation success, redirect back with success message
        header("Location: exam_schedule.php?success=Reservation successfully made!");
        exit();
    } else {
        // No available slots
        header("Location: exam_schedule.php?error=No available slots left.");
        exit();
    }
} else {
    // Invalid room or venue
    header("Location: exam_schedule.php?error=Invalid room or venue.");
    exit();
}
?>
