<?php
require_once '../includes/session.php';
require_once '../includes/config.php';
require_once '../functions/functions.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "<p>Order ID not specified.</p>";
    require_once '../includes/footer.php';
    exit();
}

$order_id = intval($_GET['order_id']);

$stmt = $conn->prepare("
    SELECT o.order_id, o.total_amount, o.order_time, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    WHERE o.order_id = ? AND o.user_id = ?
");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "<p>Order not found or access denied.</p>";
    require_once '../includes/footer.php';
    exit();
}

$stmt = $conn->prepare("
    SELECT oi.food_id, oi.quantity, oi.item_price, f.name, f.image_url
    FROM order_items oi
    JOIN food_items f ON oi.food_id = f.food_id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body, html {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        color: #fff;
        height: 100%;
        overflow-x: hidden;
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

    h2, h3 {
        text-align: center;
        color: #ffd700;
    }

    .order-summary {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .order-summary p {
        margin: 10px 0;
    }

    .item-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background-color: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        transition: 0.3s;
    }

    .item-card:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: scale(1.02);
    }

    .item-card img {
        width: 100px;
        height: auto;
        border-radius: 10px;
    }

    .item-info h4 {
        margin: 0;
        color: #00ffcc;
    }

    .item-info p {
        margin: 5px 0;
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        color: #00d4ff;
        text-decoration: none;
        font-weight: bold;
    }

    .back-link:hover {
        color: #ffcc00;
    }

    .no-items {
        text-align: center;
        font-size: 1.2rem;
        margin-top: 20px;
    }
</style>

<video autoplay muted loop class="video-bg">
    <source src="../assets/bg/bg5.webm" type="video/webm">
    Your browser does not support the video tag.
</video>

<div class="content-wrapper">
    <h2>Order Details</h2>

    <div class="order-summary">
        <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
        <p><strong>Order Date:</strong> <?php echo date('M j, Y g:i A', strtotime($order['order_time'])); ?></p>
        <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
    </div>

    <h3>Items Ordered</h3>

    <?php if (empty($items)): ?>
        <p class="no-items">No items found in this order.</p>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div class="item-card">
                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="item-info">
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                    <p>Price Each: $<?php echo number_format($item['item_price'], 2); ?></p>
                    <p>Subtotal: $<?php echo number_format($item['item_price'] * $item['quantity'], 2); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a class="back-link" href="orders.php">← Back to My Orders</a>
</div>

<?php require_once '../includes/footer.php'; ?>
