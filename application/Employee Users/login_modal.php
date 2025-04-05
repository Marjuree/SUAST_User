<!-- Login Modal -->
<div class="modal" id="empModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header text-center">
        <h4 class="modal-title"><strong>Employee Login</strong></h4>
        <img src="../img/ken.png" style="height:100px;"/>
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
          <button type="submit" class="btn btn-primary">Login</button>
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#regEmployee">Signup</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>



 
 <!-- Registration Modal -->
<div class="modal" id="regEmployee" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header text-center">
        <h4 class="modal-title"><strong>Employee Registration</strong></h4>
        <img src="../img/ken.png" style="height:100px;"/>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <form action="employee_registration.php" method="POST" id="registerForm">
          <div class="form-group">
            <label for="registerUsername">Username</label>
            <input type="text" class="form-control" id="registerUsername" name="employee_username" required>
          </div>
          <div class="form-group">
            <label for="registerEmail">Email</label>
            <input type="email" class="form-control" id="registerEmail" name="employee_email" required>
          </div>
          <div class="form-group">
            <label for="registerPassword">Password</label>
            <input type="password" class="form-control" id="registerPassword" name="employee_password" required>
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="employee_confirm_password" required>
          </div>
          <!-- Optional fields -->
          <div class="form-group">
            <label for="registerFirstName">First Name</label>
            <input type="text" class="form-control" id="registerFirstName" name="employee_first_name" required>
          </div>
          <div class="form-group">
            <label for="registerMiddleName">Middle Name</label>
            <input type="text" class="form-control" id="registerMiddleName" name="employee_middle_name" required>
          </div>
          <div class="form-group">
            <label for="registerLastName">Last Name</label>
            <input type="text" class="form-control" id="registerLastName" name="employee_last_name" required>
          </div>
          <button type="submit" class="btn btn-primary" name="register_employee">Register</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
