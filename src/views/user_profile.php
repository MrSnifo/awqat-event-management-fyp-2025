<?php
session_start();
require_once '../controllers/Auth.php';
require_once '../controllers/event.php';
require_once '../controllers/interest.php';

// Create Auth instance
$auth = new Auth();
$eventController = new EventController();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$username = $isLoggedIn ? $_SESSION['username'] : '';

if (!$isLoggedIn) {
    header("Location: ./login");
    exit();
} else{
    header("Location: ./profile/" . $_SESSION['user_id']);
    exit();
}


?>