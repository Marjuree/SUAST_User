<!-- Bootstrap Modal -->
<div class="modal fade" id="requestClearanceModal" tabindex="-1" aria-labelledby="requestClearanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestClearanceModalLabel">Request Clearance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="request_clearance.php" method="POST">
                    <div class="form-group">
                        <label for="student_id">Student ID:</label>
                        <input type="text" name="student_id" id="student_id" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Request Clearance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>






<!-- Add Permit Request Modal -->
<div class="modal fade" id="addPermitModal" tabindex="-1" role="dialog" aria-labelledby="addPermitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPermitModalLabel">Request a Permit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="process_permit.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" name="student_name" required>
                    </div>
                    <div class="form-group">
                        <label for="purpose_name">Purpose</label>
                        <input type="text" class="form-control" id="purpose_name" name="purpose_name" required>
                    </div>
                    <div class="form-group">
                        <label for="course_year">Course & Year</label>
                        <input type="text" class="form-control" id="course_year" name="course_year" required>
                    </div>
                    <div class="form-group">
                        <label for="type_of_permit">Type of Permit</label>
                        <select class="form-control" id="type_of_permit" name="type_of_permit" required>
                            <option value="Internship Permit">Internship Permit</option>
                            <option value="Research Permit">Research Permit</option>
                            <option value="Travel Permit">Travel Permit</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit_permit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
