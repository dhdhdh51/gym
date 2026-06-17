<?php
$admin_title = 'Add Blog Post';
require_once __DIR__ . '/includes/admin-header.php';

$error = '';
if (is_post()) {
    require_csrf();
    $title = input('title');
    if ($title === '') {
        $error = 'Title is required.';
    } else {
        $slug = input('slug') !== '' ? slugify(input('slug')) : slugify($title);
        // ensure unique slug
        $base = $slug; $n = 1;
        while (fetch_one('SELECT id FROM blog_posts WHERE slug = :s', [':s' => $slug])) {
            $slug = $base . '-' . (++$n);
        }
        $img = handle_upload('featured_image', '');
        if ($img === '' && input('featured_url') !== '') {
            $img = input('featured_url');
        }
        $status = input('status') === 'draft' ? 'draft' : 'published';
        $stmt = db()->prepare(
            'INSERT INTO blog_posts (title, slug, category, author, featured_image, excerpt, content,
             meta_title, meta_description, meta_keywords, canonical_url, schema_json, status, published_at)
             VALUES (:t,:s,:c,:a,:fi,:ex,:co,:mt,:md,:mk,:cu,:sj,:st,:pa)'
        );
        $stmt->execute([
            ':t' => $title, ':s' => $slug, ':c' => input('category'), ':a' => input('author'),
            ':fi' => $img, ':ex' => input('excerpt'), ':co' => $_POST['content'] ?? '',
            ':mt' => input('meta_title'), ':md' => input('meta_description'), ':mk' => input('meta_keywords'),
            ':cu' => input('canonical_url'), ':sj' => input('schema_json'),
            ':st' => $status, ':pa' => $status === 'published' ? date('Y-m-d H:i:s') : null,
        ]);
        flash('Blog post created.');
        header('Location: ' . url('admin/blog-manager.php'));
        exit;
    }
}
?>
<div class="page-title">Add Blog Post</div>
<div class="page-sub"><a href="<?php echo e(url('admin/blog-manager.php')); ?>">&larr; Back to Blog</a></div>
<?php if ($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="acard" action="<?php echo e(url('admin/blog-add.php')); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg full"><label>Title *</label><input type="text" name="title" required></div>
    <div class="fg"><label>Slug</label><input type="text" name="slug" placeholder="auto-generated if blank"></div>
    <div class="fg"><label>Category</label><input type="text" name="category"></div>
    <div class="fg"><label>Author</label><input type="text" name="author"></div>
    <div class="fg"><label>Status</label><select name="status"><option value="published">Published</option><option value="draft">Draft</option></select></div>
    <div class="fg full"><label>Featured Image</label><input type="file" name="featured_image" accept="image/*"><input type="text" name="featured_url" placeholder="...or image URL" style="margin-top:6px"></div>
    <div class="fg full"><label>Excerpt (short summary)</label><textarea name="excerpt"></textarea></div>
    <div class="fg full"><label>Content (HTML allowed)</label><textarea name="content" style="min-height:280px"></textarea></div>
  </div>

  <h3 class="section-divider">SEO</h3>
  <div class="form-grid">
    <div class="fg"><label>Meta Title</label><input type="text" name="meta_title"></div>
    <div class="fg"><label>Meta Keywords</label><input type="text" name="meta_keywords"></div>
    <div class="fg full"><label>Meta Description</label><textarea name="meta_description"></textarea></div>
    <div class="fg"><label>Canonical URL</label><input type="text" name="canonical_url"></div>
    <div class="fg full"><label>Schema JSON-LD</label><textarea name="schema_json" placeholder="Leave blank for auto-generated article schema"></textarea></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Publish Post</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
