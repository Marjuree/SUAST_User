<?php
session_start();

if (!isset($_SESSION['applicant_id'])) {
    showSweetAlert("Access denied. Please log in first.", "error", "../../php/error.php");
    exit;
}

$applicant_id = $_SESSION['applicant_id'];
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_applicant'])) {

    echo ".";
    $fields = [
        'lname', 'fname', 'mname', 'age', 'religion', 'nationality', 'civilstatus',
        'contact', 'ethnicity', 'bdate', 'gender', 'email', 'purok', 'barangay', 'municipality',
        'province', 'first_option', 'second_option', 'third_option', 'campus',
        'n_mother', 'n_father', 'c_mother', 'c_father', 'm_occupation', 'f_occupation',
        'm_address', 'f_address', 'living_status', 'siblings', 'birth_order', 'monthly_income',
        'indigenous', 'basic_sector', 'date_applied'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = mysqli_real_escape_string($con, $_POST[$field] ?? '');
    }

    $image_blob = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_blob = file_get_contents($_FILES['image']['tmp_name']);
    }

    $document_blob = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $document_blob = file_get_contents($_FILES['document']['tmp_name']);
    }

    $sql = "UPDATE tbl_applicants SET
        lname=?, fname=?, mname=?, age=?, religion=?, nationality=?, civilstatus=?, contact=?,
        ethnicity=?, bdate=?, gender=?, email=?, purok=?, barangay=?, municipality=?, province=?,
        first_option=?, second_option=?, third_option=?, campus=?,
        n_mother=?, n_father=?, c_mother=?, c_father=?, m_occupation=?, f_occupation=?,
        m_address=?, f_address=?, living_status=?, siblings=?, birth_order=?, monthly_income=?,
        indigenous=?, basic_sector=?, date_applied=?"
        . ($image_blob ? ", image_blob=?" : "") 
        . ($document_blob ? ", document_blob=?" : "") 
        . " WHERE applicant_id=?";

    $stmt = $con->prepare($sql);

    // Binding logic
    if ($image_blob && $document_blob) {
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssssssssssssssb",  // Corrected type string for BLOBs (both image and document)
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['email'], $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $image_blob, $document_blob, $applicant_id
        );
    } elseif ($image_blob) {
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssssb",  // Corrected type string for image BLOB only
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['email'], $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $image_blob, $applicant_id
        );
    } elseif ($document_blob) {
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssssb",  // Corrected type string for document BLOB only
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['email'], $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $document_blob, $applicant_id
        );
    } else {
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssi",  // No BLOBs, just strings and integer
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['email'], $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $applicant_id
        );
    }

    if ($stmt->execute()) {
        showSweetAlert("Applicant updated successfully!", "success", "applicant.php");
    } else {
        showSweetAlert("Error updating applicant.", "error", "applicant.php");
    }

    $stmt->close();
    $con->close();

} else {
    showSweetAlert("Invalid request", "error", "applicant.php");
    exit;
}


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

