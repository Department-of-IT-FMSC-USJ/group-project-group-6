document.addEventListener('DOMContentLoaded', () => {
    // Handle login form submission
    window.handleLogin = function(event) {
        event.preventDefault();
        const form = event.target;
        const email = form.email.value;
        const password = form.password.value;

        if (!email || !password) {
            const errorSpan = email ? form.password.nextElementSibling : form.email.nextElementSibling;
            errorSpan.textContent = 'This field is required.';
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            form.email.nextElementSibling.textContent = 'Please enter a valid email address.';
            return;
        }

        console.log('Login Attempt:', { email, password, remember: form.remember.checked });
        // mark as logged in for demo purposes
        if (window.auth && typeof window.auth.login === 'function') window.auth.login();
        showAlert('Login successful! Redirecting to dashboard...');
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 1000);
    };

    // Demo login functionality
    window.demoLogin = function(type) {
        console.log(`Demo login for ${type}`);
        if (window.auth && typeof window.auth.login === 'function') window.auth.login();
        showAlert(`Logged in as ${type} demo user!`);
        setTimeout(() => {
            window.location.href = 'dashboard.html';
            // Simulate dashboard switch
            setTimeout(() => window.showDashboard(type), 100);
        }, 1000);
    };

    // Show forgot password modal
    window.showForgotPassword = function() {
        const modal = document.getElementById('forgotModal');
        if (modal) {
            modal.classList.add('show');
        }
    };

    // Close forgot password modal
    window.closeForgotPassword = function() {
        const modal = document.getElementById('forgotModal');
        if (modal) {
            modal.classList.remove('show');
        }
    };

    // Handle forgot password submission
    window.handleForgotPassword = function(event) {
        event.preventDefault();
        const form = event.target;
        const email = form.forgotEmail.value;

        if (!email) {
            form.forgotEmail.nextElementSibling.textContent = 'Email is required.';
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            form.forgotEmail.nextElementSibling.textContent = 'Please enter a valid email address.';
            return;
        }

        console.log('Password Reset Requested:', { email });
        showAlert('Password reset link sent to your email.');
        closeForgotPassword();
    };
});