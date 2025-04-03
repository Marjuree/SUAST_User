whith status  #555

<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../php/error.php?welcome=Please login to access this page");
        exit();
    }
    ob_start();
    include "../../configuration/config.php";
    
    // Fetch data from database
    $clearanceApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tblclearance WHERE status = 'Approved'"));
    $clearanceDisapproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tblclearance WHERE status = 'Disapproved'"));
    $clearanceRejected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tblclearance WHERE status = 'Rejected'"));
    $permitApproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Approved'"));
    $permitDisapproved = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Disapproved'"));
    $permitRejected = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Rejected'"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accounting | Dash</title>
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
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="col-md-6 col-sm-12"><br>
                        <div class="card">
                            <div class="card-body">
                                  <h4 class="card-title">Clearance & Permit Status</h4>
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    
    <script>
        const labels = ['Clearance Approved', 'Clearance Disapproved', 'Clearance Rejected', 'Permit Approved', 'Permit Disapproved', 'Permit Rejected'];
        const data = [
            <?= $clearanceApproved ?>, 
            <?= $clearanceDisapproved ?>, 
            <?= $clearanceRejected ?>, 
            <?= $permitApproved ?>, 
            <?= $permitDisapproved ?>, 
            <?= $permitRejected ?>
        ];
        const colors = ['green', 'orange', 'red', 'blue', 'purple', 'brown'];

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Status Count',
                    data: data,
                    backgroundColor: colors,
                    borderColor: colors.map(c => c.replace('0.6', '1')),
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>

<?php require_once "../../includes/footer.php"; ?>
</body>
</html>






with all  #555

<?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../php/error.php?welcome=Please login to access this page");
        exit();
    }
    ob_start();
    include "../../configuration/config.php";
    
    // Fetch data from database
    $clearanceCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tblclearance"));
    $permitCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_permits WHERE status = 'Approved'"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Accounting | Dash</title>
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
                        ["Bar Chart", "barChart"],
                        ["Line Chart", "lineChart"],
                        ["Pie Chart", "pieChart"]
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
        const labels = ['Clearance', 'Permit'];
        const data = [<?= $clearanceCount ?>, <?= $permitCount ?>];
        const colors = ['blue', 'green'];

        const chartConfigs = [
            { id: 'barChart', type: 'bar' },
            { id: 'lineChart', type: 'line' },
            { id: 'pieChart', type: 'pie' }
        ];

        chartConfigs.forEach(cfg => {
            new Chart(document.getElementById(cfg.id), {
                type: cfg.type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Data Overview',
                        data: data,
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>

<?php require_once "../../includes/footer.php"; ?>
</body>
</html>
