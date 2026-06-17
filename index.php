<?php
require_once __DIR__ . '/config.php';
$page_key = 'home';

$plans        = fetch_all('SELECT * FROM membership_plans WHERE is_active=1 ORDER BY sort_order, id');
$trainers     = fetch_all('SELECT * FROM trainers WHERE is_active=1 ORDER BY sort_order, id LIMIT 4');
$classes      = fetch_all('SELECT * FROM classes WHERE is_active=1 ORDER BY sort_order, id');
$gallery      = fetch_all('SELECT * FROM gallery WHERE is_active=1 ORDER BY id DESC LIMIT 6');
$transforms   = fetch_all('SELECT * FROM transformations WHERE is_active=1 ORDER BY id DESC LIMIT 2');
$testimonials = fetch_all('SELECT * FROM testimonials WHERE is_active=1 ORDER BY id DESC LIMIT 4');

$heroImg = media_url(setting('hero_image'));
$wa      = whatsapp_link('Hi, I am interested in joining your gym. Please share details.');

$services = [
    ['🏋️', 'Strength Training', 'Build raw power and muscle with progressive resistance programs.'],
    ['🏃', 'Cardio Training', 'Boost stamina and burn fat with high-energy cardio sessions.'],
    ['🤝', 'Personal Training', 'One-on-one coaching tailored to your individual goals.'],
    ['⚖️', 'Weight Loss Program', 'Structured fat-loss plans with accountability and results.'],
    ['💪', 'Muscle Gain Program', 'Hypertrophy-focused training to add lean mass.'],
    ['🥗', 'Diet & Nutrition Guidance', 'Personalised nutrition plans that fit your lifestyle.'],
    ['🧘', 'Yoga & Flexibility', 'Improve mobility, posture and recovery with guided sessions.'],
    ['👥', 'Group Classes', 'Train with energy and motivation in our group batches.'],
];

$faqs = [
    ['Do you provide personal training?', 'Yes. We offer dedicated one-on-one personal training with certified coaches who build a custom plan around your goals.'],
    ['Is a free trial available?', 'Absolutely. You can book a free trial session to experience our gym, equipment and trainers before joining.'],
    ['Do you provide diet plans?', 'Yes, our Standard and Premium plans include diet guidance, and personalised nutrition plans are available with personal training.'],
    ['What are the gym timings?', setting('business_hours', 'Please contact us for our current timings.')],
    ['Can beginners join?', 'Of course. Our trainers specialise in helping beginners start safely with proper form and a structured plan.'],
    ['Are there separate batches for women?', 'Yes, we run dedicated batches and provide a comfortable, supportive environment for everyone.'],
    ['Do you have monthly membership?', 'Yes, we offer flexible monthly, quarterly and yearly membership options to suit your needs.'],
];

require __DIR__ . '/header.php';
?>

<!-- HERO -->
<section class="hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container hero-inner">
    <span class="section-tag"><?php echo e(setting('tagline', 'Train Hard. Live Strong.')); ?></span>
    <h1><?php echo e(setting('hero_heading', 'Transform Your Body, Transform Your Life')); ?></h1>
    <p><?php echo e(setting('hero_subheading')); ?></p>
    <div class="hero-actions">
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
      <a href="<?php echo e(url('plans.php')); ?>" class="btn btn-outline btn-lg">View Membership Plans</a>
      <a href="<?php echo e($wa); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
    </div>
  </div>
</section>

