<?php
$admin_title = 'Blog Manager';
require_once __DIR__ . '/includes/admin-header.php';

if (is_post()) {
    require_csrf();
    $id = (int)input('id');
    if (input('action') === 'delete' && $id) {
        db()->prepare('DELETE FROM blog_posts WHERE id = :id')->execute([':id' => $id]);
        flash('Post deleted.');
    } elseif (input('action') === 'toggle' && $id) {
        $cur = fetch_one('SELECT status FROM blog_posts WHERE id=:id', [':id' => $id]);
        $new = ($cur && $cur['status'] === 'published') ? 'draft' : 'published';
        db()->prepare('UPDATE blog_posts SET status=:s, published_at = COALESCE(published_at, NOW()) WHERE id=:id')
           ->execute([':s' => $new, ':id' => $id]);
        flash('Post status updated.');
    }
    header('Location: ' . url('admin/blog-manager.php'));
    exit;
}

$posts = fetch_all('SELECT * FROM blog_posts ORDER BY created_at DESC');
?>
<div class="toolbar">
  <div><div class="page-title" style="margin:0">Blog Manager</div><div class="page-sub" style="margin:0"><?php echo count($posts); ?> post(s)</div></div>
  <a class="abtn abtn-primary" href="<?php echo e(url('admin/blog-add.php')); ?>">+ Add Post</a>
</div>

<div class="acard">
  <?php if ($posts): ?>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($posts as $p): $img = media_url($p['featured_image']); ?>
        <tr>
          <td><?php if ($img): ?><img class="thumb" src="<?php echo e($img); ?>" alt=""><?php else: ?><span class="muted">—</span><?php endif; ?></td>
          <td><strong><?php echo e($p['title']); ?></strong><br><span class="muted" style="font-size:.78rem">/<?php echo e($p['slug']); ?></span></td>
          <td><?php echo e($p['category']); ?></td>
          <td><?php echo e($p['author']); ?></td>
          <td><span class="badge <?php echo $p['status'] === 'published' ? 'badge-green' : 'badge-amber'; ?>"><?php echo e(ucfirst($p['status'])); ?></span></td>
          <td class="muted"><?php echo e(format_date($p['published_at'] ?: $p['created_at'])); ?></td>
          <td>
            <div class="actions-cell">
              <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('post.php?slug=' . urlencode($p['slug']))); ?>" target="_blank">View</a>
              <a class="abtn abtn-ghost abtn-sm" href="<?php echo e(url('admin/blog-edit.php?id=' . $p['id'])); ?>">Edit</a>
              <form method="post" style="display:inline"><?php echo csrf_field(); ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?php echo $p['id']; ?>"><button class="abtn abtn-ghost abtn-sm" type="submit"><?php echo $p['status'] === 'published' ? 'Unpublish' : 'Publish'; ?></button></form>
              <form method="post" style="display:inline" data-confirm="Delete this post?"><?php echo csrf_field(); ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo $p['id']; ?>"><button class="abtn abtn-danger abtn-sm" type="submit">Delete</button></form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="muted">No blog posts yet. <a href="<?php echo e(url('admin/blog-add.php')); ?>">Write your first post</a>.</p>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/includes/admin-footer.php'; ?>
