<?php
require_once "../../configuration/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM tbl_student_balances WHERE id = $id";

    if (mysqli_query($con, $query)) {
        header("Location: student_balances.php?success=deleted");
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
