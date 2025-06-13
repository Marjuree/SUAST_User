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
    'Prepare Service Record',
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Employees | Dash</title>
    <link href="../../css/button.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>
<style>
    /* Desktop and general layout */
    .progress-tracker {
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 40px 0;
        list-style: none;
        position: relative;
        font-family: 'Poppins', sans-serif;
    }

    /* Step item */
    .progress-tracker li {
        position: relative;
        padding-left: 60px;
        /* space for left-aligned circle */
        margin-bottom: 30px;
    }

    /* Default circle */
    .progress-tracker li::before {
        content: counter(step);
        counter-increment: step;
        position: absolute;
        top: 0;
        left: 0;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        background: #fff;
        border: 3px solid #ccc;
        border-radius: 50%;
        color: #ccc;
        font-weight: bold;
    }

    /* DONE state: checkmark */
    .progress-tracker li.done::before {
        content: "✔";
        background-color: #4CAF50;
        border-color: #4CAF50;
        color: white;
        font-size: 18px;
        margin-top: 10px;
        width: 20px;
        height: 20px;
    }

    /* CURRENT state */
    .progress-tracker li.current::before {
        background-color: #4CAF50;
        border-color: #4CAF50;
        color: white;
    }

    /* Text content */
    .progress-tracker li span {
        display: block;
        margin-top: 5px;
        color: #666;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }

    .progress-tracker li small {
        font-size: 12px;
        color: #666;
        display: block;
        margin-top: 2px;
        line-height: 1.3;
    }


    /* Mobile view */
    @media (max-width: 600px) {
        .progress-tracker-wrapper {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-left: 40px;
            background-color: #fafafa;
            border-radius: 8px;
            padding-top: 20px;
            padding-bottom: 20px;
        }



        .progress-tracker {
            flex-direction: column;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .progress-tracker li {
            position: relative;
            padding-left: 50px;
            margin-bottom: 30px;
        }

        .progress-tracker li::before {
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            background-color: #ccc;
            border: 3px solid #ccc;
            border-radius: 50%;
            line-height: 20px;
            font-size: 12px;
            text-align: center;
        }

        .progress-tracker li.done::before {
            content: "✔";
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .progress-tracker li.current::before {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .progress-tracker li span {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .progress-tracker li small {
            font-size: 12px;
            color: #666;
        }

        .progress-tracker li:last-child {
            margin-bottom: 0;
        }
    }


    .content-header h1,
    p {
        font-family: 'Poppins', sans-serif;
    }

    .form-group {
        font-family: 'Poppins', sans-serif;
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


            <section class="content">
                <div class="row">
                    <div class="box">
                        <a href="EmployeeDashboard.php" class="btn btn-primary mb-3"
                            style="display: inline-block; font-size: 1.5rem; background: transparent; border: none; color: #003366;">
                            <i class="fas fa-arrow-left curved"></i>
                        </a>
                        <div class="box-body">
                            <section class="content-header text-center">
                                <h1>Services Record Request</h1>
                                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
                            </section>
                            <p><strong><?= htmlspecialchars($faculty) ?>:</strong></p>
                            <div class="progress-tracker-wrapper">

                                <ul class="progress-tracker">
                                    <?php
                                    foreach ($roles as $index => $role):
                                        $statusClass = ($index < $currentIndex) ? 'done' : (($index === $currentIndex) ? 'current' : '');
                                        ?>
                                        <li class="<?= $statusClass ?>" title="<?= htmlspecialchars($role) ?>">
                                            <span><?= htmlspecialchars($role) ?></span>

                                            <?php if ($role === 'Present Request'): ?>
                                                <small>SUBMITTED: <?= formatStageTime($present_request_submitted) ?></small><br>
                                            <?php elseif ($role === 'Prepare Service Record'): ?>
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
                                <!-- <div
                                    style="margin-bottom: 20px; padding: 15px 20px; background-color: #e6f9ed; border-left: 6px solid #2ecc71; border-radius: 8px; display: flex; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                    <span style="font-size: 24px; color: #2ecc71; margin-right: 12px;">✅</span>
                                    <span style="color: #2c3e50; font-weight: 500; font-size: 16px;">
                                        Your Service request is <strong>ready for release</strong>!
                                    </span>
                                </div> -->
                            <?php endif; ?>

                            <hr>

                            <!-- Submit Leave Button -->
                            <!-- <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#requestServiceRecord">
                                Submit Leave
                            </button> -->
                            <!-- Service Requests Table -->


                            <div id="serviceRequests">
                                <div
                                    style="position: absolute; border-top-left-radius: 30px;
                                     border-top-right-radius: 30px; height: 70px; background-color: #003366; z-index: 0; margin-top: -50px;  max-width: 350px; width: 337px;">
                                    <h5
                                        style="color: #fff; text-align: left; line-height: 60px; margin: 0 0 0 20px; font-size: 14px; font-weight: bold;">
                                        <i class="fas fa-file-alt" style="margin-right: 8px;"></i>Complete Details
                                    </h5>
                                </div>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM tbl_service_requests WHERE employee_id = ? ORDER BY created_at DESC");
                                $stmt->bind_param("i", $employee_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = $result->fetch_assoc()):
                                    // Prepare display values or fallback to 'N/A'
                                    $name = !empty($row['name']) ? htmlspecialchars($row['name']) : 'N/A';
                                    $requestType = !empty($row['request_type']) ? htmlspecialchars($row['request_type']) : 'N/A';
                                    $dateRequest = !empty($row['date_request']) ? htmlspecialchars($row['date_request']) : 'N/A';
                                    $faculty = !empty($row['faculty']) ? htmlspecialchars($row['faculty']) : 'N/A';
                                    $reason = !empty($row['reason']) ? htmlspecialchars($row['reason']) : 'N/A';
                                    $currentStage = !empty($row['current_stage']) ? ucfirst(strtolower($row['current_stage'])) : 'N/A';

                                    // Approval status
                                    $approvalStatusRaw = $row['approval_status'] ?? 'Pending';
                                    $approvalStatus = ucfirst(strtolower($approvalStatusRaw));
                                    $approvalBgColor = 'transparent';
                                    if ($approvalStatus === 'Approved') {
                                        $approvalBgColor = 'lightgreen';
                                    } elseif ($approvalStatus === 'Disapproved') {
                                        $approvalBgColor = '#f8d7da';
                                    } elseif ($approvalStatus === 'Pending') {
                                        $approvalBgColor = 'orange';
                                    }

                                    // Completion status
                                    $completionStatusRaw = $row['completion_status'] ?? 'Pending';
                                    $completionStatus = ucfirst(strtolower($completionStatusRaw));
                                    $completionBgColor = 'transparent';
                                    if ($completionStatus === 'Completed') {
                                        $completionBgColor = '#add8e6';
                                    } elseif ($completionStatus === 'Done') {
                                        $completionBgColor = 'lightgreen';
                                    } elseif ($completionStatus === 'Pending') {
                                        $completionBgColor = 'orange';
                                    }

                                    // Attachment
                                    $attachmentHtml = !empty($row['attachment'])
                                        ? "<a href='download_service.php?id={$row['id']}' class='form-control' style='font-size: 12px; height: 30px; line-height: 30px; color: #337ab7; text-decoration: underline; cursor: pointer; width: 230px; border-radius: 50px !important; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;' target='_blank' title='Download Service File'>"
                                        . htmlspecialchars($row['file_name']) .
                                        "</a>"
                                        : "<input type='text' class='form-control' readonly value='No file' style='font-size: 12px; height: 30px; width: 230px; border-radius: 50px !important; text-align: center;'>";

                                    ?>

                                    <!-- Card container -->
                                    <div class="card shadow-sm mb-4"
                                        style="border-radius: 20px; border: 1px solid #003366; max-width: 350px; margin: auto; position: relative; background-color: #f9f9f9;">
                                        <div class="card-body"
                                            style="padding: 20px; background-color: rgba(255,255,255,0.95); border-radius: 20px;">
                                            <form class="form-horizontal" style="margin-top: 10px;">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label" style="font-size: 12px;">Full
                                                        Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important;"
                                                            value="<?= $name ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label" style="font-size: 12px;">Request
                                                        Type</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important;"
                                                            value="<?= $requestType ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label" style="font-size: 12px;">Date of
                                                        Request</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important;"
                                                            value="<?= $dateRequest ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label"
                                                        style="font-size: 12px;">Faculty/Institute</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important;"
                                                            value="<?= $faculty ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label"
                                                        style="font-size: 12px;">Reason</label>
                                                    <div class="col-sm-8">
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-toggle="modal" data-target="#reasonModal<?= $row['id'] ?>"
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important; padding: 0 15px;">
                                                            View Reason
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label" style="font-size: 12px;">Current
                                                        Stage</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; border-radius: 50px !important; text-align:center;"
                                                            value="<?= $currentStage ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label"
                                                        style="font-size: 12px;">Approval</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; color: black; background-color: <?= $approvalBgColor ?>; border-radius: 50px !important; text-align:center;"
                                                            value="<?= $approvalStatus ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label"
                                                        style="font-size: 12px;">Completion</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" readonly
                                                            style="font-size: 12px; height: 30px; color: black; background-color: <?= $completionBgColor ?>; border-radius: 50px !important; text-align:center;"
                                                            value="<?= $completionStatus ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 control-label" style="font-size: 12px;">Service
                                                        File</label>
                                                    <div class="col-sm-8">
                                                        <?= $attachmentHtml ?>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-12 text-left">
                                                        <button class="btn btn-sm btn-info edit-btn"
                                                            data-id="<?= $row['id'] ?>" data-name="<?= $name ?>"
                                                            data-request_type="<?= $requestType ?>"
                                                            data-date_request="<?= $dateRequest ?>"
                                                            data-faculty="<?= $faculty ?>"
                                                            data-reason="<?= htmlspecialchars($row['reason']) ?>"
                                                            title="Edit Request"
                                                            style="font-size: 12px; height: 30px; width: 80px; margin-right: 10px;">
                                                            Edit
                                                        </button>

                                                        <button class="btn btn-sm btn-danger delete-btn"
                                                            data-id="<?= $row['id'] ?>" title="Delete Request"
                                                            style="font-size: 12px; height: 30px; width: 80px;">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <style>
                                        .edit-btn {
                                            background-color: #003366 !important;
                                            border-radius: 50px !important;
                                            transition: background-color 0.3s ease, box-shadow 0.2s ease;
                                        }

                                        .edit-btn:hover,
                                        .edit-btn:focus {
                                            background-color: #002244 !important;
                                            cursor: pointer;
                                            box-shadow: 0 0 8px #003366;
                                        }

                                        .edit-btn:active {
                                            background-color: #001122 !important;
                                            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.5);
                                        }

                                        .delete-btn {
                                            background-color: #dc3545 !important;
                                            border-radius: 50px !important;
                                            transition: background-color 0.3s ease, box-shadow 0.2s ease;
                                        }

                                        .delete-btn:hover,
                                        .delete-btn:focus {
                                            background-color: #b02a37 !important;
                                            cursor: pointer;
                                            box-shadow: 0 0 8px #dc3545;
                                        }

                                        .delete-btn:active {
                                            background-color: #7a1e23 !important;
                                            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.5);
                                        }
                                    </style>
                                    <!-- Reason Modal -->
                                    <div class="modal fade" id="reasonModal<?= $row['id'] ?>" tabindex="-1" role="dialog"
                                        aria-labelledby="reasonModalLabel<?= $row['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content" style="font-size: 14px;">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="reasonModalLabel<?= $row['id'] ?>">Reason
                                                        for Request</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close" style="font-size: 24px;">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?= nl2br($reason === 'N/A' ? 'No reason provided.' : $reason) ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endwhile; ?>



                            </div>
                        </div>
                    </div>
            </section>
        </aside>
    </div>



    <!-- Edit Service Request Modal -->
    <div class="modal fade" id="editServiceRequestModal" tabindex="-1" role="dialog"
        aria-labelledby="editServiceRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editServiceRequestForm" method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- Bootstrap 3 uses .close with &times; but no aria-label by default -->
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="editServiceRequestModalLabel">Edit Service
                            Request</h4>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-service-id">

                        <div class="form-group">
                            <label for="edit-service-name">Full Name</label>
                            <input type="text" class="form-control" id="edit-service-name" readonly>
                        </div>

                        <div class="form-group">
                            <label for="edit-service-request-type">Request Type</label>
                            <input type="text" class="form-control" id="edit-service-request-type" readonly>
                        </div>

                        <div class="form-group">
                            <label for="edit-service-date-request">Date of Request</label>
                            <input type="date" class="form-control" name="date_request" id="edit-service-date-request"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="edit-service-faculty">Faculty/Institute</label>
                            <input type="text" class="form-control" id="edit-service-faculty" readonly>
                        </div>

                        <div class="form-group">
                            <label for="edit-service-reason">Reason</label>
                            <textarea class="form-control" name="reason" id="edit-service-reason" rows="4"
                                required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit-service-file">Upload New Service File
                                (optional)</label>
                            <input type="file" name="service_file[]" id="edit-service-file" class="form-control"
                                multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip">
                            <small class="help-block">Accepted formats: JPG, JPEG, PNG, GIF,
                                PDF, DOC, DOCX, ZIP</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
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
                            <input type="text" class="form-control" value="<?= htmlspecialchars($faculty) ?>" readonly>
                            <input type="hidden" name="faculty" value="<?= htmlspecialchars($faculty) ?>">
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
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/chart.js"></script>
    <!-- jQuery (required for Bootstrap 3) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 3 JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Edit button click handler
            $('.edit-btn').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var requestType = $(this).data('request_type');
                var dateRequest = $(this).data('date_request');
                var faculty = $(this).data('faculty');
                var reason = $(this).data('reason');

                // Fill the modal fields
                $('#edit-service-id').val(id);
                $('#edit-service-name').val(name);
                $('#edit-service-request-type').val(requestType);
                $('#edit-service-date-request').val(dateRequest);
                $('#edit-service-faculty').val(faculty);
                $('#edit-service-reason').val(reason);

                // Show the modal
                $('#editServiceRequestModal').modal('show');
            });

            // Submit edit form with AJAX
            $('#editServiceRequestForm').submit(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: 'edit_service_request.php', // Your PHP script to handle the edit
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        Swal.close();
                        if (response.success) {
                            Swal.fire('Success', 'Request updated successfully!', 'success').then(() => {
                                $('#editServiceRequestModal').modal('hide');
                                location.reload(); // Reload to show updated data
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

            // Handle delete button click
            $('.delete-btn').click(function () {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        $.ajax({
                            url: 'delete_service_request.php', // Your PHP script to handle the delete
                            type: 'POST',
                            data: { id: id },
                            dataType: 'json',
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    Swal.fire('Deleted!', 'Request has been deleted.', 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', response.message || 'Delete failed', 'error');
                                }
                            },
                            error: function () {
                                Swal.close();
                                Swal.fire('Error', 'AJAX error occurred', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
