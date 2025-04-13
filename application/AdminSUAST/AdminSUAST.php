<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
ob_start();

include "../../configuration/config.php";

// Fetch data from database
$takers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tblapplicants"));
$availableSlots = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tbl_exam_schedule"));
$registeredTakers = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tblapplicant_registration"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="../../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
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
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="../applicant/applicant.php">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Applicants</span>
                                <span class="info-box-number"><?php echo $takers; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="#">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Available Slots</span>
                                <span class="info-box-number"><?php echo $availableSlots; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12"><br>
                        <div class="info-box">
                            <a href="#">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-file-alt"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text">Registered Takers</span>
                                <span class="info-box-number"><?php echo $registeredTakers; ?></span>
                            </div>
                        </div>
                    </div>
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
        const labels = ['Takers', 'Available Slots', 'Registered Takers'];
        const data = [<?= $takers ?>, <?= $availableSlots ?>, <?= $registeredTakers ?>];
        const colors = ['blue', 'green', 'red'];

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
                        label: 'Exam Data',
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

  <script src="../../vendors/js/vendor.bundle.base.js"></script> 
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../vendors/chart.js/Chart.min.js"></script>
</body>
</html>
