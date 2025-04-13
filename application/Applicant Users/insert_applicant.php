<?php
session_start();
require_once "../../configuration/config.php";

// Make sure the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>
            alert('User not logged in.');
            window.location.href = '../../php/error.php?welcome=Please login first';
          </script>";
    exit();
}

$applicant_id = $_SESSION['applicant_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $bdate = $_POST['bdate'];
    $age = isset($_POST['age']) && is_numeric($_POST['age']) ? (int)$_POST['age'] : 0;
    $religion = $_POST['religion'];
    $nationality = $_POST['nationality'];
    $civilstatus = $_POST['civilstatus'];
    $ethnicity = $_POST['ethnicity'];
    $contact = $_POST['contact'];
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $first_option = $_POST['first_option'];
    $second_option = $_POST['second_option'];
    $third_option = $_POST['third_option'];
    $campus = $_POST['campus'];
    $gender = $_POST['gender'];
    $email = $_POST['email']; // Added email field
    $n_mother = $_POST['n_mother'];
    $n_father = $_POST['n_father'];
    $c_mother = $_POST['c_mother'];
    $c_father = $_POST['c_father'];
    $m_occupation = $_POST['m_occupation'];
    $f_occupation = $_POST['f_occupation'];
    $m_address = $_POST['m_address'];
    $f_address = $_POST['f_address'];
    $living_status = $_POST['living_status'];

    $siblings = isset($_POST['siblings']) && is_numeric($_POST['siblings']) ? $_POST['siblings'] : 0;
    $birth_order = isset($_POST['birth_order']) && is_numeric($_POST['birth_order']) ? $_POST['birth_order'] : 0;
    $monthly_income = isset($_POST['monthly_income']) && is_numeric($_POST['monthly_income']) ? $_POST['monthly_income'] : 0;

    $indigenous = $_POST['indigenous'];
    $basic_sector = $_POST['basic_sector'];
    $date_applied = $_POST['date_applied'];

    // File upload for Profile Image
    $new_image_name = null;  // Initialize as null
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $new_image_name = file_get_contents($image_tmp_name); // Store image as binary
    }

    // File upload for Document (e.g., PDF, DOC, Image)
    $new_document_name = null;  // Initialize as null
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $document_tmp_name = $_FILES['document']['tmp_name'];
        $new_document_name = file_get_contents($document_tmp_name); // Store document as binary
    }

    // SQL query to insert data into the tbl_applicants table
    $sql = "INSERT INTO tbl_applicants 
        (applicant_id, lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity, contact, 
         purok, barangay, municipality, province, first_option, second_option, third_option, campus, 
         gender, email, n_mother, n_father, c_mother, c_father, m_occupation, f_occupation, m_address, f_address, 
         living_status, siblings, birth_order, monthly_income, indigenous, basic_sector, image_blob, document_blob, date_applied) 
        VALUES 
        ('$applicant_id', '$lname', '$fname', '$mname', '$bdate', '$age', '$religion', '$nationality', '$civilstatus', 
         '$ethnicity', '$contact', '$purok', '$barangay', '$municipality', '$province', '$first_option', 
         '$second_option', '$third_option', '$campus', '$gender', '$email', '$n_mother', '$n_father', '$c_mother', '$c_father', 
         '$m_occupation', '$f_occupation', '$m_address', '$f_address', '$living_status', '$siblings', '$birth_order', 
         '$monthly_income', '$indigenous', '$basic_sector', '" . mysqli_real_escape_string($con, $new_image_name) . "', '" . mysqli_real_escape_string($con, $new_document_name) . "', '$date_applied')";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Applicant added successfully!'); window.location.href = 'applicant.php';</script>";
    } else {
        error_log("SQL Error: " . mysqli_error($con) . " | Query: " . $sql, 3, 'error_log.txt');
        echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href = 'applicant.php';</script>";
    }
}

mysqli_close($con);
?>
