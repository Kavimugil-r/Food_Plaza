<?php require_once 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FOOD_PLAZA 🍔🍕🥗</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        header {
            position: sticky;
            top: 0;
            background-color: #ff6600;
            color: white;
            padding: 10px 30px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from { top: -80px; opacity: 0; }
            to { top: 0; opacity: 1; }
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .app-title {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: transform 0.2s ease, color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ffe;
            transform: scale(1.05);
        }

        /* 👇 For Footer Animation CSS code  */

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        footer {
            background: rgba(0, 0, 0, 0.6); /* black transparent */
            color: white;
            text-align: center;
            padding: 12px 20px;
            font-size: 16px;
            width: 100%;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.5);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Remove position sticky/fixed */
        }



        .error { color: red; }
        .success { color: green; }
        .food-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .food-item { border: 1px solid #ccc; padding: 10px; }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="app-title">
                🍽️ FOOD_PLAZA
            </div>
            <nav class="nav-links">
                <a href="../index.php">🏠 Home</a>
                <a href="../menu.php">📋 Menu</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="../user/cart.php">🛒 Cart</a>
                    <a href="../user/profile.php">👤 Profile</a>
                    <a href="../user/orders.php">📦 Orders</a>
                    <a href="../logout.php">🚪 Logout</a>
                <?php else: ?>
                    <a href="../login.php">🔑 Login</a>
                    <a href="../register.php">📝 Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main>
