<?php
require_once "../../configuration/config.php";

header('Content-Type: application/json');

$colors = ['#28a745', '#007bff', '#ffc107', '#17a2b8', '#ff5722', '#6f42c1']; // green, blue, yellow, cyan, orange, purple
$colorIndex = 0;

$query = "SELECT id, exam_name, exam_date, exam_time, venue, room, slot_limit FROM tbl_exam_schedule WHERE status = 'active'";
$result = $con->query($query);

if (!$result) {
    echo json_encode([]);
    exit;
}

$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['exam_name'],
        'start' => $row['exam_date'], // ISO date string
        'exam_date' => $row['exam_date'],
        'exam_time' => $row['exam_time'],
        'venue' => $row['venue'],
        'room' => $row['room'],
        'slot_limit' => $row['slot_limit'],
        'color' => $colors[$colorIndex % count($colors)]
    ];
    $colorIndex++;
}

echo json_encode($events);
?>
