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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

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



<body style="overflow-x: hidden;
" class="skin-blue">
    <?php
    require_once('includes/header.php');
    require_once('../../includes/head_css.php');
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side" style="padding: 30px; background-color: #f8f9fa;">
            <section class="content">

                <!-- Dashboard Header -->
                <div style="
                    background-color: rgb(255, 226, 7); 
                    color: black; 
                    padding: 15px; 
                    text-align: center; 
                    width: 100vw; 
                    position: relative; 
                    left: 50%; 
                    right: 50%; 
                    margin-left: -50vw;
                    margin-right: -50vw;
                    margin-top: -50px;
                ">
                    <h4 style="margin: 0; font-weight: bold;">Dashboard</h4>
                    <hr class="my-2" style="border-top: 2px solid gray; width: 190px;">

                </div>

                <!-- Welcome Card -->
                <div style="
                    background-color: rgb(255, 226, 7); 
                    color: black; 
                    padding: 15px; 
                    text-align: center; 
                    width: 100vw; 
                    position: relative; 
                    left: 50%; 
                    right: 50%; 
                    margin-top:-30px;
                    margin-left: -50vw;
                    margin-right: -50vw;
                    border-bottom-left-radius: 30px;
                    border-bottom-right-radius: 30px;

                ">
                    <h1 style="font-size: 26px; font-weight: bold;">Hello<br>
                        <span style="color:#004085;"><?php echo $full_name; ?></span>!
                    </h1>
                    <p style="font-size: 14px; margin-top: 10px;">
                        "Your First Step to DORSU Starts Here."<br>
                        Reserve. Secure. Succeed.
                    </p>
                </div>


                <!-- Info Cards -->
                <div class="row text-center"
                    style="gap: 15px; display: flex; flex-direction: column; align-items: center;">

                    <!-- Make a Reservation Card -->
                    <div class="card" onclick="window.location.href='index.php';"
                        style="width: 100%; max-width: 350px; cursor: pointer; border: none; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #003366;"><i class="fas fa-file-alt"></i></div>
                            <h5 class="card-title" style="font-weight: bold; margin-top: 10px;">Make a Reservation</h5>
                            <p class="card-text" style="font-size: 13px;"><strong>Click here</strong> to make a
                                reservation and secure a
                                slot for the State University Admission and Scholarship Test (SUAST) of Davao Oriental
                                State University (DORSU).</p>
                        </div>
                    </div>

                    <!-- Exam Schedule Card -->
                    <div class="card" data-toggle="modal" data-target="#examScheduleModal"
                        style="width: 100%; max-width: 350px; cursor: pointer; border: none; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #003366;"><i class="fas fa-calendar-check"></i></div>
                            <h5 class="card-title" style="font-weight: bold; margin-top: 10px;">Your Exam Schedule</h5>
                            <p class="card-text" style="font-size: 13px;"><strong>Click here</strong> to view your exam
                                schedule and
                                reservation status.</p>
                        </div>
                    </div>

                    <div class="card" data-toggle="modal" data-target="#personalDetails"
                        style="width: 100%; max-width: 350px; cursor: pointer; border: none; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px;">
                        <div class="card-body">
                            <div style="font-size: 28px; color: #003366;"><i class="fas fa-book"></i>
                            </div>
                            <h5 class="card-title" style="font-weight: bold; margin-top: 10px;">Personal Deetails</h5>
                            <p class="card-text" style="font-size: 13px;">Click here to view your exam schedule and
                                assigned location.</p>
                        </div>
                    </div>

                </div>


            </section>
        </aside>

    </div>



    <!-- Personal Details Modal -->
    <div class="modal fade" id="personalDetails" tabindex="-1" role="dialog" aria-labelledby="personalDetailsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,.5);">
                <div class="modal-header"
                    style="background-color: #003366; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title" id="personalDetailsLabel" style="font-size: 16px;">
                        <i class="fas fa-book-open" style="margin-right: 8px;"></i>
                        Personal Information
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white; font-size: 1.5rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-size: 12px;">
                    <?php
                    // Fetch applicant details from tbl_applicants
                    $query = "SELECT * FROM tbl_applicants WHERE applicant_id = '$applicant_id'";
                    $result = mysqli_query($con, $query);

                    if ($row = mysqli_fetch_assoc($result)):
                        ?>
                        <!-- Personal Information -->
                        <h6 style="font-weight: bold; color: #000; font-size: 15px;">Personal Information</h6>
                        <div class="row">
                            <div class="col-md-4"><strong>Last Name:</strong> <?= htmlspecialchars($row['lname']) ?></div>
                            <div class="col-md-4"><strong>First Name:</strong> <?= htmlspecialchars($row['fname']) ?></div>
                            <div class="col-md-4"><strong>Middle Name:</strong> <?= htmlspecialchars($row['mname']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Birthdate:</strong> <?= htmlspecialchars($row['bdate']) ?></div>
                            <div class="col-md-4"><strong>Age:</strong> <?= htmlspecialchars($row['age']) ?></div>
                            <div class="col-md-4"><strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Nationality:</strong> <?= htmlspecialchars($row['nationality']) ?>
                            </div>
                            <div class="col-md-4"><strong>Religion:</strong> <?= htmlspecialchars($row['religion']) ?></div>
                            <div class="col-md-4"><strong>Civil Status:</strong>
                                <?= htmlspecialchars($row['civilstatus']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Ethnicity:</strong> <?= htmlspecialchars($row['ethnicity']) ?>
                            </div>
                            <div class="col-md-4"><strong>Contact Number:</strong> <?= htmlspecialchars($row['contact']) ?>
                            </div>
                            <div class="col-md-4"><strong>Type of Applicant:</strong>
                                <?= htmlspecialchars($row['living_status']) ?></div>
                        </div>

                        <hr>
                        <!-- Address -->
                        <h6 style="font-weight: bold; color: #000; font-size: 15px;">Address</h6>
                        <div class="row">
                            <div class="col-md-3"><strong>Street:</strong> <?= htmlspecialchars($row['purok']) ?></div>
                            <div class="col-md-3"><strong>Barangay:</strong> <?= htmlspecialchars($row['barangay']) ?></div>
                            <div class="col-md-3"><strong>Municipality:</strong>
                                <?= htmlspecialchars($row['municipality']) ?></div>
                            <div class="col-md-3"><strong>Province:</strong> <?= htmlspecialchars($row['province']) ?></div>
                        </div>

                        <hr>
                        <!-- Parent's Details -->
                        <h6 style="font-weight: bold; color: #000; font-size: 15px;">Parent's Details</h6>
                        <div class="row">
                            <div class="col-md-6"><strong>Mother's Name:</strong> <?= htmlspecialchars($row['n_mother']) ?>
                            </div>
                            <div class="col-md-6"><strong>Mother's Contact:</strong>
                                <?= htmlspecialchars($row['c_mother']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Mother's Occupation:</strong>
                                <?= htmlspecialchars($row['m_occupation']) ?></div>
                            <div class="col-md-6"><strong>Mother's Address:</strong>
                                <?= htmlspecialchars($row['m_address']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Father's Name:</strong> <?= htmlspecialchars($row['n_father']) ?>
                            </div>
                            <div class="col-md-6"><strong>Father's Contact:</strong>
                                <?= htmlspecialchars($row['c_father']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Father's Occupation:</strong>
                                <?= htmlspecialchars($row['f_occupation']) ?></div>
                            <div class="col-md-6"><strong>Father's Address:</strong>
                                <?= htmlspecialchars($row['f_address']) ?></div>
                        </div>

                        <hr>
                        <!-- College Preferences -->
                        <h6 style="font-weight: bold; color: #000; font-size: 15px;">College Preferences</h6>
                        <div class="row">
                            <div class="col-md-3"><strong>1st Option:</strong> <?= htmlspecialchars($row['first_option']) ?>
                            </div>
                            <div class="col-md-3"><strong>2nd Option:</strong>
                                <?= htmlspecialchars($row['second_option']) ?></div>
                            <div class="col-md-3"><strong>3rd Option:</strong> <?= htmlspecialchars($row['third_option']) ?>
                            </div>
                            <div class="col-md-3"><strong>Preferred Campus:</strong> <?= htmlspecialchars($row['campus']) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><strong>Date Applied:</strong>
                                <?= htmlspecialchars($row['date_applied']) ?></div>
                        </div>

                    <?php else: ?>
                        <p class="text-center text-muted" style="font-size: 12px;">No personal details available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        style="background-color: rgb(191, 10, 10); color: white; font-size: 12px;">
                        Close
                    </button>

                    <a href="edit_applicant.php" class="btn btn-primary"
                        style="font-size: 12px; background-color: #003366; color: white;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>


            </div>
        </div>
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
            <div class="modal-content" style="border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,.5);">
                <div class="modal-header"
                    style="background-color: #003366; color: white; border-bottom: none; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: #003366; opacity: 1; font-size: 2rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="examScheduleModalLabel">
                        <i class="fas fa-calendar-check" aria-hidden="true" style="margin-right: 8px;"></i>
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
                                    <p><strong>Venue:</strong> <?= htmlspecialchars($row['venue']) ?></p>
                                    <p><strong>Room:</strong> <?= htmlspecialchars($row['room']) ?></p>
                                    <p><strong>Date:</strong>
                                        <?= $row['exam_date'] ? date('F j, Y', strtotime($row['exam_date'])) : '<span class="text-muted" style="font-style: italic;">Not Selected</span>' ?>
                                    </p>
                                    <p><strong>Time:</strong> <?= htmlspecialchars($row['exam_time']) ?></p>
                                    <p>
                                        <strong>Status:</strong>
                                        <span
                                            style="color: 
                            <?= strtolower($row['status']) === 'rejected' ? 'red' : (strtolower($row['status']) === 'approved' ? 'green' : 'black') ?>;">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </p>
                                    <p><strong>Reason (if rejected):</strong>
                                        <?= !empty($row['reason']) ? htmlspecialchars($row['reason']) : '<span class="text-muted" style="font-style: italic;">N/A</span>' ?>
                                    </p>

                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">No exam schedules available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn" data-dismiss="modal"
                        style="background-color: #003366; color: white; border: none;">
                        Close
                    </button>
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


    <!-- Terms & Privacy Modal -->
    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 10px;">
                <div class="modal-header"
                    style="background-color: #b30000; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: white;"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="termsLabel">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <strong> Terms and Conditions & Data Privacy Notice</strong>
                    </h4>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <p>By proceeding to reserve a slot for the SUAST Entrance Exam, you acknowledge and agree to the
                        following terms:</p>
                    <ul>
                        <li>Your personal information, including but not limited to your name, address, contact details,
                            family background, and college preferences, will be collected solely for the purpose of
                            processing your reservation and participation in the SUAST Entrance Examination. All
                            information provided will be treated with confidentiality and used only by authorized
                            personnel involved in exam scheduling, coordination, and admissions processing.</li>
                        <li>We are committed to protecting your privacy and complying with applicable data protection
                            laws. Your data will not be shared with third parties without your explicit consent, except
                            where required by law. You have the right to access, update, or request the deletion of your
                            personal data at any time by contacting us directly.</li>
                    </ul>
                    <p>By submitting your information, you confirm that all the details you provide are accurate and
                        truthful to the best of your knowledge. You also consent to the processing of your personal data
                        in accordance with this notice.</p>
                    <p>If you do not agree with these terms, please do not proceed with the application.</p>

                    <div class="checkbox">
                        <label style="font-weight: bold; color: #b30000;">
                            <input type="checkbox" id="termsCheckbox">
                            I have read and understood the Terms and Conditions and Data Privacy Notice, and I
                            voluntarily agree to the collection and processing of my personal information for the
                            purpose of reserving a slot for the SUAST Entrance Exam.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                    <button type="button" class="btn btn-primary" id="proceedBtn" disabled>PROCEED</button>
                </div>
            </div>
        </div>
    </div>






    <!-- jQuery, Bootstrap JS, DataTables JS -->
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- jQuery (required by Bootstrap 3) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Bootstrap 3 JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            const termsAccepted = localStorage.getItem('termsAccepted');

            if (!termsAccepted) {
                $('#termsModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }

            $('#termsCheckbox').on('change', function () {
                $('#proceedBtn').prop('disabled', !this.checked);
            });

            $('#proceedBtn').on('click', function () {
                localStorage.setItem('termsAccepted', 'true');
                $('#termsModal').modal('hide');
            });
        });
    </script>




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
