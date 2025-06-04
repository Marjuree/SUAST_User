<?php
require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $leave_date = $_POST['leave_date'];
    $leave_end_date = $_POST['leave_end_date'];

    if (isset($_FILES['leave_form']) && !empty($_FILES['leave_form']['name'][0]) && $_FILES['leave_form']['error'][0] === UPLOAD_ERR_OK) {
        $zip = new ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'leave_form_') . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
            die("Could not create ZIP file.");
        }

        foreach ($_FILES['leave_form']['tmp_name'] as $index => $tmpName) {
            $originalName = $_FILES['leave_form']['name'][$index];
            $zip->addFile($tmpName, $originalName);
        }

        $zip->close();

        $zipData = file_get_contents($zipFileName);
        unlink($zipFileName);

        $fileName = "leave_form_" . $id . ".zip"; // a meaningful name

        $stmt = $con->prepare("UPDATE tbl_leave_requests SET date_request = ?, leave_end_date = ?, leave_form = ?, file_name = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $leave_date, $leave_end_date, $zipData, $fileName, $id);
    } else {
        $stmt = $con->prepare("UPDATE tbl_leave_requests SET date_request = ?, leave_end_date = ? WHERE id = ?");
        $stmt->bind_param("ssi", $leave_date, $leave_end_date, $id);
    }

    if ($stmt->execute()) {
        header("Location: leave_requests.php?msg=updated");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
