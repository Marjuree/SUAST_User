<?php
require_once "../../configuration/config.php";

if (isset($_POST['update_balance'])) {
    $id = $_POST['id'];
    $total_balance = $_POST['total_balance'];
    $last_payment = $_POST['last_payment'];
    $due_date = $_POST['due_date'];

    $query = "UPDATE tbl_student_balances 
              SET total_balance = ?, last_payment = ?, due_date = ? 
              WHERE id = ?";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("dssi", $total_balance, $last_payment, $due_date, $id);
        if ($stmt->execute()) {
            header("Location: student_balances.php?success=Balance updated successfully");
        } else {
            header("Location: student_balances.php?error=Failed to update balance");
        }
        $stmt->close();
    } else {
        header("Location: student_balances.php?error=Database error");
    }
}
?>
