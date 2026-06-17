<?php
$admin_title = 'Transformations';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $action = input('action');
    if ($action === 'add') {
        $before = handle_upload('before_image', '');
        if ($before === '' && input('before_url') !== '') { $before = input('before_url'); }
        $after = handle_upload('after_image', '');
        if ($after === '' && input('after_url') !== '') { $after = input('after_url'); }
        db()->prepare('INSERT INTO transformations (member_name, before_image, after_image, duration, description, is_active) VALUES (:n,:b,:a,:d,:de,1)')
           ->execute([
               ':n' => input('member_name'), ':b' => $before, ':a' => $after,
               ':d' => input('duration'), ':de' => input('description'),
           ]);
        flash('Transformation added.');
    } elseif ($action === 'delete') {
        db()->prepare('DELETE FROM transformations WHERE id = :id')->execute([':id' => (int)input('id')]);
        flash('Transformation deleted.');
    } elseif ($action === 'toggle') {
        db()->prepare('UPDATE transformations SET is_active = 1 - is_active WHERE id = :id')->execute([':id' => (int)input('id')]);
        flash('Status updated.');
    }
    header('Location: ' . url('admin/transformations.php'));
    exit;
}

$items = fetch_all('SELECT * FROM transformations ORDER BY id DESC');
?>
<div class="page-title">Transformations</div>
<div class="page-sub">Showcase member before/after transformation stories.</div>

<div class="acard mb-2">
  <h3 class="section-divider" style="margin-top:0">Add Transformation</h3>
  <form method="post" enctype="multipart/form-data" action="<?php echo e(url('admin/transformations.php')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="add">
    <div class="form-grid">
      <div class="fg"><label>Member Name *</label><input type="text" name="member_name" required></div>
      <div class="fg"><label>Duration</label><input type="text" name="duration" placeholder="6 Months"></div>
      <div class="fg"><label>Before Image (upload)</label><input type="file" name="before_image" accept="image/*"><input type="text" name="before_url" placeholder="...or URL" style="margin-top:6px"></div>
      <div class="fg"><label>After Image (upload)</label><input type="file" name="after_image" accept="image/*"><input type="text" name="after_url" placeholder="...or URL" style="margin-top:6px"></div>
      <div class="fg full"><label>Result Description</label><textarea name="description"></textarea></div>
    </div>
    <button class="abtn abtn-primary" type="submit">Add Transformation</button>
  </form>
</div>

<div class="acard">
  <h3 class="section-divider" style="margin-top:0">Stories (<?php echo count($items); ?>)</h3>
  <?php if ($items): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Before</th><th>After</th><th>Member</th><th>Duration</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $t): ?>
        <tr>
          <td><?php if ($b = media_url($t['before_image'])): ?><img class="thumb" src="<?php echo e($b); ?>" alt=""><?php endif; ?></td>
          <td><?php if ($a = media_url($t['after_image'])): ?><img class="thumb" src="<?php echo e($a); ?>" alt=""><?php endif; ?></td>
          <td><strong><?php echo e($t['member_name']); ?></strong></td>
          <td><?php echo e($t['duration']); ?></td>
          <td><span class="badge <?php echo $t['is_active'] ? 'badge-green' : 'badge-gray'; ?>"><?php echo $t['is_active'] ? 'Active' : 'Hidden'; ?></span></td>
          <td>
            <div class="actions-cell">
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $t['is_active'] ? 'Hide' : 'Show'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this transformation?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No transformations yet.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
