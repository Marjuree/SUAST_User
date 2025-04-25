<?php
session_start();
require_once "../../configuration/config.php";

if (!isset($_SESSION['applicant_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$applicant_id = $_SESSION['applicant_id'];
$name = $_SESSION['first_name']; // or get from DB if needed

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

        // ✅ Insert reservation
        $insert = "INSERT INTO tbl_reservation (applicant_id, name, exam_date, exam_time, venue, room, status)
                   VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt_insert = $con->prepare($insert);
        $stmt_insert->bind_param("isssss", $applicant_id, $name, $exam_date, $exam_time, $venue, $room);

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
