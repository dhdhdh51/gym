<?php
$admin_title = 'Contact Settings';
require_once __DIR__ . '/includes/admin-header.php';

$keys = ['phone_number', 'whatsapp_number', 'email', 'address', 'business_hours', 'map_embed',
         'instagram_link', 'facebook_link', 'youtube_link'];

if (is_post()) {
    require_csrf();
    foreach ($keys as $k) {
        update_setting($k, input($k));
    }
    flash('Contact settings saved.');
    header('Location: ' . url('admin/contact-settings.php'));
    exit;
}
function cs(string $k): string { return setting($k); }
?>
<form method="post" action="<?php echo e(url('admin/contact-settings.php')); ?>">
  <?php echo csrf_field(); ?>
  <div class="toolbar">
    <div><div class="page-title" style="margin:0">Contact Settings</div><div class="page-sub" style="margin:0">Manage how customers reach you.</div></div>
    <button class="abtn abtn-primary" type="submit">💾 Save</button>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Contact Details</h3>
    <div class="form-grid">
      <div class="fg"><label>Phone Number</label><input type="text" name="phone_number" value="<?php echo e(cs('phone_number')); ?>"></div>
      <div class="fg"><label>WhatsApp Number</label><input type="text" name="whatsapp_number" value="<?php echo e(cs('whatsapp_number')); ?>"><div class="hint">Digits only with country code, e.g. 919999999999</div></div>
      <div class="fg"><label>Email</label><input type="email" name="email" value="<?php echo e(cs('email')); ?>"></div>
      <div class="fg"><label>Business Hours</label><textarea name="business_hours"><?php echo e(cs('business_hours')); ?></textarea></div>
      <div class="fg full"><label>Address</label><textarea name="address"><?php echo e(cs('address')); ?></textarea></div>
      <div class="fg full"><label>Google Map Embed (iframe)</label><textarea name="map_embed"><?php echo e(cs('map_embed')); ?></textarea></div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Social Links</h3>
    <div class="form-grid">
      <div class="fg"><label>Instagram</label><input type="text" name="instagram_link" value="<?php echo e(cs('instagram_link')); ?>"></div>
      <div class="fg"><label>Facebook</label><input type="text" name="facebook_link" value="<?php echo e(cs('facebook_link')); ?>"></div>
      <div class="fg"><label>YouTube</label><input type="text" name="youtube_link" value="<?php echo e(cs('youtube_link')); ?>"></div>
    </div>
  </div>
  <button class="abtn abtn-primary" type="submit">💾 Save</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
