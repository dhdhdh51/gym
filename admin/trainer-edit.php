<?php
$admin_title = 'Edit Trainer';
require_once __DIR__ . '/includes/admin-header.php';

$id = (int)input('id');
$t  = $id ? fetch_one('SELECT * FROM trainers WHERE id = :id', [':id' => $id]) : null;
if (!$t) {
    flash('Trainer not found.', 'error');
    header('Location: ' . url('admin/trainers.php'));
    exit;
}

if (is_post()) {
    require_csrf();
    $photo = handle_upload('photo', $t['photo']);
    if (input('photo_url') !== '') {
        $photo = input('photo_url');
    }
    $stmt = db()->prepare(
        'UPDATE trainers SET name=:n, photo=:p, specialty=:sp, experience=:ex, bio=:bio,
         instagram_link=:ig, is_active=:a, sort_order=:so WHERE id=:id'
    );
    $stmt->execute([
        ':n' => input('name'),
        ':p' => $photo,
        ':sp' => input('specialty'),
        ':ex' => input('experience'),
        ':bio' => input('bio'),
        ':ig' => input('instagram_link'),
        ':a' => isset($_POST['is_active']) ? 1 : 0,
        ':so' => (int)input('sort_order'),
        ':id' => $id,
    ]);
    flash('Trainer updated successfully.');
    header('Location: ' . url('admin/trainers.php'));
    exit;
}
$photo = media_url($t['photo']);
?>
<div class="page-title">Edit Trainer</div>
<div class="page-sub"><a href="<?php echo e(url('admin/trainers.php')); ?>">&larr; Back to Trainers</a></div>

<form method="post" enctype="multipart/form-data" class="acard" action="<?php echo e(url('admin/trainer-edit.php?id=' . $id)); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg"><label>Name *</label><input type="text" name="name" value="<?php echo e($t['name']); ?>" required></div>
    <div class="fg"><label>Specialty</label><input type="text" name="specialty" value="<?php echo e($t['specialty']); ?>"></div>
    <div class="fg"><label>Experience</label><input type="text" name="experience" value="<?php echo e($t['experience']); ?>"></div>
    <div class="fg"><label>Instagram Link</label><input type="text" name="instagram_link" value="<?php echo e($t['instagram_link']); ?>"></div>
    <div class="fg"><label>Sort Order</label><input type="number" name="sort_order" value="<?php echo (int)$t['sort_order']; ?>"></div>
    <div class="fg">
      <label>Photo</label>
      <input type="file" name="photo" accept="image/*">
      <input type="text" name="photo_url" placeholder="...or paste image URL" value="<?php echo preg_match('#^https?://#', (string)$t['photo']) ? e($t['photo']) : ''; ?>" style="margin-top:6px">
      <?php if ($photo): ?><div class="current-media"><img src="<?php echo e($photo); ?>" alt=""></div><?php endif; ?>
    </div>
    <div class="fg full"><label>Bio</label><textarea name="bio"><?php echo e($t['bio']); ?></textarea></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_active" id="a" <?php echo $t['is_active'] ? 'checked' : ''; ?>><label for="a" style="margin:0">Active</label></div></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Update Trainer</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
