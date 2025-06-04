<?php
session_start();
session_regenerate_id(true);
require_once "../../configuration/config.php"; // Ensure database connection

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .contact-form {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            border-radius: 15px;
            background: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .contact-form h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .contact-form .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .contact-form .form-group i {
            font-size: 20px;
            color: #003366;
            margin-right: 10px;
            min-width: 25px;
            text-align: center;
        }

        .contact-form .form-group span {
            font-size: 16px;
            color: #555;
        }

        .contact-message {
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        hr {
            background-color: #003366;
            border: none;
            margin: 20px auto;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-group i {
            font-size: 20px;
            color: #003366;
            min-width: 30px;
            text-align: center;
            margin-right: 10px;
        }

        .contact-item {
            flex-grow: 1;
        }

        .contact-item span {
            display: block;
            font-size: 16px;
            color: #222;
        }

        .contact-item hr {
            border: none;
            border-bottom: 1.5px solid #003366;
            margin: 4px 0 0 0;
            width: 100%;
        }
    </style>
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
                <h2 style="text-align:center;">Contact Details</h2>
                <hr>
                <div class="contact-message"
                    style="background-color: #e0f7fa; border-left: 8px solid #003366; padding: 10px; margin: 20px auto; max-width: 500px; color: #003366;">
                    For more information, contact the DOrSU Office of the Student Counseling and Development (OSCD).
                </div>
                <div class="contact-form" style="background-color:rgb(212, 212, 212);">
                    <?php
                    $query = "SELECT * FROM tbl_contact";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
            <div class="form-group">
                <i class="fas fa-phone"></i>
                <div class="contact-item">
                    <span>' . htmlspecialchars($row['phone']) . '</span>
                    <hr>
                </div>
            </div>
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <div class="contact-item">
                    <span>' . htmlspecialchars($row['email']) . '</span>
                    <hr>
                </div>
            </div>
            <div class="form-group">
                <i class="fab fa-facebook"></i>
                <div class="contact-item">
                    <span>OSCD.DOrSU</span>
                    <hr>
                </div>
            </div>';
                        }
                    } else {
                        echo "<p style='text-align: center; color: #999;'>No contact details found.</p>";
                    }
                    ?>
                </div>




            </section>
        </aside>
    </div>

    <?php require_once "../../includes/footer.php"; ?>
</body>

</html>