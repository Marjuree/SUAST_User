<?php
session_start();
session_regenerate_id(true);

require_once "../../configuration/config.php"; // Your config

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Applicant') {
    header("Location: ../../php/error.php?welcome=Please login as an applicant");
    exit();
}

$first_name = isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : "";
$middle_name = isset($_SESSION['middle_name']) ? htmlspecialchars($_SESSION['middle_name']) : "";
$last_name = isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : "";

$full_name = trim("$first_name $middle_name $last_name");
if (empty($full_name))
    $full_name = "Applicant";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Applicant | Dashboard</title>
    <link rel="shortcut icon" href="../../img/favicon.png" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <style>
        .stepwizard {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .stepwizard::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #d3d3d3;
            z-index: 0;
        }

        .stepwizard-step {
            position: relative;
            text-align: center;
            z-index: 1;
            flex: 1;
        }

        .stepwizard-step .btn-circle {
            width: 40px;
            height: 40px;
            font-size: 14px;
            border-radius: 50%;
            background-color: #d3d3d3;
            color: #fff;
            margin-bottom: 5px;
            border: none;
            cursor: pointer;
        }

        .stepwizard-step .btn-primary {
            background-color: #337ab7 !important;
        }

        .stepwizard-step p {
            margin-top: 0;
            font-size: 14px;
        }

        h5 {
            font-size: 20px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 30px !important;
        }
    </style>
</head>

