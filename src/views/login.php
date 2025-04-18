<?php
session_start(); // Required for sessions to work

$email = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and Password are required !";
    } else {
        require_once __DIR__ . '/../config/database.php';
        $dbConnection = getDatabaseConnection();

        $statement = $dbConnection->prepare("SELECT id,username,email,password FROM users WHERE email =?");
        $statement->bind_param("s", $email);
        $statement->execute();
        $statement->bind_result($id, $username, $email, $stored_password);

        if ($statement->fetch()) {
            if ($password === $stored_password) {
                $_SESSION["id"] = $id;
                $_SESSION["email"] = $email;
                $_SESSION["username"] = $username;
                header("Location: /PFA-2024-2025test/src/public/");
                exit;
            }
        }

        $statement->close();
        $error = "Email or Password invalide ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ouqat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header clickable-logo" id="homeLink">
                <h2>Welcome Back</h2>
                <p>Sign in to continue to Ouqat</p>
            </div>

            <?php
            session_start();
            require_once '../config/database.php';
            require_once '../controllers/auth.php';



            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];
                // $remember = isset($_POST['remember']) ? true : false;

                if (empty($email) || empty($password)) {
                    $error = "Please fill in all fields";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email format";
                } else {
                    try {
                        $auth = new Auth();
                        $result = $auth->verifyLogin($email, $password);
                        if ($result['success']) {
                            $auth->createSession($result['user_id']);
                            header('Location: dashboard.php');
                            exit();
                        } else { 
                            echo $result['message'];
                        }

                        if (!$auth->verifySession()) {
                            header('Location: login.php');
                            exit();
                        }

                        // Logout
                        $auth->logout();
                        header('Location: login.php');


                    } catch (PDOException $e) {
                        error_log("Login error: " . $e->getMessage());
                        $error = "A system error occurred. Please try again later.";
                    }
                }
            }

        ?>
            
<<<<<<< HEAD
            <form class="login-form" method="post">
=======
            <form class="login-form" method="POST">
>>>>>>> 38d8d3b5b18ae33c9c7362932ea9fec4a3cd6eb8
                <div class="input-group">
                    <span class="input-icon"><i class="bi bi-at"></i></span>
                    <input type="email" name="email" id="email" placeholder=" " required>
                    <label for="email">Email Address</label>
                </div>
                
                <div class="input-group">
                    <span class="input-icon"><i class="bi bi-key-fill"></i></span>
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <button type="button" class="password-toggle">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                </div>
                
                <div class="login-options">
                    <div class="remember-container">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="forgot-password" class="forgot-password">Forgot password?</a>
                </div>
                
                <button type="submit" class="login-btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Log In
                </button>
            </form>

            <div class="theme-switcher">
                <button type="button" id="themeToggle" class="theme-toggle-btn">
                    <i class="bi bi-moon-fill theme-icon"></i>
                    <span>Switch Appearance</span>
                </button>
            </div>
            
            <div class="login-footer">
                <p>Don't have an account? <a href="register">Sign up</a></p>
            </div>
        </div>
        
        <div class="login-decoration">
            <div class="decoration-circle"></div>
            <div class="decoration-circle"></div>
            <div class="decoration-circle"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/login.js"></script>
</body>
</html>