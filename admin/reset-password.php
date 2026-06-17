<?php
/**
 * reset-password.php
 * Emergency admin password reset tool.
 * Open this in browser: https://yoursite.com/admin/reset-password.php
 * It will reset the admin password to "admin123" and show you the DB status.
 * DELETE THIS FILE after use!
 */
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../config.php';

echo "<h2>Admin Password Reset & DB Diagnostic</h2><pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_NAME: " . DB_NAME . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_PASS: " . (DB_PASS === '' ? '(empty!)' : '***set***') . "\n\n";

echo "--- Testing Database Connection ---\n";
try {
    $pdo = db();
    echo "Connection: SUCCESS\n\n";
    
    // Check if admin_users table exists
    echo "--- Checking admin_users table ---\n";
    try {
        $users = $pdo->query("SELECT id, username, password_hash FROM admin_users")->fetchAll();
        echo "Found " . count($users) . " admin user(s):\n";
        foreach ($users as $u) {
            $hashOk = password_verify('admin123', $u['password_hash']);
            echo "  ID={$u['id']} Username={$u['username']} Hash=" . substr($u['password_hash'], 0, 20) . "... PasswordVerify(admin123)=" . ($hashOk ? "OK" : "FAIL") . "\n";
        }
    } catch (Throwable $e) {
        echo "Table admin_users not found or error: " . $e->getMessage() . "\n";
        echo "You probably need to run install.php first!\n";
    }
    
    echo "\n--- Resetting password to admin123 ---\n";
    $newHash = password_hash('admin123', PASSWORD_BCRYPT);
    echo "New hash: " . $newHash . "\n";
    echo "Verify new hash: " . (password_verify('admin123', $newHash) ? "OK" : "FAIL") . "\n";
    
    try {
        // Delete all existing and insert fresh
        $pdo->exec("DELETE FROM admin_users");
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
        $stmt->execute(['admin', $newHash]);
        echo "Admin user reset: username=admin password=admin123\n";
        echo "\nSUCCESS! Now go to login.php and use admin / admin123\n";
    } catch (Throwable $e) {
        echo "Failed to reset: " . $e->getMessage() . "\n";
    }
    
} catch (Throwable $e) {
    echo "Connection: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "SOLUTION:\n";
    echo "1. Open config.php in a text editor\n";
    echo "2. Set the correct database credentials:\n";
    echo "   define('DB_HOST', 'localhost');  // or 127.0.0.1\n";
    echo "   define('DB_NAME', 'your_database_name');\n";
    echo "   define('DB_USER', 'your_database_username');\n";
    echo "   define('DB_PASS', 'your_database_password');\n";
    echo "3. These credentials are from your hosting panel (cPanel > MySQL Databases)\n";
}

echo "</pre>";
echo "<p><b style='color:red'>DELETE this file (reset-password.php) after you fix the issue!</b></p>";
