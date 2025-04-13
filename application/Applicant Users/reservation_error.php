<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reservation Error</title>
</head>
<body>
    <h2>❌ Reservation Failed</h2>
    <p><?php echo htmlspecialchars($_GET['error'] ?? 'Unknown error'); ?></p>
    <a href="exam_reservation.php">Try again</a>
</body>
</html>
