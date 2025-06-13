<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../../configuration/config.php";

// Ensure session is valid
if (!isset($_SESSION['student_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Session expired. Please log in again.'
    ]);
    exit;
}

$student_id = $_SESSION['student_id']; // This is the internal numeric ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_requested = date("Y-m-d H:i:s");
    $status = 'Pending';

    // Get faculty and year_level from POST
    $faculty = isset($_POST['faculty']) ? trim($_POST['faculty']) : '';
    $year_level = isset($_POST['year_level']) ? trim($_POST['year_level']) : '';

    // Fetch the school_id using the internal student_id
    $stmt = $con->prepare("SELECT school_id FROM tbl_student_users WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student || empty($student['school_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Student record not found or missing school ID.'
        ]);
        exit;
    }

    $school_id = $student['school_id']; // Use this as the student_id in the clearance request table

    // Check if a clearance request already exists for this school_id
    $checkSql = "SELECT COUNT(*) as count FROM tbl_clearance_requests WHERE student_id = ?";
    $checkStmt = $con->prepare($checkSql);
    $checkStmt->bind_param("s", $school_id); // school_id is a string
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You have already submitted a clearance request.'
        ]);
        exit;
    }

    // Insert new request using the school_id, faculty, and year_level
    $insertSql = "INSERT INTO tbl_clearance_requests (student_id, status, date_requested, faculty, year_level) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $con->prepare($insertSql);
    $insertStmt->bind_param("sssss", $school_id, $status, $date_requested, $faculty, $year_level);

    if ($insertStmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Clearance request submitted successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error submitting request. Please try again.'
        ]);
    }

    $insertStmt->close();
    $con->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
