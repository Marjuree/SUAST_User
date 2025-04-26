<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../configuration/config.php";

// Check if the database connection is successful
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}

session_regenerate_id(true);

$employee_id = $_SESSION['employee_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Employee";

// Get the latest certification request info
$query = "SELECT current_stage, 
                 issuance_submitted, issuance_received, 
                 cashier_submitted, cashier_received, 
                 present_request_submitted, present_request_received, 
                 prepare_service_record_submitted, prepare_service_record_received, 
                 hr_director_signs_submitted, hr_director_signs_received, 
                 logbook_submitted, logbook_received, 
                 for_releasing_submitted, for_releasing_received, 
                 completed_date
          FROM tbl_service_requests
          WHERE employee_id = ? 
          ORDER BY created_at DESC 
          LIMIT 1";

// Prepare the SQL statement
$stmt = $con->prepare($query);

// Check if the prepare statement fails
if (!$stmt) {
    die("Query preparation failed: " . $con->error);
}

// Bind parameters
$stmt->bind_param("i", $employee_id);

// Execute the query
$stmt->execute();

// Bind result variables
$stmt->bind_result(
    $current_stage,
    $issuance_submitted, $issuance_received,
    $cashier_submitted, $cashier_received,
    $present_request_submitted, $present_request_received,
    $prepare_service_record_submitted, $prepare_service_record_received,
    $hr_director_signs_submitted, $hr_director_signs_received,
    $logbook_submitted, $logbook_received,
    $for_releasing_submitted, $for_releasing_received,
    $completed_date
);

// Fetch the results
$stmt->fetch();

// Close the statement
$stmt->close();

// Define roles per faculty
$roles = ['Fill out Form', 'Pay Cashier', 'Present Request', 
          'Prepare Service Record', 'HR Director Signs', 'Record in Logbook', 'For Releasing', 'Completed'];

// Determine current index
$currentIndex = array_search($current_stage, array_map('trim', $roles));

// If "For Releasing", treat it as complete (i.e., all done)
if ($current_stage === 'For Releasing') {
    $currentIndex = count($roles); // All steps done
}

// Function to format the date (without the time)
function formatStageTime($datetime) {
    return $datetime ? date('Y-m-d', strtotime($datetime)) : '';  // Format date as Y-m-d
}


// Fetch full name from tbl_employee_registration
$infoQuery = "SELECT CONCAT(first_name, ' ', middle_name, ' ', last_name) AS full_name, faculty 
              FROM tbl_employee_registration 
              WHERE employee_id = ?";
$infoStmt = $con->prepare($infoQuery);
$infoStmt->bind_param("i", $employee_id);
$infoStmt->execute();
$infoResult = $infoStmt->get_result();
$infoRow = $infoResult->fetch_assoc();
$full_name = $infoRow['full_name'] ?? 'Unknown';
$faculty = $infoRow['faculty'] ?? 'Unknown';
$infoStmt->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Employees | Dash</title>
    <link href="../../css/button.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>
<style>
.progress-tracker {
    display: flex;
    justify-content: space-between;
    align-items: center;
    counter-reset: step;
    margin: 40px 0;
    padding: 0 20px;
    position: relative;
}

.progress-tracker::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 30px;
    right: 30px;
    height: 4px;
    background-color: #ccc;
    z-index: 0;
}

.progress-tracker li {
    list-style: none;
    position: relative;
    text-align: center;
    z-index: 1;
    flex: 1;
}

.progress-tracker li::before {
    content: counter(step);
    counter-increment: step;
    display: inline-block;
    width: 35px;
    height: 35px;
    line-height: 35px;
    background: #fff;
    border: 3px solid #ccc;
    border-radius: 50%;
    margin-bottom: 10px;
    color: #ccc;
    font-weight: bold;
}

.progress-tracker li.done::before {
    content: "✔";
    background-color: #4CAF50;
    border-color: #4CAF50;
    color: white;
}

.progress-tracker li.current::before {
    background-color: #4CAF50;
    border-color: #4CAF50;
    color: white;
}

.progress-tracker li span {
    display: block;
    margin-top: 5px;
    color: #666;
    font-weight: 500;
}

@media (max-width: 600px) {
    .progress-tracker li span {
        font-size: 14px;
    }

    .progress-tracker li small {
        font-size: 10px;

    }

    .progress-tracker {
        flex-wrap: wrap;
        padding: 0 10px;
    }

    .progress-tracker li {
        text-align: center;
    }

    .progress-tracker-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 20px;
    }

    .progress-tracker {
        width: max-content;
        min-width: 800px;
    }

    .progress-tracker li small {
        display: block;
        font-size: 10 px !important;
        color: #666;
        margin-top: 4px;
    }

    .progress-tracker li span {
        display: block;
        font-size: 10px !important;
        font-weight: bold;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
    }

}
</style>

