<?php
session_start();
require_once "../../configuration/config.php"; // Ensure database connection

// Debugging: Check session values
// Uncomment the next lines to debug session issues
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit();
*/

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);

// Store session values safely
$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant"; // Prevent XSS
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Book | Dash</title>
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>

<body class="skin-blue">
    <?php 
    require_once('includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Schedule</h1>
            </section>
            
            <section class="content">
                <div class="box">
                    <div class="box-header d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#reservationModal">
                            +Book Schedule
                        </button>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <!-- Reservation Modal -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"> 
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Exam Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </div>
                <div class="modal-body">
                    <form action="process_reservation.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="exam_time" class="form-label">Exam Time</label>
                            <select class="form-control" id="exam_time" name="exam_time" required>
                                <option value="" disabled selected>Select Time</option>
                                <?php
                                $query = "SELECT DISTINCT exam_time FROM tbl_exam_schedule WHERE exam_time IS NOT NULL";
                                $result = mysqli_query($con, $query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['exam_time']}'>{$row['exam_time']}</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>No available times</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="room" class="form-label">Room</label>
                            <select class="form-control" id="room" name="room" required>
                                <option value="" disabled selected>Select Room</option>
                                <?php
                                $query = "SELECT DISTINCT room FROM tbl_exam_schedule WHERE room IS NOT NULL";
                                $result = mysqli_query($con, $query);

                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['room']}'>{$row['room']}</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>No rooms available</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="venue" class="form-label">Exam Venue</label>
                            <input type="text" class="form-control" id="venue" name="venue" value="AB Building, DORSU" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Confirm Reservation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    

    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>