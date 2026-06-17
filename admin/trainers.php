<?php
$admin_title = 'Trainers';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $id = (int)input('id');
    if (input('action') === 'delete' && $id) {
        db()->prepare('DELETE FROM trainers WHERE id = :id')->execute([':id' => $id]);
        flash('Trainer deleted.');
    } elseif (input('action') === 'toggle' && $id) {
        db()->prepare('UPDATE trainers SET is_active = 1 - is_active WHERE id = :id')->execute([':id' => $id]);
        flash('Trainer status updated.');
    }
    header('Location: ' . url('admin/trainers.php'));
    exit;
}

$trainers = fetch_all('SELECT * FROM trainers ORDER BY sort_order, id');
?>
<div class="toolbar">
  <div><div class="page-title" style="margin:0">Trainers</div><div class="page-sub" style="margin:0"><?php echo count($trainers); ?> trainer(s)</div></div>
  <a class="abtn abtn-primary" href="<?php echo e(url('admin/trainer-add.php')); ?>">+ Add Trainer</a>
</div>

<div class="acard">
  <?php if ($trainers): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Photo</th><th>Name</th><th>Specialty</th><th>Experience</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($trainers as $t): $photo = media_url($t['photo']); ?>
        <tr>
          <td><?php if ($photo): ?><img class="thumb" src="<?php echo e($photo); ?>" alt=""><?php else: ?><span class="muted">—</span><?php endif; ?></td>
          <td><strong><?php echo e($t['name']); ?></strong></td>
          <td><?php echo e($t['specialty']); ?></td>
          <td><?php echo e($t['experience']); ?></td>
          <td><span class="badge <?php echo $t['is_active'] ? 'badge-green' : 'badge-gray'; ?>"><?php echo $t['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
          <td>
            <div class="actions-cell">
              <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('admin/trainer-edit.php?id=' . $t['id'])); ?>">Edit</a>
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $t['is_active'] ? 'Deactivate' : 'Activate'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this trainer?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No trainers yet. <a href="<?php echo e(url('admin/trainer-add.php')); ?>">Add your first trainer</a>.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
