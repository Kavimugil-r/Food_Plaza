<?php
require_once __DIR__ . '/../includes/config.php';

function getFoodItems($conn, $available_only = true) {
    $sql = "SELECT * FROM food_items";
    if($available_only) $sql .= " WHERE is_available = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addToCart($conn, $user_id, $food_id, $quantity = 1) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND food_id = ?");
    $stmt->execute([$user_id, $food_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($existing) {
        $new_quantity = $existing['quantity'] + $quantity;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        return $stmt->execute([$new_quantity, $existing['cart_id']]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, food_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $food_id, $quantity]);
    }
}

function getCartItems($conn, $user_id) {
    $stmt = $conn->prepare("SELECT c.*, f.name, f.price, f.image_url FROM cart c JOIN food_items f ON c.food_id = f.food_id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCartTotal($conn, $user_id) {
    $items = getCartItems($conn, $user_id);
    $total = 0;
    foreach($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}
?>