<?php
require_once __DIR__ . '/config.php';
$page_key = 'gallery';
$heroImg  = media_url(setting('hero_image'));
$images   = fetch_all('SELECT * FROM gallery WHERE is_active=1 ORDER BY id DESC');
require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Gallery</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Gallery</div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Inside Our Gym</span>
      <h2>Take A Look Around</h2>
      <p>Explore our facility, equipment and the energy that drives our community.</p>
    </div>
    <?php if ($images): ?>
    <div class="gallery-grid">
      <?php foreach ($images as $g): $img = media_url($g['image']); ?>
      <div class="gallery-item" data-lightbox="<?php echo e($img); ?>">
        <img src="<?php echo e($img); ?>" alt="<?php echo e($g['title']); ?>" loading="lazy">
        <div class="g-overlay"><span><?php echo e($g['title']); ?><?php if ($g['category']): ?> &middot; <?php echo e($g['category']); ?><?php endif; ?></span></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="text-center" style="color:var(--muted)">Photos coming soon.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2><?php echo e(setting('cta_trial_heading', 'Start Your Fitness Journey Today')); ?></h2>
    <div class="cta-actions"><a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a></div>
  </div>
</section>

<div class="lightbox" id="lightbox"><span class="lb-close">&times;</span><img src="" alt="Gallery image"></div>
<?php require __DIR__ . '/footer.php'; ?>
