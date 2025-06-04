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
            .progress-tracker-wrapper {
                overflow-x: auto;
                overflow-y: hidden;
                /* Only scroll horizontally */
                -webkit-overflow-scrolling: touch;
                margin-bottom: 20px;
                width: 100%;
            }

            .progress-tracker {
                display: flex;
                gap: 20px;
                padding: 0;
                margin: 0;
                list-style: none;
                min-width: max-content;
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

                            <!-- Submit Leave Button -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#requestCertification">
                                Submit Leave
                            </button>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-default">
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Request Type</th>
                                            <th>Date of Request</th>
                                            <th>Position</th>
                                            <th>Reason</th>
                                            <th>Current Stage</th>
                                            <th>Approval</th>
                                            <th>Completion Status</th>
                                            <th>Certification File</th>
                                            <th>Action</th> <!-- Added action column -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $con->prepare("SELECT * FROM tbl_certification_requests WHERE employee_id = ? ORDER BY created_at DESC");
                                        $stmt->bind_param("i", $employee_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        while ($row = $result->fetch_assoc()):
                                            $stage = strtolower($row['current_stage']);
                                            $stage_display = ucfirst($stage);

                                            $file_link = !empty($row['certification_file'])
                                                ? "<a href='download_certification_file.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary' target='_blank' title='Download " . htmlspecialchars($row['file_name']) . "'>" . htmlspecialchars($row['file_name']) . "</a>"
                                                : '<span class="text-muted">No file</span>';

                                            $request_status = !empty($row['request_status']) ? ucfirst($row['request_status']) : 'Pending';
                                            $approval_bg = '';
                                            switch (strtolower($row['request_status'])) {
                                                case 'approved':
                                                    $approval_bg = 'label-success';
                                                    break;
                                                case 'disapproved':
                                                    $approval_bg = 'label-danger';
                                                    break;
                                                default:
                                                    $approval_bg = 'label-warning';
                                                    break;
                                            }

                                            $completion_status = !empty($row['completion_status']) ? ucfirst($row['completion_status']) : 'Pending';
                                            $completion_bg = '';
                                            switch (strtolower($row['completion_status'])) {
                                                case 'completed':
                                                    $completion_bg = 'label-primary';
                                                    break;
                                                case 'done':
                                                    $completion_bg = 'label-success';
                                                    break;
                                                default:
                                                    $completion_bg = 'label-warning';
                                                    break;
                                            }
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                <td><?= htmlspecialchars($row['faculty']) ?></td>
                                                <td>
                                                    <!-- Reason Modal Button -->
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                        data-target="#reasonModal<?= $row['id'] ?>">
                                                        View Reason
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="reasonModal<?= $row['id'] ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="reasonModalLabel<?= $row['id'] ?>">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    <h4 class="modal-title"
                                                                        id="reasonModalLabel<?= $row['id'] ?>">Reason for
                                                                        Request</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?= nl2br(htmlspecialchars($row['reason'])) ?>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td><span><?= $stage_display ?></span></td>
                                                <td><span class="label <?= $approval_bg ?>"><?= $request_status ?></span>
                                                </td>
                                                <td><span
                                                        class="label <?= $completion_bg ?>"><?= $completion_status ?></span>
                                                </td>
                                                <td><?= $file_link ?></td>

                                                <td>
                                                    <!-- Edit button -->
                                                    <button class="btn btn-sm btn-info edit-btn" data-id="<?= $row['id'] ?>"
                                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                                        data-request_type="<?= htmlspecialchars($row['request_type']) ?>"
                                                        data-date_request="<?= htmlspecialchars($row['date_request']) ?>"
                                                        data-faculty="<?= htmlspecialchars($row['faculty']) ?>"
                                                        data-reason="<?= htmlspecialchars($row['reason']) ?>"
                                                        title="Edit Request">
                                                        Edit
                                                    </button>



                                                    <!-- Delete form with confirmation -->
                                                    <button class="btn btn-sm btn-danger delete-btn"
                                                        data-id="<?= $row['id'] ?>" title="Delete Request">
                                                        Delete
                                                    </button>

                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>


                            <!-- Edit Certification Modal -->
                            <div class="modal fade" id="editCertificationModal" tabindex="-1" role="dialog"
                                aria-labelledby="editCertificationModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form id="editCertificationForm" enctype="multipart/form-data" method="POST"
                                        action="edit_certification_request.php">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCertificationModalLabel">Edit
                                                    Certification Request</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="edit-certification-id">

                                                <div class="form-group">
                                                    <label for="edit-certification-name">Full Name</label>
                                                    <input type="text" class="form-control" id="edit-certification-name"
                                                        readonly>
                                                    <input type="hidden" name="name"
                                                        id="edit-certification-name-hidden">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-certification-request-type">Request Type</label>
                                                    <input type="text" class="form-control"
                                                        id="edit-certification-request-type" readonly>
                                                    <input type="hidden" name="request_type"
                                                        id="edit-certification-request-type-hidden">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-certification-date-request">Date of Request</label>
                                                    <input type="date" name="date_request"
                                                        id="edit-certification-date-request" class="form-control"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-certification-faculty">Position</label>
                                                    <input type="text" class="form-control"
                                                        id="edit-certification-faculty" readonly>
                                                    <input type="hidden" name="faculty"
                                                        id="edit-certification-faculty-hidden">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-certification-reason">Reason</label>
                                                    <textarea name="reason" id="edit-certification-reason"
                                                        class="form-control" rows="4" required></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-certification-file">Upload New Certification File
                                                        (optional)</label>
                                                    <input type="file" name="certification_file"
                                                        id="edit-certification-file"
                                                        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip"
                                                        class="form-control" multiple>
                                                    <small class="form-text text-muted">Accepted formats: JPG, JPEG,
                                                        PNG, GIF, PDF, DOC, DOCX, ZIP</small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <!-- Certification Request -->
                            <div class="modal fade" id="requestCertification" tabindex="-1" role="dialog"
                                aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Request Certification</h4>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Added id="certificationForm" -->
                                            <form id="certificationForm" enctype="multipart/form-data" method="POST">
                                                <input type="hidden" name="request_type" value="Certification">

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
                                                    <label>Date of Request:</label>
                                                    <input type="date" name="date_request" required
                                                        class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label>Reason for Request:</label>
                                                    <textarea name="reason" required class="form-control"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="certification_file">Upload Certification Document (Image
                                                        or File):</label>
                                                    <input type="file" name="certification_file" id="certification_file"
                                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip"
                                                        class="form-control">
                                                    <p class="help-block">Accepted formats: JPG, PNG, PDF, DOC, DOCX,
                                                        ZIP</p>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Submit
                                                        Request</button>
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
                </div>
            </section>
        </aside>
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
            $('.edit-btn').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var requestType = $(this).data('request_type');
                var dateRequest = $(this).data('date_request');
                var faculty = $(this).data('faculty');
                var reason = $(this).data('reason');

                // Fill the modal fields
                $('#edit-certification-id').val(id);
                $('#edit-certification-name').val(name);
                $('#edit-certification-name-hidden').val(name);
                $('#edit-certification-request-type').val(requestType);
                $('#edit-certification-request-type-hidden').val(requestType);
                $('#edit-certification-date-request').val(dateRequest);
                $('#edit-certification-faculty').val(faculty);
                $('#edit-certification-faculty-hidden').val(faculty);
                $('#edit-certification-reason').val(reason);

                // Show Bootstrap 3 modal
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
