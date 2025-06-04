<?php
session_start();
require_once "../../configuration/config.php";

if (!isset($_POST['id'])) {
    die("Invalid request.");
}

$permit_id = $_POST['id'];
$query = "SELECT * FROM tbl_permits WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $permit_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Permit not found.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Permit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            text-align: center;
            background-color: #f4f4f4;
        }
        .permit-container {
            background: white;
            border: 3px solid black;
            padding: 25px;
            max-width: 600px;
            margin: 0 auto;
            text-align: left;
            box-shadow: 5px 5px 15px rgba(0,0,0,0.2);
        }
        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content {
            font-size: 16px;
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .value {
            display: inline-block;
            text-transform: uppercase;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
        .print-btn {
            background-color: blue;
            color: white;
        }
        .cancel-btn {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>

<div class="permit-container">
    <div class="header">STUDENT PERMIT DETAILS</div>
    
    <div class="content"><span class="label">Permit ID:</span> <span class="value"><?= htmlspecialchars($row['id']) ?></span></div>
    <div class="content"><span class="label">Student Name:</span> <span class="value"><?= htmlspecialchars($row['student_name']) ?></span></div>
    <div class="content"><span class="label">Purpose:</span> <span class="value"><?= htmlspecialchars($row['purpose_name']) ?></span></div>
    <div class="content"><span class="label">Course & Year:</span> <span class="value"><?= htmlspecialchars($row['course_year']) ?></span></div>
    <div class="content"><span class="label">Type of Permit:</span> <span class="value"><?= htmlspecialchars($row['type_of_permit']) ?></span></div>
    <div class="content"><span class="label">Date Requested:</span> <span class="value"><?= htmlspecialchars($row['date_requested']) ?></span></div>

    <div class="buttons">
        <button class="print-btn" onclick="window.print()">Print</button>
        <button class="cancel-btn" onclick="window.location.href='permit.php'">Cancel</button>
    </div>
</div>

</body>
</html>
