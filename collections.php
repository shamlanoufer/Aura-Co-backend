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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections - Aura & Co</title>
    <!-- Main site styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/collections.css">
    <link rel="stylesheet" href="css/components.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Site header -->
    <?php include 'header.php'; ?>

    <!-- Collections Section -->
    <section class="collections-section">
        <div class="section-header">
            <h2>Our Collections</h2>
            <p>Discover our curated jewelry collections, each telling a unique story</p>
        </div>
        <div class="collections-grid">
            <!-- Each collection card links to shop.php with a filter or anchor -->
            <div class="collection-card">
                <img src="Images/collection1.jpg" alt="Luxurious Lustre" class="collection-image">
                <div class="collection-overlay">
                    <h3>Luxurious Lustre</h3>
                    <a href="shop.php?collection=luxurious-lustre" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images/collection2.jpg" alt="Radiant Reflections" class="collection-image">
                <div class="collection-overlay">
                    <h3>Radiant Reflections</h3>
                    <a href="shop.php?collection=radiant-reflections" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images/collection3.jpg" alt="Majestic Mementos" class="collection-image">
                <div class="collection-overlay">
                    <h3>Majestic Mementos</h3>
                    <a href="shop.php?collection=majestic-mementos" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images/collection4.jpg" alt="Blissful Baubles" class="collection-image">
                <div class="collection-overlay">
                    <h3>Blissful Baubles</h3>
                    <a href="shop.php?collection=blissful-baubles" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images/collection5.jpg" alt="Timeless Treasures" class="collection-image">
                <div class="collection-overlay">
                    <h3>Timeless Treasures</h3>
                    <a href="shop.php?collection=timeless-treasures" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images/collection6.jpg" alt="Divine Diamonds" class="collection-image">
                <div class="collection-overlay">
                    <h3>Divine Diamonds</h3>
                    <a href="shop.php?collection=divine-diamonds" class="collection-link">Explore →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Site footer -->
    <?php include 'footer.php'; ?>
</body>
</html>