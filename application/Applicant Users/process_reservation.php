<?php
session_start();
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error_message'] = "Please login as an applicant.";
    displayMessage($_SESSION['error_message'], 'error', '../../php/error.php');
    exit();
}

// Get form data
$applicant_id = $_POST['applicant_id'];
$name = $_POST['name'];
$room = $_POST['room'];
$venue = $_POST['venue'];
$exam_time = !empty($_POST['exam_time']) ? $_POST['exam_time'] : NULL;  // If empty, set to NULL

// Check available slot limit
$query = "SELECT slot_limit FROM tbl_exam_schedule WHERE room = ? AND venue = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $room, $venue);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// If query fails
if (!$result) {
    displayMessage("An error occurred while checking available slots.", 'error');
    exit();
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $slot_limit = $row['slot_limit'];

    if ($slot_limit > 0) {
        // Insert reservation
        $insert_query = "INSERT INTO tbl_reservation (applicant_id, name, exam_time, room, venue) 
                         VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "issss", $applicant_id, $name, $exam_time, $room, $venue);
        $insert_result = mysqli_stmt_execute($stmt);

        if (!$insert_result) {
            displayMessage("Failed to insert reservation. Please try again.", 'error');
            exit();
        }

        // Update slot limit
        $new_slot_limit = $slot_limit - 1;
        $update_query = "UPDATE tbl_exam_schedule SET slot_limit = ? WHERE room = ? AND venue = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "iss", $new_slot_limit, $room, $venue);
        $update_result = mysqli_stmt_execute($stmt);

        if (!$update_result) {
            displayMessage("Failed to update available slots.", 'error');
            exit();
        }

        // Success
        displayMessage("✅ Reservation successfully made!", 'success');
    } else {
        displayMessage("No available slots left.", 'error');
    }
} else {
    displayMessage("Invalid room or venue.", 'error');
}

exit();


// ✨ Function to show styled message and auto-redirect
function displayMessage($message, $type, $redirect = 'exam_schedule.php') {
    $bgColor = $type === 'success' ? '#d4edda' : '#f8d7da';
    $textColor = $type === 'success' ? '#155724' : '#721c24';
    $borderColor = $type === 'success' ? '#c3e6cb' : '#f5c6cb';

    echo "
    <div style='
        max-width: 500px;
        margin: 100px auto;
        padding: 20px;
        background-color: $bgColor;
        color: $textColor;
        border: 1px solid $borderColor;
        border-radius: 8px;
        font-size: 18px;
        text-align: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    '>
        $message
        <br><br><small>Redirecting, please wait...</small>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = '$redirect';
        }, 3000);
    </script>
    ";
}
?>
