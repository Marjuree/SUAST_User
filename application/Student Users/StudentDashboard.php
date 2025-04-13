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

// Debugging: Log student data
error_log("Fetched student data: " . print_r($student_data, true));
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
    <style>
    .form-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: bold;
        color: #495057;
    }

    .form-group input,
    .form-group span {
        font-size: 1rem;
        border-radius: 30px !important;
    }

    .badge {
        padding: 0.4rem 1rem;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 25px;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .bg-info {
        background-color: #17a2b8 !important;
        color: #fff !important;
    }

    .bg-success {
        background-color: #28a745 !important;
        color: #fff !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
</style>

</head>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Student Dashboard</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Button to Open Modal -->
                        <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                            data-target="#requestClearanceModal">
                            Request Clearance
                        </button>

                        <!-- Request Clearance Section -->
                        <div id="requestClearance" class="toggle-section" style="display: none;">
                            <h3>Clearance Requests</h3>

                            <?php
                            $query = "SELECT id, student_id, status, date_requested FROM tbl_clearance_requests WHERE student_id = ? ORDER BY date_requested DESC";
                            $stmt = $con->prepare($query);
                            $stmt->bind_param('s', $student_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()): 
                                $status = !empty($row['status']) ? htmlspecialchars($row['status']) : "Pending";

                                // Badge class logic
                                switch (strtolower($status)) {
                                    case 'Approved':
                                        $badgeClass = 'success'; // Green
                                        break;
                                    case 'Rejected':
                                        $badgeClass = 'danger';  // Red
                                        break;
                                    default:
                                        $badgeClass = 'secondary'; // Gray for pending or others
                                }
                            ?>
                            <div class="form-container">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="student_id_<?= $row['id'] ?>"
                                            class="col-sm-3 col-form-label">Student ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="student_id_<?= $row['id'] ?>"
                                                value="<?= htmlspecialchars($row['student_id']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="date_requested_<?= $row['id'] ?>"
                                            class="col-sm-3 col-form-label">Date Requested</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"
                                                id="date_requested_<?= $row['id'] ?>"
                                                value="<?= htmlspecialchars($row['date_requested']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status_<?= $row['id'] ?>"
                                            class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <span class="badge bg-<?= $badgeClass ?>"><?= $status ?></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php endwhile; ?>
                        </div>

                        <!-- Display Logged-In Student User's Data as a Form -->
                        <div id="studentUser" class="toggle-section" style="display: block;">
                            <h3>Student Information</h3>

                            <?php if ($student_data): ?>
                            <div class="form-container">
                                <form class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="full_name_<?= $student_data['id'] ?>"
                                            class="col-sm-3 col-form-label">Full Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"
                                                id="full_name_<?= $student_data['id'] ?>"
                                                value="<?= htmlspecialchars($student_data['full_name']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email_<?= $student_data['id'] ?>"
                                            class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control"
                                                id="email_<?= $student_data['id'] ?>"
                                                value="<?= htmlspecialchars($student_data['email']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="school_id_<?= $student_data['id'] ?>"
                                            class="col-sm-3 col-form-label">School ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"
                                                id="school_id_<?= $student_data['id'] ?>"
                                                value="<?= htmlspecialchars($student_data['school_id']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="balance_<?= $student_data['id'] ?>"
                                            class="col-sm-3 col-form-label">Balance</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control"
                                                id="balance_<?= $student_data['id'] ?>"
                                                value="<?= htmlspecialchars($student_data['balance']) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status_<?= $student_data['id'] ?>"
                                            class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-9">
                                            <span
                                                class="badge bg-info"><?= htmlspecialchars($student_data['status']) ?: 'Not Set' ?></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <?php else: ?>
                            <p>No student data found.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </section>
        </aside>
    </div>

    <?php require_once "modal.php"; ?>
    <?php require_once "../../includes/footer.php"; ?>

    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
</body>

</html>