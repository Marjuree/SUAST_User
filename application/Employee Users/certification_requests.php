<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_regenerate_id(true);


require_once "../../configuration/config.php";

// Check if the database connection is successful
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}


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
          FROM tbl_certification_requests
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
    $issuance_submitted,
    $issuance_received,
    $cashier_submitted,
    $cashier_received,
    $present_request_submitted,
    $present_request_received,
    $prepare_service_record_submitted,
    $prepare_service_record_received,
    $hr_director_signs_submitted,
    $hr_director_signs_received,
    $logbook_submitted,
    $logbook_received,
    $for_releasing_submitted,
    $for_releasing_received,
    $completed_date
);

// Fetch the results
$stmt->fetch();

// Close the statement
$stmt->close();

// Define roles per faculty
$roles = [
    'Present Request',
    'Preparing Certification Record',
    'For Releasing',
    'Completed'
];

// Determine current index
$currentIndex = array_search($current_stage, array_map('trim', $roles));

// If "For Releasing", treat it as complete (i.e., all done)
if ($current_stage === 'For Releasing') {
    $currentIndex = count($roles); // All steps done
}

// Function to format the date (without the time)
function formatStageTime($datetime)
{
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employees | Certification Requests</title>
    <link href="../../css/button.css" rel="stylesheet" />
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

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

        table thead {
            background-color: #343a40;
            color: #fff;
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

        <aside class="right-side">
            <section class="content-header">
                <h1>Certification Request</h1>
                <p>Welcome, <strong><?= $first_name ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <h4>Certification Request Progress</h4>

                            <p><strong><?= htmlspecialchars($faculty) ?>:</strong></p>
                            <div class="progress-tracker-wrapper" >

                                <ul class="progress-tracker">
                                    <?php
                                    foreach ($roles as $index => $role):
                                        $statusClass = ($index < $currentIndex) ? 'done' : (($index === $currentIndex) ? 'current' : '');
                                        ?>
                                        <li class="<?= $statusClass ?>" title="<?= htmlspecialchars($role) ?>">
                                            <span><?= htmlspecialchars($role) ?></span>

                                            <?php if ($role === 'Present Request'): ?>
                                                <small>SUBMITTED: <?= formatStageTime($present_request_submitted) ?></small><br>
                                            <?php elseif ($role === 'Preparing Certification Record'): ?>
                                                <small style="color: transparent;">SUBMITTED:
                                                    <?= formatStageTime($prepare_service_record_submitted) ?></small><br>


                                            <?php elseif ($role === 'For Releasing'): ?>
                                                <small style="color: transparent;">SUBMITTED:
                                                    <?= formatStageTime($for_releasing_submitted) ?></small><br>

                                            <?php elseif ($role === 'Completed'): ?>
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
                                        Your certification request is <strong>ready for release</strong>!
                                    </span>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <?php
                            $stmt = $con->prepare("SELECT * FROM tbl_certification_requests WHERE employee_id = ? ORDER BY created_at DESC");
                            $stmt->bind_param("i", $employee_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                                    $stage = strtolower($row['current_stage']);
                                    $stage_display = ucfirst($stage);

                                    // Prepare the file link or no file text
                                    if (!empty($row['certification_file'])) {
                                        $file_link = "<a href='download_certification_file.php?id=" . $row['id'] . "' class='form-control' style='
                                        font-size: 12px; 
                                        height: 30px; 
                                        line-height: 30px; 
                                        display: inline-block; 
                                        color: #337ab7; 
                                        text-decoration: underline; 
                                        cursor: pointer; 
                                        width: 230px; 
                                        border-radius: 50px !important; 
                                        overflow: hidden; 
                                        white-space: nowrap; 
                                        text-overflow: ellipsis;' target='_blank' title='Download " . htmlspecialchars($row['file_name']) . "'>"
                                                                    . htmlspecialchars($row['file_name']) . "</a>";
                                                            } else {
                                                                $file_link = '<input type="text" class="form-control" readonly
                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important; color: #888;" value="No file">';
                                    }

                                    // Approval Status Background Color
                                    $approval_status = !empty($row['request_status']) ? ucfirst($row['request_status']) : 'Pending';
                                    if (strtolower($row['request_status']) === 'approved') {
                                        $approval_bg = 'lightgreen';
                                    } elseif (strtolower($row['request_status']) === 'disapproved') {
                                        $approval_bg = '#f8d7da'; // light red
                                    } else {
                                        $approval_bg = 'orange';
                                    }

                                    // Completion Status Background Color
                                    $completion_status = !empty($row['completion_status']) ? ucfirst($row['completion_status']) : 'Pending';
                                    if (strtolower($row['completion_status']) === 'done') {
                                        $completion_bg = 'lightgreen';
                                    } elseif (strtolower($row['completion_status']) === 'completed') {
                                        $completion_bg = '#add8e6'; // light blue
                                    } else {
                                        $completion_bg = 'orange';
                                    }
                                    ?>

                                    <!-- Card with header background similar to Code 1 -->
                                    <div
                                        style="position: absolute; border-top-left-radius: 30px;
                                        border-top-right-radius: 30px; height: 70px; background-color: #003366; z-index: 0; margin-top: -50px; margin-left: 11px !important; max-width: 350px; width: 350px;">
                                        <h5
                                            style="color: #fff; text-align: left; line-height: 60px; margin: 0 0 0 20px; font-size: 14px; font-weight: bold;">
                                            <i class="fas fa-file-alt" style="margin-right: 8px;"></i>Certification Details
                                        </h5>
                                    </div>

                                    <div class="card shadow-sm mb-4"
                                        style="z-index: 999; border-radius: 20px; border: 1px solid #003366; max-width: 350px; margin: auto; position: relative; overflow: hidden; background-color: #f9f9f9;">

                                        <div class="card-body"
                                            style="padding: 20px; position: relative; z-index: 1; background-color: rgba(255,255,255,0.95); border-radius: 20px;">
                                            <form class="form-horizontal" style="margin-top: 20px;">

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label" style="font-size: 12px;">Full
                                                        Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;"
                                                            value="<?= htmlspecialchars($row['name']) ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label" style="font-size: 12px;">Request
                                                        Type</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;"
                                                            value="<?= htmlspecialchars($row['request_type']) ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label" style="font-size: 12px;">Date of
                                                        Request</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;"
                                                            value="<?= htmlspecialchars($row['date_request']) ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label"
                                                        style="font-size: 12px;">Position</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;"
                                                            value="<?= htmlspecialchars($row['faculty']) ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label" style="font-size: 12px;">Current
                                                        Stage</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;"
                                                            value="<?= $stage_display ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label" style="font-size: 12px;">Certification
                                                        File</label>
                                                    <div class="col-sm-9">
                                                        <?= $file_link ?>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label"
                                                        style="font-size: 12px;">Approval</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; color: black; background-color: <?= $approval_bg ?>; border-radius: 50px !important; text-align: center;"
                                                            value="<?= htmlspecialchars($approval_status) ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label" style="font-size: 12px;">Completion
                                                        Status</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            style="font-size: 12px; height: 30px; width: 230px; color: black; background-color: <?= $completion_bg ?>; border-radius: 50px !important; text-align: center;"
                                                            value="<?= htmlspecialchars($completion_status) ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 control-label"
                                                        style="font-size: 12px;">Reason</label>
                                                    <div class="col-sm-9">
                                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                            data-target="#reasonModal<?= $row['id'] ?>"
                                                            style="font-size: 12px; height: 30px;">
                                                            View Reason
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-12 text-right">
                                                        <button class="btn btn-sm btn-info edit-btn" data-toggle="modal"
                                                            data-target="#editCertificationModal" data-id="<?= $row['id'] ?>"
                                                            data-name="<?= htmlspecialchars($row['name']) ?>"
                                                            data-request_type="<?= htmlspecialchars($row['request_type']) ?>"
                                                            data-date_request="<?= htmlspecialchars($row['date_request']) ?>"
                                                            data-faculty="<?= htmlspecialchars($row['faculty']) ?>"
                                                            data-reason="<?= htmlspecialchars($row['reason']) ?>"
                                                            style="font-size: 12px; height: 30px; background-color: #003366; border-radius: 50px !important;">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-btn"
                                                            data-id="<?= $row['id'] ?>"
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important;">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>

                                    <!-- Reason Modal -->
                                    <div class="modal fade" id="reasonModal<?= $row['id'] ?>" tabindex="-1" role="dialog"
                                        aria-labelledby="reasonModalLabel<?= $row['id'] ?>">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reasonModalLabel<?= $row['id'] ?>">Reason for
                                                        Request</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body" style="white-space: pre-wrap;">
                                                    <?= nl2br(htmlspecialchars($row['reason'])) ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                endwhile;
                            else:
                                ?>

                                <!-- No data found — show empty form -->
                                <div
                                    style="position: absolute; border-top-left-radius: 30px;
                                     border-top-right-radius: 30px; height: 70px; background-color: #003366; z-index: 0; margin-top: -50px; margin-left: 16px !important; max-width: 350px; width: 350px;">
                                    <h5
                                        style="color: #fff; text-align: left; line-height: 60px; margin: 0 0 0 20px; font-size: 14px; font-weight: bold;">
                                        <i class="fas fa-file-alt" style="margin-right: 8px;"></i>Complete Details
                                    </h5>
                                </div>

                                <div class="card shadow-sm mb-4"
                                    style=" z-index: 999; border-radius: 20px; border: 1px solid #003366; max-width: 350px; margin: auto; position: relative; overflow: hidden; background-color: #f9f9f9;">

                                    <div class="card-body"
                                        style="padding: 20px; position: relative; z-index: 1; background-color: rgba(255,255,255,0.95); border-radius: 20px;">
                                        <form class="form-horizontal" style="margin-top: 20px;">
                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label" style="font-size: 12px;">Full
                                                    Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label" style="font-size: 12px;">Request
                                                    Type</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label" style="font-size: 12px;">Date of
                                                    Request</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label"
                                                    style="font-size: 12px;">Position</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label" style="font-size: 12px;">Current
                                                    Stage</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label" style="font-size: 12px;">Certification
                                                    File</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="No file" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important; color: #888;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label"
                                                    style="font-size: 12px;">Approval</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="Pending" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; color: black; background-color: orange; border-radius: 50px !important; text-align: center;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label" style="font-size: 12px;">Completion
                                                    Status</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="Pending" readonly
                                                        style="font-size: 12px; height: 30px; width: 230px; color: black; background-color: orange; border-radius: 50px !important; text-align: center;">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-sm-3 control-label"
                                                    style="font-size: 12px;">Reason</label>
                                                <div class="col-sm-9">
                                                    <button type="button" class="btn btn-info btn-sm" disabled
                                                        style="font-size: 12px; height: 30px;">View Reason</button>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-sm btn-info edit-btn" data-toggle="modal"
                                                data-target="#editCertificationModal" data-id="<?= $row['id'] ?>"
                                                data-name="<?= !empty($row['name']) ? htmlspecialchars($row['name']) : 'N/A' ?>"
                                                data-request_type="<?= !empty($row['request_type']) ? htmlspecialchars($row['request_type']) : 'N/A' ?>"
                                                data-date_request="<?= !empty($row['date_request']) ? htmlspecialchars($row['date_request']) : 'N/A' ?>"
                                                data-faculty="<?= !empty($row['faculty']) ? htmlspecialchars($row['faculty']) : 'N/A' ?>"
                                                data-reason="<?= !empty($row['reason']) ? htmlspecialchars($row['reason']) : 'N/A' ?>"
                                                style="font-size: 12px; height: 30px; background-color: #003366; border-radius: 50px !important;">
                                                Edit
                                            </button>



                                            <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $row['id'] ?>"
                                                style="font-size: 12px; height: 30px; border-radius: 50px !important;">
                                                Delete
                                            </button>

                                        </form>
                                    </div>
                                </div>

                                <?php
                            endif;
                            ?>

                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <!-- Edit Certification Modal -->
    <!-- Edit Certification Modal -->
    <div class="modal fade" id="editCertificationModal" tabindex="-1" role="dialog"
        aria-labelledby="editCertificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editCertificationForm" enctype="multipart/form-data" method="POST"
                action="edit_certification_request.php">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCertificationModalLabel">Edit Certification Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-certification-id" value="">

                        <div class="form-group">
                            <label for="edit-certification-name">Full Name</label>
                            <input type="text" class="form-control" id="edit-certification-name" readonly value="">
                            <input type="hidden" name="name" id="edit-certification-name-hidden" value="">
                        </div>

                        <div class="form-group">
                            <label for="edit-certification-request-type">Request Type</label>
                            <input type="text" class="form-control" id="edit-certification-request-type" readonly
                                value="">
                            <input type="hidden" name="request_type" id="edit-certification-request-type-hidden"
                                value="">
                        </div>

                        <div class="form-group">
                            <label for="edit-certification-date-request">Date of Request</label>
                            <input type="date" name="date_request" id="edit-certification-date-request"
                                class="form-control" required value="">
                        </div>

                        <div class="form-group">
                            <label for="edit-certification-faculty">Position</label>
                            <input type="text" class="form-control" id="edit-certification-faculty" readonly value="">
                            <input type="hidden" name="faculty" id="edit-certification-faculty-hidden" value="">
                        </div>

                        <div class="form-group">
                            <label for="edit-certification-reason">Reason</label>
                            <textarea name="reason" id="edit-certification-reason" class="form-control" rows="4"
                                required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit-certification-file">Upload New Certification File (optional)</label>
                            <input type="file" name="certification_file" id="edit-certification-file"
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip" class="form-control" multiple>
                            <small class="form-text text-muted">Accepted formats: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX,
                                ZIP</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Certification Request -->
    <div class="modal fade" id="requestCertification" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Request Certification</h4>
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
                            <input type="text" class="form-control" value="<?= htmlspecialchars($faculty) ?>" readonly>
                            <input type="hidden" name="faculty" value="<?= htmlspecialchars($faculty) ?>">
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
                            <button type="submit" class="btn btn-primary">Submit
                                Request</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#certificationForm').on('submit', function (e) {
                e.preventDefault();  // Prevent normal form submission

                var formData = new FormData(this);  // Include file upload

                $.ajax({
                    url: 'process_certification.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',  // Expect JSON from PHP
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Close modal, reset form, and optionally reload page
                                $('#requestCertification').modal('hide');
                                $('#certificationForm')[0].reset();
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message || 'An error occurred!',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'There was an error processing your request.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Edit button click handler
            $(document).on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var requestType = $(this).data('request_type');
                var dateRequest = $(this).data('date_request');
                var faculty = $(this).data('faculty');
                var reason = $(this).data('reason');

                $('#edit-certification-id').val(id);
                $('#edit-certification-name').val(name);
                $('#edit-certification-name-hidden').val(name);
                $('#edit-certification-request-type').val(requestType);
                $('#edit-certification-request-type-hidden').val(requestType);
                $('#edit-certification-date-request').val(dateRequest);
                $('#edit-certification-faculty').val(faculty);
                $('#edit-certification-faculty-hidden').val(faculty);
                $('#edit-certification-reason').val(reason);

                $('#editCertificationModal').modal('show');
            });


            // Submit edit form with AJAX
            $('#editCertificationForm').submit(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: 'edit_certification_request.php',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire('Success', 'Updated!', 'success').then(() => {
                                // Close the modal
                                $('#editCertificationModal').modal('hide');
                                // Reload the page to show updated data
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message || 'Update failed', 'error');
                        }
                    },
                    error: function () {
                        Swal.close();
                        Swal.fire('Error', 'AJAX error occurred', 'error');
                    }
                });
            });




            // Delete button click handler with SweetAlert2 confirmation and AJAX
            $('.delete-btn').click(function () {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: 'delete_certification_request.php',
                            type: 'POST',
                            data: { id: id },
                            dataType: 'json',
                            success: function (response) {
                                Swal.close();

                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message || 'The request has been deleted.',
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to delete the request.',
                                    });
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                Swal.close();
                                console.error('AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'There was an error deleting the record.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>



</body>

</html>