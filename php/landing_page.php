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
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <!-- <a class="navbar-brand" href="index.php"> -->
                    <a class="navbar-brand">
                    <img src="../img/ken.png" alt="Brand" class="navbar-logo">
                </a>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <div class="welcome-container">
        <img src="../img/ken.png" alt="SUAST Logo" class="logo">
        <h3>Welcome, SUAST Taker!</h3>
        <p><strong> Choose a Service</strong></p>
    </div>

    <!-- Buttons for Register and Login -->
    <div class="buttons-container">
        <button  class="button"  data-toggle="modal" data-target="#loginApplicant">SUAST SLOT RESERVATION</button>
        <br>
        <button class="button" data-toggle="modal" data-target="#serviceModal">FRONTLINE SERVICE TRANSACTION</button>
    </div>



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
 

 