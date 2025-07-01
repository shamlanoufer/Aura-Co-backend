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

// Handle Add to Wishlist form submission
if (isset($_POST['add_to_wishlist'])) {
    // Get product details from form
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];

    // Check if product is already in wishlist for this user
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

// Handle Add to Cart form submission
if (isset($_POST['add_to_cart'])) {
    // Get product details from form
    $pid = $_POST['pid'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_image = $_POST['p_image'];
    $p_qty = $_POST['p_qty'] ?? 1; // Default quantity is 1 if not specified

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

// Get username and cart count from session (if needed for header)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura & Co - Fine Jewelry</title>
    <!-- Main site styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">
     <link rel="stylesheet" href="css/newstyle.css">
    <link rel="stylesheet" href="css/components.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="custom-bg">
    <!-- Show feedback messages -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
        }
    }
    ?>
    <!-- Site header -->
    <?php include 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Aura & Co.<br>Where Timeless Elegance Meets You</h1>
            <p>Discover our exquisite collection of handcrafted jewelry</p>
            <a href="shop.php" class="shop-btn">Shop Now</a>
        </div>
        <div class="hero-images">
            <img src="Images\hero5.jpg" alt="Luxury Diamond Collection">
            <img src="Images\hero2.jpg" alt="Gold Jewelry Showcase">
            <img src="Images\hero6.jpg" alt="Luxury Diamond Collection">
            <img src="Images\hero4.jpg" alt="Gold Jewelry Showcase">
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="service">
            <i class="fas fa-truck"></i>
            <h3>Free Worldwide Shipping</h3>
            <p>On all orders over $500</p>
        </div>
        <div class="service">
            <i class="fas fa-gem"></i>
            <h3>Authentic Quality</h3>
            <p>Certified precious metals & stones</p>
        </div>
        <div class="service">
            <i class="fas fa-shield-alt"></i>
            <h3>Secure Payment</h3>
            <p>100% protected transactions</p>
        </div>
        <div class="service">
            <i class="fas fa-undo"></i>
            <h3>Easy Returns</h3>
            <p>30-day return policy</p>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-section" id="featured-section">
        <div class="section-header">
            <h2>Featured Collections</h2>
            <p>Explore our finest collection of elegant jewelry</p>
            <p class="subtext">Our latest luxury styles & classic designs combined with an exquisite touch</p>
        </div>
        <div class="product-grid">
    <?php
    // Query featured products from the database
    $select_products = $conn->prepare("SELECT * FROM `products` WHERE category='FeaturedProducts' ");
    $select_products->execute();
    if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <!-- Product Card -->
    <form action="" class="product-card" method="POST">
        <div class="product-badge"><?php echo ($fetch_products['name'] == 'Reflections Necklace') ? 'New' : (($fetch_products['name'] == 'Serene Solitaire Earrings') ? 'Limited' : (($fetch_products['name'] == 'Luxury Gems Necklace') ? 'Bestseller' : '')); ?></div>
        <img src="Images/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>">
        <div class="product-info">
            <h3><?= htmlspecialchars($fetch_products['name']); ?></h3>
            <p class="price">$<?= htmlspecialchars($fetch_products['price']); ?></p>
            <div class="quantity-container">
                <input type="number" name="p_qty" value="1" min="1" class="quantity-input">
            </div>
            <div class="product-buttons">
                <!-- Hidden fields for product info -->
                <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
                <input type="hidden" name="p_name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
                <input type="hidden" name="p_price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
                <input type="hidden" name="p_image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
                <!-- Wishlist and Cart buttons -->
                <button type="submit" class="wishlist-btn" name="add_to_wishlist"><i class="far fa-heart"></i> Add To Wishlist</button>
                <button type="submit" class="add-to-cart" name="add_to_cart">Add to Cart</button>
            </div>
        </div>
    </form>
    <?php
        }
    } else {
        echo '<p class="empty">No products added yet!</p>';
    }
    ?>
</div>
        <div class="section-footer">
        <a href="shop.php" class="cta-button">View All Collections</a>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="collections-section">
        <div class="section-header">
            <h2>Our Collections</h2>
            <p>Discover our curated jewelry collections, each telling a unique story</p>
        </div>
        <div class="collections-grid">
            <!-- Collection Cards -->
            <div class="collection-card">
                <img src="Images\collection1.jpg" alt="Luxurious Lustre" class="collection-image">
                <div class="collection-overlay">
                    <h3>Luxurious Lustre</h3>
                    <a href="shop.php" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images\collection2.jpg" alt="Divine Diamonds" class="collection-image">
                <div class="collection-overlay">
                    <h3>Divine Diamonds</h3>
                    <a href="shop.php" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images\collection3.jpg" alt="Timeless Treasures" class="collection-image">
                <div class="collection-overlay">
                    <h3>Timeless Treasures</h3>
                    <a href="shop.php" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images\collection4.jpg" alt="Pearl Perfection" class="collection-image">
                <div class="collection-overlay">
                    <h3>Pearl Perfection</h3>
                    <a href="shop.php" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images\collection5.jpg" alt="Gold Glamour" class="collection-image">
                <div class="collection-overlay">
                    <h3>Gold Glamour</h3>
                    <a href="shop.php" class="collection-link">Explore →</a>
                </div>
            </div>
            <div class="collection-card">
                <img src="Images\collection6.jpg" alt="Silver Sophistication" class="collection-image">
                <div class="collection-overlay">
                    <h3>Silver Sophistication</h3>
                    <a href="shop.php" class="collection-link">Explore →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Collection Section -->
    <section class="premium-collection">
        <div class="premium-content">
            <h2>Premium Collection</h2>
            <p>Experience luxury redefined with our exclusive premium jewelry collection. Each piece is meticulously crafted to perfection.</p>
            <div class="video-container">
                <video autoplay muted loop>
                    <source src="Images\video_1.jpeg.mp4" type="video/mp4">
                </video>
                <div class="video-overlay">
                    <a href="shop.php" class="cta-button">Discover Premium</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Scripts - Load at the end -->
    <script src="js/script.js"></script>

</body>
</html>