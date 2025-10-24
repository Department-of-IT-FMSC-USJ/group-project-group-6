document.addEventListener('DOMContentLoaded', () => {
    // Show registration tab
    window.showTab = function(type) {
        const tabs = document.querySelectorAll('.tab-button');
        const forms = document.querySelectorAll('.registration-form');

        tabs.forEach(tab => tab.classList.remove('active'));
        forms.forEach(form => form.classList.remove('active'));

        document.querySelector(`[onclick="showTab('${type}')"]`).classList.add('active');
        document.getElementById(`${type}-form`).classList.add('active');
    };

    // Validate form
    function validateForm(form, type) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            const errorSpan = field.nextElementSibling;
            if (!field.value.trim()) {
                errorSpan.textContent = `${field.labels[0].textContent} is required.`;
                isValid = false;
            } else {
                errorSpan.textContent = '';
            }

            if (field.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    errorSpan.textContent = 'Please enter a valid email address.';
                    isValid = false;
                }
            }

            if (field.type === 'tel') {
                const phoneRegex = /^\+94\d{9}$/;
                if (!phoneRegex.test(field.value)) {
                    errorSpan.textContent = 'Please enter a valid phone number (e.g., +94112345678).';
                    isValid = false;
                }
            }
        });

        const password = form.querySelector('[name="password"]');
        const confirmPassword = form.querySelector('[name="confirmPassword"]');
        if (password && confirmPassword) {
            if (password.value.length < 8 || !/[a-zA-Z]/.test(password.value) || !/\d/.test(password.value)) {
                password.nextElementSibling.textContent = 'Password must be at least 8 characters with letters and numbers.';
                isValid = false;
            } else if (password.value !== confirmPassword.value) {
                confirmPassword.nextElementSibling.textContent = 'Passwords do not match.';
                isValid = false;
            } else {
                password.nextElementSibling.textContent = '';
                confirmPassword.nextElementSibling.textContent = '';
            }
        }

        if (type === 'company') {
            const serviceAreas = form.querySelectorAll('[name="serviceAreas"]:checked');
            if (serviceAreas.length === 0) {
                const errorSpan = form.querySelector('[name="serviceAreas"]').parentElement.nextElementSibling;
                errorSpan.textContent = 'Please select at least one service area.';
                isValid = false;
            }
        }

        return isValid;
    }

    // Handle form submission
    window.submitForm = function(event, type) {
        event.preventDefault();
        const form = event.target;
        if (validateForm(form, type)) {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            console.log(`${type} Registration:`, data);
            showAlert(`${type.charAt(0).toUpperCase() + type.slice(1)} registration submitted successfully!`);
            form.reset();
            if (type === 'company') {
                showAlert('Your application will be reviewed within 3-5 business days.', 'info');
            }
        }
    };
});