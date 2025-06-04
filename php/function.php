<?php
if (isset($_POST['btn_add'])) {
    $txt_lname = $_POST['txt_lname'];
    $txt_fname = $_POST['txt_fname'];
    $txt_mname = $_POST['txt_mname'];
    $txt_bdate = $_POST['txt_bdate'];
    
    //$txt_age = $_POST['txt_age'];
    $dateOfBirth = $txt_bdate;
    $today = date("Y-m-d");
    $diff = date_diff(date_create($dateOfBirth), date_create($today));
    $txt_age = $diff->format('%y');

    $ddl_school = $_POST['ddl_school'];
    $txt_religion = $_POST['txt_religion'];
    $txt_nationality = $_POST['txt_nationality'];
    $txt_civilstatus = $_POST['txt_civilstatus'];
    $ddl_ethnicity = $_POST['ddl_ethnicity'];
    $txt_contact = $_POST['txt_contact'];
    $txt_purok = $_POST['txt_purok'];
    $ddl_barangay = $_POST['ddl_barangay'];
    $ddl_municipality = $_POST['ddl_municipality'];
    $ddl_province = $_POST['ddl_province'];
    $ddl_first_option = $_POST['ddl_first_option'];
    $ddl_second_option = $_POST['ddl_second_option'];
    $ddl_third_option = $_POST['ddl_third_option'];
    $ddl_campus = $_POST['ddl_campus'];
    $ddl_gender = $_POST['ddl_gender'];
    $txt_n_mother = $_POST['txt_n_mother'];
    $txt_n_father = $_POST['txt_n_father'];
    $txt_c_mother = $_POST['txt_c_mother'];
    $txt_c_father = $_POST['txt_c_father'];
    $txt_m_occupation = $_POST['txt_m_occupation'];
    $txt_f_occupation = $_POST['txt_f_occupation'];
    $txt_m_address = $_POST['txt_m_address'];
    $txt_f_address = $_POST['txt_f_address'];
    $ddl_living_status = $_POST['ddl_living_status'];
    $txt_siblings = $_POST['txt_siblings'];
    $txt_birth_order = $_POST['txt_birth_order'];
    $ddl_monthly_income = $_POST['ddl_monthly_income'];
    $ddl_indigenous = $_POST['ddl_indigenous'];
    $ddl_basic_sector = $_POST['ddl_basic_sector'];
    $txt_username = $_POST['txt_username'];
    $txt_password = $_POST['txt_password'];

    $name = basename($_FILES['txt_image']['name']);
    $temp = $_FILES['txt_image']['tmp_name'];
    $imagetype = $_FILES['txt_image']['type'];
    $size = $_FILES['txt_image']['size'];



    $milliseconds = round(microtime(true) * 1000);
    $image = $milliseconds.'_'.$name;

    if(isset($_SESSION['role'])){
        $action = 'Added Resident named '.$txt_lname.', '.$txt_fname.' '.$txt_mname;
        $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('".$_SESSION['role']."', NOW(), '".$action."')");
    }

    $su = mysqli_query($con,"SELECT * from tblapplicants where username = '".$txt_username."' ");
    $ct = mysqli_num_rows($su);
    
    if($ct == 0){

    if($name != ""){
    if(($imagetype=="image/jpeg" || $imagetype=="image/png" || $imagetype=="image/bmp") && $size<=2048000){
    if(move_uploaded_file($temp, 'image/'.$image))
                    {
                    $txt_image = $image;
                    $query = mysqli_query($con,"INSERT INTO tblapplicants
                    (lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity,
                    contact, purok, barangay, municipality, province, first_option, second_option,
                    third_option, campus, gender, n_mother, n_father, c_mother, c_father, m_occupation,
                    f_occupation, m_address, f_address, living_status, siblings, birth_order,
                    monthly_income, indigenous, basic_sector, image, username, password) 

                   
                values ('$txt_lname', '$txt_fname', '$txt_mname', '$txt_bdate', '$txt_age', 
                        '$txt_religion', '$txt_nationality', '$txt_civilstatus', '$ddl_ethnicity', 
                        '$txt_contact', '$txt_purok', '$ddl_barangay', '$ddl_municipality', 
                        '$ddl_province', '$ddl_first_option', '$ddl_second_option', 
                        '$ddl_third_option', '$ddl_campus', '$ddl_gender', '$txt_n_mother', 
                        '$txt_n_father', '$txt_c_mother', '$txt_c_father', '$txt_m_occupation', 
                        '$txt_f_occupation', '$txt_m_address', '$txt_f_address', 
                        '$ddl_living_status', '$txt_siblings', '$txt_birth_order', 
                        '$ddl_monthly_income', '$ddl_indigenous', '$ddl_basic_sector', 
                        '$txt_image', '$txt_username', '$txt_password' )") 
                        or die('Error: ' . mysqli_error($con));
                    }
                    }
                else
                    {
                    $_SESSION['filesize'] = 1; 
                    header ("location: ".$_SERVER['REQUEST_URI']);
                    }
                    }
                else
                    {
                    $txt_image = 'default.png';
             
                    $query = mysqli_query($con,"INSERT INTO tblapplicants
                    (lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity,
                    contact, purok, barangay, municipality, province, first_option, second_option,
                    third_option, campus, gender, n_mother, n_father, c_mother, c_father, m_occupation,
                    f_occupation, m_address, f_address, living_status, siblings, birth_order,
                    monthly_income, indigenous, basic_sector, image, username, password)

                                   
                values ('$txt_lname', '$txt_fname', '$txt_mname', '$txt_bdate', '$txt_age', 
                        '$txt_religion', '$txt_nationality', '$txt_civilstatus', '$ddl_ethnicity', 
                        '$txt_contact', '$txt_purok', '$ddl_barangay', '$ddl_municipality', 
                        '$ddl_province', '$ddl_first_option', '$ddl_second_option', 
                        '$ddl_third_option', '$ddl_campus', '$ddl_gender', '$txt_n_mother', 
                        '$txt_n_father', '$txt_c_mother', '$txt_c_father', '$txt_m_occupation', 
                        '$txt_f_occupation', '$txt_m_address', '$txt_f_address', 
                        '$ddl_living_status', '$txt_siblings', '$txt_birth_order', 
                        '$ddl_monthly_income', '$ddl_indigenous', '$ddl_basic_sector', 
                        '$txt_image', '$txt_username', '$txt_password' )") 
                         or die('Error: ' . mysqli_error($con));
             
                }

        
            if($query == true)
                {
                $_SESSION['added'] = 1;
                header ("location: ".$_SERVER['REQUEST_URI']);
                }
                }
            else{
                $_SESSION['duplicateuser'] = 1;
                header ("location: ".$_SERVER['REQUEST_URI']);
                }    

                }


                if(isset($_POST['btn_save']))
                {
                $txt_id = $_POST['hidden_id'];
                $txt_edit_lname = $_POST['txt_edit_lname'];
                $txt_edit_fname = $_POST['txt_edit_fname'];
                $txt_edit_mname = $_POST['txt_edit_mname'];
                $txt_edit_bdate = $_POST['txt_edit_bdate'];
                $txt_edit_age = $_POST['txt_edit_age'];

                $dateOfBirth = $txt_edit_bdate;
                $today = date("Y-m-d");
                $diff = date_diff(date_create($dateOfBirth), date_create($today));
                $txt_edit_age = $diff->format('%y');

                $txt_edit_religion = $_POST['txt_edit_religion'];
                $txt_edit_nationality = $_POST['txt_edit_nationality'];
                $txt_edit_civilstatus = $_POST['txt_edit_civilstatus'];
                $ddl_edit_ethnicity = $_POST['ddl_edit_ethnicity'];
                $txt_edit_contact = $_POST['txt_edit_contact'];
                $txt_edit_purok = $_POST['txt_edit_purok'];
                $ddl_edit_barangay = $_POST['ddl_edit_barangay'];
                $ddl_edit_municipality = $_POST['ddl_edit_municipality'];
                $ddl_edit_province = $_POST['ddl_edit_province'];
                $ddl_edit_first_option = $_POST['ddl_edit_first_option'];
                $ddl_edit_second_option = $_POST['ddl_edit_second_option'];
                $ddl_edit_third_option = $_POST['ddl_edit_third_option'];
                $ddl_edit_campus = $_POST['ddl_edit_campus'];
                $ddl_edit_gender = $_POST['ddl_edit_gender'];
                $txt_edit_n_mother = $_POST['txt_edit_n_mother'];
                $txt_edit_n_father = $_POST['txt_edit_n_father'];
                $txt_edit_c_mother = $_POST['txt_edit_c_mother'];
                $txt_edit_c_father = $_POST['txt_edit_c_father'];
                $txt_edit_m_occupation = $_POST['txt_edit_m_occupation'];
                $txt_edit_f_occupation = $_POST['txt_edit_f_occupation'];
                $txt_edit_m_address = $_POST['txt_edit_m_address'];
                $txt_edit_f_address = $_POST['txt_edit_f_address'];
                $ddl_edit_living_status = $_POST['ddl_edit_living_status'];
                $txt_edit_siblings = $_POST['txt_edit_siblings'];
                $txt_edit_birth_order = $_POST['txt_edit_birth_order'];
                $ddl_edit_monthly_income = $_POST['ddl_edit_monthly_income'];
                $ddl_edit_indigenous = $_POST['ddl_edit_indigenous'];
                $ddl_edit_basic_sector = $_POST['ddl_edit_basic_sector'];
                $txt_edit_username = $_POST['txt_edit_username'];
                $txt_edit_password = $_POST['txt_edit_password'];
                
                $name = basename($_FILES['txt_edit_image']['name']);
                $temp = $_FILES['txt_edit_image']['tmp_name'];
                $imagetype = $_FILES['txt_edit_image']['type'];
                $size = $_FILES['txt_edit_image']['size'];

                $milliseconds = round(microtime(true) * 1000);
                $image = $milliseconds.'_'.$name;

                if(isset($_SESSION['role'])){
                    $action = 'Update Resident named '.$txt_edit_lname.', '.$txt_edit_fname.' '.$txt_edit_mname;
                    $iquery = mysqli_query($con,"INSERT INTO tbllogs (user,logdate,action) values ('".$_SESSION['role']."', NOW(), '".$action."')");
                }

                $su = mysqli_query($con,"SELECT * from tblapplicants where username = '".$txt_edit_username."' and id not in (".$txt_id.") ");
                $ct = mysqli_num_rows($su);

                if($ct == 0){

                if($name != ""){
                if(($imagetype=="image/jpeg" || $imagetype=="image/png" || $imagetype=="image/bmp") && $size<=2048000){
                if(move_uploaded_file($temp, 'image/'.$image))
                {

                    $txt_edit_image = $image;
                    $update_query = mysqli_query($con,"UPDATE tblapplicants set 
                    lname = '".$txt_edit_lname."',
                    fname = '".$txt_edit_fname."',
                    mname = '".$txt_edit_mname."',
                    bdate = '".$txt_edit_bdate."',
                    age = '".$txt_edit_age."',
                    ethnicity = '".$ddl_edit_ethnicity."',
                    contact = '".$txt_edit_contact."',
                    purok = '".$txt_edit_purok."',
                    barangay = '".$ddl_edit_barangay."',
                    municipality = '".$ddl_edit_municipality."',
                    province = '".$ddl_edit_province."',
                    first_option = '".$ddl_edit_first_option."',
                    second_option = '".$ddl_edit_second_option."',
                    third_option = '".$ddl_edit_third_option."',
                    campus = '".$ddl_edit_campus."',
                    gender = '".$ddl_edit_gender."',
                    n_mother = '".$txt_edit_n_mother."',
                    n_father = '".$txt_edit_n_father."',
                    c_mother = '".$txt_edit_c_mother."',
                    c_father = '".$txt_edit_c_father."',
                    m_occupation = '".$txt_edit_m_occupation."',
                    f_occupation = '".$txt_edit_f_occupation."',
                    m_address = '".$txt_edit_m_address."',
                    f_address = '".$txt_edit_f_address."',
                    living_status = '".$ddl_edit_living_status."',
                    siblings = '".$txt_edit_siblings."',
                    birth_order = '".$txt_edit_birth_order."',
                    monthly_income = '".$ddl_edit_monthly_income."',
                    indigenous = '".$ddl_edit_indigenous."',
                    basic_sector = '".$ddl_edit_basic_sector."',
                    religion = '".$txt_edit_religion."',
                    nationality = '".$txt_edit_nationality."',
                    civilstatus = '".$txt_edit_civilstatus."'
                    image = '".$txt_edit_image."',
                    username = '".$txt_edit_username."',
                    password = '".$txt_edit_password."'
                    WHERE id = '".$txt_id."'") 
                    or die('Error: ' . mysqli_error($con));
                     }
                            }
                        else{
                            $_SESSION['filesize'] = 1; 
                            header ("location: ".$_SERVER['REQUEST_URI']);
                            }
                            }
                        else{
                            $chk_image = mysqli_query($con,"SELECT * from tblapplicants where id = '".$_POST['hidden_id']."' ");
                            $rowimg = mysqli_fetch_array($chk_image);

                            $txt_edit_image = $rowimg['image'];
                            $update_query = mysqli_query($con,"UPDATE tblapplicants set 
                            lname = '".$txt_edit_lname."',
                            fname = '".$txt_edit_fname."',
                            mname = '".$txt_edit_mname."',
                            bdate = '".$txt_edit_bdate."',
                            age = '".$txt_edit_age."',
                            ethnicity = '".$ddl_edit_ethnicity."',
                            contact = '".$txt_edit_contact."',
                            purok = '".$txt_edit_purok."',
                            barangay = '".$ddl_edit_barangay."',
                            municipality = '".$ddl_edit_municipality."',
                            province = '".$ddl_edit_province."',
                            first_option = '".$ddl_edit_first_option."',
                            second_option = '".$ddl_edit_second_option."',
                            third_option = '".$ddl_edit_third_option."',
                            campus = '".$ddl_edit_campus."',
                            gender = '".$ddl_edit_gender."',
                            n_mother = '".$txt_edit_n_mother."',
                            n_father = '".$txt_edit_n_father."',
                            c_mother = '".$txt_edit_c_mother."',
                            c_father = '".$txt_edit_c_father."',
                            m_occupation = '".$txt_edit_m_occupation."',
                            f_occupation = '".$txt_edit_f_occupation."',
                            m_address = '".$txt_edit_m_address."',
                            f_address = '".$txt_edit_f_address."',
                            living_status = '".$ddl_edit_living_status."',
                            siblings = '".$txt_edit_siblings."',
                            birth_order = '".$txt_edit_birth_order."',
                            monthly_income = '".$ddl_edit_monthly_income."',
                            indigenous = '".$ddl_edit_indigenous."',
                            basic_sector = '".$ddl_edit_basic_sector."',
                            religion = '".$txt_edit_religion."',
                            nationality = '".$txt_edit_nationality."',
                            civilstatus = '".$txt_edit_civilstatus."',
                            image = '".$txt_edit_image."',
                            username = '".$txt_edit_username."',
                            password = '".$txt_edit_password."'
                            WHERE id = '".$txt_id."'") 
                            or die('Error: ' . mysqli_error($con));

                                      
                            }
                                
                        if($update_query == true){
                                    $_SESSION['edited'] = 1;
                                    header("location: ".$_SERVER['REQUEST_URI']);
                                }

                            }
                            else{
                                $_SESSION['duplicateuser'] = 1;
                                header ("location: ".$_SERVER['REQUEST_URI']);
                            }  

                            
                        }

                        if(isset($_POST['btn_delete']))
                        {
                            if(isset($_POST['chk_delete']))
                            {
                                foreach($_POST['chk_delete'] as $value)
                                {
                                    $delete_query = mysqli_query($con,"DELETE from tblapplicants where id = '$value' ") or die('Error: ' . mysqli_error($con));
                                            
                                    if($delete_query == true)
                                    {
                                        $_SESSION['delete'] = 1;
                                        header("location: ".$_SERVER['REQUEST_URI']);
                                    }
                                }
                            }
                        }


                        ?>