<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
 
include "../../configuration/config.php"; // Modify this path if needed

// Set default values for session variables
$role = $_SESSION['role'] ?? 'Applicant';
$applicant_id = $_SESSION['applicant_id'] ?? 0;

// Fetch messages only for Accounting (make sure to include `role` in SELECT)
$sql = "SELECT admin_name, message, role, created_at 
        FROM tbl_announcement 
        WHERE role = 'SUAST' 
        ORDER BY created_at DESC";
$result = $con->query($sql);
$num = $result ? $result->num_rows : 0; // Handle potential query failure


 
?>

<header class="header bg-dark text-white">
    <a href="#" class="logo"></a>
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
                <li>
                    <a href="#" class="text-white" data-toggle="modal" data-target="#messagesModal">
                        <i class="fa fa-bullhorn"></i>
                        <span class="badge badge-primary"><?php echo $num; ?></span>
                    </a>
                </li>
                
                <!-- User Profile Modal Trigger -->
                <li>
                    <a href="#" class="text-white" data-toggle="modal" data-target="#editProfileModal">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo htmlspecialchars($role); ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Messages Modal -->
<div id="messagesModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">You have <?php echo $num; ?> new Announcement</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 60vh;">
                <?php if ($num > 0) { 
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <div class="message-item d-flex flex-column mb-2">
                            <small class="text-muted"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></small>
                            <div class="alert alert-light rounded-lg shadow-sm p-2 position-relative w-100" style="word-break: break-word;">
                                <p class="mb-1"><strong>From: <?php echo htmlspecialchars($row['role'] ?? 'Unknown'); ?></strong></p>
                                <p class="mb-0"><strong><?php echo htmlspecialchars($row['admin_name']); ?>:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                            </div>
                        </div>
                    <?php } 
                } else { ?>
                    <p class="text-muted text-center">No new Announcement.</p>
                <?php } ?>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="./announcement.php" class="btn btn-primary btn-sm">View all messages</a>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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
                    if ($applicant_id > 0) {
                        $stmt = $con->prepare("SELECT first_name, middle_name, last_name, university_email, username, applicant_password FROM tbl_applicant_registration WHERE applicant_id = ?");
                        $stmt->bind_param("i", $applicant_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) { ?>
                            <div class="form-group">
                                <label>First Name:</label>
                                <input name="txt_first_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['first_name']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Middle Name:</label>
                                <input name="txt_middle_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['middle_name']); ?>" />
                            </div>
                            <div class="form-group">
                                <label>Last Name:</label>
                                <input name="txt_last_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['last_name']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>University Email:</label>
                                <input name="txt_university_email" class="form-control" type="email" value="<?php echo htmlspecialchars($row['university_email']); ?>" required readonly />
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <input name="txt_username" class="form-control" type="text" value="<?php echo htmlspecialchars($row['username']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>New Password:</label>
                                <input name="txt_password" class="form-control" type="password" placeholder="Enter new password (leave blank if unchanged)" />
                            </div>
                        <?php }
                        $stmt->close();
                    } else {
                        echo '<p class="text-danger">User data not found.</p>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="btn_saveeditProfile">Save Changes</button>
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
    $(document).ready(function() {
        $('#editProfileModal, #messagesModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0]?.reset();
        });
    });
</script>