<!-- TRUST BADGES -->
<section class="trust-strip" style="padding:40px 0">
  <div class="container">
    <div class="trust-grid">
      <div class="trust-item"><div class="ico">🏅</div><span>Certified Trainers</span></div>
      <div class="trust-item"><div class="ico">🏋️</div><span>Modern Equipment</span></div>
      <div class="trust-item"><div class="ico">🤝</div><span>Personal Training</span></div>
      <div class="trust-item"><div class="ico">📅</div><span>Flexible Membership</span></div>
      <div class="trust-item"><div class="ico">🥗</div><span>Diet Guidance</span></div>
      <div class="trust-item"><div class="ico">🎟️</div><span>Free Trial Available</span></div>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section id="about">
  <div class="container about-wrap">
    <div class="about-img">
      <img src="https://images.unsplash.com/photo-1571388208497-71bedc66e932?auto=format&fit=crop&w=900&q=70" alt="<?php echo e(setting('brand_name')); ?> interior" loading="lazy">
    </div>
    <div class="about-text">
      <span class="section-tag">About Us</span>
      <h2><?php echo e(setting('about_title', 'Where Champions Are Built')); ?></h2>
      <p><?php echo nl2br(e(setting('about_content'))); ?></p>
      <div class="stats">
        <div class="stat"><b><?php echo e(setting('about_years', '12')); ?>+</b><span>Years</span></div>
        <div class="stat"><b><?php echo e(setting('about_members', '5000')); ?>+</b><span>Members</span></div>
        <div class="stat"><b><?php echo e(setting('about_trainers', '25')); ?>+</b><span>Trainers</span></div>
        <div class="stat"><b><?php echo e(setting('about_classes', '40')); ?>+</b><span>Classes</span></div>
      </div>
      <a href="<?php echo e(url('about.php')); ?>" class="btn btn-primary mt-3">Learn More</a>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="bg-alt" id="services">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">What We Offer</span>
      <h2>Our Services</h2>
      <p>Everything you need to reach your fitness goals under one roof.</p>
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

<!-- PLANS -->
<section id="plans">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Pricing</span>
      <h2>Membership Plans</h2>
      <p>Flexible plans designed to fit every goal and budget.</p>
    </div>
    <div class="grid grid-3">
      <?php foreach ($plans as $p): ?>
      <div class="card plan-card <?php echo $p['is_highlighted'] ? 'featured' : ''; ?>">
        <?php if ($p['is_highlighted']): ?><span class="plan-badge">Most Popular</span><?php endif; ?>
        <h3><?php echo e($p['plan_name']); ?></h3>
        <div class="plan-price"><?php echo e(money($p['monthly_price'])); ?><small>/month</small></div>
        <div class="plan-sub">
          <?php if ($p['quarterly_price']): ?>Quarterly: <?php echo e(money($p['quarterly_price'])); ?> &nbsp;|&nbsp; <?php endif; ?>
          <?php if ($p['yearly_price']): ?>Yearly: <?php echo e(money($p['yearly_price'])); ?><?php endif; ?>
        </div>
        <ul class="plan-features">
          <?php foreach (features_to_list($p['features']) as $f): ?>
            <li><?php echo e($f); ?></li>
          <?php endforeach; ?>
        </ul>
        <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn <?php echo $p['is_highlighted'] ? 'btn-primary' : 'btn-ghost'; ?> btn-block">Join Now</a>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4"><a href="<?php echo e(url('plans.php')); ?>" class="btn btn-outline">View All Plans</a></div>
  </div>
</section>

<!-- TRAINERS -->
<section class="bg-alt" id="trainers">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Expert Coaches</span>
      <h2>Meet Our Trainers</h2>
      <p>Certified, experienced and committed to your transformation.</p>
    </div>
    <div class="grid grid-4">
      <?php foreach ($trainers as $t):
        $photo = media_url($t['photo']); ?>
      <div class="card trainer-card">
        <div class="trainer-photo">
          <?php if ($photo): ?>
            <img src="<?php echo e($photo); ?>" alt="<?php echo e($t['name']); ?>" loading="lazy">
          <?php else: ?>
            <img src="https://images.unsplash.com/photo-1567013127542-490d757e51fc?auto=format&fit=crop&w=600&q=70" alt="<?php echo e($t['name']); ?>" loading="lazy">
          <?php endif; ?>
        </div>
        <div class="trainer-body">
          <h3><?php echo e($t['name']); ?></h3>
          <div class="trainer-spec"><?php echo e($t['specialty']); ?></div>
          <div class="trainer-exp"><?php echo e($t['experience']); ?> Experience</div>
          <p><?php echo e(excerpt((string)$t['bio'], 18)); ?></p>
          <?php if ($t['instagram_link']): ?><a class="trainer-ig" href="<?php echo e($t['instagram_link']); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CLASSES -->
