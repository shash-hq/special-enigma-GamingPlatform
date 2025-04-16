<?php
include "config.php";
require 'PHPmailer/PHPMailer.php';
require 'PHPmailer/SMTP.php';
require 'PHPmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

function sendVerificationEmail($email, $v_code) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rajmohan6982@gmail.com'; // Use your email
        $mail->Password   = 'rryuqjotztyuyytc'; // Use app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('your@gmail.com', 'Your App Name');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body    = "Please verify your email: 
            <a href='http://yourdomain.com/login_system/verify.php?email=$email&v_code=$v_code'>
            Verify Email</a>";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $e->getMessage());
        return false;
    }
}

if(isset($_POST['signup'])) {
    $username = $con->real_escape_string($_POST['username']);
    $email = $con->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $v_code = bin2hex(random_bytes(16));

    // Check if user exists
    $check = $con->query("SELECT * FROM users WHERE email='$email' OR username='$username'");
    
    if($check->num_rows > 0) {
        $error = "Username or email already exists";
    } else {
        $insert = $con->query("INSERT INTO users (username, email, password, v_code, is_verified) 
                             VALUES ('$username', '$email', '$password', '$v_code', 0)");
        
        if($insert && sendVerificationEmail($email, $v_code)) {
            $_SESSION['message'] = "Registration successful! Please verify your email.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>