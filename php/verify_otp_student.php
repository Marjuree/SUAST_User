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
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
      color: #222;
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
      display: flex;
      align-items: center;
      position: relative;
    }

    .input-group .form-control {
      border-radius: 30px !important;
      padding: 12px 20px;
      padding-right: 40px;
      padding-left: 20px;
      flex: 1 1 auto;
      transition: padding 0.2s;
    }

    .input-group .toggle-password {
      cursor: pointer;
      color: black;
      opacity: 1;
      transition: opacity 0.2s, order 0.2s;
      width: 22px;
      height: 22px;
      display: flex;
      align-items: center;
      z-index: 2;
      background: transparent;
      border: none;
      padding: 0;
      order: 2;
      /* Default: right */
      margin-left: -35px;
      margin-right: 0;
    }

    .input-group.focused .toggle-password {
      order: 0;
      /* Move to left */
      margin-left: 0;
      margin-right: 10px;
    }

    .input-group.focused .form-control {
      padding-left: 40px;
      padding-right: 20px;
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

    #otp {
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="blur-overlay">
    <button type="button" class="btn btn-link p-0" onclick="window.location.href='landing_page.php'"
      style="position:absolute;left:30px;top:30px;z-index:10;">
      <!-- Left arrow SVG icon only -->
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#002B5B" stroke-width="2"
        viewBox="0 0 24 24">
        <path d="M15 18l-6-6 6-6" />
      </svg>
    </button>
    <div class="modal-header flex-column align-items-center"
      style="outline: none !important; box-shadow: none !important; border:none;">
      <img src="../img/uni.png" alt="Logo" style="width: 200px; height: auto; margin-bottom: 10px; margin-top:-40px;">
      <h4 class="modal-title" id="loginModalLabel" style="font-weight: 700; margin-top: -50px;">Welcome, Student!
      </h4>
      <h4 class="modal-title" id="loginModalLabel" style="font-size: 10px;">please login your account</h4>
    </div>
    <form action="reset_password_student.php" method="POST" novalidate>
      <div class="form-group text-left">
        <label for="otp">OTP Code</label>
        <input type="text" name="otp" id="otp" class="form-control" required maxlength="6" />
      </div>

      <!-- New Password Field -->
      <div class="form-group text-left">
        <label for="new_password">New Password</label>
        <div class="input-group">
          <input type="password" name="new_password" id="new_password" class="form-control" required minlength="8" />
          <span class="toggle-password" toggle="#new_password" title="Show/Hide Password">
            <!-- Modern Eye SVG icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24"
              width="22" height="22">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
              <circle cx="12" cy="12" r="3.5" />
            </svg>
          </span>
        </div>
        <small id="passwordHelp" class="form-text text-muted"></small>
      </div>

      <!-- Confirm Password Field -->
      <div class="form-group text-left">
        <label for="confirm_password">Confirm New Password</label>
        <div class="input-group">
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required
            minlength="8" />
          <span class="toggle-password" toggle="#confirm_password" title="Show/Hide Password">
            <!-- Modern Eye SVG icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24"
              width="22" height="22">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
              <circle cx="12" cy="12" r="3.5" />
            </svg>
          </span>
        </div>
        <small id="matchHelp" class="form-text text-muted"></small>
      </div>

      <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Eye SVGs for toggle
    const eyeSVG = `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
      <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
      <circle cx="12" cy="12" r="3.5"/>
    </svg>
    `;
    const eyeSlashSVG = `
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="black" stroke-width="1.5" viewBox="0 0 24 24" width="22" height="22">
      <path d="M17.94 17.94C16.12 19.25 14.13 20 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.06M22.54 6.42A21.77 21.77 0 0 1 23 12s-4 8-11 8a10.94 10.94 0 0 1-4.24-.88M1 1l22 22"/>
      <circle cx="12" cy="12" r="3.5"/>
    </svg>
    `;

    $(document).ready(function () {
      // Eye icon toggle
      $(".toggle-password").click(function () {
        const input = $($(this).attr("toggle"));
        if (input.attr("type") === "password") {
          input.attr("type", "text");
          $(this).html(eyeSlashSVG);
        } else {
          input.attr("type", "password");
          $(this).html(eyeSVG);
        }
      });

      // Move eye icon to front on focus
      $('.input-group .form-control').on('focus', function () {
        $(this).closest('.input-group').addClass('focused');
      });
      $('.input-group .form-control').on('blur', function () {
        $(this).closest('.input-group').removeClass('focused');
      });
    });

    // Password validation
    function validatePassword(password) {
      const minLength = /.{8,}/;
      const upper = /[A-Z]/;
      const special = /[!@#$%^&*(),.?":{}|<>]/;
      return minLength.test(password) && upper.test(password) && special.test(password);
    }

    $('#new_password').on('input', function () {
      const pwd = $(this).val();
      let msg = '';
      let color = 'green';
      if (!/.{8,}/.test(pwd)) {
        msg += 'At least 8 characters. ';
        color = 'red';
      }
      if (!/[A-Z]/.test(pwd)) {
        msg += 'At least one uppercase letter. ';
        color = 'red';
      }
      if (!/[!@#$%^&*(),.?":{}|<>]/.test(pwd)) {
        msg += 'At least one special character. ';
        color = 'red';
      }
      if (msg === '') {
        msg = 'Password looks good!';
        color = 'green';
      }
      $('#passwordHelp').text(msg).attr('style', 'color:' + color + ' !important');
    });

    $('#confirm_password, #new_password').on('input', function () {
      const pwd = $('#new_password').val();
      const cpwd = $('#confirm_password').val();
      if (cpwd.length === 0) {
        $('#matchHelp').text('').attr('style', '');
        return;
      }
      if (pwd === cpwd) {
        $('#matchHelp').text('Passwords match!').attr('style', 'color:green !important');
      } else {
        $('#matchHelp').text('Passwords do not match.').attr('style', 'color:red !important');
      }
    });

    $('form').submit(function (e) {
      e.preventDefault(); // Prevent normal form submission

      const pwd = $('#new_password').val();
      const cpwd = $('#confirm_password').val();

      if (!validatePassword(pwd)) {
        Swal.fire("Error", "Password must be at least 8 characters, include one uppercase letter and one special character.", "error");
        return;
      }
      if (pwd !== cpwd) {
        Swal.fire("Error", "Passwords do not match.", "error");
        return;
      }

      const formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: 'reset_password_student.php',
        data: formData,
        success: function (res) {
          Swal.fire({
            icon: res.status,
            title: res.title,
            text: res.message,
          }).then(() => {
            if (res.status === 'success') {
              window.location.href = 'landing_page.php';
            }
          });
        },
        error: function () {
          Swal.fire("Error", "Server error. Try again.", "error");
        }
      });
    });
  </script>
</body>

</html>
