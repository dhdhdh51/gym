<?php
/**
 * header.php  -  Shared public site header.
 * Set $page_key (string) and optionally $seo_custom (array) before including.
 */
require_once __DIR__ . '/config.php';

$page_key   = $page_key   ?? 'home';
$seo_custom = $seo_custom ?? [];

$brand     = setting('brand_name', 'Gym');
$logo      = media_url(setting('logo'));
$favicon   = media_url(setting('favicon'));
$primary   = setting('primary_color', '#e11d2a');
$secondary = setting('secondary_color', '#0d0d0d');
$btnColor  = setting('button_color', '#e11d2a');

// active nav helper
$current = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
function nav_active(string $file, string $current): string
{
    return $file === $current ? ' active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php echo render_seo($page_key, $seo_custom); ?>
<?php if ($favicon): ?><link rel="icon" href="<?php echo e($favicon); ?>"><?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo e(url('assets/css/style.css')); ?>">
<style>
:root{
  --primary: <?php echo e($primary); ?>;
  --secondary: <?php echo e($secondary); ?>;
  --btn: <?php echo e($btnColor); ?>;
}
</style>
<?php
// Google Analytics
if ($ga = setting('google_analytics')) {
    echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . e($ga) . "\"></script>\n";
    echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . e($ga) . "');</script>\n";
}
// Facebook Pixel
if ($fb = setting('facebook_pixel')) {
    echo "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','" . e($fb) . "');fbq('track','PageView');</script>\n";
}
// Custom per-page header scripts
echo seo_header_scripts($page_key);
?>
</head>
<body>
<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="<?php echo e(url('index.php')); ?>" class="brand">
      <?php if ($logo): ?>
        <img src="<?php echo e($logo); ?>" alt="<?php echo e($brand); ?>" class="brand-logo">
      <?php else: ?>
        <span class="brand-name"><?php echo e($brand); ?></span>
      <?php endif; ?>
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a class="<?php echo nav_active('index.php', $current); ?>" href="<?php echo e(url('index.php')); ?>">Home</a></li>
        <li><a class="<?php echo nav_active('about.php', $current); ?>" href="<?php echo e(url('about.php')); ?>">About</a></li>
        <li><a class="<?php echo nav_active('services.php', $current); ?>" href="<?php echo e(url('services.php')); ?>">Services</a></li>
        <li><a class="<?php echo nav_active('plans.php', $current); ?>" href="<?php echo e(url('plans.php')); ?>">Plans</a></li>
        <li><a class="<?php echo nav_active('trainers.php', $current); ?>" href="<?php echo e(url('trainers.php')); ?>">Trainers</a></li>
        <li><a class="<?php echo nav_active('classes.php', $current); ?>" href="<?php echo e(url('classes.php')); ?>">Classes</a></li>
        <li><a class="<?php echo nav_active('gallery.php', $current); ?>" href="<?php echo e(url('gallery.php')); ?>">Gallery</a></li>
        <li><a class="<?php echo nav_active('blog.php', $current); ?>" href="<?php echo e(url('blog.php')); ?>">Blog</a></li>
        <li><a class="<?php echo nav_active('contact.php', $current); ?>" href="<?php echo e(url('contact.php')); ?>">Contact</a></li>
      </ul>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary nav-cta">Book Free Trial</a>
    </nav>
  </div>
</header>
<main>
