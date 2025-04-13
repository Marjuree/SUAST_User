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
    <title>HRMO | Dash</title>

<!--For Unauthorize Icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Base CSS -->
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
   

            <aside class="right-side">
            <section class="content-header">
                <p>Welcome, <strong><?= isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'User'; ?></strong></p>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">

                    <section class="content">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Leave Request From Employee</h3>
                    </div>
                    <br>
                    
                    <?php 
                    // Display session messages for success or error
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>

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
                                    <th>Action</th>
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
                                        <td>
                                            <form action="update_status.php" method="POST">
                                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                                                <button type="submit" name="disapprove" class="btn btn-danger btn-sm">Disapprove</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </section>
        </aside>
    </div>

    <?php require_once "../../includes/footer.php"; ?>
    
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../vendors/chart.js/Chart.min.js"></script>
    <script src="../../js/chart.js"></script>
    
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










