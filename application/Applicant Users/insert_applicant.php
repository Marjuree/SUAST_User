<?php
session_start();
require_once "../../configuration/config.php";

// Ensure the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    showSweetAlert('User not logged in.', 'error', '../../php/error.php?welcome=Please login first');
    exit();
}

$applicant_id = $_SESSION['applicant_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    echo ".";
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
    $email = $_POST['email'];
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

    // Handle optional file uploads
    $image_data = null;
    $document_data = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_blob = file_get_contents($image_tmp_name);
        $image_data = mysqli_real_escape_string($con, $image_blob);
    }

    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $document_tmp_name = $_FILES['document']['tmp_name'];
        $document_blob = file_get_contents($document_tmp_name);
        $document_data = mysqli_real_escape_string($con, $document_blob);
    }

    // Construct the SQL query
    $sql = "INSERT INTO tbl_applicants (
        applicant_id, lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity, contact,
        purok, barangay, municipality, province, first_option, second_option, third_option, campus, gender, email,
        n_mother, n_father, c_mother, c_father, m_occupation, f_occupation, m_address, f_address, living_status,
        siblings, birth_order, monthly_income, indigenous, basic_sector, image_blob, document_blob, date_applied
    ) VALUES (
        '$applicant_id', '$lname', '$fname', '$mname', '$bdate', '$age', '$religion', '$nationality', '$civilstatus', '$ethnicity',
        '$contact', '$purok', '$barangay', '$municipality', '$province', '$first_option', '$second_option', '$third_option', '$campus',
        '$gender', '$email', '$n_mother', '$n_father', '$c_mother', '$c_father', '$m_occupation', '$f_occupation',
        '$m_address', '$f_address', '$living_status', '$siblings', '$birth_order', '$monthly_income', '$indigenous', '$basic_sector',
        " . ($image_data !== null ? "'$image_data'" : "NULL") . ",
        " . ($document_data !== null ? "'$document_data'" : "NULL") . ",
        '$date_applied'
    )";

    if (mysqli_query($con, $sql)) {
        showSweetAlert('Applicant added successfully!', 'success', 'applicant.php');
    } else {
        error_log("SQL Error: " . mysqli_error($con) . " | Query: " . $sql, 3, 'error_log.txt');
        showSweetAlert("Error: " . mysqli_error($con), 'error', 'applicant.php');
    }
}

mysqli_close($con);

// SweetAlert2 function
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
