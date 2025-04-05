<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../php/error.php?welcome=Please login to access this page");
        exit();
    }
    ob_start();
    
    require_once "../../configuration/config.php";
    
    // Fetch leave requests data
    $leaveRequests = mysqli_query($con, "SELECT DATE_FORMAT(date_request, '%M') as month, COUNT(*) as count FROM tbl_leave_requests GROUP BY month ORDER BY STR_TO_DATE(month, '%M')");
    $leaveMonths = [];
    $leaveCounts = [];
    while ($row = mysqli_fetch_assoc($leaveRequests)) {
        $leaveMonths[] = $row['month'];
        $leaveCounts[] = $row['count'];
    }
    
    // Fetch leave requests per faculty
    $facultyRequests = mysqli_query($con, "SELECT faculty, COUNT(*) as count FROM tbl_leave_requests GROUP BY faculty");
    $facultyNames = [];
    $facultyCounts = [];
    while ($row = mysqli_fetch_assoc($facultyRequests)) {
        $facultyNames[] = $row['faculty'];
        $facultyCounts[] = $row['count'];
    }
    
    // Fetch leave types distribution
    $leaveTypes = mysqli_query($con, "SELECT request_type, COUNT(*) as count FROM tbl_leave_requests GROUP BY request_type");
    $leaveTypeNames = [];
    $leaveTypeCounts = [];
    while ($row = mysqli_fetch_assoc($leaveTypes)) {
        $leaveTypeNames[] = $row['request_type'];
        $leaveTypeCounts[] = $row['count'];
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HRMO | Dash</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="../../assets/chart.js"></script>
    <style>
        canvas {
            max-height: 250px !important;
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
                <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong></p>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="../applicant/applicant.php">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Personal Inquire</span>
                                <span class="info-box-number"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_personnel_inquiries WHERE request_status = 'Pending'")); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="#">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Service Request</span>
                                <span class="info-box-number"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_service_requests")); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="../permit/permit.php">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-file-alt"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Leave Request</span>
                                <span class="info-box-number"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_leave_requests")); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="../students/students.php">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-certificate"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Certification</span>
                                <span class="info-box-number"><?php echo mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_certification_requests")); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Graphs Section -->
                <div class="row">
                    <div class="col-md-4 col-sm-12"><br>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Leave Requests Over Time</h4>
                                <canvas id="lineChart"></canvas>
                            </div>  
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12"><br>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Leave Requests Per Faculty</h4>
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12"><br>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Leave Types Distribution</h4>
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    
    <script>
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($leaveMonths); ?>,
                datasets: [{
                    label: 'Leave Requests Over Time',
                    data: <?php echo json_encode($leaveCounts); ?>,
                    borderColor: 'blue',
                    fill: false
                }]
            }
        });
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($facultyNames); ?>,
                datasets: [{
                    label: 'Leave Requests Per Faculty',
                    data: <?php echo json_encode($facultyCounts); ?>,
                    backgroundColor: ['red', 'green', 'blue', 'orange']
                }]
            }
        });
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($leaveTypeNames); ?>,
                datasets: [{
                    data: <?php echo json_encode($leaveTypeCounts); ?>,
                    backgroundColor: ['purple', 'yellow', 'cyan']
                }]
            }
        });
    </script>

    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
