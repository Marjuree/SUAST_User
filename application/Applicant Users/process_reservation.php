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

// Step 1: Check available slot limit
$query = "SELECT slot_limit FROM tbl_exam_schedule WHERE room = '$room' AND venue = '$venue'";
$result = mysqli_query($con, $query);

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
        // Step 3: Insert the reservation into tbl_reservation
        $insert_query = "INSERT INTO tbl_reservation (applicant_id, name, exam_time, room, venue) 
                         VALUES ('$applicant_id', '$name', " . ($exam_time ? "'$exam_time'" : "NULL") . ", '$room', '$venue')";
        $insert_result = mysqli_query($con, $insert_query);

        // Check if the insert query failed, if so redirect with error
        if (!$insert_result) {
            header("Location: exam_schedule.php?error=Failed to insert reservation. Please try again.");
            exit();
        }

        // Step 4: Update the slot_limit in tbl_exam_schedule
        $new_slot_limit = $slot_limit - 1;
        $update_query = "UPDATE tbl_exam_schedule SET slot_limit = $new_slot_limit WHERE room = '$room' AND venue = '$venue'";
        $update_result = mysqli_query($con, $update_query);

        // Check if the update query failed, if so redirect with error
        if (!$update_result) {
            header("Location: exam_schedule.php?");
            exit();
        }

        // Reservation success, redirect back with success message
        header("Location: exam_schedule.php?");
        exit();
    } else {
        // No available slots
        header("Location: exam_schedule.php?");
        exit();
    }
} else {
    // Invalid room or venue
    header("Location: exam_schedule.php?");
    exit();
}
?>
