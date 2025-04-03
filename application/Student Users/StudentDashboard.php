<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../configuration/config.php"; // Ensure database connection

// Debugging: Ensure session values are properly set
if (!isset($_SESSION['student_id'])) {
    die("Session not set. Please log in again.");
}

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../../php/error.php?welcome=Please login as a Student");
    exit();
}

// Regenerate session ID for security AFTER checking session variables
session_regenerate_id(true);

// Store session values safely
$student_id = $_SESSION['student_id'];
$first_name = isset($_SESSION['student_name']) ? htmlspecialchars($_SESSION['student_name']) : "Student"; // Prevent XSS
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Student | Dash</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>
<style>
        .table th, .table td {
            text-align: center; /* Center table content */
            vertical-align: middle;
        }
    </style>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Student Dashboard</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">

                    <div class="box-body">

                    <!-- Button to Open Modal -->
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addPermitModal">
                        Request Permit
                    </button>


                    <!-- Button to Open Modal -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#requestClearanceModal">
                        Request Clearance
                    </button>

                    

                <!-- Request Clearance Section -->
                <div id="requestClearance" class="toggle-section" style="display: none;">
                    <h3>Clearance Requests</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Date Requested</th>
                                <th>Status</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, student_id, status, date_requested FROM tbl_clearance_requests ORDER BY date_requested DESC";
                            $result = $con->query($query);
                            while ($row = $result->fetch_assoc()): 
                                $status = !empty($row['status']) ? htmlspecialchars($row['status']) : "Pending";
                                $badgeClass = ($status == 'Approved') ? 'success' : (($status == 'Disapproved') ? 'danger' : 'info');
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                                    <td><?= htmlspecialchars($row['date_requested']) ?></td>
                                    <td><span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Request Permit Section -->
                <div id="requestPermit" class="toggle-section" style="display: none;">
                    <h3>Permit Requests</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Purpose</th>
                                <th>Course & Year</th>
                                <th>Type of Permit</th>
                                <th>Status</th>
                                <th>Date Requested</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM tbl_permits ORDER BY date_requested DESC";
                            $result = $con->query($query);
                            while ($row = $result->fetch_assoc()): 
                                $status = $row['status'];
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                                    <td><?= htmlspecialchars($row['purpose_name']) ?></td>
                                    <td><?= htmlspecialchars($row['course_year']) ?></td>
                                    <td><?= htmlspecialchars($row['type_of_permit']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $status == 'Approved' ? 'success' : ($status == 'Rejected' ? 'danger' : 'info') ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['date_requested']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

 
 

    <?php require_once "modal.php"; ?>
   
    <?php require_once "../../includes/footer.php"; ?>
     
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/chart.js"></script>
    
    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0, 5] }],
                "aaSorting": []
            });
        });
    </script>

<!-- JavaScript for Toggling Sections -->
<script>
    $(document).ready(function () {
        $(".toggle-table").click(function () {
            $(".toggle-section").hide();  // Hide all sections
            $("#" + $(this).data("target")).fadeIn(); // Show only selected section
        });
    });
</script>



</body>
</html>
