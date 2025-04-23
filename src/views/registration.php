<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $data = [
        'username' => trim(strip_tags($_POST['username'] ?? '')),
        'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
        'password' => $_POST['password'] ?? '',
        'password_confirm' => $_POST['password_confirm'] ?? ''
    ];

    // Validate inputs
    if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['password_confirm'])) {
        $error = "Please fill in all fields.";
    } elseif ($data['password'] !== $data['password_confirm']) {
        $error = "Passwords do not match.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Proceed with registration
        $auth = new Auth();
        $result = $auth->register($data);

        if ($result['success']) {
            $loginResult = $auth->login($data['email'], $data['password']);
            if ($loginResult['success']) {
                $auth->createSession($loginResult['user_id'], $loginResult['username'], $loginResult['role']);
                header('Location: ./');
                exit();
            } else {
                $error = "Login failed after registration.";
            }
        } else {
            $error = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Awqat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('user-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.classList.toggle(
                'dark-theme', 
                savedTheme ? savedTheme === 'dark' : prefersDark
            );
        })();
    </script>
    <link rel="stylesheet" href="assets/css/registration.css">
</head>
<body>
    <div class="register-wrapper">
        <div class="register-container">
            <div class="register-header clickable-logo" id="homeLink">
                <h2>Create Account</h2>
                <p>Join Awqat today</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="error-message-container">
                    <div class="error-message-content">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <!-- Prevents cross site scripting by wrapping it (Partie) -->
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <form class="registration-form" method="POST">
                <div class="form-fields">
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="username" name="username" placeholder=" " required
                               minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+" 
                               title="Username must be 3-20 characters (letters, numbers, underscores)">
                        <label for="username">Username</label>
                        <div class="error-message" id="username-error"></div>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-at"></i></span>
                        <input type="email" id="email" name="email" placeholder=" " required
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                               title="Please enter a valid email address">
                        <label for="email">Email Address</label>
                        <div class="error-message" id="email-error"></div>
                    </div>

                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-key-fill"></i></span>
                        <input type="password" id="password" name="password" placeholder=" " required
                               minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$"
                               title="Password must be at least 8 characters with uppercase, lowercase, and number">
                        <label for="password">Password</label>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <div class="error-message" id="password-error"></div>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-key-fill"></i></span>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder=" " required>
                        <label for="password_confirm">Confirm Password</label>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <div class="error-message" id="confirm-password-error"></div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="register-btn">
                        <i class="bi bi-person-plus-fill me-2"></i> Sign Up
                    </button>
                </div>
            </form>

            <div class="theme-switcher">
                <button type="button" id="themeToggle" class="theme-toggle-btn">
                    <i class="bi bi-moon-fill theme-icon"></i>
                    <span>Switch Appearance</span>
                </button>
            </div>
            
            <div class="login-footer">
                <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
        
        <div class="register-decoration">
            <div class="decoration-circle"></div>
            <div class="decoration-circle"></div>
            <div class="decoration-circle"></div>
        </div>
    </div>

    <script src="assets/js/registration.js"></script>
</body>
</html>
