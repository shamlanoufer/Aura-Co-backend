<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

// Get cart count for the user
$cart_count = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$cart_count->execute([$user_id]);
$total_cart_items = $cart_count->rowCount();

// Newsletter subscription
if(isset($_POST['subscribe'])){
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   
   if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $insert_newsletter = $conn->prepare("INSERT INTO `newsletter` (email, user_id) VALUES (?, ?)");
      $insert_newsletter->execute([$email, $user_id]);
      $message[] = 'Thank you for subscribing!';
   }else{
      $message[] = 'Invalid email address!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Aura & Co</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
    <script defer src="script.js"></script>
</head>

<body class="custom-bg">

<?php include 'header.php'; ?>

<!-- About Hero Section with Video Background -->
<section class="about-hero">
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <source src="Images/video_2.jpeg.mp4" type="video/mp4">
        </video>
        <div class="video-overlay"></div>
    </div>
    <div class="hero-content">
        <h1>Our Story</h1>
        <p>Where craftsmanship meets contemporary elegance</p>
    </div>
</section>

<!-- About Content Section -->
<section class="about-section">
    <div class="about-container">
        <div class="about-text">
            <h2>About Aura & Co</h2>
            <p>Founded in 2015, Aura & Co began as a small boutique with a passion for creating jewelry that tells stories. Today, we've grown into a renowned brand known for blending traditional craftsmanship with modern design.</p>
            <p>Each piece in our collection is meticulously crafted by skilled artisans who share our commitment to quality and beauty. We source only the finest materials, ensuring every item meets our exacting standards.</p>
        </div>
        <div class="about-image">
            <img src="Images/about1.jpeg" alt="Our Workshop">
        </div>
    </div>

    <div class="about-container reverse">
        <div class="about-image">
            <img src="Images/about2.jpeg" alt="Jewelry Making">
        </div>
        <div class="about-text">
            <h2>Our Philosophy</h2>
            <p>We believe jewelry should be as unique as the person wearing it. Our designs balance timeless elegance with contemporary flair, creating pieces that become cherished heirlooms.</p>
            <ul class="about-list">
                <li><i class="fas fa-gem"></i> Ethically sourced materials</li>
                <li><i class="fas fa-hands-helping"></i> Handcrafted by master artisans</li>
                <li><i class="fas fa-leaf"></i> Sustainable production practices</li>
                <li><i class="fas fa-heart"></i> Designed to be treasured forever</li>
            </ul>
        </div>
    </div>
</section>

<!-- Craftsmanship Video with Autoplay -->
<section class="craftsmanship-video">
    <div class="video-container">
        <video autoplay muted loop playsinline poster="Images/video_2.jpeg.mp3">
            <source src="Images/video_3.jpeg.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="video-overlay"></div>
        <div class="video-content">
            <h2>The Art of Jewelry Making</h2>
            <p>Watch our artisans create a piece from start to finish</p>
        </div>
    </div>
</section>

<!-- Design Process -->
<section class="design-process">
    <div class="section-header">
        <h2>Our Design Process</h2>
        <p>Every piece begins with inspiration and ends with perfection</p>
    </div>
    <div class="process-steps">
        <div class="step">
            <div class="step-image">
                <img src="Images/step4.jpg" alt="Concept Sketch">
            </div>
            <h3>Concept</h3>
            <p>Ideation and initial sketches</p>
        </div>
        <div class="step">
            <div class="step-image">
                <img src="Images/step3.jpg" alt="Material Selection">
            </div>
            <h3>Materials</h3>
            <p>Selecting the finest gems and metals</p>
        </div>
        <div class="step">
            <div class="step-image">
                <img src="Images/step5.jpg" alt="Crafting">
            </div>
            <h3>Crafting</h3>
            <p>Hand fabrication by master jewelers</p>
        </div>
        <div class="step">
            <div class="step-image">
                <img src="Images/step6.jpg" alt="Quality Control">
            </div>
            <h3>Quality</h3>
            <p>Rigorous inspection and polishing</p>
        </div>
    </div>
</section>

<!-- Our Team - Compact Row Layout -->
<section class="our-team">
    <div class="section-header">
        <h2>Meet Our Designers</h2>
        <p>The creative minds behind our collections</p>
    </div>
    <div class="team-row">
        <div class="designer-card">
            <div class="designer-image">
                <img src="Images/des1.jpg" alt="Jennifer Robinson">
            </div>
            <div class="designer-info">
                <h3>Jennifer Robinson</h3>
                <p>Creative Director</p>
                <div class="designer-social">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
        
        <div class="designer-card">
            <div class="designer-image">
                <img src="Images/des2.jpg" alt="June Anderson">
            </div>
            <div class="designer-info">
                <h3>June Anderson</h3>
                <p>Lead Designer</p>
                <div class="designer-social">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
        
        <div class="designer-card">
            <div class="designer-image">
                <img src="Images/des3.jpg" alt="Kate Taylor">
            </div>
            <div class="designer-info">
                <h3>Kate Taylor</h3>
                <p>Jewelry Specialist</p>
                <div class="designer-social">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Instagram Gallery -->
<section class="instagram-gallery">
    <div class="section-header">
        <h2>Follow Our Journey</h2>
        <p>@Aura&Co on Instagram</p>
    </div>
    <div class="gallery-grid">
        <img src="Images/instagram1.jpeg" alt="Instagram 1">
        <img src="Images/instagram2.jpeg" alt="Instagram 2">
        <img src="Images/instagram3.jpeg" alt="Instagram 3">
        <img src="Images/instagram4.jpeg" alt="Instagram 4">
        <img src="Images/instagram5.jpeg" alt="Instagram 5">
        <img src="Images/instagram6.jpeg" alt="Instagram 6">
        <img src="Images/instagram7.jpeg" alt="Instagram 7">
        <img src="Images/instagram8.jpeg" alt="Instagram 8">
        <img src="Images/instagram9.jpeg" alt="Instagram 9">
        <img src="Images/instagram10.jpeg" alt="Instagram 10">
        <img src="Images/instagram11.jpeg" alt="Instagram 11">
        <img src="Images/instagram12.jpeg" alt="Instagram 12">
        <img src="Images/instagram13.jpeg" alt="Instagram 13">
        <img src="Images/instagram14.jpeg" alt="Instagram 14">
        <img src="Images/instagram15.jpeg" alt="Instagram 15">
        <img src="Images/instagram16.jpeg" alt="Instagram 16">
        <img src="Images/instagram17.jpeg" alt="Instagram 17">
        <img src="Images/instagram18.jpeg" alt="Instagram 18">
        <img src="Images/instagram19.jpeg" alt="Instagram 19">
        <img src="Images/instagram20.jpeg" alt="Instagram 20">
        <img src="Images/instagram21.jpeg" alt="Instagram 21">
        <img src="Images/instagram22.jpeg" alt="Instagram 22">
        <img src="Images/instagram23.jpeg" alt="Instagram 23">
        <img src="Images/instagram24.jpeg" alt="Instagram 24">
        <img src="Images/instagram25.jpeg" alt="Instagram 25">
        <img src="Images/instagram26.jpg" alt="Instagram 26">
        <img src="Images/instagram27.jpg" alt="Instagram 27">
        <img src="Images/instagram28.jpg" alt="Instagram 28">
        <img src="Images/instagram29.jpg" alt="Instagram 29">
        <img src="Images/instagram30.jpg" alt="Instagram 30">
        <img src="Images/instagram31.jpg" alt="Instagram 31">
        <img src="Images/instagram32.jpg" alt="Instagram 32">
    </div>
</section>

<!-- Footer -->
<?php include 'footer.php'; ?>
<script src="script.js"></script>

</body>
</html>