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

$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$middle_name = isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";
$full_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name);
$full_name = empty($full_name) ? "Applicant" : $full_name;

// Fetch exam schedule data
$query = "SELECT * FROM tbl_exam_schedule";
$result = mysqli_query($con, $query);

// Fetch reservations for logged-in applicant
$query_reservations = "SELECT * FROM tbl_reservation WHERE applicant_id = '$applicant_id'";
$result_reservations = mysqli_query($con, $query_reservations);

// Check if user has reservation
$user_has_reservation = mysqli_num_rows($result_reservations) > 0;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Applicant | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../css/dashboard.css" rel="stylesheet" />

    <!-- FontAwesome 5 for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

    <style>
        /* Styles for info cards */
        .info-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        /* Make all cards uniform size */
        .info-card {
            background: #ffff;
            /* Bootstrap info color */
            color: black;
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            width: 300px;
            cursor: default;
            user-select: none;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: background 0.3s ease;
            position: relative;
        }

        .info-card .icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .info-card h3 {
            margin-bottom: 10px;
            font-weight: 600;
        }

        .info-card p {
            flex-grow: 1;
            font-size: 0.9rem;
            line-height: 1.3;
        }

        /* Hover effect for clickable card */
        .info-card.clickable {
            cursor: pointer;
            user-select: none;
        }

        .info-card.clickable:hover {
            background: rgb(199, 201, 201);
            box-shadow: 0 6px 12px rgba(19, 132, 150, 0.6);
            text-decoration: none;
        }

        /* Remove button inside card when card is clickable */
        .info-card.clickable button {
            display: none;
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

        <aside class="right-side" style="padding: 30px;">
            <section class="content">
                <div class="welcome-card">
                    <h1>Welcome back, <?php echo $full_name; ?>!</h1>
                    <p>We're excited to have you here. You can review your application status, check your exam schedule,
                        or make reservations.</p>
                    <!-- Changed this link to a button that triggers the modal -->
                    <button type="button" class="btn btn-info" data-toggle="modal"
                        data-target="#applicationStatusModal">
                        View Application Status
                    </button>
                </div>

                <div class="info-cards">
                    <!-- Exam Schedule card as a clickable card -->
                    <div class="info-card clickable" data-toggle="modal" data-target="#examScheduleModal" tabindex="0"
                        role="button" aria-pressed="false" aria-label="Open Exam Schedule modal">
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                        <h3>Exam Schedule</h3>
                        <p>Check your upcoming examination dates and venues here.</p>
                    </div>

                    <!-- Application Documents card as clickable -->
                    <div class="info-card clickable" tabindex="0" role="button" aria-pressed="false"
                        aria-label="Make a reservation, click to go to home page" style="cursor:pointer;"
                        onclick="window.location.href='index.php';"
                        onkeypress="if(event.key === 'Enter' || event.key === ' ') { window.location.href='index.php'; }">
                        <div class="icon"><i class="fas fa-file-alt"></i></div>
                        <h3>Make a Reservation</h3>
                        <p>Click here to make a reservation.</p>
                    </div>




                </div>
            </section>
        </aside>
    </div>

    <!-- Application Status Modal -->
    <div class="modal fade" id="applicationStatusModal" tabindex="-1" role="dialog"
        aria-labelledby="applicationStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 6px; box-shadow: 0 5px 15px rgba(0,0,0,.5);">
                <div class="modal-header"
                    style="background-color: #337ab7; color: white; border-bottom: none; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1; font-size: 2rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="applicationStatusModalLabel">
                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"
                            style="margin-right: 8px;"></span>
                        Application Status
                    </h4>
                </div>
                <div class="modal-body">
                    <?php if (mysqli_num_rows($result_reservations) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result_reservations)):
                            $status = $row['status'] ?? 'pending';
                            $status_class = ($status === 'pending') ? 'label label-warning' :
                                (($status === 'approved') ? 'label label-success' :
                                    (($status === 'rejected') ? 'label label-danger' : 'label label-default'));
                            ?>
                            <div class="panel panel-default"
                                style="box-shadow: 0 1px 3px rgba(0,0,0,.1); border-radius: 4px; margin-bottom: 15px;">
                                <div class="panel-body">
                                    <h5>Status: <span class="<?= $status_class ?>"
                                            style="font-size: 1rem; padding: 5px 10px;"><?= ucfirst($status) ?></span></h5>
                                    <p><strong>Reason:</strong></p>
                                    <?php if ($status === 'rejected' && !empty($row['reason'])): ?>
                                        <textarea class="form-control" rows="4"
                                            disabled><?= htmlspecialchars($row['reason']) ?></textarea>
                                    <?php else: ?>
                                        <p class="text-muted" style="font-style: italic;">-</p>
                                    <?php endif; ?>
                                    <div class="text-right" style="margin-top: 10px;">
                                        <?php if ($status === 'rejected'): ?>
                                            <form method="POST" action="request_update.php" id="updateForm_<?= $row['id'] ?>"
                                                style="display:inline;">
                                                <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    onclick="showSweetAlert(<?= $row['id'] ?>)">Request Update</button>
                                            </form>
                                        <?php else: ?>
                                            <em>-</em>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">No reservation status found.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Schedule Modal -->
    <div class="modal fade" id="examScheduleModal" tabindex="-1" role="dialog" aria-labelledby="examScheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 6px; box-shadow: 0 5px 15px rgba(0,0,0,.5);">
                <div class="modal-header"
                    style="background-color: #337ab7; color: white; border-bottom: none; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1; font-size: 2rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="examScheduleModalLabel">
                        <span class="glyphicon glyphicon-calendar" aria-hidden="true" style="margin-right: 8px;"></span>
                        Exam Schedule
                    </h4>
                </div>
                <div class="modal-body">
                    <?php if (mysqli_num_rows($result_reservations) > 0): ?>
                        <?php mysqli_data_seek($result_reservations, 0); ?>
                        <?php while ($row = mysqli_fetch_assoc($result_reservations)): ?>
                            <div class="panel panel-default"
                                style="box-shadow: 0 1px 3px rgba(0,0,0,.1); border-radius: 4px; margin-bottom: 15px;">
                                <div class="panel-body">
                                    <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
                                    <p><strong>Exam Date:</strong>
                                        <?= $row['exam_date'] ? date('F j, Y', strtotime($row['exam_date'])) : '<span class="text-muted" style="font-style: italic;">Not Selected</span>' ?>
                                    </p>
                                    <p><strong>Exam Time:</strong> <?= htmlspecialchars($row['exam_time']) ?></p>
                                    <p><strong>Room:</strong> <?= htmlspecialchars($row['room']) ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">No exam schedules available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Documents Modal -->
    <div class="modal fade" id="applicationDocumentsModal" tabindex="-1" role="dialog"
        aria-labelledby="applicationDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 6px; box-shadow: 0 5px 15px rgba(0,0,0,.5);">
                <div class="modal-header"
                    style="background-color: #337ab7; color: white; border-bottom: none; border-top-left-radius: 6px; border-top-right-radius: 6px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; opacity: 1; font-size: 2rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="applicationDocumentsModalLabel">
                        <span class="fas fa-file-alt" aria-hidden="true" style="margin-right: 8px;"></span>
                        Application Documents
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Your documents info goes here -->
                    <p>Here you can list or show the applicant’s documents...</p>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery, Bootstrap JS, DataTables JS -->
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        function showSweetAlert(reservationId) {
            if (confirm('Are you sure you want to request an update?')) {
                document.getElementById('updateForm_' + reservationId).submit();
            }
        }

        // Accessibility: Enable keyboard triggering of modal on Enter or Space
        document.querySelectorAll('.info-card.clickable').forEach(card => {
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(this).modal('toggle');  // Actually toggles Bootstrap modal
                    // or simply trigger click event:
                    this.click();
                }
            });
        });
    </script>

</body>

</html>
