<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Taker's</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional Bootstrap 4 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

    <?php
  session_start();
  // Check if user is logged in as an Applicant
  if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
      exit();
  } else {
      ob_start();
      require_once('../../includes/head_css.php');
  ?>

    <style>
    .input-size {
        width: 418px;
    }
    </style>
</head>

<body class="skin-blue">

    <?php 
      require_once "../../configuration/config.php";
  ?>
    <?php require_once('../../includes/header.php'); ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <?php require_once('../../includes/sidebar.php'); ?>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Taker's
                </h1>
            </section>

            <?php 
          if(!isset($_GET['Applicant'])) {
          ?>
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">
                        <div class="box-header">
                            <div style="padding:10px;">

                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#addCourseModal"><i class="fa fa-user-plus" aria-hidden="true"></i> Add
                                    Applicant</button>
                                <?php 
                                      if(!isset($_SESSION['staff'])) {
                                  ?>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal"><i
                                        class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                <?php
                                      }
                                  ?>

                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <form method="post" enctype="multipart/form-data">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <?php 
                                              if(!isset($_SESSION['staff'])) {
                                          ?>
                                            <th style="width: 20px !important;"><input type="checkbox"
                                                    name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                                            </th>
                                            <?php
                                              }
                                          ?>
                                            <th>Purok</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Former Address</th>
                                            <th style="width: 40px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                      if(!isset($_SESSION['staff'])) {
                                          $squery = mysqli_query($con, "SELECT purok,id,CONCAT(lname, ', ', fname, ' ', mname) as cname, age, gender, barangay, image FROM tbl_applicants");
                                          while($row = mysqli_fetch_array($squery)) {
                                              echo '
                                              <tr>
                                                  <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="'.$row['id'].'" /></td>
                                                  <td>'.$row['purok'].'</td>
                                                  <td style="width:70px;"><image src="image/'.basename($row['image']).'" style="width:60px;height:60px;"/></td>
                                                  <td>'.$row['cname'].'</td>
                                                  <td>'.$row['age'].'</td>
                                                  <td>'.$row['gender'].'</td>
                                                  <td>'.$row['barangay'].'</td>
                                                  <td><button class="btn btn-primary btn-sm" data-target="#editModal'.$row['id'].'" data-toggle="modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></td>
                                              </tr>
                                              ';

                                              include "edit_modal.php";
                                          }
                                      } else {
                                          $squery = mysqli_query($con, "SELECT purok,id,CONCAT(lname, ', ', fname, ' ', mname) as cname, age, gender, barangay, image FROM tbl_applicants");
                                          while($row = mysqli_fetch_array($squery)) {
                                              echo '
                                              <tr>
                                                  <td>'.$row['purok'].'</td>
                                                  <td style="width:70px;"><image src="image/'.basename($row['image']).'" style="width:60px;height:60px;"/></td>
                                                  <td>'.$row['cname'].'</td>
                                                  <td>'.$row['age'].'</td>
                                                  <td>'.$row['gender'].'</td>
                                                  <td>'.$row['barangay'].'</td>
                                                  <td><button class="btn btn-primary btn-sm" data-target="#editModal'.$row['id'].'" data-toggle="modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></td>
                                              </tr>
                                              ';

                                              require_once "edit_modal.php";
                                          }
                                      }
                                      ?>
                                    </tbody>
                                </table>

                                <?php require_once "../deleteModal.php"; ?>

                            </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <?php require_once "../edit_notif.php"; ?>

                    <?php require_once "../added_notif.php"; ?>

                    <?php require_once "../delete_notif.php"; ?>

                    <?php require_once "../duplicate_error.php"; ?>

                    <?php require_once "add_modal.php"; ?>

                    <?php require_once "function.php"; ?>


                </div> <!-- /.row -->
            </section><!-- /.content -->
            <?php
          } else {
          ?>
            <section class="content">
                <div class="row">
                    <!-- left column -->
                    <div class="box">

                        <div class="box-body table-responsive">
                            <form method="post" enctype="multipart/form-data">
                                <table id="table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 20px !important;"><input type="checkbox"
                                                    name="chk_delete[]" class="cbxMain" onchange="checkMain(this)" />
                                            </th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Former Address</th>
                                            <th style="width: 40px !important;">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                      // $squery = mysqli_query($con, "SELECT id,CONCAT(lname, ', ', fname, ' ', mname) as cname, age, gender, barangay, image FROM tblapplicants where householdnum = '".$_GET['resident']."'");
                                      $squery = mysqli_query($con, "SELECT id,CONCAT(lname, ', ', fname, ' ', mname) as cname, age, gender, barangay, image FROM tbl_applicants");
                                      while($row = mysqli_fetch_array($squery)) {
                                          echo '
                                          <tr>
                                              <td><input type="checkbox" name="chk_delete[]" class="chk_delete" value="'.$row['id'].'" /></td>
                                              <td style="width:70px;"><image src="image/'.basename($row['image']).'" style="width:60px;height:60px;"/></td>
                                              <td>'.$row['cname'].'</td>
                                              <td>'.$row['age'].'</td>
                                              <td>'.$row['gender'].'</td>
                                              <td>'.$row['barangay'].'</td>
                                              <td><button class="btn btn-primary btn-sm" data-target="#editModal'.$row['id'].'" data-toggle="modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></td>
                                          </tr>
                                          ';

                                          require_once "edit_modal.php";
                                      }
                                      ?>
                                    </tbody>
                                </table>

                                <?php require_once "../deleteModal.php"; ?>
                                <?php require_once "../duplicate_error.php"; ?>

                            </form>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div> <!-- /.row -->
            </section><!-- /.content -->
            <?php
          }
          ?>
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

    <!-- Bootstrap 4 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables (Bootstrap 4 version) -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <script>
    $(document).ready(function() {
        $("#table").DataTable({
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 6]
            }],
            "order": [],
            "dom": '<"search"f><"top"l>rt<"bottom"ip><"clear">'
        });
    });
    </script>

    <?php }
  require_once "../../includes/footer.php"; ?>
</body>

</html>