<?php
session_start();
require_once "../../configuration/config.php";

// Only process if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_applicant'])) {
    $id = $_POST['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        echo "<script>alert('Invalid applicant ID.'); window.location.href='applicant.php';</script>";
        exit;
    }

    // Sanitize inputs
    $fields = [
        'lname', 'fname', 'mname', 'age', 'religion', 'nationality', 'civilstatus',
        'contact', 'ethnicity', 'bdate', 'gender', 'purok', 'barangay', 'province',
        'first_option', 'second_option', 'third_option', 'campus',
        'n_mother', 'c_mother', 'm_occupation', 'm_address',
        'n_father', 'c_father', 'f_occupation', 'f_address',
        'living_status', 'siblings', 'birth_order', 'monthly_income',
        'indigenous', 'basic_sector', 'date_applied'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = mysqli_real_escape_string($con, $_POST[$field] ?? '');
    }

    // Image upload handling
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

    // Build SQL with prepared statement
    $sql = "UPDATE tbl_applicants SET
        lname=?, fname=?, mname=?, age=?, religion=?, nationality=?, civilstatus=?, contact=?,
        ethnicity=?, bdate=?, gender=?, purok=?, barangay=?, province=?,
        first_option=?, second_option=?, third_option=?, campus=?,
        n_mother=?, c_mother=?, m_occupation=?, m_address=?,
        n_father=?, c_father=?, f_occupation=?, f_address=?,
        living_status=?, siblings=?, birth_order=?, monthly_income=?,
        indigenous=?, basic_sector=?, date_applied=?
        " . ($image_name ? ", image=?" : "") . " 
        WHERE id=?";

    $stmt = $con->prepare($sql);

    if ($image_name) {
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssssssi",
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['purok'], $data['barangay'], $data['province'], $data['first_option'], $data['second_option'],
            $data['third_option'], $data['campus'], $data['n_mother'], $data['c_mother'], $data['m_occupation'],
            $data['m_address'], $data['n_father'], $data['c_father'], $data['f_occupation'], $data['f_address'],
            $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $image_name, $id
        );
    } else {
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssi",
            $data['lname'], $data['fname'], $data['mname'], $data['age'], $data['religion'], $data['nationality'],
            $data['civilstatus'], $data['contact'], $data['ethnicity'], $data['bdate'], $data['gender'],
            $data['purok'], $data['barangay'], $data['province'], $data['first_option'], $data['second_option'],
            $data['third_option'], $data['campus'], $data['n_mother'], $data['c_mother'], $data['m_occupation'],
            $data['m_address'], $data['n_father'], $data['c_father'], $data['f_occupation'], $data['f_address'],
            $data['living_status'], $data['siblings'], $data['birth_order'], $data['monthly_income'],
            $data['indigenous'], $data['basic_sector'], $data['date_applied'], $id
        );
    }

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

    $stmt->close();
    $con->close();
} else {
    echo "<script>alert('Invalid request'); window.location.href = 'applicant.php';</script>";
    exit();
}
?>
