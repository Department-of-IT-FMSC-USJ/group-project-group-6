<?php
// Initialize session
require_once __DIR__ . '/../../helpers/session.php';
require_once __DIR__ . '/../../helpers/utils.php';
initSession();

$current_user = getCurrentUser();
$is_logged_in = isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'EcoCollect Lanka'; ?></title>
    <link rel="stylesheet" href="/public/style.css">
</head>
<body>
    <header>
        <img class="logo" src="/public/Logo.png" alt="EcoCollect Logo" />
        <nav>
            <ul class="nav_links">
                <li><a href="/views/index.php">Home</a></li>
                <li><a href="/views/how-it-works.php">How it Works</a></li>
                <li><a href="/views/about.php">About Us</a></li>
                <li><a href="/views/location.php">Locations</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="/views/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="header-right">
            <?php if ($is_logged_in): ?>
                <span style="margin-right: 15px;">Welcome, <?php echo htmlspecialchars($current_user['full_name']); ?>!</span>
                <a href="/controllers/AuthController.php?action=logout"><button class="btn">Log Out</button></a>
            <?php else: ?>
                <a href="/views/login.php"><button class="btn">Log In</button></a>
            <?php endif; ?>
        </div>
    </header>
    
    <?php
    // Display flash messages
    $flash = getFlashMessage();
    if ($flash):
    ?>
        <div class="flash-message flash-<?php echo $flash['type']; ?>" style="padding: 15px; margin: 20px; border-radius: 5px; text-align: center; <?php 
            echo $flash['type'] === 'success' ? 'background: #d4edda; color: #155724;' : 
                ($flash['type'] === 'error' ? 'background: #f8d7da; color: #721c24;' : 'background: #d1ecf1; color: #0c5460;');
        ?>">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
    <?php endif; ?>
