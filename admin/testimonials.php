<?php
$admin_title = 'Testimonials';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $action = input('action');
    if ($action === 'add') {
        $photo = handle_upload('photo', '');
        if ($photo === '' && input('photo_url') !== '') {
            $photo = input('photo_url');
        }
        db()->prepare('INSERT INTO testimonials (client_name, photo, rating, review, city, is_active) VALUES (:n,:p,:r,:rv,:c,1)')
           ->execute([
               ':n' => input('client_name'), ':p' => $photo,
               ':r' => max(1, min(5, (int)input('rating', '5'))),
               ':rv' => input('review'), ':c' => input('city'),
           ]);
        flash('Testimonial added.');
    } elseif ($action === 'delete') {
        db()->prepare('DELETE FROM testimonials WHERE id = :id')->execute([':id' => (int)input('id')]);
        flash('Testimonial deleted.');
    } elseif ($action === 'toggle') {
        db()->prepare('UPDATE testimonials SET is_active = 1 - is_active WHERE id = :id')->execute([':id' => (int)input('id')]);
        flash('Status updated.');
    }
    header('Location: ' . url('admin/testimonials.php'));
    exit;
}

$items = fetch_all('SELECT * FROM testimonials ORDER BY id DESC');
?>
<div class="page-title">Testimonials</div>
<div class="page-sub">Manage member reviews shown on your website.</div>

<div class="acard mb-2">
  <h3 class="section-divider" style="margin-top:0">Add Testimonial</h3>
  <form method="post" enctype="multipart/form-data" action="<?php echo e(url('admin/testimonials.php')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="add">
    <div class="form-grid">
      <div class="fg"><label>Client Name *</label><input type="text" name="client_name" required></div>
      <div class="fg"><label>City</label><input type="text" name="city"></div>
      <div class="fg"><label>Rating</label>
        <select name="rating">
          <?php for ($i = 5; $i >= 1; $i--): ?><option value="<?php echo $i; ?>"><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option><?php endfor; ?>
        </select>
      </div>
      <div class="fg"><label>Photo (upload)</label><input type="file" name="photo" accept="image/*"></div>
      <div class="fg"><label>...or Photo URL</label><input type="text" name="photo_url"></div>
      <div class="fg full"><label>Review *</label><textarea name="review" required></textarea></div>
    </div>
    <button class="abtn abtn-primary" type="submit">Add Testimonial</button>
  </form>
</div>

<div class="acard">
  <h3 class="section-divider" style="margin-top:0">Reviews (<?php echo count($items); ?>)</h3>
  <?php if ($items): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Client</th><th>City</th><th>Rating</th><th>Review</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $t): ?>
        <tr>
          <td><strong><?php echo e($t['client_name']); ?></strong></td>
          <td><?php echo e($t['city']); ?></td>
          <td style="color:#f5b301"><?php echo str_repeat('★', (int)$t['rating']); ?></td>
          <td class="notes-box"><?php echo e(excerpt((string)$t['review'], 16)); ?></td>
          <td><span class="badge <?php echo $t['is_active'] ? 'badge-green' : 'badge-gray'; ?>"><?php echo $t['is_active'] ? 'Active' : 'Hidden'; ?></span></td>
          <td>
            <div class="actions-cell">
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $t['is_active'] ? 'Hide' : 'Show'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this testimonial?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $t['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No testimonials yet.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
