<?php
/*
    include("C:\Users\chahed\Documents\GitHub\PFA-2024-2025\src\config\database.php");*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Ouqat</title>

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
    <form  action="<?php  htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST" class="login-container">
        
        <h2>Login to Ouqat</h2>

        <div class="input-group">
            <input type="text" id="username" placeholder=" " required />
            <label for="username">Username or Email</label>
        </div>

        <div class="input-group">
            <input type="password" id="password" placeholder=" " required />
            <label for="password">Password</label>
        </div>

        <div class="remember-container">
            <input type="checkbox" id="rememberMe" />
            <label for="rememberMe">Remember Me</label>
            <a href="#">Forgot password?</a>
        </div>

        <button>Log In</button>

        <p>Don't have an account? <a href="#"><label for="">Sign Up</label></a></p>
    </form>
</body>
</html>
