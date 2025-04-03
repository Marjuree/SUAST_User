<?php
session_start();
require_once "../../configuration/config.php"; // Ensure database connection

// Debugging: Check session values
// Uncomment the next lines to debug session issues
/*
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit();
*/

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

// Regenerate session ID for security
session_regenerate_id(true);

// Store session values safely
$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant"; // Prevent XSS
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Applicant | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
</head>

<body class="skin-blue">
    <?php 
    require_once('includes/header.php');
    require_once('../../includes/head_css.php'); 
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
            <section class="content-header">
                <h1>Slot Reservation</h1>
                <p>Welcome, <strong><?php echo $first_name; ?></strong></p>
            </section>
            
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Your Reservations</h3>
                        </div>
                        <div class="box-body">
                            <p>Display reservation details here...</p>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>

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
