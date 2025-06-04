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
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/student.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap 3 CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="skin-blue">
    <?php
    require_once "../../configuration/config.php";
    require_once('includes/header.php');
    require_once('../../includes/head_css.php');
    ?>

    <div class="container" style="margin-top: 30px;">
        <?php require_once('../../includes/sidebar.php'); ?>

        <h1>Welcome, <strong><?= $first_name ?></strong></h1>

        <?php if ($student_data['enabled'] == 1): ?>
            <button class="btn btn-success" id="requestClearanceBtn" style="margin-bottom: 25px;" data-toggle="tooltip"
                title="Click to request clearance">
                Request Clearance
            </button>

        <?php else: ?>
            <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> You cannot request clearance at this time.
            </div>
        <?php endif; ?>

        <!-- Clearance Requests Section -->
        <div class="card" id="requestClearance">
            <div class="card-header">
                <span class="glyphicon glyphicon-list-alt"> Clearance Requests</span>
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
        <div class="card">
            <div class="card-header">
                <span class="glyphicon glyphicon-user"></span> Student Information
            </div>
            <?php if ($student_data): ?>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Full Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($student_data['full_name']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="<?= htmlspecialchars($student_data['email']) ?>"
                                readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">School ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                value="<?= htmlspecialchars($student_data['school_id']) ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Balance</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control"
                                value="₱<?= number_format($student_data['balance'], 2) ?>" readonly>
                        </div>
                    </div>

                    <?php
                    $status = htmlspecialchars($student_data['status']) ?: 'Not Set';
                    $badgeClass = 'bg-secondary'; // default if status is unknown
                
                    if ($status === 'Pending') {
                        $badgeClass = 'bg-warning'; // yellow
                    } elseif ($status === 'For Signature') {
                        $badgeClass = 'bg-success'; // green
                    } elseif ($status === 'For Payment') {
                        $badgeClass = 'bg-danger'; // red
                    } elseif ($status === 'Cleared') {
                        $badgeClass = 'bg-primary'; // blue
                    }
                    ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-9">
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
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
