<?php
$admin_title = 'SEO Manager';
require_once __DIR__ . '/includes/admin-header.php';

$seoFields = [
    'meta_title', 'meta_description', 'meta_keywords', 'slug', 'canonical_url', 'robots_meta',
    'og_title', 'og_description', 'og_image', 'twitter_title', 'twitter_description', 'twitter_image',
    'schema_json', 'focus_keyword', 'custom_header_scripts', 'custom_footer_scripts',
];

if (is_post()) {
    require_csrf();
    $pageKey = input('page_key');
    if ($pageKey !== '') {
        $set = [];
        $params = [':page_key' => $pageKey];
        foreach ($seoFields as $f) {
            $set[] = "$f = :$f";
            $params[":$f"] = input($f);
        }
        $sql = 'UPDATE seo_settings SET ' . implode(', ', $set) . ' WHERE page_key = :page_key';
        db()->prepare($sql)->execute($params);
        flash('SEO settings updated for: ' . $pageKey);
    }
    header('Location: ' . url('admin/seo-manager.php'));
    exit;
}

$pages = fetch_all('SELECT * FROM seo_settings ORDER BY id');
?>
<div class="page-title">SEO Manager</div>
<div class="page-sub">Configure search engine optimization for each page. Blog posts have their own SEO fields in the Blog manager.</div>

<?php if (!$pages): ?>
  <div class="alert alert-info">No SEO pages found. Please import database.sql which seeds default pages.</div>
<?php else: ?>
<div class="tabs">
  <?php foreach ($pages as $i => $p): ?>
    <button type="button" class="tab-btn <?php echo $i === 0 ? 'active' : ''; ?>" data-tab="seo-<?php echo e($p['page_key']); ?>"><?php echo e($p['page_name']); ?></button>
  <?php endforeach; ?>
</div>

<?php foreach ($pages as $i => $p): ?>
<div class="tab-panel <?php echo $i === 0 ? 'active' : ''; ?>" id="seo-<?php echo e($p['page_key']); ?>">
  <form method="post" action="<?php echo e(url('admin/seo-manager.php')); ?>" class="acard">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="page_key" value="<?php echo e($p['page_key']); ?>">
    <h3 class="section-divider" style="margin-top:0"><?php echo e($p['page_name']); ?> Page SEO</h3>
    <div class="form-grid">
      <div class="fg"><label>Meta Title</label><input type="text" name="meta_title" value="<?php echo e($p['meta_title']); ?>"></div>
      <div class="fg"><label>Focus Keyword</label><input type="text" name="focus_keyword" value="<?php echo e($p['focus_keyword']); ?>"></div>
      <div class="fg full"><label>Meta Description</label><textarea name="meta_description"><?php echo e($p['meta_description']); ?></textarea></div>
      <div class="fg full"><label>Meta Keywords</label><input type="text" name="meta_keywords" value="<?php echo e($p['meta_keywords']); ?>"></div>
      <div class="fg"><label>SEO Slug</label><input type="text" name="slug" value="<?php echo e($p['slug']); ?>"></div>
      <div class="fg"><label>Canonical URL</label><input type="text" name="canonical_url" value="<?php echo e($p['canonical_url']); ?>"></div>
      <div class="fg"><label>Robots Meta</label>
        <select name="robots_meta">
          <?php foreach (['index, follow','noindex, follow','index, nofollow','noindex, nofollow'] as $opt): ?>
            <option value="<?php echo e($opt); ?>" <?php echo $p['robots_meta'] === $opt ? 'selected' : ''; ?>><?php echo e($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="fg"><label>Open Graph Image URL</label><input type="text" name="og_image" value="<?php echo e($p['og_image']); ?>"></div>
      <div class="fg"><label>Open Graph Title</label><input type="text" name="og_title" value="<?php echo e($p['og_title']); ?>"></div>
      <div class="fg full"><label>Open Graph Description</label><textarea name="og_description"><?php echo e($p['og_description']); ?></textarea></div>
      <div class="fg"><label>Twitter Title</label><input type="text" name="twitter_title" value="<?php echo e($p['twitter_title']); ?>"></div>
      <div class="fg"><label>Twitter Image URL</label><input type="text" name="twitter_image" value="<?php echo e($p['twitter_image']); ?>"></div>
      <div class="fg full"><label>Twitter Description</label><textarea name="twitter_description"><?php echo e($p['twitter_description']); ?></textarea></div>
      <div class="fg full"><label>Schema JSON-LD</label><textarea name="schema_json" placeholder='{"@context":"https://schema.org",...}'><?php echo e($p['schema_json']); ?></textarea></div>
      <div class="fg full"><label>Custom Header Scripts</label><textarea name="custom_header_scripts" placeholder="<meta>, verification tags, etc."><?php echo e($p['custom_header_scripts']); ?></textarea></div>
      <div class="fg full"><label>Custom Footer Scripts</label><textarea name="custom_footer_scripts" placeholder="tracking scripts loaded before </body>"><?php echo e($p['custom_footer_scripts']); ?></textarea></div>
    </div>
    <button class="abtn abtn-primary" type="submit">💾 Save <?php echo e($p['page_name']); ?> SEO</button>
  </form>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
