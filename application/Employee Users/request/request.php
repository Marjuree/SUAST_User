<?php
session_start();
include "../connection.php"; // Ensure this includes a valid $con connection

if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Leave | Request</title>
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    
    <?php include('../../includes/head_css.php'); ?>
</head>

<body class="skin-blue">
    <?php 
    include('../../includes/header.php'); 
    include('../../includes/sidebar.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <aside class="right-side">
            <section class="content-header">
                <p>Welcome, <strong><?php echo isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User'; ?></strong></p>
            </section>

            <section class="content">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Leave Request From Employee</h3>
                    </div>
                    <br>
                    <div class="box-body">
                        <?php
                        // Fetch all leave requests from tbl_leave_requests
                        $query = "SELECT id, request_type, date_request, name, faculty, leave_dates, leave_form, request_status, created_at 
                                  FROM tbl_leave_requests ORDER BY created_at DESC";
                        $result = $con->query($query);

                        if (!$result) {
                            die("Query failed: " . $con->error); // Debugging if query fails
                        }
                        ?>
                        <h3>Submitted Leave Requests</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date of Request</th>
                                    <th>Full Name</th>
                                    <th>Faculty/Institute</th>
                                    <th>Request Type</th>
                                    <th>Leave Dates</th>
                                    <th>Leave Form</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['date_request']) ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['faculty']) ?></td>
                                        <td><?= htmlspecialchars($row['request_type']) ?></td>
                                        <td><?= htmlspecialchars($row['leave_dates']) ?></td>
                                        <td>
                                            <?php if (!empty($row['leave_form'])): ?>
                                                <a href="../leave_forms/<?= htmlspecialchars($row['leave_form']) ?>" target="_blank">View</a>
                                            <?php else: ?>
                                                No File
                                            <?php endif; ?>
                                        </td>
                                        <td><span class="badge bg-info"><?= htmlspecialchars($row['request_status']) ?></span></td>
                                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    

                  
    
    <script src="../../vendors/js/vendor.bundle.base.js"></script> 
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../vendors/chart.js/Chart.min.js"></script>
    <script src="../../js/chart.js"></script>

    <?php include "../../includes/footer.php"; ?>
</body>
</html>
