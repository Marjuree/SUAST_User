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
    <title>Permit Requests | Dashboard</title>
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
   require_once('includes/header.php');
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
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
