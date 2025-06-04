<?php
// Start the session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sanitize role
$allowed_roles = ["SUAST", "Accounting", "HRMO", "Student", "Employee", "Applicant"];
$role = in_array($_SESSION['role'], $allowed_roles) ? $_SESSION['role'] : 'Applicant';

// Escape output to prevent XSS
function secure_output($data)
{
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
        <div class="user-panel" style="display: none;">
            <div class="user-info">
                <h4>Hello <?php echo htmlspecialchars($role); ?></h4>
            </div>
        </div>

        <ul class="custom-sidebar-menu">
            <?php
            $menu_items = [
                "SUAST" => [
                    ["link" => "../AdminSUAST/AdminSUAST.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminSUAST/exam_schedule.php", "icon" => "fa-calendar", "label" => "Manage Classroom"],
                    ["link" => "../AdminSUAST/manage_reservations.php", "icon" => "fa-book", "label" => "Manage Reservation"],
                    ["link" => "../AdminSUAST/contact.php", "icon" => "fa-envelope", "label" => "Manage Contact"],
                    ["link" => "../AdminSUAST/announcement.php", "icon" => "fa-bell", "label" => "Announcement"],
                    ["link" => "../logs/logs.php", "icon" => "fa-history", "label" => "Login History"],
                ],
                "Accounting" => [
                    ["link" => "../AdminAccounting/AccountingDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../AdminAccounting/permit.php", "icon" => "fa-file", "label" => "Permit Request"],
                    ["link" => "../AdminAccounting/clearance.php", "icon" => "fa-clipboard-check", "label" => "Clearance Request"],
                    ["link" => "../AdminAccounting/student_balances.php", "icon" => "fa-book", "label" => "Student Record"],
                    ["link" => "../AdminAccounting/announcement.php", "icon" => "fa-bell", "label" => "Announcement"],
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
                    ["link" => "../Student Users/announcement.php", "icon" => "fa-bell", "label" => "Announcements"]
                ],
                "Employee" => [
                    ["link" => "../Employee Users/EmployeeDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../Employee Users/contact.php", "icon" => "fa-envelope", "label" => "Manage Contact"],

                    // ["link" => "../Employee Users/leave_requests.php", "icon" => "fa-envelope", "label" => "Leave Request"],
                    // ["link" => "../Employee Users/certification_requests.php", "icon" => "fa-certificate", "label" => "Certification Request"],
                    // ["link" => "../Employee Users/service_requests.php", "icon" => "fa-wrench", "label" => "Service Request"],
                ],
                "Applicant" => [
                    ["link" => "../Applicant Users/dashboard.php", "icon" => "fas fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../Applicant Users/index.php", "icon" => "fas fa-calendar-check", "label" => "Reservation"],
                    ["link" => "../Applicant Users/announcement.php", "icon" => "fa-bell", "label" => "Announcement"],
                    ["link" => "../Applicant Users/contact.php", "icon" => "fa-envelope", "label" => "Contact Us"]
                ]
            ];

            if (isset($menu_items[$role])) {
                foreach ($menu_items[$role] as $item) {
                    $link = secure_output($item['link']);
                    $icon = secure_output($item['icon']);
                    $label = secure_output($item['label']);
                    $is_active = (basename($link) === $current_page) ? "active" : "";

                    $extra_class = isset($item['class']) ? ' ' . secure_output($item['class']) : '';
                    $toggle_attr = isset($item['toggle']) ? ' data-toggle="' . secure_output($item['toggle']) . '"' : '';
                    $target_attr = isset($item['target']) ? ' data-target="' . secure_output($item['target']) . '"' : '';
                    $data_attr = isset($item['data']) ? ' data-target="' . secure_output($item['data']) . '"' : '';

                    echo "<li class='$is_active'>
                            <a href='$link' class='menu-link$extra_class'$toggle_attr$target_attr$data_attr>
                                <i class='fa $icon'></i> <span>$label</span>
                            </a>
                          </li>";
                }
            }
            ?>
            <!-- <li><a href="../../logout.php"><i class="fa fa-sign-out-alt"></i> <span>Logout</span></a></li> -->
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
        transform: translateX(-100%);
    }

    .custom-sidebar-menu {
        list-style: none;
        padding: 0;
        margin-top: 100px;
    }

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
        border-left: none;
        position: relative;
    }

    .custom-sidebar-menu li.active a i {
        position: relative;
        padding-bottom: 6px;
    }

    .custom-sidebar-menu li.active a i::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background-color: #FFD700;
        border-radius: 2px;
    }


    @media (max-width: 768px) {
        .custom-sidebar {
            width: 100%;
            height: 60px;
            bottom: 0;
            left: 0;
            top: auto;
            margin-bottom: -10px;
            transform: translateY(0);
            background-color: #002B5B;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
        }

        .custom-sidebar-menu {
            display: flex;
            margin-top: 0;
            padding: 0 10px;
            width: 100%;
            justify-content: space-around;
            gap: 25px;
        }

        .custom-sidebar-menu li {
            flex: 1 1 auto;
            text-align: center;
            margin: 0;
        }

        .custom-sidebar-menu li a {
            padding: 8px 0;
            border-left: none;
            font-size: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            background: none;
            /* no background */
            border-radius: 0;
            transition: none;
        }

        .custom-sidebar-menu li a:hover {
            color: #ffd700;
            /* optional: subtle color on hover */
        }

        .custom-sidebar-menu li a i {
            margin: 0;
            font-size: 22px;
            line-height: 1;
            color: #fff;
        }

        .custom-sidebar-menu li a span {
            display: none;
        }

        /* Remove active style */
        .custom-sidebar-menu li.active {
            background: none !important;
        }
    }
</style>

<!-- Sidebar Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.sidebar-toggle');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();

                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('active');
                } else {
                    sidebar.classList.toggle('sidebar-collapsed');
                }
            });
        }
    });

    // Click outside closes sidebar on mobile
    document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.sidebar-toggle');

        if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });

</script>
