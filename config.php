<?php
/**
 * config.php
 * Central configuration & database connection (PDO).
 * Edit the database credentials below for your hosting environment.
 */

declare(strict_types=1);

/* ----------------------------------------------------------------
 | DATABASE CREDENTIALS  --  EDIT THESE FOR YOUR SERVER
 | (install.php fills these in automatically during setup.)
 * ---------------------------------------------------------------- */
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'gym_website');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/* ----------------------------------------------------------------
 | SITE PATHS
 * ---------------------------------------------------------------- */
// Auto-detect base URL. You may hardcode it for production, e.g. https://yourgym.com
// HTTPS detection is proxy-aware (Cloudflare / load balancers terminate TLS and
// forward plain HTTP to PHP). Without this, asset links become http:// on an
// https:// page and browsers block them as "mixed content" (unstyled site).
$__https = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
        || (strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https')
        || (strtolower((string)($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '')) === 'on')
        || (strtolower((string)($_SERVER['HTTP_FRONT_END_HTTPS'] ?? '')) === 'on')
        || ((int)($_SERVER['SERVER_PORT'] ?? 80) === 443);
$__scheme = $__https ? 'https' : 'http';
$__host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
$__dir    = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
// If the script is inside /admin, strip it so SITE_URL always points to the web root.
$__dir    = preg_replace('#/admin$#', '', $__dir);
if ($__dir === false) { $__dir = ''; }
define('SITE_URL', $__scheme . '://' . $__host . ($__dir ?: ''));

define('ROOT_PATH', __DIR__);
define('UPLOAD_DIR', __DIR__ . '/uploads');
define('UPLOAD_URL', SITE_URL . '/uploads');

/* ----------------------------------------------------------------
 | SESSION
 * ---------------------------------------------------------------- */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/* ----------------------------------------------------------------
 | ERROR REPORTING  (disable display in production)
 * ---------------------------------------------------------------- */
ini_set('display_errors', '0');
error_reporting(E_ALL);

/* ----------------------------------------------------------------
 | DATABASE CONNECTION (PDO)
 * ---------------------------------------------------------------- */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        http_response_code(500);
        die('Database connection failed. Please check your configuration in config.php.');
    }
    return $pdo;
}

require_once __DIR__ . '/functions.php';
