<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<!-- Applicant Login Modal -->
<div class="modal fade" id="loginApplicant" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Applicant Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="applicant_auth.php" method="POST">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="applicant_password">Password</label>
            <input type="password" class="form-control" id="applicant_password" name="applicant_password" required>
          </div>
          <button type="submit" name="btn_applicant_login" class="btn btn-primary btn-block">
            <i class="fas fa-lock"></i> Login
          </button>
          <button type="button" class="btn btn-secondary btn-block mt-2" data-toggle="modal" data-target="#agreementModal">
            <i class="fas fa-pencil-alt"></i> Register
          </button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Agreement Modal -->
<div class="modal fade" id="agreementModal" tabindex="-1" role="dialog" aria-labelledby="agreementModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agreementModalLabel">Agreement Policy</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>By participating in this data collection, you agree to the following:</p>
        <ol>
          <li>All provided data will be processed in compliance with the Data Privacy Act of 2012 (Republic Act 10173).</li>
          <li>Your data will be used exclusively for this application.</li>
          <li>You have the right to access, update, and delete your personal data at any time.</li>
        </ol>
        <p>Please confirm your agreement before proceeding with the registration.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">I Disagree</button>
        <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#registerApplicant">I Agree</a>
      </div>
    </div>
  </div>
</div>

<!-- Applicant Registration Modal -->
<div class="modal fade" id="registerApplicant" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Applicant Registration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
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
            <label for="university_email">University Email</label>
            <input type="email" class="form-control" id="university_email" name="university_email" required>
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="applicant_password">Password</label>
            <input type="password" class="form-control" id="applicant_password" name="applicant_password" required>
          </div>
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="privacy_notice" name="privacy_notice_accepted" value="1" required>
            <label class="form-check-label" for="privacy_notice">I accept the Privacy Notice</label>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>

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

<script>
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
</script>
