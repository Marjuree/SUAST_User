<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

require_once "../../configuration/config.php"; // Ensure database connection

session_regenerate_id(true);

$userid = $_SESSION['userid'];
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "role";

// Handle message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["message"])) {
    $message = trim($_POST["message"]);
    if (!empty($message)) {
        $stmt = $con->prepare("INSERT INTO tbl_message (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $message); // Use $username instead of undefined $first_name
        $stmt->execute();
        $stmt->close();
    }
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger-Chat</title>
    <link rel="stylesheet" href="../../css/button.css">
    <link rel="shortcut icon" href="../../img/favicon.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }

        .chat-container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            padding: 20px;
        }

        .chat-header {
            background: #0078ff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 70%;
            padding: 10px;
            margin: 5px;
            border-radius: 10px;
            font-size: 14px;
        }

        .sent {
            background: #0078ff;
            color: white;
            align-self: flex-end;
        }

        .received {
            background: #e0e0e0;
            align-self: flex-start;
        }

        .timestamp {
            font-size: 10px;
            color: #555;
            display: block;
            margin-top: 5px;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            background: #fff;
            border-top: 1px solid #ddd;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 5px;
        }

        .chat-input button {
            background: #0078ff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body class="skin-blue">
    <?php 
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo $username; ?></strong></p>
            </section>
            
            <section class="content">
                <div class="chat-container">
                    <div class="chat-header">Messenger Chat</div>
                    <div class="chat-box">
                        <?php
                        $result = $con->query("SELECT * FROM tbl_message ORDER BY created_at ASC");
                        while ($row = $result->fetch_assoc()): ?>
                            <div class="message <?php echo ($row['username'] == $username) ? 'sent' : 'received'; ?>">
                                <strong><?php echo $row['username']; ?>:</strong>
                                <p><?php echo $row['message']; ?></p>
                                <span class="timestamp"><?php echo $row['created_at']; ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <form method="POST" class="chat-input">
                        <input type="text" name="message" placeholder="Type a message..." required>
                        <button type="submit">Send</button>
                    </form>
                </div>
            </section>
        </aside>
    </div>

    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
