<?php
require_once "../../configuration/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_regenerate_id(true);

// Check if the user is logged in and is an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}


$employee_id = $_SESSION['employee_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";
$full_name = trim($first_name . ' ' . $last_name); // Combine first and last name

if (empty($full_name)) {
    $full_name = "Employee"; // Default fallback
}


// Get faculty from leave requests
$faculty_leave = '';
$res = $con->query("SELECT faculty FROM tbl_leave_requests WHERE employee_id = '$employee_id' ORDER BY id DESC LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $faculty_leave = $row['faculty'];
}

// Get faculty from certification requests
$faculty_cert = '';
$res = $con->query("SELECT faculty FROM tbl_certification_requests WHERE employee_id = '$employee_id' ORDER BY id DESC LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $faculty_cert = $row['faculty'];
}

// Get faculty from service record requests
$faculty_service = '';
$res = $con->query("SELECT faculty FROM tbl_service_requests WHERE employee_id = '$employee_id' ORDER BY id DESC LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $faculty_service = $row['faculty'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Employees | Dash</title>
    <link href="../../css/button.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="../../css/dashboard.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">




</head>
<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .d-grid.gap-2 a.btn {
        border-radius: 30px;
    }

    /* Normal hover */
    #submitMdoal .btn {
        transition: background-color 0.3s ease, transform 0.1s ease;
        border-radius: 30px;
    }

    #submitMdoal .btn:hover {
        background-color: #00509e;
        color: #fff;
    }

    /* Click/active effect */
    #submitMdoal .btn:active {
        background-color: #00509e;
        /* even darker blue */
        transform: scale(0.98);
        /* slight shrink */
        color: #fff;
    }

    #submitMdoal {
        margin-top: 40px
    }

    .form-group {
        font-family: 'Poppins', sans-serif;
    }

    .modal-body p {
        font-family: 'Poppins', sans-serif;
    }

    .request-btn {
        background-color: #003366;
        color: #fff;
        border-radius: 30px;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }

    .request-btn:hover {
        background-color: #00509e;
        /* slightly lighter blue */
        color: #fff;
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        text-decoration: none;
    }
</style>

