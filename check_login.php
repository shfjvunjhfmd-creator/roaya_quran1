<?php
session_start();
include "includes/config.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// جلب المستخدم
$q = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($q);

if($user && password_verify($password, $user['password'])){
    $_SESSION['user'] = $user['username'];
    header("Location: dashboard.php");
    exit;
}else{
    header("Location: login.php?error=1");
    exit;
}