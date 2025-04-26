<?php
require_once "../../configuration/config.php";

// Prevent any output before headers
ob_start();

if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // Validate that the ID is an integer
    if (!filter_var($file_id, FILTER_VALIDATE_INT)) {
        die("Invalid file ID.");
    }

    // Prepare the SQL query
    $sql = "SELECT attachment, file_name FROM tbl_service_requests WHERE id = ?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die("Error preparing the query: " . $con->error);
    }

    $stmt->bind_param("i", $file_id);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($file_data, $file_name);
            $stmt->fetch();

            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Set proper content type based on file extension
            switch ($file_extension) {
                case 'pdf':
                    header("Content-Type: application/pdf");
                    break;
                case 'doc':
                    header("Content-Type: application/msword");
                    break;
                case 'docx':
                    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
                    break;
                case 'jpg':
                case 'jpeg':
                    header("Content-Type: image/jpeg");
                    break;
                case 'png':
                    header("Content-Type: image/png");
                    break;
                case 'gif':
                    header("Content-Type: image/gif");
                    break;
                default:
                    header("Content-Type: application/octet-stream");
                    break;
            }

            // Download headers
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            header("Content-Disposition: attachment; filename=\"" . basename($file_name) . "\"");
            header("Content-Length: " . strlen($file_data));

            // Output binary data safely
            echo $file_data;
        } else {
            die("No file found with the specified ID.");
        }
    } else {
        die("Error executing the query: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("File ID is missing.");
}

$con->close();
ob_end_flush();
?>
