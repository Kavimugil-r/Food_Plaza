<?php 
require_once '../includes/config.php';

if(isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if(empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../register.php");
        exit();
    }
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash]);
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: ../login.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Registration failed: " . $e->getMessage();
        header("Location: ../register.php");
        exit();
    }
}

if(isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password!";
            header("Location: ../login.php");
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Login failed: " . $e->getMessage();
        header("Location: ../login.php");
        exit();
    }
}
?>