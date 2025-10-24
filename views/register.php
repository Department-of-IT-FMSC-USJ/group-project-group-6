<?php
$pageTitle = 'Register - EcoCollect Lanka';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';
redirectIfLoggedIn('/views/dashboard.php');
include __DIR__ . '/includes/header.php';
$districts = getDistricts();
?>

<main class="page-content">
    <div class="container">
        <section class="page-hero">
            <h1>Join EcoCollect Lanka</h1>
            <p>Start earning rewards while helping to clean up Sri Lanka</p>
        </section>

        <section class="registration-section">
            <div class="registration-wrapper">
                <div class="registration-card">
                    <div class="registration-tabs">
                        <button class="tab-button active" onclick="showTab('household')">Household / Individual</button>
                        <button class="tab-button" onclick="showTab('company')">Collection Center</button>
                    </div>

                    <!-- Household/Individual Registration -->
                    <div id="household-form" class="registration-form active">
                        <h2>Household Registration</h2>
                        <form method="POST" action="/controllers/AuthController.php?action=register">
                            <input type="hidden" name="user_type" value="household">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="h-username">Username *</label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="h-full_name">Full Name *</label>
                                    <input type="text" name="full_name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="h-email">Email *</label>
                                <input type="email" name="email" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="h-password">Password *</label>
                                    <input type="password" name="password" required minlength="6">
                                </div>
                                <div class="form-group">
                                    <label for="h-confirm">Confirm Password *</label>
                                    <input type="password" name="confirm_password" required minlength="6">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="h-phone">Phone Number</label>
                                <input type="tel" name="phone">
                            </div>

                            <div class="form-group">
                                <label for="h-address">Address</label>
                                <textarea name="address" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="h-district">District</label>
                                <select name="district">
                                    <option value="">Select District</option>
                                    <?php foreach ($districts as $district): ?>
                                        <option value="<?php echo $district; ?>"><?php echo $district; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Register</button>
                        </form>
                    </div>

                    <!-- Collection Center Registration -->
                    <div id="company-form" class="registration-form">
                        <h2>Collection Center Registration</h2>
                        <form method="POST" action="/controllers/AuthController.php?action=register">
                            <input type="hidden" name="user_type" value="company">
                            
                            <div class="form-group">
                                <label>Center Name *</label>
                                <input type="text" name="full_name" required>
                            </div>

                            <div class="form-group">
                                <label>Username *</label>
                                <input type="text" name="username" required>
                            </div>

                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Password *</label>
                                    <input type="password" name="password" required minlength="6">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password *</label>
                                    <input type="password" name="confirm_password" required minlength="6">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Phone *</label>
                                <input type="tel" name="phone" required>
                            </div>

                            <div class="form-group">
                                <label>Center Address *</label>
                                <textarea name="address" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>District *</label>
                                <select name="district" required>
                                    <option value="">Select District</option>
                                    <?php foreach ($districts as $district): ?>
                                        <option value="<?php echo $district; ?>"><?php echo $district; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Register Center</button>
                        </form>
                    </div>
                </div>

                <div class="login-footer" style="text-align: center; margin-top: 20px;">
                    <p>Already have an account? <a href="/views/login.php">Login here</a></p>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
function showTab(type) {
    // Hide all forms
    document.querySelectorAll('.registration-form').forEach(form => {
        form.classList.remove('active');
        form.style.display = 'none';
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected form
    const formId = type + '-form';
    document.getElementById(formId).classList.add('active');
    document.getElementById(formId).style.display = 'block';
    
    // Set active button
    event.target.classList.add('active');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.registration-form').forEach((form, index) => {
        if (index !== 0) form.style.display = 'none';
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
