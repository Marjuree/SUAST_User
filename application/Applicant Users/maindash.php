<?php
session_start();
require_once "../../configuration/config.php";

// Fetch distinct venues and rooms
$venueQuery = $con->query("SELECT DISTINCT TRIM(venue) AS venue FROM tbl_exam_schedule");
$venues = $venueQuery->fetch_all(MYSQLI_ASSOC);

$roomQuery = $con->query("SELECT DISTINCT TRIM(room) AS room FROM tbl_exam_schedule");
$rooms = $roomQuery->fetch_all(MYSQLI_ASSOC);


// Fetch all dates with available/unavailable slots
$datesQuery = $con->query("
    SELECT exam_date, SUM(slot_limit) AS total_slots 
    FROM tbl_exam_schedule 
    GROUP BY exam_date
");
$datesAvailability = [];
while ($row = $datesQuery->fetch_assoc()) {
    $date = $row['exam_date'];
    $available = $row['total_slots'] > 0;
    $datesAvailability[$date] = $available ? 'available' : 'unavailable';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Exam Schedule</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/calendar.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif !important;

        }
    </style>
</head>

<body style="margin-bottom: 90px; margin-top: -20px;">
    <div class="container mt-4">

        <!-- Room and Venue -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Testing Room -->
                <div class="form-group">
                    <label for="room"><strong>Testing Room No. <span class="text-danger">*</span></strong></label>
                    <select id="room" class="form-control">
                        <option value="">Select Room</option>
                        <?php foreach ($rooms as $room): ?>
                            <option value="<?= htmlspecialchars($room['room']) ?>">
                                <?= htmlspecialchars(strtoupper($room['room'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Venue -->
                <div class="form-group">
                    <label for="venue"><strong>Venue</strong></label>
                    <select id="venue" class="form-control">
                        <option value="">Select Venue</option>
                        <?php foreach ($venues as $venue): ?>
                            <option value="<?= htmlspecialchars($venue['venue']) ?>">
                                <?= htmlspecialchars(ucwords($venue['venue'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="d-flex flex-column align-items-center mt-4">
            <div id="examDate" class="border p-3 rounded" style="max-width: 380px;"></div>
            <div id="dateAvailabilityNote" class="mt-2 text-center"></div>

            <div class="legend-wrapper" style="margin-bottom: 15px; margin-top: -20px;">
                <div class="legend-cards" style="display: flex; gap: 10px;">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="btn btn-success text-white w-100 mr-2"
                            style="pointer-events: none; cursor: default;">
                            AVAILABLE
                        </div>
                        <div class="btn btn-danger text-white w-100 ml-2"
                            style="pointer-events: none; cursor: default;">
                            FULL SCHEDULE
                        </div>
                    </div>

                </div>
            </div>
            <!-- Legend + Time Slots -->
            <div style="max-width: 320px; margin-top: 20px; text-align: left;">
                <!-- Legend -->


                <!-- Time and Number of Slots -->
                <h4>Time and Number of Slots *</h4>
                <div id="timeSlots"></div>
            </div>
        </div>

        <!-- Reserve Button -->
        <div class="text-center mt-4">
            <button id="reserveBtn" class="btn btn-success" disabled>Next</button>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const datesAvailability = <?= json_encode($datesAvailability) ?>;
        let selectedSlot = null;
        let fpInstance;

        fpInstance = flatpickr("#examDate", {
            dateFormat: "Y-m-d",
            inline: true,
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                const year = dayElem.dateObj.getFullYear();
                const month = String(dayElem.dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dayElem.dateObj.getDate()).padStart(2, '0');
                const date = `${year}-${month}-${day}`;

                if (datesAvailability[date] === 'available') {
                    dayElem.style.backgroundColor = '#a4f1a4'; // green
                } else if (datesAvailability[date] === 'unavailable') {
                    dayElem.style.backgroundColor = '#f1a4a4'; // red
                }
            },
            onChange: function (selectedDates, dateStr) {
                updateDateNote(dateStr);
                selectedSlot = null;
                $('#reserveBtn').prop('disabled', true);
                loadSlots();
            }
        });

        function updateDateNote(date) {
            const availability = datesAvailability[date] || 'unavailable';
            const noteDiv = $('#dateAvailabilityNote');


        }

        function loadSlots() {
            const date = fpInstance.input.value || '';
            const venue = $('#venue').val().trim();
            const room = $('#room').val().trim();

            // Clear slots and disable button if any filter missing
            if (!date || !venue || !room) {
                $('#timeSlots').html('');
                $('#reserveBtn').prop('disabled', true);
                selectedSlot = null;
                return;
            }

            $.ajax({
                url: 'load_slots.php',
                method: 'POST',
                dataType: 'json',
                data: { date, venue, room },
                success: function (data) {
                    $('#timeSlots').empty();
                    selectedSlot = null;
                    $('#reserveBtn').prop('disabled', true);

                    if (data.error) {
                        $('#timeSlots').html('<p class="text-danger">' + data.error + '</p>');
                        return;
                    }

                    if (!Array.isArray(data) || data.length === 0) {
                        $('#timeSlots').html('<p>No slots available.</p>');
                        return;
                    }

                    data.forEach(slot => {
                        const radio = $('<input type="radio" name="slotRadio" class="mr-2 mt-1" />')
                            .attr('id', 'slot_' + slot.id)
                            .val(slot.id)
                            .prop('disabled', slot.remaining_slots <= 0);

                        const label = $('<label></label>')
                            .attr('for', 'slot_' + slot.id)
                            .html(`${slot.exam_time} <span class="text-success font-weight-bold">(${slot.remaining_slots} slots left)</span><br><small>Venue: ${slot.venue}, Room: ${slot.room}</small>`);

                        const slotDiv = $('<div class="time-slot d-flex align-items-center mb-2"></div>')
                            .append(radio)
                            .append(label);

                        $('#timeSlots').append(slotDiv);

                        radio.on('change', function () {
                            selectedSlot = $(this).val();
                            $('#reserveBtn').prop('disabled', false);
                        });
                    });
                },
                error: function (xhr, status, error) {
                    $('#timeSlots').html('<p class="text-danger">Error loading slots: ' + error + '</p>');
                    $('#reserveBtn').prop('disabled', true);
                    selectedSlot = null;
                    console.error('AJAX error:', xhr.responseText);
                }
            });
        }



        // Reload slots when venue or room changes
        $('#venue, #room').on('change', function () {
            const date = fpInstance.input.value || '';
            if (date) {
                loadSlots();
            } else {
                $('#timeSlots').html('');
                $('#totalAvailableSlots').html('');
                $('#reserveBtn').prop('disabled', true);
                selectedSlot = null;
            }
        });

        // Reserve button click handler
        $('#reserveBtn').on('click', function () {
            if (!selectedSlot) return;

            Swal.fire({
                title: 'Reserve Slot?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, reserve it!'
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'ajax_reserve.php',
                        method: 'POST',
                        data: { exam_schedule_id: selectedSlot },
                        dataType: 'json',
                        success: function (res) {
                            console.log('Reserve response:', res);

                            if (res.status === 'success') {
                                Swal.fire('Success', 'Reservation successful!', 'success').then(() => {
                                    // ✅ Go to Step 3
                                    if (window.parent && window.parent.$) {
                                        window.parent.$('.step-btn[data-step="3"]').trigger('click');
                                    }
                                });
                            } else {
                                Swal.fire('Error', res.message || 'Reservation failed.', 'error');
                            }
                        },
                        error: function (xhr) {
                            console.error('Reserve error response:', xhr.responseText);
                            Swal.fire('Error', 'Failed to reserve slot.', 'error');
                        }
                    });
                }
            });
        });



    </script>

</body>

</html>