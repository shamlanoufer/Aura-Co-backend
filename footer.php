<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <h3>
                <svg viewBox="0 0 300 40" width="150" xmlns="http://www.w3.org/2000/svg">
                    <text x="0" y="25" font-family="'Didot', serif" font-size="20" font-weight="400" letter-spacing="4" fill="#ffffff">
                        A U R A &amp; C O
                    </text>
                </svg>
            </h3>
            <p>© <?= date("Y") ?> Aura&Co, Inc. All rights reserved.</p>
        </div>

        <div class="footer-links">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="collections.php">Collections</a></li>
            </ul>
            <ul>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </div>

        <div class="footer-newsletter">
            <h4>Subscribe to our newsletter</h4>
            <p>For product announcements and exclusive insights</p>

            <!-- Newsletter form -->
            <form class="newsletter-form" method="POST">
                <input type="email" name="email" placeholder="Your email address" required>
                <button type="submit" name="subscribe">Subscribe</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['subscribe'])) {
                try {
                    // Include database connection
                    @include 'config.php';
                    
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Check if email already exists
                        $check_email = $conn->prepare("SELECT * FROM `newsletter` WHERE email = ?");
                        $check_email->execute([$email]);
                        
                        if($check_email->rowCount() > 0) {
                            echo "<p style='color: #ff9999; margin-top: 10px;'>This email is already subscribed.</p>";
                        } else {
                            // Insert new subscriber
                            $insert_subscriber = $conn->prepare("INSERT INTO `newsletter` (email) VALUES (?)");
                            $insert_subscriber->execute([$email]);
                            echo "<p style='color: lightgreen; margin-top: 10px;'>Thank you for subscribing!</p>";
                        }
                    } else {
                        echo "<p style='color: #ff9999; margin-top: 10px;'>Invalid email address.</p>";
                    }
                } catch(PDOException $e) {
                    // Log the error instead of showing it to users
                    error_log("Newsletter subscription error: " . $e->getMessage());
                    echo "<p style='color: #ff9999; margin-top: 10px;'>There was an error processing your subscription. Please try again later.</p>";
                }
            }
            ?>

            <div class="social-icons">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-pinterest"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>