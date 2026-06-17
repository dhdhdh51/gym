<?php
require_once __DIR__ . '/config.php';
$page_key = 'trainers';
$heroImg  = media_url(setting('hero_image'));
$trainers = fetch_all('SELECT * FROM trainers WHERE is_active=1 ORDER BY sort_order, id');
require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Our Trainers</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Trainers</div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Expert Coaches</span>
      <h2>Train With The Best</h2>
      <p>Our certified trainers bring experience, passion and a personalised approach to every session.</p>
    </div>
    <?php if ($trainers): ?>
    <div class="grid grid-4">
      <?php foreach ($trainers as $t): $photo = media_url($t['photo']); ?>
      <div class="card trainer-card">
        <div class="trainer-photo">
          <img src="<?php echo e($photo ?: 'https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&w=600&q=70'); ?>" alt="<?php echo e($t['name']); ?>" loading="lazy">
        </div>
        <div class="trainer-body">
          <h3><?php echo e($t['name']); ?></h3>
          <div class="trainer-spec"><?php echo e($t['specialty']); ?></div>
          <div class="trainer-exp"><?php echo e($t['experience']); ?> Experience</div>
          <p><?php echo e($t['bio']); ?></p>
          <?php if ($t['instagram_link']): ?><a class="trainer-ig" href="<?php echo e($t['instagram_link']); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
      <p class="text-center" style="color:var(--muted)">Our trainer profiles are coming soon.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Want A Trainer For Your Goals?</h2>
    <div class="cta-actions">
      <a href="<?php echo e(whatsapp_link('Hi, I would like to know about personal training.')); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
