<?php
session_start();
require_once "../../configuration/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Exam Calendar</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.print.css" rel="stylesheet"
        media="print">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        h2.text-center {
            margin: 30px 0 20px 0;
            color: #333;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.7);
        }

        /* Customize FullCalendar header */
        .fc-toolbar {
            margin-bottom: 15px;
            background: #007bff;
            padding: 12px 20px;
            border-radius: 5px;
            color: #fff;
            box-shadow: 0 3px 6px rgba(0, 123, 255, 0.3);
        }

        .fc-toolbar h2 {
            color: #fff;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .fc-button {
            background: #0056b3;
            border: none;
            box-shadow: none;
            color: #fff;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .fc-button:hover,
        .fc-button:focus,
        .fc-button:active {
            background: #003d80;
            color: #fff;
            outline: none;
        }

        /* Dates with exams get distinct background */
        .fc-day[data-has-event="true"] {
            background-color: #d4edda !important;
            color: #155724 !important;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        /* Hover effect on dates with events */
        .fc-day[data-has-event="true"]:hover {
            background-color: #c3e6cb !important;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.15);
        }

        /* Event blocks styling */
        .fc-event {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.4);
            color: #fff !important;
            font-weight: 600;
            padding: 3px 6px;
            transition: transform 0.3s ease;
        }

        .fc-event:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 123, 255, 0.6);
            cursor: pointer;
        }

        /* Modal styles */
        #examModal .modal-content,
        #listModal .modal-content {
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        }

        #examModal .modal-header,
        #listModal .modal-header {
            background: #007bff;
            color: #fff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        #examModal .modal-footer,
        #listModal .modal-footer {
            border-top: none;
        }

        #reserveBtn {
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0, 123, 255, 0.5);
        }

        /* Button to open the list modal */
        #showListBtn {
            margin-bottom: 20px;
            font-weight: 600;
            padding: 10px 20px;
            background: #28a745;
            border: none;
            border-radius: 6px;
            color: #fff;
            box-shadow: 0 3px 6px rgba(40, 167, 69, 0.5);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #showListBtn:hover {
            background: #1e7e34;
        }

        /* Table in modal */
        #listModal table {
            width: 100%;
            border-collapse: collapse;
        }

        #listModal th,
        #listModal td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        #listModal th {
            background-color: #007bff;
            color: white;
        }

        .fc-day-number {
            display: inline-block !important;
            white-space: nowrap !important;
            line-height: 1 !important;
            padding: 2px 5px;
            font-weight: bold;


            /* GAMITA RANI IF GUSTO NINYI NAKA CENTER PERO ANG KANANG +MORE MA WALA LANG  */
            /* position: static !important;
            float: none !important;
            width: 100% !important;
            text-align: center !important;
            padding-top: 8px;
            margin-top: 20px !important;
            font-size: 16px;
            color: #333;
            display: inline-block !important;
            white-space: nowrap !important;
            line-height: 1 !important;
            padding: 2px 5px; */
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Exam Schedule Calendar</h2>
        <button id="showListBtn">Exams List</button>
        <div id="calendar"></div>
    </div>

    <!-- Exam Details Modal -->
    <div class="modal fade" id="examModal" tabindex="-1" role="dialog" aria-labelledby="examModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="examModalLabel">Exam Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color:#fff;opacity:1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Venue:</strong> <span id="modalVenue"></span></p>
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Time:</strong> <span id="modalTime"></span></p>
                    <p><strong>Room:</strong> <span id="modalRoom"></span></p>
                    <p><strong>Slots:</strong> <span id="modalSlot"></span></p>
                </div>
                <div class="modal-footer">
                    <button id="reserveBtn" class="btn btn-success">Reserve</button>
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- List Modal -->
    <div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-labelledby="listModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="listModalLabel">All Exam Events</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color:#fff;opacity:1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <table id="examsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Venue</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Slots</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filled dynamically -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            var eventDates = {};
            var allEvents = [];

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek'
                },
                eventLimit: true,
                events: {
                    url: 'load_calendar_events.php',
                    success: function (events) {
                        allEvents = events;

                        events.forEach(function (event) {
                            var dateStr = moment(event.start).format('YYYY-MM-DD');
                            eventDates[dateStr] = true;
                        });
                    }
                },
                dayRender: function (date, cell) {
                    var dateString = date.format('YYYY-MM-DD');
                    if (eventDates[dateString]) {
                        cell.attr('data-has-event', 'true');
                    }
                },
                eventClick: function (event, jsEvent, view) {
                    jsEvent.preventDefault();  // <<<<<< Prevent modal auto-close or page navigation

                    $('#modalVenue').text(event.venue);
                    $('#modalDate').text(event.exam_date);
                    $('#modalTime').text(event.exam_time);
                    $('#modalRoom').text(event.room);
                    $('#modalSlot').text(event.slot_limit);
                    $('#reserveBtn').data('id', event.id);
                    $('#examModal').modal('show');
                }
            });

            // Show list modal
            $('#showListBtn').on('click', function () {
                var tbody = $('#examsTable tbody');
                tbody.empty();

                if (allEvents.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center">No exam events found.</td></tr>');
                } else {
                    allEvents.forEach(function (event) {
                        tbody.append(
                            '<tr>' +
                            '<td>' + (event.venue || '-') + '</td>' +
                            '<td>' + (event.exam_date || moment(event.start).format('YYYY-MM-DD')) + '</td>' +
                            '<td>' + (event.exam_time || '-') + '</td>' +
                            '<td>' + (event.room || '-') + '</td>' +
                            '<td>' + (event.slot_limit || '-') + '</td>' +
                            '</tr>'
                        );
                    });
                }

                $('#listModal').modal('show');
            });

            $('#reserveBtn').on('click', function () {
                var examId = $(this).data('id');
                Swal.fire({
                    title: 'Reserve Slot?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, reserve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('ajax_reserve.php', { exam_id: examId }, function (res) {
                            const response = JSON.parse(res);
                            if (response.status === 'success') {
                                Swal.fire('Reserved!', 'You have reserved your slot.', 'success');
                                $('#examModal').modal('hide');
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
