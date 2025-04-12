document.addEventListener('DOMContentLoaded', function() {
    // Home link functionality
    const homeLink = document.getElementById('homeLink');
    if (homeLink) {
        homeLink.addEventListener('click', function() {
            window.location.href = './'; 
        });
    }

    // Password toggle functionality
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });
    });

    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('user-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.classList.add('dark-theme');
            updateThemeIcon(true);
        }

        themeToggle.addEventListener('click', function() {
            const isDark = document.documentElement.classList.toggle('dark-theme');
            localStorage.setItem('user-theme', isDark ? 'dark' : 'light');
            updateThemeIcon(isDark);
        });

        function updateThemeIcon(isDark) {
            const icon = themeToggle.querySelector('.theme-icon');
            const text = themeToggle.querySelector('span');
            
            if (isDark) {
                icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                text.textContent = 'Light Mode';
            } else {
                icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                text.textContent = 'Dark Mode';
            }
        }
    }

    // Form validation
    const form = document.getElementById('registrationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();

            // Validate username
            const username = document.getElementById('username');
            if (!username.validity.valid) {
                showError(username, 'Username must be 3-20 alphanumeric characters');
                return;
            }

            // Validate email
            const email = document.getElementById('email');
            if (!email.validity.valid) {
                showError(email, 'Please enter a valid email address');
                return;
            }

            // Validate password
            const password = document.getElementById('password');
            if (!password.validity.valid) {
                showError(password, 'Password must be at least 8 characters with uppercase, lowercase, and number');
                return;
            }

            // Validate password confirmation
            const confirmPassword = document.getElementById('confirm_password');
            if (confirmPassword.value !== password.value) {
                showError(confirmPassword, 'Passwords do not match');
                return;
            }

            // If all validations pass
            alert('Form submitted successfully!');
            // form.submit(); // Uncomment to actually submit the form
        });

        // Real-time validation on input change
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (input.id === 'confirm_password') {
                    const password = document.getElementById('password');
                    if (this.value !== password.value) {
                        showError(this, 'Passwords do not match');
                    } else {
                        clearError(this);
                    }
                } else {
                    if (!input.validity.valid) {
                        showError(input, input.title);
                    } else {
                        clearError(input);
                    }
                }
            });
        });
    }

    function showError(input, message) {
        const inputGroup = input.closest('.input-group');
        inputGroup.classList.add('invalid');
        const errorElement = inputGroup.querySelector('.error-message');
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    function clearError(input) {
        const inputGroup = input.closest('.input-group');
        inputGroup.classList.remove('invalid');
        const errorElement = inputGroup.querySelector('.error-message');
        errorElement.style.display = 'none';
    }

    function clearErrors() {
        document.querySelectorAll('.input-group').forEach(group => {
            group.classList.remove('invalid');
            const errorElement = group.querySelector('.error-message');
            errorElement.style.display = 'none';
        });
    }
});