<body class="skin-blue">
    <?php
    require_once('includes/header.php');
    require_once('../../includes/head_css.php');
    ?>

    <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php require_once('../../includes/sidebar.php'); ?>

        <aside class="right-side">
            <section class="content-header">
                <h1>Dashboard</h1>
                <p>Welcome, <strong><?php echo $full_name; ?></strong></p>
            </section>

            <section class="content">

                <!-- Step wizard -->
                <div class="stepwizard" role="tablist" aria-label="Application steps">
                    <div class="stepwizard-step" role="tab" aria-selected="true" tabindex="0">
                        <button type="button" class="btn btn-primary btn-circle step-btn" data-step="1"
                            aria-controls="step-content" aria-selected="true" aria-label="Step 1: Profile">1</button>
                        <p>Step 1</p>
                    </div>
                    <div class="stepwizard-step" role="tab" aria-selected="false" tabindex="-1">
                        <button type="button" class="btn btn-default btn-circle step-btn" data-step="2"
                            aria-controls="step-content" aria-label="Step 2: Documents">2</button>
                        <p>Step 2</p>
                    </div>
                    <div class="stepwizard-step" role="tab" aria-selected="false" tabindex="-1">
                        <button type="button" class="btn btn-default btn-circle step-btn" data-step="3"
                            aria-controls="step-content" aria-label="Step 3: Preview">3</button>
                        <p>Step 3</p>

                    </div>
                </div>

                <!-- Step content area -->
                <div id="step-content" tabindex="0" aria-live="polite" aria-atomic="true">

                    <!-- Step 1: Profile form embedded here by default -->
                    <form id="applicantForm" method="POST" enctype="multipart/form-data">
                        <div class="modal-content">


                            <div class="modal-body">
                                <div class="container-fluid">
                                    <!-- Personal Information -->
                                    <h5 class="mt-3">Personal Information</h5>
                                    <div class="row">
                                        <?php
                                        $personal = [
                                            "lname" => "Last Name",
                                            "fname" => "First Name",
                                            "mname" => "Middle Name",
                                            "age" => "Age",
                                            "religion" => "Religion",
                                            "nationality" => "Nationality",
                                            "civilstatus" => "Civil Status",
                                            "contact" => "Contact No."
                                        ];
                                        foreach ($personal as $name => $label) {
                                            echo "<div class='col-md-4 mb-3'>
                                            <label for='{$name}'>{$label}</label>
                                            <input type='text' class='form-control' name='{$name}' id='{$name}' required>
                                        </div>";
                                        }
                                        ?>

                                        <!-- Ethnicity Dropdown -->
                                        <div class="col-md-4 mb-3">
                                            <label for="ethnicity">Ethnicity</label>
                                            <select class="form-control" name="ethnicity" id="ethnicity" required>
                                                <option value="" disabled selected>Select Ethnicity</option>
                                                <option value="Indigenous People">Indigenous People</option>
                                                <option>Not a member</option>
                                                <option>Abelling</option>
                                                <option>Agta-Agay</option>
                                                <option>Agta</option>
                                                <option>Agutaynen</option>
                                                <option>Alangan-Mangyan</option>
                                                <option>Alta</option>
                                                <option>Applai</option>
                                                <option>Aromanen-Ne-Manuvu</option>
                                                <option>Ata-Manobo</option>
                                                <option>Ata</option>
                                                <option>Ati</option>
                                                <option>Ayangan</option>
                                                <option>Ayta-Ambala</option>
                                                <option>Ayta-Magantsi</option>
                                                <option>Ayta-Magbukun</option>
                                                <option>Ayta</option>
                                                <option>Badjao</option>
                                                <option>Bago</option>
                                                <option>Bagobo-Klata</option>
                                                <option>Bagobo-Tagakawa</option>
                                                <option>Balangao</option>
                                                <option>Bangon-Mangyan</option>
                                                <option>Bantoanon</option>
                                                <option>Banwaon</option>
                                                <option>Batak</option>
                                                <option>Blaan</option>
                                                <option>Bontok</option>
                                                <option>Bugkalot</option>
                                                <option>Buhid-Mangyan</option>
                                                <option>Bukidnon</option>
                                                <option>Cagayanen</option>
                                                <option>Calinga</option>
                                                <option>Cuyunon</option>
                                                <option>Dibabawon</option>
                                                <option>Dumagat</option>
                                                <option>Eskaya</option>
                                                <option>Gaddang</option>
                                                <option>Gubatnon-Ratagnon-Mangyan</option>
                                                <option>Hanunuo-Mangyan</option>
                                                <option>Higaonon</option>
                                                <option>Ibaloi</option>
                                                <option>Ibanag</option>
                                                <option>Ibatan</option>
                                                <option>Ilongot</option>
                                                <option>Imalawa</option>
                                                <option>Iraya-Mangyan</option>
                                                <option>Isinai</option>
                                                <option>Isnag-of-Apayao</option>
                                                <option>Isnag-of-Ilocos Norte</option>
                                                <option>Isneg-Isnag</option>
                                                <option>Itawes</option>
                                                <option>Itneg</option>
                                                <option>Ivatan</option>
                                                <option>Iwak</option>
                                                <option>Kabihug</option>
                                                <option>Kagan/Kalagan</option>
                                                <option>Kalanguya</option>
                                                <option>Kalinga</option>
                                                <option>Kamigin</option>
                                                <option>Kankaney</option>
                                                <option>Karao</option>
                                                <option>Karulano</option>
                                                <option>Kalibogan</option>
                                                <option>Malaueg</option>
                                                <option>Mamanwa</option>
                                                <option>Mandaya</option>
                                                <option>Mangguangan</option>
                                                <option>Manobo-Blit</option>
                                                <option>Manobo-Dulangan</option>
                                                <option>Manobo-Lambanguian</option>
                                                <option>Manobo</option>
                                                <option>Manobo-Tasabay</option>
                                                <option>Mansaka</option>
                                                <option>Matigsalog</option>
                                                <option>Molbog</option>
                                                <option>Obu-Manuvu</option>
                                                <option>Palawan</option>
                                                <option>Panay-Bukidnon</option>
                                                <option>Sama-Bajau</option>
                                                <option>Sama-Bangingi</option>
                                                <option>Sama</option>
                                                <option>Sibuyan-Mangyan-Tagabukid</option>
                                                <option>Tadyawan-Mangyan</option>
                                                <option>Tagakaulo</option>
                                                <option>Tagbanua-Calamian</option>
                                                <option>Tagbanua</option>
                                                <option>Tagbanoa-Tandulanen</option>
                                                <option>Talaandig</option>
                                                <option>Tau-Buid</option>
                                                <option>Tboli</option>
                                                <option>Teduray</option>
                                                <option>Tigwahanon</option>
                                                <option>Tingguian</option>
                                                <option>Tinonanen</option>
                                                <option>Tuwali</option>
                                                <option>Umayamnon</option>
                                                <option>Yakan</option>
                                                <option>Yapayao</option>
                                                <option>Yogad</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>

                                        <!-- Birthdate Field -->
                                        <div class="col-md-4 mb-3">
                                            <label for="bdate">Birthdate</label>
                                            <input type="date" class="form-control" name="bdate" id="bdate" required>
                                        </div>

                                        <!-- Gender Dropdown -->
                                        <div class="col-md-4 mb-3">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" name="gender" id="gender" required>
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Prefer not to say">Prefer not to say</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" required>
                                        </div>


                                    </div>
                                    <!-- Profile Image -->
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" name="image" id="image" accept="image/*"
                                            style="display: block; position: relative; z-index: 1; opacity: 1;">
                                        <small class="form-text text-muted">Accepted formats: .jpg, .jpeg, .png,
                                            .gif</small>
                                    </div>

                                    <!-- Document Upload -->
                                    <div class="col-12 col-md-6 mb-3">
                                        <label for="document" class="form-label">Suast Appication Form</label>
                                        <input type="file" class="form-control" name="document" id="document"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                            style="display: block; position: relative; z-index: 1; opacity: 1;">
                                        <small class="form-text text-muted">Accepted formats: .Certificate, Tor and Card,
                                            .jpeg,
                                            .png</small>
                                    </div>
                                </div>





                                <!-- Address Section -->
                                <h5 class="mt-4">Address</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="purok">Purok</label>
                                        <input type="text" class="form-control" name="purok" id="purok"
                                            placeholder="Enter Purok" required>
                                    </div>


                                    <div class="col-md-4 mb-3">
                                        <label for="barangay">Barangay</label>
                                        <select class="form-control" name="barangay" id="barangay" required>
                                            <option value="" disabled selected>Select Barangay</option>
                                            <!-- Mati -->
                                            <option>Badas, Mati</option>
                                            <option>Bobon, Mati</option>
                                            <option>Buso, Mati</option>
                                            <option>Cabuaya, Mati</option>
                                            <option>Central, Mati</option>
                                            <option>Culian, Mati</option>
                                            <option>Dahican, Mati</option>
                                            <option>Danao, Mati</option>
                                            <option>Dawan, Mati</option>
                                            <option>Don Enrique Lopez, Mati</option>
                                            <option>Don Martin Marundan, Mati</option>
                                            <option>Don Salvador Lopez, Sr., Mati</option>
                                            <option>Langka, Mati</option>
                                            <option>Lawigan, Mati</option>
                                            <option>Libudon, Mati</option>
                                            <option>Luban, Mati</option>
                                            <option>Macambol, Mati</option>
                                            <option>Mamali, Mati</option>
                                            <option>Matiao, Mati</option>
                                            <option>Mayo, Mati</option>
                                            <option>Sainz, Mati</option>
                                            <option>Sanghay, Mati</option>
                                            <option>Tagabakid, Mati</option>
                                            <option>Tagbinonga, Mati</option>
                                            <option>Taguibo, Mati</option>
                                            <option>Tamisan, Mati</option>

                                            <!-- Baganga -->
                                            <option>Baculin, Baganga</option>
                                            <option>Banao, Baganga</option>
                                            <option>Batawan, Baganga</option>
                                            <option>Batiano, Baganga</option>
                                            <option>Binondo, Baganga</option>
                                            <option>Bobonao, Baganga</option>
                                            <option>Campawan, Baganga</option>
                                            <option>Central, Baganga</option>
                                            <option>Dapnan, Baganga</option>
                                            <option>Kinablangan, Baganga</option>
                                            <option>Lambajon, Baganga</option>
                                            <option>Lucod, Baganga</option>
                                            <option>Mahanub, Baganga</option>
                                            <option>Mikit, Baganga</option>
                                            <option>Salingcomot, Baganga</option>
                                            <option>San Isidro, Baganga</option>
                                            <option>San Victor, Baganga</option>
                                            <option>Saoquegue, Baganga</option>

                                            <!-- Banaybanay -->
                                            <option>Cabangcalan, Banaybanay</option>
                                            <option>Caganganan, Banaybanay</option>
                                            <option>Calubihan, Banaybanay</option>
                                            <option>Causwagan, Banaybanay</option>
                                            <option>Mahayag, Banaybanay</option>
                                            <option>Maputi, Banaybanay</option>
                                            <option>Mogbongcogon, Banaybanay</option>
                                            <option>Panikian, Banaybanay</option>
                                            <option>Pintatagan, Banaybanay</option>
                                            <option>Piso Proper, Banaybanay</option>
                                            <option>Poblacion, Banaybanay</option>
                                            <option>Punta Linao, Banaybanay</option>
                                            <option>Rang-ay, Banaybanay</option>
                                            <option>San Vicente, Banaybanay</option>

                                            <!-- Boston -->
                                            <option>Caatihan, Boston</option>
                                            <option>Cabasagan, Boston</option>
                                            <option>Carmen, Boston</option>
                                            <option>Cawayanan, Boston</option>
                                            <option>Poblacion, Boston</option>
                                            <option>San Jose, Boston</option>
                                            <option>Sibajay, Boston</option>
                                            <option>Simulao, Boston</option>

                                            <!-- Caraga -->
                                            <option>Alvar, Caraga</option>
                                            <option>Caningag, Caraga</option>
                                            <option>Don Leon Balante, Caraga</option>
                                            <option>Lamiawan, Caraga</option>
                                            <option>Manorigao, Caraga</option>
                                            <option>Mercedes, Caraga</option>
                                            <option>Palma Gil, Caraga</option>
                                            <option>Pichon, Caraga</option>
                                            <option>Poblacion, Caraga</option>
                                            <option>San Antonio, Caraga</option>
                                            <option>San Jose, Caraga</option>
                                            <option>San Luis, Caraga</option>
                                            <option>San Miguel, Caraga</option>
                                            <option>San Pedro, Caraga</option>
                                            <option>Santa Fe, Caraga</option>
                                            <option>Santiago, Caraga</option>
                                            <option>Sobrecarey, Caraga</option>

                                            <!-- Cateel -->
                                            <option>Abijod, Cateel</option>
                                            <option>Alegria, Cateel</option>
                                            <option>Aliwagwag, Cateel</option>
                                            <option>Aragon, Cateel</option>
                                            <option>Baybay, Cateel</option>
                                            <option>Maglahus, Cateel</option>
                                            <option>Mainit, Cateel</option>
                                            <option>Malibago, Cateel</option>
                                            <option>Poblacion, Cateel</option>
                                            <option>San Alfonso, Cateel</option>
                                            <option>San Antonio, Cateel</option>
                                            <option>San Miguel, Cateel</option>
                                            <option>San Rafael, Cateel</option>
                                            <option>San Vicente, Cateel</option>
                                            <option>Santa Filomena, Cateel</option>
                                            <option>Taytayan, Cateel</option>

                                            <!-- Tarragona -->
                                            <option>Cabagayan, Tarragona</option>
                                            <option>Central, Tarragona</option>
                                            <option>Dadong, Tarragona</option>
                                            <option>Jovellar, Tarragona</option>
                                            <option>Limot, Tarragona</option>
                                            <option>Lucatan, Tarragona</option>
                                            <option>Maganda, Tarragona</option>
                                            <option>Ompao, Tarragona</option>
                                            <option>Tamoaong, Tarragona</option>
                                            <option>Tubaon, Tarragona</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="municipality">Municipality</label>
                                        <select class="form-control" name="municipality" id="municipality" required>
                                            <option value="" disabled selected>Select Municipality</option>
                                            <option>Banaybanay</option>
                                            <option>Lupon</option>
                                            <option>San Isidro</option>
                                            <option>Mati City</option>
                                            <option>Tarragona</option>
                                            <option>Manay</option>
                                            <option>Baganga</option>
                                            <option>Caraga</option>
                                            <option>Cateel</option>
                                            <option>Boston</option>
                                            <option>Outside Davao Oriental</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="province">Province</label>
                                        <select class="form-control" name="province" id="province" required>
                                            <option value="" disabled selected>Select Province</option>
                                            <option>Davao Oriental</option>
                                            <option>Outside Davao Oriental</option>
                                        </select>
                                    </div>
                                </div>





                                <!-- Parental Information -->
                                <h5 class="mt-4">Parent's Details</h5>
                                <div class="row">
                                    <?php
                                    $parents = [
                                        "n_mother" => "Mother's Name",
                                        "c_mother" => "Mother's Contact",
                                        "m_occupation" => "Mother's Occupation",
                                        "m_address" => "Mother's Address",
                                        "n_father" => "Father's Name",
                                        "c_father" => "Father's Contact",
                                        "f_occupation" => "Father's Occupation",
                                        "f_address" => "Father's Address"
                                    ];
                                    foreach ($parents as $name => $label) {
                                        echo "<div class='col-md-4 mb-3'>
                                    <label for='{$name}'>{$label}</label>
                                    <input type='text' class='form-control' name='{$name}' id='{$name}' required>
                                </div>";
                                    }
                                    ?>
                                </div>

                                <!-- Additional Details -->
                                <h5 class="mt-4">Other Details</h5>
                                <div class="row">
                                    <?php
                                    $other = [
                                        "living_status" => "Living Status",
                                        "siblings" => "No. of Siblings",
                                        "birth_order" => "Birth Order",
                                        "monthly_income" => "Monthly Income",
                                        "indigenous" => "Indigenous?",
                                        "basic_sector" => "Basic Sector?",

                                    ];

                                    foreach ($other as $name => $label) {
                                        // Check if the field should be number input for fields like siblings, birth_order, or monthly_income
                                        $input_type = in_array($name, ['siblings', 'birth_order', 'monthly_income']) ? 'number' : 'text';

                                        echo "<div class='col-md-4 mb-3'>
                                        <label for='{$name}'>{$label}</label>
                                        <input type='{$input_type}' class='form-control' name='{$name}' id='{$name}' required>
                                    </div>";
                                    }
                                    ?>
                                    <div class="col-md-4 mb-3">
                                        <label for="date_applied">Date Applied</label>
                                        <input type="date" class="form-control" name="date_applied" id="date_applied"
                                            required>
                                    </div>
                                </div>
                                <!-- Educational Preferences -->
                                <h5 class="mt-4">Educational Preferences</h5>
                                <div class="row">
                                    <?php
                                    // Updated options for courses and campus
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
                                        'Bachelor of Science in Psychology (Board Course)',
                                        'Bachelor of Science in Nursing (Board Course)',
                                        'Bachelor of Arts in Political Science'
                                    ];
                                    $campus_options = ['Main Campus', 'banaybanay', 'cateel', 'baganga', 'san isidro', 'taragonna'];

                                    // Define fields with labels and options
                                    $education = [
                                        "first_option" => ["1st Option", $course_options],
                                        "second_option" => ["2nd Option", $course_options],
                                        "third_option" => ["3rd Option", $course_options],
                                        "campus" => ["Campus", $campus_options]
                                    ];

                                    foreach ($education as $name => [$label, $options]) {
                                        echo "<div class='col-md-4 mb-3'>
                                        <label for='{$name}'>{$label}</label>
                                        <select class='form-control' name='{$name}' id='{$name}' required>
                                            <option value='' disabled selected>Select {$label}</option>";

                                        foreach ($options as $option) {
                                            echo "<option value='{$option}'>{$option}</option>";
                                        }

                                        echo "  </select>
                                        </div>";
                                    }
                                    ?>
                                </div>

                                <!-- Checkbox with link to open modal -->
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="privacy_notice" name="privacy_notice_accepted"
                                                value="1" required>
                                            I have read and agree to the <a href="#" data-toggle="modal"
                                                data-target="#privacyModal">Privacy Policy</a>.
                                        </label>
                                    </div>
                                </div>


                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="add_applicant" class="btn btn-success">Submit</button>
                        </div>
                    </form>

                </div>


            </section>
        </aside>
    </div>

    <!-- Bootstrap 3 Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="privacyModalLabel">Privacy Policy</h4>
                </div>
                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    <h5>Data Privacy Statement</h5>
                    <p>
                        We value your privacy and are committed to protecting your personal information. The data you
                        provide during registration will be used solely for the purpose of managing your student account
                        and providing services related to your academic experience.
                    </p>
                    <p>
                        Your personal information will not be shared with third parties without your explicit consent,
                        except as required by law. We implement appropriate security measures to safeguard your data
                        from unauthorized access, alteration, or disclosure.
                    </p>
                    <h6>Information Collected</h6>
                    <!-- <ul>
                        <li>Full name</li>
                        <li>Email address</li>
                        <li>School ID</li>
                        <li>Username and Password</li>
                        <li>Faculty and Year Level</li>
                    </ul> -->
                    <p>
                        By agreeing to this privacy policy, you consent to the collection and use of your data as
                        described above.
                    </p>
                    <p>
                        If you have any questions about our data privacy practices, please contact our support team.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.bundle.min.js"></script>

    <script src="../../assets/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery (required for Bootstrap JS) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Bootstrap 3 JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

</html>
