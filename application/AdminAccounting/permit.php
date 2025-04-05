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
    <title>Permit Requests | Dashboard</title>
    
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
    require_once "../../configuration/config.php";
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Student Dashboard</h1>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <hr>
                            <h3>Permit Requests</h3>

                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Purpose</th>
                                        <th>Course & Year</th>
                                        <th>Type of Permit</th>
                                        <th>Status</th>
                                        <th>Date Requested</th>
                                        <th>Actions</th>
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
                                            <td>
                                                <?php if ($status == "Pending") : ?>
                                                    <form method="POST" action="update_permit.php">
                                                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                                        <button type="submit" name="reject" class="btn btn-danger">Reject</button>
                                                    </form>
                                                <?php elseif ($status == "Approved") : ?>
                                                    <form method="POST" action="generate_permit.php" target="_blank">
                                                        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                                        <button type="submit" class="btn btn-primary">Generate</button>
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

    <?php include "../../includes/footer.php"; ?>
    
</body>
</html>
