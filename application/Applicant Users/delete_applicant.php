<?php
require_once "../../configuration/config.php";

// Check if ID is passed via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    echo ".";
    // Attempt to delete the applicant
    $sql = "DELETE FROM tbl_applicants WHERE id = $id";
    $query = mysqli_query($con, $sql);

    if ($query) {
        showSweetAlert("Applicant deleted successfully!", "success", "applicant.php");
    } else {
        showSweetAlert("Error deleting applicant. Please try again!", "error", "applicant.php");
    }
} else {
    // Invalid or missing ID
    showSweetAlert("Invalid applicant ID.", "error", "applicant.php");
}

exit();


// 🔔 SweetAlert2 Alert Function
function showSweetAlert($message, $type, $redirect) {
    $icon = $type === 'success' ? 'success' : 'error';
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: '$icon',
            title: '".ucfirst($type)."',
            text: `$message`,
            confirmButtonText: 'OK',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = '$redirect';
        });
    </script>";
}
?>
