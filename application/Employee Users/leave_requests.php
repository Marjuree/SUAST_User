<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../../configuration/config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}

session_regenerate_id(true);

$employee_id = $_SESSION['employee_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Employee";

// Get the latest leave request info
$query = "SELECT current_stage, faculty, 
                 hr_submitted, hr_received, 
                 vp_acad_submitted, vp_acad_received, 
                 vp_finance_submitted, vp_finance_received, 
                 hr_received_submitted, hr_received_received, 
                 for_releasing_submitted, for_releasing_received, 
                 completed_date
          FROM tbl_leave_requests
          WHERE employee_id = ? 
          ORDER BY created_at DESC 
          LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$stmt->bind_result(
    $current_stage,
    $faculty,
    $hr_submitted,
    $hr_received,
    $vp_acad_submitted,
    $vp_acad_received,
    $vp_finance_submitted,
    $vp_finance_received,
    $hr_received_submitted,
    $hr_received_received,
    $for_releasing_submitted,
    $for_releasing_received,
    $completed_date
);
$stmt->fetch();
$stmt->close();

$current_stage = trim($current_stage ?? '');
$faculty = trim($faculty ?? '');


// Define roles per faculty
$roles = (strtolower($faculty) === 'faculty')
    ? ['HR', 'VP ACAD', 'HR Received', 'For Releasing', 'Completed']
    : ['HR', 'VP Finance', 'HR Received', 'For Releasing', 'Completed'];

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
    <title>Employees | Dashboard</title>
    <link href="../../css/button.css" rel="stylesheet" />
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
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

        th.leave-form-column,
        td.leave-form-column {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                <h1>Leave Request</h1>
                <p>Welcome, <strong><?= $first_name ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <h4>Leave Request Progress</h4>
                            <p><strong><?= htmlspecialchars($faculty) ?>:</strong></p>
                            <div class="progress-tracker-wrapper">
                                <ul class="progress-tracker">
                                    <?php
                                    foreach ($roles as $index => $role):
                                        $statusClass = ($index < $currentIndex) ? 'done' : (($index === $currentIndex) ? 'current' : '');
                                        ?>
                                        <li class="<?= $statusClass ?>" title="<?= htmlspecialchars($role) ?>">
                                            <span><?= htmlspecialchars($role) ?></span>

                                            <?php if ($role === 'HR'): ?>
                                                <small>SUBMITTED: <?= formatStageTime($hr_submitted) ?></small><br>

                                            <?php elseif ($role === 'VP Finance'): ?>
                                                <small>FORWARDED: <?= formatStageTime($vp_finance_submitted) ?></small><br>

                                            <?php elseif ($role === 'HR Received'): ?>
                                                <small>RECEIVED: <?= formatStageTime($hr_received_received) ?></small>

                                            <?php elseif ($role === 'For Releasing'): ?>
                                                <span style="color:transparent;">
                                                    SUBMITTED: <br>
                                                </span>

                                            <?php elseif ($role === 'Completed'): ?>
                                                <small>RECEIVED: <?= formatStageTime($for_releasing_received) ?></small>

                                            <?php endif; ?>
                                        </li>

                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>


                            <?php if ($current_stage === 'For Releasing'): ?>
                                <div
                                    style="margin-bottom: 20px; padding: 15px 20px; background-color: #e6f9ed; border-left: 6px solid #2ecc71; border-radius: 8px; display: flex; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                    <span style="font-size: 24px; color: #2ecc71; margin-right: 12px;">✅</span>
                                    <span style="color: #2c3e50; font-weight: 500; font-size: 16px;">
                                        Your leave request is <strong>ready to cliam!</strong>!
                                    </span>
                                </div>
                            <?php endif; ?>

                            <hr>
                            <!-- Submit Leave Button -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#requestLeaveApplication">
                                Submit Leave
                            </button>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Faculty/Institute</th>
                                            <th>Request Type</th>
                                            <!-- <th>Leave Date</th>
                                            <th>End of Leave</th> -->
                                            <th>Current Stage</th>
                                            <th>Completion</th> <!-- Added column for Status -->
                                            <th>Approval</th> <!-- Added column for Status -->
                                            <th class="leave-form-column">Leave Form</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query to fetch leave requests and their statuses
                                        $stmt = $con->prepare("SELECT * FROM tbl_leave_requests WHERE employee_id = ? ORDER BY created_at DESC");
                                        $stmt->bind_param("i", $employee_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        while ($row = $result->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['faculty']) ?></td>
                                                <td><?= htmlspecialchars($row['request_type']) ?></td>
                                                <!-- <td><?= htmlspecialchars($row['date_request']) ?></td>
                                                <td><?= htmlspecialchars($row['leave_end_date']) ?></td> -->
                                                <td><?= htmlspecialchars($row['current_stage']) ?></td>
                                                <td>
                                                    <?php
                                                    // Display status with corresponding label color
                                                    $status = htmlspecialchars($row['status']);
                                                    if ($status === 'done') {
                                                        echo "<span class='label label-success'>Completed</span>";
                                                    } elseif ($status === 'approved') {
                                                        echo "<span class='label label-primary'>Approved</span>";
                                                    } else {
                                                        echo "<span class='label label-warning'>Pending</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    // Display approval_status with corresponding color
                                                    $approval_status = htmlspecialchars($row['approval_status']);
                                                    if ($approval_status === 'Approved') {
                                                        echo "<span class='label label-success'>$approval_status</span>";
                                                    } elseif ($approval_status === 'Disapproved') {
                                                        echo "<span class='label label-danger'>$approval_status</span>";
                                                    } else { // For Pending or other statuses
                                                        echo "<span class='label label-warning'>$approval_status</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="leave-form-column">
                                                    <?php
                                                    // Check if leave form exists and provide a download link
                                                    $leave_form = !empty($row['leave_form'])
                                                        ? "<a href='download_leave_form.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary' target='_blank' title='Download Leave Form'>" . htmlspecialchars($row['file_name']) . "</a>"
                                                        : "<span class='text-muted'>No form</span>";
                                                    echo $leave_form;
                                                    ?>
                                                </td>


                                                <td>
                                                    <button class="btn btn-sm btn-info edit-btn" data-toggle="modal"
                                                        data-target="#editLeaveModal" data-id="<?= $row['id'] ?>"
                                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                                        data-faculty="<?= htmlspecialchars($row['faculty']) ?>"
                                                        data-request_type="<?= htmlspecialchars($row['request_type']) ?>"
                                                        data-leave_date="<?= htmlspecialchars($row['date_request']) ?>"
                                                        data-leave_end_date="<?= htmlspecialchars($row['leave_end_date']) ?>"
                                                        data-leave_form_name="<?= htmlspecialchars($row['file_name']) ?>"
                                                        title="Edit Leave Request">
                                                        Edit
                                                    </button>

                                                    <button class="btn btn-sm btn-danger delete-btn"
                                                        data-id="<?= $row['id'] ?>" title="Delete Leave Request">
                                                        Delete
                                                    </button>
                                                </td>



                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>


                            <!-- Edit Leave Modal -->
                            <div class="modal fade" id="editLeaveModal" tabindex="-1" role="dialog"
                                aria-labelledby="editLeaveModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="editLeaveModalLabel">Edit Leave Request</h4>
                                        </div>

                                        <!-- Modal Body with form -->
                                        <div class="modal-body">
                                            <form id="editLeaveForm" enctype="multipart/form-data">

                                                <input type="hidden" name="id" id="edit-id">
                                                <input type="hidden" name="request_type" value="Leave Processing">

                                                <div class="form-group">
                                                    <label for="edit-name">Full Name:</label>
                                                    <input type="text" class="form-control" id="edit-name" readonly>
                                                    <input type="hidden" name="name" id="edit-name-hidden">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-faculty">Position:</label>
                                                    <input type="text" class="form-control" id="edit-faculty" readonly>
                                                    <input type="hidden" name="faculty" id="edit-faculty-hidden">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-leave-date">Leave Date:</label>
                                                    <input type="date" name="leave_date" id="edit-leave-date" required
                                                        class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-leave-end-date">End of Leave:</label>
                                                    <input type="date" name="leave_end_date" id="edit-leave-end-date"
                                                        required class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-leave-form">
                                                        Upload New Leave Form (optional):
                                                    </label>
                                                    <input type="file" name="leave_form" id="edit-leave-form"
                                                        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip"
                                                        class="form-control" multiple>
                                                    <p class="help-block">Accepted formats: JPG, JPEG, PNG, GIF, PDF,
                                                        DOC, DOCX</p>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </form>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Leave Processing Request Modal -->
                            <div class="modal fade" id="requestLeaveApplication" tabindex="-1" role="dialog"
                                aria-labelledby="requestLeaveApplicationLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="requestLeaveApplicationLabel">Request Leave
                                                Processing</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="process_leave.php" method="POST"
                                                enctype="multipart/form-data">
                                                <input type="hidden" name="request_type" value="Leave Processing">

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
                                                    <label for="leave_date">Leave Date:</label> <!-- Leave Date -->
                                                    <input type="date" name="leave_date" required class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="leave_end_date">End of Leave:</label>
                                                    <!-- End of Leave -->
                                                    <input type="date" name="leave_end_date" required
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="leave_form">
                                                        Upload CSC Application for Leave Form (CS Form No. 6, Revised
                                                        2020):
                                                    </label>
                                                    <input type="file" name="leave_form" id="leave_form"
                                                        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip" required
                                                        class="form-control">
                                                    <p class="help-block">
                                                        Accepted formats: JPG, JPEG, PNG, GIF, PDF, DOC, DOCX, ZIP
                                                    </p>
                                                </div>




                                                <button type="submit" class="btn btn-primary">Submit Request</button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
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
    <script>
        $(document).ready(function () {
            // Fill in the edit modal with data when "Edit" is clicked
            $('.edit-btn').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var faculty = $(this).data('faculty');
                var leave_date = $(this).data('leave_date');
                var leave_end_date = $(this).data('leave_end_date');
                var fileName = $(this).data('file_name');

                $('#edit-id').val(id);
                $('#edit-name').val(name);
                $('#edit-name-hidden').val(name);
                $('#edit-faculty').val(faculty);
                $('#edit-faculty-hidden').val(faculty);
                $('#edit-leave-date').val(leave_date);
                $('#edit-leave-end-date').val(leave_end_date);

                // Show current leave form file name with download link or message
                if (fileName) {
                    $('#current-leave-form').html(
                        `<a href="download_leave_form.php?id=${id}" target="_blank">${fileName}</a>`
                    );
                } else {
                    $('#current-leave-form').text('No file uploaded');
                }
            });

            // Submit the form with AJAX
            $('#editLeaveModal form').submit(function (e) {
                e.preventDefault(); // prevent default form submit

                Swal.fire({
                    title: 'Updating...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                var formData = new FormData(this);

                $.ajax({
                    url: 'edit_leave_request.php', // your PHP script
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Leave request updated successfully.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reload page to reflect changes
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Please try again!'
                        });
                    }
                });
            });
        });

        $(document).on('click', '.delete-btn', function () {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the leave request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'delete_leave_request.php',
                        type: 'POST',
                        data: { id: id },
                        success: function (response) {
                            Swal.fire(
                                'Deleted!',
                                'The leave request has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload(); // reload page to update table
                            });
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'Something went wrong while deleting.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

    </script>


    <?php require_once "../../includes/footer.php"; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
