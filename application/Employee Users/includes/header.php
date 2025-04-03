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
                        <i class="fa fa-envelope"></i>
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
                <h5 class="modal-title">You have <?php echo $num; ?> new messages</h5>
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
                    <p class="text-muted text-center">No new messages.</p>
                <?php } ?>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="#" class="btn btn-primary btn-sm">View all messages</a>
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
                    // Fetch employee data
                    $stmt = $con->prepare("SELECT username, email, first_name, middle_name, last_name FROM tbl_employee_registration WHERE employee_id = ?");
                    $stmt->bind_param("i", $employee_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) { ?>
                        <div class="form-group">
                            <label>First Name:</label>
                            <input name="first_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['first_name']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Middle Name:</label>
                            <input name="middle_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['middle_name']); ?>" />
                        </div>
                        <div class="form-group">
                            <label>Last Name:</label>
                            <input name="last_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['last_name']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <input name="username" class="form-control" type="text" value="<?php echo htmlspecialchars($row['username']); ?>" required />
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input name="email" class="form-control" type="email" value="<?php echo htmlspecialchars($row['email']); ?>" required />
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
        $(document).ready(function() {
            $('#editProfileModal, #messagesModal').on('hidden.bs.modal', function () {
                $(this).find('form')[0]?.reset();
            });
        });
    </script>