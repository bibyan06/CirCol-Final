<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'Item already added to wishlist!';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'Item already added to cart!';
      }else{
         // Fetch product category from your database, assuming you have a table named `products` with a `category` column
      $check_product_category = $conn->prepare("SELECT category FROM `products` WHERE id = ?");
      $check_product_category->execute([$pid]);
      $category_row = $check_product_category->fetch(PDO::FETCH_ASSOC);
      $category = $category_row['category'];

      if($category == 'Shirt'){
         $size = $_POST['size'];
         $size = filter_var($size, FILTER_SANITIZE_STRING);
         // Insert size only if the category is 'Shirt'
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image, size) VALUES(?,?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image, $size]);
         $message[] = 'Item added to wishlist!';
      } else {
         // If the category is not 'Shirt', insert without size
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         $message[] = 'Item added to wishlist!';
      }
         
      }

   }

}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);
      

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'Item already added to cart!';
      }else{

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$name, $user_id]);
         }

         // Fetch product category from your database, assuming you have a table named `products` with a `category` column
         $check_product_category = $conn->prepare("SELECT category FROM `products` WHERE id = ?");
         $check_product_category->execute([$pid]);
         $category_row = $check_product_category->fetch(PDO::FETCH_ASSOC);
         $category = $category_row['category'];

         if($category == 'Shirt'){
            $size = $_POST['size'];
            $size = filter_var($size, FILTER_SANITIZE_STRING);
            // Insert size only if the category is 'Shirt'
            $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, size, image) VALUES(?,?,?,?,?,?,?)");
            $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $size, $image]);
            $message[] = 'Item added to cart!';
         } else {
            // If the category is not 'Shirt', insert without size
            $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
            $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
            $message[] = 'Item added to cart!';
         }
         
         
      }

   }

}

?>