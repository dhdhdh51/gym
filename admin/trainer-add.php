<?php
$admin_title = 'Add Trainer';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $photo = handle_upload('photo', '');
    if ($photo === '' && input('photo_url') !== '') {
        $photo = input('photo_url');
    }
    $stmt = db()->prepare(
        'INSERT INTO trainers (name, photo, specialty, experience, bio, instagram_link, is_active, sort_order)
         VALUES (:n, :p, :sp, :ex, :bio, :ig, :a, :so)'
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
    ]);
    flash('Trainer added successfully.');
    header('Location: ' . url('admin/trainers.php'));
    exit;
}
?>
<div class="page-title">Add Trainer</div>
<div class="page-sub"><a href="<?php echo e(url('admin/trainers.php')); ?>">&larr; Back to Trainers</a></div>

<form method="post" enctype="multipart/form-data" class="acard" action="<?php echo e(url('admin/trainer-add.php')); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg"><label>Name *</label><input type="text" name="name" required></div>
    <div class="fg"><label>Specialty</label><input type="text" name="specialty" placeholder="Strength & Conditioning"></div>
    <div class="fg"><label>Experience</label><input type="text" name="experience" placeholder="10 Years"></div>
    <div class="fg"><label>Instagram Link</label><input type="text" name="instagram_link"></div>
    <div class="fg"><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
    <div class="fg">
      <label>Photo</label>
      <input type="file" name="photo" accept="image/*">
      <input type="text" name="photo_url" placeholder="...or paste image URL" style="margin-top:6px">
    </div>
    <div class="fg full"><label>Bio</label><textarea name="bio"></textarea></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_active" id="a" checked><label for="a" style="margin:0">Active</label></div></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Save Trainer</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
