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

    // Registration button functionality
    const registerBtn = document.getElementById('registerBtn');
    if (registerBtn) {
        registerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get form values
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Simple validation
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            // Add your registration logic here
            console.log('Registration submitted:', { username, email, password });
            // Example: window.location.href = '/dashboard';
        });
    }
});