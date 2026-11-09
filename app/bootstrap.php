<?php

declare(strict_types=1);

use App\Core\Router;

$GLOBALS['app_config'] = require CONFIG_PATH . '/app.php';
require APP_PATH . '/Helpers/functions.php';
require APP_PATH . '/Helpers/resources.php';
date_default_timezone_set((string) config('timezone', 'Asia/Colombo'));

if (config('debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = APP_PATH . '/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$sessionName = (string) config('session_name', 'SLGTISESSID');
session_name($sessionName);
session_set_cookie_params([
    'lifetime' => 0,
    'path' => base_url() === '/' ? '/' : rtrim(base_url(), '/'),
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = time();
}

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; frame-src https://www.openstreetmap.org https://www.youtube.com https://www.youtube-nocookie.com; connect-src 'self'");

set_exception_handler(static function (Throwable $exception): void {
    $log = STORAGE_PATH . '/logs/php-error.log';
    error_log('[' . date('c') . '] ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine() . PHP_EOL, 3, $log);
    if (!headers_sent()) {
        http_response_code(500);
    }
    if (config('debug')) {
        echo '<pre style="white-space:pre-wrap;font-family:Consolas,monospace;padding:24px">';
        echo htmlspecialchars($exception->getMessage() . "\n" . $exception->getFile() . ':' . $exception->getLine(), ENT_QUOTES, 'UTF-8');
        echo '</pre>';
        return;
    }
    echo 'The page could not be completed. Please try again later.';
});

$router = new Router();
require ROOT_PATH . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
clear_old();
unset($_SESSION['_errors']);
