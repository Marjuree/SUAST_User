<?php
session_start();
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    showAlert("Please login as an applicant.", "error", "../../php/error.php");
    exit();
}

// Get form data
$applicant_id = $_POST['applicant_id'];
$name = $_POST['name'];
$room_raw = $_POST['room'];
$venue_raw = $_POST['venue'];
$exam_time = !empty($_POST['exam_time']) ? $_POST['exam_time'] : NULL;
echo ".";

// Normalize for matching
$room = trim(strtolower($room_raw));
$venue = trim(strtolower($venue_raw));

// Check available slot limit
$query = "SELECT slot_limit FROM tbl_exam_schedule 
          WHERE LOWER(TRIM(room)) = ? AND LOWER(TRIM(venue)) = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $room, $venue);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    showAlert("An error occurred while checking available slots.", "error");
    exit();
}

$row = mysqli_fetch_assoc($result);
if ($row) {
    $slot_limit = $row['slot_limit'];

    if ($slot_limit > 0) {
        // Insert reservation using raw (original format) values
        $insert_query = "INSERT INTO tbl_reservation (applicant_id, name, exam_time, room, venue) 
                         VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        mysqli_stmt_bind_param($stmt, "issss", $applicant_id, $name, $exam_time, $room_raw, $venue_raw);
        $insert_result = mysqli_stmt_execute($stmt);

        if (!$insert_result) {
            showAlert("Failed to insert reservation. Please try again.", "error");
            exit();
        }

        // Update slot limit
        $new_slot_limit = $slot_limit - 1;
        $update_query = "UPDATE tbl_exam_schedule 
                         SET slot_limit = ? 
                         WHERE LOWER(TRIM(room)) = ? AND LOWER(TRIM(venue)) = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, "iss", $new_slot_limit, $room, $venue);
        $update_result = mysqli_stmt_execute($stmt);

        if (!$update_result) {
            showAlert("Failed to update available slots.", "error");
            exit();
        }

        showAlert("Reservation successfully made!", "success");
    } else {
        showAlert("No available slots left.", "error");
    }
} else {
    showAlert("Invalid room or venue.", "error");
}

exit();


// ✅ SweetAlert2 wrapper
function showAlert($message, $type, $redirect = 'exam_schedule.php') {
    $icon = $type === 'success' ? 'success' : 'error';

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: '$icon',
            title: '".ucfirst($type)."',
            text: '$message',
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>";
}
?>
