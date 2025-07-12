<?php
require_once 'includes/session.php';
require_once 'includes/header.php';
require_once 'functions/functions.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cart_items = getCartItems($conn, $_SESSION['user_id']);
$total = getCartTotal($conn, $_SESSION['user_id']);

if(empty($cart_items)) {
    header("Location: menu.php");
    exit();
}

if(isset($_POST['checkout'])) {
    try {
        $conn->beginTransaction();
        
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $order_id = $conn->lastInsertId();
        
        foreach($cart_items as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, item_price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['food_id'], $item['quantity'], $item['price']]);
        }
        
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        $conn->commit();
        
        header("Location: order_confirmation.php?order_id=$order_id");
        exit();
    } catch(PDOException $e) {
        $conn->rollBack();
        $error = "Checkout failed: " . $e->getMessage();
    }
}
?>

<style>
    .video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        object-fit: cover;
 
    }
    .checkout-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .checkout-header {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    .order-section, .payment-section {
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .section-title {
        font-size: 20px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    .order-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .order-item:last-child {
        border-bottom: none;
    }
    .order-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        margin-right: 15px;
    }
    .order-item-details {
        flex-grow: 1;
    }
    .order-item-name {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .order-item-price {
        color: #666;
    }
    .order-summary {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .summary-label {
        font-weight: bold;
    }
    .total-row {
        font-size: 18px;
        font-weight: bold;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    .payment-form {
        margin-top: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .form-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    .submit-btn {
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .submit-btn:hover {
        background-color: #45a049;
    }
    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
        text-align: center;
    }
    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<video autoplay muted loop class="video-background">
    <source src="assets/bg/bg5.webm" type="video/webm">
</video>

<div class="checkout-container">
    <h2 class="checkout-header">Checkout</h2>

    <?php if(isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="checkout-grid">
        <div class="order-section">
            <h3 class="section-title">Your Order</h3>
            
            <?php foreach($cart_items as $item): ?>
                <div class="order-item">
                    <img src="<?php echo $item['image_url']; ?>" class="order-item-image">
                    <div class="order-item-details">
                        <h4 class="order-item-name"><?php echo $item['name']; ?></h4>
                        <p class="order-item-price">$<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></p>
                        <p>Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="order-summary">
                <div class="summary-row">
                    <span class="summary-label">Total Items:</span>
                    <span><?php echo count($cart_items); ?></span>
                </div>
                <div class="summary-row total-row">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
        </div>
        
        <div class="payment-section">
            <h3 class="section-title">Payment Information</h3>
            
            <form action="checkout.php" method="post" class="payment-form">
                <div class="form-group">
                    <label class="form-label">Card Number</label>
                    <input type="text" name="card_number" class="form-input" placeholder="1234 5678 9012 3456" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Cardholder Name</label>
                    <input type="text" name="card_name" class="form-input" placeholder="John Doe" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label class="form-label">Expiry (MM/YY)</label>
                        <input type="text" name="expiry" class="form-input" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">CVV</label>
                        <input type="text" name="cvv" class="form-input" placeholder="123" required>
                    </div>
                </div>
                
                <button type="submit" name="checkout" class="submit-btn">Place Order</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>