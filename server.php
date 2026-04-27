<?php
/**
 * Development server file to serve the application when using `php -S` or `artisan serve`.
 * This mirrors the default Laravel `server.php` behavior and avoids relying on
 * the vendor copy when dependencies are not yet installed.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// If a file exists in the public folder, serve it directly.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require __DIR__.'/public/index.php';
