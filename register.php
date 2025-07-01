<?php
session_start();
include 'config.php';

$message = []; // Initialize message array

if(isset($_POST['submit'])) {
    $user_type = $_POST['user_type'];
    $name = trim($_POST['name']);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    
    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    $pass = md5($_POST['pass']);
    $cpass = md5($_POST['cpass']);
    
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;

    // Validate inputs
    if(empty($name) || empty($email) || empty($pass)) {
        $message[] = 'All fields are required!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format!';
    } else {
        // Check if email already exists
        $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select->execute([$email]);

        if($select->rowCount() > 0) {
            $message[] = 'Email already exists!';
        } elseif($pass != $cpass) {
            $message[] = 'Passwords do not match!';
        } elseif($image_size > 2000000) {
            $message[] = 'Image size is too large (max 2MB)!';
        } else {
            // Insert new user
            try {
                $conn->beginTransaction();
                
                $insert = $conn->prepare("INSERT INTO `users`(name, email, password, user_type, image) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([$name, $email, $pass, $user_type, $image]);
                
                if($insert->rowCount() > 0) {
                    if(move_uploaded_file($image_tmp_name, $image_folder)) {
                        $conn->commit();
                        $_SESSION['success_message'] = 'Registered successfully!';
                        header('Location: login.php');
                        exit();
                    } else {
                        throw new Exception('Image upload failed!');
                    }
                } else {
                    throw new Exception('Registration failed!');
                }
            } catch(Exception $e) {
                $conn->rollBack();
                $message[] = $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- css file link -->
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<?php
// Display messages
if(!empty($message)) {
    foreach($message as $msg) {
        echo '
        <div class="message">
            <span>'.$msg.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>';
    }
}
?>

<section class="form-container">
    <form action="" method="POST" enctype="multipart/form-data">
        <a href="#" class="logo">
            <img src="Logo/aura-co-logo.svg" alt="Company Logo">
        </a>
        <h3>Register Now</h3>
        
        <div class="input-group">
            <input type="text" name="name" class="box" placeholder="Enter your name" required 
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>
        
        <div class="input-group">
            <input type="email" name="email" class="box" placeholder="Enter your email" required
                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        
        <div class="input-group">
            <input type="password" name="pass" class="box" placeholder="Enter your password" required>
        </div>
        
        <div class="input-group">
            <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
        </div>

        <div class="input-group">
            <select name="user_type" class="box" required>
                <option value="" disabled selected>Select user type</option>
                <option value="user" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo (isset($_POST['user_type']) && $_POST['user_type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>
        
        <div class="input-group">
            <label for="image"></label>
            <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
        </div>
        
        <input type="submit" value="Register now" class="btn" name="submit">
        <p>Already have an account? <a href="login.php">Login now</a></p>
    </form>
</section>

<script>
    // Remove messages after 5 seconds
    setTimeout(() => {
        const messages = document.querySelectorAll('.message');
        messages.forEach(msg => {
            msg.remove();
        });
    }, 5000);
</script>

</body>
</html>