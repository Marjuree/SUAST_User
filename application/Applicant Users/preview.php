<?php
session_start();
require_once "../../configuration/config.php";
session_regenerate_id(true);

// Redirect to login if not logged in as Applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../login.php");
    exit();
}


$applicant_id = $_SESSION['applicant_id'];

$query_reservations = "SELECT * FROM tbl_reservation WHERE applicant_id = ?";
$stmt = $con->prepare($query_reservations);
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result_reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Exam Schedule Preview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
        }

        .reservation-preview {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            max-width: 400px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .reservation-preview p {
            font-size: 16px;
            margin: 8px 0;
        }
    </style>
</head>

<body>
    <section class="content container" style="margin-top:20px;">
        <div class="box">
            <div class="box-header"
                style="background: #002B5B; color: #fff; padding: 10px; border-radius: 20px; height: 30px; display: flex; justify-content: center; align-items: center;">
                <h3 class="box-title" style="margin: 0; font-size: 14px; font-weight: bold;">Your Selected Exam Schedule</h3>
            </div>

            <?php if ($result_reservations->num_rows > 0): ?>
                <?php while ($row = $result_reservations->fetch_assoc()): ?>
                    <div class="reservation-preview"
                        style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; background: #ffffff; border-radius: 5px;">
                        <p><strong style="font-size: 13px;">Venue:</strong> <span style="font-size: 13px;"><?= htmlspecialchars($row['venue']) ?></span></p>
                        <p><strong style="font-size: 13px;">Testing Room No:</strong> <span style="font-size: 13px;"><?= htmlspecialchars($row['room']) ?></span></p>
                        <p><strong style="font-size: 13px;">Date:</strong>
                            <span style="font-size: 13px;"><?= $row['exam_date'] ? date('F j, Y', strtotime($row['exam_date'])) : '<em>Not Selected</em>' ?></span>
                        </p>
                        <p><strong style="font-size: 13px;">Time:</strong>
                            <span style="font-size: 13px;"><?= htmlspecialchars($row['exam_time']) ?: '<em>Not Selected</em>' ?></span>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted" style="font-size: 12px;">No exam schedule found.</p>
            <?php endif; ?>
        </div>

        <div style="text-align: right; margin-top: -20px;">
            <button id="finalSubmitBtn" class="btn btn-success">Confirm</button>
        </div>
    </section>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#finalSubmitBtn').on('click', function () {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to submit your application finally?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Submitted Complete!',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            willClose: () => {
                                window.location.href = 'dashboard.php';
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
