<?php
$admin_title = 'Gallery';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $action = input('action');
    if ($action === 'add') {
        $img = handle_upload('image', '');
        if ($img === '' && input('image_url') !== '') {
            $img = input('image_url');
        }
        if ($img !== '') {
            db()->prepare('INSERT INTO gallery (title, image, category, is_active) VALUES (:t,:i,:c,1)')
               ->execute([':t' => input('title'), ':i' => $img, ':c' => input('category')]);
            flash('Image added to gallery.');
        } else {
            flash('Please upload an image or provide an image URL.', 'error');
        }
    } elseif ($action === 'delete') {
        db()->prepare('DELETE FROM gallery WHERE id = :id')->execute([':id' => (int)input('id')]);
        flash('Image deleted.');
    } elseif ($action === 'toggle') {
        db()->prepare('UPDATE gallery SET is_active = 1 - is_active WHERE id = :id')->execute([':id' => (int)input('id')]);
        flash('Image status updated.');
    }
    header('Location: ' . url('admin/gallery.php'));
    exit;
}

$items = fetch_all('SELECT * FROM gallery ORDER BY id DESC');
?>
<div class="page-title">Gallery</div>
<div class="page-sub">Manage your gym photos. Upload a file or paste an image URL.</div>

<div class="acard mb-2">
  <h3 class="section-divider" style="margin-top:0">Add Image</h3>
  <form method="post" enctype="multipart/form-data" action="<?php echo e(url('admin/gallery.php')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="add">
    <div class="form-grid">
      <div class="fg"><label>Title</label><input type="text" name="title"></div>
      <div class="fg"><label>Category</label><input type="text" name="category" placeholder="Equipment / Classes / Training"></div>
      <div class="fg"><label>Upload Image</label><input type="file" name="image" accept="image/*"></div>
      <div class="fg"><label>...or Image URL</label><input type="text" name="image_url"></div>
    </div>
    <button class="abtn abtn-primary" type="submit">Add Image</button>
  </form>
</div>

<div class="acard">
  <h3 class="section-divider" style="margin-top:0">Images (<?php echo count($items); ?>)</h3>
  <?php if ($items): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($items as $g): ?>
        <tr>
          <td><img class="thumb" src="<?php echo e(media_url($g['image'])); ?>" alt=""></td>
          <td><?php echo e($g['title']); ?></td>
          <td><?php echo e($g['category']); ?></td>
          <td><span class="badge <?php echo $g['is_active'] ? 'badge-green' : 'badge-gray'; ?>"><?php echo $g['is_active'] ? 'Active' : 'Hidden'; ?></span></td>
          <td>
            <div class="actions-cell">
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $g['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $g['is_active'] ? 'Hide' : 'Show'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this image?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $g['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No images yet.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
