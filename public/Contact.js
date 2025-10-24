// Contact page specific helpers (wrappers)
// The real handler lives in JS/script.js (submitContactForm)
// Keep a small helper to forward calls.
function initContact() {
	var form = document.getElementById('contactForm');
	if (form) form.addEventListener('submit', submitContactForm);
}
document.addEventListener('DOMContentLoaded', initContact);
