<?php
/**
 * robots.php  -  Dynamic robots.txt.
 * Add this rule to .htaccess to serve at /robots.txt:
 *   RewriteRule ^robots\.txt$ robots.php [L]
 */
require_once __DIR__ . '/config.php';
header('Content-Type: text/plain; charset=utf-8');

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /admin/\n";
echo "Disallow: /uploads/\n";
echo "Disallow: /thank-you.php\n";
echo "\n";
echo "Sitemap: " . SITE_URL . "/sitemap.xml\n";
