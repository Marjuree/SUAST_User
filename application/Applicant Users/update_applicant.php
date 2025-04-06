<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<script>alert('Access denied. Please log in first.'); window.location.href='../../php/error.php';</script>";
    exit;
}

$applicant_id = $_SESSION['applicant_id'];

require_once "../../configuration/config.php";

// Only process if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_applicant'])) {

    // Sanitize inputs for the applicant's information
    $fields = [
        'lname', 'fname', 'mname', 'age', 'religion', 'nationality', 'civilstatus',
        'contact', 'ethnicity', 'bdate', 'gender', 'purok', 'barangay', 'municipality',
        'province', 'first_option', 'second_option', 'third_option', 'campus',
        'n_mother', 'n_father', 'c_mother', 'c_father', 'm_occupation', 'f_occupation',
        'm_address', 'f_address', 'living_status', 'siblings', 'birth_order', 'monthly_income',
        'indigenous', 'basic_sector', 'date_applied'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = mysqli_real_escape_string($con, $_POST[$field] ?? '');
    }

    // Image upload handling (optional)
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        // Optional: validate image type
        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_type, $allowed_types)) {
            echo "<script>alert('Invalid image type.'); window.history.back();</script>";
            exit;
        }

        // Upload the image
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // Document upload handling (optional)
    $document_name = '';
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $document_name = basename($_FILES['document']['name']);
        $target_file = $target_dir . $document_name;

        // Optional: validate document type
        $document_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

        if (!in_array($document_type, $allowed_types)) {
            echo "<script>alert('Invalid document type.'); window.history.back();</script>";
            exit;
        }

        // Upload the document
        move_uploaded_file($_FILES['document']['tmp_name'], $target_file);
    }

    // Update SQL query to update applicant information based on applicant_id
    $sql = "UPDATE tbl_applicants SET
    lname=?, fname=?, mname=?, age=?, religion=?, nationality=?, civilstatus=?, contact=?,
    ethnicity=?, bdate=?, gender=?, purok=?, barangay=?, municipality=?, province=?,
    first_option=?, second_option=?, third_option=?, campus=?,
    n_mother=?, n_father=?, c_mother=?, c_father=?, m_occupation=?, f_occupation=?,
    m_address=?, f_address=?, living_status=?, siblings=?, birth_order=?, monthly_income=?,
    indigenous=?, basic_sector=?, date_applied=?"
    . ($image_name ? ", image=?" : "") 
    . ($document_name ? ", document=?" : "") 
    . " WHERE applicant_id=?";

    $stmt = $con->prepare($sql);

    // Check if image or document is uploaded, adjust the bind_param accordingly
    if ($image_name && $document_name) {
        // Image and document uploaded, add extra parameters for both
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssssssssssi", // 34 's' and 1 'i' for applicant_id
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $image_name, $document_name, $applicant_id
        );
    } else if ($image_name) {
        // Only image uploaded, add an extra parameter for the image
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssi", // 34 's' and 1 'i' for applicant_id
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $image_name, $applicant_id
        );
    } else if ($document_name) {
        // Only document uploaded, add an extra parameter for the document
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssi", // 34 's' and 1 'i' for applicant_id
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $document_name, $applicant_id
        );
    } else {
        // No image or document uploaded, bind parameters without those fields
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssssssi", // 34 's' and 1 'i' for applicant_id
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['purok'], $data['barangay'], $data['municipality'], $data['province'], $data['first_option'],
            $data['second_option'], $data['third_option'], $data['campus'], $data['n_mother'], $data['n_father'],
            $data['c_mother'], $data['c_father'], $data['m_occupation'], $data['f_occupation'], $data['m_address'],
            $data['f_address'], $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $applicant_id
        );
    }

    // Execute the query and give feedback
    if ($stmt->execute()) {
        echo "<script>
            alert('Applicant updated successfully!');
            window.location.href = 'applicant.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating applicant.');
            window.location.href = 'applicant.php';
        </script>";
    }

    // Close statement and connection
    $stmt->close();
    $con->close();

} else {
    echo "<script>alert('Invalid request'); window.location.href = 'applicant.php';</script>";
    exit();
}
?>
