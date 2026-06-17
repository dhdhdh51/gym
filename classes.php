<?php
require_once __DIR__ . '/config.php';
$page_key = 'classes';
$heroImg  = media_url(setting('hero_image'));
$classes  = fetch_all('SELECT * FROM classes WHERE is_active=1 ORDER BY sort_order, id');
require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Our Classes</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Classes</div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Schedule</span>
      <h2>Group Class Timetable</h2>
      <p>High-energy classes for every level, led by our expert coaches.</p>
    </div>
    <?php if ($classes): ?>
    <div class="grid grid-3">
      <?php foreach ($classes as $c): $img = media_url($c['image']); ?>
      <div class="card class-card">
        <div class="class-img">
          <img src="<?php echo e($img ?: 'https://images.unsplash.com/photo-1517963879433-6ad2b056d712?auto=format&fit=crop&w=700&q=70'); ?>" alt="<?php echo e($c['class_name']); ?>" loading="lazy">
        </div>
        <div class="class-body">
          <h3><?php echo e($c['class_name']); ?></h3>
          <div class="class-meta">
            <?php if ($c['trainer_name']): ?><span>👤 <?php echo e($c['trainer_name']); ?></span><?php endif; ?>
            <?php if ($c['class_time']): ?><span>🕒 <?php echo e($c['class_time']); ?></span><?php endif; ?>
            <?php if ($c['class_days']): ?><span>📅 <?php echo e($c['class_days']); ?></span><?php endif; ?>
            <?php if ($c['duration']): ?><span>⏱️ <?php echo e($c['duration']); ?></span><?php endif; ?>
          </div>
          <p><?php echo e($c['description']); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="text-center" style="color:var(--muted)">Our class schedule is being updated. Please check back soon.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Join A Class This Week</h2>
    <div class="cta-actions">
      <a href="<?php echo e(whatsapp_link('Hi, I want to join a class.')); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
