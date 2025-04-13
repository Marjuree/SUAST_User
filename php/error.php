<?php
session_start();
// Regenerate session ID to prevent fixation attacks
session_regenerate_id(true);
require_once "../configuration/config.php"; // Ensure this file contains $conn for database connection

// Get error message from URL or set a default message
$error_message = isset($_GET['welcome']) ? htmlspecialchars($_GET['welcome'], ENT_QUOTES, 'UTF-8') : 'Access Denied';

// Generate a unique error ID
$error_id = uniqid('ERR-', true);
$_SESSION['error_id'] = $error_id;

// Securely store the username if available
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'Guest';

// Get user IP address
$ip_address = $_SERVER['REMOTE_ADDR'];

// Get User-Agent string
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Function to detect device name
function getDeviceName($user_agent) {
    if (strpos($user_agent, 'iPhone') !== false) {
        return 'iPhone';
    } elseif (strpos($user_agent, 'iPad') !== false) {
        return 'iPad';
    } elseif (strpos($user_agent, 'Android') !== false) {
        return 'Android Phone';
    } elseif (strpos($user_agent, 'Windows') !== false) {
        return 'Windows PC';
    } elseif (strpos($user_agent, 'Macintosh') !== false || strpos($user_agent, 'Mac OS') !== false) {
        return 'Mac';
    } elseif (strpos($user_agent, 'Linux') !== false) {
        return 'Linux PC';
    } else {
        return 'Unknown Device';
    }
}

// Detect device name
$device_name = getDeviceName($user_agent);

// Store unauthorized access in the database
$stmt = $con->prepare("INSERT INTO tbl_unauthorized_access (username, error_id, ip_address, device_name) VALUES (?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("ssss", $username, $error_id, $ip_address, $device_name);
    $stmt->execute();
    $stmt->close();
} else {
    die("Database error: " . $con->error);
}

// Check if the user has access to redirect them
if (isset($_SESSION['testing_access']) && $_SESSION['testing_access'] === true) {
    header("Location: dashboard.php"); // Redirect to any page you want
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #87CEEB;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 20px;
            text-align: center;
        }
        .error-container {
            background: #fff;
            color: #000;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 15px 40px rgba(255, 0, 0, 0.6);
            max-width: 600px;
            width: 100%;
        }
        h1 { color: #ff4d4d; font-size: 30px; margin-bottom: 15px; }
        p { font-size: 18px; margin: 15px 0; line-height: 1.6; }
        .error-id { font-weight: bold; color: #ff4d4d; font-size: 20px; }
        .back-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 15px 30px;
            background: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0px 6px 15px rgba(255, 0, 0, 0.5);
        }
        .back-btn:hover { background: #cc0000; transform: scale(1.08); }
        .law-notice { margin-top: 20px; font-size: 14px; color: #555; }
        .law-notice b { color: #d32f2f; }
    </style>
</head>
<body>

    <div class="error-container">
        <h1>⚠️ Access Denied</h1>
        <p><?php echo $error_message; ?></p>
        <p>Your Error ID: <span class="error-id"><?php echo $error_id; ?></span></p>
        <p>User: <b><?php echo $username; ?></b></p>
        <p>Device: <b><?php echo $device_name; ?></b></p>
        <a href="landing_page.php" class="back-btn">Go to Login</a>

        <p class="law-notice">
            Unauthorized access is a criminal offense under <b>Republic Act No. 10175 (Cybercrime Prevention Act of 2012)</b>.  
            Any attempt to breach this system will be reported to the authorities.
        </p>
    </div>

</body>
</html>
