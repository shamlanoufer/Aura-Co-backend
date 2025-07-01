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

// Get product ID from query string
$pid = isset($_GET['pid']) ? $_GET['pid'] : null;
if (!$pid) {
    // If no product ID, redirect to home
    header('location:home.php');
    exit();
}

// Query product details from database
$select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
$select_product->execute([$pid]);
$product = $select_product->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    // If product not found, redirect to home
    header('location:home.php');
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

// Existing session data for username and cart count
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($product['name']); ?> - Aura & Co</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="css/shop.css">
   <link rel="stylesheet" href="css/components.css">
   <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
   <script defer src="js/script.js"></script>
</head>
<body>
   
<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
    }
}
?>

<?php include 'header.php'; ?>

<section class="product-details-section">
    <div class="product-details-container">
        <img src="Images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-details-image">
        <div class="product-details-info">
            <h2><?= htmlspecialchars($product['name']); ?></h2>
            <p class="price">$<?= htmlspecialchars($product['price']); ?></p>
            <p class="description"><?= htmlspecialchars($product['details']); ?></p>
            <form action="cart.php" method="POST" class="product-action-form">
                <input type="hidden" name="pid" value="<?= htmlspecialchars($product['id']); ?>">
                <input type="hidden" name="p_name" value="<?= htmlspecialchars($product['name']); ?>">
                <input type="hidden" name="p_price" value="<?= htmlspecialchars($product['price']); ?>">
                <input type="hidden" name="p_image" value="<?= htmlspecialchars($product['image']); ?>">
                <input type="number" name="p_qty" value="1" min="1" class="quantity-input">
                <button type="submit" class="add-to-cart" name="add_to_cart">Add to Cart</button>
            </form>
            <form action="wishlist.php" method="POST" class="product-action-form">
                <input type="hidden" name="pid" value="<?= htmlspecialchars($product['id']); ?>">
                <input type="hidden" name="p_name" value="<?= htmlspecialchars($product['name']); ?>">
                <input type="hidden" name="p_price" value="<?= htmlspecialchars($product['price']); ?>">
                <input type="hidden" name="p_image" value="<?= htmlspecialchars($product['image']); ?>">
                <button type="submit" class="wishlist-btn" name="add_to_wishlist"><i class="far fa-heart"></i> Add to Wishlist</button>
            </form>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

</body>
</html>