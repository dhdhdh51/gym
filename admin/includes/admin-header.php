<?php
/**
 * admin-header.php
 * Include at top of every protected admin page.
 * Set $admin_title before including.
 */
require_once __DIR__ . '/../../config.php';
require_login();

$admin_title = $admin_title ?? 'Dashboard';
$brand = setting('brand_name', 'Gym');
$current = basename($_SERVER['SCRIPT_NAME'] ?? '');

$nav = [
    'Main' => [
        ['dashboard.php', 'Dashboard', '📊'],
    ],
    'Content' => [
        ['plans.php', 'Membership Plans', '💳'],
        ['trainers.php', 'Trainers', '🏋️'],
        ['classes.php', 'Classes', '📅'],
        ['gallery.php', 'Gallery', '🖼️'],
        ['transformations.php', 'Transformations', '🔥'],
        ['testimonials.php', 'Testimonials', '⭐'],
        ['blog-manager.php', 'Blog', '📝'],
    ],
    'Leads' => [
        ['enquiries.php', 'Enquiries & Trials', '📥'],
    ],
    'Configuration' => [
        ['settings.php', 'Site Settings', '⚙️'],
        ['contact-settings.php', 'Contact Settings', '📞'],
        ['seo-manager.php', 'SEO Manager', '🔍'],
    ],
];

// blog-add/edit & *-add/edit should highlight their parent
$parentMap = [
    'plan-add.php' => 'plans.php', 'plan-edit.php' => 'plans.php',
    'trainer-add.php' => 'trainers.php', 'trainer-edit.php' => 'trainers.php',
    'class-add.php' => 'classes.php', 'class-edit.php' => 'classes.php',
    'blog-add.php' => 'blog-manager.php', 'blog-edit.php' => 'blog-manager.php',
];
$activeFile = $parentMap[$current] ?? $current;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e($admin_title); ?> | Admin</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrap">
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand"><?php echo e($brand); ?> <span>Admin</span></div>
    <nav class="sidebar-nav">
      <?php foreach ($nav as $group => $items): ?>
        <div class="group-label"><?php echo e($group); ?></div>
        <?php foreach ($items as $item): ?>
          <a class="<?php echo $activeFile === $item[0] ? 'active' : ''; ?>" href="<?php echo e(url('admin/' . $item[0])); ?>">
            <span class="ico"><?php echo $item[2]; ?></span><?php echo e($item[1]); ?>
          </a>
        <?php endforeach; ?>
      <?php endforeach; ?>
      <div class="group-label">Site</div>
      <a href="<?php echo e(url('index.php')); ?>" target="_blank"><span class="ico">🌐</span>View Website</a>
      <a href="<?php echo e(url('admin/logout.php')); ?>"><span class="ico">🚪</span>Logout</a>
    </nav>
  </aside>

  <div class="main">
    <header class="topbar">
      <div style="display:flex;align-items:center;gap:12px">
        <button class="sidebar-toggle" id="sidebarToggle">&#9776;</button>
        <h1><?php echo e($admin_title); ?></h1>
      </div>
      <div class="topbar-right">
        <span>Hi, <?php echo e($_SESSION['admin_username'] ?? 'Admin'); ?></span>
        <a href="<?php echo e(url('admin/logout.php')); ?>" class="abtn abtn-ghost abtn-sm">Logout</a>
      </div>
    </header>
    <div class="content">
      <?php echo render_flash(); ?>
