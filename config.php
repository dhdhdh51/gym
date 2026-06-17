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
        // Show a styled error page instead of plain text
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
        echo '<title>Database Error</title>';
        echo '<style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:#0a0a0a;color:#f4f4f5;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}';
        echo '.card{background:#161618;border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:40px;max-width:520px;width:100%;text-align:center}';
        echo 'h1{font-size:1.8rem;margin-bottom:12px;color:#e11d2a}p{color:#a1a1aa;margin-bottom:16px;line-height:1.6}';
        echo 'code{background:#1a1a1d;padding:3px 8px;border-radius:6px;font-size:.85rem;color:#fca5a5}';
        echo '.btn{display:inline-block;padding:12px 24px;background:#e11d2a;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;margin-top:10px}</style></head>';
        echo '<body><div class="card"><h1>Database Connection Failed</h1>';
        echo '<p>The website cannot connect to the database. This usually means:</p>';
        echo '<p style="text-align:left;color:#d4d4d8">';
        echo '1. You have not run the installer yet<br>';
        echo '2. Database credentials in <code>config.php</code> are incorrect<br>';
        echo '3. MySQL/MariaDB service is not running</p>';
        echo '<p>Run the installer to set up your database:</p>';
        echo '<a class="btn" href="install.php">Run Installer</a>';
        echo '</div></body></html>';
        exit;
    }
    return $pdo;
}

require_once __DIR__ . '/functions.php';
