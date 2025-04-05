<?php
// Include the database connection from config.php
require_once "../../configuration/config.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data from POST request
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $bdate = $_POST['bdate']; // Ensure the date is in the format YYYY-MM-DD
    $age = $_POST['age'];
    $religion = $_POST['religion'];
    $nationality = $_POST['nationality'];
    $civilstatus = $_POST['civilstatus'];
    $ethnicity = $_POST['ethnicity'];
    $contact = $_POST['contact'];
    $purok = $_POST['purok'];
    $barangay = $_POST['barangay'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $first_option = $_POST['first_option'];
    $second_option = $_POST['second_option'];
    $third_option = $_POST['third_option'];
    $campus = $_POST['campus'];
    $gender = $_POST['gender'];
    $n_mother = $_POST['n_mother'];
    $n_father = $_POST['n_father'];
    $c_mother = $_POST['c_mother'];
    $c_father = $_POST['c_father'];
    $m_occupation = $_POST['m_occupation'];
    $f_occupation = $_POST['f_occupation'];
    $m_address = $_POST['m_address'];
    $f_address = $_POST['f_address'];
    $living_status = $_POST['living_status'];

    // Validate numeric fields: siblings, birth_order, monthly_income
    $siblings = isset($_POST['siblings']) && is_numeric($_POST['siblings']) ? $_POST['siblings'] : 0;
    $birth_order = isset($_POST['birth_order']) && is_numeric($_POST['birth_order']) ? $_POST['birth_order'] : 0;
    $monthly_income = isset($_POST['monthly_income']) && is_numeric($_POST['monthly_income']) ? $_POST['monthly_income'] : 0;
    
    $indigenous = $_POST['indigenous'];
    $basic_sector = $_POST['basic_sector'];
    $date_applied = $_POST['date_applied'];

    // File upload handling (profile image)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = time() . '.' . $image_ext; // Unique file name
        $image_path = 'uploads/' . $new_image_name;

        // Ensure the uploads directory exists and has correct permissions
        if (!file_exists('uploads/')) {
            mkdir('uploads/', 0777, true); // Creates the directory if it doesn't exist
        }

        move_uploaded_file($image_tmp_name, $image_path);
    } else {
        $new_image_name = ''; // If no image is uploaded, set it to an empty string
    }

    // Insert query (removed password from fields)
    $sql = "INSERT INTO tbl_applicants 
        (lname, fname, mname, bdate, age, religion, nationality, civilstatus, ethnicity, contact, 
        purok, barangay, municipality, province, first_option, second_option, third_option, campus, 
        gender, n_mother, n_father, c_mother, c_father, m_occupation, f_occupation, m_address, f_address, 
        living_status, siblings, birth_order, monthly_income, indigenous, basic_sector, image, date_applied) 
        VALUES 
        ('$lname', '$fname', '$mname', '$bdate', '$age', '$religion', '$nationality', '$civilstatus', '$ethnicity', 
        '$contact', '$purok', '$barangay', '$municipality', '$province', '$first_option', '$second_option', 
        '$third_option', '$campus', '$gender', '$n_mother', '$n_father', '$c_mother', '$c_father', '$m_occupation', 
        '$f_occupation', '$m_address', '$f_address', '$living_status', '$siblings', '$birth_order', '$monthly_income', 
        '$indigenous', '$basic_sector', '$new_image_name', '$date_applied')";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        // Success - show pop-up message using JavaScript and redirect to applicants.php
        echo "<script>
                alert('Applicant added successfully!');
                window.location.href = 'applicant.php';
              </script>";
    } else {
        // Error - show error message and redirect to applicants.php
        echo "<script>
                alert('Error: " . mysqli_error($con) . "');
                window.location.href = 'applicant.php';
              </script>";
    }
}

// Close the database connection
mysqli_close($con);
?>
