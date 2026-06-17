<?php
require_once __DIR__ . '/config.php';

$slug = input('slug');
$post = $slug ? fetch_one("SELECT * FROM blog_posts WHERE slug = :s AND status='published' LIMIT 1", [':s' => $slug]) : null;

if (!$post) {
    http_response_code(404);
    $page_key = 'blog';
    require __DIR__ . '/header.php';
    echo '<section class="page-hero"><div class="container"><h1>Post Not Found</h1>'
       . '<div class="breadcrumbs"><a href="' . e(url('blog.php')) . '">Back to Blog</a></div></div></section>';
    require __DIR__ . '/footer.php';
    exit;
}

$page_key = 'blog';
$canonical = $post['canonical_url'] ?: url('post.php?slug=' . urlencode($post['slug']));
$ogImage   = media_url($post['featured_image']);

// Build JSON-LD for the article if none provided
$schema = $post['schema_json'];
if (trim((string)$schema) === '') {
    $schema = json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'BlogPosting',
        'headline' => $post['title'],
        'image'    => $ogImage ?: null,
        'author'   => ['@type' => 'Person', 'name' => $post['author'] ?: setting('brand_name')],
        'publisher'=> ['@type' => 'Organization', 'name' => setting('brand_name')],
        'datePublished' => $post['published_at'] ?: $post['created_at'],
        'description'   => $post['meta_description'] ?: excerpt((string)$post['content'], 30),
        'mainEntityOfPage' => $canonical,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

$seo_custom = [
    'meta_title'       => $post['meta_title'] ?: $post['title'],
    'meta_description' => $post['meta_description'] ?: excerpt((string)$post['content'], 30),
    'meta_keywords'    => $post['meta_keywords'],
    'canonical_url'    => $canonical,
    'og_title'         => $post['meta_title'] ?: $post['title'],
    'og_image'         => $ogImage,
    'twitter_image'    => $ogImage,
    'schema_json'      => $schema,
];

// Related posts (same category, else latest)
$related = fetch_all(
    "SELECT * FROM blog_posts WHERE status='published' AND id <> :id AND (category = :cat OR :cat = '') ORDER BY COALESCE(published_at, created_at) DESC LIMIT 3",
    [':id' => $post['id'], ':cat' => (string)$post['category']]
);
if (count($related) < 3) {
    $related = fetch_all("SELECT * FROM blog_posts WHERE status='published' AND id <> :id ORDER BY COALESCE(published_at, created_at) DESC LIMIT 3", [':id' => $post['id']]);
}

require __DIR__ . '/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1 style="max-width:880px;margin:0 auto"><?php echo e($post['title']); ?></h1>
    <div class="breadcrumbs">
      <a href="<?php echo e(url('index.php')); ?>">Home</a> /
      <a href="<?php echo e(url('blog.php')); ?>">Blog</a> /
      <?php echo e($post['category'] ?: 'Article'); ?>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <article class="article">
      <div class="article-meta">
        <?php if ($post['category']): ?><span class="post-cat"><?php echo e($post['category']); ?></span><?php endif; ?>
        <?php if ($post['author']): ?><span>By <?php echo e($post['author']); ?></span><?php endif; ?>
        <span><?php echo e(format_date($post['published_at'] ?: $post['created_at'])); ?></span>
      </div>
      <?php if ($ogImage): ?>
      <div class="article-feature"><img src="<?php echo e($ogImage); ?>" alt="<?php echo e($post['title']); ?>"></div>
      <?php endif; ?>
      <div class="article-content"><?php echo $post['content']; ?></div>
    </article>
  </div>
</section>

<?php if ($related): ?>
<section class="bg-alt">
  <div class="container">
    <div class="section-head"><span class="section-tag">Keep Reading</span><h2>Related Posts</h2></div>
    <div class="blog-grid">
      <?php foreach ($related as $r): $rimg = media_url($r['featured_image']); $rlink = url('post.php?slug=' . urlencode($r['slug'])); ?>
      <article class="card post-card">
        <a href="<?php echo e($rlink); ?>" class="post-img">
          <img src="<?php echo e($rimg ?: 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=700&q=70'); ?>" alt="<?php echo e($r['title']); ?>" loading="lazy">
        </a>
        <div class="post-body">
          <?php if ($r['category']): ?><span class="post-cat"><?php echo e($r['category']); ?></span><?php endif; ?>
          <h3><a href="<?php echo e($rlink); ?>"><?php echo e($r['title']); ?></a></h3>
          <div class="post-meta"><?php echo e(format_date($r['published_at'] ?: $r['created_at'])); ?></div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="cta-band">
  <div class="container">
    <h2><?php echo e(setting('cta_trial_heading', 'Start Your Fitness Journey Today')); ?></h2>
    <div class="cta-actions"><a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a></div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
