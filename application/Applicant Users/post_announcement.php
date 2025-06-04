<?php
require_once "../../configuration/config.php";

 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin_name'], $_POST['message'], $_POST['status'])) {
    $admin_name = trim($_POST['admin_name']);
    $message = trim($_POST['message']);
    $status = trim($_POST['status']);

    if (!empty($admin_name) && !empty($message)) {
        $admin_name = $con->real_escape_string($admin_name);
        $message = $con->real_escape_string($message);
        $status = $con->real_escape_string($status);

        $sql = "INSERT INTO tbl_announcement (admin_name, message, status) VALUES ('$admin_name', '$message', '$status')";
        
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
