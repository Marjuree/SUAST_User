    <?php
    // Start session only if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "../configuration/config.php"; // Ensure no output or whitespace
    require_once "../application/SystemLog.php"; // Logging system

    // Login Handler for Employee
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['employee_password'];

        // Fetch user from tbl_employee_registration
        $query = "SELECT * FROM tbl_employee_registration WHERE username = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['employee_password'])) {
                session_regenerate_id(true); // Prevent session fixation

                $_SESSION['employee_id'] = $user['employee_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['middle_name'] = $user['middle_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = 'Employee';

                // ✅ Log successful login
                logMessage("INFO", "Login Success", "Employee '$username' logged in successfully.");

                // SweetAlert2 success message and redirect
                echo "
                <html>
                <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
                <body>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '../application/Employee Users/EmployeeDashboard.php?success=login';
                        });
                    </script>
                </body>
                </html>";
                exit();
            } else {
                // ❌ Log failed login attempt
                logMessage("WARNING", "Login Failed", "Employee Invalid Password! '$username'.");

                echo "
                <html>
                <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
                <body>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Password!',
                            text: 'Please try again.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'landing_page.php';
                        });
                    </script>
                </body>
                </html>";
                exit();
            }
        } else {
            // ❌ Log failed login attempt
            logMessage("WARNING", "Login Failed", "No account found with this username! '$username'.");

            echo "
            <html>
            <head><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'User Not Found!',
                        text: 'No account exists with that username.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'landing_page.php';
                    });
                </script>
            </body>
            </html>";
            exit();
        }

        $stmt->close();
        $con->close();
    }
    ?>
