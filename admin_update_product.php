<?php
// Include database configuration
@include 'config.php';

// Start session and check if admin is logged in
session_start();
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
if (!isset($admin_id)) {
    header('location:login.php');
    exit();
}

// Get product ID to update from query string
$update_id = isset($_GET['update']) ? $_GET['update'] : null;
if (!$update_id) {
    header('location:admin_products.php');
    exit();
}

// Query product details for the update form
$select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
$select_product->execute([$update_id]);
$product = $select_product->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    header('location:admin_products.php');
    exit();
}

// Handle product update form submission
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $details = $_POST['details'];
    $category = $_POST['category'];
    // Update product in database
    $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ?, category = ? WHERE id = ?");
    $update_product->execute([$name, $price, $details, $category, $update_id]);
    $message[] = 'Product updated successfully!';
    // Optionally, handle image update here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - Aura & Co</title>
    <!-- Main site styles -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Didot:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Admin header -->
    <?php include 'admin_header.php'; ?>

    <!-- Show feedback messages -->
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
        }
    }
    ?>

    <!-- Update Product Form -->
    <section class="admin-update-product-section">
        <div class="section-header">
            <h2>Update Product</h2>
        </div>
        <form action="" method="POST" class="admin-update-form">
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
            <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>
            <textarea name="details" required><?= htmlspecialchars($product['details']); ?></textarea>
            <input type="text" name="category" value="<?= htmlspecialchars($product['category']); ?>" required>
            <button type="submit" name="update_product" class="update-btn">Update Product</button>
        </form>
    </section>

    <!-- Admin footer -->
    <?php include 'footer.php'; ?>
</body>
</html>

