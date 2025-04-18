<?php
session_start();
session_regenerate_id(true);
require_once "../../configuration/config.php";

// Debugging: Ensure session values are properly set
if (!isset($_SESSION['student_id'])) {
    die("Session not set. Please log in again.");
}

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../../php/error.php?welcome=Please login as a Student");
    exit();
}

// Store session values safely
$student_id = $_SESSION['student_id'];
$first_name = isset($_SESSION['student_name']) ? htmlspecialchars($_SESSION['student_name']) : "Student";

// Fetch the specific student data based on the logged-in user's ID
$query = "SELECT * FROM tbl_student_users WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student_data = $result->fetch_assoc();

// Debugging: Log student data
error_log("Fetched student data: " . print_r($student_data, true));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Announcements | Dash</title>
    <link rel="shortcut icon" href="../../../img/favicon.png" />

    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .announcement-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .announcement-container img {
            width: 80px;
            margin-bottom: 15px;
        }

        .announcement-container h1 {
            font-size: 36px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .announcement-container p {
            font-size: 16px;
            color: #444;
            margin: 10px 0 20px;
        }

        .notice-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #2196f3;
            color: #fff;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .notice-btn:hover {
            background: #1769aa;
        }

        .announcement-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 13px;
            color: #777;
        }

        .announcement-footer strong {
            display: block;
            color: #111;
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
            <section class="content">
                <div id="announcementList"></div>
            </section>
        </aside>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", fetchAnnouncements);

        function fetchAnnouncements() {
            fetch("fetch_announcements.php")
                .then(response => response.text())
                .then(data => {
                    document.getElementById("announcementList").innerHTML = data;
                })
                .catch(error => console.error("Error fetching announcements:", error));
        }
    </script>

    <?php require_once "../../includes/footer.php"; ?>
</body>

</html>
