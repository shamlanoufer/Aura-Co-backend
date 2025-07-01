<?php
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
?>

<header class="header">
    <nav class="navbar">
        <a href="home.php" class="logo">
            <svg viewBox="0 0 400 80" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#000000" />
                        <stop offset="100%" stop-color="#222222" />
                    </linearGradient>
                </defs>
                <text x="0" y="50" font-family="'Didot', serif" font-size="28" font-weight="400" letter-spacing="6" fill="url(#logoGradient)">
                    A U R A &amp; C O
                </text>
                <text x="120" y="70" font-family="'Didot', serif" font-size="8" font-weight="300" letter-spacing="3" fill="#666666">
                    JEWELRY
                </text>
            </svg>
        </a>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li><a href="collections.php">Collections</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="icons">
            <i class="fas fa-user" id="user-btn"></i>
            <i class="fas fa-bars" id="menu-btn"></i>
            <a href="search.php"><i class="fas fa-search"></i></a>
            <?php
                $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $count_cart_items->execute([$user_id]);
                $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $count_wishlist_items->execute([$user_id]);
            ?>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
        </div>
        <?php if ($user_id): ?>
        <div class="profile">
            <?php
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id=?");
                $select_profile->execute([$user_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="uploaded_img/<?= $fetch_profile['image'];?>" alt="">
            <p><?= $fetch_profile['name'];?></p>
            <a href="logout.php" class="option-btn">logout</a>
            <div class="flex-btn">
                <a href="user_profile_update.php" class="btn">update profile</a>
            </div>
        </div>
        <?php endif; ?>
    </nav>
</header>