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

      .announcements-section {
    max-width: 700px;
    margin: 30px auto;
    padding: 0 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.section-title {
    font-weight: bold;
    font-size: 18px;
    margin: 20px 0 10px;
    color: #111;
}
.announcement-card {
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.announcement-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}


.announcement-card.new {
    background: #cbe7f0;
}

.announcement-card.earlier {
    background: #cbe7f0;
}

.timestamp {
    display: block;
    margin-top: 10px;
    font-size: 12px;
    color: #666;
}

.section-separator {
    text-align: center;
    margin: 10px 0;
}

.section-separator .dot {
    width: 8px;
    height: 8px;
    background: red;
    border-radius: 50%;
    display: inline-block;
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
                <div class="announcement-wrapper" id="announcementList">
                    <!-- Dynamic announcements will be loaded here -->
                </div>
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