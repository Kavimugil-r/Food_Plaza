<?php 
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/functions/functions.php';

if(isset($_GET['add_to_cart']) && isset($_SESSION['user_id'])) {
    $food_id = $_GET['add_to_cart'];
    if(addToCart($conn, $_SESSION['user_id'], $food_id)) {
        $success = "Item added to cart!";
    } else {
        $error = "Failed to add item to cart!";
    }
    header("Location: ../menu.php");
    exit();
}

$food_items = getFoodItems($conn);
?>

<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }

    /* Background video */
    .video-bg {
        position: fixed;
        top: 0;
        left: 0;
        min-width: 100%;
        min-height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    /* Overlay container */
    .menu-container {
        position: relative;
        padding: 50px 20px;
        background: rgba(0, 0, 0, 0.6);
        min-height: 100vh;
        color: #fff;
        animation: fadeIn 1.5s ease-in-out;
    }

    h2 {
        text-align: center;
        font-size: 3rem;
        margin-bottom: 40px;
        animation: slideDown 1s ease-out;
    }

    .success, .error {
        text-align: center;
        margin-bottom: 20px;
        padding: 10px 20px;
        border-radius: 10px;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
        animation: fadeIn 1s ease-in-out;
    }

    .success { background-color: #28a745; color: white; }
    .error { background-color: #dc3545; color: white; }

    .food-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: auto;
    }

    .food-item {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        backdrop-filter: blur(8px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: popUp 0.7s ease;
    }

    .food-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 15px;
    }

    .food-item h4 {
        margin: 15px 0 5px;
        font-size: 1.5rem;
    }

    .food-item p {
        margin: 5px 0;
    }

    .food-item a {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #ff6f00;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        transition: background-color 0.3s ease;
    }

    .food-item a:hover {
        background-color: #ff9000;
    }

    .food-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes popUp {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @media (max-width: 600px) {
        h2 { font-size: 2rem; }
    }
</style>

<!-- Background Video -->
<video class="video-bg" autoplay muted loop>
    <source src="../assets/bg/bg2.webm" type="video/webm">
    Your browser does not support the video tag.
</video>

<div class="menu-container">
    <h2>Our Menu</h2>

    <?php if(isset($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="food-grid">
        <?php foreach($food_items as $item): ?>
        <div class="food-item">
            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
            <h4><?php echo $item['name']; ?></h4>
            <p><?php echo $item['description']; ?></p>
            <p><strong>$<?php echo $item['price']; ?></strong></p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="../menu.php?add_to_cart=<?php echo $item['food_id']; ?>">Add to Cart</a>
            <?php else: ?>
                <a href="../login.php">Login to Order</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
