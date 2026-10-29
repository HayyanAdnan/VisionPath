<?php
/**
 * Database configuration template for VisionPath.
 *
 * Set DB_HOST, DB_NAME, DB_USER, DB_PASS, and ADMIN_API_KEY in your
 * hosting environment. Never commit real credentials to GitHub.
 */
define('DB_HOST', getenv('DB_HOST') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: '');
define('DB_USER', getenv('DB_USER') ?: '');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('ADMIN_API_KEY', getenv('ADMIN_API_KEY') ?: '');
if (!defined('API_BASE')) define('API_BASE', 'api/');

function getDBConnection() {
    if (!DB_HOST || !DB_NAME || !DB_USER || !DB_PASS) {
        error_log('VisionPath database settings are missing.');
        http_response_code(500);
        die('Database is not configured. See README.md for setup instructions.');
    }
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(500);
        die('Database connection failed. Please try again later.');
    }
}

function csrfToken() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function csrfCheck($token) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
