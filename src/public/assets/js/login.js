document.addEventListener('DOMContentLoaded', function() {
    // Home link functionality
    const homeLink = document.getElementById('homeLink');
    if (homeLink) {
        homeLink.addEventListener('click', function() {
            window.location.href = './'; 
        });
    }

    // Password toggle functionality
    const passwordToggle = document.querySelector('.password-toggle');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            }
        });
    }

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

    // Form submission
    const loginForm = document.querySelector('.login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            console.log('Form submitted');
            // Example: window.location.href = '/dashboard';
        });
    }
});