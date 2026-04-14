<?php
// Simple router for PHP built-in server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If it's a file that exists and not a PHP file, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri) && !preg_match('/\.php$/', $uri)) {
    return false;
}

// If it's root, serve index.php
if ($uri === '/') {
    require __DIR__ . '/index.php';
    exit;
}

// If it's a PHP file, serve it
if (preg_match('/\.php$/', $uri) && file_exists(__DIR__ . $uri)) {
    require __DIR__ . $uri;
    exit;
}

// Otherwise let the server handle it
return false;
