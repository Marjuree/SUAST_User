<?php
require_once "../../configuration/config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Employee') {
    header("Location: ../../php/error.php?welcome=Please login as an employee");
    exit();
}

session_regenerate_id(true);

$employee_id = $_SESSION['employee_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Employee";

// Fetch the leave request stages for this employee
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

// Prepare the query and check if it's successful
$stmt = $con->prepare($query);
if ($stmt === false) {
    die('Error preparing query: ' . $con->error);  // Show the actual error
}

$stmt->bind_param("i", $employee_id);
$stmt->execute();
$stmt->bind_result(
    $current_stage, $faculty,
    $hr_submitted, $hr_received,
    $vp_acad_submitted, $vp_acad_received,
    $vp_finance_submitted, $vp_finance_received,
    $hr_received_submitted, $hr_received_received,
    $for_releasing_submitted, $for_releasing_received,
    $completed_date
);
$stmt->fetch();
$stmt->close();

// Function to format the date (without the time)
function formatStageTime($datetime) {
    return $datetime ? date('Y-m-d', strtotime($datetime)) : '';  // Format date as Y-m-d
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Employees | Dash</title>
    <link href="../../css/button.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="../../img/favicon.png" />

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
        top: 18px;
        left: 0;
        right: 0;
        height: 4px;
        background-color: #4CAF50;
        /* Change to green */
        z-index: 0;
        border-radius: 5px;
    }

    .progress-tracker li {
        list-style: none;
        position: relative;
        text-align: center;
        z-index: 1;
        flex: 1;
        min-width: 120px;
        /* Ensure consistent width even for empty stages */
    }

    .progress-tracker li::before {
        content: counter(step);
        counter-increment: step;
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        background: #fff;
        border: 3px solid #4CAF50;
        /* Green border */
        border-radius: 50%;
        margin-bottom: 10px;
        color: #4CAF50;
        /* Green number color */
        font-weight: bold;
        font-size: 18px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Add shadow for depth */
        transition: all 0.3s ease-in-out;
        visibility: visible;
        /* Make sure visibility is handled even if empty */
    }

    .progress-tracker li.done::before {
        content: "✔";
        background-color: #4CAF50;
        border-color: #4CAF50;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        /* Increase shadow for completed stages */
    }

    .progress-tracker li.current::before {
        background-color: #ff9800;
        /* Change current stage to amber */
        border-color: #ff9800;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        /* Add a shadow for emphasis */
    }

    /* The rest of your existing styles */

    /* Update font size for the SUBMITTED and RECEIVED dates and labels */
    .progress-tracker li span {
        display: block;
        margin-top: 5px;
        color: #444;
        font-weight: 600;
        font-size: 12px;
        /* Adjusted size for the text */
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: color 0.3s ease;
    }

    /* Apply smaller font size to the labels (SUBMITTED and RECEIVED) */
    .progress-tracker li span strong {
        font-weight: bold;
        font-size: 10px;
        /* Smaller font size for the labels */
    }

    /* Apply smaller font size to the date itself */
    .progress-tracker li span {
        font-size: 10px;
        /* Smaller font size for the dates */
        color: #777;
        /* Slightly lighter color for readability */
    }


    .progress-tracker li.done span {
        color: #4CAF50;
        /* Green text for completed stages */
    }

    .progress-tracker li.current span {
        color: #ff9800;
        /* Amber text for the current stage */
    }

    .stage-info {
        font-size: 14px;
        font-weight: normal;
        color: #333;
        margin-top: 5px;
    }

    .stage-info span {
        display: block;
        margin-top: 5px;
        color: #555;
        font-size: 12px;
        font-weight: 500;
    }

    .stage-info strong {
        font-weight: bold;
        color: #333;
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
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <!-- SERVICE Modal Button -->
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#servicehrmo">SERVICE</button>

                         
                     

                            <!-- TRACKERS START HERE -->
<hr>
<h3>Application for Leave – Tracking</h3>

<?php if ($faculty == 'Faculty'): ?>
<p><strong>Faculty:</strong></p>
<ul class="progress-tracker">
    <li
        class="<?= ($current_stage == 'HR' || $current_stage == 'VP ACAD' || $current_stage == 'HR (Received)' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
        HR
        <span><strong>SUBMITTED:</strong>
            <?= $hr_submitted ? formatStageTime($hr_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $hr_received ? formatStageTime($hr_received) : 'N/A' ?></span>
    </li>
    <li
        class="<?= ($current_stage == 'VP ACAD' || $current_stage == 'HR (Received)' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
        VP ACAD
        <span><strong>SUBMITTED:</strong>
            <?= $vp_acad_submitted ? formatStageTime($vp_acad_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $vp_acad_received ? formatStageTime($vp_acad_received) : 'N/A' ?></span>
    </li>
    <li
        class="<?= ($current_stage == 'HR (Received)' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
        HR (Received)
        <span><strong>SUBMITTED:</strong>
            <?= $hr_received_submitted ? formatStageTime($hr_received_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $hr_received_received ? formatStageTime($hr_received_received) : 'N/A' ?></span>
    </li>
    <li class="<?= ($current_stage == 'For Releasing') ? 'current' : '' ?>">
        For Releasing
        <span><strong>SUBMITTED:</strong>
            <?= $for_releasing_submitted ? formatStageTime($for_releasing_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $for_releasing_received ? formatStageTime($for_releasing_received) : 'N/A' ?></span>
    </li>
</ul>
<?php elseif ($faculty == 'Staff'): ?>
<p><strong>Staff:</strong></p>
<ul class="progress-tracker">
    <li
        class="<?= ($current_stage == 'HR' || $current_stage == 'VP Finance' || $current_stage == 'HR (Received)' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
        HR
        <span><strong>SUBMITTED:</strong>
            <?= $hr_submitted ? formatStageTime($hr_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $hr_received ? formatStageTime($hr_received) : 'N/A' ?></span>
    </li>
    <li
        class="<?= ($current_stage == 'VP Finance' || $current_stage == 'HR (Received)' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
        VP Finance
        <span><strong>SUBMITTED:</strong>
            <?= $vp_finance_submitted ? formatStageTime($vp_finance_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $vp_finance_received ? formatStageTime($vp_finance_received) : 'N/A' ?></span>
    </li>
    <li
        class="<?= ($current_stage == 'HR (Received)' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
        HR (Received)
        <span><strong>SUBMITTED:</strong>
            <?= $hr_received_submitted ? formatStageTime($hr_received_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $hr_received_received ? formatStageTime($hr_received_received) : 'N/A' ?></span>
    </li>
    <li class="<?= ($current_stage == 'For Releasing') ? 'current' : '' ?>">
        For Releasing
        <span><strong>SUBMITTED:</strong>
            <?= $for_releasing_submitted ? formatStageTime($for_releasing_submitted) : 'N/A' ?></span>
        <span><strong>RECEIVED:</strong>
            <?= $for_releasing_received ? formatStageTime($for_releasing_received) : 'N/A' ?></span>
    </li>
</ul>
<?php endif; ?>


                            <h3>Issuance of Certification & Service Record – Tracking</h3>
                            <ul class="progress-tracker">
                                <li
                                    class="<?= ($current_stage == 'Fillout Form' || $current_stage == 'Pay Cashier' || $current_stage == 'Present Request Form & Receipt' || $current_stage == 'Prepare Service Record' || $current_stage == 'HR Director Signs' || $current_stage == 'Record in Logbook' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
                                    Fillout Form 
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong>
                                            <?= $hr_submitted ? formatStageTime($hr_submitted) : 'N/A' ?></span>
                                        <span><strong>RECEIVED:</strong> N/A</span>
                                    </div>
                                </li>
                                <li
                                    class="<?= ($current_stage == 'Pay Cashier' || $current_stage == 'Present Request Form & Receipt' || $current_stage == 'Prepare Service Record' || $current_stage == 'HR Director Signs' || $current_stage == 'Record in Logbook' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
                                    Pay Cashier
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong>
                                            <?= $vp_acad_submitted ? formatStageTime($vp_acad_submitted) : 'N/A' ?></span>
                                        <span><strong>RECEIVED:</strong> N/A</span>
                                    </div>
                                </li>
                                <li
                                    class="<?= ($current_stage == 'Present Request Form & Receipt' || $current_stage == 'Prepare Service Record' || $current_stage == 'HR Director Signs' || $current_stage == 'Record in Logbook' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
                                    Present Request
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong>
                                            <?= $hr_received ? formatStageTime($hr_received) : 'N/A' ?></span>
                                        <span><strong>RECEIVED:</strong> N/A</span>
                                    </div>
                                </li>
                                <li
                                    class="<?= ($current_stage == 'Prepare Service Record' || $current_stage == 'HR Director Signs' || $current_stage == 'Record in Logbook' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
                                    Prepare Service Record
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong>
                                            <?= $for_releasing_submitted ? formatStageTime($for_releasing_submitted) : 'N/A' ?></span>
                                        <span><strong>RECEIVED:</strong> N/A</span>
                                    </div>
                                </li>
                                <li
                                    class="<?= ($current_stage == 'HR Director Signs' || $current_stage == 'Record in Logbook' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
                                    HR Director Signs
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong>
                                            <?= $completed_date ? formatStageTime($completed_date) : 'N/A' ?></span>
                                        <span><strong>RECEIVED:</strong> N/A</span>
                                    </div>
                                </li>
                                <li
                                    class="<?= ($current_stage == 'Record in Logbook' || $current_stage == 'For Releasing') ? 'done' : '' ?>">
                                    Record in Logbook
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong> N/A</span>
                                        <span><strong>RECEIVED:</strong>
                                            <?= $hr_received_received ? formatStageTime($hr_received_received) : 'N/A' ?></span>
                                    </div>
                                </li>
                                <li class="<?= ($current_stage == 'For Releasing') ? 'current' : '' ?>">
                                    For Releasing
                                    <div class="tracker-info">
                                        <span><strong>SUBMITTED:</strong>
                                            <?= $completed_date ? formatStageTime($completed_date) : 'N/A' ?></span>
                                        <span><strong>RECEIVED:</strong> N/A</span>
                                    </div>
                                </li>
                            </ul>

                            <!-- TRACKERS END HERE -->


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
</body>

</html>