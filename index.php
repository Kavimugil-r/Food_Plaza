<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/functions/functions.php';

$food_items = getFoodItems($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAVIMUGIL - Food Ordering System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #ff6b6b;
            --secondary: #4ecdc4;
            --dark: #292f36;
            --light: #f7fff7;
            --accent: #ff9f1c;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--light);
            overflow-x: hidden;
            background-color: var(--dark);
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .video-background video {
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            
        }

        .video-background .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(41, 47, 54, 0.7);
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }
        
        /* Animated Hero Section */
        .hero {
            text-align: center;
            padding: 150px 20px 100px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: var(--primary);
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: var(--secondary);
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .hero h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--primary);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            animation: fadeInDown 1s ease-out;
        }
        
        .hero p {
            font-size: 1.5rem;
            max-width: 800px;
            margin: 0 auto 30px;
            animation: fadeInUp 1s ease-out 0.3s both;
        }
        
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            animation: pulse 2s infinite 1s;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--accent), var(--primary));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .btn:hover::before {
            opacity: 1;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Animated Food Grid */
        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin: 80px 0;
            perspective: 1000px;
        }
        
        .food-item {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: var(--dark);
            transform-style: preserve-3d;
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .food-item:nth-child(1) { animation-delay: 0.1s; }
        .food-item:nth-child(2) { animation-delay: 0.2s; }
        .food-item:nth-child(3) { animation-delay: 0.3s; }
        .food-item:nth-child(4) { animation-delay: 0.4s; }
        .food-item:nth-child(5) { animation-delay: 0.5s; }
        .food-item:nth-child(6) { animation-delay: 0.6s; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .food-item:hover {
            transform: translateY(-10px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .food-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .food-item:hover img {
            transform: scale(1.05);
        }
        
        .food-item-content {
            padding: 25px;
            position: relative;
        }
        
        .food-item h4 {
            margin: 0 0 10px;
            color: var(--primary);
            font-size: 1.3rem;
        }
        
        .food-item .description {
            margin: 10px 0;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .food-item .price {
            font-weight: bold;
            color: var(--secondary);
            font-size: 1.3rem;
            margin: 15px 0;
            display: block;
        }
        
        .food-item a {
            display: inline-block;
            margin-top: 10px;
            background: var(--secondary);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .food-item a:hover {
            background: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* About Section with Animation */
        .about-section {
            background: rgba(255,255,255,0.95);
            padding: 60px;
            border-radius: 15px;
            margin: 80px 0;
            color: var(--dark);
            transform-style: preserve-3d;
            animation: fadeInUp 1s ease-out 0.5s both;
        }
        
        .about-section h2 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .about-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }
        
        .about-section p {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 40px;
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .feature {
            text-align: center;
            padding: 30px;
            background: rgba(78, 205, 196, 0.1);
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(78, 205, 196, 0.2);
        }
        
        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            background: rgba(78, 205, 196, 0.15);
        }
        
        .feature i {
            font-size: 3rem;
            color: var(--secondary);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .feature:hover i {
            transform: scale(1.2);
            color: var(--primary);
        }
        
        .feature h3 {
            color: var(--primary);
            font-size: 1.4rem;
            margin-bottom: 15px;
        }
        
        .feature p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }
        
        /* Floating Animation for Special Items */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.2rem;
            }
            
            .about-section {
                padding: 30px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="video-background">
        <video autoplay muted loop>
            <source src="assets/bg/bg1.webm" type="video/webm">
            Your browser does not support the video tag.
        </video>
        <div class="overlay"></div>
    </div>

    <div class="container">
        <section class="hero">
            <h1 class="animate__animated animate__fadeInDown">Welcome to KAVIMUGIL</h1>
            <p class="animate__animated animate__fadeInUp">Discover delicious meals from our menu and order with just a few clicks</p>
            <a href="menu.php" class="btn animate__animated animate__fadeInUp animate__delay-1s">
                <i class="fas fa-utensils"></i> Explore Our Menu
            </a>
        </section>

        <h2 style="text-align: center; color: var(--light); margin-bottom: 30px; font-size: 2.5rem;" 
            class="animate__animated animate__fadeIn">
            Our Signature Dishes
        </h2>

        <div class="food-grid">
            <?php 
            // Display first 6 items as featured
            $featured_items = array_slice($food_items, 0, 6);
            foreach($featured_items as $item): 
            ?>
                <div class="food-item">
                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <div class="food-item-content">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                        <span class="price">$<?php echo number_format($item['price'], 2); ?></span>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="menu.php?add_to_cart=<?php echo $item['food_id']; ?>">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </a>
                        <?php else: ?>
                            <a href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login to Order
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin: 50px 0;">
            <a href="menu.php" class="btn" style="
                display: inline-block;
                padding: 15px 40px;
                background: linear-gradient(45deg, var(--primary), var(--accent));
                color: white;
                text-decoration: none;
                border-radius: 50px;
                font-size: 1.2rem;
                font-weight: bold;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                animation: pulse 2s infinite;
            ">
                <i class="fas fa-utensils"></i> View Full Menu
            </a>
        </div>

        <section class="about-section">
            <h2>About This Project</h2>
            <p>KAVIMUGIL Food Ordering System is a comprehensive web application developed as a showcase of modern web development techniques. This project demonstrates full-stack development skills with PHP and MySQL, featuring a complete food ordering system with user authentication, shopping cart, and order processing.</p>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-lock"></i>
                    <h3>Secure Authentication</h3>
                    <p>Password hashing and session management for secure user accounts</p>
                </div>
                <div class="feature">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Shopping Cart</h3>
                    <p>Full cart functionality with quantity adjustment and checkout</p>
                </div>
                <div class="feature">
                    <i class="fas fa-database"></i>
                    <h3>Database Driven</h3>
                    <p>MySQL database with proper relationships and validation</p>
                </div>
                <div class="feature">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Responsive Design</h3>
                    <p>Works on all devices from mobile to desktop</p>
                </div>
            </div>
        </section>

        <div style="text-align: center; margin: 80px 0 40px;">
            <h2 style="color: var(--light); font-size: 2rem; margin-bottom: 20px;">Ready to Order?</h2>
            <a href="menu.php" class="btn floating" style="
                padding: 15px 40px;
                font-size: 1.2rem;
                background: linear-gradient(45deg, var(--secondary), var(--primary));
            ">
                <i class="fas fa-rocket"></i> Start Your Order Now
            </a>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

    <script>
        // Additional animation triggers
        document.addEventListener('DOMContentLoaded', function() {
            // Animate features on scroll
            const features = document.querySelectorAll('.feature');
            
            const featureObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                        featureObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            features.forEach(feature => {
                featureObserver.observe(feature);
            });
            
            // Floating animation for special elements
            const floaters = document.querySelectorAll('.floating');
            floaters.forEach((floater, index) => {
                floater.style.animationDelay = `${index * 0.2}s`;
            });
        });
    </script>
</body>
</html>