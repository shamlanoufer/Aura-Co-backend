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

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
   $delete_cart_item->execute([$delete_id, $user_id]);
   header('location:cart.php');
   exit();
}

if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
   exit();
}

// Handle Update Quantity
if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $new_qty = $_POST['cart_qty'];
    // Update the quantity for the specific cart item
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ? AND user_id = ?");
    $update_qty->execute([$new_qty, $cart_id, $user_id]);
    $message[] = 'cart quantity updated!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart - Aura&Co</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="css/index.css">
   <link rel="stylesheet" href="css/cart.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="featured-section">
   <div class="section-header">
      <h2>Shopping Cart</h2>
      <p>Review your selected items and proceed to checkout</p>
   </div>

   <div class="product-grid">
   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? ORDER BY id DESC");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="product-card">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="remove-btn" onclick="return confirm('Remove this item from cart?');">
         <i class="fas fa-times"></i>
      </a>
      <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="view-btn">
         <i class="fas fa-eye"></i>
      </a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="<?= $fetch_cart['name']; ?>">
      <div class="product-info">
         <h3><?= $fetch_cart['name']; ?></h3>
         <p class="price">$<?= $fetch_cart['price']; ?></p>
         <form action="" method="POST" class="quantity-form">
            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
            <div class="quantity-container">
               <label for="qty-<?= $fetch_cart['id']; ?>">Quantity:</label>
               <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" name="cart_qty" id="qty-<?= $fetch_cart['id']; ?>" class="quantity-input">
               <button type="submit" name="update_qty" class="update-btn">Update</button>
            </div>
         </form>
         <div class="sub-total">
            <strong>Sub Total: $<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></strong>
         </div>
      </div>
   </div>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<div class="empty-cart">';
      echo '<i class="fas fa-shopping-cart"></i>';
      echo '<h3>Your cart is empty</h3>';
      echo '<p>Add some beautiful jewelry to your cart!</p>';
      echo '<a href="shop.php" class="cta-button">Start Shopping</a>';
      echo '</div>';
   }
   ?>
   </div>

   <?php if($grand_total > 0): ?>
   <div class="cart-summary-section">
      <div class="cart-summary">
         <h3>Cart Summary</h3>
         <div class="summary-row">
            <span>Total Items:</span>
            <span><?= $select_cart->rowCount(); ?></span>
         </div>
         <div class="summary-row total">
            <span>Grand Total:</span>
            <span>$<?= $grand_total; ?></span>
         </div>
         <div class="cart-actions">
            <a href="shop.php" class="continue-shopping">Continue Shopping</a>
            <a href="cart.php?delete_all" class="clear-cart" onclick="return confirm('Clear all items from cart?');">Clear Cart</a>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
         </div>
      </div>
   </div>
   <?php else: ?>
   <div class="cart-summary-section">
      <div class="cart-summary">
         <h3>Cart Summary</h3>
         <div class="summary-row">
            <span>Total Items:</span>
            <span>0</span>
         </div>
         <div class="summary-row total">
            <span>Grand Total:</span>
            <span>$0</span>
         </div>
         <div class="cart-actions">
            <a href="shop.php" class="continue-shopping">Continue Shopping</a>
            <a href="#" class="clear-cart disabled" style="pointer-events:none;opacity:0.5;">Clear Cart</a>
            <a href="#" class="checkout-btn disabled" style="pointer-events:none;opacity:0.5;">Proceed to Checkout</a>
         </div>
      </div>
   </div>
   <?php endif; ?>
</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>