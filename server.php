<?php
// Simple PHP server bootstrap for testing
// This file helps run Laravel without artisan serve when there are path issues

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Bootstrap Laravel application
require_once __DIR__.'/public/index.php';