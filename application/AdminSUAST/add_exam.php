<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_name = mysqli_real_escape_string($con, $_POST['exam_name']);
    $exam_date = mysqli_real_escape_string($con, $_POST['exam_date']);
    $exam_time = mysqli_real_escape_string($con, $_POST['exam_time']);
    $subject = mysqli_real_escape_string($con, $_POST['subject']);
    $room = mysqli_real_escape_string($con, $_POST['room']);

    // Check if the selected room already has 30 slots filled
    $checkSlotsQuery = "SELECT COUNT(*) as total_slots FROM tbl_exam_schedule WHERE room = '$room' AND exam_date = '$exam_date'";
    $result = mysqli_query($con, $checkSlotsQuery);
    $data = mysqli_fetch_assoc($result);

    if ($data['total_slots'] >= 30) {
        echo "Error: Room is already fully booked for this date.";
        exit();
    }

    // Insert the data into the database
    $insertQuery = "INSERT INTO tbl_exam_schedule (exam_name, exam_date, exam_time, subject, room) 
                    VALUES ('$exam_name', '$exam_date', '$exam_time', '$subject', '$room')";

    if (mysqli_query($con, $insertQuery)) {
        echo "Exam schedule successfully added.";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
