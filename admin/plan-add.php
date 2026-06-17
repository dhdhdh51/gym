<?php
$admin_title = 'Add Plan';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $stmt = db()->prepare(
        'INSERT INTO membership_plans (plan_name, monthly_price, quarterly_price, yearly_price, features, is_highlighted, is_active, sort_order)
         VALUES (:n, :m, :q, :y, :f, :h, :a, :s)'
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
    ]);
    flash('Plan added successfully.');
    header('Location: ' . url('admin/plans.php'));
    exit;
}
?>
<div class="page-title">Add Membership Plan</div>
<div class="page-sub"><a href="<?php echo e(url('admin/plans.php')); ?>">&larr; Back to Plans</a></div>

<form method="post" class="acard" action="<?php echo e(url('admin/plan-add.php')); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg"><label>Plan Name *</label><input type="text" name="plan_name" required></div>
    <div class="fg"><label>Sort Order</label><input type="number" name="sort_order" value="0"></div>
    <div class="fg"><label>Monthly Price</label><input type="text" name="monthly_price" placeholder="999"></div>
    <div class="fg"><label>Quarterly Price</label><input type="text" name="quarterly_price" placeholder="2699"></div>
    <div class="fg"><label>Yearly Price</label><input type="text" name="yearly_price" placeholder="9999"></div>
    <div class="fg"></div>
    <div class="fg full"><label>Features (one per line)</label><textarea name="features" placeholder="Gym access&#10;Cardio section&#10;Locker facility"></textarea></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_highlighted" id="h"><label for="h" style="margin:0">Highlight this plan</label></div></div>
    <div class="fg"><div class="checkbox-row"><input type="checkbox" name="is_active" id="a" checked><label for="a" style="margin:0">Active (visible on site)</label></div></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Save Plan</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
