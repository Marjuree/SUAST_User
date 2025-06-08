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
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye"
                viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
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


<script>
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput = document.querySelector('#applicant_password_login');

  togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    togglePassword.innerHTML = type === 'password'
      ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
          <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
          <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
        </svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
          <path d="M13.359 11.238l1.388 1.388a.5.5 0 0 1-.708.708l-1.388-1.388a8.06 8.06 0 0 1-4.651 1.323C3 13.269 0 8 0 8a13.134 13.134 0 0 1 3.112-3.93L1.72 2.68a.5.5 0 1 1 .708-.708l11 11a.5.5 0 0 1-.708.708l-1.36-1.36zM5.754 6.185a3 3 0 0 0 4.256 4.256L5.754 6.185z"/>
          <path d="M10.793 12.458a8.06 8.06 0 0 0 4.607-3.94s-3-5.5-8-5.5a7.49 7.49 0 0 0-3.093.612l.987.987a3 3 0 0 1 3.986 3.986l.113.113z"/>
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
        <form id="forgotPasswordForm" method="POST" novalidate>
          <div class="form-group">
            <label for="usernameOrEmail" class="font-weight-semibold">Enter Username or Email</label>
            <input type="text" class="form-control form-control-lg rounded-pill border-primary" id="usernameOrEmail"
              name="usernameOrEmail" placeholder="Username or Email" required autocomplete="username" autofocus />
          </div>
          <button type="submit" class="btn btn-primary btn-lg btn-block rounded-pill shadow-sm mt-4"
            style="background-color: #02457A; color: white;">
            Send OTP
          </button>
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
        style="outline: none !important; box-shadow: none !important; border:none; margin-bottom: 100px;">
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
          <button type="submit" class="btn btn-primary btn-block" style="background-color: #02457A;">Register</button>
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


<!-- Taker Side Modal -->
<div id="takerModal" class="modal">
  <div class="modal-content">
    <h2>Select Exam Schedule</h2>
    <form id="takerForm" method="POST" action="schedule_handler.php">
      <div class="form-group">
        <label for="examDate">Choose Date:</label>
        <input type="date" id="examDate" name="exam_date" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="examTime">Choose Time Slot:</label>
        <select id="examTime" name="exam_time" class="form-control" required>
          <option value="">Select Time</option>
          <option value="8:00-10:00 AM">8:00-10:00 AM</option>
          <option value="10:00-12:00 NN">10:00-12:00 NN</option>
          <option value="1:00-3:00 PM">1:00-3:00 PM</option>
        </select>
      </div>
      <div class="form-group">
        <label for="testingRoom">Choose Testing Room:</label>
        <select id="testingRoom" name="testing_room" class="form-control" required>
          <option value="">Select Room</option>
          <option value="1">Testing Room 1</option>
          <option value="2">Testing Room 2</option>
          <option value="3">Testing Room 3</option>
          <option value="4">Testing Room 4</option>
          <option value="5">Testing Room 5</option>
        </select>
      </div>
      <p>Remaining Slots: <span id="remainingSlots">30</span></p>
      <button type="submit" class="btn btn-primary">Confirm Schedule</button>
      <button type="button" class="btn btn-secondary" onclick="closeModal('takerModal')">Cancel</button>
    </form>
  </div>
</div>

<!-- Close modal function -->
<script>
  function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
  }
</script>