<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../configuration/config.php";

$role = $_SESSION['role'] ?? 'Student';
$id = $_SESSION['student_id'] ?? 0;

$status = null;

// Fetch current student's status
if ($id > 0) {
    $stmt = $con->prepare("SELECT status FROM tbl_student_users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("Prepare failed: " . $con->error);
    }
}

// Badge count: show 1 if status exists and is not 'Pending'
$total_statuses = ($status && strtolower($status) !== 'pending') ? 1 : 0;
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
                        <?php if ($total_statuses > 0): ?>
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
                                <?php echo $total_statuses; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>



                <!-- User Profile Modal Trigger -->
                <li>
                    <a href="#" class="text-white" data-toggle="modal" data-target="#viewProfileModal">
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
                    <h6 class="font-weight-bold">Student Status Update</h6>

                    <?php if ($status === 'Cleared'): ?>
                        <p class="text-success mb-2">✅ You have been <strong>Cleared</strong>! Please proceed to the next
                            steps.</p>
                    <?php elseif ($status === 'For Payment'): ?>
                        <p class="text-warning mb-2">💰 Your status is <strong>For Payment</strong>. Kindly settle your
                            payment to proceed.</p>
                    <?php elseif ($status !== null && strtolower($status) !== 'pending'): ?>
                        <p class="text-info mb-2">ℹ️ Your current status is
                            <strong><?php echo htmlspecialchars($status); ?></strong>.</p>
                    <?php else: ?>
                        <p class="text-muted mb-2">No status updates found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger px-4" data-dismiss="modal">CLOSE</button>
            </div>

        </div>
    </div>
</div>



<!-- View Profile Modal -->
<div id="viewProfileModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                if ($id > 0) {
                    $stmt = $con->prepare("SELECT full_name, email FROM tbl_student_users WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($row = $result->fetch_assoc()) {
                        $fullName = htmlspecialchars($row['full_name']);
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
                        <h3 style="margin-bottom: 10px; font-family: 'Poppins', sans-serif !important;"><?php echo $fullName; ?></h3>
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
                    } else {
                        echo '<p class="text-danger">User data not found.</p>';
                    }
                    $stmt->close();
                } else {
                    echo '<p class="text-danger">User not logged in.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        width: 100%;
        max-width: 500px;
    }
</style>



<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal fade" tabindex="-1" role="dialog">
    <form method="post">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white"
                    style="background-color: #003366; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h4 class="modal-title">Edit profile</h4>
                </div>
                <div class="modal-body">
                    <?php
                    // Fetch student data
                    $stmt = $con->prepare("SELECT full_name, email, school_id, username FROM tbl_student_users WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) { ?>
                        <div class="form-group">
                            <label>Full Name:</label>

                            <input name="full_name" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['full_name']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input name="email" class="form-control" type="email"
                                value="<?php echo htmlspecialchars($row['email']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>School ID:</label>
                            <input name="school_id" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['school_id']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <input name="username" class="form-control" type="text"
                                value="<?php echo htmlspecialchars($row['username']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>New Password (leave blank if unchanged):</label>
                            <input name="password" class="form-control" type="password" placeholder="Enter new password" />
                        </div>

                        <div class="form-group">
                            <label for="txt_confirm_password">Confirm Password:</label>
                            <input id="txt_confirm_password" name="txt_confirm_password" class="form-control"
                                type="password" placeholder="Re-enter new password" />
                        </div>
                    <?php } else {
                        echo '<p class="text-danger">User data not found.</p>';
                    }
                    $stmt->close();
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        style="background-color:red; color: white;">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="save_profile"
                        style="background-color:#003366;">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
// Update student profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile']) && $id > 0) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $school_id = trim($_POST['school_id']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($full_name) && !empty($email) && !empty($school_id) && !empty($username)) {
        if (!empty($password)) {
            // Hash new password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $con->prepare("UPDATE tbl_student_users SET full_name = ?, email = ?, school_id = ?, username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $full_name, $email, $school_id, $username, $hashed_password, $id);
        } else {
            // Update without changing password
            $stmt = $con->prepare("UPDATE tbl_student_users SET full_name = ?, email = ?, school_id = ?, username = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $full_name, $email, $school_id, $username, $id);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='dashboard.php';</script>";
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
