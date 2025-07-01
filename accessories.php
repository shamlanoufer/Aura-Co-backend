<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM products WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO products(name, category, details, price, image) VALUES(?,?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $price, $image]);

      if($insert_products){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new product added!';
         }

      }

   }

};
// Delete Product
if(isset($_GET['delete_product'])){
   $delete_id = $_GET['delete_product'];
   $select_delete_image = $conn->prepare("SELECT image FROM products WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM products WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM cart WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin.php#show-products');
   exit();
}

if(isset($_POST['update_order'])){

   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_orders = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'payment has been updated!';

}

if(isset($_GET['delete_order'])){
   $delete_id = $_GET['delete_order'];
   $delete_orders = $conn->prepare("DELETE FROM orders WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:admin.php#placed-order');
   exit();
}



if(isset($_GET['delete_user'])){
   $delete_id = $_GET['delete_user'];
   $delete_users = $conn->prepare("DELETE FROM users WHERE id = ?");
   $delete_users->execute([$delete_id]);
   header('location:admin.php#user_accounts');
   exit();
}


if(isset($_GET['delete_message'])){
   $delete_id = $_GET['delete_message'];
   $delete_message = $conn->prepare("DELETE FROM message WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:admin.php#messages');
   exit();
}

// Handle Add to Wishlist functionality
if (isset($_POST['add_to_wishlist'])) {
    // Get product details from form
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];

    // Check if this exact product is already in user's wishlist
    $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
        $message[] = 'already added to wishlist!';
    } else {
        // Insert new item into wishlist
        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
        $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
        $message[] = 'added to wishlist!';
    }
}

// Handle Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    // Get product details from form
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];
    $p_qty = $_POST['p_qty'] ?? 1; // Default quantity is 1 if not specified

    // Check if this exact product is already in user's cart
    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        // If item exists in cart, update the quantity instead of adding duplicate
        $update_cart = $conn->prepare("UPDATE `cart` SET quantity = quantity + ? WHERE name = ? AND user_id = ?");
        $update_cart->execute([$p_qty, $p_name, $user_id]);
        $message[] = 'cart quantity updated!';
    } else {
        // Insert new item into cart
        $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
        $message[] = 'added to cart!';
    }
}
?>

<?php
if(isset($message)){
foreach($message as $msg){
  echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bracelets Collection - Aura & Co</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/jewelry.css"> <!-- Fixed space in filename -->
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>
<?php
// Dummy example for user and cart session data
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
?>
   <main class="accessories-main">
        <div class="page-header">
            <h1>Accessories Collection</h1>
            <p>Complementary pieces to complete your look</p>
        </div>


        <!-- Filters -->
 <div class="filters">
<div class="filter-options">
                <a href="rings.php" class="filter">Rings</a>
                <a href="necklaces.php" class="filter">Necklaces</a>
                <a href="earrings.php" class="filter">Earrings</a>
                <a href="bracelets.php" class="filter">Bracelets</a>
                <a href="accessories.php" class="filter active">Accessories</a>
                <a href="personalised_jewellery.php" class="filter">Personalised Jewellery </a>
                <a href="home.php#featured-section" class="filter">Featured Products</a>
            </div>
</div>


   <div class="box-container">

   <?php
  $select_products = $conn->prepare("SELECT * FROM products WHERE category = 'accessories'");
   $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
 <form action="" class="box" method="POST">
         <div class="price">Rs.<span><?= $fetch_products['price']; ?></span>/-</div>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <div class="name"><?= $fetch_products['name']; ?></div>
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
         <input type="number" min="1" value="1" name="p_qty" class="qty">
         <button type="submit" class="wishlist-btn" name="add_to_wishlist"><i class="far fa-heart"></i> Add To Wishlist</button>
         <input type="submit" value="add to cart" class="btn" name="add_to_cart">
      </form>
   <?php
      }
   }else{
      echo '<p class="empty">No products added yet!</p>';
   }
   ?>
   </div>
      </main>

        <!-- Footer -->
 <?php include 'footer.php'; ?>
 <script src="script.js"></script>


</body>
</html>