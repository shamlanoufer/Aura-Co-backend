<?php
@include 'config.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if(isset($_POST['send'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);
      $message[] = 'sent message successfully!';
   }
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$cartCount = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Aura&Co</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="custom-bg">
 
    <!-- Navigation Bar -->
    <?php include 'header.php'; ?>
    <!-- Contact Us Section -->
    <section class="contact-section">
        <div class="section-header">
            <h2>Contact Our Team</h2>
            <p>We'd love to hear from you</p>
        </div>
        
        <?php
        if(isset($message)){
            foreach($message as $message){
                echo '
                <div class="message">
                    <span>'.$message.'</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
                ';
            }
        }
        ?>
        
        <div class="contact-container">
            <!-- Contact Form -->
            <form class="contact-form" action="" method="POST">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Full name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="yourname@email.com" required>
                </div>
                <div class="form-group">
                    <input type="number" name="number" min="0" placeholder="Phone number" required>
                </div>
                <div class="form-group">
                    <textarea name="msg" placeholder="Your message..." required></textarea>
                </div>
                <button type="submit" class="submit-btn" name="send">Send Message <i class="fas fa-paper-plane"></i></button>
            </form>

            <!-- Image Grid -->
            <div class="contact-images">
                <div class="image-grid">
                    <img src="Images/contact1.jpeg" alt="Our Workshop">
                    <img src="Images/contact2.jpeg" alt="Jewelry Crafting">
                    <img src="Images/contact3.jpeg" alt="Team Meeting">
                    <img src="Images/contact4.jpeg" alt="Customer Service">
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="contact-details">
            <div class="detail-card">
                <i class="fas fa-envelope"></i>
                <h3>Email Us</h3>
                <p>auraco@yahoomail.com</p>
            </div>
            <div class="detail-card">
                <i class="fas fa-phone"></i>
                <h3>Call Us</h3>
                <p>+123 456 7890</p>
                <p>+987 654 3210</p>
            </div>
            <div class="detail-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Visit Us</h3>
                <p>123 Jewelry Lane<br>New York, NY 10001</p>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="customer-reviews">
        <div class="section-header">
            <h2>What Our Customers Say</h2>
            <p>Hear from those who have experienced Aura & Co</p>
        </div>
        
        <div class="reviews-container">
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-image">
                        <img src="Images/reviewer1.jpeg" alt="Sarah Johnson">
                    </div>
                    <div class="reviewer-info">
                        <h3>Sarah Johnson</h3>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="review-content">
                    <p>"The necklace I purchased is absolutely stunning! The craftsmanship is exceptional and it arrived beautifully packaged. Will definitely shop here again!"</p>
                    <span class="review-date">May 15, 2023</span>
                </div>
            </div>
            
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-image">
                        <img src="Images/reviewer2.jpeg" alt="Michael Chen">
                    </div>
                    <div class="reviewer-info">
                        <h3>Michael Chen</h3>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="review-content">
                    <p>"I bought the engagement ring for my fiancée and she loves it. The quality exceeded my expectations and customer service was very helpful throughout the process."</p>
                    <span class="review-date">March 2, 2023</span>
                </div>
            </div>
            
            <div class="review-card">
                <div class="review-header">
                    <div class="reviewer-image">
                        <img src="Images/reviewer3.jpeg" alt="Emma Rodriguez">
                    </div>
                    <div class="reviewer-info">
                        <h3>Emma Rodriguez</h3>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <div class="review-content">
                    <p>"The earrings are even more beautiful in person than in the photos. They've become my favorite piece of jewelry. Fast shipping too!"</p>
                    <span class="review-date">January 28, 2023</span>
                </div>
            </div>
        </div>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="script.js"></script>


</body>
</html>