<?php
/**
 * functions.php
 * Shared helper functions: settings, SEO, security, uploads, auth.
 */

declare(strict_types=1);

/* =================================================================
 | OUTPUT ESCAPING
 * ================================================================= */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* =================================================================
 | SITE SETTINGS  (key/value, cached per request)
 * ================================================================= */
function all_settings(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    try {
        $rows = db()->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll();
        foreach ($rows as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Throwable $e) {
        $cache = [];
    }
    return $cache;
}

function setting(string $key, string $default = ''): string
{
    $settings = all_settings();
    return isset($settings[$key]) && $settings[$key] !== null ? (string)$settings[$key] : $default;
}

function update_setting(string $key, string $value): void
{
    $stmt = db()->prepare(
        'INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v)
         ON DUPLICATE KEY UPDATE setting_value = :v2'
    );
    $stmt->execute([':k' => $key, ':v' => $value, ':v2' => $value]);
}

/* =================================================================
 | SEO  (per page)
 * ================================================================= */
function get_seo(string $pageKey): array
{
    static $cache = [];
    if (isset($cache[$pageKey])) {
        return $cache[$pageKey];
    }
    $stmt = db()->prepare('SELECT * FROM seo_settings WHERE page_key = :k LIMIT 1');
    $stmt->execute([':k' => $pageKey]);
    $cache[$pageKey] = $stmt->fetch() ?: [];
    return $cache[$pageKey];
}

/**
 * Render the full <head> SEO block. Pass overrides (for blog posts) via $custom.
 */
function render_seo(string $pageKey, array $custom = []): string
{
    $seo = array_merge(get_seo($pageKey), array_filter($custom, function($v) { return $v !== null && $v !== ''; }));

    $title       = $seo['meta_title']       ?? setting('default_meta_title', 'Premium Gym Website');
    $description = $seo['meta_description']  ?? setting('default_meta_description');
    $keywords    = $seo['meta_keywords']     ?? setting('default_meta_keywords');
    $robots      = $seo['robots_meta']       ?? 'index, follow';
    $canonical   = $seo['canonical_url']     ?? current_url();
    $ogTitle     = $seo['og_title']          ?? $title;
    $ogDesc      = $seo['og_description']    ?? $description;
    $ogImage     = $seo['og_image']          ?? media_url(setting('hero_image'));
    $twTitle     = $seo['twitter_title']     ?? $ogTitle;
    $twDesc      = $seo['twitter_description'] ?? $ogDesc;
    $twImage     = $seo['twitter_image']     ?? $ogImage;
    $schema      = $seo['schema_json']       ?? '';
    $brand       = setting('brand_name', 'Gym');

    $out  = '<title>' . e($title) . '</title>' . "\n";
    $out .= '<meta name="description" content="' . e($description) . '">' . "\n";
    if ($keywords) {
        $out .= '<meta name="keywords" content="' . e($keywords) . '">' . "\n";
    }
    $out .= '<meta name="robots" content="' . e($robots) . '">' . "\n";
    $out .= '<link rel="canonical" href="' . e($canonical) . '">' . "\n";
    // Open Graph
    $out .= '<meta property="og:type" content="website">' . "\n";
    $out .= '<meta property="og:site_name" content="' . e($brand) . '">' . "\n";
    $out .= '<meta property="og:title" content="' . e($ogTitle) . '">' . "\n";
    $out .= '<meta property="og:description" content="' . e($ogDesc) . '">' . "\n";
    $out .= '<meta property="og:url" content="' . e($canonical) . '">' . "\n";
    if ($ogImage) {
        $out .= '<meta property="og:image" content="' . e($ogImage) . '">' . "\n";
    }
    // Twitter
    $out .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $out .= '<meta name="twitter:title" content="' . e($twTitle) . '">' . "\n";
    $out .= '<meta name="twitter:description" content="' . e($twDesc) . '">' . "\n";
    if ($twImage) {
        $out .= '<meta name="twitter:image" content="' . e($twImage) . '">' . "\n";
    }
    // Schema JSON-LD
    if (trim((string)$schema) !== '') {
        $out .= '<script type="application/ld+json">' . $schema . '</script>' . "\n";
    }
    // Search console verification
    if ($sc = setting('search_console')) {
        $out .= '<meta name="google-site-verification" content="' . e($sc) . '">' . "\n";
    }
    return $out;
}

function seo_header_scripts(string $pageKey): string
{
    $seo = get_seo($pageKey);
    return $seo['custom_header_scripts'] ?? '';
}

function seo_footer_scripts(string $pageKey): string
{
    $seo = get_seo($pageKey);
    return $seo['custom_footer_scripts'] ?? '';
}

/* =================================================================
 | URL HELPERS
 * ================================================================= */
function current_url(): string
{
    $https = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
          || (strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https')
          || (strtolower((string)($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '')) === 'on')
          || (strtolower((string)($_SERVER['HTTP_FRONT_END_HTTPS'] ?? '')) === 'on')
          || ((int)($_SERVER['SERVER_PORT'] ?? 80) === 443);
    $scheme = $https ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $uri    = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    return $scheme . '://' . $host . $uri;
}

function url(string $path = ''): string
{
    return SITE_URL . '/' . ltrim($path, '/');
}

/**
 * Resolve a stored media value into a usable URL.
 * Accepts full http(s) URLs (returned as-is) or filenames stored in /uploads.
 */
function media_url(?string $value): string
{
    $value = trim((string)$value);
    if ($value === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $value) || str_starts_with($value, 'data:')) {
        return $value;
    }
    if (str_starts_with($value, '/')) {
        return SITE_URL . $value;
    }
    return UPLOAD_URL . '/' . $value;
}

/* =================================================================
 | CONTACT / SOCIAL HELPERS
 * ================================================================= */
function whatsapp_link(string $text = ''): string
{
    $number = preg_replace('/\D+/', '', setting('whatsapp_number'));
    $link = 'https://wa.me/' . $number;
    if ($text !== '') {
        $link .= '?text=' . rawurlencode($text);
    }
    return $link;
}

function tel_link(): string
{
    $number = preg_replace('/[^\d+]/', '', setting('phone_number'));
    return 'tel:' . $number;
}

/* =================================================================
 | CSRF PROTECTION
 * ================================================================= */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_csrf(): void
{
    if (!verify_csrf()) {
        http_response_code(419);
        die('Invalid or expired form token. Please go back and try again.');
    }
}

/* =================================================================
 | INPUT HELPERS
 * ================================================================= */
function input(string $key, string $default = ''): string
{
    $val = $_POST[$key] ?? $_GET[$key] ?? $default;
    return is_string($val) ? trim($val) : $default;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim((string)$text, '-');
    return $text !== '' ? $text : 'post-' . time();
}

/* =================================================================
 | FILE UPLOADS  (images only)
 * ================================================================= */
function handle_upload(string $field, ?string $existing = ''): string
{
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return (string)$existing;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return (string)$existing;
    }
    // Size limit 5MB
    if ($file['size'] > 5 * 1024 * 1024) {
        return (string)$existing;
    }
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!isset($allowed[$mime])) {
        return (string)$existing;
    }
    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0755, true);
    }
    $ext  = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
    $dest = UPLOAD_DIR . '/' . $name;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $name;
    }
    return (string)$existing;
}

