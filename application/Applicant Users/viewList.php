<?php
session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php"; // Ensure database connection

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}


$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Applicant | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <!-- Bootstrap 3 -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
    .horizontal-scroll {
        overflow-x: auto;
        white-space: nowrap;
    }

    .scroll-box {
        display: inline-block;
        min-width: 600px;
        vertical-align: top;
        margin-right: 20px;
        background: #fff;
        padding: 20px;
        border: 1px solid #000;
        border-radius: 10px;
    }
    </style>
</head>

<body class="skin-blue">

    <?php 
require_once('includes/header.php');
require_once('../../includes/head_css.php'); 
require_once('../../includes/sidebar.php');
?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>

            <div class="modal-dialog" style="width: 90%;">
                <div class="modal-content" style="padding: 20px; border-radius: 15px; font-family: Arial, sans-serif;">

                    <!-- Header Layout -->
                    <div class="container-fluid" style="padding-bottom: 15px; border-bottom: 1px solid #ccc;">
                        <div class="row">
                            <div class="col-xs-9">
                                <h6>Republic of the Philippines</h6>
                                <div style="height: 5px; background-color: #003399; width: 325px; margin-bottom: 5px;">
                                </div>
                                <h3 style="color: #003399; font-weight: bold;">DAVAO ORIENTAL <br> STATE UNIVERSITY</h3>
                                <p style="font-style: italic; font-size: 14px;">"A university of excellence, innovation,
                                    and inclusion"</p>
                                <div style="height: 5px; background-color: #003399; width: 325px;"></div>
                            </div>
                            <div class="col-xs-3 text-right">
                                <img src="../../php/image/logo1.png" alt="University Seal" style="height: 120px;">
                            </div>
                        </div>

                        <div class="text-center" style="margin-top: 10px;">
                            <h6 style="font-weight: bold; text-decoration: underline;">OFFICE OF STUDENT COUNSELING AND
                                DEVELOPMENT</h6>
                            <p style="font-weight: bold; text-decoration: underline; font-size: 14px;">State University
                                Aptitude and Scholarship Test</p>
                        </div>
                    </div>

                    <!-- Reminders -->
                    <div class="panel panel-danger" style="margin-top: 20px;">
                        <div class="panel-heading text-center">
                            <strong>REMINDERS</strong>
                        </div>
                        <div class="panel-body text-center">
                            <ul style="list-style: none; padding: 0;">
                                <li><strong>Bring your OWN pencil, ball pen, and eraser. Strictly no borrowing.</strong>
                                </li>
                                <li><strong>Prepare the EXACT Testing Fee Payment of Php. 150.00</strong></li>
                                <li><strong>Arrive 30 minutes before your examination schedule.</strong></li>
                                <li><strong>Wear appropriate attire.</strong></li>
                                <li><strong>You may bring snacks but observe the University’s no single-use plastic
                                        policy.</strong></li>
                                <li><strong>Wear face-mask and observe safety protocols.</strong></li>
                                <li><strong>Wear white polo shirt.</strong></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Room and Schedule Info -->
                    <div class="modal-body">
                        <?php
    $query = "SELECT * FROM tbl_reservation ORDER BY room, name ASC";
    $result = $con->query($query);

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $room = $row['room'];
        if (!isset($rooms[$room])) {
            $rooms[$room] = [];
        }
        $rooms[$room][] = $row;
    }
    ?>

                        <div class="horizontal-scroll" style="padding: 15px 0;">
                            <?php foreach ($rooms as $room => $students): ?>
                            <div class="scroll-box">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <h5 style="font-weight: bold;">
                                            <?= htmlspecialchars($students[0]['venue']) ?>
                                        </h5>
                                        <h5>Date and Venue</h5>

                                                                <?php
                                            // Conditionally apply scroll style
                                            $scrollStyle = count($students) >= 5 ? 'style="max-height: 300px; overflow-y: auto;"' : '';
                                            ?>

                                        <div <?= $scrollStyle ?>>
                                            <table class="table table-bordered table-striped mb-0">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th style="width: 50px;">#</th>
                                                        <th>NAME</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($students as $index => $student): ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td><?= htmlspecialchars($student['name']) ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="text-center"
                                            style="border: 1px solid #000; padding: 10px; margin-bottom: 15px;">
                                            <h5><strong><?= htmlspecialchars($room) ?></strong></h5>
                                        </div>
                                        <div class="text-center" style="border: 1px solid #000; padding: 10px;">
                                            <h5><strong><?= date('F d, Y', strtotime($students[0]['exam_date'])) ?></strong>
                                            </h5>
                                            <h5>Time: <?= date('g:i a', strtotime($students[0]['exam_time'])) ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>


                </div>
            </div>
        </aside>
    </div>

    <?php require_once "../../includes/footer.php"; ?>
    <?php require_once "view_list_modal.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

</html>