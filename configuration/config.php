
<?php
$dbhost = "bwygtvcqnww3bwtcc8mw-mysql.services.clever-cloud.com";
$dbusername = "uwfbf1jptm3pg6p0";
$dbpassword = "mjLQ9V30EsAOUNyr3u1G";
$dbname = "bwygtvcqnww3bwtcc8mw";
$dbport = 3306; // Explicitly specify the port

try {
    $con = new mysqli($dbhost, $dbusername, $dbpassword, $dbname, $dbport);

    if ($con->connect_error) {
        error_log("Database connection failed: " . $con->connect_error);
        die("Database connection failed."); // Optional: Stop execution if DB connection fails
    }
} catch (\Throwable $th) {
    error_log("Exception in DB connection: " . $th->getMessage());
    header("location: ErrorPage.php?error=500");
    exit();
}
?>













