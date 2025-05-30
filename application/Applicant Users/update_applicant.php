<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['applicant_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Please log in first.']);
    exit;
}

$applicant_id = $_SESSION['applicant_id'];
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_applicant'])) {

    // Define expected fields
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
        $data[$field] = isset($_POST[$field]) ? mysqli_real_escape_string($con, $_POST[$field]) : '';
    }

    // Handle file uploads (image and document)
    $image_blob = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_blob = file_get_contents($_FILES['image']['tmp_name']);
    }

    $document_blob = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $document_blob = file_get_contents($_FILES['document']['tmp_name']);
    }

    // Build SQL with conditional BLOBs
    $sql = "UPDATE tbl_applicants SET
        lname=?, fname=?, mname=?, age=?, religion=?, nationality=?, civilstatus=?, contact=?,
        ethnicity=?, bdate=?, gender=?, email=?, purok=?, barangay=?, municipality=?, province=?,
        first_option=?, second_option=?, third_option=?, campus=?,
        n_mother=?, n_father=?, c_mother=?, c_father=?, m_occupation=?, f_occupation=?,
        m_address=?, f_address=?, living_status=?, siblings=?, birth_order=?, monthly_income=?,
        indigenous=?, basic_sector=?, date_applied=?";

    if ($image_blob) {
        $sql .= ", image_blob=?";
    }
    if ($document_blob) {
        $sql .= ", document_blob=?";
    }
    $sql .= " WHERE applicant_id=?";

    $stmt = $con->prepare($sql);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: '.$con->error]);
        exit;
    }

    // Bind params dynamically
    $types = str_repeat('s', count($fields)); // string types for all text fields
    if ($image_blob) $types .= 'b'; // blob type
    if ($document_blob) $types .= 'b'; // blob type
    $types .= 'i'; // integer for applicant_id

    // Build array of params
    $params = [];
    foreach ($fields as $field) {
        $params[] = $data[$field];
    }
    if ($image_blob) $params[] = $image_blob;
    if ($document_blob) $params[] = $document_blob;
    $params[] = (int)$applicant_id;

    // Use call_user_func_array for dynamic binding
    // Prepare references for bind_param
    $bind_names[] = $types;
    for ($i=0; $i<count($params); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $params[$i];
        $bind_names[] = &$$bind_name;
    }

    call_user_func_array([$stmt, 'bind_param'], $bind_names);

    // Execute
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Applicant updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating applicant: ' . $stmt->error]);
    }

    $stmt->close();
    $con->close();
    exit;

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}
?>
