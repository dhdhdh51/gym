<?php
/**
 * sitemap.php  -  Dynamic XML sitemap.
 * Add this rule to .htaccess to serve at /sitemap.xml:
 *   RewriteRule ^sitemap\.xml$ sitemap.php [L]
 */
require_once __DIR__ . '/config.php';
header('Content-Type: application/xml; charset=utf-8');

$pages = [
    'index.php', 'about.php', 'services.php', 'plans.php', 'trainers.php',
    'classes.php', 'gallery.php', 'testimonials.php', 'blog.php', 'contact.php',
];

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as $p) {
    $loc = $p === 'index.php' ? SITE_URL . '/' : SITE_URL . '/' . $p;
    echo "  <url>\n";
    echo '    <loc>' . e($loc) . "</loc>\n";
    echo '    <changefreq>weekly</changefreq>' . "\n";
    echo '    <priority>' . ($p === 'index.php' ? '1.0' : '0.8') . "</priority>\n";
    echo "  </url>\n";
}

// Blog posts
$posts = fetch_all("SELECT slug, COALESCE(updated_at, published_at, created_at) AS lastmod FROM blog_posts WHERE status='published' ORDER BY id DESC");
foreach ($posts as $post) {
    $loc = SITE_URL . '/post.php?slug=' . rawurlencode($post['slug']);
    echo "  <url>\n";
    echo '    <loc>' . e($loc) . "</loc>\n";
    if (!empty($post['lastmod'])) {
        echo '    <lastmod>' . e(date('Y-m-d', strtotime((string)$post['lastmod']))) . "</lastmod>\n";
    }
    echo '    <changefreq>monthly</changefreq>' . "\n";
    echo '    <priority>0.6</priority>' . "\n";
    echo "  </url>\n";
}

echo '</urlset>';
