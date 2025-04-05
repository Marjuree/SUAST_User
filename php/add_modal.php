<!-- ========================= MODAL ======================= -->
<div id="addCourseModal" class="modal fade">
            <form class="form-horizontal" method="post" enctype="multipart/form-data">
              <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">APPLICANT PERSONAL INFORMATION</h4>
                        Please fill-out all the requested information below (Palihog sulati ang tanang gipangayo nga impormasyon sa ubos)
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="container-fluid">
                                <div class="col-md-6 col-sm-12">

                                    <div class="form-group">
                                        <label class="control-label" >Name:</label><br>
                                        <div class="col-sm-4">
                                            <input name="txt_lname" class="form-control input-sm" type="text" placeholder="Lastname"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <input name="txt_fname" class="form-control input-sm col-sm-4" type="text" placeholder="Firstname"/>
                                        </div>
                                        <div class="col-sm-4">
                                            <input name="txt_mname" class="form-control input-sm col-sm-4" type="text" placeholder="Middlename"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Birthdate:</label>
                                        <input name="txt_bdate" class="form-control input-sm input-size" type="date" placeholder="Birthdate"/>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Age:</label>
                                        <input name="txt_age" class="form-control input-sm input-size" type="number" placeholder="Age"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">School(Eskwelahan Kung Asa ka Na Enroll/Nag Eskwela:</label>
                                        <select name="ddl_school" class="form-control input-sm input-size">
                                        <option>Select School</option>
                                        <option>Badas National High School</option>
                                        <option>Mati Doctors Academy, Inc.</option>
                                        <option>Mati Polytechnic College</option>
                                        <option>Sunbeam Christian School of Mati, Inc.</option>
                                        </select>
                                    </div>



                                    <div class="form-group">
                                        <label class="control-label">Religion:</label>
                                        <input name="txt_religion" class="form-control input-sm input-size" type="text" placeholder="Religion"/>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label">Nationality:</label>
                                        <input name="txt_nationality" class="form-control input-sm" type="text" placeholder="Nationality"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Civil Status:</label>
                                        <input name="txt_civilstatus" class="form-control input-sm input-size" type="text" placeholder="Civil Status"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">What is your ethnicity?:</label>
                                        <select name="ddl_ethnicity" class="form-control input-sm input-size">
                                        <option>Select Ethnicity</option>
                                        <option>Abaknon</option>
                                        <option>Agta</option>
                                        <option>Agutaynon</option>
                                        <option>Aklanon</option>
                                        <option>Alangan</option>
                                        <option>Alta</option>
                                        <option>Amerasian</option>
                                        <option>Ati</option>
                                        <option>Atta</option>
                                        <option>Ayta (Aeta)</option>
                                        <option>B'laan</option>
                                        <option>Badjao</option>
                                        <option>Bagobo</option>
                                        <option>Balangao</option>
                                        <option>Balangingi</option>
                                        <option>Bantoanon</option>
                                        <option>Banwaon</option>
                                        <option>Batak</option>
                                        <option>Bicolano</option>
                                        <option>Bisaya/Binisaya</option>
                                        <option>Boholano</option>
                                        <option>Bolinao</option>
                                        <option>Bontoc</option>
                                        <option>Buhid</option>
                                        <option>Butuanon</option>
                                        <option>Caluyanon</option>
                                        <option>Capiznon</option>
                                        <option>Cebuano</option>
                                        <option>Cotabateño</option>
                                        <option>Cuyonon</option>
                                        <option>Chinese Filipinos</option>
                                        <option>Davaoeño</option>
                                        <option>Ermiteño</option>
                                        <option>Ga'dang</option>
                                        <option>Gaddang</option>
                                        <option>Hanunoo</option>
                                        <option>Higaonon</option>
                                        <option>Ibaloi</option>
                                        <option>Ibanag</option>
                                        <option>Ifugao</option>
                                        <option>Ikalahan</option>
                                        <option>Illanun</option>
                                        <option>Ilocano</option>
                                        <option>Ilonggo</option>
                                        <option>Ilongot</option>
                                        <option>Indian Filipinos</option>
                                        <option>Inonhan</option>
                                        <option>Iraya</option>
                                        <option>Isinai</option>
                                        <option>Isneg</option>
                                        <option>Itneg</option>
                                        <option>Ivatan</option>
                                        <option>Japanese Filipinos</option>
                                        <option>Kagayanen</option>
                                        <option>Kalagan</option>
                                        <option>Kalinga</option>
                                        <option>Kamayo</option>
                                        <option>Kankanaey</option>
                                        <option>Kapampangan</option>
                                        <option>Karao</option>
                                        <option>Kasiguranin</option>
                                        <option>Kinaray-a</option>
                                        <option>Kinamiguin</option>
                                        <option>Kolibugan</option>
                                        <option>Korean Filipinos</option>
                                        <option>Magahat</option>
                                        <option>Maguindanaon</option>
                                        <option>Malaweg</option>
                                        <option>Mamanwa</option>
                                        <option>Mandaya</option>
                                        <option>Mansaka</option>
                                        <option>Manguwangan</option>
                                        <option>Manobo</option>
                                        <option>Matigsalug</option>
                                        <option>Maranao</option>
                                        <option>Masbateño</option>
                                        <option>Molbog</option>
                                        <option>Negrense</option>
                                        <option>Palawano</option>
                                        <option>Pangasinense</option>
                                        <option>Paranan</option>
                                        <option>Porohanon</option>
                                        <option>Ratagnon</option>
                                        <option>Romblomanon</option>
                                        <option>Sama</option>
                                        <option>Sambal</option>
                                        <option>Sangil</option>
                                        <option>Spanish Filipinos</option>
                                        <option>Subanun</option>
                                        <option>Sulod</option>
                                        <option>Surigaonon</option>
                                        <option>T'boli</option>
                                        <option>Tadyawan</option>
                                        <option>Tagabawa</option>
                                        <option>Tagakaulo</option>
                                        <option>Tagalog</option>
                                        <option>Tagbanwa</option>
                                        <option>Talaandig</option>
                                        <option>Tasaday</option>
                                        <option>Tau't Bato</option>
                                        <option>Tausug</option>
                                        <option>Tawbuid</option>
                                        <option>Ternateño</option>
                                        <option>Tiruray</option>
                                        <option>Waray</option>
                                        <option>Yakan</option>
                                        <option>Yogad</option>
                                        <option>Zamboangueño</option>
                                        </select>
                                    </div>

                                     <div class="form-group">
                                        <label class="control-label">Active Contact Number (Kanang Ma Tawagan):</label>
                                        <input name="txt_contact" class="form-control input-sm input-size" type="number" min="1" placeholder="Active Contact Number  #"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Purok #:</label>
                                        <input name="txt_purok" class="form-control input-sm" type="text"  placeholder="Purok#"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Barangay:</label>
                                        <select name="ddl_barangay" class="form-control input-sm input-size">
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

                                    <div class="form-group">
                                        <label class="control-label">Municipality:</label>
                                        <select name="ddl_municipality" class="form-control input-sm input-size">
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

                                    <div class="form-group">
                                        <label class="control-label">Province:</label>
                                        <select name="ddl_province" class="form-control input-sm input-size">
                                        <option value="" disabled selected>Select Province</option>
                                        <option>Davao Oriental</option>
                                        <option>Outside Davao Oriental</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">First Option of Course You are Interested to Enroll</label>
                                        <select name="ddl_first_option" class="form-control input-sm">
                                        <option value="" disabled selected>Select Course</option>
                                        <option>Bachelor of Science in Business Administration (Non-Board Course)</option>
                                        <option>Bachelor of Science in Criminology (Board Course)</option>
                                        <option>Bachelor of Science in Hospitality Management (Non-Board Course)</option>
                                        <option>Bachelor of Science in Agriculture Major in Crop Science (Board Course)</option>
                                        <option>Bachelor of Science in Agriculture Major in Animal Science (Board Course)</option>
                                        <option>Bachelor of Science in Agribusiness Management</option>
                                        <option>Bachelor of Science in Development Communication (Non-Board Course)</option>
                                        <option>Bachelor in Environmental Science (Non-Board Course)</option>
                                        <option>Bachelor of Science in Biology (Non-Board Course)</option>
                                        <option>Bachelor in Early Childhood Education (Board Course)</option>
                                        <option>Bachelor of Elementary Education (Board Course)</option>
                                        <option>Bachelor of Special Needs Education (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in English (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Science (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Filipino (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Mathematics (Board Course)</option>
                                        <option>Bachelor of Physical Education (Board Course)</option>
                                        <option>Bachelor in Technology and Livelihood Education-Home Economics (Board Course)</option>
                                        <option>Bachelor in Technology and Livelihood Education-Industrial Arts (Board Course)</option>
                                        <option>Bachelor in Industrial Technology Management (Non-Board Course)</option>
                                        <option>Bachelor of Science in Information Technology (Non-Board Course)</option>
                                        <option>Bachelor of Science in Mathematics with Research and Statistics (Non-Board Course)</option>
                                        <option>Bachelor of Science in Mathematics (Non-Board Course)</option>
                                        <option>Bachelor of Science in Civil Engineering (Board Course)</option>
                                        <option>Bachelor of Science in Nursing (Board Course)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Second Option of Course You are Interested to Enroll</label>
                                        <select name="ddl_second_option" class="form-control input-sm">
                                        <option value="" disabled selected>Select Second Option of Course</option>
                                        <option>Bachelor of Science in Business Administration (Non-Board Course)</option>
                                        <option>Bachelor of Science in Criminology (Board Course)</option>
                                        <option>Bachelor of Science in Hospitality Management (Non-Board Course)</option>
                                        <option>Bachelor of Science in Agriculture Major in Crop Science (Board Course)</option>
                                        <option>Bachelor of Science in Agriculture Major in Animal Science (Board Course)</option>
                                        <option>Bachelor of Science in Agribusiness Management</option>
                                        <option>Bachelor of Science in Development Communication (Non-Board Course)</option>
                                        <option>Bachelor in Environmental Science (Non-Board Course)</option>
                                        <option>Bachelor of Science in Biology (Non-Board Course)</option>
                                        <option>Bachelor in Early Childhood Education (Board Course)</option>
                                        <option>Bachelor of Elementary Education (Board Course)</option>
                                        <option>Bachelor of Special Needs Education (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in English (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Science (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Filipino (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Mathematics (Board Course)</option>
                                        <option>Bachelor of Physical Education (Board Course)</option>
                                        <option>Bachelor in Technology and Livelihood Education-Home Economics (Board Course)</option>
                                        <option>Bachelor in Technology and Livelihood Education-Industrial Arts (Board Course)</option>
                                        <option>Bachelor in Industrial Technology Management (Non-Board Course)</option>
                                        <option>Bachelor of Science in Information Technology (Non-Board Course)</option>
                                        <option>Bachelor of Science in Mathematics with Research and Statistics (Non-Board Course)</option>
                                        <option>Bachelor of Science in Mathematics (Non-Board Course)</option>
                                        <option>Bachelor of Science in Civil Engineering (Board Course)</option>
                                        <option>Bachelor of Science in Nursing (Board Course)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Third Option of Course You are Interested to Enroll</label>
                                        <select name="ddl_third_option" class="form-control input-sm">
                                        <option value="" disabled selected>Third Option of Course</option>
                                        <option>Bachelor of Science in Business Administration (Non-Board Course)</option>
                                        <option>Bachelor of Science in Criminology (Board Course)</option>
                                        <option>Bachelor of Science in Hospitality Management (Non-Board Course)</option>
                                        <option>Bachelor of Science in Agriculture Major in Crop Science (Board Course)</option>
                                        <option>Bachelor of Science in Agriculture Major in Animal Science (Board Course)</option>
                                        <option>Bachelor of Science in Agribusiness Management</option>
                                        <option>Bachelor of Science in Development Communication (Non-Board Course)</option>
                                        <option>Bachelor in Environmental Science (Non-Board Course)</option>
                                        <option>Bachelor of Science in Biology (Non-Board Course)</option>
                                        <option>Bachelor in Early Childhood Education (Board Course)</option>
                                        <option>Bachelor of Elementary Education (Board Course)</option>
                                        <option>Bachelor of Special Needs Education (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in English (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Science (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Filipino (Board Course)</option>
                                        <option>Bachelor of Secondary Education Major in Mathematics (Board Course)</option>
                                        <option>Bachelor of Physical Education (Board Course)</option>
                                        <option>Bachelor in Technology and Livelihood Education-Home Economics (Board Course)</option>
                                        <option>Bachelor in Technology and Livelihood Education-Industrial Arts (Board Course)</option>
                                        <option>Bachelor in Industrial Technology Management (Non-Board Course)</option>
                                        <option>Bachelor of Science in Information Technology (Non-Board Course)</option>
                                        <option>Bachelor of Science in Mathematics with Research and Statistics (Non-Board Course)</option>
                                        <option>Bachelor of Science in Mathematics (Non-Board Course)</option>
                                        <option>Bachelor of Science in Civil Engineering (Board Course)</option>
                                        <option>Bachelor of Science in Nursing (Board Course)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Preferred DOrSU Campus to Enroll</label>
                                        <select name="ddl_campus" class="form-control input-sm input-size">
                                        <option value="" disabled selected>Select Campus</option>
                                        <option>Banaybanay Extension Campus</option>
                                        <option>San Isidro Extension Campus</option>
                                        <option>Main Campus (Mati City)</option>
                                        <option>Tarragona External Classes</option>
                                        <option>Baganga External Classes</option>
                                        <option>Cateel Extension Campus</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="col-md-6 col-sm-12">
                                    
                                    <div class="form-group">     
                                        <label class="control-label">Sex:</label>
                                        <select name="ddl_gender" class="form-control input-sm">
                                            <option selected="" disabled="">-Select Gender-</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Name of Mother (Last Name, First Name, Middle Name):</label>
                                        <input name="txt_n_mother" class="form-control input-sm" type="text" placeholder="Name of Mother"/>
                                    </div> 

                                    <div class="form-group">
                                        <label class="control-label">Name of Father (Last Name, First Name, Middle Name):</label>
                                        <input name="txt_n_father" class="form-control input-sm" type="text" placeholder="Name of Father"/>
                                    </div>  
      

                                    <div class="form-group">
                                        <label class="control-label">Mother's Active Contact Number:</label>
                                        <input name="txt_c_mother" class="form-control input-sm" type="number" placeholder="Mother Contact Number"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Father's Active Contact Number:</label>
                                        <input name="txt_c_father" class="form-control input-sm" type="number" placeholder="Father Contact Number"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Mother's Occupation (trabaho saimong Mama):</label>
                                        <input name="txt_m_occupation" class="form-control input-sm" type="text" placeholder="Mother Occupation"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Father's Occupation  (trabaho saimong Papa) :</label>
                                        <input name="txt_f_occupation" class="form-control input-sm" type="text" placeholder="Father Occupation"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Mother's Home Address (Purok, Barangay, Municipality):</label>
                                        <input name="txt_m_address" class="form-control input-sm" type="text" placeholder="Mother's Home Address"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"> Father's Home Address (Purok, Barangay, Municipality):</label>
                                        <input name="txt_f_address" class="form-control input-sm" type="text" placeholder="Fathers's Home Address"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Nagpuyo ba ka kauban saimong mga ginikanan?:</label>
                                        <select name="ddl_living_status" class="form-control input-sm">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="Live with Parents/Relatives">Live with Parents/Relatives</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">How many siblings do you have? (Pila mo kabuok mag igsuon):</label>
                                        <input name="txt_siblings" class="form-control input-sm" type="text"  placeholder="siblings"/>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label">What is your birth order? (Ika pila ka sa mag igsuon?):</label>
                                        <input name="txt_birth_order" class="form-control input-sm" type="number" max="999999999999" min="1" placeholder="eg. 010000000001"/>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label">HProjected Family Monthly Income:</label>
                                        <select name="ddl_monthly_income" class="form-control input-sm" placeholder="Monthly Income"/>
                                        <option value="" disabled selected>Select Monthly Income</option>
                                        <option>Below Php 10,000</option>
                                        <option>Php 10,000-20,000</option>
                                        <option>Php 20,000-30,000</option>
                                        <option>Php 30,000-40,000</option>
                                        <option>Php 40,000-50,000</option>
                                        <option>Php 50,000-60,000</option>
                                        <option>Php 60,000-70,000</option>
                                        <option>Php 70,000-80,000</option>
                                        <option>Php 80,000-90,000</option>
                                        <option>Php 90,000-100,000</option>
                                        <option>Above Php 100,000</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Answered YES, please specify which Indigenous you belong</label>
                                        <select name="ddl_indigenous" class="form-control input-sm input-size">
                                        <option value="" disabled selected>Select Indigenous Group</option>
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
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Basic sector you/your family belong:</label>
                                        <select name="ddl_basic_sector" class="form-control input-sm input-size">
                                        <option value="" disabled selected>Select Basic Sector</option>
                                        <option>Farmer-Peasant</option>
                                        <option>Artisinal Fisherfolk</option>
                                        <option>Indigenous Peoples (IP) and Cultural Communities</option>
                                        <option>Women</option>
                                        <option>Differently-abled persons</option>
                                        <option>Senior Citizen</option>
                                        <option>Victims of Calamities and Disasters</option>
                                        <option>Urban Poor</option>
                                        <option>Self Employed/Unpaid Family Workers</option>
                                        <option>Workers In the Government/Private Sector</option>
                                        <option>Migrant Workers</option>
                                        <option>Other</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Active E-mail Address:</label>
                                        <input name="txt_username" id="username" class="form-control input-sm input-size" type="text" placeholder="Username"/>
                                        <label id="user_msg" style="color:#CC0000;" ></label>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Password:</label>
                                        <input name="txt_password" class="form-control input-sm" type="password" placeholder="Password"/>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Image:</label>
                                        <input name="txt_image" class="form-control input-sm" type="file" />
                                    </div>



                                </div>

                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default btn-sm" data-dismiss="modal" value="Cancel"/>
                        <input type="submit" class="btn btn-primary btn-sm" name="btn_add" id="btn_add" value="Submit"/>
                    </div>
                </div>
              </div>
              </form>
            </div>

<script type="text/javascript">
    $(document).ready(function() {
 
        var timeOut = null; // this used for hold few seconds to made ajax request
 
        var loading_html = '<img src="../img/ajax-loader.gif" style="height: 20px; width: 20px;"/>'; // just an loading image or we can put any texts here
 
        //when button is clicked
        $('#username').keyup(function(e){
 
            // when press the following key we need not to make any ajax request, you can customize it with your own way
            switch(e.keyCode)
            {
                //case 8:   //backspace
                case 9:     //tab
                case 13:    //enter
                case 16:    //shift
                case 17:    //ctrl
                case 18:    //alt
                case 19:    //pause/break
                case 20:    //caps lock
                case 27:    //escape
                case 33:    //page up
                case 34:    //page down
                case 35:    //end
                case 36:    //home
                case 37:    //left arrow
                case 38:    //up arrow
                case 39:    //right arrow
                case 40:    //down arrow
                case 45:    //insert
                //case 46:  //delete
                    return;
            }
            if (timeOut != null)
                clearTimeout(timeOut);
            timeOut = setTimeout(is_available, 500);  // delay delay ajax request for 1000 milliseconds
            $('#user_msg').html(loading_html); // adding the loading text or image
        });
  });
function is_available(){
    //get the username
    var username = $('#username').val();
 
    //make the ajax request to check is username available or not
    $.post("check_username.php", { username: username },
    function(result)
    {
        console.log(result);
        if(result != 0)
        {
            $('#user_msg').html('Not Available❌');
            document.getElementById("btn_add").disabled = true;
        }
        else
        {
            $('#user_msg').html('<span style="color:#006600;">Available✅</span>');
            document.getElementById("btn_add").disabled = false;
        }
    });
 
}
</script>