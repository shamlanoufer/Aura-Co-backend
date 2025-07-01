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

// Handle order placement (if form is submitted)
if (isset($_POST['place_order'])) {
    // Get form data
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $address = $_POST['address'];
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    // Insert order into database
    $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
    $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);
    $message[] = 'order placed successfully!';

    // Optionally, clear the cart after order
    $clear_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $clear_cart->execute([$user_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Aura & Co</title>
    <!-- Main site styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/components.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Site header -->
    <?php include 'header.php'; ?>

    <!-- Show feedback messages -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
        }
    }
    ?>

    <!-- Checkout Section -->
    <section class="checkout-section">
        <div class="section-header">
            <h2>Checkout</h2>
            <p>Complete your purchase</p>
        </div>
        <div class="checkout-container">
            <div class="checkout-summary">
                <h3>Order Summary</h3>
                <ul>
                <?php
                // Query cart items for this user only
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                $grand_total = 0;
                $products = [];
                if ($select_cart->rowCount() > 0) {
                    while ($item = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $line_total = $item['price'] * $item['quantity'];
                        $grand_total += $line_total;
                        $products[] = $item['name'] . ' (' . $item['quantity'] . ')';
                        echo '<li>' . htmlspecialchars($item['name']) . ' x ' . htmlspecialchars($item['quantity']) . ' <span>$' . htmlspecialchars($line_total) . '</span></li>';
                    }
                } else {
                    echo '<li>Your cart is empty.</li>';
                }
                ?>
                </ul>
                <div class="checkout-total">
                    <strong>Total: $<?= $grand_total; ?></strong>
                </div>
            </div>
            <div class="checkout-form">
                <h3>Shipping Details</h3>
                <form action="" method="POST">
                    <input type="text" name="name" placeholder="Full Name" required>
                    <input type="text" name="number" placeholder="Phone Number" required>
                    <input type="email" name="email" placeholder="Email Address" required>
                    <input type="text" name="address" placeholder="Shipping Address" required>
                    <select name="method" required>
                        <option value="credit card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                    <input type="hidden" name="total_products" value="<?= htmlspecialchars(implode(', ', $products)); ?>">
                    <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
                    <button type="submit" name="place_order" class="checkout-btn"<?= ($grand_total > 0) ? '' : ' disabled'; ?>>Place Order</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Site footer -->
    <?php include 'footer.php'; ?>
</body>
</html>