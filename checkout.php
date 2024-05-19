<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   if (isset($_FILES['receipt'])) {
       $receipt = $_FILES['receipt']['name'];
       $receipt = filter_var($receipt, FILTER_SANITIZE_STRING);
       $image_size_01 = $_FILES['receipt']['size'];
       $image_tmp_name_01 = $_FILES['receipt']['tmp_name'];
       $image_folder_01 = './receipt/'.$receipt;

       $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
       $check_cart->execute([$user_id]);

       if($check_cart->rowCount() > 0){
          $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, receipt) VALUES(?,?,?,?,?,?,?,?,?)");
          $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $receipt ]);

          $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
          $delete_cart->execute([$user_id]);

          if($insert_order){
             if($image_size_01 > 2000000){
                $message[] = 'Image size is too large!';
             }else{
                move_uploaded_file($image_tmp_name_01, $image_folder_01);
                $message[] = 'Order Placed successfully!';
                header('location:orders.php');
             }
          }
       } else {
          $message[] = 'Your cart is Empty';
       }
   } else {
       $message[] = 'Please upload a receipt.';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST" enctype="multipart/form-data">

   <h3>Your Orders</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items = [];
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               // Fetch the selected size for the current cart item
               $size = $fetch_cart['size'];
               $item_name_with_size = $fetch_cart['name'] . ($size ? ' (Size: ' . $size . ')' : '');
               $cart_items[] = $item_name_with_size . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $item_name_with_size; ?> <span>(<?= 'Php.' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         } else {
            echo '<p class="empty">your cart is empty!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Grand Total : <span>Php.<?= $grand_total; ?></span></div>
      </div>

      <h3>Place Your Orders</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Full Name :</span>
            <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Phone Number :</span>
            <input type="text" name="number" placeholder="Enter your phone number" class="box" min="0" max="99999999999" onkeypress="if(this.value.length == 11) return false;" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
         </div>
         <input type="hidden" name="method" value="Gcash">
         <div class="inputBox">
            <span>Address :</span>
            <input type="text" name="address" placeholder="Legazpi, Albay" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>GCash QR Code :</span>
            <img class="box" src="./images/gcash_qr.jpg" alt="Gcash_QR" style="width:450px; height: 550px;">
         </div>
         <div class="inputBox">
            <span>GCash Receipt :</span>
            <input type="file" name="receipt" class="box" required>
         </div>
      </div>
      
      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Place Order">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
