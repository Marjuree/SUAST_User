<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
  .modal,
  .modal * {
    font-family: 'Poppins', sans-serif !important;
  }
</style>
<!-- Applicant Login Modal -->
<div class="modal fade" id="loginApplicant" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
  style="margin-top: 70px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header flex-column align-items-center"
        style="outline: none !important; box-shadow: none !important; border:none;">
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px; margin-top:-40px;">
        <h4 class="modal-title" id="loginModalLabel" style="font-weight: 700; margin-top: -50px;">Welcome, Applicant!
        </h4>
        <h4 class="modal-title" id="loginModalLabel" style="font-size: 10px;">please login your account</h4>

        <button type="button" class="close position-absolute" style="right: 10px; top: 10px;" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="applicant_auth.php" method="POST">
          <div class="form-group">
            <label for="usernameLogin">Username</label>
            <input type="text" class="form-control" id="usernameLogin" name="username" required>
          </div>
          <div class="form-group position-relative">
            <label for="applicant_password_login">Password</label>
            <input type="password" class="form-control" id="applicant_password_login" name="applicant_password"
              required>
            <span id="togglePassword"
              style="position: absolute; right: 10px; top: 38px; cursor: pointer; user-select: none; margin-top: -8px;">
              <!-- Eye icon SVG -->
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24"
                width="22" height="22">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
                <circle cx="12" cy="12" r="3.5" />
              </svg>
            </span>
          </div>
          <button type="submit" name="btn_applicant_login" class="btn btn-block"
            style="background-color: #02457A; color: white;">
            Login
          </button>

          <p class="mt-2 text-center">
            <span style="color: black;">New User?</span>
            <a href="#" data-toggle="modal" data-target="#agreementModal" class="text-warning ">
              Register
            </a>
          </p>
          <p class="mt-2 text-center">
            <a href="#" data-toggle="modal" data-target="#forgotPasswordModal" class="text-primary">
              Forgot Password?
            </a>
          </p>

        </form>
      </div>
    </div>
  </div>
</div>

<!-- Forgot Password Modal -->
<!-- <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Forgot Password</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="send_otp.php" method="POST">
          <div class="form-group">
            <label>Enter your email address</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <button type="submit" name="send_otp" class="btn btn-primary btn-block">Send OTP</button>
        </form>
      </div>
    </div>
  </div>
</div> -->


<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput = document.querySelector('#applicant_password_login');

  togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

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


