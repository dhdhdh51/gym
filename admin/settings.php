<?php
$admin_title = 'Site Settings';
require_once __DIR__ . '/includes/admin-header.php';

// Text settings keys saved directly from POST
$textKeys = [
    'brand_name', 'tagline', 'hero_heading', 'hero_subheading', 'about_title', 'about_content',
    'about_years', 'about_members', 'about_trainers', 'about_classes',
    'primary_color', 'secondary_color', 'button_color',
    'whatsapp_number', 'phone_number', 'email', 'address', 'map_embed', 'business_hours',
    'instagram_link', 'facebook_link', 'youtube_link', 'footer_text',
    'google_analytics', 'search_console', 'facebook_pixel',
    'default_meta_title', 'default_meta_description', 'default_meta_keywords',
    'cta_trial_heading', 'cta_trial_text', 'cta_final_heading',
];
$imageKeys = ['logo', 'favicon', 'hero_image'];

if (is_post()) {
    require_csrf();
    foreach ($textKeys as $k) {
        update_setting($k, input($k));
    }
    foreach ($imageKeys as $k) {
        // allow either upload or pasted URL
        $uploaded = handle_upload($k, setting($k));
        $urlField = input($k . '_url');
        if ($urlField !== '') {
            update_setting($k, $urlField);
        } else {
            update_setting($k, $uploaded);
        }
    }
    flash('Settings saved successfully.');
    header('Location: ' . url('admin/settings.php'));
    exit;
}

