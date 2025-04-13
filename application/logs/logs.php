<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Logs</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <?php
    session_start();
    if (!isset($_SESSION['role'])) {
        header("Location: ../../login.php");
    } else {
        ob_start();
        require_once('../../includes/head_css.php');
    ?>
</head>
<body class="skin-blue">
    <?php require_once "../../configuration/config.php"; ?>
    <?php require_once('../../includes/header.php'); ?>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        <aside class="right-side">
            <section class="content-header">
                <h1 class="text-primary">Login History</h1>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="logsTable" class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Type</th>
                                                <th>Title</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $squery = mysqli_query($con, "SELECT * FROM tbl_logs ORDER BY log_date DESC");
                                            while ($row = mysqli_fetch_array($squery)) {
                                                echo "<tr>
                                                    <td>" . htmlspecialchars($row['log_type']) . "</td>
                                                    <td>" . htmlspecialchars($row['title']) . "</td>
                                                    <td>" . htmlspecialchars($row['message']) . "</td>
                                                    <td>" . htmlspecialchars($row['log_date']) . "</td>
                                                </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    <?php }
    require_once "../../includes/footer.php"; ?>
    
    <script>
        $(document).ready(function() {
            $('#logsTable').DataTable({
                "order": [[3, "desc"]], // Order by Date column
                "columnDefs": [{ "orderable": false, "targets": [0, 1, 2] }] // Disable sorting for some columns
            });
        });
    </script>
</body>
</html>
