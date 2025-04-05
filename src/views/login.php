<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Ouqat</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="btn"><a href="assets/views/home.php">← Home</a></div>
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
        </div>

        <button>Log In</button>

        <p>Don't have an account? <a href="#"><label for="">Sign Up</label></a></p>
    </div>
</body>
</html>
