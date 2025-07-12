<?php 
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/header.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];
?>

<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow: hidden;
    }

    .background-video {
        position: fixed;
        top: 0;
        left: 0;
        min-width: 100%;
        min-height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    .confirmation-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.85);
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
        max-width: 500px;
    }

    .confirmation-container h2 {
        margin-top: 0;
        font-size: 2em;
        color: #333;
    }

    .confirmation-container h3 {
        margin-bottom: 10px;
        color: #27ae60;
    }

    .confirmation-container p {
        font-size: 1.2em;
        color: #444;
    }

    .confirmation-container a {
        display: inline-block;
        margin: 15px 10px 0;
        padding: 10px 20px;
        text-decoration: none;
        background-color: #3498db;
        color: white;
        border-radius: 8px;
        transition: background 0.3s ease;
    }

    .confirmation-container a:hover {
        background-color: #2980b9;
    }
</style>

<video autoplay muted loop class="background-video">
    <source src="assets/bg/bg5.webm" type="video/webm">
    Your browser does not support the video tag.
</video>

<div class="confirmation-container">
    <h2>Order Confirmation</h2>
    <h3>Thank you for your order!</h3>
    <p>Your order #<?php echo htmlspecialchars($order_id); ?> has been placed successfully.</p>
    <a href="user/orders.php">View Your Orders</a>
    <a href="menu.php">Continue Shopping</a>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
