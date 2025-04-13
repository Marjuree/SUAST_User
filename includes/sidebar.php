<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$allowed_roles = ["SUAST", "Accounting", "HRMO", "Student", "Employee", "Applicant"];
$role = in_array($_SESSION['role'], $allowed_roles) ? $_SESSION['role'] : 'Applicant';

function secure_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<aside style="margin-top: 50px;" class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel" style="display: none;">
            <div class="pull-left info" style="margin-top:70px;">
                <h4>Hello <?php echo secure_output($role); ?></h4>
            </div>
        </div>
        <style>

        </style>
        <ul class="sidebar-menu">
            <?php
            $menu_items = [
                "SUAST" => [
                    ["link" => "../AdminSUAST/AdminSUAST.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
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
                ],
             "Employee" => [
                    ["link" => "../Employee Users/EmployeeDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "#", "icon" => "fa-cogs", "label" => "Service", "toggle" => "modal", "target" => "#servicehrmo"],
                    ["link" => "#", "icon" => "fa-envelope", "label" => "Leave Request", "class" => "toggle-table", "data" => "leaverequest"],
                    ["link" => "#", "icon" => "fa-certificate", "label" => "Certification Request", "class" => "toggle-table", "data" => "certificationRequests"],
                    ["link" => "#", "icon" => "fa-wrench", "label" => "Service Request", "class" => "toggle-table", "data" => "serviceRequests"],
                    ["link" => "../Employee Users/chat.php", "icon" => "fa-comments", "label" => "Personnel Inquiry"],
                ],

                "Applicant" => [
                    ["link" => "../Applicant Users/dashboard.php", "icon" => "fas fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../Applicant Users/exam_schedule.php", "icon" => "fa-calendar-check", "label" => "Request Slot"],
                    ["link" => "../Applicant Users/ViewList.php", "icon" => "fa-calendar-check", "label" => "View List"],
                    ["link" => "../Applicant Users/applicant.php", "icon" => "fa-users", "label" => "Applicants Detail's"],
                    ["link" => "../Applicant Users/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                    ["link" => "../Applicant Users/contact.php", "icon" => "fa-envelope", "label" => "Contact Us"]
                ]
            ];

            // Generate dynamic sidebar
            if (isset($menu_items[$role])) {
                foreach ($menu_items[$role] as $item) {
                    $link = secure_output($item['link']);
                    $icon = secure_output($item['icon']);
                    $label = secure_output($item['label']);
                    $is_active = (basename($link) === $current_page) ? "active" : "";

                    // Optional attributes
                    $extra_class = isset($item['class']) ? ' ' . secure_output($item['class']) : '';
                    $toggle_attr = isset($item['toggle']) ? ' data-toggle="' . secure_output($item['toggle']) . '"' : '';
                    $target_attr = isset($item['target']) ? ' data-target="' . secure_output($item['target']) . '"' : '';
                    $data_attr = isset($item['data']) ? ' data-target="' . secure_output($item['data']) . '"' : ''; // for toggle-table

                    echo "<li class='$is_active'>
                            <a href='$link' class='menu-link$extra_class'$toggle_attr$target_attr$data_attr>
                                <i class='fa $icon'></i> <span>$label</span>
                            </a>
                          </li>";
                }
            }
            ?>
            <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </section>
</aside>

<style>
.sidebar-menu li.active {
    background-color: rgb(0, 0, 0) !important;
    color: #fff;
}

.sidebar-menu li.active a {
    color: #fff;
}
</style>