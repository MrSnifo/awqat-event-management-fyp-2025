<?php
session_start(); // <-- Add this line!
if (isset($_SESSION['email'])){
    header("Location: /PFA-2024-2025test/src/public/");
    exit;
}

// Initialization
$username = "";
$email = "";
$password = "";
$confirm_password = "";

$password_identique_error = "";
$email_error = "";
$error = false;

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    require_once __DIR__ . '/../config/database.php';
    $dbConnection = getDatabaseConnection();

    $statement = $dbConnection->prepare("SELECT id FROM users WHERE email =?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $email_error = "Email is already Used!";
        $error = true;
    }
    $statement->close();

    if (!$error) {
        $statement = $dbConnection->prepare("INSERT INTO users (username,email,password) VALUES(?,?,?)");
        $statement->bind_param('sss', $username, $email, $password);
        $statement->execute();
        $insert_id = $statement->insert_id;
        $statement->close();

        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        header("Location: /PFA-2024-2025/src/public/");
        exit;
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ouqat</title>
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
                <p>Join Ouqat today</p>
            </div>
            
            <form id="registrationForm" class="registration-form" method="post">
                <div class="form-fields">
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="username" name="username" placeholder=" " required
                               minlength="3" maxlength="20" pattern="[a-zA-Z0-9_]+"
                               title="Username must be 3-20 characters (letters, numbers, underscores)">
                        <label for="username">Username</label>
                        <div class="error-message" id="username-error" value="<?php echo $username ?>"></div>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-at"></i></span>
                        <input type="email" id="email" name="email" placeholder=" " required
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                               title="Please enter a valid email address">
                        <label for="email">Email Address</label>
                        <div class="error-message" id="email-error" value="<?php echo $email_error ?>" ></div>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-key-fill"></i></span>
                        <input type="password" id="password" name="password" placeholder=" " required
                               minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$"
                               title="Password must be at least 8 characters with uppercase, lowercase, and number" value="">
                        <label for="password">Password</label>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <div class="error-message" id="password-error"></div>
                    </div>
                    
                    <div class="input-group">
                        <span class="input-icon"><i class="bi bi-key-fill"></i></span>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required value="">
                        <label for="confirm_password">Confirm Password</label>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                        <div class="error-message" id="confirm-password-error"><?= $password_identique_error?></div>
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
                <p>Already have an account? <a href="login">Sign in</a></p>
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