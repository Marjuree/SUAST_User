<!-- Login Modal -->
<div class="modal fade" id="empModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Updated Modal Header -->
      <div class="modal-header flex-column align-items-center"
        style="outline: none !important; box-shadow: none !important; border: none;">
        <!-- Logo -->
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px; margin-top: -40px;">
        <h4 class="modal-title" id="loginModalLabel" style="font-weight: 700; margin-top: -50px;">Welcome, Employee!</h4>
        <h4 class="modal-title" style="font-size: 10px;">please login your account</h4>

        <button type="button" class="close position-absolute" style="right: 10px; top: 10px;" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <form action="employee_login.php" method="POST" id="loginForm">
          <div class="form-group">
            <label for="loginUsername">Username</label>
            <input type="text" class="form-control" id="loginUsername" name="username" required>
          </div>
          <div class="form-group">
            <label for="loginPassword">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="employee_password" required>
          </div>
          <button type="submit" class="btn btn-block"
            style="background-color: #02457A; color: white;">Login</button>

          <p class="mt-2 text-center">
            <span style="color: black;">New User?</span>
            <a href="#" data-toggle="modal" data-target="#agreementModal2" class="text-warning ">
              Register
            </a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>





<!-- Agreement Modal -->
<div class="modal fade" id="agreementModal2" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header flex-column align-items-center"
        style="outline: none !important; box-shadow: none !important; border:none;">
        <!-- Logo on top -->
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px;">
        <!-- Title below logo -->
        <h5 class="modal-title" id="agreementModalLabel" style="margin-top:-50px; font-weight: 700;"> Agreement Policy2
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
        <a href="#" class="btn btn-block" data-toggle="modal" data-target="#regEmployee" data-dismiss="modal"
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

<!-- Employee Registration Modal -->
<div class="modal fade" id="regEmployee" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content" style="outline: none !important; box-shadow: none !important; border: none;">
      <div class="modal-header flex-column align-items-center">
        <!-- Logo above the title -->
        <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px;" class="img-fluid">

        <h4 class="modal-title text-center font-weight-bold mb-1" style="margin-top: -40px;">Employee Registration</h4>
        <h6 class="modal-title text-center text-muted mb-3" style="font-size: 0.9rem;">Please create your account</h6>

        <button type="button" class="close position-absolute" style="right: 15px; top: 15px;" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body px-3 px-sm-4" style="outline: none !important; box-shadow: none !important; border: none;">
        <form action="employee_registration.php" method="POST" id="registerForm">
          <div class="form-group">
            <label for="registerFirstName">First Name</label>
            <input type="text" class="form-control" id="registerFirstName" name="employee_first_name" required>
          </div>
          <div class="form-group">
            <label for="registerMiddleName">Middle Name (Optional)</label>
            <input type="text" class="form-control" id="registerMiddleName" name="employee_middle_name">
          </div>
          <div class="form-group">
            <label for="registerLastName">Last Name</label>
            <input type="text" class="form-control" id="registerLastName" name="employee_last_name" required>
          </div>
          <div class="form-group">
            <label for="registerEmail">Email</label>
            <input type="email" class="form-control" id="registerEmail" name="employee_email" required>
          </div>
          <div class="form-group">
            <label for="registerUsername">Username</label>
            <input type="text" class="form-control" id="registerUsername" name="employee_username" required>
          </div>
          <div class="form-group">
            <label for="registerFaculty">Position</label>
            <select class="form-control" id="registerFaculty" name="employee_faculty" required>
              <option value="">-- Select --</option>
              <option value="Faculty">Faculty</option>
              <option value="Staff">Staff</option>
            </select>
          </div>
          <div class="form-group">
            <label for="registerPassword">Password</label>
            <input type="password" class="form-control" id="registerPassword" name="employee_password" required minlength="8">
            <small id="reg_passwordHelp" class="text-muted"></small>
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="employee_confirm_password" required minlength="8">
          </div>
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="privacy_notice" name="privacy_notice_accepted" value="1" required>
            <label class="form-check-label" for="privacy_notice">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I accept the privacy notice</label>
          </div>
          <button type="submit" class="btn btn-primary btn-block" name="register_employee" style="background-color: #02457A;">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

