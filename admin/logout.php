<?php
require_once __DIR__ . '/../config.php';
admin_logout();
header('Location: ' . url('admin/login.php'));
exit;
