<?php
session_start();
session_regenerate_id(true);
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


// Store session values safely
$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant"; // Prevent XSS
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Contact Details | Dash</title>
    <link rel="shortcut icon" href="../../../img/favicon.png" />
</head>
<body class="skin-blue">
    <?php 
        require_once('includes/header.php');
        require_once('../../includes/head_css.php');
    ?>
    
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>
        
        <aside class="right-side">
          
            
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="container mt-4">
                            <h2 class="text-center">Contact Details</h2>
                            <div class="contact-container mt-3" id="contactList">
                                <?php
                                $query = "SELECT * FROM tbl_contact";
                                $result = mysqli_query($con, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<table class='table table-bordered'>";
                                    echo "<tr><th>Name</th><th>Email</th><th>Phone</th></tr>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['phone']}</td></tr>";
                                    }
                                    echo "</table>";
                                } else {
                                    echo "<p>No contact details found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
    
    <?php require_once "../../includes/footer.php"; ?>
</body>
</html>
