<?php
require_once '../includes/session.php';
require_once '../functions/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_time DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<style>
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        color: #fff;
        height: 100%;
        overflow-x: hidden;
        position: relative;
    }

    .video-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        object-fit: cover;
        z-index: -1;
        filter: brightness(0.6);
    }

    .content-wrapper {
        max-width: 900px;
        margin: 60px auto;
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.6);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2rem;
        color: #ffd700;
    }

    .order-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: 0.3s ease;
    }

    .order-card:hover {
        transform: scale(1.02);
        background: rgba(255, 255, 255, 0.15);
    }

    .order-card h3 {
        margin: 0 0 10px;
        color: #00ffcc;
    }

    .order-card p {
        margin: 5px 0;
    }

    .order-card a {
        display: inline-block;
        margin-top: 10px;
        color: #00d4ff;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s;
    }

    .order-card a:hover {
        color: #ffcc00;
    }

    .empty-message {
        text-align: center;
        font-size: 1.2rem;
    }

    .empty-message a {
        color: #ffcc00;
        text-decoration: none;
        font-weight: bold;
    }

    .empty-message a:hover {
        color: #ffffff;
    }
</style>

<video autoplay muted loop class="video-bg">
    <source src="../assets/bg/bg5.webm" type="video/webm">
    Your browser does not support the video tag.
</video>

<div class="content-wrapper">
    <h2>Your Orders</h2>

    <?php if (empty($orders)): ?>
        <p class="empty-message">
            You haven't placed any orders yet. <a href="../menu.php">Browse our menu</a>
        </p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <h3>Order #<?php echo $order['order_id']; ?></h3>
                <p>Status: <strong><?php echo ucfirst($order['order_status']); ?></strong></p>
                <p>Date: <?php echo date('M j, Y g:i A', strtotime($order['order_time'])); ?></p>
                <p>Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
                <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>">➤ View Details</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
