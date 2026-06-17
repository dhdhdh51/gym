<?php
/**
 * debug.php - Diagnostic page to identify hosting issues.
 * DELETE THIS FILE after debugging!
 */
ini_set('display_errors', '1');
error_reporting(E_ALL);
echo "<h2>Server Diagnostics</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . "\n";
echo "Script: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'unknown') . "\n";
echo "HTTPS: " . (($_SERVER['HTTPS'] ?? 'off')) . "\n";
echo "X-Forwarded-Proto: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'not set') . "\n\n";

echo "--- Extensions ---\n";
echo "PDO: " . (extension_loaded('pdo') ? 'YES' : 'NO') . "\n";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "\n";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "\n";
echo "Fileinfo: " . (extension_loaded('fileinfo') ? 'YES' : 'NO') . "\n";
echo "Session: " . (extension_loaded('session') ? 'YES' : 'NO') . "\n\n";

echo "--- File Checks ---\n";
echo "config.php exists: " . (file_exists(__DIR__ . '/config.php') ? 'YES' : 'NO') . "\n";
echo "functions.php exists: " . (file_exists(__DIR__ . '/functions.php') ? 'YES' : 'NO') . "\n";
echo "assets/css/style.css exists: " . (file_exists(__DIR__ . '/assets/css/style.css') ? 'YES' : 'NO') . "\n";
echo "admin/login.php exists: " . (file_exists(__DIR__ . '/admin/login.php') ? 'YES' : 'NO') . "\n";
echo "install.php exists: " . (file_exists(__DIR__ . '/install.php') ? 'YES' : 'NO') . "\n\n";

echo "--- Database Test ---\n";
try {
    require_once __DIR__ . '/config.php';
    echo "DB_HOST: " . DB_HOST . "\n";
    echo "DB_NAME: " . DB_NAME . "\n";
    echo "DB_USER: " . DB_USER . "\n";
    echo "DB_PASS: " . (DB_PASS === '' ? '(empty)' : '***set***') . "\n";
    $pdo = db();
    echo "Connection: SUCCESS\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "\n";
    echo "Tables: " . implode(', ', $tables) . "\n";
} catch (Throwable $e) {
    echo "Connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
echo "</pre>";
echo "<p><b>DELETE this file after debugging!</b></p>";
