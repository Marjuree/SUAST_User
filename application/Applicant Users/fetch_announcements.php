<?php
require_once "../../configuration/config.php";

$sql = "SELECT id, admin_name, message, role, created_at 
        FROM tbl_announcement 
        WHERE role = 'SUAST' 
        ORDER BY created_at DESC";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    $count = 0;
    echo '<div class="announcements-section">';

    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row["id"]);
        $message = nl2br(htmlspecialchars($row["message"]));
        $role = htmlspecialchars($row["role"]);
        $created_at = strtotime($row["created_at"]);
        $formattedDate = date("F d, Y h:i A", $created_at);

        $sectionClass = ($count === 0) ? 'new' : 'earlier';
        if ($count === 0) {
            echo '<div class="section-title">New</div>';
        } elseif ($count === 1) {
            echo '
                <div class="section-separator">
                    <div class="dot"></div>
                </div>
                <div class="section-title">Earlier</div>
            ';
        }

        echo '
            <div class="announcement-card ' . $sectionClass . '" 
                 data-toggle="modal" 
                 data-target="#announcementModal' . $id . '"
                 style="border: 1px solid #b3b3b3;">
                <p style="font-weight: bold; margin: 0;">Announcement</p>
                <p style="margin: 5px 0;">' . (strlen($row["message"]) > 100 ? substr($message, 0, 100) . '...' : $message) . '</p>
                <span class="timestamp">' . $formattedDate . '</span>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="announcementModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="announcementModalLabel' . $id . '" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius: 15px;">
                        <div class="modal-header text-white" style="background: #B32121; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <h5 class="modal-title" id="announcementModalLabel' . $id . '">
                                <i class="fas fa-bullhorn mr-2" style="color: white;"></i>
                            </h5>
                           
                        </div>
                        <div class="modal-body" style="font-size: 14px;">
                           <div class="card" style="background: #f0f0f0; border: 1px solid black; padding: 10px;">
                            <p style="white-space: pre-wrap; margin: 0;">' . $message . '</p>
                            <div style="margin-top: 25px;"></div> <!-- Adds a gap/space -->
                            <p style="margin: 0;"><strong>From:</strong> ' . $role . '</p>
                            <p style="margin: 0;"><strong>Date:</strong> ' . $formattedDate . '</p>
                        </div>

                            
                        </div>

                        <div class="modal-footer" style="border-top: none;">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style ="background: #003366; color: #fff;">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        ';
        $count++;
    }

    echo '</div>';
} else {
    echo '<p style="text-align: center; color: #999;">No announcements yet.</p>';
}
?>