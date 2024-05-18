<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   header('Location: user_login.php');
   exit;
}

$message = '';

if (isset($_POST['submit'])) {
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   // Validate password matching
   if ($pass === $cpass) {
      $pass = password_hash($pass, PASSWORD_DEFAULT);

      $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
      $update_password->execute([$pass, $user_id]);

      $message = 'Password has been updated successfully!';
      session_destroy();
      header('Location: user_login.php');
      exit;
   } else {
      $message = 'Passwords do not match. Please try again!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset Password</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .password-container {
         position: relative;
      }

      .password-container .box {
         width: 100%;
         padding-right: 40px; /* Make room for the icon */
      }

      .password-container i {
         position: absolute;
         right: 10px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
      }
   </style>
</head>
<body>
   
<!-- <?php include 'components/user_header.php'; ?> -->

<section class="form-container">

   <form action="" method="post" onsubmit="return validatePasswords()">
      <img src="images/login-banner.png" alt="Login Icon" style="vertical-align: middle; width: 200px; height: 150px;">
      <h3>Reset Password</h3>
      <div class="password-container">
         <input type="password" name="pass" required placeholder="Create new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" id="password">
      </div>
      <div class="password-container">
         <input type="password" name="cpass" required placeholder="Confirm new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" id="cpassword">
      </div>
      <input type="submit" value="Reset" class="btn" name="submit">
      <p style="font-size:20px;">
        <a href="user_login.php" class="forgot-password">Cancel</a>
      </p>
      <?php if($message != ''): ?>
         <p style="color: red; font-size: 16px;"><?php echo $message; ?></p>
      <?php endif; ?>
   </form>

</section>

<!-- <?php include 'components/footer.php'; ?> -->

<script src="js/script.js"></script>
<script>
   function validatePasswords() {
      const password = document.getElementById('password').value;
      const cpassword = document.getElementById('cpassword').value;

      if (password !== cpassword) {
         alert('Passwords do not match. Please try again!');
         return false;
      }

      return true;
   }

   document.getElementById('togglePassword').addEventListener('click', function (e) {
      const password = document.getElementById('password');
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
   });
</script>

</body>
</html>
