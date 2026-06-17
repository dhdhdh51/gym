<?php
$admin_title = 'Dashboard';
require_once __DIR__ . '/includes/admin-header.php';

$totalEnq   = count_rows('enquiries');
$newEnq     = count_rows('enquiries', "status = 'new'");
$trials     = count_rows('enquiries', "enquiry_type = 'free_trial'");
$plans      = count_rows('membership_plans');
$trainers   = count_rows('trainers');
$classes    = count_rows('classes');
$posts      = count_rows('blog_posts');
$testi      = count_rows('testimonials');

$recent = fetch_all('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 8');
?>
<div class="stat-grid">
  <div class="stat-card accent"><span class="ico">📥</span><div class="num"><?php echo $totalEnq; ?></div><div class="label">Total Enquiries</div></div>
  <div class="stat-card"><span class="ico">🆕</span><div class="num"><?php echo $newEnq; ?></div><div class="label">New Enquiries</div></div>
  <div class="stat-card"><span class="ico">🎟️</span><div class="num"><?php echo $trials; ?></div><div class="label">Trial Bookings</div></div>
  <div class="stat-card"><span class="ico">💳</span><div class="num"><?php echo $plans; ?></div><div class="label">Membership Plans</div></div>
  <div class="stat-card"><span class="ico">🏋️</span><div class="num"><?php echo $trainers; ?></div><div class="label">Trainers</div></div>
  <div class="stat-card"><span class="ico">📅</span><div class="num"><?php echo $classes; ?></div><div class="label">Classes</div></div>
  <div class="stat-card"><span class="ico">📝</span><div class="num"><?php echo $posts; ?></div><div class="label">Blog Posts</div></div>
  <div class="stat-card"><span class="ico">⭐</span><div class="num"><?php echo $testi; ?></div><div class="label">Testimonials</div></div>
</div>

<div class="acard mb-2">
  <h3 class="section-divider" style="margin-top:0">Quick Actions</h3>
  <div class="quick-actions">
    <a class="abtn abtn-primary" href="<?php echo e(url('admin/plan-add.php')); ?>">+ Add Plan</a>
    <a class="abtn abtn-ghost" href="<?php echo e(url('admin/trainer-add.php')); ?>">+ Add Trainer</a>
    <a class="abtn abtn-ghost" href="<?php echo e(url('admin/class-add.php')); ?>">+ Add Class</a>
    <a class="abtn abtn-ghost" href="<?php echo e(url('admin/blog-add.php')); ?>">+ Add Blog Post</a>
    <a class="abtn abtn-ghost" href="<?php echo e(url('admin/gallery.php')); ?>">+ Manage Gallery</a>
    <a class="abtn abtn-ghost" href="<?php echo e(url('admin/settings.php')); ?>">⚙️ Site Settings</a>
  </div>
</div>

<div class="acard">
  <div class="toolbar" style="margin-bottom:14px">
    <h3 style="margin:0">Recent Enquiries</h3>
    <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('admin/enquiries.php')); ?>">View All</a>
  </div>
  <?php if ($recent): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Type</th><th>Phone</th><th>Email</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach ($recent as $r): ?>
        <tr>
          <td><?php echo e($r['name']); ?></td>
          <td><span class="badge <?php echo $r['enquiry_type'] === 'free_trial' ? 'badge-blue' : 'badge-gray'; ?>"><?php echo $r['enquiry_type'] === 'free_trial' ? 'Free Trial' : 'Contact'; ?></span></td>
          <td><?php echo e($r['phone']); ?></td>
          <td><?php echo e($r['email']); ?></td>
          <td><span class="badge <?php echo $r['status'] === 'new' ? 'badge-amber' : 'badge-green'; ?>"><?php echo e(ucfirst($r['status'])); ?></span></td>
          <td class="muted"><?php echo e(format_date($r['created_at'], 'M j, Y')); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No enquiries yet. They will appear here when visitors submit your forms.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
