<?php
session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$middle_name = isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";

$full_name = trim($last_name . ', ' . $first_name . ' ' . $middle_name . '.');
$full_name = empty($full_name) ? "Applicant" : $full_name;

if (isset($_POST['exam_id'])) {
    $exam_id = intval($_POST['exam_id']);

    // Check if user already has a reservation
    $check = $con->prepare("SELECT id FROM tbl_reservation WHERE applicant_id = ?");
    $check->bind_param("i", $applicant_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already made a reservation.']);
        exit;
    }

    // Begin transaction for atomic check + insert
    $con->begin_transaction();

    try {
        // Fetch slot_limit and count existing reservations for this exam_id
        $slotQuery = $con->prepare("
            SELECT slot_limit, 
                   (SELECT COUNT(*) FROM tbl_reservation WHERE exam_id = ?) AS reserved_count,
                   exam_date, exam_time, venue, room
            FROM tbl_exam_schedule
            WHERE id = ?
            FOR UPDATE
        ");
        $slotQuery->bind_param("ii", $exam_id, $exam_id);
        $slotQuery->execute();
        $slotResult = $slotQuery->get_result();

        if ($row = $slotResult->fetch_assoc()) {
            $slot_limit = (int)$row['slot_limit'];
            $reserved_count = (int)$row['reserved_count'];
            $remaining_slots = $slot_limit - $reserved_count;

            if ($remaining_slots <= 0) {
                $con->rollback();
                echo json_encode(['status' => 'error', 'message' => 'No slots available']);
                exit;
            }

            // Insert reservation
            $insert = $con->prepare("INSERT INTO tbl_reservation (applicant_id, exam_id, name, exam_date, exam_time, venue, room, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $insert->bind_param("iisssss", $applicant_id, $exam_id, $full_name, $row['exam_date'], $row['exam_time'], $row['venue'], $row['room']);
            $insert->execute();

            if ($insert->affected_rows > 0) {
                // Optionally update slot_limit or keep consistent with counting reservations only.
                // You can either update slot_limit, or leave it, since you calculate available slots dynamically.

                $con->commit();
                echo json_encode(['status' => 'success']);
            } else {
                $con->rollback();
                echo json_encode(['status' => 'error', 'message' => 'Failed to save reservation']);
            }
        } else {
            $con->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID']);
        }

    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing exam ID']);
}
?>
