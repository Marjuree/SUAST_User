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
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

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

                "Student" => [
                    ["link" => "../Student Users/StudentDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../Student Users/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcements"]
                ],
                "Employee" => [
                    ["link" => "../Employee Users/EmployeeDashboard.php", "icon" => "fa-tachometer-alt", "label" => "Dashboard"],
                    ["link" => "../Employee Users/contact.php", "icon" => "fa-envelope", "label" => "Contact Us"],
                ],
                "Applicant" => [
                    ["link" => "../Applicant Users/dashboard.php", "icon" => "fas fa-tachometer-alt", "label" => "Dashboard"],
                    // ["link" => "../Applicant Users/index.php", "icon" => "fas fa-calendar-check", "label" => "Reservation"],
                    ["link" => "../Applicant Users/announcement.php", "icon" => "fa-bullhorn", "label" => "Announcement"],
                    ["link" => "../Applicant Users/contact.php", "icon" => "fa-envelope", "label" => "Contact Us"]
                ]
            ];

            if (isset($menu_items[$role])) {
                foreach ($menu_items[$role] as $item) {
                    $link = secure_output($item['link']);
                    $icon = secure_output($item['icon']);
                    $label = secure_output($item['label']);
                    $is_active = (basename($link) === $current_page) ? "active" : "";

                    echo "<li class='$is_active'>
                            <a href='$link' class='menu-link'>
                                <div class='menu-icon-label'>
                                    <i class='fa $icon'></i>
                                    <span class='icon-label'>$label</span>
                                </div>
                            </a>
                          </li>";
                }
            }
            ?>
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
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        transition: transform 0.3s ease;
        font-family: 'Poppins', sans-serif;

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
        margin-bottom: 22px;
    }

    .custom-sidebar-menu li a {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 20px;
        color: #fff;
        text-decoration: none;
        font-size: 15px;
        border-left: 4px solid transparent;
    }

    .menu-icon-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 12px;
        color: #fff;
    }

    .menu-icon-label i {
        font-size: 22px;
        margin-bottom: 4px;
    }

    .icon-label {
        display: block;
        font-size: 11px;
        text-align: center;
    }

    .custom-sidebar-menu li:hover,
    .custom-sidebar-menu li.active {
        background-color: #0056b3;
    }

    .custom-sidebar-menu li.active a {
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .custom-sidebar {
            width: 100%;
            height: 60px;
            bottom: 0;
            left: 0;
            top: auto;
            transform: translateY(0);
            background-color: #002B5B;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            font-family: 'Poppins', sans-serif;

        }

        .custom-sidebar-menu {
            display: flex;
            margin-top: 0;
            padding: 0 10px;
            width: 100%;
            justify-content: space-around;
        }

        .custom-sidebar-menu li {
            flex: 1 1 auto;
            text-align: center;
            margin: 0;
            padding: 10px;
            margin-bottom: -10px;
        }

        .custom-sidebar-menu li a {
            flex-direction: column;
            padding: 6px 0;
            font-size: 11px;
        }

        .menu-icon-label {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu-icon-label i {
            font-size: 20px;
        }

        .icon-label {
            display: block;
            font-size: 10px;
            /* Add this line */
        }

        .custom-sidebar-menu li.active {
            background: none !important;
        }

        .custom-sidebar-menu li.active .icon-label {
            color: yellow !important;
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
