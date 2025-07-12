<?php
require_once '../includes/session.php';
require_once '../functions/functions.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    if($stmt->execute([$cart_id, $_SESSION['user_id']])) {
        $success = "Item removed from cart!";
    } else {
        $error = "Failed to remove item!";
    }
    header("Location: cart.php");
    exit();
}

if(isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    
    if($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
        if($stmt->execute([$quantity, $cart_id, $_SESSION['user_id']])) {
            $success = "Cart updated!";
        } else {
            $error = "Failed to update cart!";
        }
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        if($stmt->execute([$cart_id, $_SESSION['user_id']])) {
            $success = "Item removed from cart!";
        } else {
            $error = "Failed to remove item!";
        }
    }
    header("Location: cart.php");
    exit();
}

$cart_items = getCartItems($conn, $_SESSION['user_id']);
$total = getCartTotal($conn, $_SESSION['user_id']);

require_once '../includes/header.php';
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
    .cart-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .cart-header {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    .cart-item {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .cart-item:hover {
        transform: translateY(-3px);
    }
    .item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 5px;
        margin-right: 20px;
    }
    .item-details {
        flex-grow: 1;
    }
    .item-name {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }
    .item-price, .item-subtotal {
        color: #666;
        margin-bottom: 5px;
    }
    .quantity-form {
        display: flex;
        align-items: center;
    }
    .quantity-input {
        width: 60px;
        padding: 5px;
        margin-right: 10px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .update-btn, .remove-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-right: 10px;
    }
    .update-btn {
        background-color: #4CAF50;
        color: white;
    }
    .remove-btn {
        background-color: #f44336;
        color: white;
        text-decoration: none;
        display: inline-block;
        padding: 5px 10px;
    }
    .cart-summary {
        text-align: right;
        margin-top: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .total-price {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .checkout-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .checkout-btn:hover {
        background-color: #0056b3;
    }
    .empty-cart {
        text-align: center;
        padding: 40px;
        font-size: 18px;
    }
    .empty-cart a {
        color: #007bff;
        text-decoration: none;
    }
    .empty-cart a:hover {
        text-decoration: underline;
    }
    .alert {
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 4px;
        text-align: center;
    }
    .success {
        background-color: #d4edda;
        color: #155724;
    }
    .error {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<video autoplay muted loop class="video-background">
    <source src="../assets/bg/bg5.webm" type="video/webm">
</video>

<div class="cart-container">
    <h2 class="cart-header">Your Shopping Cart</h2>

    <?php if(isset($success)): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(empty($cart_items)): ?>
        <div class="empty-cart">
            <p>Your cart is empty. <a href="../menu.php">Browse our menu</a> to add delicious items!</p>
        </div>
    <?php else: ?>
        <?php foreach($cart_items as $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['image_url']; ?>" class="item-image">
                <div class="item-details">
                    <h4 class="item-name"><?php echo $item['name']; ?></h4>
                    <p class="item-price">Price: $<?php echo number_format($item['price'], 2); ?></p>
                    <p class="item-subtotal">Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                </div>
                <form action="cart.php" method="post" class="quantity-form">
                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                    <button type="submit" name="update_quantity" class="update-btn">Update</button>
                    <a href="cart.php?remove=<?php echo $item['cart_id']; ?>" class="remove-btn">Remove</a>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="cart-summary">
            <p class="total-price">Total: $<?php echo number_format($total, 2); ?></p>
            <a href="../checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>