<?php
$admin_title = 'Classes';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $id = (int)input('id');
    if (input('action') === 'delete' && $id) {
        db()->prepare('DELETE FROM classes WHERE id = :id')->execute([':id' => $id]);
        flash('Class deleted.');
    } elseif (input('action') === 'toggle' && $id) {
        db()->prepare('UPDATE classes SET is_active = 1 - is_active WHERE id = :id')->execute([':id' => $id]);
        flash('Class status updated.');
    }
    header('Location: ' . url('admin/classes.php'));
    exit;
}

$classes = fetch_all('SELECT * FROM classes ORDER BY sort_order, id');
?>
<div class="toolbar">
  <div><div class="page-title" style="margin:0">Classes</div><div class="page-sub" style="margin:0"><?php echo count($classes); ?> class(es)</div></div>
  <a class="abtn abtn-primary" href="<?php echo e(url('admin/class-add.php')); ?>">+ Add Class</a>
</div>

<div class="acard">
  <?php if ($classes): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Class</th><th>Trainer</th><th>Time</th><th>Days</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($classes as $c): ?>
        <tr>
          <td><strong><?php echo e($c['class_name']); ?></strong></td>
          <td><?php echo e($c['trainer_name']); ?></td>
          <td><?php echo e($c['class_time']); ?></td>
          <td><?php echo e($c['class_days']); ?></td>
          <td><?php echo e($c['duration']); ?></td>
          <td><span class="badge <?php echo $c['is_active'] ? 'badge-green' : 'badge-gray'; ?>"><?php echo $c['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
          <td>
            <div class="actions-cell">
              <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('admin/class-edit.php?id=' . $c['id'])); ?>">Edit</a>
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $c['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $c['is_active'] ? 'Deactivate' : 'Activate'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this class?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $c['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No classes yet. <a href="<?php echo e(url('admin/class-add.php')); ?>">Add your first class</a>.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
