<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

$message = '';

if (isset($_POST['submit'])) {
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $_SESSION['user_id'] = $row['id'];
      header('Location: reset_password.php');
      exit; // Ensure no further code is executed
   } else {
      $message = 'Email does not exist. Please try again!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   
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

   <form action="" method="post">
      <img src="images/login-banner.png" alt="Login Icon" style="vertical-align: middle; width: 200px; height: 150px;">
      <h3>Verify your Email</h3>
      <input type="email" name="email" required placeholder="Email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="Verify" class="btn" name="submit">
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
   document.getElementById('togglePassword').addEventListener('click', function (e) {
      const password = document.getElementById('password');
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
   });
</script>

</body>
</html>
