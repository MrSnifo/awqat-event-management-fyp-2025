<?php
require_once "../controllers/auth.php";

$auth = new Auth();
$auth->logout();

header("Location: ./");
exit();
?>
