<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php";

// Check if user is logged in as Applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    echo json_encode(['status' => 'error', 'message' => 'Please login as an applicant']);
    exit();
}

$applicant_id = $_SESSION['applicant_id'] ?? null;
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$middle_name = isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";

$full_name = trim($last_name . ', ' . $first_name . ' ' . $middle_name . '.');
if (empty(trim(str_replace('.', '', $full_name)))) {
    $full_name = "Applicant";
}

// Changed to check for exam_schedule_id instead of exam_id
if (!isset($_POST['exam_schedule_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing exam ID']);
    exit();
}

$exam_schedule_id = intval($_POST['exam_schedule_id']);

try {
    // Check for existing reservation
    $check = $con->prepare("SELECT id FROM tbl_reservation WHERE applicant_id = ?");
    if (!$check) {
        throw new Exception("Check prepare failed: " . $con->error);
    }
    $check->bind_param("i", $applicant_id);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already made a reservation.']);
        exit();
    }

    // Start transaction
    if (!$con->begin_transaction()) {
        throw new Exception("Transaction start failed: " . $con->error);
    }

    // Slot query
    $slotQuery = $con->prepare("
        SELECT slot_limit, 
               (SELECT COUNT(*) FROM tbl_reservation WHERE exam_schedule_id = ?) AS reserved_count,
               exam_date, exam_time, venue, room
        FROM tbl_exam_schedule
        WHERE id = ?
        FOR UPDATE
    ");
    if (!$slotQuery) {
        throw new Exception("Slot query prepare failed: " . $con->error);
    }
    $slotQuery->bind_param("ii", $exam_schedule_id, $exam_schedule_id);
    $slotQuery->execute();
    $slotResult = $slotQuery->get_result();

    if (!$row = $slotResult->fetch_assoc()) {
        $con->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID']);
        exit();
    }

    $slot_limit = (int)$row['slot_limit'];
    $reserved_count = (int)$row['reserved_count'];
    $remaining_slots = $slot_limit - $reserved_count;

    if ($remaining_slots <= 0) {
        $con->rollback();
        echo json_encode(['status' => 'error', 'message' => 'No slots available']);
        exit();
    }

    // Insert reservation
    $insert = $con->prepare("
        INSERT INTO tbl_reservation 
        (applicant_id, exam_schedule_id, name, exam_date, exam_time, venue, room, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    if (!$insert) {
        throw new Exception("Insert prepare failed: " . $con->error);
    }
    $insert->bind_param(
        "iisssss",
        $applicant_id,
        $exam_schedule_id,
        $full_name,
        $row['exam_date'],
        $row['exam_time'],
        $row['venue'],
        $row['room']
    );
    $insert->execute();

    if ($insert->affected_rows > 0) {
        $con->commit();
        echo json_encode(['status' => 'success']);
    } else {
        $con->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Failed to save reservation']);
    }

} catch (Exception $e) {
    if ($con->errno) {
        $con->rollback();
    }

    echo json_encode([
        'status' => 'error',
        'message' => 'Exception: ' . $e->getMessage()
    ]);
}
