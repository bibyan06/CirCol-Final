<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   } else {
      $message[] = 'Incorrect username or password!';
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
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <img src="images/login-banner.png" alt="Login Icon" style="vertical-align: middle; width: 200px; height: 150px;">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <div class="password-container">
         <input type="password" name="pass" required placeholder="Password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" id="password">
         <i class="fas fa-eye" id="togglePassword"></i>
      </div>
      <input type="submit" value="login now" class="btn" name="submit">
      <p style="font-size:19px;">Don't have an account?</p>
      <a href="user_register.php" class="option-btn">Register Now.</a>
      <p style="font-size:15px;">
        <a href="forgot_password.php" class="forgot-password">I forgot my Password</a>
      </p>
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