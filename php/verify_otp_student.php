<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Verify OTP & Reset Password</title>
  <link rel="shortcut icon" href="../img/favicon.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Google Fonts: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: url('../img/logo3.jpg') no-repeat center 25% fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .blur-overlay {
      backdrop-filter: blur(2px);
      background-color: white;
      /* changed from semi-transparent dark to solid white */
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
      color: #222;
      /* dark text for contrast */
      text-align: center;
    }


    h3 {
      margin-bottom: 30px;
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
      font-weight: 600;
    }

    label {
      font-weight: 500;
    }

    /* Container for input + eye icon */
    .input-group {
      position: relative;
    }

    .form-control {
      border-radius: 30px !important;
      padding: 12px 20px;
      padding-right: 45px;
      /* space for eye icon */
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      user-select: none;
      color: black;
      /* Make sure icon color is black */
      opacity: 1;
      /* Fully visible */
      transition: opacity 0.2s ease-in-out;
      width: 20px;
      height: 20px;
    }

    .toggle-password:hover {
      opacity: 0.8;
      /* Slight fade on hover, or set 1 for no fade */
    }


    .btn-primary {
      background-color: #002B5B;
      border: 1px solid white;
      border-radius: 50px;
      padding: 14px 28px;
      font-size: 1.1em;
      width: 100%;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease-in-out;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .btn-primary:hover {
      background-color: #018ABE;
      transform: translateY(-2px);
      box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>
  <div class="blur-overlay">
    <div class="modal-header flex-column align-items-center"
      style="outline: none !important; box-shadow: none !important; border:none;">
      <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px; margin-top:-40px;">
      <h4 class="modal-title" id="loginModalLabel" style="font-weight: 700; margin-top: -50px;">Welcome, Student!
      </h4>
      <h4 class="modal-title" id="loginModalLabel" style="font-size: 10px;">please login your account</h4>

      <!-- <button type="button" class="close position-absolute" style="right: 10px; top: 10px;" data-dismiss="modal"
        aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button> -->
    </div>
    <form action="reset_password_student.php" method="POST" novalidate>
      <div class="form-group text-left">
        <label for="otp">OTP Code</label>
        <input type="text" name="otp" id="otp" class="form-control" required maxlength="6" />
      </div>

      <div class="form-group text-left">
        <label for="new_password">New Password</label>
        <div class="input-group">
          <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6" />
          <span class="toggle-password" toggle="#new_password" title="Show/Hide Password">
            <svg xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 16 16" width="20" height="20">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
              <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
            </svg>
          </span>

        </div>
      </div>

      <div class="form-group text-left">
        <label for="confirm_password">Confirm New Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required
            minlength="6" />
          <span class="toggle-password" toggle="#new_password" title="Show/Hide Password">
            <svg xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 16 16" width="20" height="20">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
              <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" />
            </svg>
          </span>

        </div>
      </div>

      <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function () {
      $(".toggle-password").click(function () {
        const input = $($(this).attr("toggle"));
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          // Change icon to eye-slash
          $(this).html(`
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" width="20" height="20">
              <path d="M13.359 11.238l1.388 1.388a.5.5 0 0 1-.708.708l-1.388-1.388a8.06 8.06 0 0 1-4.651 1.323C3 13.269 0 8 0 8a13.134 13.134 0 0 1 3.112-3.93L1.72 2.68a.5.5 0 1 1 .708-.708l11 11a.5.5 0 0 1-.708.708l-1.36-1.36zM5.754 6.185a3 3 0 0 0 4.256 4.256L5.754 6.185z"/>
              <path d="M10.793 12.458a8.06 8.06 0 0 0 4.607-3.94s-3-5.5-8-5.5a7.49 7.49 0 0 0-3.093.612l.987.987a3 3 0 0 1 3.986 3.986l.113.113z"/>
            </svg>
          `);
        } else {
          input.attr("type", "password");
          // Change icon back to eye
          $(this).html(`
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" width="20" height="20">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
              <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
            </svg>
          `);
        }
      });
    });


    $('form').submit(function (e) {
      e.preventDefault(); // Prevent normal form submission

      const formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: 'reset_password_student.php',
        data: formData,
        success: function (response) {
          try {
            const res = JSON.parse(response);

            Swal.fire({
              icon: res.status,
              title: res.title,
              text: res.message,
            }).then(() => {
              if (res.status === 'success') {
                window.location.href = 'landing_page.php'; // or login page
              }
            });
          } catch (e) {
            console.error("Invalid JSON response", response);
            Swal.fire("Error", "Unexpected error occurred. Try again.", "error");
          }
        },
        error: function () {
          Swal.fire("Error", "Server error. Try again.", "error");
        }
      });
    });

  </script>
</body>

</html>