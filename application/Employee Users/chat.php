<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../configuration/config.php"; // Ensure database connection

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}

session_regenerate_id(true);

$employee_id = $_SESSION['employee_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Employee";

// Handle message submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["message"])) {
    $message = trim($_POST["message"]);
    if (!empty($message)) {
        $stmt = $con->prepare("INSERT INTO tbl_message (username, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $first_name, $message);
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
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(120deg, #e0eafc, #cfdef3);
        margin: 0;
        padding: 0;
    }

    .chat-container {
        width: 100%;
        max-width: 700px;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        margin: 30px auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        background: linear-gradient(to right, #0078ff, #00b4db);
        color: #fff;
        padding: 20px;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        letter-spacing: 1px;
        box-shadow: inset 0 -2px 5px rgba(0,0,0,0.1);
    }

    .chat-box {
        height: 450px;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        background-color: #f9f9f9;
        scroll-behavior: smooth;
    }

    .message {
        max-width: 75%;
        padding: 12px 16px;
        margin: 10px 0;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        transition: all 0.3s ease;
    }

    .sent {
        background: #0078ff;
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 0;
    }

    .received {
        background: #e2e8f0;
        align-self: flex-start;
        color: #333;
        border-bottom-left-radius: 0;
    }

    .message strong {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        color: rgba(255,255,255,0.85);
    }

    .received strong {
        color: #0078ff;
    }

    .timestamp {
        font-size: 11px;
        color: #999;
        margin-top: 6px;
        display: block;
        text-align: right;
    }

    .chat-input {
        display: flex;
        padding: 15px;
        background: #f1f5f9;
        border-top: 1px solid #e0e0e0;
    }

    .chat-input input {
        flex: 1;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 25px;
        outline: none;
        font-size: 14px;
        transition: border 0.3s;
    }

    .chat-input input:focus {
        border-color: #0078ff;
    }

    .chat-input input::placeholder {
        color: #aaa;
    }

    .chat-input button {
        background: #0078ff;
        color: white;
        border: none;
        padding: 12px 20px;
        margin-left: 10px;
        border-radius: 25px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .chat-input button:hover {
        background: #005fcc;
    }
</style>

</head>
<body class="skin-blue">
    <?php 
    require_once('includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>
            
            <section class="content">
                <div class="chat-container">
                    <div class="chat-header">Messenger Chat</div>
                    <div class="chat-box">
                        <?php
                        $result = $con->query("SELECT * FROM tbl_message ORDER BY created_at ASC");
                        while ($row = $result->fetch_assoc()): ?>
                            <div class="message <?php echo ($row['username'] == $first_name) ? 'sent' : 'received'; ?>">
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
