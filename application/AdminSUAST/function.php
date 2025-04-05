<?php
require_once "../../configuration/config.php";

if ($_POST['action'] == "edit") {
    $id = $_POST['id'];
    $name = $_POST['exam_name'];
    $date = $_POST['exam_date'];
    $time = $_POST['exam_time'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];

    mysqli_query($con, "UPDATE tbl_exam_schedule SET exam_name='$name', exam_date='$date', exam_time='$time', subject='$subject', room='$room' WHERE id='$id'");
    echo "Exam schedule updated successfully!";
}

if ($_POST['action'] == "delete") {
    $id = $_POST['id'];
    mysqli_query($con, "DELETE FROM tbl_exam_schedule WHERE id='$id'");
    echo "Exam schedule deleted successfully!";
}
?>















