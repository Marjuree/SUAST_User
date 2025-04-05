<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../configuration/config.php"; // Ensure database connection

// Uncomment the next lines to debug session issues

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// exit();

// Check if the user is logged in and is an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);

// Store session values safely
$employee_id = $_SESSION['employee_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Employee"; // Prevent XSS
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Employees | Dash</title>
    <link href="../../css/button.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />
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
                <div class="row">
                    <div class="box">

                    <div class="box-body">
                        <!-- Buttons to toggle tables -->
                        <button class="btn btn-primary" data-toggle="modal" data-target="#servicehrmo">SERVICE</button>

                        <hr>

                        <!-- Tables (Hidden by Default) -->
                        <div id="leaverequest" class="toggle-section" style="display: none;">
                            <h3>Leave Requests</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date of Request</th>
                                        <th>Full Name</th>
                                        <th>Faculty/Institute</th>
                                        <th>Request Type</th>
                                        <th>Leave Dates</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tbl_leave_requests ORDER BY created_at DESC";
                                    $result = $con->query($query);
                                    while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['date_request']) ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['faculty']) ?></td>
                                            <td><?= htmlspecialchars($row['request_type']) ?></td>
                                            <td><?= htmlspecialchars($row['leave_dates']) ?></td>
                                            <td><span class="badge bg-info"><?= htmlspecialchars($row['request_status']) ?></span></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="certificationRequests" class="toggle-section" style="display: none;">
                            <h3>Certification Requests</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date of Request</th>
                                        <th>Full Name</th>
                                        <th>Faculty/Institute</th>
                                        <th>Request Type</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tbl_certification_requests ORDER BY created_at DESC";
                                    $result = $con->query($query);
                                    while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['date_request']) ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['faculty']) ?></td>
                                            <td><?= htmlspecialchars($row['request_type']) ?></td>
                                            <td><?= htmlspecialchars($row['reason']) ?></td>
                                            <td><span class="badge bg-info"><?= htmlspecialchars($row['request_status']) ?></span></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="serviceRequests" class="toggle-section" style="display: none;">
                            <h3>Service Requests</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date of Request</th>
                                        <th>Full Name</th>
                                        <th>Faculty/Institute</th>
                                        <th>Request Type</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tbl_service_requests ORDER BY created_at DESC";
                                    $result = $con->query($query);
                                    while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['date_request']) ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['faculty']) ?></td>
                                            <td><?= htmlspecialchars($row['request_type']) ?></td>
                                            <td><?= htmlspecialchars($row['reason']) ?></td>
                                            <td><span class="badge bg-info"><?= htmlspecialchars($row['request_status']) ?></span></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <div id="personnelinquiry" class="toggle-section" style="display: none;">
                                <h3>Submitted Personnel Inquiries</h3>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date of Request</th>
                                            <th>Full Name</th>
                                            <th>Faculty/Institute</th>
                                            <th>Request Type</th>
                                            <th>Question</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM tbl_personnel_inquiries ORDER BY created_at DESC";
                                        $result = $con->query($query);
                                        while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['faculty']) ?></td>
                                                <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                <td><?= htmlspecialchars($row['question']) ?></td>
                                                <td>
                                                    <span class="badge <?= ($row['request_status'] == 'Approved') ? 'bg-success' : (($row['request_status'] == 'Pending') ? 'bg-warning' : 'bg-danger') ?>">
                                                        <?= htmlspecialchars($row['request_status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </section>
        </aside>
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
