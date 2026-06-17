<?php
require_once __DIR__ . '/config.php';
$page_key = 'about';
$heroImg  = media_url(setting('hero_image'));
require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>About Us</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / About</div>
  </div>
</section>

<section>
  <div class="container about-wrap">
    <div class="about-img">
      <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=900&q=70" alt="<?php echo e(setting('brand_name')); ?>" loading="lazy">
    </div>
    <div class="about-text">
      <span class="section-tag">Our Story</span>
      <h2><?php echo e(setting('about_title', 'Where Champions Are Built')); ?></h2>
      <p><?php echo nl2br(e(setting('about_content'))); ?></p>
      <div class="stats">
        <div class="stat"><b><?php echo e(setting('about_years', '12')); ?>+</b><span>Years</span></div>
        <div class="stat"><b><?php echo e(setting('about_members', '5000')); ?>+</b><span>Members</span></div>
        <div class="stat"><b><?php echo e(setting('about_trainers', '25')); ?>+</b><span>Trainers</span></div>
        <div class="stat"><b><?php echo e(setting('about_classes', '40')); ?>+</b><span>Classes</span></div>
      </div>
    </div>
  </div>
</section>

<section class="bg-alt">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Why Choose Us</span>
      <h2>Built For Your Success</h2>
    </div>
    <div class="grid grid-3">
      <div class="card service-card"><div class="ico">🎯</div><h3>Goal-Driven Programs</h3><p>Every program is designed around measurable goals and real, lasting results.</p></div>
      <div class="card service-card"><div class="ico">🏅</div><h3>Certified Trainers</h3><p>Our coaches are certified professionals with years of hands-on experience.</p></div>
      <div class="card service-card"><div class="ico">🏋️</div><h3>Modern Equipment</h3><p>Train with premium, well-maintained equipment for every workout style.</p></div>
      <div class="card service-card"><div class="ico">🥗</div><h3>Nutrition Support</h3><p>Get personalised diet guidance to fuel your training and recovery.</p></div>
      <div class="card service-card"><div class="ico">👥</div><h3>Supportive Community</h3><p>Train alongside a motivated community that pushes you to be better.</p></div>
      <div class="card service-card"><div class="ico">📅</div><h3>Flexible Timings</h3><p>Open early to late so you can train whenever it suits your schedule.</p></div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2><?php echo e(setting('cta_final_heading', 'Ready to Join the Best Gym Near You?')); ?></h2>
    <div class="cta-actions">
      <a href="<?php echo e(tel_link()); ?>" class="btn btn-outline btn-lg">Call Now</a>
      <a href="<?php echo e(whatsapp_link()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
