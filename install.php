<?php
/**
 * install.php
 * Web-based installer for the Rebrandable Gym Website.
 *
 * What it does:
 *   1. Collects database credentials and admin login.
 *   2. Connects to MySQL (optionally creating the database).
 *   3. Imports the schema + seed data from database.sql.
 *   4. Sets the admin username/password.
 *   5. Writes your DB credentials directly into config.php.
 *   6. Creates install.lock so the installer cannot be run again.
 *
 * SECURITY: After a successful install, DELETE this file (install.php).
 */

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_DIR', __DIR__);
define('LOCK_FILE', BASE_DIR . '/install.lock');
define('CONFIG_FILE', BASE_DIR . '/config.php');
define('SQL_FILE', BASE_DIR . '/database.sql');

$alreadyInstalled = is_file(LOCK_FILE);
$forced  = isset($_GET['force']);
$errors  = [];
$success = false;

function h(?string $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function post(string $k, string $d = ''): string { $v = $_POST[$k] ?? $d; return is_string($v) ? trim($v) : $d; }

/**
 * Split a SQL dump into individual statements, respecting single-quoted
 * strings (incl. '' and \' escapes) and skipping -- and # line comments.
 */
function split_sql(string $sql): array
{
    $statements = [];
    $buffer = '';
    $len = strlen($sql);
    $inString = false;
    for ($i = 0; $i < $len; $i++) {
        $ch = $sql[$i];

        // Skip line comments only when not inside a string and at start of token
        if (!$inString && ($ch === '-' && ($sql[$i + 1] ?? '') === '-')) {
            // consume to end of line
            while ($i < $len && $sql[$i] !== "\n") { $i++; }
            continue;
        }
        if (!$inString && $ch === '#') {
            while ($i < $len && $sql[$i] !== "\n") { $i++; }
            continue;
        }

        if ($ch === "'") {
            if ($inString) {
                // handle escaped quote: backslash before, or doubled ''
                $prev = $sql[$i - 1] ?? '';
                if ($prev === '\\') {
                    $buffer .= $ch;
                    continue;
                }
                if (($sql[$i + 1] ?? '') === "'") {
                    $buffer .= "''";
                    $i++;
                    continue;
                }
                $inString = false;
            } else {
                $inString = true;
            }
            $buffer .= $ch;
            continue;
        }

        if ($ch === ';' && !$inString) {
            $stmt = trim($buffer);
            if ($stmt !== '') { $statements[] = $stmt; }
            $buffer = '';
            continue;
        }

        $buffer .= $ch;
    }
    $tail = trim($buffer);
    if ($tail !== '') { $statements[] = $tail; }
    return $statements;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && (!$alreadyInstalled || $forced)) {
    $host   = post('db_host', 'localhost');
    $name   = post('db_name');
    $user   = post('db_user');
    $pass   = post('db_pass');
    $createDb   = isset($_POST['create_db']);
    $adminUser  = post('admin_user', 'admin');
    $adminPass  = post('admin_pass');

    // Validation
    if ($name === '')      { $errors[] = 'Database name is required.'; }
    if ($user === '')      { $errors[] = 'Database username is required.'; }
    if ($adminUser === '') { $errors[] = 'Admin username is required.'; }
    if (strlen($adminPass) < 5) { $errors[] = 'Admin password must be at least 5 characters.'; }
    if (!is_file(SQL_FILE)) { $errors[] = 'database.sql was not found in this folder.'; }

    if (!$errors) {
        try {
            // 1) Connect (optionally without DB so we can create it)
            $opts = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            if ($createDb) {
                $pdo = new PDO("mysql:host={$host};charset=utf8mb4", $user, $pass, $opts);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$name}`");
            } else {
                $pdo = new PDO("mysql:host={$host};dbname={$name};charset=utf8mb4", $user, $pass, $opts);
            }

            // 2) Import schema
            $sql = file_get_contents(SQL_FILE);
            foreach (split_sql($sql) as $stmt) {
                $pdo->exec($stmt);
            }

            // 3) Set admin credentials (replace seeded default)
            $hash = password_hash($adminPass, PASSWORD_BCRYPT);
            $pdo->exec('DELETE FROM admin_users');
            $ins = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (:u, :p)');
            $ins->execute([':u' => $adminUser, ':p' => $hash]);

            // 4) Write the DB credentials directly into config.php (single source of truth)
            $cfg = file_get_contents(CONFIG_FILE);
            if ($cfg === false) {
                throw new RuntimeException('Could not read config.php.');
            }
            $creds = ['DB_HOST' => $host, 'DB_NAME' => $name, 'DB_USER' => $user, 'DB_PASS' => $pass, 'DB_CHARSET' => 'utf8mb4'];
            foreach ($creds as $key => $val) {
                $literal = var_export($val, true); // safely escaped single-quoted PHP literal
                $pattern = "/define\\(\\s*'" . $key . "'\\s*,\\s*.*?\\);/s";
                $replaced = preg_replace_callback($pattern, function () use ($key, $literal) {
                    return "define('" . $key . "', " . $literal . ");";
                }, $cfg, 1, $count);
                if ($replaced === null || $count === 0) {
                    throw new RuntimeException("Could not update {$key} in config.php. Please edit config.php manually.");
                }
                $cfg = $replaced;
            }
            if (file_put_contents(CONFIG_FILE, $cfg) === false) {
                throw new RuntimeException('Could not write config.php. Make it writable (chmod 644/664), or edit it manually with the credentials you entered.');
            }

            // 5) Lock the installer
            @file_put_contents(LOCK_FILE, 'Installed on ' . date('c') . "\n");

            $success = true;
        } catch (Throwable $e) {
            $errors[] = 'Installation failed: ' . $e->getMessage();
        }
    }
}

// Determine base URL for the success links
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$hostName = $_SERVER['HTTP_HOST'] ?? 'localhost';
$dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
$baseUrl = $scheme . '://' . $hostName . ($dir ?: '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Install | Rebrandable Gym Website</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/admin.css">
<style>
  body{display:flex;align-items:flex-start;justify-content:center;padding:40px 16px;background:radial-gradient(circle at 50% 0%,rgba(225,29,42,.15),transparent 55%),#0f1115}
  .install-card{width:100%;max-width:620px}
  .install-card .logo{text-align:center;font-family:'Barlow Condensed';font-weight:800;font-size:1.8rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px}
  .install-card .logo span{color:#e11d2a}
  .install-card .sub{text-align:center;color:#9aa0ac;font-size:.9rem;margin-bottom:24px}
  .steps{font-size:.82rem;color:#9aa0ac;margin-bottom:18px;line-height:1.7}
  ol.next{padding-left:18px;margin-top:8px;color:#c8ccd4;font-size:.9rem;line-height:1.9}
</style>
</head>
<body>
<div class="install-card">
  <div class="logo">Gym <span>Installer</span></div>
  <div class="sub">Set up your database and admin account</div>

  <?php if ($success): ?>
    <div class="acard">
      <div class="alert alert-success"><strong>Installation complete!</strong> Your gym website is ready.</div>
      <div class="alert alert-danger">
        <strong>Important:</strong> Delete <code>install.php</code> now for security.
      </div>
      <p class="muted" style="margin-bottom:14px">Next steps:</p>
      <ol class="next">
        <li>Visit your website: <a href="<?php echo h($baseUrl); ?>/index.php"><?php echo h($baseUrl); ?>/</a></li>
        <li>Log in to the admin panel: <a href="<?php echo h($baseUrl); ?>/admin/login.php"><?php echo h($baseUrl); ?>/admin/login.php</a></li>
        <li>Open <strong>Site Settings</strong> to rebrand the site.</li>
      </ol>
      <a class="abtn abtn-primary mt-2" href="<?php echo h($baseUrl); ?>/admin/login.php" style="margin-top:18px">Go to Admin Login</a>
    </div>

  <?php elseif ($alreadyInstalled && !$forced): ?>
    <div class="acard">
      <div class="alert alert-info">This site appears to be <strong>already installed</strong> (install.lock exists).</div>
      <p class="muted">For security, please delete <code>install.php</code>. If you really need to re-run the installer,
        delete <code>install.lock</code> first, or open
        <a href="?force=1">install.php?force=1</a> (this will overwrite existing data).</p>
      <a class="abtn abtn-ghost mt-2" href="<?php echo h($baseUrl); ?>/admin/login.php">Go to Admin Login</a>
    </div>

  <?php else: ?>
    <?php if ($errors): ?>
      <div class="alert alert-error"><?php echo implode('<br>', array_map('h', $errors)); ?></div>
    <?php endif; ?>
    <?php if ($forced): ?>
      <div class="alert alert-info">Force mode is ON. Running the installer will reset existing tables and the admin account.</div>
    <?php endif; ?>

    <form method="post" class="acard" action="install.php<?php echo $forced ? '?force=1' : ''; ?>">
      <h3 class="section-divider" style="margin-top:0">Database Connection</h3>
      <div class="form-grid">
        <div class="fg"><label>Database Host</label><input type="text" name="db_host" value="<?php echo h(post('db_host', 'localhost') ?: 'localhost'); ?>"></div>
        <div class="fg"><label>Database Name</label><input type="text" name="db_name" value="<?php echo h(post('db_name', 'gym_website') ?: 'gym_website'); ?>" required></div>
        <div class="fg"><label>Database Username</label><input type="text" name="db_user" value="<?php echo h(post('db_user')); ?>" required></div>
        <div class="fg"><label>Database Password</label><input type="text" name="db_pass" value="<?php echo h(post('db_pass')); ?>"></div>
      </div>
      <div class="fg">
        <div class="checkbox-row">
          <input type="checkbox" name="create_db" id="create_db" checked>
          <label for="create_db" style="margin:0">Create the database if it does not exist</label>
        </div>
        <div class="hint">Uncheck this if your host pre-creates databases (most shared hosting does).</div>
      </div>

      <h3 class="section-divider">Admin Account</h3>
      <div class="form-grid">
        <div class="fg"><label>Admin Username</label><input type="text" name="admin_user" value="<?php echo h(post('admin_user', 'admin') ?: 'admin'); ?>" required></div>
        <div class="fg"><label>Admin Password</label><input type="text" name="admin_pass" value="<?php echo h(post('admin_pass', 'admin123') ?: 'admin123'); ?>" required></div>
      </div>
      <div class="steps">The installer will import <code>database.sql</code>, create your admin account, write your
        credentials into <code>config.php</code>, and lock itself when finished.</div>

      <button type="submit" class="abtn abtn-primary" style="width:100%;justify-content:center;padding:13px">Run Installation</button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>
