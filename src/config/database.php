<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'ouqat'); 
define('DB_USER', 'root'); 
define('DB_PASS', '');


$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";

try {
    // PDO will use exceptions in case of an error
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
     // Set the error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>