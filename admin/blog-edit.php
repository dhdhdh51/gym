<?php
$admin_title = 'Edit Blog Post';
require_once __DIR__ . '/includes/admin-header.php';

$id = (int)input('id');
$post = $id ? fetch_one('SELECT * FROM blog_posts WHERE id = :id', [':id' => $id]) : null;
if (!$post) {
    flash('Post not found.', 'error');
    header('Location: ' . url('admin/blog-manager.php'));
    exit;
}

$error = '';
if (is_post()) {
    require_csrf();
    $title = input('title');
    if ($title === '') {
        $error = 'Title is required.';
    } else {
        $slug = input('slug') !== '' ? slugify(input('slug')) : slugify($title);
        $base = $slug; $n = 1;
        while ($row = fetch_one('SELECT id FROM blog_posts WHERE slug = :s AND id <> :id', [':s' => $slug, ':id' => $id])) {
            $slug = $base . '-' . (++$n);
        }
        $img = handle_upload('featured_image', $post['featured_image']);
        if (input('featured_url') !== '') {
            $img = input('featured_url');
        }
        $status = input('status') === 'draft' ? 'draft' : 'published';
        $publishedAt = $post['published_at'];
        if ($status === 'published' && !$publishedAt) {
            $publishedAt = date('Y-m-d H:i:s');
        }
        $stmt = db()->prepare(
            'UPDATE blog_posts SET title=:t, slug=:s, category=:c, author=:a, featured_image=:fi, excerpt=:ex,
             content=:co, meta_title=:mt, meta_description=:md, meta_keywords=:mk, canonical_url=:cu,
             schema_json=:sj, status=:st, published_at=:pa WHERE id=:id'
        );
        $stmt->execute([
            ':t' => $title, ':s' => $slug, ':c' => input('category'), ':a' => input('author'),
            ':fi' => $img, ':ex' => input('excerpt'), ':co' => $_POST['content'] ?? '',
            ':mt' => input('meta_title'), ':md' => input('meta_description'), ':mk' => input('meta_keywords'),
            ':cu' => input('canonical_url'), ':sj' => input('schema_json'),
            ':st' => $status, ':pa' => $publishedAt, ':id' => $id,
        ]);
        flash('Blog post updated.');
        header('Location: ' . url('admin/blog-manager.php'));
        exit;
    }
    $post = array_merge($post, $_POST);
}
$img = media_url($post['featured_image']);
?>
<div class="page-title">Edit Blog Post</div>
<div class="page-sub"><a href="<?php echo e(url('admin/blog-manager.php')); ?>">&larr; Back to Blog</a></div>
<?php if ($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="acard" action="<?php echo e(url('admin/blog-edit.php?id=' . $id)); ?>">
  <?php echo csrf_field(); ?>
  <div class="form-grid">
    <div class="fg full"><label>Title *</label><input type="text" name="title" value="<?php echo e($post['title']); ?>" required></div>
    <div class="fg"><label>Slug</label><input type="text" name="slug" value="<?php echo e($post['slug']); ?>"></div>
    <div class="fg"><label>Category</label><input type="text" name="category" value="<?php echo e($post['category']); ?>"></div>
    <div class="fg"><label>Author</label><input type="text" name="author" value="<?php echo e($post['author']); ?>"></div>
    <div class="fg"><label>Status</label><select name="status"><option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Published</option><option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option></select></div>
    <div class="fg full">
      <label>Featured Image</label>
      <input type="file" name="featured_image" accept="image/*">
      <input type="text" name="featured_url" placeholder="...or image URL" value="<?php echo preg_match('#^https?://#', (string)$post['featured_image']) ? e($post['featured_image']) : ''; ?>" style="margin-top:6px">
      <?php if ($img): ?><div class="current-media"><img src="<?php echo e($img); ?>" alt=""></div><?php endif; ?>
    </div>
    <div class="fg full"><label>Excerpt</label><textarea name="excerpt"><?php echo e($post['excerpt']); ?></textarea></div>
    <div class="fg full"><label>Content (HTML allowed)</label><textarea name="content" style="min-height:280px"><?php echo e($post['content']); ?></textarea></div>
  </div>

  <h3 class="section-divider">SEO</h3>
  <div class="form-grid">
    <div class="fg"><label>Meta Title</label><input type="text" name="meta_title" value="<?php echo e($post['meta_title']); ?>"></div>
    <div class="fg"><label>Meta Keywords</label><input type="text" name="meta_keywords" value="<?php echo e($post['meta_keywords']); ?>"></div>
    <div class="fg full"><label>Meta Description</label><textarea name="meta_description"><?php echo e($post['meta_description']); ?></textarea></div>
    <div class="fg"><label>Canonical URL</label><input type="text" name="canonical_url" value="<?php echo e($post['canonical_url']); ?>"></div>
    <div class="fg full"><label>Schema JSON-LD</label><textarea name="schema_json"><?php echo e($post['schema_json']); ?></textarea></div>
  </div>
  <button class="abtn abtn-primary" type="submit">Update Post</button>
</form>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
