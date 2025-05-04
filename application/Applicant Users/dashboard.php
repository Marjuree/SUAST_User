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
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$middle_name = isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";

// Combine first, middle, and last name
$full_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name);
$full_name = empty($full_name) ? "Applicant" : $full_name;

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
                                        <th>Availability</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SQL query now checks for active status
                                    $sql = "SELECT id, exam_name, exam_date, exam_time, venue, room, slot_limit, status FROM tbl_exam_schedule WHERE status = 'active'";
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
                                                <td><button class='btn btn-primary btn-sm btn-reserve' data-id='{$row['id']}'>Reserve</button></td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
    $(document).ready(function() {
        // Remove rows where slot == 0
        $('#reservationsTable tbody tr').each(function() {
            const $row = $(this);
            const slotText = $row.find('.slot').text().trim();
            const slot = parseInt(slotText);

            if (!isNaN(slot) && slot === 0) {
                $row.remove();
            }
        });

        const table = $('#reservationsTable').DataTable({
            "order": [],
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 5]
            }]
        });

        function updateAvailability() {
            $('#reservationsTable tbody tr').each(function() {
                const $row = $(this);
                const slotText = $row.find('.slot').text().trim();
                const slot = parseInt(slotText);
                const $availabilityCell = $row.find('.availability');

                if (slotText === '' || isNaN(slot)) {
                    $availabilityCell.text('Unknown');
                } else {
                    $availabilityCell.html('<span class="badge badge-success">Available</span>');
                }
            });
        }

        updateAvailability();
        table.on('draw', updateAvailability);

        // Handle reservation
        $(document).on('click', '.btn-reserve', function() {
            const button = $(this);
            const examId = button.data('id');

            Swal.fire({
                title: 'Confirm Reservation',
                text: "Are you sure you want to reserve this slot?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reserve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax_reserve.php',
                        type: 'POST',
                        data: {
                            exam_id: examId
                        },
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Your reservation has been submitted.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                button
                                    .prop('disabled', true)
                                    .removeClass('btn-primary')
                                    .addClass('btn-success')
                                    .text('Reserved');

                                const slotCell = button.closest('tr').find('.slot');
                                let currentSlot = parseInt(slotCell.text().trim());
                                if (!isNaN(currentSlot) && currentSlot > 0) {
                                    slotCell.text(currentSlot - 1);
                                }

                                updateAvailability();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: res.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An AJAX error occurred.',
                                icon: 'error'
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
