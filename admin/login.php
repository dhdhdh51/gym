<?php
require_once __DIR__ . '/../config.php';

if (is_logged_in()) {
    header('Location: ' . url('admin/dashboard.php'));
    exit;
}

$error = '';
if (is_post()) {
    if (!verify_csrf()) {
        $error = 'Session expired. Please try again.';
    } else {
        $u = input('username');
        $p = input('password');
        if (admin_login($u, $p)) {
            header('Location: ' . url('admin/dashboard.php'));
            exit;
        }
        $error = 'Invalid username or password.';
    }
}
$brand = setting('brand_name', 'Gym Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | <?php echo e($brand); ?></title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="login-page">
  <form class="login-card" method="post" action="<?php echo e(url('admin/login.php')); ?>">
    <?php echo csrf_field(); ?>
    <div class="logo"><?php echo e($brand); ?></div>
    <p class="sub">Sign in to your admin dashboard</p>
    <?php if ($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>
    <div class="fg">
      <label>Username</label>
      <input type="text" name="username" autofocus required>
    </div>
    <div class="fg">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit" class="abtn abtn-primary" style="width:100%;justify-content:center;padding:13px">Login</button>
    <p class="muted" style="text-align:center;margin-top:18px;font-size:.8rem">Default: admin / admin123</p>
  </form>
</div>
</body>
</html>
