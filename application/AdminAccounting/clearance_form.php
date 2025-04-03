<?php
require_once "../../configuration/config.php"; // Ensure database connection

// Fetch student details (modify as needed if listing multiple students)
$query = "SELECT * FROM tblapplicants";
$result = $con->query($query);

if (!$result) {
    die("Error fetching student data: " . $con->error); // Debugging SQL error
}

$students = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .permit-box {
            width: 600px;
            margin: 20px auto;
            border: 2px solid black;
            padding: 20px;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
        .info {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="permit-box">
        <h2>CLEARANCE FORM</h2>

        <?php foreach ($students as $student): ?>
            <p class="info"><span class="label">Student Name:</span> <?php echo $student['fname'] . " " . $student['mname'] . " " . $student['lname']; ?></p>
            <p class="info"><span class="label">Birthdate:</span> <?php echo $student['bdate']; ?></p>
            <p class="info"><span class="label">Contact:</span> <?php echo $student['contact']; ?></p>
            <p class="info"><span class="label">Campus:</span> <?php echo $student['campus']; ?></p>
            <hr>
        <?php endforeach; ?>

        <br><br>
        <p style="text-align: center;">This clearance form must be completed before graduation.</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
