<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerate session ID for security AFTER checking session variables
session_regenerate_id(true);

// Ensure database connection
require_once "../../configuration/config.php";

// Debugging: Ensure session values are properly set
if (!isset($_SESSION['student_id'])) {
    die("Session not set. Please log in again.");
}

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../../php/error.php?welcome=Please login as a Student");
    exit();
}

// Store session values safely
$student_id = $_SESSION['student_id'];
$first_name = isset($_SESSION['student_name']) ? htmlspecialchars($_SESSION['student_name']) : "Student";

// Fetch the specific student data based on the logged-in user's ID
$query = "SELECT * FROM tbl_student_users WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Student | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/student.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 3 CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

</head>
<style>
    body {
        font-family: 'Poppins', sans-serif !important;

    }
</style>

<body class="skin-blue">
    <?php
    require_once "../../configuration/config.php";
    require_once('includes/header.php');
    require_once('../../includes/head_css.php');
    ?>

    <div class="container" style="margin-top: 30px;">
        <?php require_once('../../includes/sidebar.php'); ?>

        <div class="container my-4" style="background-color: #B1D4E0;
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
                     border-bottom-left-radius: 30px;
                    border-bottom-right-radius: 30px;">
            <!-- Dashboard Title -->
            <h4 class="text-center mb-0" style="font-family: 'Poppins', sans-serif !important;">Dashboard</h4>
            <hr class="my-2" style="border-top: 1px solid  rgb(117, 118, 120); ">

            <!-- Hello and Student Full Name -->
            <div class="text-center my-3">
                <h2 class="mb-1" style="font-family: 'Poppins', sans-serif !important;"><Strong>Hello</Strong> </h2>
                <h4 class="text-center" style="font-family: 'Poppins', sans-serif !important;"><strong><?= $first_name ?>!</strong></h4>
            </div>

            <?php if ($student_data['enabled'] == 1): ?>
                <button class="btn btn-success" id="requestClearanceBtn" style="margin-bottom: 25px; font-weight: 700;"
                    data-toggle="tooltip" title="Click to request clearance">
                    Request Clearance <br> and Balance
                </button>

            <?php else: ?>
                <div class="alert alert-info">
                    <span class="glyphicon glyphicon-info-sign"></span> You cannot request clearance at this time.
                </div>
            <?php endif; ?>

        </div>



        <!-- Clearance Requests Section -->
        <div class="card" id="requestClearance">
            <div class="card-header">
                <span style="font-family: 'Poppins', sans-serif !important;" > Clearance Requests</span>
            </div>

            <?php
            $school_id = $student_data['school_id']; // get school_id from student data
            
            $query = "SELECT id, student_id, status, date_requested FROM tbl_clearance_requests WHERE student_id = ? ORDER BY date_requested DESC";
            $stmt = $con->prepare($query);
            $stmt->bind_param('s', $school_id);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0): ?>
                <p class="text-muted">No clearance requests found.</p>
            <?php else:
                while ($row = $result->fetch_assoc()):
                    ?>
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-xs-6"><strong>Request ID:</strong> <?= $row['id'] ?></div>
                            <div class="col-xs-6 text-right"><strong>Date:</strong>
                                <?= htmlspecialchars($row['date_requested']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6"><strong>Student ID:</strong> <?= htmlspecialchars($row['student_id']) ?></div>
                            <!-- Removed status badge column -->
                        </div>
                    </div>
                <?php endwhile; endif; ?>
        </div>


        <!-- Student Info Section -->
        <div class="card-header"
            style=" border-top-left-radius: 20px; border-top-right-radius: 20px;  background-color: #003366; color: white; margin-top: 10px; margin-bottom: -20px; height: 65px; display: flex; justify-content: center; align-items: center;">
            <span class="glyphicon glyphicon-user" style="margin-right: 8px;"></span> <strong> Student Information
            </strong>
        </div>

        <div class="card" style="border-radius: 30px; border: 1px solid #003366; margin-bottom: 100px;">
            <?php if ($student_data): ?>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="font-size: 12px;">Full Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                style="font-size: 12px; height: 30px; max-width: 100%; width: 230px; margin-left: -50px;"
                                value="<?= htmlspecialchars($student_data['full_name']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="font-size: 12px;">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control"
                                style="font-size: 12px; height: 30px; max-width: 100%; width: 230px; margin-left: -50px;"
                                value="<?= htmlspecialchars($student_data['email']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="font-size: 12px;">School ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                style="font-size: 12px; height: 30px; max-width: 100%; width: 230px; margin-left: -50px;"
                                value="<?= htmlspecialchars($student_data['school_id']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="font-size: 12px;">Balance</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                style="font-size: 12px; height: 30px; max-width: 100%; width: 230px; margin-left: -50px;"
                                value="₱<?= number_format($student_data['balance'], 2) ?>" readonly>
                        </div>
                    </div>

                    <?php
                    $status = htmlspecialchars($student_data['status']) ?: 'Not Set';
                    $badgeClass = 'label label-default'; // default grey
                    if ($status === 'Pending') {
                        $badgeClass = 'label label-warning';       // yellow - good
                    } elseif ($status === 'For Signature') {
                        $badgeClass = 'label label-success';       // green - but you want orange here
                    } elseif ($status === 'For Payment') {
                        $badgeClass = 'label label-danger';        // red - good
                    } elseif ($status === 'Cleared') {
                        $badgeClass = 'label label-success';       // blue - you want green here
                    }


                    ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="font-size: 12px;">Status</label>
                        <div class="col-sm-9">
                            <span class="<?= $badgeClass ?>" style="font-size: 12px; width: 100px; margin-left: -50px;">
                                <?= htmlspecialchars($status) ?>
                            </span>

                        </div>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-danger">Student data not found.</p>
            <?php endif; ?>
        </div>



    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
        // Show clearance requests section only after clicking button
        $(document).ready(function () {
            $('#requestClearance').hide();

            // Initialize all tooltips
            $('[data-toggle="tooltip"]').tooltip();

            $('#requestClearanceBtn').click(function () {
                // Toggle clearance requests div
                $('#requestClearance').slideToggle();

                // AJAX request to submit clearance request
                $.ajax({
                    url: 'request_clearance.php',
                    type: 'POST',
                    data: {},
                    success: function (response) {
                        console.log('AJAX response:', response); // debug

                        var res = JSON.parse(response);

                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Submitted',
                                text: res.message,
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            // Show error message as tooltip on the button
                            var $btn = $('#requestClearanceBtn');

                            // Update tooltip text dynamically
                            $btn.attr('data-original-title', res.message)
                                .tooltip('fixTitle')  // update tooltip content
                                .tooltip('show');

                            // Hide tooltip after 3 seconds
                            setTimeout(function () {
                                $btn.tooltip('hide');

                                // Optionally reset tooltip text to default after hiding
                                $btn.attr('data-original-title', 'Click to request clearance')
                                    .tooltip('fixTitle');
                            }, 3000);
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Request Failed',
                            text: 'There was an issue processing your request. Please try again later.'
                        });
                    }
                });
            });
        });
    </script>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>
</body>

</html>