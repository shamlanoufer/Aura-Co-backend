<?php
@include 'config.php';

session_start();

if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0){
      if($row['user_type'] == 'admin'){
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');
      }elseif($row['user_type'] == 'user'){
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
      }else{
         $message[] = 'no user found!';
      }
   }else{
      $message[] = 'incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - Aura & Co</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/login.css">
</head>
<body>

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

<div class="login-container">
   <div class="login-header">
      <h1>Welcome Back</h1>
      <p>Sign in to your Aura & Co account</p>
   </div>
   
   <form action="" method="POST" class="login-form">
      <div class="form-group">
         <label for="email">Email Address</label>
         <input type="email" id="email" name="email" required>
      </div>
      
      <div class="form-group">
         <label for="password">Password</label>
         <input type="password" id="password" name="pass" required>
      </div>
      
      <button type="submit" name="submit" class="signin-btn">Sign In</button>
      
      <p class="register-link">Don't have an account? <a href="register.php">Register Now</a></p>
   </form>
</div>

</body>
</html>