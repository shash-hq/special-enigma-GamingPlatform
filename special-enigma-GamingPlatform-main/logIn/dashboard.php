<?php
include "config.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, ".$_SESSION['username']."!";
echo "<a href='logout.php'>Logout</a>";
?>