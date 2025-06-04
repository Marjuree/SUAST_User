<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}

require_once "../../configuration/config.php"; // Ensure database connection

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clearance Requests | Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../css/style.css">
    <link href="../../css/landing.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <style>
        .table th, .table td {
            text-align: center; /* Center table content */
            vertical-align: middle;
        }
    </style>
</head>
<body class="skin-blue">
    <?php 
    require_once('../../includes/header.php');
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Request Clearance | Dashboard</h1>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <hr>
                            <h3>Clearance Requests</h3>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Status</th>
                                        <th>Date Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "
                                        SELECT cr.id, cr.student_id, 
                                               COALESCE(su.full_name, 'Unknown Student') AS full_name, 
                                               cr.status, cr.date_requested 
                                        FROM tbl_clearance_requests cr
                                        LEFT JOIN tbl_student_users su ON cr.student_id = su.school_id
                                        ORDER BY cr.date_requested DESC";
                                    
                                    $result = $con->query($query);
                                    while ($row = $result->fetch_assoc()): 
                                        $status = !empty($row['status']) ? htmlspecialchars($row['status']) : "Pending";
                                        $badgeClass = ($status == 'Approved') ? 'success' : (($status == 'Disapproved') ? 'danger' : 'info');
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['student_id']) ?></td>
                                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= $status ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['date_requested']) ?></td>
                                            <td>
                                                <?php if ($status === "Pending") : ?>
                                                    <form method="POST" action="process_clearance.php">
                                                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                                        <button type="submit" name="disapprove" class="btn btn-danger">Disapprove</button>
                                                    </form>
                                                <?php elseif ($status === "Approved") : ?>
                                                    <form method="POST" action="generate_clearance.php" target="_blank">
                                                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                                        <button type="submit" name="generate_clearance" class="btn btn-primary">
                                                            <i class="fas fa-file-alt"></i> Generate Clearance
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-muted">No actions available</span>
                                                <?php endif; ?>
                                            </td>
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
    
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
</body>
</html>
