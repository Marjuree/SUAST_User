<div class="modal fade" id="viewListModal" tabindex="-1" role="dialog" aria-labelledby="viewListModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 15px; font-family: Arial, sans-serif;">

            <!-- Custom Header Layout -->
            <div class="container-fluid pb-3 border-bottom" style="font-family: 'Times New Roman', serif;">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h6 class="mb-1">Republic of the Philippines</h6>
                        <div class="divider mb-2" style="height: 5px; background-color: #003399; width: 325px;"></div>
                        <h3 class="mb-1"
                            style="color: #003399; font-weight: 900; letter-spacing: 1px; line-height: 1.2;">
                            DAVAO ORIENTAL <br> STATE UNIVERSITY
                        </h3>
                        <p class="mb-2" style="font-style: italic; font-size: 14px; color: #000 !important;">
                            "A university of excellence, innovation, and inclusion"
                        </p>
                        <div class="divider mb-2" style="height: 5px; background-color: #003399; width: 325px;"></div>

                    </div>

                    <div class="col-3 text-right">
                        <img src="image/logo1.png" alt="University Seal" style="height: 120px;">
                    </div>
                </div>

                <div class="text-center mt-2">
                    <h6 style="font-weight: bold; text-decoration: underline; margin-bottom: 6px;">
                        OFFICE OF STUDENT COUNSELING AND DEVELOPMENT
                    </h6>
                    <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0; font-size: 14px;">
                        State University Aptitude and Scholarship Test
                    </p>
                </div>
            </div>



            <!-- Reminders -->
            <div class="card border-danger p-4 bg-light rounded mt-4 text-center">
                <div class="card-body">
                    <h5 class="text-danger font-weight-bold mb-3">REMINDERS</h5>
                    <ul style="font-size: 14px; padding-left: 0; list-style: none; text-align: center; margin: 0;">
                        <li class="mb-2"> <strong> Bring yourOWN pencil, ball pen, and eraser. Strictly no
                            borrowing. </strong></li>
                        <li class="mb-2"><strong>Prepare the EXACT Testing Fee Payment of Php.
                                150.00</strong></li>
                        <li class="mb-2"><strong>Arrive 30 minutes before your examination schedule.</strong></li>
                        <li class="mb-2"><strong>Wear appropriate attire.</strong></li>
                        <li class="mb-2"><strong>You may bring snacks but observe the University’s no single-use plastic policy. </strong>
                        </li>
                        <li class="mb-2"><strong>Wear face-mask and observe safety protocols. </strong></li>
                        <li><strong>Wear white polo shirt.</strong></li>
                    </ul>
                </div>
            </div>

            <!-- Modal Body -->
            <!-- Modal Body -->
            <div class="modal-body">
                <?php
require_once "../../configuration/config.php"; // Ensure database connection

    $query = "SELECT * FROM tbl_reservation ORDER BY room, name ASC";
    $result = $con->query($query);

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $room = $row['room'];
        if (!isset($rooms[$room])) {
            $rooms[$room] = [];
        }
        $rooms[$room][] = $row;
    }
    ?>

                <!-- Horizontal scroll wrapper -->
                <div class="d-flex flex-row overflow-auto py-3" style="gap: 1.5rem;">
                    <?php foreach ($rooms as $room => $students): ?>
                    <div class="border border-dark rounded p-4"
                        style="min-width: 600px; flex-shrink: 0; background-color: #fff;">
                        <div class="row">
                            <div class="col-md-7">
                                <h5 class="mb-1 font-weight-bold" style="font-size: 16px;">
                                    <?= htmlspecialchars($students[0]['venue']) ?>
                                </h5>
                                <h5 class="mb-4">Date and Venue</h5>

                                <table class="table table-bordered">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>NAME</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $index => $student): ?>
                                        <tr>
                                            <td class="text-center"><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($student['name']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-5">
                                <div class="text-center border border-dark p-2 mb-3">
                                    <h5 class="font-weight-bold mb-0"><?= htmlspecialchars($room) ?></h5>
                                </div>
                                <div class="text-center border border-dark p-2 mb-3">
                                    <h5 class="font-weight-bold mb-0">
                                        <?= date('F d, Y', strtotime($students[0]['exam_date'])) ?></h5>
                                    <h5 class="mb-0" style="color: black;">Time:
                                        <?= date('g:i a', strtotime($students[0]['exam_time'])) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>