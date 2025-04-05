<?php
session_start();
require_once "../../configuration/config.php";

if (!isset($_POST['id'])) {
    die("No clearance request ID provided.");
}

$request_id = intval($_POST['id']);
$query = "SELECT cr.id, cr.student_id, su.full_name, cr.status, cr.date_requested 
          FROM tbl_clearance_requests cr 
          LEFT JOIN tbl_student_users su ON cr.student_id = su.school_id 
          WHERE cr.id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Invalid request ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Document</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #000; }
        h1 { font-size: 24px; }
        .details { text-align: left; margin-top: 20px; }
        .details p { font-size: 16px; }
        .footer { margin-top: 30px; font-style: italic; }
        .buttons { margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; cursor: pointer; font-size: 16px; margin: 5px; }
        .print-btn { background-color: blue; color: white; }
        .print-btn:hover { background-color: darkblue; }
        .cancel-btn { background-color: red; color: white; }
        .cancel-btn:hover { background-color: darkred; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Student Clearance Document</h1>
        <div class="details">
            <p><strong>Clearance Request ID:</strong> <?= htmlspecialchars($row['id']) ?></p>
            <p><strong>Student Name:</strong> <?= htmlspecialchars($row['full_name'] ?? 'N/A') ?></p>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($row['student_id']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
            <p><strong>Date Requested:</strong> <?= htmlspecialchars($row['date_requested']) ?></p>
        </div>

        <div class="footer">
            <p>Generated on: <?= date("Y-m-d H:i:s") ?></p>
        </div>

        <div class="buttons">
            <button class="btn print-btn" onclick="printPage()">Print</button>
            <button class="btn cancel-btn" onclick="goBack()">Cancel</button>
        </div>
    </div>

    <script>
        function printPage() {
            window.print(); // Opens the print dialog
        }

        function goBack() {
            window.location.href = "AccountingDashboard.php?success=cancel"; // Redirect back to the dashboard
        }
    </script>

</body>
</html>
