<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include "../../configuration/config.php"; // Adjust the path as needed

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../../login.php");
    exit();
}

// Retrieve session variables
$userid = $_SESSION['userid'] ?? 0;
$role = $_SESSION['role'] ?? 'Guest';
$username = $_SESSION['username'] ?? 'Guest';
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
                <li>
                    <a href="#" class="text-white" data-toggle="modal" data-target="#editProfileModal">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo htmlspecialchars($username) . " (" . htmlspecialchars($role) . ")"; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

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
                    if ($userid > 0) {
                        $stmt = $con->prepare("SELECT name, email, school_id, username FROM tbl_users_management WHERE id = ?");
                        $stmt->bind_param("i", $userid);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($row = $result->fetch_assoc()) { ?>
                            <div class="form-group">
                                <label>Name:</label>
                                <input name="txt_name" class="form-control" type="text" value="<?php echo htmlspecialchars($row['name']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input name="txt_email" class="form-control" type="email" value="<?php echo htmlspecialchars($row['email']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>School ID:</label>
                                <input name="txt_schoolid" class="form-control" type="text" value="<?php echo htmlspecialchars($row['school_id']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <input name="txt_username" class="form-control" type="text" value="<?php echo htmlspecialchars($row['username']); ?>" required />
                            </div>
                            <div class="form-group">
                                <label>New Password:</label>
                                <input name="txt_password" class="form-control" type="password" placeholder="Leave blank to keep current password" />
                            </div>
                        <?php } else {
                            echo '<p class="text-danger">User data not found.</p>';
                        }
                        $stmt->close();
                    }
                    ?>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger px-4 py-2" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2" name="btn_saveeditProfile">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>





<?php
// Update user profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_saveeditProfile']) && $userid > 0) {
    $name = trim($_POST['txt_name']);
    $email = trim($_POST['txt_email']);
    $school_id = trim($_POST['txt_schoolid']);
    $username = trim($_POST['txt_username']);
    $password = trim($_POST['txt_password']);

    if (!empty($name) && !empty($email) && !empty($school_id) && !empty($username)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare("UPDATE tbl_users_management SET name = ?, email = ?, school_id = ?, username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $name, $email, $school_id, $username, $hashed_password, $userid);
        } else {
            $stmt = $con->prepare("UPDATE tbl_users_management SET name = ?, email = ?, school_id = ?, username = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $email, $school_id, $username, $userid);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Account updated successfully!'); window.location.href='AdminSUAST.php?success=login';</script>";
        } else {
            echo "<script>alert('Error updating account!');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields except password are required!');</script>";
    }
}
?>

<!-- JavaScript for Bootstrap Modals -->
<script>
    $(document).ready(function() {
        $('#editProfileModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0]?.reset();
        });
    });
</script>
