<?php
include "config.php";

function sendLoginNotification($email) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your@gmail.com';
        $mail->Password   = 'your-app-password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('your@gmail.com', 'Your App Name');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Successful Login';
        $mail->Body    = "You have successfully logged in to your account on " . date('Y-m-d H:i:s');
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $e->getMessage());
        return false;
    }
}

if(isset($_POST['login'])) {
    $email = $con->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $con->query("SELECT * FROM users WHERE email='$email'");
    
    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if(password_verify($password, $user['password'])) {
            if($user['is_verified'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Send login notification
                sendLoginNotification($email);
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Please verify your email first";
            }
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Email not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if(isset($_SESSION['message'])) { 
            echo "<p class='success'>".$_SESSION['message']."</p>";
            unset($_SESSION['message']);
        } ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
</body>
</html>