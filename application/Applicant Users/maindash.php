<?php
session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php"; // Ensure database connection


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

    .box-body.table-responsive {
        padding: 0 20px 0 20px;
    }

    .custom-modal {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        border: none;
    }

    .custom-modal-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
        padding: 1rem 1.5rem;
        border-bottom: none;
    }

    .custom-close {
        color: white;
        opacity: 1;
        font-size: 1.5rem;
        border: none;
        background: transparent;
    }

    .custom-close:hover {
        color: #ff4757;
        cursor: pointer;
    }

    .custom-modal-body {
        background: #f9faff;
        font-size: 1.1rem;
        padding: 1.5rem 2rem;
        color: #333;
    }

    .custom-modal-body p {
        margin-bottom: 0.75rem;
    }

    .custom-modal-footer {
        background: #f1f3f8;
        border-top: none;
        padding: 1rem 1.5rem;
    }

    .custom-modal-footer .btn-primary {
        background:rgb(31, 233, 62);
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .custom-modal-footer .btn-primary:hover {
        background: #6a11cb;
    }

    #reservationsTable tbody tr:nth-child(odd) {
        background-color: rgb(230, 229, 229);
    }

    #reservationsTable tbody tr:nth-child(even) {
        background-color: #ffffff;
    }
</style>

<body class="skin-blue">


    <div class="wrapper row-offcanvas row-offcanvas-left">

        <aside class="right-side">
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body table-responsive">
                            <table id="reservationsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name of Venue</th>
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
                                           
                                            <td>
                                                <button class='btn btn-success btn-sm btn-reserve' data-id='{$row['id']}'>Reserve</button>
                                                <button class='btn btn-secondary btn-sm btn-show-details' 
                                                    data-id='{$row['id']}'
                                                    data-venue='{$row['venue']}'
                                                    data-date='{$row['exam_date']}'
                                                    data-time='{$row['exam_time']}'
                                                    data-room='{$row['room']}'
                                                    data-slot='{$row['slot_limit']}'>
                                                    Show Details
                                                </button>
                                            </td>
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
    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Exam Slot Details</h5>
                    <button type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body custom-modal-body">
                    <p><strong>Venue:</strong> <span id="detailVenue"></span></p>
                    <p><strong>Date of Examination:</strong> <span id="detailDate"></span></p>
                    <p><strong>Time:</strong> <span id="detailTime"></span></p>
                    <p><strong>Room Number:</strong> <span id="detailRoom"></span></p>
                    <p><strong>Slot Limit:</strong> <span id="detailSlot"></span></p>
                </div>
                <div class="modal-footer custom-modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "../../includes/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function () {
            // Remove rows where slot == 0
            $('#reservationsTable tbody tr').each(function () {
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
                    "targets": [0, 1]
                }]
            });

            function updateAvailability() {
                $('#reservationsTable tbody tr').each(function () {
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
            $(document).on('click', '.btn-reserve', function () {
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
                            data: { exam_id: examId },
                            success: function (response) {
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
                            error: function () {
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

            // Handle Show Details button
            $(document).on('click', '.btn-show-details', function () {
                const button = $(this);
                const venue = button.data('venue');
                const date = button.data('date');
                const time = button.data('time');
                const room = button.data('room');
                const slot = button.data('slot');

                $('#detailVenue').text(venue);
                $('#detailDate').text(date);
                $('#detailTime').text(time);
                $('#detailRoom').text(room);
                $('#detailSlot').text(slot);

                $('#detailsModal').modal('show');
            });
        });
    </script>




</body>

</html>
