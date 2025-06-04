<?php
if (isset($_GET['ref'])) {
    $reference_number = $_GET['ref'];
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Request Confirmation</title>
</head>
<body>
    <h2>Your request has been submitted successfully!</h2>
    <p>Reference Number: <strong><?php echo $reference_number; ?></strong></p>
    <a href="dashboard.php">Go Back to Dashboard</a>
</body>
</html>
