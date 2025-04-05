<?php
session_start();
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

session_regenerate_id(true);

$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Applicants List</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>

<style>
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

.btn {
    margin-top: 10px;
    margin-left: 6px;
}
</style>

<body class="skin-blue">
    <?php
    require_once('includes/header.php');
    require_once('../../includes/head_css.php');
    
    ?>
    <?php include 'add_modal.php'; ?>


    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Applicants List</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addApplicantModal">
                                <i class="fa fa-plus"></i> Add Applicant
                            </button>

                        </div>
                        <div class="box-body table-responsive">
                            <table id="applicantsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Image</th> <!-- Added column for the image -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Fetch applicants' data including image
                                        $sql = "SELECT id, lname, fname, mname, image FROM tbl_applicants";
                                        $query = mysqli_query($con, $sql);
                                        
                                        // Loop through each row of applicants
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            // Check if the applicant has an image, otherwise use a placeholder
                                            $image_path = !empty($row['image']) ? 'uploads/' . $row['image'] : 'path/to/default/image.jpg';
                                            echo "<tr class='text-center'>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['lname']}</td>
                                                    <td>{$row['fname']}</td>
                                                    <td>{$row['mname']}</td>
                                                    <td>";
                                                    
                                            // Display the image if it exists
                                            if ($row['image']) {
                                                echo "<img src='{$image_path}' alt='Profile Image' class='rounded-circle' width='50' height='50'>";
                                            } else {
                                                // Default image if no profile image exists
                                                echo "<img src='{$image_path}' alt='No Image' class='rounded-circle' width='50' height='50'>";
                                            }
                                            
                                            echo "</td>
                                                    <td>
                                                        <a href='edit_applicant.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='fa fa-edit'></i> Edit</a>
                                                        <a href='delete_applicant.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this applicant?');\"><i class='fa fa-trash'></i> Delete</a>
                                                    </td>
                                                </tr>";
                                        }
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

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <script>
    $(document).ready(function() {
        $('#applicantsTable').DataTable({
            "order": [],
            "columnDefs": [{
                "orderable": false,
                "targets": []
            }]
        });
    });
    </script>
</body>

</html>