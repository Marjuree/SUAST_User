<?php
session_start();
session_regenerate_id(true);
require_once "../../configuration/config.php"; // Ensure database connection

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    $_SESSION['error_message'] = "Please login as an applicant.";
    header("Location: ../../php/error.php");
    exit();
}

// Fetch applicant details
$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Application</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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

.table td,
.table th {
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
    max-width: 150px;
    vertical-align: middle;
}

@media (max-width: 768px) {

    .table td,
    .table th {
        font-size: 14px;
    }
}

.modal-body input {
    border-radius: 30px !important;
}
</style>

<body class="skin-blue">
    <?php
    require_once('includes/header.php');
    require_once('../../includes/head_css.php');
    include 'add_modal.php';
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>My Application</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>

            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#addApplicantModal">
                                <i class="fa fa-plus"></i> Submit Details
                            </button>
                        </div>

                        <div class="box-body table-responsive">
                            <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <?= $_SESSION['error_message']; ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <?= $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                            <?php endif; ?>

                            <table id="applicantsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>Middle Name</th>
                                        <th>Image</th>
                                        <th>Document</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
    $sql = "SELECT id, lname, fname, mname, image_blob, document_blob FROM tbl_applicants WHERE applicant_id = ?";
    $stmt = $con->prepare($sql);

    if ($stmt === false) {
        error_log("SQL Prepare failed: " . $con->error);
        die("SQL Prepare failed: " . $con->error);
    }

    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $upload_dir = 'uploads/'; // Ensure this folder exists and is writable

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    while ($row = $result->fetch_assoc()) {
        // Handle the image encoding for display
        $image_base64 = !empty($row['image_blob']) ? base64_encode($row['image_blob']) : '';
        $image_path = !empty($row['image_blob']) ? 'data:image/jpeg;base64,' . $image_base64 : 'path/to/default/image.jpg';

        // Handle the document download
        $document_path = '';
        $document_filename = '';

        if (!empty($row['document_blob'])) {
            // Define a unique filename for the document
            $document_filename = "document_" . $row['id'] . ".pdf"; 
            $document_file_path = $upload_dir . $document_filename;

            // Save the document to a file if not already saved
            if (is_writable($upload_dir)) {
                if (!file_exists($document_file_path)) {
                    file_put_contents($document_file_path, $row['document_blob']);
                }
                $document_path = $document_file_path; // Path to the file for download
            } else {
                error_log("Directory not writable: $upload_dir");
            }
        }

        // Output the data into the table
        echo "<tr class='text-center'>
            <td>{$row['id']}</td>
            <td>{$row['lname']}</td>
            <td>{$row['fname']}</td>
            <td>{$row['mname']}</td>
            <td>
                <img src='{$image_path}' alt='Profile Image' class='rounded-circle' width='50' height='50'>
            </td>
            <td>";

        // Provide a download link if the document is available
        if ($document_path) {
            echo "<a href='{$document_path}' download='{$document_filename}' class='btn btn-info btn-sm'>
                <i class='fa fa-file-pdf-o'></i> Download Document
            </a>";
        } else {
            echo "No Document";
        }

        echo "</td>
            <td>
                <a href='edit_applicant.php?id={$row['id']}' class='btn btn-sm btn-warning'><i class='fa fa-edit'></i> Edit</a>
                <a href='delete_applicant.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete your application?');\">
                    <i class='fa fa-trash'></i> Delete
                </a>
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

    <?php require_once "../../includes/footer.php"; ?>

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
            }],
            "lengthChange": false
        });
    });
    </script>

</body>

</html>
