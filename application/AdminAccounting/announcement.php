<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../php/error.php?welcome=Please login to access this page");
        exit();
    }
    ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Announcement | Dash</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    
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
        require_once('../../includes/header.php');
        require_once('../../includes/head_css.php');
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="container mt-4">
                            <h2 class="text-center">Announcements</h2>
                            
                            <button class="btn btn-primary" data-toggle="modal" data-target="#announcementModal">
                                New Announcement
                            </button>
                            <hr>
                            
                            
                            <div class="chat-container mt-3" id="announcementList">
                            <!-- Messages will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    
  <!-- Enhanced Announcement Modal -->
<div id="announcementModal" class="modal fade" tabindex="-1" role="dialog">
    <form method="post">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">📢 Post Announcement</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="adminName">Name From:</label>
                        <input type="text" id="adminName" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="messageInput">Announcement:</label>
                        <textarea id="messageInput" class="form-control" rows="4" placeholder="Type your message here..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="statusInput">Status:</label>
                        <select id="statusInput" class="form-control">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="roleInput">Office of:</label>
                        <select id="roleInput" class="form-control">
                            <option value="Accounting">Accounting</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger px-4 py-2" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success px-4 py-2" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i> Post Announcement
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


    
    <script>
        document.addEventListener("DOMContentLoaded", fetchAnnouncements);
        
        function fetchAnnouncements() {
            fetch("announcement_fetch.php")
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
        let role = document.getElementById("roleInput").value;

        if (adminName !== "" && messageText !== "") {
            let formData = new URLSearchParams();
            formData.append("admin_name", adminName);
            formData.append("message", messageText);
            formData.append("status", status);
            formData.append("role", role);

            fetch("announcement_post.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData.toString()
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes("success")) {
                    // Clear fields
                    document.getElementById("adminName").value = "";
                    document.getElementById("messageInput").value = "";

                    // Refresh announcements
                    fetchAnnouncements();

                    // Close the modal (for Bootstrap 4)
                    $('#announcementModal').modal('hide');
                } else {
                    alert("Failed to send: " + data);
                }
            })
            .catch(error => console.error("Error:", error));
        } else {
            alert("Please fill in all fields.");
        }
    }

        </script>
        
        <?php include "../../includes/footer.php"; ?>
        
    
        
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