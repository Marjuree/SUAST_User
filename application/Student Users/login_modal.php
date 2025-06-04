<!-- Login Modal FOR Student -->
<div class="modal fade" id="StudentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Header with logo and titles -->
            <div class="modal-header flex-column align-items-center"
                style="outline: none !important; box-shadow: none !important; border: none;">
                <!-- Logo -->
                <img src="../img/uni.png" alt="Logo"
                    style="width: 200px; height: auto; margin-bottom: 10px; margin-top: -40px;">
                <h4 class="modal-title" id="studentModalLabel" style="font-weight: 700; margin-top: -50px;">Welcome,
                    Students!</h4>
                <h4 class="modal-title" id="studentModalSubLabel" style="font-size: 10px;">Please login your account
                </h4>

                <!-- Close button -->
                <button type="button" class="close position-absolute" style="right: 10px; top: 10px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form action="student_auth.php" method="POST">
                    <div class="form-group">
                        <label for="student_username">Username</label>
                        <input type="text" class="form-control" id="student_username" name="student_username" required>
                    </div>
                    <div class="form-group">
                        <label for="student_password">Password</label>
                        <input type="password" class="form-control" id="student_password" name="student_password"
                            required>
                    </div>
                    <button type="submit" name="btn_student" class="btn btn-block"
                        style="background-color: #02457A; color: white;">
                        Login
                    </button>

                    <p class="mt-2 text-center">
                        <span style="color: black;">New User?</span>
                        <a href="#" data-toggle="modal" data-target="#agreementModalStudent" class="text-warning">
                            Register
                        </a>
                    </p>
                </form>

                <!-- Error message and SweetAlert2 -->
                <div id="error" class="text-danger text-right mt-2">
                    <?php
                    if (isset($_GET['login_error'])) {
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                        echo "<script>
              Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '" . htmlspecialchars($_GET['login_error']) . "',
                confirmButtonText: 'Try Again'
              }).then(() => {
                $('#StudentModal').modal('show');
              });
            </script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Agreement Modal -->
<div id="agreementModalStudent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header flex-column align-items-center"
                style="outline: none !important; box-shadow: none !important; border:none;">
                <!-- Logo on top -->
                <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px;">
                <!-- Title below logo -->
                <h5 class="modal-title" id="agreementModalLabel" style="margin-top:-50px; font-weight: 700;"> Agreement
                    Policy
                </h5>
                <!-- Close button remains top-right -->
                <button type="button" class="close position-absolute" style="right: 15px; top: 15px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body"
                style="outline: none !important; box-shadow: none !important; border:none; margin-top:-20px;">
                <ol>
                    <li>All provided data will be processed in compliance with the Data Privacy Act of 2012 (Republic
                        Act 10173).
                    </li>
                    <li>Your data will be used exclusively for this application.</li>
                    <li>You have the right to access, update, and delete your personal data at any time.</li>
                </ol>
                <p>Please confirm your agreement before proceeding with the registration.</p>
            </div>

            <div class="modal-footer d-flex flex-column"
                style="outline: none !important; box-shadow: none !important; border:none; margin-top: -40px;">
                <a href="#" class="btn btn-block" data-toggle="modal" data-target="#regModal" data-dismiss="modal"
                    style="background-color: #20457A; color: white;">
                    I Agree
                </a>
                <button type="button" class="btn btn-block" data-dismiss="modal"
                    style="background-color: red; color: white; margin-bottom: 10px;">
                    I Disagree
                </button>

            </div>

        </div>
    </div>
</div>
<!-- Registration Modal for Student -->
<div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="outline: none !important; box-shadow: none !important; border:none;">

            <div class="modal-header flex-column align-items-center">

                <img src="../img/uni.png" alt="Student Logo" style="height: 150px; margin-bottom: 10px;">

                <h4 class="modal-title font-weight-bold mb-1" id="regModalLabel" style="margin-top: -30px;">STUDENT
                    REGISTRATION</h4>

                <h6 class="modal-title text-center text-muted mb-3" style="font-size: 0.9rem;">Please create your
                    account</h6>

                <button type="button" class="close position-absolute" style="right: 15px; top: 15px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body px-3 px-sm-4"
                style="outline: none !important; box-shadow: none !important; border:none;">
                <form method="post" action="register_student.php">
                    <div class="form-group">
                        <label for="student_name">Full Name</label>
                        <input type="text" class="form-control" id="student_name" name="student_name"
                            placeholder="Enter Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="student_email">Email</label>
                        <input type="email" class="form-control" id="student_email" name="student_email"
                            placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <label for="student_school_id">School ID</label>
                        <input type="text" class="form-control" id="student_school_id" name="student_school_id"
                            placeholder="Enter School ID" required>
                    </div>
                    <div class="form-group">
                        <label for="student_username">Username</label>
                        <input type="text" class="form-control" id="student_username" name="student_username"
                            placeholder="Choose a Username" required>
                    </div>
                    <div class="form-group">
                        <label for="student_faculty">Faculty</label>
                        <input type="text" class="form-control" id="student_faculty" name="student_faculty"
                            placeholder="Enter Faculty" required>
                    </div>
                    <div class="form-group">
                        <label for="student_year_level">Year Level</label>
                        <select class="form-control" id="student_year_level" name="student_year_level" required>
                            <option value="" disabled selected>Select Year Level</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reg_student_password">Password</label>
                        <input type="password" class="form-control" id="reg_student_password" name="student_password"
                            placeholder="Password" required minlength="8">
                        <small id="reg_passwordHelp" class="text-muted"></small>
                    </div>
                    <div class="form-group">
                        <label for="reg_student_confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="reg_student_confirm_password"
                            name="student_confirm_password" placeholder="Confirm Password" required minlength="8">
                        <small id="reg_confirmPasswordHelp" class="text-muted"></small>
                    </div>

                    <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="privacy_notice" name="privacy_notice_accepted" value="1"
              required>
            <label class="form-check-label" for="privacy_notice">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I accept the privacy
              notice</label>
          </div>

                    <button type="submit" class="btn btn-success btn-block" name="register_student" style="background-color: #02457A;">Register</button>
                </form>
                <div id="error" class="text-danger text-right mt-2">
                    <?php if (isset($_GET['register_error']))
                        echo $_GET['register_error']; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('reg_student_password');
        const confirmInput = document.getElementById('reg_student_confirm_password');
        const passwordHelp = document.getElementById('reg_passwordHelp');
        const confirmPasswordHelp = document.getElementById('reg_confirmPasswordHelp');

        const strongPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/;

        passwordInput.addEventListener('input', function () {
            const password = passwordInput.value;

            if (password.length === 0) {
                passwordHelp.textContent = '';
            } else if (!strongPattern.test(password)) {
                passwordHelp.textContent = '';
                passwordHelp.style.color = 'red';
            } else {
                passwordHelp.textContent = 'Strong password!';
                passwordHelp.style.color = 'green';
            }
            checkPasswordMatch();
        });

        confirmInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            if (confirmInput.value.length === 0) {
                confirmPasswordHelp.textContent = '';
                return;
            }
            if (passwordInput.value === confirmInput.value) {
                confirmPasswordHelp.textContent = 'Passwords match.';
                confirmPasswordHelp.style.color = 'green';
            } else {
                confirmPasswordHelp.textContent = 'Passwords do not match.';
                confirmPasswordHelp.style.color = 'red';
            }
        }
    });

</script>