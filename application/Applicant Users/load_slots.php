<?php
session_start();
require_once "../../configuration/config.php";

header('Content-Type: application/json');

$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$venue = isset($_POST['venue']) ? trim($_POST['venue']) : '';
$room = isset($_POST['room']) ? trim($_POST['room']) : '';

// For debugging:
error_log("Received data: date=$date, venue=$venue, room=$room");

if (empty($date) || empty($venue) || empty($room)) {
    echo json_encode(['error' => 'Missing required fields (date, venue, or room).']);
    exit;
}

try {
    // Use REPLACE to handle extra spaces in database
    $query = "
        SELECT id, exam_time, slot_limit,
               slot_limit - IFNULL(
                   (SELECT COUNT(*) FROM tbl_reservation WHERE id = tbl_exam_schedule.id), 0
               ) AS remaining_slots
        FROM tbl_exam_schedule
        WHERE exam_date = ?
          AND REPLACE(LOWER(TRIM(venue)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')
          AND REPLACE(LOWER(TRIM(room)), ' ', '') = REPLACE(LOWER(TRIM(?)), ' ', '')
        ORDER BY exam_time ASC
    ";

    $stmt = $con->prepare($query);

    if (!$stmt) {
        throw new Exception("Failed to prepare SQL: " . $con->error);
    }

    $stmt->bind_param("sss", $date, $venue, $room);
    $stmt->execute();
    $result = $stmt->get_result();

    error_log("Rows found: " . $result->num_rows);

    $slots = [];
    while ($row = $result->fetch_assoc()) {
        $slots[] = [
            'id' => $row['id'],
            'exam_time' => $row['exam_time'],
            'slot_limit' => (int)$row['slot_limit'],
            'remaining_slots' => max(0, (int)$row['remaining_slots']),
        ];
    }

    echo json_encode($slots);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
