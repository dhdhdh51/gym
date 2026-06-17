<?php
$admin_title = 'Membership Plans';
require_once __DIR__ . '/includes/admin-header.php';

// Handle delete + toggle
if (is_post()) {
    require_csrf();
    $id = (int)input('id');
    if (input('action') === 'delete' && $id) {
        db()->prepare('DELETE FROM membership_plans WHERE id = :id')->execute([':id' => $id]);
        flash('Plan deleted.');
    } elseif (input('action') === 'toggle' && $id) {
        db()->prepare('UPDATE membership_plans SET is_active = 1 - is_active WHERE id = :id')->execute([':id' => $id]);
        flash('Plan status updated.');
    }
    header('Location: ' . url('admin/plans.php'));
    exit;
}

$plans = fetch_all('SELECT * FROM membership_plans ORDER BY sort_order, id');
?>
<div class="toolbar">
  <div><div class="page-title" style="margin:0">Membership Plans</div><div class="page-sub" style="margin:0"><?php echo count($plans); ?> plan(s)</div></div>
  <a class="abtn abtn-primary" href="<?php echo e(url('admin/plan-add.php')); ?>">+ Add Plan</a>
</div>

<div class="acard">
  <?php if ($plans): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Order</th><th>Plan</th><th>Monthly</th><th>Quarterly</th><th>Yearly</th><th>Highlight</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($plans as $p): ?>
        <tr>
          <td class="muted"><?php echo (int)$p['sort_order']; ?></td>
          <td><strong><?php echo e($p['plan_name']); ?></strong></td>
          <td><?php echo e(money($p['monthly_price'])); ?></td>
          <td><?php echo e(money($p['quarterly_price'])); ?></td>
          <td><?php echo e(money($p['yearly_price'])); ?></td>
          <td><?php echo $p['is_highlighted'] ? '<span class="badge badge-blue">Featured</span>' : '<span class="muted">—</span>'; ?></td>
          <td><span class="badge <?php echo $p['is_active'] ? 'badge-green' : 'badge-gray'; ?>"><?php echo $p['is_active'] ? 'Active' : 'Hidden'; ?></span></td>
          <td>
            <div class="actions-cell">
              <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('admin/plan-edit.php?id=' . $p['id'])); ?>">Edit</a>
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $p['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $p['is_active'] ? 'Hide' : 'Show'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this plan?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $p['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No plans yet. <a href="<?php echo e(url('admin/plan-add.php')); ?>">Add your first plan</a>.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
