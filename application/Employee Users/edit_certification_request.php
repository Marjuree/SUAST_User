<?php
require_once "../../configuration/config.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $date_request = $_POST['date_request'] ?? null;
    $reason = $_POST['reason'] ?? null;

    if (!$id || !$date_request || !$reason) {
        echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
        exit;
    }

    // Check if multiple files uploaded
    $hasFiles = isset($_FILES['certification_file']) &&
                !empty($_FILES['certification_file']['name'][0]) &&
                $_FILES['certification_file']['error'][0] === UPLOAD_ERR_OK;

    if ($hasFiles) {
        $zip = new ZipArchive();
        $zipFileName = tempnam(sys_get_temp_dir(), 'certification_file_') . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
            echo json_encode(['success' => false, 'message' => 'Could not create ZIP file.']);
            exit;
        }

        // Add all uploaded files to zip
        foreach ($_FILES['certification_file']['tmp_name'] as $index => $tmpName) {
            $originalName = $_FILES['certification_file']['name'][$index];
            if (is_uploaded_file($tmpName)) {
                $zip->addFile($tmpName, $originalName);
            }
        }

        $zip->close();

        $zipData = file_get_contents($zipFileName);
        unlink($zipFileName);

        $fileName = "certification_file_" . $id . ".zip";

        // Prepare and bind statement with blob for zip data
        $stmt = $con->prepare("UPDATE tbl_certification_requests SET date_request = ?, reason = ?, certification_file = ?, file_name = ? WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $con->error]);
            exit;
        }

        // "ssbsi" means string, string, blob, string, integer
        $null = NULL; // For blob data binding placeholder
        $stmt->bind_param("ssbsi", $date_request, $reason, $null, $fileName, $id);

        // Send the blob data
        $stmt->send_long_data(2, $zipData);

    } else {
        // No files uploaded, just update date_request and reason
        $stmt = $con->prepare("UPDATE tbl_certification_requests SET date_request = ?, reason = ? WHERE id = ?");
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $con->error]);
            exit;
        }
        $stmt->bind_param("ssi", $date_request, $reason, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Request updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
