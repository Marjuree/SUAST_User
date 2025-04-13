<?php
// Start output buffering
ob_start();

// Include necessary PHP files
require_once  "../configuration/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome Applicants</title>
    <link rel="shortcut icon" href="../img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Stylesheets -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/ionicons.min.css" rel="stylesheet">
    <link href="../js/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="../css/AdminLTE.css" rel="stylesheet">
    <link href="../css/select2.css" rel="stylesheet">
    <link href="../css/landing_page.css" rel="stylesheet">
    <link href="../css/button.css" rel="stylesheet">

    <script src="../js/jquery-1.12.3.js"></script>
</head><style>
    html,
    body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    body {
    font-family: Arial, sans-serif;
    background: url('../img/logo3.jpg') no-repeat center 25% fixed;
    background-size: cover;
}


    .modal-backdrop {
        z-index: 1049 !important;
    }

    .modal {
        z-index: 1050 !important;
        position: fixed !important;
    }

    /* Push background elements back */
    .blur-overlay {
        z-index: 1 !important;
        position: relative;
    }

    .blur-overlay {
        flex: 1;
        backdrop-filter: blur(2px);
        background-color: rgba(31, 30, 30, 0.5);
        padding: 20px;
        position: relative;
        z-index: 1;
    }

    .welcome-container {
        text-align: center;
        color: white;
        margin-top: 50px;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7) !important;
    }

    .logo {
        height: 100px;
        margin-bottom: 20px;
        filter: drop-shadow(1px 1px 4px rgba(0, 0, 0, 0.6));
        /* Shadow behind logo */
    }

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
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5) !important;
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

    .title {
        color: white !important;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }
    p{
      color: white !important;
    }
    </style>
<body>
    
   
<!-- Include the View List Modal -->
<?php include 'view_list_modal.php'; ?>

<div class="blur-overlay">

    <!-- Welcome Section -->
    <div class="welcome-container">
        <img src="../img/logo1.png" alt="SUAST Logo" class="logo">
        <h3>Welcome to UniReserve</h3>
        <p><strong> Choose a Service</strong></p>
         <!-- View List Button -->
    <!-- <a class="" data-toggle="modal" data-target="#viewListModal">View List</a> -->

    </div>

    <!-- Buttons for Register and Login -->
    <div class="buttons-container">
        <button  class="button"  data-toggle="modal" data-target="#loginApplicant">SUAST SLOT RESERVATION</button>
        <br>
        <button class="button" data-toggle="modal" data-target="#serviceModal">FRONTLINE SERVICE TRANSACTION</button>
    </div>

    </div> <!-- /.blur-overlay -->



<!-----------------------------------Service Modal----------------------------------------->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- Smaller for better mobile fit -->
    <div class="modal-content text-center p-4" style="border-radius: 15px; border: none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
      
      <!-- Logo -->
      <br>
      <br>
      <div class="d-flex justify-content-center mb-3">
        <img src="../img/ken.png" alt="Logo" style="width: 100px; height: 100px;">
      </div>
      <br>

      <!-- Divider -->
      <hr style="width: 60%; margin: auto; border: 1px solid #555;">
      <br>
      <br>
      <!-- Title -->
      <h5 class="fw-bold mt-3 text-uppercase text-dark">Choose a Frontline Service</h5>

      <!-- Buttons -->
      <br>
      <br>
      <div class="buttons-container">
        <button  class="button"  data-toggle="modal" data-target="#StudentModal">ACCOUNTING OFFICE</button>
        <br>
        <br>
        <!-- <button class="button" data-toggle="modal" data-target="#loginEmployee">HUMAN RESOURCE MANAGEMENT</button> OLD-->
        <button class="button" data-toggle="modal" data-target="#empModal">HUMAN RESOURCE MANAGEMENT</button>
        </div>
        <br>
        <br>
        <br>
      </div>
    </div>
  </div>
</div>

    
 
<?php include "../controller/controller.php"; ?>

<?php include "../includes/foot.php"; ?>
    
 

    <!-- Bootstrap JS and other necessary scripts -->
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/morris/raphael-2.1.0.min.js" type="text/javascript"></script>
    <script src="../js/morris/morris.js" type="text/javascript"></script>
    <script src="../js/select2.full.js" type="text/javascript"></script>

    <!-- DataTables & AdminLTE App -->
    <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="../js/buttons.print.min.js" type="text/javascript"></script>
    <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
    <!-- Bootstrap JS and dependencies (for modal) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $("#table").dataTable({
                "aoColumnDefs": [{ "bSortable": false, "aTargets": [0,5] }], 
                "aaSorting": [], 
                "dom": '<"search"f><"top"l>rt<"bottom"ip><"clear">'
            });
        });
    </script>
</body>
</html>

<?php
// End output buffering and flush content
ob_end_flush();
?>
 

 
