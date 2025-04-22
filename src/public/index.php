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
        include __DIR__ . '/../views/create_event.php';
        break;
    case '/logout':
        include __DIR__ . '/../views/logout.php';
        break;
        
    // Handle requests for files in storage/uploads
    default:
        if (strpos($cleanPath, '/storage/') === 0) {
            $filePath = __DIR__ . '/..' . $cleanPath;
            
            // Ensure the requested path is a file, not a directory
            if (is_dir($filePath)) {
                // Prevent access to directories
                header('HTTP/1.0 403 Forbidden');
                echo 'Forbidden: Access to directories is not allowed.';
                exit;
            }
            
            // Check if file exists and is readable
            if (file_exists($filePath) && is_readable($filePath)) {
                // Get the MIME type
                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'pdf' => 'application/pdf',
                    // Add more as needed
                ];
                
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
                
                // Set headers and output file
                header('Content-Type: ' . $mimeType);
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit;
            } else {
                header('HTTP/1.0 404 Not Found');
                echo 'File not found';
                exit;
            }
        }
        break;
}