<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true"
  style="margin-top: 70px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header flex-column align-items-center"
        style="outline: none !important; box-shadow: none !important; border:none;">
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px; margin-top:-40px;">
        <h4 class="modal-title" id="loginModalLabel" style="font-weight: 700; margin-top: -50px;">Welcome, Applicant!
        </h4>
        <h4 class="modal-title" id="loginModalLabel" style="font-size: 10px;">please input exisitng email accoung!</h4>

        <button type="button" class="close position-absolute" style="right: 10px; top: 10px;" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="send_otp.php" method="POST">
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
      const response = await fetch('../../php/send_otp.php', {
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
<div class="modal fade" id="agreementModal" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel"
  aria-hidden="true" style="margin-top: 70px;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header flex-column align-items-center"
        style="outline: none !important; box-shadow: none !important; border:none;">
        <!-- Logo on top -->
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px;">
        <!-- Title below logo -->
        <h5 class="modal-title" id="agreementModalLabel" style="margin-top:-50px; font-weight: 700;"> Agreement Policy
        </h5>
        <!-- Close button remains top-right -->
        <button type="button" class="close position-absolute" style="right: 15px; top: 15px;" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body"
        style="outline: none !important; box-shadow: none !important; border:none; margin-top:-20px;">
        <ol>
          <li>All provided data will be processed in compliance with the Data Privacy Act of 2012 (Republic Act 10173).
          </li>
          <li>Your data will be used exclusively for this application.</li>
          <li>You have the right to access, update, and delete your personal data at any time.</li>
        </ol>
        <p>Please confirm your agreement before proceeding with the registration.</p>
      </div>

      <div class="modal-footer d-flex flex-column"
        style="outline: none !important; box-shadow: none !important; border:none; margin-top: -40px;">
        <a href="#" class="btn btn-block" data-toggle="modal" data-target="#registerApplicant" data-dismiss="modal"
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
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<script>
  // Scroll input field into view on focus (for mobile keyboard)
  document.querySelectorAll('#registerApplicant input').forEach(input => {
    input.addEventListener('focus', function () {
      setTimeout(() => {
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 300); // wait for keyboard to appear
    });
  });
</script>

<!-- Applicant Registration Modal -->
<div class="modal fade" id="registerApplicant" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true"
  style="margin-top: 70px; ">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <!-- Centered and small modal -->
    <div class="modal-content" style="outline: none !important; box-shadow: none !important; border:none;">
      <div class="modal-header flex-column align-items-center">
        <!-- Logo above the title, smaller on small screens -->
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px;" class="img-fluid">

        <h4 class="modal-title text-center font-weight-bold mb-1" style="margin-top: -40px;">Applicant Registration</h4>
        <h6 class="modal-title text-center text-muted mb-3" style="font-size: 0.9rem;">Please create your account</h6>

        <button type="button" class="close position-absolute" style="right: 15px; top: 15px;" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body px-3 px-sm-4"
        style="outline: none !important; box-shadow: none !important; border:none; margin-bottom: 200px !important;">
        <form action="register_applicant.php" method="POST">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
          </div>
          <div class="form-group">
            <label for="middle_name">Middle Name (Optional)</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name">
          </div>
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
          </div>
          <div class="form-group">
            <label for="university_email">Email</label>
            <input type="email" class="form-control" id="university_email" name="university_email" required>
          </div>
          <div class="form-group">
            <label for="usernameRegister">Username</label>
            <input type="text" class="form-control" id="usernameRegister" name="username" required>
          </div>
          <div class="form-group">
            <label for="applicant_password_register">Password</label>
            <input type="password" class="form-control" id="applicant_password_register" name="applicant_password"
              required minlength="8">
            <small id="passwordHelp" class="text-muted"></small>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
              minlength="8">
          </div>
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="privacy_notice" name="privacy_notice_accepted" value="1"
              required>
            <label class="form-check-label" for="privacy_notice">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I accept the privacy
              notice</label>
          </div>
          <button type="submit" class="btn btn-primary btn-block"
            style="background-color: #02457A; margin-bottom: 40px;">Register</button>
        </form>
      </div>

    </div>
  </div>
</div>

<style>
  #registerApplicant .modal-body {
    min-height: 350px;
    padding-bottom: 60px;
    overflow-y: auto;
  }
</style>

<style>
  #passwordHelp.strong {
    color: green !important;
  }

  #passwordHelp.weak {
    color: red !important;
  }

  .modal-dialog {
    max-height: 90vh;
    /* max height relative to viewport */
    display: flex;
    flex-direction: column;
  }

  .modal-content {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .modal-body {
    overflow-y: auto;
    flex: 1 1 auto;
    padding: 1rem 1.5rem;
    /* you can adjust padding */
  }
</style>
<!-- Password Strength Check Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('applicant_password_register');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const helpText = document.getElementById('passwordHelp');
    const form = document.querySelector('form');

    if (!passwordInput || !confirmPasswordInput || !form) return;

    // Password strength validation
    passwordInput.addEventListener('input', function () {
      const password = passwordInput.value;
      const strongPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;

      if (password.length === 0) {
        helpText.textContent = '';
        helpText.classList.remove('strong', 'weak');
      } else if (!strongPattern.test(password)) {
        helpText.textContent = 'Password must include uppercase, lowercase, number, special character, and be at least 8 characters.';
        helpText.classList.add('weak');
        helpText.classList.remove('strong');
      } else {
        helpText.textContent = 'Strong password!';
        helpText.classList.add('strong');
        helpText.classList.remove('weak');
      }

      checkPasswordsMatch();
    });

    // Real-time password matching check
    confirmPasswordInput.addEventListener('input', checkPasswordsMatch);

    function checkPasswordsMatch() {
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;

      // Create or select the helper text for confirm password
      let confirmHelper = document.getElementById('confirmPasswordHelp');
      if (!confirmHelper) {
        confirmHelper = document.createElement('small');
        confirmHelper.id = 'confirmPasswordHelp';
        confirmHelper.classList.add('text-muted');
        confirmPasswordInput.parentNode.appendChild(confirmHelper);
      }

      if (confirmPassword.length === 0) {
        confirmHelper.textContent = '';
      } else if (password !== confirmPassword) {
        confirmHelper.textContent = 'Passwords do not match!';
        confirmHelper.style.color = 'red';
      } else {
        confirmHelper.textContent = 'Passwords match!';
        confirmHelper.style.color = 'green';
      }
    }

    // Confirm password matching on form submission
    form.addEventListener('submit', function (e) {
      const password = passwordInput.value;
      const confirmPassword = confirmPasswordInput.value;

      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
      }
    });
  });
</script>

<!-- Close modal function -->
<script>
  function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
  }
</script>
