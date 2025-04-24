<?php
session_start();
// Generate new session ID for security
session_regenerate_id(true);

require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error_message'] = "Please login as an applicant";
    // No header redirection. Instead, display the message
}


$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";

// Debugging: Check if data is being passed correctly
if (isset($_GET['success'])) {
    echo "<p class='success'>Success: " . $_GET['success'] . "</p>";
} elseif (isset($_GET['error'])) {
    echo "<p class='error'>Error: " . $_GET['error'] . "</p>";
}

// Fetch the exam schedule data to display available slots
$query = "SELECT * FROM tbl_exam_schedule";
$result = mysqli_query($con, $query);

// Fetch only the reservations made by the logged-in user (based on applicant_id)
$query_reservations = "SELECT * FROM tbl_reservation WHERE applicant_id = '$applicant_id'";
$result_reservations = mysqli_query($con, $query_reservations);

// Check if the user has already made a reservation
$user_has_reservation = mysqli_num_rows($result_reservations) > 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Book | Dash</title>
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
    .success {
        color: green;
        font-weight: bold;
    }

    .error {
        color: red;
        font-weight: bold;
    }

    .status-pending {
        background-color: #f39c12;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .status-approved {
        background-color: #28a745;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .status-rejected {
        background-color: #dc3545;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .status-other {
        background-color: #6c757d;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .modal-body input {
        border-radius: 30px !important;
    }

    .form-control {
        border-radius: 30px !important;
    }
    </style>
</head>

<body class="skin-blue">
    <?php require_once('includes/header.php'); ?>
    <?php require_once('../../includes/head_css.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        <aside class="right-side">
            <section class="content-header">
                <h1>Request</h1>
                <p>Welcome, <strong><?= $first_name ?></strong></p>
            </section>

            <!-- View Exam Reservations Table -->
            <section class="content">
                <div class="box">

                    <div class="box-header">
                        <h3 class="box-title">Your Exam Reservations</h3>
                    </div>
                    <div class="box-body">
                        <!-- Display messages if any -->
                        <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error_message']; ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <!-- Make table responsive -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Exam Date</th>
                                        <th>Exam Time</th>
                                        <th>Room</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Reason</th> <!-- New Column -->
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        if (mysqli_num_rows($result_reservations) > 0) {
                                            while ($row = mysqli_fetch_assoc($result_reservations)) {
                                                $status = ($row['status'] == NULL) ? 'pending' : $row['status'];

                                                $status_class = '';
                                                switch ($status) {
                                                    case 'pending':
                                                        $status_class = 'status-pending';
                                                        break;
                                                    case 'approved':
                                                        $status_class = 'status-approved';
                                                        break;
                                                    case 'rejected':
                                                        $status_class = 'status-rejected';
                                                        break;
                                                    default:
                                                        $status_class = 'status-other';
                                                        break;
                                                }

                                                echo "<tr>";
                                                echo "<td>{$row['name']}</td>";
                                                echo "<td>" . ($row['exam_date'] ? date('F j, Y', strtotime($row['exam_date'])) : 'Not Selected') . "</td>";
                                                echo "<td>" . ($row['exam_time'] ? $row['exam_time'] : 'Not Selected') . "</td>";
                                                echo "<td>{$row['room']}</td>";
                                                echo "<td>{$row['venue']}</td>";
                                                echo "<td><span class='{$status_class}'>" . ucfirst($status) . "</span></td>";

                                                // Reason modal column
                                                if ($status === 'rejected' && !empty($row['reason'])) {
                                                    $modalId = "reasonModal_" . $row['id'];
                                                    echo "<td><button class='btn btn-xs btn-danger' data-toggle='modal' data-target='#{$modalId}'>View Reason</button></td>";

                                                    // Modal code
                                                    echo "
                                                    <div class='modal fade' id='{$modalId}' tabindex='-1' role='dialog' aria-labelledby='reasonModalLabel_{$row['id']}' aria-hidden='true'>
                                                        <div class='modal-dialog'>
                                                            <div class='modal-content'>
                                                                <div class='modal-header'>
                                                                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                                                                    <h4 class='modal-title' id='reasonModalLabel_{$row['id']}'>Reason for Rejection</h4>
                                                                </div>
                                                                <div class='modal-body'>
                                                                    <p class='text-justify'>" . nl2br(htmlspecialchars($row['reason'])) . "</p>
                                                                </div>
                                                                <div class='modal-footer'>
                                                                    <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>";
                                                } else {
                                                    echo "<td><em>-</em></td>";
                                                }

                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No reservations found.</td></tr>";
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </aside>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <?php require_once "../../includes/footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</body>

</html>
