<?php
require_once __DIR__ . '/../config/database.php'; // makes $connection available

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/' . implode('/', array_slice(explode('/', str_replace('\\', '/', __DIR__)), -3));
$cleanPath = str_replace($basePath, '', $path);

switch ($cleanPath) {
    case '/':
        include __DIR__ . '/../views/home.php';
        break;

    case '/login':
        include __DIR__ . '/../views/login.php';
        break;
    case '/register':
        include __DIR__ . '/../views/registration.php';
        break;
    case '/profile':
        include __DIR__ . '/../views/profile.php';
        break;
    case '/interests':
        include __DIR__ . '/../views/interests.php';
        break;
    case '/create-event':
        include __DIR__ . '/../views/add_event.php';
        break;
    case '/logout':
        include __DIR__ . '/../views/logout.php'; // Or wherever logout logic lives
        break;
        
    default:
        break;
}
?>
