<?php

include '../components/connect.php';
date_default_timezone_set("Asia/Manila");
session_start();
$today = date('Y-m-d');

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';
}
if(isset($_POST['update_date'])){
   $order_id = $_POST['order_id'];
   $pickup_date = $_POST['pickup_date'];
   $update_to_pickup = "For Pick Up";
   $pickup_date = filter_var($pickup_date, FILTER_SANITIZE_STRING);
   $update_pickup_date = $conn->prepare("UPDATE `orders` SET pickup_date = ?, payment_status = ? WHERE id = ?");
   $update_pickup_date->execute([$pickup_date, $update_to_pickup, $order_id]);
   $message[] = 'Pick Up Date Set!';
}
if(isset($_POST['update_completed'])){
   $order_id = $_POST['order_id'];
   $payment_status_complete = $_POST['payment_status_complete'];
   $payment_status_complete = filter_var($payment_status_complete, FILTER_SANITIZE_STRING);

   // Handle file upload
   $proof_pic = $_FILES['proof_pic']['name'];
   $proof_pic = filter_var($proof_pic, FILTER_SANITIZE_STRING);
   $proof_tmp_name = $_FILES['proof_pic']['tmp_name'];
   $proof_folder = '../completed_pic/' . $proof_pic;
   $proof_date = date('Y-m-d H:i:s');

   if (move_uploaded_file($proof_tmp_name, $proof_folder)) {
      // Update query to include proof_pic and proof_date
      $update_payment_complete = $conn->prepare("UPDATE `orders` SET payment_status = ?, proof_pic = ?, proof_date = ? WHERE id = ?");
      $update_payment_complete->execute([$payment_status_complete, $proof_pic, $proof_date, $order_id]);
      $message[] = 'Payment status and proof of purchase updated!';
   } else {
      $message[] = 'Failed to upload proof picture!';
   }
}
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>To Pick up Orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      input[type="date"] {
         padding: 8px;
         border: 1px solid #ccc;
         border-radius: 4px;
         width: 285px;
         box-sizing: border-box;
      }
      input[type="file"] {
         background-color: var(--light-bg);
         padding: 8px;
         border: 1px solid #ccc;
         border-radius: 4px;
         width: 285px;
         box-sizing: border-box;
      }  
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">

<h1 class="heading">Set Pick Up Date</h1>

<div class="box-container">

   <?php
      $status1 = "To Process";
      $select_orders = $conn->prepare("SELECT * FROM `orders`WHERE payment_status = ?");
      $select_orders->execute([$status1]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total price : <span>Php <?= $fetch_orders['total_price']; ?></span> </p>
      <p> Order Status: <span><?= $fetch_orders['payment_status']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <input type="date" name="pickup_date" required min="<?= $today ?>">
        <div >
         <input type="submit" value="update" class="option-btn" name="update_date">
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No orders to set pick up date!</p>';
      }
   ?>

</div>

</section>

<section class="orders">

<h1 class="heading">For Pick Up Product</h1>

<div class="box-container">

   <?php
   $status2 = "For Pick Up";
   $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
   $select_orders->execute([$status2]);
   if($select_orders->rowCount() > 0){
      while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total price : <span>Php. <?= $fetch_orders['total_price']; ?></span> </p>
      <p> Pick Up Date: <span><?= $fetch_orders['pickup_date']; ?></span> </p>
      <form action="" method="post" enctype="multipart/form-data">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <p>Proof of Purchase</p>
         <input type="file" name="proof_pic" required>
         <p>Order Status</p>
         <select name="payment_status_complete" class="select" required>
            <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="For Pick Up">For Pick Up</option>
            <option value="Completed">Completed</option>
         </select>
         <div>
            <input type="submit" value="update" class="option-btn" name="update_completed">
         </div>
      </form>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No orders For Pick Up</p>';
   }
   ?>

</div>

</section>



<script src="../js/admin_script.js"></script>
   
</body>
</html>