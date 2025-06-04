<?php
session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php"; // Ensure database connection

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$middle_name = isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";

// Combine first, middle, and last name
$full_name = trim($last_name . ', ' . $first_name . ' ' . $middle_name . '.');

$full_name = empty($full_name) ? "Applicant" : $full_name; 

if (isset($_POST['exam_id'])) {
    $exam_id = $_POST['exam_id'];

    // ✅ First: Check if this user already has a reservation
    $check = $con->prepare("SELECT id FROM tbl_reservation WHERE applicant_id = ?");
    $check->bind_param("i", $applicant_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already made a reservation.']);
        exit;
    }

    // ✅ Fetch exam schedule info
    $query = "SELECT * FROM tbl_exam_schedule WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $exam_date = $row['exam_date'];
        $exam_time = $row['exam_time'];
        $venue = $row['venue'];
        $room = $row['room'];
        $slot = $row['slot_limit'];

        // ✅ Optional: Check if slot still available
        if ($slot <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Slot not available']);
            exit;
        }

        // ✅ Insert reservation with combined full name
        $insert = "INSERT INTO tbl_reservation (applicant_id, name, exam_date, exam_time, venue, room, status)
                   VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt_insert = $con->prepare($insert);
        $stmt_insert->bind_param("isssss", $applicant_id, $full_name, $exam_date, $exam_time, $venue, $room);

        if ($stmt_insert->execute()) {
            // ✅ Reduce slot in tbl_exam_schedule
            $update_slot = $con->prepare("UPDATE tbl_exam_schedule SET slot_limit = slot_limit - 1 WHERE id = ?");
            $update_slot->bind_param("i", $exam_id);
            $update_slot->execute();

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Reservation failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing exam ID']);
}
?>
