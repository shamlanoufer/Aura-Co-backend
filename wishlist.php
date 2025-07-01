<?php

// Include database configuration
@include 'config.php';

// Start session and get user ID
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Redirect to login if user is not logged in
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Handle Add to Cart from Wishlist
if (isset($_POST['add_to_cart'])) {
    // Get product details from form
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];
    $p_qty = $_POST['p_qty'] ?? 1;

    // Check if product is already in cart for this user
    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        // If item exists in cart, update the quantity
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

// Handle Remove from Wishlist
if (isset($_POST['remove_from_wishlist'])) {
    $wishlist_id = $_POST['wishlist_id'];
    // Delete the specific wishlist item for this user
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE id = ? AND user_id = ? LIMIT 1");
    $delete_wishlist->execute([$wishlist_id, $user_id]);
    $message[] = 'removed from wishlist!';
}

// Delete all wishlist items for current user
if(isset($_GET['delete_all'])){

   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');
   exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Wishlist - Aura&Co</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="css/index.css">
   <link rel="stylesheet" href="css/wishlist.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="featured-section">
   <div class="section-header">
      <h2>My Wishlist</h2>
      <p>Your saved favorites and dream jewelry pieces</p>
   </div>

   <div class="product-grid">
   <?php
      $grand_total = 0;
      // Only show current user's wishlist items
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? ORDER BY id DESC");
      $select_wishlist->execute([$user_id]);
      
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="product-card">
      <a href="wishlist.php?delete=<?= $fetch_wishlist['id']; ?>" class="remove-btn" onclick="return confirm('Remove this item from wishlist?');">
         <i class="fas fa-times"></i>
      </a>
      <a href="view_page.php?pid=<?= $fetch_wishlist['pid']; ?>" class="view-btn">
         <i class="fas fa-eye"></i>
      </a>
      <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="<?= $fetch_wishlist['name']; ?>">
      <div class="product-info">
         <h3><?= $fetch_wishlist['name']; ?></h3>
         <p class="price">$<?= $fetch_wishlist['price']; ?></p>
         <form action="" method="POST" class="wishlist-form">
            <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
            <input type="hidden" name="p_name" value="<?= $fetch_wishlist['name']; ?>">
            <input type="hidden" name="p_price" value="<?= $fetch_wishlist['price']; ?>">
            <input type="hidden" name="p_image" value="<?= $fetch_wishlist['image']; ?>">
            <div class="quantity-container">
               <label for="qty-<?= $fetch_wishlist['id']; ?>">Quantity:</label>
               <input type="number" min="1" value="1" name="p_qty" id="qty-<?= $fetch_wishlist['id']; ?>" class="quantity-input">
            </div>
            <button type="submit" class="wishlist-btn" name="add_to_wishlist"><i class="far fa-heart"></i> Add To Wishlist</button>
         </form>
      </div>
   </div>
   <?php
      $grand_total += $fetch_wishlist['price'];
      }
   }else{
      echo '<div class="empty-wishlist">';
      echo '<i class="fas fa-heart"></i>';
      echo '<h3>Your wishlist is empty</h3>';
      echo '<p>Start adding your favorite jewelry pieces!</p>';
      echo '<a href="shop.php" class="cta-button">Explore Collections</a>';
      echo '</div>';
   }
   ?>
   </div>

   <?php if($grand_total > 0): ?>
   <div class="wishlist-summary-section">
      <div class="wishlist-summary">
         <h3>Wishlist Summary</h3>
         <div class="summary-row">
            <span>Total Items:</span>
            <span><?= $select_wishlist->rowCount(); ?></span>
         </div>
         <div class="summary-row total">
            <span>Total Value:</span>
            <span>$<?= $grand_total; ?></span>
         </div>
         <div class="wishlist-actions">
            <a href="shop.php" class="continue-shopping">Continue Shopping</a>
            <a href="wishlist.php?delete_all" class="clear-wishlist" onclick="return confirm('Clear all items from wishlist?');">Clear Wishlist</a>
         </div>
      </div>
   </div>
   <?php endif; ?>
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script src="script.js"></script>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
    }
}
?>

</body>
</html>