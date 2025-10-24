document.addEventListener('DOMContentLoaded', () => {
    // Hamburger menu toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav_links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    // Close mobile menu when a link is clicked
    const navItems = document.querySelectorAll('.nav_links a');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            navLinks.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });

    // Close modals when clicking outside
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    });
});

// Utility function to show alerts
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}

// Simple count-up animation utility
// elementOrSelector: DOM element or selector string
// start, end: numbers
// duration: ms
function countUp(elementOrSelector, start, end, duration = 800) {
    const el = (typeof elementOrSelector === 'string') ? document.querySelector(elementOrSelector) : elementOrSelector;
    if (!el) return;
    const startTime = performance.now();
    const step = (now) => {
        const progress = Math.min((now - startTime) / duration, 1);
        const value = Math.floor(start + (end - start) * progress);
        el.textContent = value.toLocaleString();
        if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

// Expose on window for pages to use
window.countUp = countUp;

// Navigate to redeem page (used by dashboard Redeem button)
function showRedeemPoints(){
    // relative navigation to redeem page
    window.location.href = 'redeem.html';
}
window.showRedeemPoints = showRedeemPoints;

// Navigate user to locations page in pickup-request mode
function showRequestPickup(){
    window.location.href = 'location.html?pickup=true';
}
window.showRequestPickup = showRequestPickup;