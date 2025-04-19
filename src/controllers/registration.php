<?php
require_once '../config/database.php';
require_once '../models/User.php';

$user = new User($pdo);

session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $password_confirm) {
        $error = "Passwords do not match.";
    } elseif ($user->usernameExists($username)) {
        $error = "Username already exists.";
    } elseif ($user->emailExists($email)) {
        $error = "Email already exists.";
    } else {
        // Hash the password!.
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user->username = $username;
        $user->email = $email;
        $user->password_hash = $password_hash;
        $user->profile_description = "";
        $user->profile_picture_url = "";
        $user->social_links = [];

        // role (0=user, 1=mod, 2=admin, 3=superadmin)
        $user->role = 0;
        // Status (0=inactive, 1=active, 2=banned)
        $user->status = 1;

        // Attempt to create the user in the database
        if ($user->create()) {
            // Start the session and store user info.
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            header('Location: ./');
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
} else {
    include __DIR__ . '/../views/registration.php';
}
?>