<body class="skin-blue">
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
                    background-color: rgb(0, 33, 66); 
                    color: white; 
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
                    <h4 style="margin: 0; font-weight: bold; font-family: 'Poppins', sans-serif;">Dashboard</h4>
                    <hr class="my-2" style="border-top: 2px solid white; width: 190px;">

                </div>

                <!-- Welcome Card -->
                <div style="
                    background-color: rgb(0, 33, 66); 
                    color: white; 
                    padding: 10px; 
                    text-align: center; 
                    width: 100vw; 
                    position: relative; 
                    left: 50%; 
                    right: 50%; 
                    margin-left: -50vw;
                    margin-right: -50vw;
                    border-bottom-left-radius: 30px;
                    border-bottom-right-radius: 30px;

                ">
                    <h1
                        style="font-size: 26px; font-weight: bold; margin-top: -20px; font-family: 'Poppins', sans-serif;">
                        Hello,<br>
                        <span style="color:rgb(255, 145, 0);"><?php echo $first_name . ' ' . $last_name . '!'; ?></span>
                    </h1>
                    <p style="font-size: 14px; margin-top: 10px; font-family: 'Poppins', sans-serif; ">
                        "Where Every Request Matters."<br>
                        Connect. Process. Progress.
                    </p>
                </div>


                <!-- Info Cards -->
                <div class="row text-center"
                    style="gap: 15px; display: flex; flex-direction: column; align-items: center;">

                    <!-- Make a Reservation Card -->
                    <div class="card" data-toggle="modal" data-target="#submitMdoal"
                        style="width: 100%; max-width: 350px; cursor: pointer; border: none; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px;">
                        <div class="card-body" style="background-color:#FFC4D4">
                            <div style="font-size: 28px; color: #003366;"><i class="fas fa-file-alt"></i></div>
                            <h5 class="card-title" style="font-weight: bold; margin-top: 10px; color: #003366;">Services
                            </h5>
                            <p class="card-text" style="font-size: 13px; font-family: 'Poppins', sans-serif;">
                                <strong>Click here</strong>
                                to submit a request for leave, certification, or services record at Davao Oriental State
                                University.
                            </p>
                        </div>
                    </div>

                    <!-- Exam Schedule Card -->
                    <!-- Card that triggers the modal -->
                    <div class="card" data-toggle="modal" data-target="#requestModal"
                        style="width: 100%; max-width: 350px; cursor: pointer; border: none; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px;">
                        <div class="card-body" style="background-color: #B1D4E0;">
                            <div style="font-size: 28px; color: #003366;"><i class="fas fa-calendar-check"></i></div>
                            <h5 class="card-title"
                                style="font-weight: bold; margin-top: 10px; color: #003366; font-family: 'Poppins', sans-serif;">
                                Track
                                Your Process</h5>
                            <p class="card-text" style="font-size: 13px; font-family: 'Poppins', sans-serif;">
                                <strong>Click here</strong> to view your
                                request status and track your progress.
                            </p>
                        </div>
                    </div>
                </div>


            </section>
        </aside>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; position: relative;">
                <div class="modal-body text-center">
                    <!-- Close icon at the top right -->
                    <span data-dismiss="modal"
                        style="position: absolute; top: 10px; right: 15px; font-size: 1.5rem; color: #003366; cursor: pointer;">&times;</span>

                    <!-- Logo at the top -->
                    <img src="../../img/uni.png" alt="Logo" style="max-width: 120px; margin-bottom: 10px;">
                    <!-- Text below the logo -->
                    <h5 style="margin-bottom: 20px; font-family: 'Poppins', sans-serif;"><strong>Choose a
                            Service</strong></h5>

                    <!-- Request Options -->
                    <p style=" font-family: 'Poppins', sans-serif;">Please choose one of the following request types:
                    </p>
                    <div class="d-grid gap-2">
                        <a href="leave_requests.php" class="btn btn-block my-2"
                            style="background-color: #003366; color: #fff; border-radius: 30px;  font-family: 'Poppins', sans-serif;">
                            LEAVE REQUEST
                        </a>
                        <a href="certification_requests.php" class="btn btn-block my-2"
                            style="background-color: #003366; color: #fff; border-radius: 30px;  font-family: 'Poppins', sans-serif;">
                            CERTIFICATION REQUEST
                        </a>
                        <a href="service_requests.php" class="btn btn-block my-2"
                            style="background-color: #003366; color: #fff; border-radius: 30px;  font-family: 'Poppins', sans-serif;">
                            SERVICE RECORD REQUEST
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="submitMdoal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; position: relative;">
                <div class="modal-body text-center">
                    <!-- Close icon at the top right -->
                    <span data-dismiss="modal"
                        style="position: absolute; top: 10px; right: 15px; font-size: 1.5rem; color: #003366; cursor: pointer;">&times;</span>

                    <!-- Logo at the top -->
                    <img src="../../img/uni.png" alt="Logo" style="max-width: 120px; margin-bottom: 10px;">

                    <!-- Text below the logo -->
                    <h5 style="margin-bottom: 20px;  font-family: 'Poppins', sans-serif;"><strong>Choose a
                            Service</strong></h5>
                    <p>Please choose one of the following request types:</p>

                    <!-- Request Option Buttons -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-block my-2"
                            style="background-color: #003366; color: #fff;  font-family: 'Poppins', sans-serif;"
                            data-toggle="modal" data-target="#requestLeaveApplication" data-dismiss="modal">
                            SUBMIT LEAVE REQUEST
                        </button>

                        <button class="btn btn-block my-2"
                            style="background-color: #003366; color: #fff;  font-family: 'Poppins', sans-serif;"
                            data-toggle="modal" data-target="#requestCertification" data-dismiss="modal">
                            SUBMIT CERTIFICATION REQUEST
                        </button>

                        <button class="btn btn-block my-2"
                            style="background-color: #003366; color: #fff;  font-family: 'Poppins', sans-serif;"
                            data-toggle="modal" data-target="#requestServiceRecord" data-dismiss="modal">
                            SUBMIT SERVICE RECORD REQUEST
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modal buttons hover */
        #requestModal .btn,
        #submitMdoal .btn {
            transition: background-color 0.3s ease, box-shadow 0.2s ease;
            border-radius: 30px !important;
            /* enforce rounded corners */
        }

        #requestModal .btn:hover,
        #requestModal .btn:focus,
        #submitMdoal .btn:hover,
        #submitMdoal .btn:focus {
            background-color: #002244 !important;
            /* darker blue on hover */
            box-shadow: 0 4px 10px rgba(0, 51, 102, 0.6);
            cursor: pointer;
        }
    </style>


    <!-- Certification Request -->
    <div class="modal fade" id="requestCertification" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style=" font-family: 'Poppins', sans-serif;">Request Certification</h4>
                </div>

                <div class="modal-body">
                    <!-- Added id="certificationForm" -->
                    <form id="certificationForm" enctype="multipart/form-data" method="POST">
                        <input type="hidden" name="request_type" value="Certification">

                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($full_name) ?>"
                                readonly>
                            <input type="hidden" name="name" value="<?= htmlspecialchars($full_name) ?>">
                        </div>

                        <div class="form-group">
                            <label for="faculty">Position:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($faculty_cert) ?>"
                                readonly>
                            <input type="hidden" name="faculty" value="<?= htmlspecialchars($faculty_cert) ?>">
                        </div>


                        <div class="form-group">
                            <label>Date of Request:</label>
                            <input type="date" name="date_request" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Reason for Request:</label>
                            <textarea name="reason" required class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="certification_file">Upload Certification Document (Image
                                or File):</label>
                            <input type="file" name="certification_file" id="certification_file"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip" class="form-control">
                            <p class="help-block">Accepted formats: JPG, PNG, PDF, DOC, DOCX,
                                ZIP</p>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" style="background-color: #003366;">Submit
                                Request</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"
                                style="background-color: red; color:white;">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>




    <!-- Leave Processing Request Modal -->
    <div class="modal fade" id="requestLeaveApplication" tabindex="-1" role="dialog"
        aria-labelledby="requestLeaveApplicationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable"><!-- Added scrollable class here -->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="requestLeaveApplicationLabel">Request Leave Processing</h4>
                </div>
                <div class="modal-body">
                    <form action="process_leave.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="request_type" value="Leave Processing">

                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($full_name) ?>"
                                readonly>
                            <input type="hidden" name="name" value="<?= htmlspecialchars($full_name) ?>">
                        </div>

                        <div class="form-group">
                            <label for="faculty">Position:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($faculty_leave) ?>"
                                readonly>
                            <input type="hidden" name="faculty" value="<?= htmlspecialchars($faculty_leave) ?>">
                        </div>

                        <div class="form-group">
                            <label for="leave_date">Leave Date:</label>
                            <input type="date" name="leave_date" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="leave_end_date">End of Leave:</label>
                            <input type="date" name="leave_end_date" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="leave_type">Leave Type:</label>
                            <select name="leave_type" class="form-control" required>
                                <option value="" disabled selected>Select Leave Type</option>
                                <option value="Vacation Leave">Vacation Leave</option>
                                <option value="Mandatory/Forced Leave">Mandatory/Forced Leave</option>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Paternity Leave">Paternity Leave</option>
                                <option value="Special Privilege Leave">Special Privilege Leave</option>
                                <option value="Solo Parent Leave">Solo Parent Leave</option>
                                <option value="Study Leave">Study Leave</option>
                                <option value="10-Day VAWC Leave">10-Day VAWC Leave</option>
                                <option value="Rehabilitation Privilege">Rehabilitation Privilege</option>
                                <option value="Special Leave Benefits for Women">Special Leave Benefits for Women
                                </option>
                                <option value="Special Emergency (Calamity) Leave">Special Emergency (Calamity) Leave
                                </option>
                                <option value="Adoption Leave">Adoption Leave</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="leave_form">
                                Upload CSC Application for Leave Form (CS Form No. 6, Revised 2020):
                            </label>
                            <input type="file" name="leave_form" id="leave_form"
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip" required class="form-control">
                            <p class="help-block">
                                Accepted formats: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, ZIP
                            </p>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" style="background-color: #003366;">Submit
                        Request</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        style="background-color: red; color: white;">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Service Record Request -->
    <div class="modal fade" id="requestServiceRecord" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Request Service Record</h4>
                </div>
                <form action="process_request.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="request_type" value="Service Record">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($full_name) ?>"
                                readonly>
                            <input type="hidden" name="name" value="<?= htmlspecialchars($full_name) ?>">
                        </div>

                        <div class="form-group">
                            <label for="faculty">Position:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($faculty_service) ?>"
                                readonly>
                            <input type="hidden" name="faculty" value="<?= htmlspecialchars($faculty_service) ?>">
                        </div>

                        <div class="form-group">
                            <label for="date_request">Date of Request:</label>
                            <input type="date" name="date_request" id="date_request" required class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="reason">Reason for Request:</label>
                            <textarea name="reason" id="reason" required class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="attachment">Upload Supporting Document (Image or
                                File):</label>
                            <input type="file" name="attachment" id="attachment"
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="form-control">
                            <p class="help-block">Accepted formats: JPG, PNG, PDF, DOC, DOCX</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="background-color: #003366;">Submit
                            Request</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"
                            style="background-color: red; color: #fff;">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>


    <script>
        function handleRequest(type) {
            alert("You selected: " + type);
            // Optionally, redirect to a specific page:
            // window.location.href = 'your-page.php?request=' + encodeURIComponent(type);
            $('#requestModal').modal('hide');
        }
    </script>
</body>

</html>