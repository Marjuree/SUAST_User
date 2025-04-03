<?php
require_once "../../configuration/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_name'], $_POST['message'], $_POST['status'], $_POST['role'])) {
    $admin_name = trim($_POST['admin_name']);
    $message = trim($_POST['message']);
    $status = trim($_POST['status']);
    $role = trim($_POST['role']);

    if (!empty($admin_name) && !empty($message) && !empty($role)) {  // Ensure role is not empty
        $admin_name = $con->real_escape_string($admin_name);
        $message = $con->real_escape_string($message);
        $status = $con->real_escape_string($status);
        $role = $con->real_escape_string($role); // Fixed: Escaping role input

        $sql = "INSERT INTO tbl_announcement (admin_name, message, status, role) VALUES ('$admin_name', '$message', '$status', '$role')";

        if ($con->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $con->error;
        }
    } else {
        echo "Error: Fields cannot be empty!";
    }
} else {
    echo "Error: Invalid request!";
}
?>
