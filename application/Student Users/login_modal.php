<!-- Login Modal FOR Student -->
<div id="StudentModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title"><strong>WELCOME STUDENTS!</strong></h4>
                <img src="../img/ken.png" style="height:100px;" />
            </div>
            <div class="modal-body">
                <form method="post" action="student_auth.php">
                    <div class="form-group">
                        <label for="student_username">Username</label>
                        <input type="text" class="form-control" id="student_username" name="student_username"
                            placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <label for="student_password">Password</label>
                        <input type="password" class="form-control" id="student_password" name="student_password"
                            placeholder="Enter Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn_student">Log in</button>
                    <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#regModal">Signup</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
                <div id="error" class="text-danger text-right mt-2">
                    <?php
                    if (isset($_GET['login_error'])) {
                        // Trigger SweetAlert2 on error
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: '" . htmlspecialchars($_GET['login_error']) . "',
                                confirmButtonText: 'Try Again'
                            }).then(() => {
                                $('#StudentModal').modal('show'); // Show the login modal again
                            });
                        </script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal for Student -->
<div id="regModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title"><strong>STUDENT REGISTRATION</strong></h4>
                <img src="../img/ken.png" style="height:100px;" />
            </div>
            <div class="modal-body">
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
                        <label for="student_password">Password</label>
                        <input type="password" class="form-control" id="reg_student_password" name="student_password"
                            placeholder="Password" required minlength="8">
                        <small id="reg_passwordHelp" ></small>
                    </div>
                    <div class="form-group">
                        <label for="student_confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="reg_student_confirm_password"
                            name="student_confirm_password" placeholder="Confirm Password" required minlength="8">
                        <small id="reg_confirmPasswordHelp" ></small>
                    </div>


                    <button type="submit" class="btn btn-success" name="register_student">Register</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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

</script><!-- Login Modal FOR Student -->
<div id="StudentModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title"><strong>WELCOME STUDENTS!</strong></h4>
                <img src="../img/ken.png" style="height:100px;" />
            </div>
            <div class="modal-body">
                <form method="post" action="student_auth.php">
                    <div class="form-group">
                        <label for="student_username">Username</label>
                        <input type="text" class="form-control" id="student_username" name="student_username"
                            placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <label for="student_password">Password</label>
                        <input type="password" class="form-control" id="student_password" name="student_password"
                            placeholder="Enter Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn_student">Log in</button>
                    <button type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#regModal">Signup</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
                <div id="error" class="text-danger text-right mt-2">
                    <?php
                    if (isset($_GET['login_error'])) {
                        // Trigger SweetAlert2 on error
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: '" . htmlspecialchars($_GET['login_error']) . "',
                                confirmButtonText: 'Try Again'
                            }).then(() => {
                                $('#StudentModal').modal('show'); // Show the login modal again
                            });
                        </script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal for Student -->
<div id="regModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title"><strong>STUDENT REGISTRATION</strong></h4>
                <img src="../img/ken.png" style="height:100px;" />
            </div>
            <div class="modal-body">
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
                        <label for="student_password">Password</label>
                        <input type="password" class="form-control" id="reg_student_password" name="student_password"
                            placeholder="Password" required minlength="8">
                        <small id="reg_passwordHelp" ></small>
                    </div>
                    <div class="form-group">
                        <label for="student_confirm_password">Confirm Password</label>
                        <input type="password" class="form-control" id="reg_student_confirm_password"
                            name="student_confirm_password" placeholder="Confirm Password" required minlength="8">
                        <small id="reg_confirmPasswordHelp" ></small>
                    </div>


                    <button type="submit" class="btn btn-success" name="register_student">Register</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
