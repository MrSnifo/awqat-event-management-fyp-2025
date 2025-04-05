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

        <div class="btn"><a href="C:\xampp\htdocs\PFA-2024-2025test\src\views\home.php"> Back Home</a></div>
    </form>
</body>
</html>

<?php
/*
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        
        $Email = filter_input(INPUT_POST,"mail",FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST,"password",FILTER_SANITIZE_SPECIAL_CHARS);

        
        if(empty($Email)){
            echo "Please Enter an Email !!";
        }
        elseif(empty($password)){
            echo "Please Enter a Password !!";
        }else{
            
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $sql = "INSERT INTO users_login (user_mail , password) values ('$Email','$hash')";

            try{
                mysqli_query($conn, $sql);
                echo "you are regestired ";
                header("Location: Login.php");
            }catch(mysqli_sql_exception){
                echo "Email Exsists ";
            }
        }

    }
    mysqli_close($conn);


*/
?>
