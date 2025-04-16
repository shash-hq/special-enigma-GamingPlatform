<?php
include "config.php";

if(isset($_GET['email']) && isset($_GET['v_code'])) {
    $email = $con->real_escape_string($_GET['email']);
    $v_code = $con->real_escape_string($_GET['v_code']);
    
    $result = $con->query("SELECT * FROM users WHERE email='$email' AND v_code='$v_code'");
    
    if($result->num_rows == 1) {
        $con->query("UPDATE users SET is_verified=1 WHERE email='$email'");
        $_SESSION['message'] = "Email verified successfully! You can now login.";
        header("Location: login.php");
    } else {
        $_SESSION['message'] = "Invalid verification link";
        header("Location: login.php");
    }
    exit();
}
?>