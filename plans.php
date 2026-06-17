<?php
require_once __DIR__ . '/config.php';
$page_key = 'plans';
$heroImg  = media_url(setting('hero_image'));
$plans    = fetch_all('SELECT * FROM membership_plans WHERE is_active=1 ORDER BY sort_order, id');
require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Membership Plans</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Plans</div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head">
      <span class="section-tag">Pricing</span>
      <h2>Choose Your Plan</h2>
      <p>Flexible memberships with monthly, quarterly and yearly options. No hidden fees.</p>
    </div>
    <?php if ($plans): ?>
    <div class="grid grid-3">
      <?php foreach ($plans as $p): ?>
      <div class="card plan-card <?php echo $p['is_highlighted'] ? 'featured' : ''; ?>">
        <?php if ($p['is_highlighted']): ?><span class="plan-badge">Most Popular</span><?php endif; ?>
        <h3><?php echo e($p['plan_name']); ?></h3>
        <div class="plan-price"><?php echo e(money($p['monthly_price'])); ?><small>/month</small></div>
        <div class="plan-sub">
          <?php if ($p['quarterly_price']): ?>Quarterly: <?php echo e(money($p['quarterly_price'])); ?><?php endif; ?>
          <?php if ($p['yearly_price']): ?><br>Yearly: <?php echo e(money($p['yearly_price'])); ?><?php endif; ?>
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
    <?php else: ?>
      <p class="text-center" style="color:var(--muted)">Membership plans will be published soon. Please contact us for details.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Not Sure Which Plan To Pick?</h2>
    <p>Talk to our team and we will help you choose the perfect membership for your goals.</p>
    <div class="cta-actions">
      <a href="<?php echo e(whatsapp_link('Hi, I need help choosing a membership plan.')); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('contact.php#trial')); ?>" class="btn btn-primary btn-lg">Book Free Trial</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
