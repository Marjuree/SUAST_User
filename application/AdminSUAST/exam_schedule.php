<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: ../../php/error.php?welcome=Please login to access this page");
    exit();
}
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../../css/exam_schedule.css">
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>

<body class="skin-blue">
    <?php 
    require_once "../../configuration/config.php";
    require_once('../../includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Manage Room</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header d-flex justify-content-between align-items-center">
                    
                        <!-- ✅ Room Filter Form (Dynamic) -->
                        <form method="GET" action="">
                            <label for="roomFilter">Find by Slot:</label>
                            <select name="roomFilter" id="roomFilter" class="form-control" style="width: 200px; display: inline-block;">
                                <option value="">All Slot</option>
                                <?php
                                $roomQuery = mysqli_query($con, "SELECT DISTINCT room FROM tbl_exam_schedule ORDER BY room ASC");
                                while ($roomRow = mysqli_fetch_assoc($roomQuery)) {
                                    $selected = (isset($_GET['roomFilter']) && $_GET['roomFilter'] == $roomRow['room']) ? "selected" : "";
                                    echo "<option value='{$roomRow['room']}' $selected>{$roomRow['room']}</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Search Slot</button>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addScheduleModal">
                                +Add Slot
                            </button>
                        </form>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="examTable" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>SUAST</th>
                                    <th>Exam Date</th>
                                    <th>Exam Time</th>
                                    <th>EXAM VENUE</th>
                                    <th>Room</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // ✅ Applying Room Filter to the Query
                                $roomFilter = isset($_GET['roomFilter']) ? mysqli_real_escape_string($con, $_GET['roomFilter']) : '';

                                $sql = "SELECT * FROM tbl_exam_schedule";
                                if (!empty($roomFilter)) {
                                    $sql .= " WHERE room = '$roomFilter'";
                                }

                                $query = mysqli_query($con, $sql);

                                while ($row = mysqli_fetch_assoc($query)) {
                                    echo "<tr class='text-center'>
                                            <td>{$row['id']}</td>
                                            <td>{$row['exam_name']}</td>
                                            <td>{$row['exam_date']}</td>
                                            <td>{$row['exam_time']}</td>
                                            <td>{$row['subject']}</td>
                                            <td>{$row['room']}</td>
                                            <td>
                                                <button class='btn btn-warning btn-edit' 
                                                    data-id='{$row['id']}' 
                                                    data-name='{$row['exam_name']}' 
                                                    data-date='{$row['exam_date']}' 
                                                    data-time='{$row['exam_time']}' 
                                                    data-subject='{$row['subject']}' 
                                                    data-room='{$row['room']}' 
                                                    data-toggle='modal' 
                                                    data-target='#editScheduleModal'>Edit</button>
                                                <button class='btn btn-danger btn-delete' data-id='{$row['id']}'>Delete</button>
                                            </td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </aside>
    </div>
 


    <!-- Modal for Adding Exam Schedule -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addScheduleLabel">Add Exam Schedule</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addScheduleForm">
                        <div class="form-group">
                            <label>Exam Name</label>
                            <input type="text" name="exam_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Exam Date</label>
                            <input type="date" id="exam_date" name="exam_date" class="form-control" required>
                            <small class="text-danger" id="date-warning" style="display: none;">This date is not available.</small>
                        </div>
                        <div class="form-group">
                            <label>Exam Time</label>
                            <input type="time" name="exam_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>EXAM VENUE</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <input type="text" name="room" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


 <!-- Modal for Editing Exam Schedule -->
 <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Exam Schedule</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="editScheduleForm">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Exam Name</label>
                            <input type="text" name="exam_name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Exam Date</label>
                            <input type="date" name="exam_date" id="edit_date" class="form-control" required>
                            <small class="text-danger" id="date-warning" style="display: none;">This date is not available.</small>
                        </div>
                        <div class="form-group">
                            <label>Exam Time</label>
                            <input type="time" name="exam_time" id="edit_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>EXAM VENUE</label>
                            <input type="text" name="subject" id="edit_subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Room</label>
                            <input type="text" name="room" id="edit_room" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
 
    <script>
        $(document).ready(function() {
            // Populate Edit Modal
            $(".btn-edit").click(function() {
                $("#edit_id").val($(this).data("id"));
                $("#edit_name").val($(this).data("name"));
                $("#edit_date").val($(this).data("date"));
                $("#edit_time").val($(this).data("time"));
                $("#edit_subject").val($(this).data("subject"));
                $("#edit_room").val($(this).data("room"));
            });


            // Handle Delete
            $(".btn-delete").click(function() {
                let id = $(this).data("id");
                if (confirm("Are you sure you want to delete this exam schedule?")) {
                    $.post("function.php", { action: "delete", id: id }, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });

            // Submit Edit Form
            $("#editScheduleForm").submit(function(e) {
                e.preventDefault();
                $.post("edit.php", $(this).serialize() + "&action=edit", function(response) {
                    alert(response);
                    location.reload();
                });
            });
        });
        </script>


  


    <script>
        $(document).ready(function() {
            let unavailableDates = [];

            // Fetch unavailable dates from the database
            $.ajax({
                url: "fetch_unavailable_dates.php",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    unavailableDates = data;
                }
            });

            $("#exam_date").on("change", function() {
                let selectedDate = $(this).val();
                if (unavailableDates.includes(selectedDate)) {
                    $("#date-warning").show();
                    $(this).val("");
                } else {
                    $("#date-warning").hide();
                }
            });

            $("#addScheduleForm").submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    type: "POST",
                    url: "add_exam.php",
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        alert("AJAX Error: " + error);
                    }
                });
            });
        });
    </script>


<?php require_once "../../includes/footer.php"; ?>
    
    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0, 5] }],
                "aaSorting": []
            });
        });
    </script>
</body>
</html>



 