<section id="classes">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Schedule</span>
      <h2>Our Classes</h2>
      <p>Join our energising group classes led by expert trainers.</p>
    </div>
    <div class="grid grid-3">
      <?php foreach ($classes as $c):
        $img = media_url($c['image']); ?>
      <div class="card class-card">
        <div class="class-img">
          <img src="<?php echo e($img ?: 'https://images.unsplash.com/photo-1517963879433-6ad2b056d712?auto=format&fit=crop&w=700&q=70'); ?>" alt="<?php echo e($c['class_name']); ?>" loading="lazy">
        </div>
        <div class="class-body">
          <h3><?php echo e($c['class_name']); ?></h3>
          <div class="class-meta">
            <?php if ($c['class_time']): ?><span>🕒 <?php echo e($c['class_time']); ?></span><?php endif; ?>
            <?php if ($c['class_days']): ?><span>📅 <?php echo e($c['class_days']); ?></span><?php endif; ?>
            <?php if ($c['duration']): ?><span>⏱️ <?php echo e($c['duration']); ?></span><?php endif; ?>
          </div>
          <p><?php echo e($c['description']); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- GALLERY -->
<?php if ($gallery): ?>
<section class="bg-alt" id="gallery">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Inside Our Gym</span>
      <h2>Gallery</h2>
    </div>
    <div class="gallery-grid">
      <?php foreach ($gallery as $g): $img = media_url($g['image']); ?>
      <div class="gallery-item" data-lightbox="<?php echo e($img); ?>">
        <img src="<?php echo e($img); ?>" alt="<?php echo e($g['title']); ?>" loading="lazy">
        <div class="g-overlay"><span><?php echo e($g['title']); ?></span></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4"><a href="<?php echo e(url('gallery.php')); ?>" class="btn btn-outline">View Full Gallery</a></div>
  </div>
</section>
<?php endif; ?>

<!-- TRANSFORMATIONS -->
<?php if ($transforms): ?>
<section id="transformations">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Real Results</span>
      <h2>Member Transformations</h2>
      <p>See the incredible journeys of our dedicated members.</p>
    </div>
    <div class="grid grid-2">
      <?php foreach ($transforms as $tr): ?>
      <div class="card transform-card">
        <div class="transform-images">
          <figure><img src="<?php echo e(media_url($tr['before_image'])); ?>" alt="Before" loading="lazy"><figcaption>Before</figcaption></figure>
          <figure><img src="<?php echo e(media_url($tr['after_image'])); ?>" alt="After" loading="lazy"><figcaption>After</figcaption></figure>
        </div>
        <div class="transform-body">
          <h3><?php echo e($tr['member_name']); ?></h3>
          <div class="transform-dur"><?php echo e($tr['duration']); ?></div>
          <p><?php echo e($tr['description']); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- FREE TRIAL CTA -->
<section class="cta-band">
  <div class="container">
    <h2><?php echo e(setting('cta_trial_heading', 'Start Your Fitness Journey Today')); ?></h2>
    <p><?php echo e(setting('cta_trial_text')); ?></p>
    <div class="cta-actions"><a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a></div>
  </div>
</section>

<!-- TESTIMONIALS -->
<?php if ($testimonials): ?>
<section id="testimonials">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Reviews</span>
      <h2>What Our Members Say</h2>
    </div>
    <div class="grid grid-4">
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
  </div>
</section>
<?php endif; ?>

<!-- FAQ -->
<section class="bg-alt" id="faq">
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Got Questions?</span>
      <h2>Frequently Asked Questions</h2>
    </div>
    <div class="faq-list">
      <?php foreach ($faqs as $f): ?>
      <div class="faq-item">
        <button class="faq-q" type="button"><?php echo e($f[0]); ?><span class="plus">+</span></button>
        <div class="faq-a"><div><?php echo nl2br(e($f[1])); ?></div></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="cta-band">
  <div class="container">
    <h2><?php echo e(setting('cta_final_heading', 'Ready to Join the Best Gym Near You?')); ?></h2>
    <div class="cta-actions">
      <a href="<?php echo e(tel_link()); ?>" class="btn btn-outline btn-lg">Call Now</a>
      <a href="<?php echo e($wa); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>

<div class="lightbox" id="lightbox"><span class="lb-close">&times;</span><img src="" alt="Gallery image"></div>

<?php require __DIR__ . '/footer.php'; ?>
