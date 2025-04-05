<?php
require_once "../../configuration/config.php";

// Check if ID is passed via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the applicant
    $sql = "DELETE FROM tbl_applicants WHERE id = $id";
    $query = mysqli_query($con, $sql);

    if ($query) {
        // Redirect with success message
        echo "<script>
                alert('Applicant deleted successfully!');
                window.location.href = 'applicant.php';  // Redirect back to the applicant list
              </script>";
    } else {
        // Redirect with error message
        echo "<script>
                alert('Error deleting applicant. Please try again!');
                window.location.href = 'applicant.php';  // Redirect back to the applicant list
              </script>";
    }
} else {
    // Invalid or missing ID
    echo "<script>
            alert('Invalid applicant ID.');
            window.location.href = 'applicant.php';  // Redirect back to the applicant list
          </script>";
}
exit();
