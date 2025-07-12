<?php
require_once '../includes/session.php';
require_once '../includes/config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($oldPassword, $user['password_hash'])) {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        $updateStmt->execute([$newHash, $userId]);
        $_SESSION['success'] = "Password changed successfully!";
    } else {
        $_SESSION['error'] = "Incorrect old password!";
    }
    header("Location: profile.php");
    exit();
}
?>