function s(string $k, string $d = ''): string { return setting($k, $d); }
?>
<form method="post" enctype="multipart/form-data" action="<?php echo e(url('admin/settings.php')); ?>">
  <?php echo csrf_field(); ?>
  <div class="toolbar">
    <div><div class="page-title" style="margin:0">Site Settings</div><div class="page-sub" style="margin:0">Rebrand your entire website from here.</div></div>
    <button class="abtn abtn-primary" type="submit">💾 Save Changes</button>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Branding</h3>
    <div class="form-grid">
      <div class="fg"><label>Gym / Brand Name</label><input type="text" name="brand_name" value="<?php echo e(s('brand_name')); ?>"></div>
      <div class="fg"><label>Tagline</label><input type="text" name="tagline" value="<?php echo e(s('tagline')); ?>"></div>
      <div class="fg">
        <label>Logo Image</label>
        <input type="file" name="logo" accept="image/*">
        <input type="text" name="logo_url" placeholder="...or paste image URL" value="<?php echo preg_match('#^https?://#', s('logo')) ? e(s('logo')) : ''; ?>" style="margin-top:6px">
        <?php if ($m = media_url(s('logo'))): ?><div class="current-media"><img src="<?php echo e($m); ?>" alt="logo"></div><?php endif; ?>
        <div class="hint">Leave blank to keep current. Upload a file or paste a URL.</div>
      </div>
      <div class="fg">
        <label>Favicon</label>
        <input type="file" name="favicon" accept="image/*">
        <input type="text" name="favicon_url" placeholder="...or paste image URL" value="<?php echo preg_match('#^https?://#', s('favicon')) ? e(s('favicon')) : ''; ?>" style="margin-top:6px">
        <?php if ($m = media_url(s('favicon'))): ?><div class="current-media"><img src="<?php echo e($m); ?>" alt="favicon"></div><?php endif; ?>
      </div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Theme Colors</h3>
    <div class="form-grid">
      <div class="fg"><label>Primary Color</label><div class="color-row"><input type="color" name="primary_color" value="<?php echo e(s('primary_color', '#e11d2a')); ?>"><input type="text" value="<?php echo e(s('primary_color', '#e11d2a')); ?>" oninput="this.previousElementSibling.value=this.value" onchange="this.previousElementSibling.value=this.value"></div></div>
      <div class="fg"><label>Secondary Color</label><div class="color-row"><input type="color" name="secondary_color" value="<?php echo e(s('secondary_color', '#0d0d0d')); ?>"><input type="text" value="<?php echo e(s('secondary_color', '#0d0d0d')); ?>" oninput="this.previousElementSibling.value=this.value" onchange="this.previousElementSibling.value=this.value"></div></div>
      <div class="fg"><label>Button Color</label><div class="color-row"><input type="color" name="button_color" value="<?php echo e(s('button_color', '#e11d2a')); ?>"><input type="text" value="<?php echo e(s('button_color', '#e11d2a')); ?>" oninput="this.previousElementSibling.value=this.value" onchange="this.previousElementSibling.value=this.value"></div></div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Hero Section</h3>
    <div class="form-grid">
      <div class="fg full"><label>Hero Heading</label><input type="text" name="hero_heading" value="<?php echo e(s('hero_heading')); ?>"></div>
      <div class="fg full"><label>Hero Subheading</label><textarea name="hero_subheading"><?php echo e(s('hero_subheading')); ?></textarea></div>
      <div class="fg full">
        <label>Hero Background Image</label>
        <input type="file" name="hero_image" accept="image/*">
        <input type="text" name="hero_image_url" placeholder="...or paste image URL" value="<?php echo preg_match('#^https?://#', s('hero_image')) ? e(s('hero_image')) : ''; ?>" style="margin-top:6px">
        <?php if ($m = media_url(s('hero_image'))): ?><div class="current-media"><img src="<?php echo e($m); ?>" alt="hero"></div><?php endif; ?>
      </div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">About & Stats</h3>
    <div class="form-grid">
      <div class="fg full"><label>About Title</label><input type="text" name="about_title" value="<?php echo e(s('about_title')); ?>"></div>
      <div class="fg full"><label>About Content</label><textarea name="about_content"><?php echo e(s('about_content')); ?></textarea></div>
      <div class="fg"><label>Years (stat)</label><input type="text" name="about_years" value="<?php echo e(s('about_years')); ?>"></div>
      <div class="fg"><label>Members (stat)</label><input type="text" name="about_members" value="<?php echo e(s('about_members')); ?>"></div>
      <div class="fg"><label>Trainers (stat)</label><input type="text" name="about_trainers" value="<?php echo e(s('about_trainers')); ?>"></div>
      <div class="fg"><label>Classes (stat)</label><input type="text" name="about_classes" value="<?php echo e(s('about_classes')); ?>"></div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Contact & Social</h3>
    <div class="form-grid">
      <div class="fg"><label>WhatsApp Number</label><input type="text" name="whatsapp_number" value="<?php echo e(s('whatsapp_number')); ?>"><div class="hint">Include country code, digits only e.g. 919999999999</div></div>
      <div class="fg"><label>Phone Number</label><input type="text" name="phone_number" value="<?php echo e(s('phone_number')); ?>"></div>
      <div class="fg"><label>Email</label><input type="email" name="email" value="<?php echo e(s('email')); ?>"></div>
      <div class="fg"><label>Business Hours</label><textarea name="business_hours"><?php echo e(s('business_hours')); ?></textarea></div>
      <div class="fg full"><label>Address</label><textarea name="address"><?php echo e(s('address')); ?></textarea></div>
      <div class="fg full"><label>Google Map Embed (iframe code)</label><textarea name="map_embed"><?php echo e(s('map_embed')); ?></textarea></div>
      <div class="fg"><label>Instagram Link</label><input type="text" name="instagram_link" value="<?php echo e(s('instagram_link')); ?>"></div>
      <div class="fg"><label>Facebook Link</label><input type="text" name="facebook_link" value="<?php echo e(s('facebook_link')); ?>"></div>
      <div class="fg"><label>YouTube Link</label><input type="text" name="youtube_link" value="<?php echo e(s('youtube_link')); ?>"></div>
      <div class="fg full"><label>Footer Text</label><textarea name="footer_text"><?php echo e(s('footer_text')); ?></textarea></div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">CTA Sections</h3>
    <div class="form-grid">
      <div class="fg"><label>Free Trial CTA Heading</label><input type="text" name="cta_trial_heading" value="<?php echo e(s('cta_trial_heading')); ?>"></div>
      <div class="fg"><label>Final CTA Heading</label><input type="text" name="cta_final_heading" value="<?php echo e(s('cta_final_heading')); ?>"></div>
      <div class="fg full"><label>Free Trial CTA Text</label><textarea name="cta_trial_text"><?php echo e(s('cta_trial_text')); ?></textarea></div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Analytics & Verification</h3>
    <div class="form-grid">
      <div class="fg"><label>Google Analytics ID</label><input type="text" name="google_analytics" value="<?php echo e(s('google_analytics')); ?>" placeholder="G-XXXXXXX"></div>
      <div class="fg"><label>Search Console Verification</label><input type="text" name="search_console" value="<?php echo e(s('search_console')); ?>" placeholder="verification code"></div>
      <div class="fg full"><label>Facebook Pixel ID</label><input type="text" name="facebook_pixel" value="<?php echo e(s('facebook_pixel')); ?>"></div>
    </div>
  </div>

  <div class="acard mb-2">
    <h3 class="section-divider" style="margin-top:0">Default SEO (fallbacks)</h3>
    <div class="form-grid">
      <div class="fg full"><label>Default Meta Title</label><input type="text" name="default_meta_title" value="<?php echo e(s('default_meta_title')); ?>"></div>
      <div class="fg full"><label>Default Meta Description</label><textarea name="default_meta_description"><?php echo e(s('default_meta_description')); ?></textarea></div>
      <div class="fg full"><label>Default Meta Keywords</label><input type="text" name="default_meta_keywords" value="<?php echo e(s('default_meta_keywords')); ?>"></div>
    </div>
  </div>

  <button class="abtn abtn-primary" type="submit">💾 Save Changes</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
