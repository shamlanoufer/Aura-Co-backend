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

// Handle message deletion
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']); // Ensure only integer ID is used
    echo "<!-- Debug: Raw delete parameter: " . $_GET['delete'] . " -->"; // Debug output
    echo "<!-- Debug: Attempting to delete message ID: $delete_id -->"; // Debug output
    
    if ($delete_id > 0) {
        try {
            // Delete only the selected message (using correct table name 'message')
            $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ? LIMIT 1");
            $result = $delete_message->execute([$delete_id]);
            
            if ($result) {
                $feedback_message[] = 'Message deleted successfully!';
            } else {
                $feedback_message[] = 'Failed to delete message!';
            }
        } catch (Exception $e) {
            $feedback_message[] = 'Error: ' . $e->getMessage();
        }
    } else {
        $feedback_message[] = 'Invalid message ID: ' . $delete_id;
    }
    // Temporarily comment out redirect to see messages
    // header('location:admin_contacts.php');
    // exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Contacts - Aura & Co</title>
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
    if (isset($feedback_message)) {
        foreach ($feedback_message as $msg) {
            echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
        }
    }
    ?>

    <!-- Messages Management Section -->
    <section class="admin-messages-section">
        <div class="section-header">
            <h2>Contact Messages</h2>
        </div>
        <div class="admin-messages-grid">
            <?php
            // Query all messages from the database (using correct table name 'message')
            $select_messages = $conn->prepare("SELECT * FROM `message`");
            $select_messages->execute();
            if ($select_messages->rowCount() > 0) {
                while ($message_item = $select_messages->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <!-- Message Card -->
            <div class="admin-message-card">
                <h3><?= htmlspecialchars($message_item['name']); ?></h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($message_item['email']); ?></p>
                <p><strong>Message:</strong> <?= htmlspecialchars($message_item['message']); ?></p>
                <p><strong>ID:</strong> <?= htmlspecialchars($message_item['id']); ?></p> <!-- Debug: Show message ID -->
                <a href="admin_contacts.php?delete=<?= htmlspecialchars($message_item['id']); ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No messages found.</p>';
            }
            ?>
        </div>
    </section>

    <!-- Admin footer -->
    <?php include 'footer.php'; ?>
</body>
</html>