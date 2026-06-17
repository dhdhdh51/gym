<?php
require_once __DIR__ . '/config.php';
$page_key     = 'testimonials';
$heroImg      = media_url(setting('hero_image'));
$testimonials = fetch_all('SELECT * FROM testimonials WHERE is_active=1 ORDER BY id DESC');
require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Testimonials</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Testimonials</div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Reviews</span>
      <h2>Loved By Our Members</h2>
      <p>Real stories from people who transformed their lives with us.</p>
    </div>
    <?php if ($testimonials): ?>
    <div class="grid grid-3">
      <?php foreach ($testimonials as $tm): $photo = media_url($tm['photo']); ?>
      <div class="card testi-card">
        <div class="testi-stars"><?php echo star_rating((int)$tm['rating']); ?></div>
        <p>"<?php echo e($tm['review']); ?>"</p>
        <div class="testi-author">
          <?php if ($photo): ?>
            <img src="<?php echo e($photo); ?>" alt="<?php echo e($tm['client_name']); ?>">
          <?php else: ?>
            <div class="testi-avatar"><?php echo e(strtoupper(substr($tm['client_name'], 0, 1))); ?></div>
          <?php endif; ?>
          <div><b><?php echo e($tm['client_name']); ?></b><span><?php echo e($tm['city']); ?></span></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="text-center" style="color:var(--muted)">Member reviews coming soon.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Become Our Next Success Story</h2>
    <div class="cta-actions">
      <a href="<?php echo e(whatsapp_link()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
