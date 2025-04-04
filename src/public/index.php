<?php
// Get requested path and clean it
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

    case '/404':
        include __DIR__ . '/../views/404.php';
        break;

    default:
        http_response_code(404);
        break;
}
?>
