<!------------------------------------------------- Service HRMO --------------------------------------------->
<div class="modal fade" id="servicehrmo" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content text-center p-4" style="border-radius: 15px; border: none; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);">
      
      <br><br>
      <!-- Logo -->
      <div class="d-flex justify-content-center mb-3">
        <img src="../../img/ken.png" alt="Logo" style="width: 100px; height: 100px;">
      </div>
      <!-- Divider -->
      <hr style="width: 60%; margin: auto; border: 1px solid #555;">
      <br>
      <p><strong>HRMO OFFICE</strong></p>
      <!-- Title -->
      <h5 class="fw-bold mt-3 text-uppercase text-dark">Choose a Frontline Service</h5>
      <!-- Buttons (Triggers Request Forms) -->
      <div class="buttons-container">
        <button class="button" data-toggle="modal" data-target="#requestServiceRecord">Issuance of Service Record</button>
        <br>
        <button class="button" data-toggle="modal" data-target="#requestCertification">Issuance of Certification</button>
        <br>
        <button class="button" data-toggle="modal" data-target="#requestLeaveApplication">Processing of Application for Leave</button>
        <br>
        <button class="button" data-toggle="modal" data-target="#requestPersonnelInquiry">Inquiries on Personnel-related Matters</button>
      </div>
      <br><br>
    </div>
  </div>
</div>
 
 

<!-- Service Record Request -->
<div class="modal fade" id="requestServiceRecord" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h5 class="modal-title">Request Service Record</h5>
      <form action="process_request.php" method="POST">
        <input type="hidden" name="request_type" value="Service Record">
        
        <label>Date of Request:</label>
        <input type="date" name="date_request" required class="form-control">
        
        <label>Full Name:</label>
        <input type="text" name="name" required class="form-control">
        
        <label>Faculty/Institute Name:</label>
        <input type="text" name="faculty" required class="form-control">
        
        <label>Reason for Request:</label>
        <textarea name="reason" required class="form-control"></textarea>
        <br>
        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>


<!-- Certification Request -->
<div class="modal fade" id="requestCertification" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h5 class="modal-title">Request Certification</h5>
      <form action="process_certification.php" method="POST">
        <input type="hidden" name="request_type" value="Certification">
        
        <label>Date of Request:</label>
        <input type="date" name="date_request" required class="form-control">
        
        <label>Full Name:</label>
        <input type="text" name="name" required class="form-control">
        
        <label>Faculty/Institute Name:</label>
        <input type="text" name="faculty" required class="form-control">
        
        <label>Reason for Request:</label>
        <textarea name="reason" required class="form-control"></textarea>
        <br>
        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>


<!-- Leave Processing Request -->
<div class="modal fade" id="requestLeaveApplication" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h5 class="modal-title">Request Leave Processing</h5>
      <form action="process_leave.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="request_type" value="Leave Processing">
        
        <label>Date of Request:</label>
        <input type="date" name="date_request" required class="form-control">
        
        <label>Full Name:</label>
        <input type="text" name="name" required class="form-control">
        
        <label>Faculty/Institute Name:</label>
        <input type="text" name="faculty" required class="form-control">
        
        <label>Leave Dates:</label>
        <input type="text" name="leave_dates" required class="form-control">
        
        <label>Upload CSC Application for Leave Form (CS Form No. 6, Revised 2020):</label>
        <input type="file" name="leave_form" accept=".pdf,.doc,.docx" required class="form-control">
        <br>
        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>


<!-- Personnel Inquiry Request -->
<div class="modal fade" id="requestPersonnelInquiry" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <h5 class="modal-title">Request Personnel Inquiry</h5>
      <form action="process_inquiry.php" method="POST">
        <input type="hidden" name="request_type" value="Personnel Inquiry">
        
        <label>Date of Request:</label>
        <input type="date" name="date_request" required class="form-control">
        
        <label>Full Name:</label>
        <input type="text" name="name" required class="form-control">
        
        <label>Faculty/Institute Name:</label>
        <input type="text" name="faculty" required class="form-control">
        
        <label>Question:</label>
        <textarea name="question" required class="form-control"></textarea>
        <br>
        <button type="submit" class="btn btn-primary">Submit Request</button>
      </form>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>

