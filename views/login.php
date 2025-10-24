<?php
$pageTitle = 'Login - EcoCollect Lanka';
require_once __DIR__ . '/../helpers/session.php';
redirectIfLoggedIn('/views/dashboard.php');
include __DIR__ . '/includes/header.php';
?>

<main class="login-main">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Login to your EcoCollect Lanka account</p>
            </div>

            <form method="POST" action="/controllers/AuthController.php?action=login" class="login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="/views/register.php">Register here</a></p>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
