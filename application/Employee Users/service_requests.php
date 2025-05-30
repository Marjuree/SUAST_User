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
                                                <th>Approval</th>
                                                <th>Completion Status</th>
                                                <th>Service File</th>
                                                <th>Action</th> <!-- Changed from Created At -->
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
                                                if (strtolower($status) === 'approved')
                                                    $status_class = 'label-success';
                                                elseif (strtolower($status) === 'disapproved')
                                                    $status_class = 'label-danger';

                                                $completion_class = 'label-warning';
                                                if (strtolower($completion) === 'completed')
                                                    $completion_class = 'label-primary';
                                                elseif (strtolower($completion) === 'done')
                                                    $completion_class = 'label-success';

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

                                                    <!-- Action buttons -->
                                                    <td>
                                                        <button class="btn btn-sm btn-info edit-btn"
                                                            data-id="<?= $row['id'] ?>"
                                                            data-name="<?= htmlspecialchars($row['name']) ?>"
                                                            data-request_type="<?= htmlspecialchars($row['request_type']) ?>"
                                                            data-date_request="<?= htmlspecialchars($row['date_request']) ?>"
                                                            data-faculty="<?= htmlspecialchars($row['faculty']) ?>"
                                                            data-reason="<?= htmlspecialchars($row['reason']) ?>"
                                                            title="Edit Request">
                                                            Edit
                                                        </button>

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
                            </div>

                            <!-- Edit Service Request Modal -->
                            <div class="modal fade" id="editServiceRequestModal" tabindex="-1" role="dialog"
                                aria-labelledby="editServiceRequestModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form id="editServiceRequestForm" method="POST" enctype="multipart/form-data">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <!-- Bootstrap 3 uses .close with &times; but no aria-label by default -->
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="editServiceRequestModalLabel">Edit Service
                                                    Request</h4>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="edit-service-id">

                                                <div class="form-group">
                                                    <label for="edit-service-name">Full Name</label>
                                                    <input type="text" class="form-control" id="edit-service-name"
                                                        readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-service-request-type">Request Type</label>
                                                    <input type="text" class="form-control"
                                                        id="edit-service-request-type" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-service-date-request">Date of Request</label>
                                                    <input type="date" class="form-control" name="date_request"
                                                        id="edit-service-date-request" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-service-faculty">Faculty/Institute</label>
                                                    <input type="text" class="form-control" id="edit-service-faculty"
                                                        readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-service-reason">Reason</label>
                                                    <textarea class="form-control" name="reason"
                                                        id="edit-service-reason" rows="4" required></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit-service-file">Upload New Service File
                                                        (optional)</label>
                                                    <input type="file" name="service_file[]" id="edit-service-file"
                                                        class="form-control" multiple
                                                        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip">
                                                    <small class="help-block">Accepted formats: JPG, JPEG, PNG, GIF,
                                                        PDF, DOC, DOCX, ZIP</small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
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
