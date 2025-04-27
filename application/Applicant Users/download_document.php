<?php
session_start();
require_once '../../configuration/config.php';

// Check if the document ID is passed
if (isset($_GET['id'])) {
    $document_id = $_GET['id'];

    // SQL query to fetch the document
    $sql = "SELECT document_blob, id FROM tbl_applicants WHERE id = ?";
    $stmt = $con->prepare($sql);

    if ($stmt === false) {
        error_log("SQL Prepare failed: " . $con->error);
        die("SQL Prepare failed: " . $con->error);
    }

    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($document_blob, $id);
    $stmt->fetch();

    // Check if the document exists
    if ($document_blob) {
        // Set headers to prompt download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="document_' . $id . '.pdf"');
        header('Content-Length: ' . strlen($document_blob));
        echo $document_blob; // Output the document
    } else {
        echo "Document not found or doesn't exist.";
    }

    $stmt->close();
} else {
    echo "Invalid document ID.";
}
?>