<body class="skin-blue">
    <?php 
    require_once('includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <h4>Service Request Progress</h4>

                            <p><strong><?= htmlspecialchars($faculty) ?>:</strong></p>
                            <div class="progress-tracker-wrapper">

                                <ul class="progress-tracker">
                                    <?php 
                                foreach ($roles as $index => $role):
                                    $statusClass = ($index < $currentIndex) ? 'done' : (($index === $currentIndex) ? 'current' : '');
                                ?>
                                    <li class="<?= $statusClass ?>" title="<?= htmlspecialchars($role) ?>">
                                        <span><?= htmlspecialchars($role) ?></span>
                                        <?php if ($role === 'Fill out Form'): ?>
                                        <small>SUBMITTED: <?= formatStageTime($issuance_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($issuance_received) ?></small>
                                        <?php elseif ($role === 'Pay Cashier'): ?>
                                        <small>SUBMITTED: <?= formatStageTime($cashier_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($cashier_received) ?></small>
                                        <?php elseif ($role === 'Present Request'): ?>
                                        <small>SUBMITTED: <?= formatStageTime($present_request_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($present_request_received) ?></small>
                                        <?php elseif ($role === 'Prepare Service Record'): ?>
                                        <small>SUBMITTED:
                                            <?= formatStageTime($prepare_service_record_submitted) ?></small><br>
                                        <small>RECEIVED:
                                            <?= formatStageTime($prepare_service_record_received) ?></small>
                                        <?php elseif ($role === 'HR Director Signs'): ?>
                                        <small>SUBMITTED:
                                            <?= formatStageTime($hr_director_signs_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($hr_director_signs_received) ?></small>
                                        <?php elseif ($role === 'Record in Logbook'): ?>
                                        <small>SUBMITTED: <?= formatStageTime($logbook_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($logbook_received) ?></small>
                                        <?php elseif ($role === 'For Releasing'): ?>
                                        <small>SUBMITTED: <?= formatStageTime($for_releasing_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($for_releasing_received) ?></small>
                                        <?php elseif ($role === 'Completed'): ?>
                                        <small>SUBMITTED: <?= formatStageTime($for_releasing_submitted) ?></small><br>
                                        <small>RECEIVED: <?= formatStageTime($for_releasing_received) ?></small>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <?php if ($current_stage === 'For Releasing'): ?>
                            <div
                                style="margin-bottom: 20px; padding: 15px 20px; background-color: #e6f9ed; border-left: 6px solid #2ecc71; border-radius: 8px; display: flex; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                <span style="font-size: 24px; color: #2ecc71; margin-right: 12px;">✅</span>
                                <span style="color: #2c3e50; font-weight: 500; font-size: 16px;">
                                    Your Service request is <strong>ready for release</strong>!
                                </span>
                            </div>
                            <?php endif; ?>

                            <hr>

                            <!-- Submit Leave Button -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#requestServiceRecord">
                                Submit Leave
                            </button>
                            <!-- Service Requests Table -->
                            <div id="serviceRequests">
                                <h3>Service Requests</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-default">
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Request Type</th>
                                                <th>Date of Request</th>
                                                <th>Faculty/Institute</th>
                                                <th>Reason</th>
                                                <th>Current Stage</th>
                                                <th>Aproval</th>
                                                <th>Completion Status</th>
                                                <th>Service File</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $stmt = $con->prepare("SELECT * FROM tbl_service_requests WHERE employee_id = ? ORDER BY created_at DESC");
                                                $stmt->bind_param("i", $employee_id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                while ($row = $result->fetch_assoc()):
                                                    $stage = ucfirst(strtolower($row['current_stage']));
                                                    $status = ucfirst($row['request_status'] ?? 'Pending');
                                                    $completion = ucfirst($row['completion_status'] ?? 'Pending');

                                                    // Style classes
                                                    $status_class = 'label-warning';
                                                    if (strtolower($status) === 'approved') $status_class = 'label-success';
                                                    elseif (strtolower($status) === 'disapproved') $status_class = 'label-danger';

                                                    $completion_class = 'label-warning';
                                                    if (strtolower($completion) === 'completed') $completion_class = 'label-primary';
                                                    elseif (strtolower($completion) === 'done') $completion_class = 'label-success';

                                                 // Attachment
                                                    $attachment_html = !empty($row['attachment'])
                                                    ? "<a href='download_service.php?id={$row['id']}' class='btn btn-sm btn-primary' target='_blank' title='" . htmlspecialchars($row['file_name']) . "'>
                                                            " . htmlspecialchars($row['file_name']) . "
                                                    </a>"
                                                    : "<span class='text-muted'>No file</span>";

                                                
                                                ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                <td><?= htmlspecialchars($row['faculty']) ?></td>

                                                <!-- Reason -->
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-toggle="modal" data-target="#reasonModal<?= $row['id'] ?>">
                                                        View Reason
                                                    </button>
                                                    <div class="modal fade" id="reasonModal<?= $row['id'] ?>"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="reasonModalLabel<?= $row['id'] ?>">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal"><span>&times;</span></button>
                                                                    <h4 class="modal-title"
                                                                        id="reasonModalLabel<?= $row['id'] ?>">Reason
                                                                        for Request</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?= nl2br(htmlspecialchars($row['reason'])) ?></div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Current Stage -->
                                                <td><span><?= $stage ?></span></td>

                                                <!-- Status -->
                                                <td><span class="label <?= $status_class ?>"><?= $status ?></span></td>

                                                <!-- Completion Status -->
                                                <td><span
                                                        class="label <?= $completion_class ?>"><?= $completion ?></span>
                                                </td>

                                                <!-- Attachment -->
                                                <td><?= $attachment_html ?></td>

                                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            <!-- Service Record Request -->
                            <div class="modal fade" id="requestServiceRecord" tabindex="-1" role="dialog"
                                aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Request Service Record</h4>
                                        </div>
                                        <form action="process_request.php" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="request_type" value="Service Record">

                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">Full Name:</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= htmlspecialchars($full_name) ?>" readonly>
                                                    <input type="hidden" name="name"
                                                        value="<?= htmlspecialchars($full_name) ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="faculty">Position:</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= htmlspecialchars($faculty) ?>" readonly>
                                                    <input type="hidden" name="faculty"
                                                        value="<?= htmlspecialchars($faculty) ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label for="date_request">Date of Request:</label>
                                                    <input type="date" name="date_request" id="date_request" required
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="reason">Reason for Request:</label>
                                                    <textarea name="reason" id="reason" required
                                                        class="form-control"></textarea>
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
                                                <button type="submit" class="btn btn-primary">Submit Request</button>
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/chart.js"></script>
</body>

</html>
