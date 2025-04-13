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
    <title>Clearance Requests | Dashboard</title>
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
                            <h3>Clearance Requests</h3>

                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th> <!-- Added Student Name Column -->
                                        <th>Status</th>
                                        <th>Date Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch clearance requests with student names
                                    $query = "
                                        SELECT cr.id, cr.student_id, su.student_name, cr.status, cr.date_requested
                                        FROM tbl_clearance_requests cr
                                        LEFT JOIN tbl_student_users su ON cr.student_id = su.student_id
                                        ORDER BY cr.date_requested DESC";
                                    
                                    $result = $con->query($query);

                                    while ($row = $result->fetch_assoc()): 
                                        $status = !empty($row['status']) ? htmlspecialchars($row['status']) : "Pending";
                                        $badgeClass = ($status == 'Approved') ? 'success' : (($status == 'Disapproved') ? 'danger' : 'info');
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['student_id']) ?></td>
                                            <td><?= htmlspecialchars($row['student_name'] ?? 'Unknown') ?></td> <!-- Display Student Name -->
                                            <td>
                                                <span class="badge bg-<?= $badgeClass ?>">
                                                    <?= $status ?>
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
