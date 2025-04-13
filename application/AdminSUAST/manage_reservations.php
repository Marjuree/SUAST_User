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

    <style>
        /* Default: Hide header */
        .school-header {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }

        .school-header img {
            width: 120px;
            height: 120px;
        }

        .school-header h2 {
            color: #002f6c;
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }

        .school-header h4 {
            color: #333;
            font-size: 14px;
            font-style: italic;
            margin: 5px 0;
        }

        .line-break {
            width: 100%;
            height: 3px;
            background: #002f6c;
            margin: 10px 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printSection, #printSection * {
                visibility: visible;
            }

            #printSection {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                text-align: center;
            }

            .school-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
            }

            .header-left {
                text-align: left;
            }

            .header-right img {
                width: 120px;
                height: 120px;
            }

            /* Hide the Action column */
            #printSection th:last-child, #printSection td:last-child {
                display: none;
            }
        }
    </style>
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
                <h1>Manage Reservation</h1>
            </section>

            <section class="content">
                <div class="box">
                    <div class="box-header d-flex justify-content-between align-items-center">
                        <form method="GET" action="">
                            <label for="nameFilter">Find by Name:</label>
                            <input type="text" name="nameFilter" id="nameFilter" class="form-control" style="width: 200px; display: inline-block;" placeholder="Enter Name" value="<?php echo isset($_GET['nameFilter']) ? htmlspecialchars($_GET['nameFilter']) : ''; ?>">
                            
                            <label for="roomFilter">Find by Room:</label>
                            <select name="roomFilter" id="roomFilter" class="form-control" style="width: 200px; display: inline-block;">
                                <option value="">All Rooms</option>
                                <?php
                                $roomQuery = mysqli_query($con, "SELECT DISTINCT room FROM tbl_reservation ORDER BY room ASC");
                                while ($roomRow = mysqli_fetch_assoc($roomQuery)) {
                                    $selected = (isset($_GET['roomFilter']) && $_GET['roomFilter'] == $roomRow['room']) ? "selected" : "";
                                    echo "<option value='{$roomRow['room']}' $selected>{$roomRow['room']}</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary">Search</button>
                               <!-- Print Button -->
                        <!-- <button class="btn btn-success" onclick="printReservations()">Print Reports</button> -->
                        <button class="btn btn-success" onclick="printReservations()"><i class="fa fa-chart-bar"></i> Print Reports</button>
                        </form>
                    </div>

                    <div class="box-body table-responsive">
                        <div id="printSection">
                            <!-- ✅ School Header for Print -->
                            <div class="school-header">
                                <div class="header-left">
                                    <h4><strong>Republic of the Philippines</strong></h4>
                                    <div class="line-break"></div>
                                    <h2>DAVAO ORIENTAL STATE UNIVERSITY</h2>
                                    <h4>A university of excellence, innovation, and inclusion</h4>
                                </div>
                                <div class="header-right">
                                    <img src="images/ken.png" alt="School Logo">
                                </div>
                            </div>

                            <div class="line-break"></div>
                            <h3><u>EXAM RESERVATION LIST</u></h3>

                            <table id="examTable" class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr class="text-center">
                                        <th>Name</th>
                                        <th>Exam Time</th>
                                        <th>Room</th>
                                        <th>Venue</th>
                                        <th>Action</th> <!-- This will be hidden when printing -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tbl_reservation WHERE 1";
                                    if (isset($_GET['nameFilter']) && !empty($_GET['nameFilter'])) {
                                        $nameFilter = mysqli_real_escape_string($con, $_GET['nameFilter']);
                                        $query .= " AND name LIKE '%$nameFilter%'";
                                    }
                                    if (isset($_GET['roomFilter']) && !empty($_GET['roomFilter'])) {
                                        $roomFilter = mysqli_real_escape_string($con, $_GET['roomFilter']);
                                        $query .= " AND room = '$roomFilter'";
                                    }
                                    $query .= " ORDER BY exam_time ASC";
                                    
                                    $result = mysqli_query($con, $query);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['exam_time']}</td>
                                                    <td>{$row['room']}</td>
                                                    <td>{$row['venue']}</td>
                                                    <td>
                                                        <form action='delete_reservation.php' method='POST'>
                                                            <input type='hidden' name='id' value='{$row['id']}'>
                                                            <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                                                        </form>
                                                    </td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No reservations found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php require_once "../../includes/footer.php"; ?>
                </div>
            </section>
        </aside>
    </div>

    <script>
        function printReservations() {
            window.print();
        }
    </script>
</body>
</html>