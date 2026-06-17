<?php
$admin_title = 'Edit Plan';
require_once __DIR__ . '/includes/admin-header.php';

$id = (int)input('id');
$plan = $id ? fetch_one('SELECT * FROM membership_plans WHERE id = :id', [':id' => $id]) : null;
if (!$plan) {
    flash('Plan not found.', 'error');
    header('Location: ' . url('admin/plans.php'));
    exit;
}

if (is_post()) {
    require_csrf();
    $stmt = db()->prepare(
        'UPDATE membership_plans SET plan_name=:n, monthly_price=:m, quarterly_price=:q, yearly_price=:y,
         features=:f, is_highlighted=:h, is_active=:a, sort_order=:s WHERE id=:id'
    );
    $stmt->execute([
        ':n' => input('plan_name'),
        ':m' => input('monthly_price'),
        ':q' => input('quarterly_price'),
        ':y' => input('yearly_price'),
        ':f' => input('features'),
        ':h' => isset($_POST['is_highlighted']) ? 1 : 0,
        ':a' => isset($_POST['is_active']) ? 1 : 0,
        ':s' => (int)input('sort_order'),
        ':id' => $id,
    ]);
    flash('Plan updated successfully.');
    header('Location: ' . url('admin/plans.php'));
    exit;
}
?>
<div class="page-title">Edit Plan</div>
<div class="page-sub"><a href="<?php echo e(url('admin/plans.php')); ?>">&larr; Back to Plans</a></div>

<form method="post" class="acard" action="<?php echo e(url('admin/plan-edit.php?id=' . $id)); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg"><label>Plan Name *</label><input type="text" name="plan_name" value="<?php echo e($plan['plan_name']); ?>" required></div>
    <div class="fg"><label>Sort Order</label><input type="number" name="sort_order" value="<?php echo (int)$plan['sort_order']; ?>"></div>
    <div class="fg"><label>Monthly Price</label><input type="text" name="monthly_price" value="<?php echo e($plan['monthly_price']); ?>"></div>
    <div class="fg"><label>Quarterly Price</label><input type="text" name="quarterly_price" value="<?php echo e($plan['quarterly_price']); ?>"></div>
    <div class="fg"><label>Yearly Price</label><input type="text" name="yearly_price" value="<?php echo e($plan['yearly_price']); ?>"></div>
    <div class="fg"></div>
    <div class="fg full"><label>Features (one per line)</label><textarea name="features"><?php echo e($plan['features']); ?></textarea></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_highlighted" id="h" <?php echo $plan['is_highlighted'] ? 'checked' : ''; ?>><label for="h" style="margin:0">Highlight this plan</label></div></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_active" id="a" <?php echo $plan['is_active'] ? 'checked' : ''; ?>><label for="a" style="margin:0">Active (visible on site)</label></div></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Update Plan</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
