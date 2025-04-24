<?php
session_start();
require_once "../controllers/Auth.php";
require_once "../controllers/event.php";
require_once "../controllers/interest.php";

// Create Auth instance
$auth = new AuthController();
$eventController = new EventController();
$isLoggedIn = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
$username = $isLoggedIn ? $_SESSION["username"] : "";
$redirect = base64_encode($_SERVER['REQUEST_URI']);

if (!$isLoggedIn) {
    header("Location: ./login?redirect=$redirect");
    exit();
} else {
    header("Location: ./profile/" . $_SESSION["user_id"]);
    exit();
}
?>
