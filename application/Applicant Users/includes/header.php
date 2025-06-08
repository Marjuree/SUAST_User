<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../configuration/config.php"; // Adjust path if needed

// Set default values for session variables
$role = $_SESSION['role'] ?? 'Applicant';
$applicant_id = $_SESSION['applicant_id'] ?? 0;

$reservation_approved = 0;
$reservation_rejected = 0;

if ($applicant_id > 0) {
    // Reservation Approved
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_reservation WHERE status = 'Approved' AND applicant_id = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $stmt->bind_result($reservation_approved);
    $stmt->fetch();
    $stmt->close();

    // Reservation Rejected
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_reservation WHERE status = 'Rejected' AND applicant_id = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $stmt->bind_result($reservation_rejected);
    $stmt->fetch();
    $stmt->close();
}

// Total notifications for modal badge (approved + rejected)
$total_requests = $reservation_approved + $reservation_rejected;
?>


<header class="header bg-dark text-white">
    <a href="#" class="logo">
        <img src="../../img/uni.png" alt="Logo" style="width: 70px; height: auto;">
    </a>

    <nav class="navbar navbar-static-top">
        <!-- <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a> -->
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!-- Message Modal Trigger -->
                <li style="position: relative;">
                    <a href="#" class="text-white" data-toggle="modal" data-target="#messagesModal"
                        style="position: relative; display: inline-block;">
                        <i class="fa fa-bell" style="font-size: 20px;"></i>
                        <span class="badge" style="
                            position: absolute;
                            top: 8px;
                            right: 8px;
                            background-color: red;
                            color: white;
                            padding: 3px 7px;
                            border-radius: 50%;
                            font-size: 8px;
                            font-weight: bold;
                            min-width: 10px;
                            text-align: center;
                            line-height: 1;
                            ">
                            <?php echo $total_requests; ?>
                        </span>
                    </a>
                </li>


                <!-- User Profile Modal Trigger -->
                <li>
                    <a href="#" class="text-white" data-toggle="modal" data-target="#viewProfile">
                        <i class="glyphicon glyphicon-user"></i>
                        <!-- <span><?php echo htmlspecialchars($role); ?></span> -->
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>


<!-- Messages Modal -->
<div class="modal fade" id="messagesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Red header with bullhorn icon -->
            <div class="modal-header text-white" style="background-color: #c62828;">
                <i class="fa fa-bullhorn mr-2" style="font-size: 22px; color: #fff;"></i>
                <button type="button" class="close text-white ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4" style="background-color: #fff; font-size: 16px;">
                <div class="mb-4">
                    <h6 class="font-weight-bold">Reservation Requests</h6>
                    <?php if ($reservation_approved > 0): ?>
                        <p class="text-success mb-2">🎉 Congratulations, your reservation request(s) is/are
                            <strong>Approved</strong>!
                        </p>
                    <?php endif; ?>
                    <?php if ($reservation_rejected > 0): ?>
                        <p class="text-danger mb-2">😞 Sorry, your reservation request(s) is/are <strong>Rejected</strong>.
                        </p>
                    <?php endif; ?>
                    <?php if ($reservation_approved === 0 && $reservation_rejected === 0): ?>
                        <p class="text-muted mb-2">No reservation requests found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger px-4" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
    #viewProfile {
        font-family: 'Poppins', sans-serif !important;

    }

    #editProfileModal {
        font-family: 'Poppins', sans-serif;
    }
</style>


<!-- View Profile Modal -->
<div id="viewProfile" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm"> <!-- smaller modal -->
        <div class="modal-content">
            <div class="modal-header" style="background-color:#003366; color:white;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
                <h4 class="modal-title">Profile</h4>
            </div>
            <div class="modal-body text-center" style="padding: 30px;">
                <?php
                if ($applicant_id > 0) {
                    $stmt = $con->prepare("SELECT first_name, middle_name, last_name, university_email FROM tbl_applicant_registration WHERE applicant_id = ?");
                    $stmt->bind_param("i", $applicant_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $fullName = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);
                        $email = htmlspecialchars($row['university_email']);
                        ?>
                        <!-- Circle Profile Icon -->
                        <div style="
                                width: 80px; 
                                height: 80px; 
                                background-color: #003366; 
                                color: white; 
                                border-radius: 50%; 
                                display: flex; 
                                justify-content: center; 
                                align-items: center; 
                                font-size: 40px; 
                                margin: 0 auto 20px auto;">
                            <i class="glyphicon glyphicon-user" aria-hidden="true"></i>
                        </div>
                        <h3 style="margin-bottom: 10px; font-family: 'Poppins', sans-serif;"><?php echo htmlspecialchars($fullName); ?></h3>
                        <p style="margin-bottom: 25px; color: #555;"><?php echo $email; ?></p>
                        <div style="max-width: 200px; margin-left: 30px; font-size: 16px; font-weight: 500;">
                            <!-- Edit Profile Link -->
                            <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#editProfileModal"
                                style="color: #003366; text-decoration: none; cursor: pointer; display: flex; align-items: center; margin-bottom: 15px;">
                                <i class="glyphicon glyphicon-pencil" aria-hidden="true"
                                    style="color: green; margin-right: 5px;"></i>
                                <span style="color: black;">Edit Profile</span>
                                <span style="font-size: 24px; color: black; margin-left: auto;">&gt;</span>
                            </a>

                            <!-- Logout Link -->
                            <a href="../../logout.php"
                                style="color: #003366; text-decoration: none; cursor: pointer; display: flex; align-items: center;">
                                <i class="fa fa-sign-out-alt" aria-hidden="true" style="margin-right: 5px;"></i>
                                <span style="color: black;">Logout</span>
                            </a>
                        </div>

                        <?php
                    }
                    $stmt->close();
                } else {
                    echo '<p class="text-danger">User data not found.</p>';
                }
                ?>
            </div>

        </div>
    </div>
