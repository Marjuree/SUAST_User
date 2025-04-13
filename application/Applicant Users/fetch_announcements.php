<?php
require_once "../../configuration/config.php";

// Fetch only announcements where role is 'SUAST'
$sql = "SELECT admin_name, message, status, role, created_at 
        FROM tbl_announcement 
        WHERE role = 'SUAST' 
        ORDER BY created_at DESC";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admin = htmlspecialchars($row["admin_name"]);
        $message = nl2br(htmlspecialchars($row["message"]));
        $status = htmlspecialchars($row["status"]);
        $role = htmlspecialchars($row["role"]);
        $created_at = date("F d, Y h:i A", strtotime($row["created_at"]));

        // Status badge color
        $statusColor = $status === 'Active' ? '#4CAF50' : '#F44336';

        echo '
        <div style="
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.07);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        ">
            <img src=\'https://cdn-icons-png.flaticon.com/512/3039/3039396.png\' alt=\'icon\' width=\'60\' style=\'margin-bottom: 15px;\'>
            <h2 style="color: #003366; font-weight: bold; margin-bottom: 10px;">NOTICE</h2>
            <p style="font-size: 16px; color: #333; margin-bottom: 20px;">' . $message . '</p>

            <div style="display: flex; justify-content: space-around; align-items: center; font-size: 14px; color: #666; flex-wrap: wrap;">
                <div><strong>Status:</strong> <span style="color:' . $statusColor . '; font-weight:bold;">' . $status . '</span></div>
                <div><strong>From:</strong> ' . $admin . '</div>
                <div><strong>Role:</strong> ' . $role . '</div>
                <div><strong>Date:</strong> ' . $created_at . '</div>
            </div>
        </div>';
    }
} else {
    echo '<p style="text-align: center; color: #999;">No announcements yet.</p>';
}
?>
