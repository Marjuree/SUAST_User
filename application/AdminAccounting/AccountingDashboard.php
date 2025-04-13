<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../php/error.php?welcome=Please login to access this page");
        exit();
    }
    ob_start();
    include "../../configuration/config.php";

    // Fetch Clearance Data from the correct table
    $clearanceApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Approved'"));
    $clearancePending = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Pending'"));
    $clearanceRejected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests WHERE status = 'Rejected'"));
    $clearanceCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_clearance_requests"));

    // Fetch Permit Data
    $permitApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Approved'"));
    $permitPending = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Pending'"));
    $permitRejected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Rejected'"));
    $permitCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accounting | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <script src="../../assets/chart.js"></script>
    <style>
        canvas { max-height: 250px !important; }
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
                    <?php 
                    $info_boxes = [
                        ["Clearance", "fa-file", $clearanceCount, "#"],
                        ["Permit", "fa-check-circle", $permitCount, "#"]
                    ];
                    foreach ($info_boxes as $box) { ?>
                        <div class="col-md-3 col-sm-6 col-xs-12"><br>
                            <div class="info-box">
                                <a href="<?= $box[3] ?>">
                                    <span class="info-box-icon bg-aqua"><i class="fa <?= $box[1] ?>"></i></span>
                                </a>
                                <div class="info-box-content">
                                    <span class="info-box-text"><?= $box[0] ?></span>
                                    <span class="info-box-number"><?= $box[2] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <?php 
                    $charts = [
                        ["Status Count", "barChart"],
                        ["Request Overtime", "lineChart"],
                        ["Overall Request", "pieChart"]
                    ];
                    foreach ($charts as $chart) { ?>
                        <div class="col-md-4 col-sm-12"><br>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><?= $chart[0] ?></h4>
                                    <canvas id="<?= $chart[1] ?>"></canvas>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </aside>
    </div>
    
    <script>
        // Labels for the bar chart
        const barChartLabels = [
            'Clearance Pending', 'Clearance Approved', 'Clearance Rejected',
            'Permit Pending', 'Permit Approved', 'Permit Rejected'
        ];

        // Data for the bar chart
        const barChartData = [
            <?= $clearancePending ?>, <?= $clearanceApproved ?>, <?= $clearanceRejected ?>,
            <?= $permitPending ?>, <?= $permitApproved ?>, <?= $permitRejected ?>
        ];

        // Colors for the bars
        const colors = ['orange', 'green', 'red', 'yellow', 'blue', 'purple'];

        // Generate the bar chart
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: barChartLabels,
                datasets: [{
                    label: 'Status Count',
                    data: barChartData,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Labels for the general overview charts
        const generalLabels = ['Clearance', 'Permit'];
        const generalData = [<?= $clearanceCount ?>, <?= $permitCount ?>];
        const generalColors = ['blue', 'green'];

        // Generate the line chart
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: generalLabels,
                datasets: [{
                    label: 'Requests Over Time',
                    data: generalData,
                    backgroundColor: generalColors,
                    borderColor: generalColors,
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Generate the pie chart
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: generalLabels,
                datasets: [{
                    label: 'Overall Requests',
                    data: generalData,
                    backgroundColor: generalColors
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>

    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
