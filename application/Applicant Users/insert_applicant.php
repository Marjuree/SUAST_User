<?php
session_start();
require_once "../../configuration/config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['applicant_id'])) {
    sendJsonResponse('User not logged in.', 'error');
    exit();
}

$applicant_id = $_SESSION['applicant_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $bdate = $_POST['bdate'];
    $age = isset($_POST['age']) && is_numeric($_POST['age']) ? (int) $_POST['age'] : 0;
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
    $suast_form_data = null;
    $image_data = null;
    $document_data = null;
    $certificate_data = null;
    $tor_data = null;
    $card_data = null;
    $certificate_rating_data = null;


    if (isset($_FILES['suast_form']) && $_FILES['suast_form']['error'] === UPLOAD_ERR_OK) {
        $suast_form_tmp_name = $_FILES['suast_form']['tmp_name'];
        $suast_form_blob = file_get_contents($suast_form_tmp_name);
        $suast_form_data = mysqli_real_escape_string($con, $suast_form_blob);
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_data = mysqli_real_escape_string($con, file_get_contents($_FILES['image']['tmp_name']));
    }
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $document_data = mysqli_real_escape_string($con, file_get_contents($_FILES['document']['tmp_name']));
    }
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
        $certificate_data = mysqli_real_escape_string($con, file_get_contents($_FILES['certificate']['tmp_name']));
    }
    if (isset($_FILES['tor']) && $_FILES['tor']['error'] === UPLOAD_ERR_OK) {
        $tor_data = mysqli_real_escape_string($con, file_get_contents($_FILES['tor']['tmp_name']));
    }
    if (isset($_FILES['card']) && $_FILES['card']['error'] === UPLOAD_ERR_OK) {
        $card_data = mysqli_real_escape_string($con, file_get_contents($_FILES['card']['tmp_name']));
    }
    if (isset($_FILES['certificate_rating']) && $_FILES['certificate_rating']['error'] === UPLOAD_ERR_OK) {
        $certificate_rating_data = mysqli_real_escape_string($con, file_get_contents($_FILES['certificate_rating']['tmp_name']));
    }

    $sql = "INSERT INTO tbl_applicants (
    applicant_id, lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity, contact,
    purok, barangay, municipality, province, first_option, second_option, third_option, campus, gender, email,
    n_mother, n_father, c_mother, c_father, m_occupation, f_occupation, m_address, f_address, living_status,
    siblings, birth_order, monthly_income, indigenous, basic_sector, image_blob, document_blob, certificate_blob, tor_blob, card_blob, certificate_rating_blob, suast_form_blob, date_applied
    ) VALUES (
        '$applicant_id', '$lname', '$fname', '$mname', '$bdate', '$age', '$religion', '$nationality', '$civilstatus', '$ethnicity',
        '$contact', '$purok', '$barangay', '$municipality', '$province', '$first_option', '$second_option', '$third_option', '$campus',
        '$gender', '$email', '$n_mother', '$n_father', '$c_mother', '$c_father', '$m_occupation', '$f_occupation',
        '$m_address', '$f_address', '$living_status', '$siblings', '$birth_order', '$monthly_income', '$indigenous', '$basic_sector',
        " . ($image_data !== null ? "'$image_data'" : "NULL") . ",
        " . ($document_data !== null ? "'$document_data'" : "NULL") . ",
        " . ($certificate_data !== null ? "'$certificate_data'" : "NULL") . ",
        " . ($tor_data !== null ? "'$tor_data'" : "NULL") . ",
        " . ($card_data !== null ? "'$card_data'" : "NULL") . ",
        " . ($certificate_rating_data !== null ? "'$certificate_rating_data'" : "NULL") . ",
        " . ($suast_form_data !== null ? "'$suast_form_data'" : "NULL") . ",
        '$date_applied'
    )";


    if (mysqli_query($con, $sql)) {
        sendJsonResponse('Step 1 is complete!', 'success');
    } else {
        error_log("SQL Error: " . mysqli_error($con) . " | Query: " . $sql, 3, 'error_log.txt');
        sendJsonResponse("Error: " . mysqli_error($con), 'error');
    }
}

mysqli_close($con);

function sendJsonResponse($message, $type)
{
    echo json_encode([
        'type' => $type,
        'message' => $message
    ]);
    exit();
}
?>