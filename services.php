<?php
require_once __DIR__ . '/config.php';
$page_key = 'services';
$heroImg  = media_url(setting('hero_image'));

$services = [
    ['🏋️', 'Strength Training', 'Build raw power and muscle with progressive resistance programs guided by expert coaches.'],
    ['🏃', 'Cardio Training', 'Boost stamina, heart health and burn fat with high-energy cardio sessions.'],
    ['🤝', 'Personal Training', 'One-on-one coaching with a custom plan tailored to your individual goals.'],
    ['⚖️', 'Weight Loss Program', 'Structured fat-loss programs combining training, nutrition and accountability.'],
    ['💪', 'Muscle Gain Program', 'Hypertrophy-focused training and nutrition to add quality lean mass.'],
    ['🥗', 'Diet & Nutrition Guidance', 'Personalised nutrition plans that fit your lifestyle and accelerate results.'],
    ['🧘', 'Yoga & Flexibility', 'Improve mobility, posture and recovery with guided yoga sessions.'],
    ['👥', 'Group Classes', 'Train with energy and motivation in our supportive group batches.'],
];

require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Our Services</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Services</div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head">
      <span class="section-tag">What We Offer</span>
      <h2>Train Smarter, Reach Further</h2>
      <p>From strength and cardio to nutrition and recovery, we cover every part of your fitness journey.</p>
    </div>
    <div class="grid grid-4">
      <?php foreach ($services as $s): ?>
      <div class="card service-card">
        <div class="ico"><?php echo $s[0]; ?></div>
        <h3><?php echo e($s[1]); ?></h3>
        <p><?php echo e($s[2]); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2><?php echo e(setting('cta_trial_heading', 'Start Your Fitness Journey Today')); ?></h2>
    <p><?php echo e(setting('cta_trial_text')); ?></p>
    <div class="cta-actions">
      <a href="<?php echo e(url('plans.php')); ?>" class="btn btn-outline btn-lg">View Plans</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
