<?php
$admin_title = 'Add Class';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $img = handle_upload('image', '');
    if ($img === '' && input('image_url') !== '') {
        $img = input('image_url');
    }
    $stmt = db()->prepare(
        'INSERT INTO classes (class_name, trainer_name, class_time, class_days, duration, description, image, is_active, sort_order)
         VALUES (:cn, :tn, :ct, :cd, :du, :de, :im, :a, :so)'
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
    ]);
    flash('Class added successfully.');
    header('Location: ' . url('admin/classes.php'));
    exit;
}
?>
<div class="page-title">Add Class</div>
<div class="page-sub"><a href="<?php echo e(url('admin/classes.php')); ?>">&larr; Back to Classes</a></div>

<form method="post" enctype="multipart/form-data" class="acard" action="<?php echo e(url('admin/class-add.php')); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg"><label>Class Name *</label><input type="text" name="class_name" required></div>
    <div class="fg"><label>Trainer Name</label><input type="text" name="trainer_name"></div>
    <div class="fg"><label>Time</label><input type="text" name="class_time" placeholder="6:00 AM - 7:00 AM"></div>
    <div class="fg"><label>Days</label><input type="text" name="class_days" placeholder="Mon, Wed, Fri"></div>
    <div class="fg"><label>Duration</label><input type="text" name="duration" placeholder="60 min"></div>
    <div class="fg"><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
    <div class="fg full">
      <label>Image</label>
      <input type="file" name="image" accept="image/*">
      <input type="text" name="image_url" placeholder="...or paste image URL" style="margin-top:6px">
    </div>
    <div class="fg full"><label>Description</label><textarea name="description"></textarea></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_active" id="a" checked><label for="a" style="margin:0">Active</label></div></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Save Class</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
