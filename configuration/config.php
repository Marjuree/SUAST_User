<?php
$dbhost = "bwygtvcqnww3bwtcc8mw-mysql.services.clever-cloud.com";
$dbusername = "uwfbf1jptm3pg6p0";
$dbpassword = "mjLQ9V30EsAOUNyr3u1G";
$dbname = "bwygtvcqnww3bwtcc8mw";
$dbport = 3306; // Explicitly specify the port

try {
    // Attempt to connect
    $con = new mysqli($dbhost, $dbusername, $dbpassword, $dbname, $dbport);

    // Check connection
    if ($con->connect_error) {
        error_log("Connection failed: " . $con->connect_error);
        die("Database connection failed: " . $con->connect_error);
    }
} catch (\Throwable $th) {
    // Log the exception details
    error_log("Exception caught during DB connection: " . $th->getMessage());
    die("Exception caught: " . $th->getMessage());
}
?>
