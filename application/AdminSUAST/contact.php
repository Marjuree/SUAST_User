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
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
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
                            <h2 class="text-center">Contact Details</h2>
                            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addContactModal">Add Contact</button>
                            <div class="contact-container mt-3" id="contactList">
                                <?php
                                $query = "SELECT * FROM tbl_contact";
                                $result = mysqli_query($con, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<table class='table table-bordered'>";
                                    echo "<tr><th>Name</th><th>Email</th><th>Phone</th></tr>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['phone']}</td></tr>";
                                    }
                                    echo "</table>";
                                } else {
                                    echo "<p>No contact details found.</p>";
                                }
                                ?>
                            </div>
    
                        
                        <!-- Add Contact Modal -->
                        <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addContactModalLabel">Add Contact</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="add_contact.php">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone</label>
                                                <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Add Contact</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    
    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
