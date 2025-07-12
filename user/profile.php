<?php 
require_once '../includes/session.php'; 

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php'; 
require_once '../includes/header.php';

// Get user details
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
    .profile-background {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: -1;
        object-fit: cover;
    }

    .profile-container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 600px;
    width: 90%;
    background: rgba(255, 255, 255, 0.9);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    z-index: 20; /* Ensure it appears above the video */
    }


    .profile-container h2 {
        text-align: center;
        margin-bottom: 2rem;
        color: #fc8019;
    }

    .profile-form .form-group {
        margin-bottom: 1.5rem;
    }

    .profile-form label {
        font-weight: bold;
        margin-bottom: 0.4rem;
        display: block;
        color: #333;
    }

    .profile-form input {
        width: 100%;
        padding: 0.7rem;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .profile-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .btn-primary {
        background-color: #fc8019;
        color: #fff;
        padding: 0.7rem 1.5rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #f96a00;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        padding: 0.7rem 1.5rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>

<!-- Background video -->
<video autoplay muted loop class="profile-background">
    <source src="../assets/bg/bg1.webm" type="video/webm">
    Your browser does not support the video tag.
</video>

<section class="profile-container">
    <h2>Your Profile</h2>

    <form class="profile-form" method="post" action="../update_profile.php">
        <div class="form-group">
            <label for="username">👤 Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">📧 Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>📅 Member Since</label>
            <input type="text" value="<?php echo date('M j, Y', strtotime($user['created_at'])); ?>" disabled>
        </div>

        <div class="profile-buttons">
    <button type="submit" class="btn-primary">💾 Save Changes</button>
    <button type="button" class="btn-secondary" onclick="openModal()">🔑 Change Password</button>
</div>

<!-- Modal HTML -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Change Password</h2>
        <form id="changePasswordForm" method="POST" action="change_password.php">
            <input type="password" name="old_password" placeholder="Old Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit" class="btn-primary">Update Password</button>
        </form>
        <div id="message"></div>
    </div>
</div>

    </form>
</section>

<style>
/* Modal backdrop */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease;
}

/* Modal content box */
.modal-content {
    background: linear-gradient(145deg, #f9f9f9, #ffffff);
    margin: 10% auto;
    padding: 30px;
    width: 90%;
    max-width: 420px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    position: relative;
    animation: slideUp 0.3s ease;
    font-family: 'Segoe UI', sans-serif;
}

/* Close button */
.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 24px;
    color: #888;
    cursor: pointer;
    transition: color 0.2s ease;
}
.close:hover {
    color: #ff3b3b;
}

/* Form inputs */
.modal-content input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    margin: 10px 0 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    transition: border 0.3s ease;
}
.modal-content input:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Button */
.modal-content .btn-primary {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 100%;
}
.modal-content .btn-primary:hover {
    background-color: #45a049;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; } 
    to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Top Navigation Tabs */
.navbar {
    display: flex;
    justify-content: center;
    gap: 2rem;
    padding: 1rem 0;
    background-color: #fc8019;
    position: fixed;       /* Changed from sticky to fixed */
    top: 0;
    left: 0;
    right: 0;
    width: 100%;           /* Ensure full width */
    z-index: 10;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.navbar a {
    text-decoration: none;
    color: white;
    font-weight: bold;
    font-size: 1rem;
    transition: color 0.3s ease;
}
.navbar a:hover {
    color: #ffe7d0;
}

/* Bottom Navigation Tabs */
.bottom-tab {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 1rem;
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #fc8019;
    color: white;
    z-index: 10;
}
.bottom-tab a {
    text-decoration: none;
    color: white;
    font-size: 1rem;
    font-weight: 600;
}
.bottom-tab a:hover {
    color: #ffe7d0;
}


</style>

<script>
function openModal() {
    const modal = document.getElementById("changePasswordModal");
    modal.style.display = "block";
}

function closeModal() {
    const modal = document.getElementById("changePasswordModal");
    modal.style.display = "none";
}

// Optional: Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById("changePasswordModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
</script>
<?php require_once '../includes/footer.php'; ?>
