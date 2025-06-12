<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css" />
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

<style>
    .modal,
    .modal * {
        font-family: 'Poppins', sans-serif !important;
    }
</style><!-- Login Modal FOR Student -->
<div class="modal fade" id="StudentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true"
    style="margin-top: 70px;">
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
                    <div class="form-group position-relative">
                        <label for="student_password">Password</label>
                        <input type="password" class="form-control" id="student_password" name="student_password"
                            required>
                        <span id="toggleStudentPassword"
                            style="position: absolute; right: 10px; top: 38px; cursor: pointer; user-select: none; margin-top: -8px;">
                            <!-- Eye icon SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                                viewBox="0 0 24 24" width="22" height="22">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                                <circle cx="12" cy="12" r="3.5" />
                            </svg>
                        </span>
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
                    <p class="mt-2 text-center">
                        <a href="#" data-toggle="modal" data-target="#forgotPasswordModal2" class="text-primary">
                            Forgot Password?
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

<script>
    const toggleStudentPassword = document.querySelector('#toggleStudentPassword');
    const studentPasswordInput = document.querySelector('#student_password');

    toggleStudentPassword.addEventListener('click', () => {
        const type = studentPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        studentPasswordInput.setAttribute('type', type);

        togglePassword.innerHTML = type === 'password'
            ? `   <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3.5"/>
        </svg>`
            : `   <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
            <path d="M17.94 17.94C16.12 19.25 14.13 20 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.06M22.54 6.42A21.77 21.77 0 0 1 23 12s-4 8-11 8a10.94 10.94 0 0 1-4.24-.88M1 1l22 22"/>
            <circle cx="12" cy="12" r="3.5"/>
        </svg>`;
    });
</script>

<!-- Reset password OTP Modal -->
<div class="modal fade" id="forgotPasswordModal2" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
    style="margin-top: 70px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-center"
                style="outline: none !important; box-shadow: none !important; border:none;">
                <img src="../img/uni.png" alt="Logo"
                    style="width: 200px; height: auto; margin-bottom: 10px; margin-top:-40px;">
                <h4 class="modal-title" id="loginModalLabel" style="font-weight: 700; margin-top: -50px;">
                    Welcome, Student!
                </h4>
                <h5 class="modal-title" style="font-size: 10px;">
                    Please input your existing email account!
                </h5>

                <button type="button" class="close position-absolute" style="right: 10px; top: 10px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="send_otp_student.php" method="POST">
                    <div class="form-group">
                        <label>Enter your email address</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <button type="submit" name="send_otp" class="btn btn-primary btn-block"
                        style="background-color: #02457A; color: white;">Send OTP</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const notyf = new Notyf();

    document.getElementById('forgotPasswordForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch('../../php/send_otp_student.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                notyf.success(data.message);
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000); // delay before redirecting
                }
            } else {
                notyf.error(data.message);
            }
        } catch (error) {
            notyf.error('An unexpected error occurred.');
            console.error('Fetch error:', error);
        }
    });
</script>


<!-- Agreement Modal -->
<div id="agreementModalStudent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel"
    aria-hidden="true" style="margin-top: 70px;">
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
                <p style="color: black !important;">Please confirm your agreement before proceeding with the registration.</p>
            </div>

            <div class="modal-footer d-flex flex-column"
                style="outline: none !important; box-shadow: none !important; border:none; margin-top: -10px;">
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

<style>
    #regModal .modal-body {
        min-height: 350px;
        padding-bottom: 80px;
        overflow-y: auto;
    }
