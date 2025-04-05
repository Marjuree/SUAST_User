<?php

require_once "../configuration/config.php"; // Include database connection

if (!function_exists('logMessage')) {
    function logMessage($type, $title, $message)
    {
        global $con; // Use the global database connection

        $filename = __DIR__ . "../../System.log"; // Ensure correct path

        // Create log file if it does not exist
        if (!file_exists($filename)) {
            $file = fopen($filename, "w");
            if ($file) {
                fclose($file);
            }
        }

        // Append log to file
        if ($file = fopen($filename, "a")) {
            $date = date("Y-m-d H:i:s");
            fwrite($file, "[$date] - $type - $title - $message\n");
            fclose($file);
        }

        // Insert log into database
        $sql = "INSERT INTO tbl_logs (log_type, title, message) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $type, $title, $message);
            $stmt->execute();
            $stmt->close();
        } else {
            error_log("Database log failed: " . $con->error);
        }
    }
}

?>



























<!--?php

require_once "../configuration/config.php"; // Use require_once to prevent multiple inclusions

// Check if the function is already defined
if (!function_exists('logMessage')) {
    function logMessage($type, $title, $message)
    {
        $filename = __DIR__ . "../../System.log"; // Use absolute path to avoid inconsistencies

        // Create log file if it does not exist
        if (!file_exists($filename)) {
            $file = fopen($filename, "w");
            if ($file) {
                fclose($file);
            }
        }

        // Open log file in append mode
        if ($file = fopen($filename, "a")) {
            $date = date("Y-m-d H:i:s");
            fwrite($file, "[$date] - $type - $title - $message\n");
            fclose($file);
        }
    }
}
    
?-->
