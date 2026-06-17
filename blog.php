<?php
require_once __DIR__ . '/config.php';
$page_key = 'blog';
$heroImg  = media_url(setting('hero_image'));

$perPage = 9;
$pageNum = max(1, (int)($_GET['p'] ?? 1));
$offset  = ($pageNum - 1) * $perPage;

$total = count_rows('blog_posts', "status='published'");
$pages = (int)ceil($total / $perPage);

$stmt = db()->prepare("SELECT * FROM blog_posts WHERE status='published' ORDER BY COALESCE(published_at, created_at) DESC LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Fitness Blog</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Blog</div>
  </div>
</section>

<section>
  <div class="container">
    <?php if ($posts): ?>
    <div class="blog-grid">
      <?php foreach ($posts as $post):
        $img  = media_url($post['featured_image']);
        $link = url('post.php?slug=' . urlencode($post['slug']));
        $exc  = $post['excerpt'] ?: excerpt((string)$post['content'], 24);
      ?>
      <article class="card post-card">
        <a href="<?php echo e($link); ?>" class="post-img">
          <img src="<?php echo e($img ?: 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=700&q=70'); ?>" alt="<?php echo e($post['title']); ?>" loading="lazy">
        </a>
        <div class="post-body">
          <?php if ($post['category']): ?><span class="post-cat"><?php echo e($post['category']); ?></span><?php endif; ?>
          <h3><a href="<?php echo e($link); ?>"><?php echo e($post['title']); ?></a></h3>
          <p class="post-excerpt"><?php echo e($exc); ?></p>
          <div class="post-meta">
            <?php if ($post['author']): ?>By <?php echo e($post['author']); ?> &middot; <?php endif; ?>
            <?php echo e(format_date($post['published_at'] ?: $post['created_at'])); ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
    <div class="text-center mt-4" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a class="btn <?php echo $i === $pageNum ? 'btn-primary' : 'btn-ghost'; ?>" href="<?php echo e(url('blog.php?p=' . $i)); ?>"><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
      <p class="text-center" style="color:var(--muted)">No blog posts published yet. Check back soon!</p>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
