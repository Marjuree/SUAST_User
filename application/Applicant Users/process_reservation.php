<?php
session_start();
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error_message'] = "Please login as an applicant.";
    // Display the error message and use JavaScript to redirect
    echo "<p class='error'>{$_SESSION['error_message']}</p>";
    echo "<script>setTimeout(function(){ window.location.href = '../../php/error.php'; }, 3000);</script>";
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

// Check if query failed
if (!$result) {
    $_SESSION['error_message'] = "An error occurred while checking available slots.";
    echo "<p class='error'>{$_SESSION['error_message']}</p>";
    echo "<script>setTimeout(function(){ window.location.href = 'exam_schedule.php'; }, 3000);</script>";
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

        // Check if the insert query failed
        if (!$insert_result) {
            $_SESSION['error_message'] = "Failed to insert reservation. Please try again.";
            echo "<p class='error'>{$_SESSION['error_message']}</p>";
            echo "<script>setTimeout(function(){ window.location.href = 'exam_schedule.php'; }, 3000);</script>";
            exit();
        }

        // Step 4: Update the slot_limit in tbl_exam_schedule using prepared statements
        $new_slot_limit = $slot_limit - 1;
        $update_query = "UPDATE tbl_exam_schedule SET slot_limit = ? WHERE room = ? AND venue = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "iss", $new_slot_limit, $room, $venue);
        $update_result = mysqli_stmt_execute($stmt);

        // Check if the update query failed
        if (!$update_result) {
            $_SESSION['error_message'] = "Failed to update available slots.";
            echo "<p class='error'>{$_SESSION['error_message']}</p>";
            echo "<script>setTimeout(function(){ window.location.href = 'exam_schedule.php'; }, 3000);</script>";
            exit();
        }

        // Reservation success
        $_SESSION['success_message'] = "Reservation successfully made!";
        echo "<p class='success'>{$_SESSION['success_message']}</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'exam_schedule.php'; }, 3000);</script>";
    } else {
        // No available slots
        $_SESSION['error_message'] = "No available slots left.";
        echo "<p class='error'>{$_SESSION['error_message']}</p>";
        echo "<script>setTimeout(function(){ window.location.href = 'exam_schedule.php'; }, 3000);</script>";
    }
} else {
    // Invalid room or venue
    $_SESSION['error_message'] = "Invalid room or venue.";
    echo "<p class='error'>{$_SESSION['error_message']}</p>";
    echo "<script>setTimeout(function(){ window.location.href = 'exam_schedule.php'; }, 3000);</script>";
}

exit();
?>
