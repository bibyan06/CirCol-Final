<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search page</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<section class="products" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
<<<<<<< HEAD
     $search_box = $_POST['search_box'];

     $query ="SELECT * FROM products WHERE 
              name LIKE :search 
              OR details LIKE :search 
              OR price LIKE :search 
              OR image_01 LIKE :search 
              OR category LIKE :search 
              OR stock LIKE :search";

     $select_products = $conn->prepare($query); 
     $select_products->bindValue(':search', '%' . $search_box . '%');
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
=======
      $search_box = $_POST['search_box'];
      // Prepare the query with placeholders to prevent SQL injection
      $query = "SELECT * FROM `products` WHERE 
                name LIKE :search 
                OR details LIKE :search 
                OR price LIKE :search 
                OR image_01 LIKE :search 
                OR category LIKE :search 
                OR stock LIKE :search";
      $select_products = $conn->prepare($query);
      // Bind the parameter
      $select_products->bindValue(':search', '%' . $search_box . '%');
      $select_products->execute();
      if($select_products->rowCount() > 0){
          while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
>>>>>>> e40e59de633f8d8fe7ee239d4162d8e56d26d85f
   ?>

   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>Php. </span><?= $fetch_product['price']; ?><span></span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <?php if($fetch_product['category'] === 'Shirt'): ?>
      <div class="sizes">
         <label><input type="radio" name="size" value="Small" required> Small</label>
         <label><input type="radio" name="size" value="Medium"> Medium</label>
         <label><input type="radio" name="size" value="Large"> Large</label>
      </div>
      <?php endif; ?>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
   }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
