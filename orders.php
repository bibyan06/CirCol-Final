<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   

  <?php
      if($user_id == ''){
         echo '<p class="empty">Please login to see your orders</p>';
      } else {
         $status1 = "Pending";
         $status2 = "To Process";
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND (payment_status = ? OR payment_status = ?)");
         $select_orders->execute([$user_id, $status1, $status2]);
         if($select_orders->rowCount() > 0){
               while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>

   <h1 class="heading">Pending Orders</h1>

   <div class="box-container">

   <div class="box">
      <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Phone Number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total price : <span>Php.<?= $fetch_orders['total_price']; ?></span></p>
      <!-- <p> Order status : <span style="color:<?php if($fetch_orders['payment_status'] == 'Pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p> -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#receiptModal" data-receipt="<?= $fetch_orders['receipt']; ?>" style="height:30px;font-size: 15px;">
            View Receipt
      </button>
      <!-- <p> Pick Up Date: <span><?= $fetch_orders['pickup_date']; ?></span> </p> -->
      
      <p>Order Status: <span><?= $fetch_orders['payment_status']; ?></span></p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no pending yet!</p>';
      }
      }
   ?>

   </div>
   
   <?php
    if($user_id == ''){
       
    } else {
        $status = "For Pick Up";
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = ?");
        $select_orders->execute([$user_id, $status]);
        if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <h1 class="heading">For Pick Up Orders</h1>

   <div class="box-container">

   <div class="box">
      <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Phone Number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total price : <span>Php.<?= $fetch_orders['total_price']; ?></span></p>
      <!-- <p> Order status : <span style="color:<?php if($fetch_orders['payment_status'] == 'Pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p> -->
      <p> Pick Up Date: <span><?= $fetch_orders['pickup_date']; ?></span> </p>
      <p>Order Status: <span><?= $fetch_orders['payment_status']; ?></span></p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">Order is not yet Ready to Pick up</p>';
      }
      }
   ?>

   </div>
   <?php
    if($user_id == ''){
       
    } else {
        $status = "Completed";
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = ?");
        $select_orders->execute([$user_id, $status]);
        if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <h1 class="heading">Completed Orders</h1>

   <div class="box-container">

   <div class="box">
      <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Phone Number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total price : <span>Php.<?= $fetch_orders['total_price']; ?></span></p>
      
      <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#proofPicModal" data-proof-pic="<?= $fetch_orders['proof_pic']; ?>" style="height:30px;font-size: 15px;">
            View Proof
      </button>
      <p>Order Status: <span><?= $fetch_orders['payment_status']; ?></span></p>
      <p>Completed Date & Time: <span><?= date('m/d/Y h:i A', strtotime($fetch_orders['proof_date'])); ?></span></p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">No Order Completed</p>';
      }
      }
   ?>

   </div>

</section>



<?php include 'components/footer.php'; ?>

<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="receiptImage" src="" class="img-fluid" alt="Receipt Image">
            </div>
        </div>
    </div>
</div>

<!-- Proof Pic Modal -->
<div class="modal fade" id="proofPicModal" tabindex="-1" aria-labelledby="proofPicModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofPicModalLabel">Proof Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="proofPicImage" src="" class="img-fluid" alt="Proof Picture">
            </div>
        </div>
    </div>
</div>
<script src="js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var receiptModal = document.getElementById('receiptModal');
    receiptModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var receipt = button.getAttribute('data-receipt');
        var receiptImage = document.getElementById('receiptImage');
        receiptImage.src = './receipt/' + receipt;
    });

    var proofPicModal = document.getElementById('proofPicModal');
    proofPicModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var proofPic = button.getAttribute('data-proof-pic');
        var proofPicImage = document.getElementById('proofPicImage');
        proofPicImage.src = './completed_pic/' + proofPic;
    });
});
</script>

</body>
</html>