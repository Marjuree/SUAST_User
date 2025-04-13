<?php
session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php"; // Ensure database connection

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}


$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Applicant | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<style>
.badge-success {
    background-color: #28a745 !important;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
}
.badge-danger {
    background-color: #dc3545 !important;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
}

div.dataTables_wrapper label {
    color: #000 !important;
    font-weight: bold !important;
}

.dataTables_length select,
.dataTables_filter input {
    color: #000 !important;
}

table thead {
    background-color: #343a40;
    color: #fff;
}

table thead th {
    text-align: center;
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
                    <div class="box-header">
                        <h3 class="box-title">Available Slot</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="reservationsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name of Venue</th>
                                    <th>Date of Examination</th>
                                    <th>Time & Testing Room No.</th>
                                    <th>Slot</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT id, exam_name, exam_date, exam_time, venue, room, slot_limit FROM tbl_exam_schedule";
                                    $stmt = $con->prepare($sql);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr class='text-center'>
                                                <td>{$row['venue']}</td>
                                                <td>{$row['exam_date']}</td>
                                                <td>{$row['exam_time']} | Room {$row['room']}</td>
                                                <td class='slot'>{$row['slot_limit']}</td>
                                                <td class='availability'></td>
                                            </tr>";
                                    }

                                    $stmt->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</div>

<?php require_once "../../includes/footer.php"; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Remove rows ONLY with slot_limit == 0
    $('#reservationsTable tbody tr').each(function() {
        const slot = parseInt($(this).find('.slot').text());
        if (!isNaN(slot) && slot === 0) {
            $(this).remove(); // Only remove rows where slot is 0
        }
    });

    // Initialize DataTable
    $('#reservationsTable').DataTable({
        "order": [],
        "columnDefs": [
            { "orderable": false, "targets": [0] }
        ]
    });

    // Add availability badge
    $('#reservationsTable tbody tr').each(function() {
        const slot = parseInt($(this).find('.slot').text());
        const $availabilityCell = $(this).find('.availability');

        if (!isNaN(slot)) {
            $availabilityCell.html('<span class="badge badge-success">Available</span>');
        } else {
            $availabilityCell.text('Unknown');
        }
    });
});
</script>



</body>
</html>
