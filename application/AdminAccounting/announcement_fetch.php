<?php
require_once "../../configuration/config.php";

// Fetch only announcements where role is 'Accounting'
$sql = "SELECT admin_name, message, status, role, created_at 
        FROM tbl_announcement 
        WHERE role = 'Accounting' 
        ORDER BY created_at DESC";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="chat-message">';
        echo '<strong>' . htmlspecialchars($row["admin_name"]) . ':</strong> ' . htmlspecialchars($row["message"]);
        echo '<div class="chat-meta">Status: <strong>' . htmlspecialchars($row["status"]) . '</strong> | From: <strong>' . htmlspecialchars($row["role"]) . '</strong> | ' . date("M d, Y h:i A", strtotime($row["created_at"])) . '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="text-muted">No announcements yet.</p>';
}
?>
