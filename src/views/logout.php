<?php
require_once "../controllers/auth.php";

$auth = new AuthController();
$auth->logout();

$redirect = isset($_GET['redirect']) ? base64_decode($_GET['redirect']) : './';
if($auth->isSafeRedirect($redirect)){
    header("Location: $redirect");
} else{
    header("Location: ./");
}
exit();
?>
