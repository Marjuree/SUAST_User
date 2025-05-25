<?php
// edit_service_request.php

session_start();
header('Content-Type: application/json');

require_once "../../configuration/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $date_request = trim($_POST['date_request'] ?? '');
    $reason = trim($_POST['reason'] ?? '');

    if ($id <= 0 || empty($date_request) || empty($reason)) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit;
    }

    // Check if files are uploaded and zip them
    if (isset($_FILES['service_file']) && $_FILES['service_file']['error'][0] === UPLOAD_ERR_OK) {
        $zip = new ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'service_file_') . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
            echo json_encode(['success' => false, 'message' => 'Could not create ZIP file.']);
            exit;
        }

        foreach ($_FILES['service_file']['tmp_name'] as $index => $tmpName) {
            $originalName = $_FILES['service_file']['name'][$index];
            $zip->addFile($tmpName, $originalName);
        }

        $zip->close();

        $zipData = file_get_contents($zipFileName);
        unlink($zipFileName);

        $fileName = "service_file_" . $id . ".zip";

        $stmt = $con->prepare("UPDATE tbl_service_requests SET date_request = ?, reason = ?, attachment = ?, file_name = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $date_request, $reason, $zipData, $fileName, $id);
    } else {
        // No new file uploaded
        $stmt = $con->prepare("UPDATE tbl_service_requests SET date_request = ?, reason = ? WHERE id = ?");
        $stmt->bind_param("ssi", $date_request, $reason, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Request updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating request: ' . $stmt->error]);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
