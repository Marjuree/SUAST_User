<style>
h5 {
    font-size: 20px;
    font-weight: bold;
}
</style>
<!-- add_modal.php -->
<div class="modal fade" id="addApplicantModal" tabindex="-1" role="dialog" aria-labelledby="addApplicantLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="insert_applicant.php" method="POST" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addApplicantLabel">Add New Applicant</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">
                        <!-- Personal Information -->
                        <h5 class="mt-3">Personal Information</h5>
                        <div class="row">
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
                        <!-- Profile Image and Document Upload -->
                        <div class="row">
                            <!-- Profile Image -->
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*"
                                    required>
                                <small class="form-text text-muted">Accepted formats: .jpg, .jpeg, .png, .gif</small>
                            </div>

                            <!-- Add Document Upload Input -->
                            <div class="col-md-6 mb-3">
                                <label for="document" class="form-label">Upload Document</label>
                                <input type="file" class="form-control" name="document" id="document"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                <small class="form-text text-muted">Accepted formats: .pdf, .doc, .docx, .jpg, .jpeg,
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
                            "n_mother" => "Mother's Name", "c_mother" => "Mother's Contact", "m_occupation" => "Mother's Occupation",
                            "m_address" => "Mother's Address", "n_father" => "Father's Name", "c_father" => "Father's Contact",
                            "f_occupation" => "Father's Occupation", "f_address" => "Father's Address"
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
                                <input type="date" class="form-control" name="date_applied" id="date_applied" required>
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
                                'Bachelor of Science in Nursing (Board Course)'
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

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="add_applicant" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>