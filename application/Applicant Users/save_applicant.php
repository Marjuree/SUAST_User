<?php
require_once "../../configuration/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $age   = (int) $_POST['age'];

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../../uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $upload_dir . uniqid() . "_" . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "Image upload failed.";
            exit;
        }
    }

    // Insert into database
    $sql = "INSERT INTO tbl_applicants (fname, lname, age, image) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssis", $fname, $lname, $age, $image_path);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "fail";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
