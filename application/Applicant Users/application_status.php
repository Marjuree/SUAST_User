<?php
session_start();
// Generate new session ID for security
session_regenerate_id(true);

require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error_message'] = "Please login as an applicant";
    // No redirect here, just message
}

$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";

// Debug messages for success or error (via GET params)
if (isset($_GET['success'])) {
    echo "<p class='success'>Success: " . htmlspecialchars($_GET['success']) . "</p>";
} elseif (isset($_GET['error'])) {
    echo "<p class='error'>Error: " . htmlspecialchars($_GET['error']) . "</p>";
}

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
    <title>Book | Dash</title>
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
</head>

<body class="skin-blue">

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <aside class="right-side">

            <!-- View Exam Reservations Table -->
            <section class="content">
                <div class="box">
                    <div class="box-body">

                        <!-- Display session error message -->
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($_SESSION['error_message']); ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <!-- Responsive table -->
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
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result_reservations) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_reservations)) {
                                            $status = ($row['status'] === NULL) ? 'pending' : $row['status'];

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
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . ($row['exam_date'] ? date('F j, Y', strtotime($row['exam_date'])) : 'Not Selected') . "</td>";
                                            echo "<td>" . ($row['exam_time'] ? htmlspecialchars($row['exam_time']) : 'Not Selected') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['room']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['venue']) . "</td>";
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

                                            // Action column with update button
                                            echo "<td>";
                                            if ($status === 'rejected') {
                                                echo "
                                                <form method='POST' action='request_update.php' style='display:inline;' id='updateForm_{$row['id']}'>
                                                    <input type='hidden' name='reservation_id' value='{$row['id']}'>
                                                    <button type='button' class='btn btn-xs btn-warning' onclick='showSweetAlert({$row['id']});'>Request Update</button>
                                                </form>";
                                            } else {
                                                echo "<em>-</em>";
                                            }
                                            echo "</td>";

                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                                <td colspan='8' class='text-center'>No reservations found.</td>
                                            </tr>";
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

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showSweetAlert(reservationId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to send an update request to admin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, send request!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('updateForm_' + reservationId).submit();
                }
            });
        }
    </script>
</body>

</html>
