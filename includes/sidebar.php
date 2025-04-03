<?php
// Start the session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sanitize role
$allowed_roles = ["SUAST", "Accounting", "HRMO", "Student", "Employee", "Applicant"];
$role = in_array($_SESSION['role'], $allowed_roles) ? $_SESSION['role'] : 'Applicant';

// Escape output to prevent XSS
function secure_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

?>

 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left info" style="margin-top:70px;">
                <h4>Hello <?php echo htmlspecialchars($role); ?></h4>
            </div>
        </div>



        <ul class="sidebar-menu">
            <?php
            // Define role-based menu items
            $menu_items = [
                "SUAST" => [
                    ["link" => "../AdminSUAST/AdminSUAST.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../applicant/applicant.php", "icon" => "fa-users", "label" => "Applicants Detail's"],
                    ["link" => "../AdminSUAST/exam_schedule.php", "icon" => "fa-calendar", "label" => "Manage Classroom"],
                    ["link" => "../AdminSUAST/manage_reservations.php", "icon" => "fa-book", "label" => "Manage Reservation"],
                    ["link" => "../AdminSUAST/contact.php", "icon" => "fa-envelope", "label" => "Manage Contact"],
                    ["link" => "../AdminSUAST/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                    ["link" => "../logs/logs.php", "icon" => "fa-history", "label" => "Login History"],
                ],
                "Accounting" => [
                    ["link" => "../AdminAccounting/AccountingDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminAccounting/permit.php", "icon" => "fa-file", "label" => "Permit Request"],
                    ["link" => "../AdminAccounting/clearance.php", "icon" => "fa-clipboard-check", "label" => "Clearance Request"],
                    ["link" => "../AdminAccounting/student_balances.php", "icon" => "fa-book", "label" => "Student Record"],
                    ["link" => "../AdminAccounting/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                ],
                "HRMO" => [
                    ["link" => "../AdminHRMO/HRMODashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminHRMO/service_request.php", "icon" => "fa-file-alt", "label" => "Issuance of Service Record"],
                    ["link" => "../AdminHRMO/certification_request.php", "icon" => "fa-certificate", "label" => "Issuance of Certification"],
                    ["link" => "../AdminHRMO/leave_request.php", "icon" => "fa-file-signature", "label" => "Application for Leave"],
                    ["link" => "../AdminHRMO/chat.php", "icon" => "fa-comments", "label" => "Personnel Inquiry"],
                ],
                "Student" => [
                    ["link" => "../Student Users/StudentDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "#", "icon" => "fa-file-alt", "label" => "Request Clearance", "class" => "toggle-table", "data" => "requestClearance"],
                    ["link" => "#", "icon" => "fa-file", "label" => "Request Permit", "class" => "toggle-table", "data" => "requestPermit"],
                ],
                "Employee" => [
                    ["link" => "../Employee Users/EmployeeDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "#", "icon" => "fa-cogs", "label" => "Service", "toggle" => "modal", "target" => "#servicehrmo"],
                    ["link" => "#", "icon" => "fa-envelope", "label" => "Leave Request", "class" => "toggle-table", "data" => "leaverequest"],
                    ["link" => "../Employee Users/chat.php", "icon" => "fa-comments", "label" => "Personnel Inquiry"],
                ],
                "Applicant" => [
                    ["link" => "../Applicant Users/dashboard.php", "icon" => "fas fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../Applicant Users/exam_schedule.php", "icon" => "fa-calendar-check", "label" => "Slot Reservation"],
                    ["link" => "../Applicant Users/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                    ["link" => "../Applicant Users/contact.php", "icon" => "fa-envelope", "label" => "Contact Us"],
                ]
            ];

            // Display menu items based on user role
            if (isset($menu_items[$role])) {
                foreach ($menu_items[$role] as $item) {
                    $link = secure_output($item['link']);
                    $icon = secure_output($item['icon']);
                    $label = secure_output($item['label']);
                    
                    if (isset($item['class'])) {
                        echo "<li><a href='$link' class='{$item['class']}' data-target='{$item['data']}'><i class='fa $icon'></i> <span>$label</span></a></li>";
                    } elseif (isset($item['toggle'])) {
                        echo "<li><a href='$link' data-toggle='{$item['toggle']}' data-target='{$item['target']}'><i class='fa $icon'></i> <span>$label</span></a></li>";
                    } else {
                        echo "<li><a href='$link'><i class='fa $icon'></i> <span>$label</span></a></li>";
                    }
                }
            }
            ?>

            <!-- Logout -->
            <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </section>
</aside>
</body>
</html>