/* =================================================================
 | AUTH  (admin sessions)
 * ================================================================= */
function is_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . url('admin/login.php'));
        exit;
    }
}

function admin_login(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = :u LIMIT 1');
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']       = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        return true;
    }
    return false;
}

function admin_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/* =================================================================
 | FLASH MESSAGES
 * ================================================================= */
function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'][] = ['msg' => $message, 'type' => $type];
}

function render_flash(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $out = '';
    foreach ($_SESSION['flash'] as $f) {
        $out .= '<div class="alert alert-' . e($f['type']) . '">' . e($f['msg']) . '</div>';
    }
    unset($_SESSION['flash']);
    return $out;
}

/* =================================================================
 | SMALL DATA FETCH HELPERS
 * ================================================================= */
function fetch_all(string $sql, array $params = []): array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetch_one(string $sql, array $params = []): ?array
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row ?: null;
}

function count_rows(string $table, string $where = '1', array $params = []): int
{
    $stmt = db()->prepare("SELECT COUNT(*) AS c FROM {$table} WHERE {$where}");
    $stmt->execute($params);
    return (int)($stmt->fetch()['c'] ?? 0);
}

/* =================================================================
 | PRESENTATION HELPERS
 * ================================================================= */
function features_to_list(?string $features): array
{
    if (!$features) {
        return [];
    }
    $lines = preg_split('/\r\n|\r|\n/', $features);
    return array_values(array_filter(array_map('trim', $lines), function($l) { return $l !== ''; }));
}

function money(?string $value): string
{
    $value = trim((string)$value);
    if ($value === '') {
        return '';
    }
    if (is_numeric(str_replace([',', ' '], '', $value))) {
        return '₹' . number_format((float)str_replace([',', ' '], '', $value));
    }
    return $value;
}

function format_date(?string $date, string $fmt = 'M j, Y'): string
{
    if (!$date) {
        return '';
    }
    $ts = strtotime($date);
    return $ts ? date($fmt, $ts) : '';
}

function star_rating(int $rating): string
{
    $rating = max(0, min(5, $rating));
    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}

function excerpt(string $html, int $words = 28): string
{
    $text = trim(strip_tags($html));
    $parts = preg_split('/\s+/', $text);
    if (count($parts) <= $words) {
        return $text;
    }
    return implode(' ', array_slice($parts, 0, $words)) . '…';
}
