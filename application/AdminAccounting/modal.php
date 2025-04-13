<!--------------------------------Login Modal For Accounting----------------------------->
<div id="loginAccounting" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title"><strong>WELCOME TO ACCOUNTING OFFICE</strong></h4>
                    <img src="../img/ken.png" style="height:100px;"/>
                </div>
                <div class="modal-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label for="txt_username">Username</label>
                            <input type="text" class="form-control" name="txt_username" placeholder="Enter Username" required>
                        </div>
                        <div class="form-group">
                            <label for="txt_password">Password</label>
                            <input type="password" class="form-control" name="txt_password" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                            <label for="select_role">Office</label>
                            <select class="form-control" name="select_role" required>
                            <!-- <option value="" disabled selected>Select Office</option> -->
                                <option value="Accounting">Accounting</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="btn_login">Log in</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#registerAccounting">Signup</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <label id="error" class="label label-danger pull-right">
                            <?php echo isset($login_error) ? $login_error : ''; ?>
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!------------------------------------Registration Modal for Accounting----------------------------->
    <div id="registerAccounting" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">User Registration</h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" name="reg_username" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="reg_password" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <div class="form-group">
                            <label>Office</label>
                            <select class="form-control" name="reg_role">
                                <!-- <option value="" disabled selected>Select Office</option> -->
                                <option value="Accounting">Accounting</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" name="btn_register">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Student Record and Balances Modal -->
<div class="modal fade" id="studentBalanceModal" tabindex="-1" role="dialog" aria-labelledby="studentBalanceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentBalanceLabel">Student Record and Balances</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Student Records Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch student balances from the database
                        $query = "SELECT student_id, CONCAT(first_name, ' ', last_name) AS name, course, balance FROM tbl_students";
                        $result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['student_id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['course']}</td>
                                    <td>₱{$row['balance']}</td>
                                    <td><button class='btn btn-primary btn-sm'>View</button></td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<!-- Add Student Balance Modal -->
<div class="modal fade" id="addBalanceModal" tabindex="-1" aria-labelledby="addBalanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBalanceLabel">Add Student Balance</h5>
            </div>
            <form method="POST" action="add_balance.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Student ID:</label>
                        <input type="text" class="form-control" name="student_id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student Name:</label>
                        <input type="text" class="form-control" name="student_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Balance:</label>
                        <input type="number" class="form-control" name="total_balance" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Payment Date:</label>
                        <input type="date" class="form-control" name="last_payment" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Date:</label>
                        <input type="date" class="form-control" name="due_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
