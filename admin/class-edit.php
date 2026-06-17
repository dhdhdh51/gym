<?php
$admin_title = 'Edit Class';
require_once __DIR__ . '/includes/admin-header.php';

$id = (int)input('id');
$c  = $id ? fetch_one('SELECT * FROM classes WHERE id = :id', [':id' => $id]) : null;
if (!$c) {
    flash('Class not found.', 'error');
    header('Location: ' . url('admin/classes.php'));
    exit;
}

if (is_post()) {
    require_csrf();
    $img = handle_upload('image', $c['image']);
    if (input('image_url') !== '') {
        $img = input('image_url');
    }
    $stmt = db()->prepare(
        'UPDATE classes SET class_name=:cn, trainer_name=:tn, class_time=:ct, class_days=:cd,
         duration=:du, description=:de, image=:im, is_active=:a, sort_order=:so WHERE id=:id'
    );
    $stmt->execute([
        ':cn' => input('class_name'),
        ':tn' => input('trainer_name'),
        ':ct' => input('class_time'),
        ':cd' => input('class_days'),
        ':du' => input('duration'),
        ':de' => input('description'),
        ':im' => $img,
        ':a' => isset($_POST['is_active']) ? 1 : 0,
        ':so' => (int)input('sort_order'),
        ':id' => $id,
    ]);
    flash('Class updated successfully.');
    header('Location: ' . url('admin/classes.php'));
    exit;
}
$img = media_url($c['image']);
?>
<div class="page-title">Edit Class</div>
<div class="page-sub"><a href="<?php echo e(url('admin/classes.php')); ?>">&larr; Back to Classes</a></div>

<form method="post" enctype="multipart/form-data" class="acard" action="<?php echo e(url('admin/class-edit.php?id=' . $id)); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg"><label>Class Name *</label><input type="text" name="class_name" value="<?php echo e($c['class_name']); ?>" required></div>
    <div class="fg"><label>Trainer Name</label><input type="text" name="trainer_name" value="<?php echo e($c['trainer_name']); ?>"></div>
    <div class="fg"><label>Time</label><input type="text" name="class_time" value="<?php echo e($c['class_time']); ?>"></div>
    <div class="fg"><label>Days</label><input type="text" name="class_days" value="<?php echo e($c['class_days']); ?>"></div>
    <div class="fg"><label>Duration</label><input type="text" name="duration" value="<?php echo e($c['duration']); ?>"></div>
    <div class="fg"><label>Sort Order</label><input type="number" name="sort_order" value="<?php echo (int)$c['sort_order']; ?>"></div>
    <div class="fg full">
      <label>Image</label>
      <input type="file" name="image" accept="image/*">
      <input type="text" name="image_url" placeholder="...or paste image URL" value="<?php echo preg_match('#^https?://#', (string)$c['image']) ? e($c['image']) : ''; ?>" style="margin-top:6px">
      <?php if ($img): ?><div class="current-media"><img src="<?php echo e($img); ?>" alt=""></div><?php endif; ?>
    </div>
    <div class="fg full"><label>Description</label><textarea name="description"><?php echo e($c['description']); ?></textarea></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_active" id="a" <?php echo $c['is_active'] ? 'checked' : ''; ?>><label for="a" style="margin:0">Active</label></div></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Update Class</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