</style>
<!-- Registration Modal for Student -->
<div class="modal fade" id="regModal" tabindex="-1" aria-labelledby="regModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false" style="margin-top: 70px;">
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
                    <!-- Password Field -->
                    <div class="form-group position-relative">
                        <label for="reg_student_password">Password</label>
                        <input type="password" class="form-control" id="reg_student_password" name="student_password"
                            placeholder="Password" required minlength="8">
                        <span id="toggleRegPassword" style="position:absolute; right:15px; top:30px; cursor:pointer; user-select:none;">
                            <!-- Eye icon SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                                viewBox="0 0 24 24" width="22" height="22">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                <circle cx="12" cy="12" r="3.5"/>
                            </svg>
                        </span>
                        <small id="reg_passwordHelp" class="text-muted" style="color: red !important;"></small>
                    </div>
                    <!-- Confirm Password Field -->
                    <div class="form-group position-relative">
                        <label for="reg_student_confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="reg_student_confirm_password"
                            name="student_confirm_password" placeholder="Confirm Password" required minlength="8">
                        <span id="toggleRegConfirmPassword" style="position:absolute; right:15px; top:30px; cursor:pointer; user-select:none;">
                            <!-- Eye icon SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                                viewBox="0 0 24 24" width="22" height="22">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                <circle cx="12" cy="12" r="3.5"/>
                            </svg>
                        </span>
                        <small id="reg_confirmPasswordHelp" class="text-muted"></small>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="privacy_notice"
                            name="privacy_notice_accepted" value="1" required>
                        <label class="form-check-label" for="privacy_notice">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I accept the
                            privacy
                            notice</label>
                    </div>

                    <button type="submit" class="btn btn-success btn-block" name="register_student"
                        style="background-color: #002B5B !important;">Register</button>
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
        const registerBtn = document.querySelector('button[name="register_student"]');

        // At least 8 chars, one uppercase, one lowercase, one digit, one special char
        const strongPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]).{8,}$/;

        function validatePassword() {
            const password = passwordInput.value;
            if (password.length === 0) {
                passwordHelp.textContent = '';
                return false;
            } else if (!strongPattern.test(password)) {
                passwordHelp.textContent = 'Password must be at least 8 characters and include uppercase, lowercase, number, and special symbol.';
                passwordHelp.style.color = 'red';
                return false;
            } else {
                passwordHelp.textContent = 'Strong password!';
                passwordHelp.style.color = 'green';
                return true;
            }
        }

        function checkPasswordMatch() {
            const passwordValid = validatePassword();
            if (confirmInput.value.length === 0) {
                confirmPasswordHelp.textContent = '';
                setRegisterBtnState(false);
                return false;
            }
            if (passwordInput.value === confirmInput.value && passwordValid) {
                confirmPasswordHelp.textContent = 'Passwords match.';
                confirmPasswordHelp.style.color = 'green';
                setRegisterBtnState(true);
                return true;
            } else {
                confirmPasswordHelp.textContent = 'Passwords do not match.';
                confirmPasswordHelp.style.color = 'red';
                setRegisterBtnState(false);
                return false;
            }
        }

        function setRegisterBtnState(enabled) {
            registerBtn.disabled = !enabled;
        }

        passwordInput.addEventListener('input', function () {
            validatePassword();
            checkPasswordMatch();
        });

        confirmInput.addEventListener('input', checkPasswordMatch);

        // Initial state
        setRegisterBtnState(false);

        // Eye icon toggle for password
        const regPasswordInput = document.getElementById('reg_student_password');
        const regConfirmInput = document.getElementById('reg_student_confirm_password');
        const toggleRegPassword = document.getElementById('toggleRegPassword');
        const toggleRegConfirmPassword = document.getElementById('toggleRegConfirmPassword');

        toggleRegPassword.addEventListener('click', function () {
            const type = regPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            regPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password'
                ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                viewBox="0 0 24 24" width="22" height="22">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3.5"/>
            </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                viewBox="0 0 24 24" width="22" height="22">
                <path d="M17.94 17.94C16.12 19.25 14.13 20 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.06M22.54 6.42A21.77 21.77 0 0 1 23 12s-4 8-11 8a10.94 10.94 0 0 1-4.24-.88M1 1l22 22"/>
                <circle cx="12" cy="12" r="3.5"/>
            </svg>`;
        });

        toggleRegConfirmPassword.addEventListener('click', function () {
            const type = regConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            regConfirmInput.setAttribute('type', type);
            this.innerHTML = type === 'password'
                ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                viewBox="0 0 24 24" width="22" height="22">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3.5"/>
            </svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5"
                viewBox="0 0 24 24" width="22" height="22">
                <path d="M17.94 17.94C16.12 19.25 14.13 20 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.06M22.54 6.42A21.77 21.77 0 0 1 23 12s-4 8-11 8a10.94 10.94 0 0 1-4.24-.88M1 1l22 22"/>
                <circle cx="12" cy="12" r="3.5"/>
            </svg>`;
        });
    });

</script>
