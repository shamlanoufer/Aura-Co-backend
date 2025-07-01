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
    <title>Personalised Jewellery - Aura & Co</title>
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

    <!-- Personalised Jewellery Section -->
    <section class="collections-section">
        <div class="section-header">
            <h2>Personalised Jewellery</h2>
            <p>Design your own unique piece with our bespoke service</p>
        </div>
        <div class="collections-grid">
            <?php
            // Query personalised jewellery products from the database
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE category='PersonalisedJewellery'");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
                while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <!-- Product Card -->
            <div class="collection-card">
                <img src="Images/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>" class="collection-image">
                <div class="collection-overlay">
                    <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
                    <p class="price">$<?= htmlspecialchars($fetch_products['price']); ?></p>
                    <a href="view_page.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="collection-link">View Details</a>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No personalised jewellery available at the moment.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Site footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
