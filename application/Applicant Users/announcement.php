<?php
session_start();
require_once "../../configuration/config.php"; // Ensure database connection

// Debugging: Check session values
// Uncomment the next lines to debug session issues
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit();
*/

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);

// Store session values safely
$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant"; // Prevent XSS
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Announcement | Dash</title>
    <link rel="shortcut icon" href="../../../img/favicon.png" />
    
    <style>
        .chat-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .chat-message {
            background-color: #d1e7dd;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .chat-meta {
            font-size: 12px;
            color: gray;
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
                <div class="row">
                    <div class="box">
                        <div class="container mt-4">
                            <h2 class="text-center">Announcements</h2>
                            
                            
                            <div class="chat-container mt-3" id="announcementList">
                            <!-- Messages will be loaded here -->
                            </div>
                        </div>
                    </div>
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
        
        function sendMessage() {
            let adminName = document.getElementById("adminName").value.trim();
            let messageText = document.getElementById("messageInput").value.trim();
            let status = document.getElementById("statusInput").value;
        
            if (adminName !== "" && messageText !== "") {
                fetch("post_announcement.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `admin_name=${encodeURIComponent(adminName)}&message=${encodeURIComponent(messageText)}&status=${encodeURIComponent(status)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("success")) {
                        document.getElementById("adminName").value = "";
                        document.getElementById("messageInput").value = "";
                        fetchAnnouncements();
                        let modal = bootstrap.Modal.getInstance(document.getElementById("announcementModal"));
                        modal.hide();
                    } else {
                        alert("Failed to send: " + data);
                    }
                })
                .catch(error => console.error("Error:", error));
            } else {
                alert("Please fill all fields.");
            }
        }
    </script>
    
    <?php require_once "../../includes/footer.php"; ?>
    
 
    
    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0, 5] }],
                "aaSorting": []
            });
        });
    </script>
</body>
</html>
