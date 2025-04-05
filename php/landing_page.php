<?php
// Start session at the very top before any HTML or output
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Applicants</title>
    <link rel="shortcut icon" href="../img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome & Other Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="../css/landing_page.css" rel="stylesheet">

    <!-- DataTables (Bootstrap 4 version) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
</head>

<style>
    .button {
        font-size: 1.1em;
        background-color: #00bcd4;
        color: white;
        padding: 14px 28px;
        border: none;
        border-radius: 50px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 10px;
        width: 250px;
        max-width: 90%;
        text-align: center;
        display: inline-block;
    }

    .button:hover {
        background-color: #0097a7;
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    }

    .buttons-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    @media (max-width: 767px) {
        .button {
            width: 100%;
        }
        .buttons-container {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<body>

    <!-- Welcome Section -->
    <div class="text-center my-5">
        <img src="../img/ken.png" alt="SUAST Logo" class="img-fluid" style="max-width: 150px;">
        <h3 class="mt-3">Welcome, UniReserve</h3>
        <p><strong>Choose a Service</strong></p>
    </div>

    <!-- Buttons for Register and Login -->
    <div class="buttons-container">
        <button class="button" data-toggle="modal" data-target="#loginApplicant">SUAST SLOT RESERVATION</button>
        <button class="button" data-toggle="modal" data-target="#serviceModal">FRONTLINE SERVICE TRANSACTION</button>
    </div>

    <!-- Service Modal -->
    <div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content text-center p-4" style="border-radius: 15px; border: none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
                <div class="d-flex justify-content-center mb-3">
                    <img src="../img/ken.png" alt="Logo" style="width: 100px; height: 100px;">
                </div>
                <hr style="width: 60%; margin: auto; border: 1px solid #555;">
                <h5 class="fw-bold mt-3 text-uppercase text-dark">Choose a Frontline Service</h5>
                <div class="buttons-container">
                    <button class="button" data-toggle="modal" data-target="#StudentModal">ACCOUNTING OFFICE</button>
                    <button class="button" data-toggle="modal" data-target="#empModal">HUMAN RESOURCE MANAGEMENT</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "../controller/controller.php"; ?>
    <?php include "../includes/foot.php"; ?>

    <!-- Bootstrap 4 Bundle JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables (Bootstrap 4 Version) -->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            $("#table").DataTable({
                "columnDefs": [{ "orderable": false, "targets": [0,5] }],
                "order": [],
                "dom": '<"search"f><"top"l>rt<"bottom"ip><"clear">'
            });
        });
    </script>

</body>
</html>

<?php
// Ensure output buffering is not needed if not explicitly required
// No need for ob_end_flush() if we aren't using output buffering
?>
