<?php
// Router for PHP built-in server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route to index.php for root
if ($uri === '/') {
    require_once __DIR__ . '/index.php';
    return true;
}

// Try to serve the requested PHP file
$file = __DIR__ . $uri;
if (file_exists($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require_once $file;
    return true;
}

// 404
http_response_code(404);
echo '404 Not Found';
return true;