</div>


<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <form method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#003366; color:white;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white;">&times;</span>
                    </button>
                    <h4 class="modal-title">Edit Profile</h4>
                </div>
                <div class="modal-body" style="overflow-y: auto; max-height: calc(100vh - 210px);">
                    <!-- Style for rounded input borders -->
                    <style>
                        .form-control input {
                            border-radius: 10px !important;
                        }
                    </style>
                    <?php
                    if ($applicant_id > 0) {
                        $stmt = $con->prepare("SELECT first_name, middle_name, last_name, university_email, username, applicant_password FROM tbl_applicant_registration WHERE applicant_id = ?");
                        $stmt->bind_param("i", $applicant_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) { ?>
                            <div class="form-group">
                                <label for="txt_first_name">First Name:</label>
                                <input id="txt_first_name" name="txt_first_name" class="form-control" type="text"
                                    value="<?php echo htmlspecialchars($row['first_name']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label for="txt_middle_name">Middle Name:</label>
                                <input id="txt_middle_name" name="txt_middle_name" class="form-control" type="text"
                                    value="<?php echo htmlspecialchars($row['middle_name']); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="txt_last_name">Last Name:</label>
                                <input id="txt_last_name" name="txt_last_name" class="form-control" type="text"
                                    value="<?php echo htmlspecialchars($row['last_name']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label for="txt_university_email">University Email:</label>
                                <input id="txt_university_email" name="txt_university_email" class="form-control" type="email"
                                    value="<?php echo htmlspecialchars($row['university_email']); ?>" required readonly />
                            </div>
                            <div class="form-group">
                                <label for="txt_username">Username:</label>
                                <input id="txt_username" name="txt_username" class="form-control" type="text"
                                    value="<?php echo htmlspecialchars($row['username']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label for="txt_password">New Password:</label>
                                <input id="txt_password" name="txt_password" class="form-control" type="password"
                                    placeholder="Enter new password (leave blank if unchanged)" />
                            </div>
                            <div class="form-group">
                                <label for="txt_confirm_password">Confirm Password:</label>
                                <input id="txt_confirm_password" name="txt_confirm_password" class="form-control"
                                    type="password" placeholder="Re-enter new password" />
                            </div>
                        <?php }
                        $stmt->close();
                    } else {
                        echo '<p class="text-danger">User data not found.</p>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        style="background-color:red; color: white;">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="btn_saveeditProfile"
                        style="background-color:#003366;">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>



<?php
// Update user profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_saveeditProfile']) && $applicant_id > 0) {
    $first_name = trim($_POST['txt_first_name']);
    $middle_name = trim($_POST['txt_middle_name']);
    $last_name = trim($_POST['txt_last_name']);
    $username = trim($_POST['txt_username']);
    $password = trim($_POST['txt_password']);

    if (!empty($first_name) && !empty($last_name) && !empty($username)) {
        if (!empty($password)) {
            // Hash password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $con->prepare("UPDATE tbl_applicant_registration SET first_name = ?, middle_name = ?, last_name = ?, username = ?, applicant_password = ? WHERE applicant_id = ?");
            $stmt->bind_param("sssssi", $first_name, $middle_name, $last_name, $username, $hashed_password, $applicant_id);
        } else {
            // Update without changing password
            $stmt = $con->prepare("UPDATE tbl_applicant_registration SET first_name = ?, middle_name = ?, last_name = ?, username = ? WHERE applicant_id = ?");
            $stmt->bind_param("ssssi", $first_name, $middle_name, $last_name, $username, $applicant_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Account updated successfully!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating account!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Required fields cannot be empty!');</script>";
    }
}
?>

<!-- JavaScript for Bootstrap Modals -->
<script>
    $(document).ready(function () {
        $('#editProfileModal, #messagesModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0]?.reset();
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.sidebar-toggle');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                sidebar.classList.toggle('sidebar-collapsed');
            });
        }
    });
</script>