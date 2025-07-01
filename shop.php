<?php
@include 'config.php'; // or require_once 'config.php';
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Aura&Co</title>
    <!-- Main stylesheet -->
   <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/newstyle.css">

    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/components.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="custom-bg">
    <?php include 'header.php'; ?>



    <main>
        <h1>Our Products</h1>
        <!-- Filters -->
        <div class="filters">
            <a href="rings.php"><button class="filter">Rings</button></a>
            <a href="necklaces.php"><button class="filter">Necklaces</button></a>
            <a href="earrings.php"><button class="filter">Earrings</button></a>
            <a href="bracelets.php"><button class="filter">Bracelets</button></a>
            <a href="accessories.php"><button class="filter">Accessories</button></a>
            <a href="personalised_jewellery.php"><button class="filter">Personalised Jewellery</button></a>
        </div>

        <!-- Product Grid -->
<div class="product-grid">
    <!-- Product Card 1 -->
    <div class="product-card">
        <img src="Images/shop1.jpg" alt="Aurora Ring - 14K Gold Plated Minimalist Ring" loading="lazy">
        <h2>Aurora Ring</h2>
        <p class="product-description">14K gold-plated minimalist ring with delicate engraving</p>
        <p class="price">$125.28 <span class="original-price">$150.00</span></p>
          </div>

    <!-- Product Card 2 -->
    <div class="product-card">
        <img src="Images/shop2.jpg" alt="Reflections Necklace - Diamond-Cut Pendant with 18\" Chain" loading="lazy">
        <h2>Reflections Necklace</h2>
        <p class="product-description">Diamond-cut pendant on an 18" sterling silver chain</p>
        <p class="price">$620.73</p>
           </div>

    <!-- Product Card 3 -->
    <div class="product-card">
        <img src="Images/shop3.jpg" alt="Dreamy Infinity Ring - Stackable Silver Ring with Symbol" loading="lazy">
        <h2>Dreamy Infinity Ring</h2>
        <p class="product-description">Stackable sterling silver ring with infinity symbol</p>
        <p class="price">$327.71</p>
           </div>

    <!-- Product Card 4 -->
    <div class="product-card">
        <img src="Images/shop4.jpg" alt="Luxury Gems Necklace - Ruby & Diamond Statement Piece" loading="lazy">
        <h2>Luxury Gems Necklace</h2>
        <p class="product-description">Handset rubies and diamonds in 14K gold</p>
        <p class="price">$168.76</p>
            </div>

    <!-- Product Card 5 -->
    <div class="product-card">
        <img src="Images/shop5.jpg" alt="Golden Twilight Earrings - Hoop Earrings with Crystal Details" loading="lazy">
        <h2>Golden Twilight Earrings</h2>
        <p class="product-description">18K gold-plated hoops with Swarovski crystals</p>
        <p class="price">$210.99</p>
          </div>

    <!-- Product Card 6 -->
    <div class="product-card">
        <img src="Images/shop6.jpg" alt="Starlight Pendant - Moonstone Pendant on Gold Chain" loading="lazy">
        <h2>Starlight Pendant</h2>
        <p class="product-description">Moonstone centerpiece on 16" gold vermeil chain</p>
        <p class="price">$359.49</p>
           </div>

    <!-- Product Card 7 -->
    <div class="product-card">
        <img src="Images/shop7.jpg" alt="Radiant Pearl Bracelet - Freshwater Pearls with Gold Clasp" loading="lazy">
        <h2>Radiant Pearl Bracelet</h2>
        <p class="product-description">6mm freshwater pearls with 14K gold clasp</p>
        <p class="price">$278.35</p>
       
    </div>
    <!-- Product Card 8 -->
    <div class="product-card">
        <img src="Images/shop8.jpg" alt="Celestial Gemstone Ring - Blue Topaz in Sterling Silver" loading="lazy">
        <h2>Celestial Gemstone Ring</h2>
        <p class="product-description">Oval blue topaz in sterling silver setting</p>
        <p class="price">$415.60</p>
           </div>

    <!-- Product Card 9 -->
    <div class="product-card">
        <img src="Images/shop9.jpg" alt="Silver Harmony Bangle - Adjustable Open Cuff Bracelet" loading="lazy">
        <h2>Silver Harmony Bangle</h2>
        <p class="product-description">Adjustable open cuff in polished sterling silver</p>
        <p class="price">$189.99</p>
       
    </div>

    <!-- Product Card 10 -->
    <div class="product-card">
        <img src="Images/shop10.jpg" alt="Eternal Diamond Ring - Solitaire 0.5ct in Platinum" loading="lazy">
        <h2>Eternal Diamond Ring</h2>
        <p class="product-description">0.5ct solitaire diamond in platinum setting</p>
        <p class="price">$799.99</p>
            </div>

    <!-- Product Card 11 -->
    <div class="product-card">
        <img src="Images/shop11.jpg" alt="Gold Daisy Claw Clip - Hair Accessory with Enamel Details" loading="lazy">
        <h2>Gold Daisy Claw Clip</h2>
        <p class="product-description">Gold-tone metal clip with enamel daisy accents</p>
        <p class="price">$14.50</p>
           </div>

    <!-- Product Card 12 -->
    <div class="product-card">
        <img src="Images/shop12.jpg" alt="Heart Tiara Headband - Gold-Tone Crystal Bridal Hairpiece" loading="lazy">
        <h2>Heart Tiara Headband</h2>
        <p class="product-description">Gold-tone wire with crystal heart details</p>
        <p class="price">$95.99</p>
          </div>
</div>
    </main>

<section class="story-grid">
    <div class="story-grid-container">
        <div class="grid-row">
            <div class="grid-item"><img src="Images/grid1.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid2.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid3.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid4.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid5.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid12.jpg" alt="Jewelry Story"></div>
        </div>
        <hr>
        <!-- Replace the story-text div with this brand showcase -->
<div class="brand-showcase">
    <span class="brand">ELLE</span>
    <span class="brand">VOGUE</span>
    <span class="brand">RUSSI</span>
    <span class="brand">style</span>
</div>
        <hr>
        <div class="grid-row">
            <div class="grid-item"><img src="Images/grid6.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid7.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid8.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid9.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid10.jpg" alt="Jewelry Story"></div>
            <div class="grid-item"><img src="Images/grid11.jpg" alt="Jewelry Story"></div>

        </div>
    </div>
</section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>

</body>
</html>
