<?php
require_once '../includes/session.php';
require_once '../includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);

if(empty($username) || empty($email)) {
    $_SESSION['error'] = "Both fields are required!";
    header("Location: profile.php");
    exit();
}

$stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
if($stmt->execute([$username, $email, $_SESSION['user_id']])) {
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "Profile updated!";
} else {
    $_SESSION['error'] = "Failed to update profile.";
}
header("Location: profile.php");
exit();
?>