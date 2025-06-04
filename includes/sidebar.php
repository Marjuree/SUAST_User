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

// Get the current script name to determine the active menu
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Font Awesome (icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Sidebar (Styled) -->
<aside class="custom-sidebar" id="sidebar">
    <section class="sidebar-content">
        <div class="user-panel" style="display:none;">
            <div class="user-info">
                <h4>Hello <?php echo htmlspecialchars($role); ?></h4>
            </div>
        </div>

        <ul class="custom-sidebar-menu">
            <?php
            // Define role-based menu items
            $menu_items = [
                "SUAST" => [
                    ["link" => "../AdminSUAST/AdminSUAST.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminSUAST/exam_schedule.php", "icon" => "fa-calendar", "label" => "Manage Schedule"],
                    ["link" => "../AdminSUAST/manage_reservations.php", "icon" => "fa-book", "label" => "Manage Request"],
                    ["link" => "../AdminSUAST/viewList.php", "icon" => "fa-calendar-check", "label" => "View List"],
                    ["link" => "../AdminSUAST/contact.php", "icon" => "fa-envelope", "label" => "Manage Contact"],
                    ["link" => "../AdminSUAST/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                ],
                "Accounting" => [
                    ["link" => "../AdminAccounting/AccountingDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminAccounting/clearance.php", "icon" => "fa-clipboard-check", "label" => "Clearance Request"],
                    ["link" => "../AdminAccounting/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                ],
                "HRMO" => [
                    ["link" => "../AdminHRMO/HRMODashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminHRMO/service_request.php", "icon" => "fa-file-alt", "label" => "Issuance of Service Record"],
                    ["link" => "../AdminHRMO/certification_request.php", "icon" => "fa-certificate", "label" => "Issuance of Certification"],
                    ["link" => "../AdminHRMO/leave_request.php", "icon" => "fa-file-signature", "label" => "Application for Leave"],
                    // ["link" => "../AdminHRMO/chat.php", "icon" => "fa-comments", "label" => "Personnel Inquiry"],
                ],
                "Student" => [
                    ["link" => "../Student Users/StudentDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "#", "icon" => "fa-file-alt", "label" => "Request Clearance", "class" => "toggle-table", "data" => "requestClearance"],
                    ["link" => "#", "icon" => "fa-file", "label" => "Request Permit", "class" => "toggle-table", "data" => "requestPermit"],
                ],
                "Applicant" => [
                    ["link" => "../Applicant Users/dashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
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
                    $is_active = (basename($link) == $current_page) ? "active" : "";

                    echo "<li class='$is_active'>
                            <a href='$link'>
                                <i class='fa $icon'></i>
                                <span>$label</span>
                            </a>
                          </li>";
                }
            }
            ?>

            <!-- Logout -->
            <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </section>
</aside>


<!-- Sidebar Styles -->
<style>
.custom-sidebar {
    width: 220px;
    background-color: #002B5B;
    color: #fff;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    font-family: 'Segoe UI', sans-serif;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    transition: transform 0.3s ease;
}

.custom-sidebar.sidebar-collapsed {
    transform: translateX(-100%); /* Sidebar is hidden by default when collapsed */
}

.custom-sidebar-menu {
    list-style: none;
    padding: 0;
margin-top: 100px;}

.custom-sidebar-menu li {
    transition: background 0.3s;
}

.custom-sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #fff;
    text-decoration: none;
    font-size: 15px;
    border-left: 4px solid transparent;
}

.custom-sidebar-menu li a i {
    margin-right: 12px;
    font-size: 18px;
    width: 25px;
    text-align: center;
}

.custom-sidebar-menu li:hover,
.custom-sidebar-menu li.active {
    background-color: #0056b3;
}

.custom-sidebar-menu li.active a {
    font-weight: bold;
    border-left: 4px solid #FFD700;
}

@media (max-width: 768px) {
    .custom-sidebar {
        transform: translateX(-100%); 
    }

    .custom-sidebar.active {
        transform: translateX(0); 
    }

    /* Show sidebar when active */
    .custom-sidebar.sidebar-collapsed {
        transform: translateX(0); 
    }
}

</style>
