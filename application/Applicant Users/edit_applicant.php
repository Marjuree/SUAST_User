<?php
session_start();
require_once "../../configuration/config.php";

// Check if the user is logged in and is an applicant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

session_regenerate_id(true);

$applicant_id = $_SESSION['applicant_id'];
$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "Applicant";

// Fetch applicant data from the database using the applicant_id from the session
$query = "SELECT * FROM tbl_applicants WHERE applicant_id = ?";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $applicant_id);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc();

if (!$applicant) {
    die("Applicant not found.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Applicant</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>

<style>
h5 {
    font-size: 20px !important;
    font-weight: bold !important;
}

.profile-card {
    border: 2px solid #f0f0f0;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    background-color: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 300px;
}

.profile-card .card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

/* Stylish upload button */
.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    cursor: pointer;
    margin-top: 15px;
    margin-left: 90px;
    background-color: #007bff;
    color: #fff;
    font-weight: 500;
    border-radius: 25px;
    transition: background-color 0.3s ease;
    border: none;
}

.custom-file-upload:hover {
    background-color: #0056b3;
}

.custom-file-upload input[type="file"] {
    display: none;
}

/* Profile image */
.profile-img {
    border: 3px solid #f0f0f0;
    padding: 5px;
    width: 150px;
    height: 150px;
    object-fit: cover;
    transition: transform 0.3s ease;
    margin-left: 70px;
}

.profile-img:hover {
    transform: scale(1.05);
}

/* Responsive tweaks */
@media (max-width: 767px) {
    .profile-card {
        padding: 15px;
    }

    .profile-card .card-title {
        font-size: 1.2rem;
    }
}
</style>

<body class="skin-blue">
    <?php
    require_once('../../configuration/config.php');
    require_once('includes/header.php');
    require_once('../../includes/sidebar.php');
    require_once('../../includes/head_css.php'); ?>



    <!-- Edit Applicant Modal -->
    <div class="modal fade" id="editApplicantModal" tabindex="-1" role="dialog" aria-labelledby="editApplicantLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="update_applicant.php" method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editApplicantLabel">Edit Applicant</h5>
                        <button type="button" class="close text-white" aria-label="Close"
                            onclick="window.location.href='applicant.php'">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="container-fluid">
                            <input type="hidden" name="id" value="<?= $applicant['id']; ?>">

                            <!-- Personal Information -->
                            <h5 class="mt-3 text-center">Personal Information</h5>

                            <div class="card mb-4 profile-card">
                                <div class="card-body">
                                    <h5 class="card-title text-center">Profile Image</h5>
                                    <div class="row justify-content-center mb-3">
                                        <div class="col-md-6 d-flex flex-column align-items-center">

                                            <!-- Display profile image if available -->
                                            <?php 
                                        // Check if image_blob is available
                                        if (!empty($applicant['image_blob'])):
                                            // Convert the image_blob to base64
                                            $image_base64 = base64_encode($applicant['image_blob']);
                                            $image_path = 'data:image/jpeg;base64,' . $image_base64;
                                        ?>
                                            <div class="mt-3">
                                                <img src="<?= $image_path; ?>" alt="Profile Image"
                                                    class="rounded-circle profile-img">
                                            </div>
                                            <label class="custom-file-upload mb-3">
                                                <input type="file" name="image" id="image">
                                                Choose Image
                                            </label>
                                            <?php else: ?>
                                            <div class="mt-3">
                                                <img src="path/to/default/image.jpg" alt="Default Profile Image"
                                                    class="rounded-circle profile-img">
                                            </div>
                                            <label class="custom-file-upload mb-3">
                                                <input type="file" name="image" id="image">
                                                Choose Image
                                            </label>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div style="margin-top: 80px;" class="row">
                                <?php
                                    $personal = [
                                        "lname" => "Last Name", "fname" => "First Name", "mname" => "Middle Name",
                                        "age" => "Age", "religion" => "Religion",
                                        "nationality" => "Nationality", "civilstatus" => "Civil Status", 
                                        "contact" => "Contact No."
                                    ];
                                    foreach ($personal as $name => $label) {
                                        echo "<div class='col-md-4 mb-3'>
                                                <label for='{$name}'>{$label}</label>
                                                <input type='text' class='form-control' name='{$name}' id='{$name}' value='{$applicant[$name]}' required>
                                            </div>";
                                    }
                                    ?>

                                <!-- Ethnicity Dropdown -->
                                <div class="col-md-4 mb-3">
                                    <label for="ethnicity">Ethnicity</label>
                                    <select class="form-control" name="ethnicity" id="ethnicity" required>
                                        <option value="" disabled selected>Select Ethnicity</option>
                                        <option value="Indigenous People"
                                            <?= ($applicant['ethnicity'] == 'Indigenous People') ? 'selected' : ''; ?>>
                                            Indigenous People</option>
                                        <option value="Not a member"
                                            <?= ($applicant['ethnicity'] == 'Not a member') ? 'selected' : ''; ?>>Not a
                                            member</option>
                                        <option value="Abelling"
                                            <?= ($applicant['ethnicity'] == 'Abelling') ? 'selected' : ''; ?>>Abelling
                                        </option>
                                        <option value="Agta-Agay"
                                            <?= ($applicant['ethnicity'] == 'Agta-Agay') ? 'selected' : ''; ?>>Agta-Agay
                                        </option>
                                        <option value="Agta"
                                            <?= ($applicant['ethnicity'] == 'Agta') ? 'selected' : ''; ?>>Agta</option>
                                        <option value="Agutaynen"
                                            <?= ($applicant['ethnicity'] == 'Agutaynen') ? 'selected' : ''; ?>>Agutaynen
                                        </option>
                                        <option value="Alangan-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Alangan-Mangyan') ? 'selected' : ''; ?>>
                                            Alangan-Mangyan</option>
                                        <option value="Alta"
                                            <?= ($applicant['ethnicity'] == 'Alta') ? 'selected' : ''; ?>>Alta</option>
                                        <option value="Applai"
                                            <?= ($applicant['ethnicity'] == 'Applai') ? 'selected' : ''; ?>>Applai
                                        </option>
                                        <option value="Aromanen-Ne-Manuvu"
                                            <?= ($applicant['ethnicity'] == 'Aromanen-Ne-Manuvu') ? 'selected' : ''; ?>>
                                            Aromanen-Ne-Manuvu</option>
                                        <option value="Ata-Manobo"
                                            <?= ($applicant['ethnicity'] == 'Ata-Manobo') ? 'selected' : ''; ?>>
                                            Ata-Manobo</option>
                                        <option value="Ata"
                                            <?= ($applicant['ethnicity'] == 'Ata') ? 'selected' : ''; ?>>Ata</option>
                                        <option value="Ati"
                                            <?= ($applicant['ethnicity'] == 'Ati') ? 'selected' : ''; ?>>Ati</option>
                                        <option value="Ayangan"
                                            <?= ($applicant['ethnicity'] == 'Ayangan') ? 'selected' : ''; ?>>Ayangan
                                        </option>
                                        <option value="Ayta-Ambala"
                                            <?= ($applicant['ethnicity'] == 'Ayta-Ambala') ? 'selected' : ''; ?>>
                                            Ayta-Ambala</option>
                                        <option value="Ayta-Magantsi"
                                            <?= ($applicant['ethnicity'] == 'Ayta-Magantsi') ? 'selected' : ''; ?>>
                                            Ayta-Magantsi</option>
                                        <option value="Ayta-Magbukun"
                                            <?= ($applicant['ethnicity'] == 'Ayta-Magbukun') ? 'selected' : ''; ?>>
                                            Ayta-Magbukun</option>
                                        <option value="Ayta"
                                            <?= ($applicant['ethnicity'] == 'Ayta') ? 'selected' : ''; ?>>Ayta</option>
                                        <option value="Badjao"
                                            <?= ($applicant['ethnicity'] == 'Badjao') ? 'selected' : ''; ?>>Badjao
                                        </option>
                                        <option value="Bago"
                                            <?= ($applicant['ethnicity'] == 'Bago') ? 'selected' : ''; ?>>Bago</option>
                                        <option value="Bagobo-Klata"
                                            <?= ($applicant['ethnicity'] == 'Bagobo-Klata') ? 'selected' : ''; ?>>
                                            Bagobo-Klata</option>
                                        <option value="Bagobo-Tagakawa"
                                            <?= ($applicant['ethnicity'] == 'Bagobo-Tagakawa') ? 'selected' : ''; ?>>
                                            Bagobo-Tagakawa</option>
                                        <option value="Balangao"
                                            <?= ($applicant['ethnicity'] == 'Balangao') ? 'selected' : ''; ?>>Balangao
                                        </option>
                                        <option value="Bangon-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Bangon-Mangyan') ? 'selected' : ''; ?>>
                                            Bangon-Mangyan</option>
                                        <option value="Bantoanon"
                                            <?= ($applicant['ethnicity'] == 'Bantoanon') ? 'selected' : ''; ?>>Bantoanon
                                        </option>
                                        <option value="Banwaon"
                                            <?= ($applicant['ethnicity'] == 'Banwaon') ? 'selected' : ''; ?>>Banwaon
                                        </option>
                                        <option value="Batak"
                                            <?= ($applicant['ethnicity'] == 'Batak') ? 'selected' : ''; ?>>Batak
                                        </option>
                                        <option value="Blaan"
                                            <?= ($applicant['ethnicity'] == 'Blaan') ? 'selected' : ''; ?>>Blaan
                                        </option>
                                        <option value="Bontok"
                                            <?= ($applicant['ethnicity'] == 'Bontok') ? 'selected' : ''; ?>>Bontok
                                        </option>
                                        <option value="Bugkalot"
                                            <?= ($applicant['ethnicity'] == 'Bugkalot') ? 'selected' : ''; ?>>Bugkalot
                                        </option>
                                        <option value="Buhid-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Buhid-Mangyan') ? 'selected' : ''; ?>>
                                            Buhid-Mangyan</option>
                                        <option value="Bukidnon"
                                            <?= ($applicant['ethnicity'] == 'Bukidnon') ? 'selected' : ''; ?>>Bukidnon
                                        </option>
                                        <option value="Cagayanen"
                                            <?= ($applicant['ethnicity'] == 'Cagayanen') ? 'selected' : ''; ?>>Cagayanen
                                        </option>
                                        <option value="Calinga"
                                            <?= ($applicant['ethnicity'] == 'Calinga') ? 'selected' : ''; ?>>Calinga
                                        </option>
                                        <option value="Cuyunon"
                                            <?= ($applicant['ethnicity'] == 'Cuyunon') ? 'selected' : ''; ?>>Cuyunon
                                        </option>
                                        <option value="Dibabawon"
                                            <?= ($applicant['ethnicity'] == 'Dibabawon') ? 'selected' : ''; ?>>Dibabawon
                                        </option>
                                        <option value="Dumagat"
                                            <?= ($applicant['ethnicity'] == 'Dumagat') ? 'selected' : ''; ?>>Dumagat
                                        </option>
                                        <option value="Eskaya"
                                            <?= ($applicant['ethnicity'] == 'Eskaya') ? 'selected' : ''; ?>>Eskaya
                                        </option>
                                        <option value="Gaddang"
                                            <?= ($applicant['ethnicity'] == 'Gaddang') ? 'selected' : ''; ?>>Gaddang
                                        </option>
                                        <option value="Gubatnon-Ratagnon-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Gubatnon-Ratagnon-Mangyan') ? 'selected' : ''; ?>>
                                            Gubatnon-Ratagnon-Mangyan</option>
                                        <option value="Hanunuo-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Hanunuo-Mangyan') ? 'selected' : ''; ?>>
                                            Hanunuo-Mangyan</option>
                                        <option value="Higaonon"
                                            <?= ($applicant['ethnicity'] == 'Higaonon') ? 'selected' : ''; ?>>Higaonon
                                        </option>
                                        <option value="Ibaloi"
                                            <?= ($applicant['ethnicity'] == 'Ibaloi') ? 'selected' : ''; ?>>Ibaloi
                                        </option>
                                        <option value="Ibanag"
                                            <?= ($applicant['ethnicity'] == 'Ibanag') ? 'selected' : ''; ?>>Ibanag
                                        </option>
                                        <option value="Ibatan"
                                            <?= ($applicant['ethnicity'] == 'Ibatan') ? 'selected' : ''; ?>>Ibatan
                                        </option>
                                        <option value="Ilongot"
                                            <?= ($applicant['ethnicity'] == 'Ilongot') ? 'selected' : ''; ?>>Ilongot
                                        </option>
                                        <option value="Imalawa"
                                            <?= ($applicant['ethnicity'] == 'Imalawa') ? 'selected' : ''; ?>>Imalawa
                                        </option>
                                        <option value="Iraya-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Iraya-Mangyan') ? 'selected' : ''; ?>>
                                            Iraya-Mangyan</option>
                                        <option value="Isinai"
                                            <?= ($applicant['ethnicity'] == 'Isinai') ? 'selected' : ''; ?>>Isinai
                                        </option>
                                        <option value="Isnag-of-Apayao"
                                            <?= ($applicant['ethnicity'] == 'Isnag-of-Apayao') ? 'selected' : ''; ?>>
                                            Isnag-of-Apayao</option>
                                        <option value="Isnag-of-Ilocos Norte"
                                            <?= ($applicant['ethnicity'] == 'Isnag-of-Ilocos Norte') ? 'selected' : ''; ?>>
                                            Isnag-of-Ilocos Norte</option>
                                        <option value="Isneg-Isnag"
                                            <?= ($applicant['ethnicity'] == 'Isneg-Isnag') ? 'selected' : ''; ?>>
                                            Isneg-Isnag</option>
                                        <option value="Itawes"
                                            <?= ($applicant['ethnicity'] == 'Itawes') ? 'selected' : ''; ?>>Itawes
                                        </option>
                                        <option value="Itneg"
                                            <?= ($applicant['ethnicity'] == 'Itneg') ? 'selected' : ''; ?>>Itneg
                                        </option>
                                        <option value="Ivatan"
                                            <?= ($applicant['ethnicity'] == 'Ivatan') ? 'selected' : ''; ?>>Ivatan
                                        </option>
                                        <option value="Iwak"
                                            <?= ($applicant['ethnicity'] == 'Iwak') ? 'selected' : ''; ?>>Iwak</option>
                                        <option value="Kabihug"
                                            <?= ($applicant['ethnicity'] == 'Kabihug') ? 'selected' : ''; ?>>Kabihug
                                        </option>
                                        <option value="Kagan/Kalagan"
                                            <?= ($applicant['ethnicity'] == 'Kagan/Kalagan') ? 'selected' : ''; ?>>
                                            Kagan/Kalagan</option>
                                        <option value="Kalanguya"
                                            <?= ($applicant['ethnicity'] == 'Kalanguya') ? 'selected' : ''; ?>>Kalanguya
                                        </option>
                                        <option value="Kalinga"
                                            <?= ($applicant['ethnicity'] == 'Kalinga') ? 'selected' : ''; ?>>Kalinga
                                        </option>
                                        <option value="Kamigin"
                                            <?= ($applicant['ethnicity'] == 'Kamigin') ? 'selected' : ''; ?>>Kamigin
                                        </option>
                                        <option value="Kankaney"
                                            <?= ($applicant['ethnicity'] == 'Kankaney') ? 'selected' : ''; ?>>Kankaney
                                        </option>
                                        <option value="Karao"
                                            <?= ($applicant['ethnicity'] == 'Karao') ? 'selected' : ''; ?>>Karao
                                        </option>
                                        <option value="Karulano"
                                            <?= ($applicant['ethnicity'] == 'Karulano') ? 'selected' : ''; ?>>Karulano
                                        </option>
                                        <option value="Kalibogan"
                                            <?= ($applicant['ethnicity'] == 'Kalibogan') ? 'selected' : ''; ?>>Kalibogan
                                        </option>
                                        <option value="Malaueg"
                                            <?= ($applicant['ethnicity'] == 'Malaueg') ? 'selected' : ''; ?>>Malaueg
                                        </option>
                                        <option value="Mamanwa"
                                            <?= ($applicant['ethnicity'] == 'Mamanwa') ? 'selected' : ''; ?>>Mamanwa
                                        </option>
                                        <option value="Mandaya"
                                            <?= ($applicant['ethnicity'] == 'Mandaya') ? 'selected' : ''; ?>>Mandaya
                                        </option>
                                        <option value="Mangguangan"
                                            <?= ($applicant['ethnicity'] == 'Mangguangan') ? 'selected' : ''; ?>>
                                            Mangguangan</option>
                                        <option value="Manobo-Blit"
                                            <?= ($applicant['ethnicity'] == 'Manobo-Blit') ? 'selected' : ''; ?>>
                                            Manobo-Blit</option>
                                        <option value="Manobo-Dulangan"
                                            <?= ($applicant['ethnicity'] == 'Manobo-Dulangan') ? 'selected' : ''; ?>>
                                            Manobo-Dulangan</option>
                                        <option value="Manobo-Lambanguian"
                                            <?= ($applicant['ethnicity'] == 'Manobo-Lambanguian') ? 'selected' : ''; ?>>
                                            Manobo-Lambanguian</option>
                                        <option value="Manobo"
                                            <?= ($applicant['ethnicity'] == 'Manobo') ? 'selected' : ''; ?>>Manobo
                                        </option>
                                        <option value="Manobo-Tasabay"
                                            <?= ($applicant['ethnicity'] == 'Manobo-Tasabay') ? 'selected' : ''; ?>>
                                            Manobo-Tasabay</option>
                                        <option value="Mansaka"
                                            <?= ($applicant['ethnicity'] == 'Mansaka') ? 'selected' : ''; ?>>Mansaka
                                        </option>
                                        <option value="Matigsalog"
                                            <?= ($applicant['ethnicity'] == 'Matigsalog') ? 'selected' : ''; ?>>
                                            Matigsalog</option>
                                        <option value="Molbog"
                                            <?= ($applicant['ethnicity'] == 'Molbog') ? 'selected' : ''; ?>>Molbog
                                        </option>
                                        <option value="Obu-Manuvu"
                                            <?= ($applicant['ethnicity'] == 'Obu-Manuvu') ? 'selected' : ''; ?>>
                                            Obu-Manuvu</option>
                                        <option value="Palawan"
                                            <?= ($applicant['ethnicity'] == 'Palawan') ? 'selected' : ''; ?>>Palawan
                                        </option>
                                        <option value="Panay-Bukidnon"
                                            <?= ($applicant['ethnicity'] == 'Panay-Bukidnon') ? 'selected' : ''; ?>>
                                            Panay-Bukidnon</option>
                                        <option value="Sama-Bajau"
                                            <?= ($applicant['ethnicity'] == 'Sama-Bajau') ? 'selected' : ''; ?>>
                                            Sama-Bajau</option>
                                        <option value="Sama-Bangingi"
                                            <?= ($applicant['ethnicity'] == 'Sama-Bangingi') ? 'selected' : ''; ?>>
                                            Sama-Bangingi</option>
                                        <option value="Sama"
                                            <?= ($applicant['ethnicity'] == 'Sama') ? 'selected' : ''; ?>>Sama</option>
                                        <option value="Sibuyan-Mangyan-Tagabukid"
                                            <?= ($applicant['ethnicity'] == 'Sibuyan-Mangyan-Tagabukid') ? 'selected' : ''; ?>>
                                            Sibuyan-Mangyan-Tagabukid</option>
                                        <option value="Tadyawan-Mangyan"
                                            <?= ($applicant['ethnicity'] == 'Tadyawan-Mangyan') ? 'selected' : ''; ?>>
                                            Tadyawan-Mangyan</option>
                                        <option value="Tagakaulo"
                                            <?= ($applicant['ethnicity'] == 'Tagakaulo') ? 'selected' : ''; ?>>Tagakaulo
                                        </option>
                                        <option value="Tagbanua-Calamian"
                                            <?= ($applicant['ethnicity'] == 'Tagbanua-Calamian') ? 'selected' : ''; ?>>
                                            Tagbanua-Calamian</option>
                                        <option value="Tagbanua"
                                            <?= ($applicant['ethnicity'] == 'Tagbanua') ? 'selected' : ''; ?>>Tagbanua
                                        </option>
                                        <option value="Tagbanoa-Tandulanen"
                                            <?= ($applicant['ethnicity'] == 'Tagbanoa-Tandulanen') ? 'selected' : ''; ?>>
                                            Tagbanoa-Tandulanen</option>
                                        <option value="Talaandig"
                                            <?= ($applicant['ethnicity'] == 'Talaandig') ? 'selected' : ''; ?>>Talaandig
                                        </option>
                                        <option value="Tau-Buid"
                                            <?= ($applicant['ethnicity'] == 'Tau-Buid') ? 'selected' : ''; ?>>Tau-Buid
                                        </option>
                                        <option value="Tboli"
                                            <?= ($applicant['ethnicity'] == 'Tboli') ? 'selected' : ''; ?>>Tboli
                                        </option>
                                        <option value="Teduray"
                                            <?= ($applicant['ethnicity'] == 'Teduray') ? 'selected' : ''; ?>>Teduray
                                        </option>
                                        <option value="Tigwahanon"
                                            <?= ($applicant['ethnicity'] == 'Tigwahanon') ? 'selected' : ''; ?>>
                                            Tigwahanon</option>
                                        <option value="Tingguian"
                                            <?= ($applicant['ethnicity'] == 'Tingguian') ? 'selected' : ''; ?>>Tingguian
                                        </option>
                                        <option value="Tinonanen"
                                            <?= ($applicant['ethnicity'] == 'Tinonanen') ? 'selected' : ''; ?>>Tinonanen
                                        </option>
                                        <option value="Tuwali"
                                            <?= ($applicant['ethnicity'] == 'Tuwali') ? 'selected' : ''; ?>>Tuwali
                                        </option>
                                        <option value="Umayamnon"
                                            <?= ($applicant['ethnicity'] == 'Umayamnon') ? 'selected' : ''; ?>>Umayamnon
                                        </option>
                                        <option value="Yakan"
                                            <?= ($applicant['ethnicity'] == 'Yakan') ? 'selected' : ''; ?>>Yakan
                                        </option>
                                        <option value="Yapayao"
                                            <?= ($applicant['ethnicity'] == 'Yapayao') ? 'selected' : ''; ?>>Yapayao
                                        </option>
                                        <option value="Yogad"
                                            <?= ($applicant['ethnicity'] == 'Yogad') ? 'selected' : ''; ?>>Yogad
                                        </option>
                                    </select>

                                </div>

                                <!-- Birthdate Field -->
                                <div class="col-md-4 mb-3">
                                    <label for="bdate">Birthdate</label>
                                    <input type="date" class="form-control" name="bdate" id="bdate"
                                        value="<?= $applicant['bdate']; ?>" required>
                                </div>

                                <!-- Gender Dropdown -->
                                <div class="col-md-4 mb-3">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option value="Male" <?= ($applicant['gender'] == 'Male') ? 'selected' : ''; ?>>
                                            Male</option>
                                        <option value="Female"
                                            <?= ($applicant['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Prefer not to say"
                                            <?= ($applicant['gender'] == 'Prefer not to say') ? 'selected' : ''; ?>>
                                            Prefer not to say</option>
                                    </select>
                                </div>

                                <!-- Email Field -->
                                <div class="col-md-4 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        value="<?= isset($applicant['email']) ? $applicant['email'] : ''; ?>" required>
                                </div>

                                <!-- Document Upload Section -->
                                <!-- <div class="col-md-4 mb-3">
                                    <label for="document">Upload Document</label>
                                    <input type="file" class="form-control" name="document" id="document"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                        <?= isset($applicant['document']) && $applicant['document'] ? '' : 'required'; ?>>

                                    <?php 
                                        if (isset($applicant['document_blob']) && !empty($applicant['document_blob'])):
                                            $document_base64 = base64_encode($applicant['document_blob']);
                                            $document_path = 'data:application/pdf;base64,' . $document_base64;
                                    ?>
                                    <div class="mt-3">
                                        <a href="<?= $document_path; ?>" download class="btn btn-info">
                                            Download Document
                                        </a>
                                    </div>
                                    <?php else: ?>
                                    <div class="mt-3">
                                        <p>No document uploaded</p>
                                    </div>
                                    <?php endif; ?>
                                </div> -->






                            </div>

                            <!-- Address Section -->
                            <h5 class="mt-4">Address</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="purok">Purok</label>
                                    <input type="text" class="form-control" name="purok" id="purok"
                                        value="<?= $applicant['purok']; ?>" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="purok">Barangay</label>

                                    <select name="barangay" class="form-control" id="barangay">
                                        <option value="" disabled selected>Select Barangay</option>

                                        <!-- Mati -->
                                        <option value="Badas, Mati"
                                            <?= ($applicant['barangay'] == 'Badas, Mati') ? 'selected' : ''; ?>>Badas,
                                            Mati</option>
                                        <option value="Bobon, Mati"
                                            <?= ($applicant['barangay'] == 'Bobon, Mati') ? 'selected' : ''; ?>>Bobon,
                                            Mati</option>
                                        <option value="Buso, Mati"
                                            <?= ($applicant['barangay'] == 'Buso, Mati') ? 'selected' : ''; ?>>Buso,
                                            Mati</option>
                                        <option value="Cabuaya, Mati"
                                            <?= ($applicant['barangay'] == 'Cabuaya, Mati') ? 'selected' : ''; ?>>
                                            Cabuaya, Mati</option>
                                        <option value="Central, Mati"
                                            <?= ($applicant['barangay'] == 'Central, Mati') ? 'selected' : ''; ?>>
                                            Central, Mati</option>
                                        <option value="Culian, Mati"
                                            <?= ($applicant['barangay'] == 'Culian, Mati') ? 'selected' : ''; ?>>Culian,
                                            Mati</option>
                                        <option value="Dahican, Mati"
                                            <?= ($applicant['barangay'] == 'Dahican, Mati') ? 'selected' : ''; ?>>
                                            Dahican, Mati</option>
                                        <option value="Danao, Mati"
                                            <?= ($applicant['barangay'] == 'Danao, Mati') ? 'selected' : ''; ?>>Danao,
                                            Mati</option>
                                        <option value="Dawan, Mati"
                                            <?= ($applicant['barangay'] == 'Dawan, Mati') ? 'selected' : ''; ?>>Dawan,
                                            Mati</option>
                                        <option value="Don Enrique Lopez, Mati"
                                            <?= ($applicant['barangay'] == 'Don Enrique Lopez, Mati') ? 'selected' : ''; ?>>
                                            Don Enrique Lopez, Mati</option>
                                        <option value="Don Martin Marundan, Mati"
                                            <?= ($applicant['barangay'] == 'Don Martin Marundan, Mati') ? 'selected' : ''; ?>>
                                            Don Martin Marundan, Mati</option>
                                        <option value="Don Salvador Lopez, Sr., Mati"
                                            <?= ($applicant['barangay'] == 'Don Salvador Lopez, Sr., Mati') ? 'selected' : ''; ?>>
                                            Don Salvador Lopez, Sr., Mati</option>
                                        <option value="Langka, Mati"
                                            <?= ($applicant['barangay'] == 'Langka, Mati') ? 'selected' : ''; ?>>Langka,
                                            Mati</option>
                                        <option value="Lawigan, Mati"
                                            <?= ($applicant['barangay'] == 'Lawigan, Mati') ? 'selected' : ''; ?>>
                                            Lawigan, Mati</option>
                                        <option value="Libudon, Mati"
                                            <?= ($applicant['barangay'] == 'Libudon, Mati') ? 'selected' : ''; ?>>
                                            Libudon, Mati</option>
                                        <option value="Luban, Mati"
                                            <?= ($applicant['barangay'] == 'Luban, Mati') ? 'selected' : ''; ?>>Luban,
                                            Mati</option>
                                        <option value="Macambol, Mati"
                                            <?= ($applicant['barangay'] == 'Macambol, Mati') ? 'selected' : ''; ?>>
                                            Macambol, Mati</option>
                                        <option value="Mamali, Mati"
                                            <?= ($applicant['barangay'] == 'Mamali, Mati') ? 'selected' : ''; ?>>Mamali,
                                            Mati</option>
                                        <option value="Matiao, Mati"
                                            <?= ($applicant['barangay'] == 'Matiao, Mati') ? 'selected' : ''; ?>>Matiao,
                                            Mati</option>
                                        <option value="Mayo, Mati"
                                            <?= ($applicant['barangay'] == 'Mayo, Mati') ? 'selected' : ''; ?>>Mayo,
                                            Mati</option>
                                        <option value="Sainz, Mati"
                                            <?= ($applicant['barangay'] == 'Sainz, Mati') ? 'selected' : ''; ?>>Sainz,
                                            Mati</option>
                                        <option value="Sanghay, Mati"
                                            <?= ($applicant['barangay'] == 'Sanghay, Mati') ? 'selected' : ''; ?>>
                                            Sanghay, Mati</option>
                                        <option value="Tagabakid, Mati"
                                            <?= ($applicant['barangay'] == 'Tagabakid, Mati') ? 'selected' : ''; ?>>
                                            Tagabakid, Mati</option>
                                        <option value="Tagbinonga, Mati"
                                            <?= ($applicant['barangay'] == 'Tagbinonga, Mati') ? 'selected' : ''; ?>>
                                            Tagbinonga, Mati</option>
                                        <option value="Taguibo, Mati"
                                            <?= ($applicant['barangay'] == 'Taguibo, Mati') ? 'selected' : ''; ?>>
                                            Taguibo, Mati</option>
                                        <option value="Tamisan, Mati"
                                            <?= ($applicant['barangay'] == 'Tamisan, Mati') ? 'selected' : ''; ?>>
                                            Tamisan, Mati</option>

                                        <!-- Baganga -->
                                        <option value="Baculin, Baganga"
                                            <?= ($applicant['barangay'] == 'Baculin, Baganga') ? 'selected' : ''; ?>>
                                            Baculin, Baganga</option>
                                        <option value="Banao, Baganga"
                                            <?= ($applicant['barangay'] == 'Banao, Baganga') ? 'selected' : ''; ?>>
                                            Banao, Baganga</option>
                                        <option value="Batawan, Baganga"
                                            <?= ($applicant['barangay'] == 'Batawan, Baganga') ? 'selected' : ''; ?>>
                                            Batawan, Baganga</option>
                                        <option value="Batiano, Baganga"
                                            <?= ($applicant['barangay'] == 'Batiano, Baganga') ? 'selected' : ''; ?>>
                                            Batiano, Baganga</option>
                                        <option value="Binondo, Baganga"
                                            <?= ($applicant['barangay'] == 'Binondo, Baganga') ? 'selected' : ''; ?>>
                                            Binondo, Baganga</option>
                                        <option value="Bobonao, Baganga"
                                            <?= ($applicant['barangay'] == 'Bobonao, Baganga') ? 'selected' : ''; ?>>
                                            Bobonao, Baganga</option>
                                        <option value="Campawan, Baganga"
                                            <?= ($applicant['barangay'] == 'Campawan, Baganga') ? 'selected' : ''; ?>>
                                            Campawan, Baganga</option>
                                        <option value="Central, Baganga"
                                            <?= ($applicant['barangay'] == 'Central, Baganga') ? 'selected' : ''; ?>>
                                            Central, Baganga</option>
                                        <option value="Dapnan, Baganga"
                                            <?= ($applicant['barangay'] == 'Dapnan, Baganga') ? 'selected' : ''; ?>>
                                            Dapnan, Baganga</option>
                                        <option value="Kinablangan, Baganga"
                                            <?= ($applicant['barangay'] == 'Kinablangan, Baganga') ? 'selected' : ''; ?>>
                                            Kinablangan, Baganga</option>

                                        <!-- Baganga -->
                                        <option value="Baculin, Baganga"
                                            <?= ($applicant['barangay'] == 'Baculin, Baganga') ? 'selected' : ''; ?>>
                                            Baculin, Baganga</option>
                                        <option value="Banao, Baganga"
                                            <?= ($applicant['barangay'] == 'Banao, Baganga') ? 'selected' : ''; ?>>
                                            Banao, Baganga</option>
                                        <option value="Batawan, Baganga"
                                            <?= ($applicant['barangay'] == 'Batawan, Baganga') ? 'selected' : ''; ?>>
                                            Batawan, Baganga</option>
                                        <option value="Batiano, Baganga"
                                            <?= ($applicant['barangay'] == 'Batiano, Baganga') ? 'selected' : ''; ?>>
                                            Batiano, Baganga</option>
                                        <option value="Binondo, Baganga"
                                            <?= ($applicant['barangay'] == 'Binondo, Baganga') ? 'selected' : ''; ?>>
                                            Binondo, Baganga</option>
                                        <option value="Bobonao, Baganga"
                                            <?= ($applicant['barangay'] == 'Bobonao, Baganga') ? 'selected' : ''; ?>>
                                            Bobonao, Baganga</option>
                                        <option value="Campawan, Baganga"
                                            <?= ($applicant['barangay'] == 'Campawan, Baganga') ? 'selected' : ''; ?>>
                                            Campawan, Baganga</option>
                                        <option value="Central, Baganga"
                                            <?= ($applicant['barangay'] == 'Central, Baganga') ? 'selected' : ''; ?>>
                                            Central, Baganga</option>
                                        <option value="Dapnan, Baganga"
                                            <?= ($applicant['barangay'] == 'Dapnan, Baganga') ? 'selected' : ''; ?>>
                                            Dapnan, Baganga</option>
                                        <option value="Kinablangan, Baganga"
                                            <?= ($applicant['barangay'] == 'Kinablangan, Baganga') ? 'selected' : ''; ?>>
                                            Kinablangan, Baganga</option>

                                        <!-- Banaybanay -->
                                        <option value="Cabangcalan, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Cabangcalan, Banaybanay') ? 'selected' : ''; ?>>
                                            Cabangcalan, Banaybanay</option>
                                        <option value="Caganganan, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Caganganan, Banaybanay') ? 'selected' : ''; ?>>
                                            Caganganan, Banaybanay</option>
                                        <option value="Calubihan, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Calubihan, Banaybanay') ? 'selected' : ''; ?>>
                                            Calubihan, Banaybanay</option>
                                        <option value="Causwagan, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Causwagan, Banaybanay') ? 'selected' : ''; ?>>
                                            Causwagan, Banaybanay</option>
                                        <option value="Mahayag, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Mahayag, Banaybanay') ? 'selected' : ''; ?>>
                                            Mahayag, Banaybanay</option>
                                        <option value="Maputi, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Maputi, Banaybanay') ? 'selected' : ''; ?>>
                                            Maputi, Banaybanay</option>
                                        <option value="Mogbongcogon, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Mogbongcogon, Banaybanay') ? 'selected' : ''; ?>>
                                            Mogbongcogon, Banaybanay</option>
                                        <option value="Panikian, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Panikian, Banaybanay') ? 'selected' : ''; ?>>
                                            Panikian, Banaybanay</option>
                                        <option value="Pintatagan, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Pintatagan, Banaybanay') ? 'selected' : ''; ?>>
                                            Pintatagan, Banaybanay</option>
                                        <option value="Piso Proper, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Piso Proper, Banaybanay') ? 'selected' : ''; ?>>
                                            Piso Proper, Banaybanay</option>
                                        <option value="Poblacion, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Poblacion, Banaybanay') ? 'selected' : ''; ?>>
                                            Poblacion, Banaybanay</option>
                                        <option value="Punta Linao, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Punta Linao, Banaybanay') ? 'selected' : ''; ?>>
                                            Punta Linao, Banaybanay</option>
                                        <option value="Rang-ay, Banaybanay"
                                            <?= ($applicant['barangay'] == 'Rang-ay, Banaybanay') ? 'selected' : ''; ?>>
                                            Rang-ay, Banaybanay</option>
                                        <option value="San Vicente, Banaybanay"
                                            <?= ($applicant['barangay'] == 'San Vicente, Banaybanay') ? 'selected' : ''; ?>>
                                            San Vicente, Banaybanay</option>

                                        <!-- Boston -->
                                        <option value="Caatihan, Boston"
                                            <?= ($applicant['barangay'] == 'Caatihan, Boston') ? 'selected' : ''; ?>>
                                            Caatihan, Boston</option>
                                        <option value="Cabasagan, Boston"
                                            <?= ($applicant['barangay'] == 'Cabasagan, Boston') ? 'selected' : ''; ?>>
                                            Cabasagan, Boston</option>
                                        <option value="Carmen, Boston"
                                            <?= ($applicant['barangay'] == 'Carmen, Boston') ? 'selected' : ''; ?>>
                                            Carmen, Boston</option>
                                        <option value="Cawayanan, Boston"
                                            <?= ($applicant['barangay'] == 'Cawayanan, Boston') ? 'selected' : ''; ?>>
                                            Cawayanan, Boston</option>
                                        <option value="Poblacion, Boston"
                                            <?= ($applicant['barangay'] == 'Poblacion, Boston') ? 'selected' : ''; ?>>
                                            Poblacion, Boston</option>
                                        <option value="San Jose, Boston"
                                            <?= ($applicant['barangay'] == 'San Jose, Boston') ? 'selected' : ''; ?>>San
                                            Jose, Boston</option>
                                        <option value="Sibajay, Boston"
                                            <?= ($applicant['barangay'] == 'Sibajay, Boston') ? 'selected' : ''; ?>>
                                            Sibajay, Boston</option>
                                        <option value="Simulao, Boston"
                                            <?= ($applicant['barangay'] == 'Simulao, Boston') ? 'selected' : ''; ?>>
                                            Simulao, Boston</option>

                                        <!-- Caraga -->
                                        <option value="Alvar, Caraga"
                                            <?= ($applicant['barangay'] == 'Alvar, Caraga') ? 'selected' : ''; ?>>Alvar,
                                            Caraga</option>
                                        <option value="Caningag, Caraga"
                                            <?= ($applicant['barangay'] == 'Caningag, Caraga') ? 'selected' : ''; ?>>
                                            Caningag, Caraga</option>
                                        <option value="Don Leon Balante, Caraga"
                                            <?= ($applicant['barangay'] == 'Don Leon Balante, Caraga') ? 'selected' : ''; ?>>
                                            Don Leon Balante, Caraga</option>
                                        <option value="Lamiawan, Caraga"
                                            <?= ($applicant['barangay'] == 'Lamiawan, Caraga') ? 'selected' : ''; ?>>
                                            Lamiawan, Caraga</option>
                                        <option value="Manorigao, Caraga"
                                            <?= ($applicant['barangay'] == 'Manorigao, Caraga') ? 'selected' : ''; ?>>
                                            Manorigao, Caraga</option>
                                        <option value="Mercedes, Caraga"
                                            <?= ($applicant['barangay'] == 'Mercedes, Caraga') ? 'selected' : ''; ?>>
                                            Mercedes, Caraga</option>
                                        <option value="Palma Gil, Caraga"
                                            <?= ($applicant['barangay'] == 'Palma Gil, Caraga') ? 'selected' : ''; ?>>
                                            Palma Gil, Caraga</option>
                                        <option value="Pichon, Caraga"
                                            <?= ($applicant['barangay'] == 'Pichon, Caraga') ? 'selected' : ''; ?>>
                                            Pichon, Caraga</option>
                                        <option value="Poblacion, Caraga"
                                            <?= ($applicant['barangay'] == 'Poblacion, Caraga') ? 'selected' : ''; ?>>
                                            Poblacion, Caraga</option>
                                        <option value="San Antonio, Caraga"
                                            <?= ($applicant['barangay'] == 'San Antonio, Caraga') ? 'selected' : ''; ?>>
                                            San Antonio, Caraga</option>
                                        <option value="San Jose, Caraga"
                                            <?= ($applicant['barangay'] == 'San Jose, Caraga') ? 'selected' : ''; ?>>San
                                            Jose, Caraga</option>
                                        <option value="San Luis, Caraga"
                                            <?= ($applicant['barangay'] == 'San Luis, Caraga') ? 'selected' : ''; ?>>San
                                            Luis, Caraga</option>
                                        <option value="San Miguel, Caraga"
                                            <?= ($applicant['barangay'] == 'San Miguel, Caraga') ? 'selected' : ''; ?>>
                                            San Miguel, Caraga</option>
                                        <option value="San Pedro, Caraga"
                                            <?= ($applicant['barangay'] == 'San Pedro, Caraga') ? 'selected' : ''; ?>>
                                            San Pedro, Caraga</option>
                                        <option value="Santa Fe, Caraga"
                                            <?= ($applicant['barangay'] == 'Santa Fe, Caraga') ? 'selected' : ''; ?>>
                                            Santa Fe, Caraga</option>
                                        <option value="Santiago, Caraga"
                                            <?= ($applicant['barangay'] == 'Santiago, Caraga') ? 'selected' : ''; ?>>
                                            Santiago, Caraga</option>
                                        <option value="Sobrecarey, Caraga"
                                            <?= ($applicant['barangay'] == 'Sobrecarey, Caraga') ? 'selected' : ''; ?>>
                                            Sobrecarey, Caraga</option>

                                        <!-- Cateel -->
                                        <option value="Abijod, Cateel"
                                            <?= ($applicant['barangay'] == 'Abijod, Cateel') ? 'selected' : ''; ?>>
                                            Abijod, Cateel</option>
                                        <option value="Alegria, Cateel"
                                            <?= ($applicant['barangay'] == 'Alegria, Cateel') ? 'selected' : ''; ?>>
                                            Alegria, Cateel</option>
                                        <option value="Aliwagwag, Cateel"
                                            <?= ($applicant['barangay'] == 'Aliwagwag, Cateel') ? 'selected' : ''; ?>>
                                            Aliwagwag, Cateel</option>
                                        <option value="Aragon, Cateel"
                                            <?= ($applicant['barangay'] == 'Aragon, Cateel') ? 'selected' : ''; ?>>
                                            Aragon, Cateel</option>
                                        <option value="Baybay, Cateel"
                                            <?= ($applicant['barangay'] == 'Baybay, Cateel') ? 'selected' : ''; ?>>
                                            Baybay, Cateel</option>
                                        <option value="Maglahus, Cateel"
                                            <?= ($applicant['barangay'] == 'Maglahus, Cateel') ? 'selected' : ''; ?>>
                                            Maglahus, Cateel</option>
                                        <option value="Mainit, Cateel"
                                            <?= ($applicant['barangay'] == 'Mainit, Cateel') ? 'selected' : ''; ?>>
                                            Mainit, Cateel</option>
                                        <option value="Malibago, Cateel"
                                            <?= ($applicant['barangay'] == 'Malibago, Cateel') ? 'selected' : ''; ?>>
                                            Malibago, Cateel</option>
                                        <option value="Poblacion, Cateel"
                                            <?= ($applicant['barangay'] == 'Poblacion, Cateel') ? 'selected' : ''; ?>>
                                            Poblacion, Cateel</option>
                                        <option value="San Alfonso, Cateel"
                                            <?= ($applicant['barangay'] == 'San Alfonso, Cateel') ? 'selected' : ''; ?>>
                                            San Alfonso, Cateel</option>
                                        <option value="San Antonio, Cateel"
                                            <?= ($applicant['barangay'] == 'San Antonio, Cateel') ? 'selected' : ''; ?>>
                                            San Antonio, Cateel</option>
                                        <option value="San Miguel, Cateel"
                                            <?= ($applicant['barangay'] == 'San Miguel, Cateel') ? 'selected' : ''; ?>>
                                            San Miguel, Cateel</option>
                                        <option value="San Rafael, Cateel"
                                            <?= ($applicant['barangay'] == 'San Rafael, Cateel') ? 'selected' : ''; ?>>
                                            San Rafael, Cateel</option>
                                        <option value="San Vicente, Cateel"
                                            <?= ($applicant['barangay'] == 'San Vicente, Cateel') ? 'selected' : ''; ?>>
                                            San Vicente, Cateel</option>
                                        <option value="Santa Filomena, Cateel"
                                            <?= ($applicant['barangay'] == 'Santa Filomena, Cateel') ? 'selected' : ''; ?>>
                                            Santa Filomena, Cateel</option>
                                        <option value="Taytayan, Cateel"
                                            <?= ($applicant['barangay'] == 'Taytayan, Cateel') ? 'selected' : ''; ?>>
                                            Taytayan, Cateel</option>

                                        <!-- Tarragona -->
                                        <option value="Cabagayan, Tarragona"
                                            <?= ($applicant['barangay'] == 'Cabagayan, Tarragona') ? 'selected' : ''; ?>>
                                            Cabagayan, Tarragona</option>
                                        <option value="Central, Tarragona"
                                            <?= ($applicant['barangay'] == 'Central, Tarragona') ? 'selected' : ''; ?>>
                                            Central, Tarragona</option>
                                        <option value="Dadong, Tarragona"
                                            <?= ($applicant['barangay'] == 'Dadong, Tarragona') ? 'selected' : ''; ?>>
                                            Dadong, Tarragona</option>
                                        <option value="Jovellar, Tarragona"
                                            <?= ($applicant['barangay'] == 'Jovellar, Tarragona') ? 'selected' : ''; ?>>
                                            Jovellar, Tarragona</option>
                                        <option value="Limot, Tarragona"
                                            <?= ($applicant['barangay'] == 'Limot, Tarragona') ? 'selected' : ''; ?>>
                                            Limot, Tarragona</option>
                                        <option value="Lucatan, Tarragona"
                                            <?= ($applicant['barangay'] == 'Lucatan, Tarragona') ? 'selected' : ''; ?>>
                                            Lucatan, Tarragona</option>
                                        <option value="Maganda, Tarragona"
                                            <?= ($applicant['barangay'] == 'Maganda, Tarragona') ? 'selected' : ''; ?>>
                                            Maganda, Tarragona</option>
                                        <option value="Ompao, Tarragona"
                                            <?= ($applicant['barangay'] == 'Ompao, Tarragona') ? 'selected' : ''; ?>>
                                            Ompao, Tarragona</option>
                                        <option value="Tamoaong, Tarragona"
                                            <?= ($applicant['barangay'] == 'Tamoaong, Tarragona') ? 'selected' : ''; ?>>
                                            Tamoaong, Tarragona</option>
                                        <option value="Tubaon, Tarragona"
                                            <?= ($applicant['barangay'] == 'Tubaon, Tarragona') ? 'selected' : ''; ?>>
                                            Tubaon, Tarragona</option>

                                    </select>

                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="municipality">Municipality</label>
                                    <select class="form-control" name="municipality" id="municipality" required>
                                        <option value="Banaybanay"
                                            <?= $applicant['municipality'] == 'Banaybanay' ? 'selected' : '' ?>>
                                            Banaybanay</option>
                                        <option value="Lupon"
                                            <?= $applicant['municipality'] == 'Lupon' ? 'selected' : '' ?>>Lupon
                                        </option>
                                        <option value="San Isidro"
                                            <?= $applicant['municipality'] == 'San Isidro' ? 'selected' : '' ?>>San
                                            Isidro</option>
                                        <option value="Mati City"
                                            <?= $applicant['municipality'] == 'Mati City' ? 'selected' : '' ?>>Mati City
                                        </option>
                                        <option value="Tarragona"
                                            <?= $applicant['municipality'] == 'Tarragona' ? 'selected' : '' ?>>Tarragona
                                        </option>
                                        <option value="Manay"
                                            <?= $applicant['municipality'] == 'Manay' ? 'selected' : '' ?>>Manay
                                        </option>
                                        <option value="Baganga"
                                            <?= $applicant['municipality'] == 'Baganga' ? 'selected' : '' ?>>Baganga
                                        </option>
                                        <option value="Caraga"
                                            <?= $applicant['municipality'] == 'Caraga' ? 'selected' : '' ?>>Caraga
                                        </option>
                                        <option value="Cateel"
                                            <?= $applicant['municipality'] == 'Cateel' ? 'selected' : '' ?>>Cateel
                                        </option>
                                        <option value="Boston"
                                            <?= $applicant['municipality'] == 'Boston' ? 'selected' : '' ?>>Boston
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="province">Province</label>
                                    <select class="form-control" name="province" id="province" required>
                                        <option value="" disabled>Select Province</option>
                                        <option value="Davao Oriental"
                                            <?= $applicant['province'] == 'Davao Oriental' ? 'selected' : '' ?>>Davao
                                            Oriental</option>
                                        <option value="Outside Davao Oriental"
                                            <?= $applicant['province'] == 'Outside Davao Oriental' ? 'selected' : '' ?>>
                                            Outside Davao Oriental</option>
                                    </select>
                                </div>
                            </div>




                            <!-- Parental Information -->
                            <h5 class="mt-4">Parent's Details</h5>
                            <div class="row">
                                <?php
                                $parents = [
                                    "n_mother" => "Mother's Name", "c_mother" => "Mother's Contact", "m_occupation" => "Mother's Occupation",
                                    "m_address" => "Mother's Address", "n_father" => "Father's Name", "c_father" => "Father's Contact",
                                    "f_occupation" => "Father's Occupation", "f_address" => "Father's Address"
                                ];

                                foreach ($parents as $name => $label) {
                                    echo "<div class='col-md-4 mb-3'>
                                            <label for='{$name}'>{$label}</label>
                                            <input type='text' class='form-control' name='{$name}' id='{$name}' value='{$applicant[$name]}' required>
                                        </div>";
                                }
                                ?>
                            </div>

                            <!-- Other Details Section -->
                            <h5 class="mt-4">Other Details</h5>
                            <div class="row">
                                <?php
                                $other = [
                                    "living_status" => "Living Status",
                                    "siblings" => "No. of Siblings",
                                    "birth_order" => "Birth Order",
                                    "monthly_income" => "Monthly Income",
                                    "indigenous" => "Indigenous?",
                                    "basic_sector" => "Basic Sector?"
                                ];

                                foreach ($other as $name => $label) {
                                    // Check if the field should be number input for fields like siblings, birth_order, or monthly_income
                                    $input_type = in_array($name, ['siblings', 'birth_order', 'monthly_income']) ? 'number' : 'text';

                                    echo "<div class='col-md-4 mb-3'>
                                            <label for='{$name}'>{$label}</label>
                                            <input type='{$input_type}' class='form-control' name='{$name}' id='{$name}' value='{$applicant[$name]}' required>
                                        </div>";
                                }
                                ?>

                                <div class="col-md-4 mb-3">
                                    <label for="date_applied">Date Applied</label>
                                    <input type="date" class="form-control" name="date_applied" id="date_applied"
                                        value="<?= $applicant['date_applied']; ?>" required>
                                </div>
                            </div>

                            <!-- Educational Preferences Section -->
                            <h5 class="mt-4">Educational Preferences</h5>
                            <div class="row">
                                <?php
                                // Define fields for education preferences
                                $education = [
                                    "first_option" => "1st Option", "second_option" => "2nd Option", "third_option" => "3rd Option",
                                    "campus" => "Campus"
                                ];

                                // Updated course options
                                $course_options = [
                                    'Bachelor of Science in Business Administration (Non-Board Course)',
                                    'Bachelor of Science in Criminology (Board Course)',
                                    'Bachelor of Science in Hospitality Management (Non-Board Course)',
                                    'Bachelor of Science in Agriculture Major in Crop Science (Board Course)',
                                    'Bachelor of Science in Agriculture Major in Animal Science (Board Course)',
                                    'Bachelor of Science in Agribusiness Management',
                                    'Bachelor of Science in Development Communication (Non-Board Course)',
                                    'Bachelor in Environmental Science (Non-Board Course)',
                                    'Bachelor of Science in Biology (Non-Board Course)',
                                    'Bachelor in Early Childhood Education (Board Course)',
                                    'Bachelor of Elementary Education (Board Course)',
                                    'Bachelor of Special Needs Education (Board Course)',
                                    'Bachelor of Secondary Education Major in English (Board Course)',
                                    'Bachelor of Secondary Education Major in Science (Board Course)',
                                    'Bachelor of Secondary Education Major in Filipino (Board Course)',
                                    'Bachelor of Secondary Education Major in Mathematics (Board Course)',
                                    'Bachelor of Physical Education (Board Course)',
                                    'Bachelor in Technology and Livelihood Education-Home Economics (Board Course)',
                                    'Bachelor in Technology and Livelihood Education-Industrial Arts (Board Course)',
                                    'Bachelor in Industrial Technology Management (Non-Board Course)',
                                    'Bachelor of Science in Information Technology (Non-Board Course)',
                                    'Bachelor of Science in Mathematics with Research and Statistics (Non-Board Course)',
                                    'Bachelor of Science in Mathematics (Non-Board Course)',
                                    'Bachelor of Science in Civil Engineering (Board Course)',
                                    'Bachelor of Science in Nursing (Board Course)'
                                ];

                                // Campus options
                                $campus_options = ['Main Campus', 'banaybanay', 'cateel', 'baganga', 'san isidro', 'taragonna'];

                                // Loop through educational fields
                                foreach ($education as $name => $label) {
                                    echo "<div class='col-md-4 mb-3'>
                                            <label for='{$name}'>{$label}</label>
                                            <select class='form-control' name='{$name}' id='{$name}' required>
                                                <option value='' disabled selected>Select {$label}</option>";

                                    // Handle courses for first, second, and third options
                                    if ($name == "first_option" || $name == "second_option" || $name == "third_option") {
                                        foreach ($course_options as $course) {
                                            echo "<option value='{$course}'" . ($applicant[$name] == $course ? ' selected' : '') . ">{$course}</option>";
                                        }
                                    }
                                    // Handle campus options
                                    elseif ($name == "campus") {
                                        foreach ($campus_options as $campus) {
                                            echo "<option value='{$campus}'" . ($applicant[$name] == $campus ? ' selected' : '') . ">{$campus}</option>";
                                        }
                                    }
                                    echo "</select>
                                        </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="update_applicant" class="btn btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once "../../includes/footer.php"; ?>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#editApplicantModal').modal('show');
    });
    </script>
</body>

</html>