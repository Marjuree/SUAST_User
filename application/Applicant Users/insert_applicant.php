<?php
session_start();
require_once "../../configuration/config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['applicant_id'])) {
    sendJsonResponse('User not logged in.', 'error');
}

$applicant_id = $_SESSION['applicant_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('Invalid request method.', 'error');
}

// Sanitize and validate inputs function
function getPost($key, $default = "") {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

// Fetch all inputs safely
$lname = getPost('lname');
$fname = getPost('fname');
$mname = getPost('mname') !== "" ? getPost('mname') : null;
$bdate = getPost('bdate');
$age = (isset($_POST['age']) && is_numeric($_POST['age'])) ? (int) $_POST['age'] : 0;
$religion = getPost('religion');
$nationality = getPost('nationality');
$civilstatus = getPost('civilstatus');
$ethnicity = getPost('ethnicity');
$contact = getPost('contact');
$purok = getPost('purok');
$barangay = getPost('barangay');
$municipality = getPost('municipality');
$province = getPost('province');
$first_option = getPost('first_option');
$second_option = getPost('second_option');
$third_option = getPost('third_option');
$campus = getPost('campus');
$gender = getPost('gender');
$n_mother = getPost('n_mother');
$n_father = getPost('n_father');
$c_mother = getPost('c_mother');
$c_father = getPost('c_father');
$m_occupation = getPost('m_occupation');
$f_occupation = getPost('f_occupation');
$m_address = getPost('m_address');
$f_address = getPost('f_address');
$living_status = getPost('living_status');
$siblings = (isset($_POST['siblings']) && is_numeric($_POST['siblings'])) ? (int) $_POST['siblings'] : 0;
$birth_order = (isset($_POST['birth_order']) && is_numeric($_POST['birth_order'])) ? (int) $_POST['birth_order'] : 0;
$monthly_income = (isset($_POST['monthly_income']) && is_numeric($_POST['monthly_income'])) ? (float) $_POST['monthly_income'] : 0;
$indigenous = getPost('indigenous');
$basic_sector = getPost('basic_sector');
$date_applied = getPost('date_applied');
$applicantNo = getPost('applicantNo');

// Helper to get base64 encoded file contents or empty string
function getBase64FileContent($fileKey) {
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        return base64_encode(file_get_contents($_FILES[$fileKey]['tmp_name']));
    }
    return "";
}

$suast_form_data = getBase64FileContent('suast_form');
$image_data = getBase64FileContent('image');
$document_data = getBase64FileContent('document');
$certificate_data = getBase64FileContent('certificate');
$tor_data = getBase64FileContent('tor');
$card_data = getBase64FileContent('card');
$certificate_rating_data = getBase64FileContent('certificate_rating');


// Prepare and bind
$stmt = $con->prepare("
    INSERT INTO tbl_applicants (
        applicant_id, lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity, contact,
        purok, barangay, municipality, province, first_option, second_option, third_option, campus, gender,
        n_mother, n_father, c_mother, c_father, m_occupation, f_occupation, m_address, f_address, living_status,
        siblings, birth_order, monthly_income, indigenous, basic_sector,
        image_blob, document_blob, certificate_blob, tor_blob, card_blob, certificate_rating_blob, suast_form_blob,
        date_applied, applicantNo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

// Bind types:
// s = string, i = integer, d = double (for monthly_income)
$bind_types = "ssssissssssssssssssssssssssii d ssssssssssssssss";

$stmt->bind_param(
    "ssssissssssssssssssssssssssii d ssssssssssssssss",
    $applicant_id, $lname, $fname, $mname, $bdate, $age, $religion, $nationality, $civilstatus, $ethnicity, $contact,
    $purok, $barangay, $municipality, $province, $first_option, $second_option, $third_option, $campus, $gender,
    $n_mother, $n_father, $c_mother, $c_father, $m_occupation, $f_occupation, $m_address, $f_address, $living_status,
    $siblings, $birth_order, $monthly_income, $indigenous, $basic_sector,
    $image_data, $document_data, $certificate_data, $tor_data, $card_data, $certificate_rating_data, $suast_form_data,
    $date_applied, $applicantNo
);

if ($stmt->execute()) {
    sendJsonResponse('Step 1 is complete!', 'success');
} else {
    error_log("SQL Error: " . $stmt->error, 3, 'error_log.txt');
    sendJsonResponse("Error: " . $stmt->error, 'error');
}

$stmt->close();
$con->close();

function sendJsonResponse($message, $type)
{
    echo json_encode([
        'type' => $type,
        'message' => $message
    ]);
    exit();
}
?>
