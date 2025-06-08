<?php
session_start();
session_regenerate_id(true);
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Announcements | Dash</title>
    <link rel="shortcut icon" href="../../../img/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #eef2f3, #ffffff);
            font-family: 'Poppins', sans-serif !important;
        }

        .announcements-section {
            max-width: 700px;
            margin: 30px auto;
            padding: 0 20px;
            font-family: 'Poppins', sans-serif !important;
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
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }


        .announcement-card.new {
            background: #cbe7f0;
            border: none;
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

        .announcement-card {
            width: 100%;
            box-sizing: border-box;
            margin: 0;
            padding: 15px;
            border-radius: 8px;
            border: none;
        }

        @media (max-width: 768px) {
            .announcements-section {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
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