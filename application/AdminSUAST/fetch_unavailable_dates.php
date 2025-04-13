<?php
require_once "../../configuration/config.php";

$query = mysqli_query($con, "SELECT exam_date FROM tbl_exam_schedule");
$unavailableDates = [];

while ($row = mysqli_fetch_assoc($query)) {
    $unavailableDates[] = $row['exam_date'];
}

echo json_encode($unavailableDates);
?>
