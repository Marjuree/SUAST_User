<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../configuration/config.php"; // Modify this path if needed

// Set default values for session variables
$role = $_SESSION['role'] ?? 'Employee';
$employee_id = $_SESSION['employee_id'] ?? 0;




// Fetch messages only for Accounting (make sure to include `role` in SELECT)
$sql = "SELECT admin_name, message, role, created_at 
        FROM tbl_announcement 
        WHERE role = 'Accounting' 
        ORDER BY created_at DESC";
$result = $con->query($sql);
$num = $result ? $result->num_rows : 0; // Handle potential query failure
// Initialize counts
$cert_approved = 0;
$cert_rejected = 0;
$leave_approved = 0;
$leave_rejected = 0;
$service_approved = 0;
$service_rejected = 0;

if ($employee_id > 0) {
    // Certification Approved
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_certification_requests WHERE request_status = 'Approved' AND employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($cert_approved);
    $stmt->fetch();
    $stmt->close();

    // Certification Rejected
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_certification_requests WHERE request_status = 'Rejected' AND employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($cert_rejected);
    $stmt->fetch();
    $stmt->close();

    // Leave Approved
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_leave_requests WHERE approval_status = 'Approved' AND employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leave_approved);
    $stmt->fetch();
    $stmt->close();

    // Leave Rejected
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_leave_requests WHERE approval_status = 'Rejected' AND employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($leave_rejected);
    $stmt->fetch();
    $stmt->close();

    // Service Approved
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_service_requests WHERE request_status = 'Approved' AND employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($service_approved);
    $stmt->fetch();
    $stmt->close();

    // Service Rejected
    $stmt = $con->prepare("SELECT COUNT(*) FROM tbl_service_requests WHERE request_status = 'Rejected' AND employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->bind_result($service_rejected);
    $stmt->fetch();
    $stmt->close();
}


// Total notifications
// Sum all approved + rejected counts for all requests
$total_requests =
    $cert_approved + $cert_rejected +
    $leave_approved + $leave_rejected +
    $service_approved + $service_rejected;
?>

<header class="header bg-dark text-white">
    <a href="#" class="logo">
        <img src="../../img/uni.png" alt="Logo" style="width: 70px; height: auto;">
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
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
                            <?php echo $total_requests > 0 ? $total_requests : ''; ?>
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
                if ($employee_id > 0) {
                    $stmt = $con->prepare("SELECT username, email, first_name, middle_name, last_name FROM tbl_employee_registration WHERE employee_id = ?");
                    $stmt->bind_param("i", $employee_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $fullName = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);
                        $email = htmlspecialchars($row['email']);
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
                        <h3 style="margin-bottom: 10px;"><?php echo htmlspecialchars($fullName); ?></h3>
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

<!-- Messages Modal -->
<div class="modal fade" id="messagesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Red header with bullhorn icon -->
            <div class="modal-header text-white" style="background-color: #c62828; color: #fff;">
                <div class="d-flex align-items-center">
                    <i class="fa fa-bullhorn mr-2" style="font-size: 22px;"></i>
                </div>

            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4" style="background-color: #fff; font-size: 16px;">

                <div class="mb-4">
                    <h6 class="font-weight-bold">Certification Requests</h6>
                    <?php if ($cert_approved > 0): ?>
                        <p class="text-success">🎉 Congratulations, your certification request(s) is/are
                            <strong>Approved</strong>!
                        </p>
                    <?php endif; ?>
                    <?php if ($cert_rejected > 0): ?>
                        <p class="text-danger">😞 Sorry, your certification request(s) is/are <strong>Rejected</strong>.</p>
                    <?php endif; ?>
                    <?php if ($cert_approved === 0 && $cert_rejected === 0): ?>
                        <p>No certification requests found.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <h6 class="font-weight-bold">Leave Requests</h6>
                    <?php if ($leave_approved > 0): ?>
                        <p class="text-success">🎉 Congratulations, your leave request(s) is/are <strong>Approved</strong>!
                        </p>
                    <?php endif; ?>
                    <?php if ($leave_rejected > 0): ?>
                        <p class="text-danger">😞 Sorry, your leave request(s) is/are <strong>Rejected</strong>.</p>
                    <?php endif; ?>
                    <?php if ($leave_approved === 0 && $leave_rejected === 0): ?>
                        <p>No leave requests found.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <h6 class="font-weight-bold">Service Requests</h6>
                    <?php if ($service_approved > 0): ?>
                        <p class="text-success">🎉 Congratulations, your service request(s) is/are
                            <strong>Approved</strong>!
                        </p>
                    <?php endif; ?>
                    <?php if ($service_rejected > 0): ?>
                        <p class="text-danger">😞 Sorry, your service request(s) is/are <strong>Rejected</strong>.</p>
                    <?php endif; ?>
                    <?php if ($service_approved === 0 && $service_rejected === 0): ?>
                        <p>No service requests found.</p>
                    <?php endif; ?>
                </div>


            </div>

            <!-- Modal footer with Close button -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal"
                    style="background-color:red; color: #fff;">CLOSE</button>
            </div>
        </div>
    </div>
</div>




<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal fade" tabindex="-1" role="dialog">
    <form method="post">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Manage Account</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php
                    // Fetch employee data
                    $stmt = $con->prepare("SELECT username, email, first_name, middle_name, last_name FROM tbl_employee_registration WHERE employee_id = ?");
                    $stmt->bind_param("i", $employee_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) { ?>
                        <div class="form-group">
                            <label>First Name:</label>
                            <input name="first_name" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['first_name']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Middle Name:</label>
                            <input name="middle_name" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['middle_name']); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Last Name:</label>
                            <input name="last_name" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['last_name']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <input name="username" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['username']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input name="email" class="form-control" type="email"
                                value="<?php echo htmlspecialchars($row['email']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>New Password (leave blank if unchanged):</label>
                            <input name="password" class="form-control" type="password" placeholder="Enter new password" />
                        </div>
                    <?php } else {
                        echo '<p class="text-danger">User data not found.</p>';
                    }
                    $stmt->close();
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="save_profile">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
// Update employee profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile']) && $employee_id > 0) {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $updated_at = date('Y-m-d H:i:s');

    if (!empty($first_name) && !empty($last_name) && !empty($username) && !empty($email)) {
        if (!empty($password)) {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $con->prepare("UPDATE tbl_employee_registration SET first_name = ?, middle_name = ?, last_name = ?, username = ?, email = ?, employee_password = ?, updated_at = ? WHERE employee_id = ?");
            $stmt->bind_param("sssssssi", $first_name, $middle_name, $last_name, $username, $email, $hashed_password, $updated_at, $employee_id);
        } else {
            // Update without changing password
            $stmt = $con->prepare("UPDATE tbl_employee_registration SET first_name = ?, middle_name = ?, last_name = ?, username = ?, email = ?, updated_at = ? WHERE employee_id = ?");
            $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $username, $email, $updated_at, $employee_id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='leave_requests.php';</script>";
        } else {
            echo "<script>alert('Error updating profile!');</script>";
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