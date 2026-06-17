<?php
require_once __DIR__ . '/config.php';
$page_key = 'contact';
$seo_custom = ['robots_meta' => 'noindex, follow', 'meta_title' => 'Thank You | ' . setting('brand_name')];

$type = input('type') === 'free_trial' ? 'free_trial' : 'contact';
$heading = $type === 'free_trial' ? 'Your Free Trial Is Booked!' : 'Thank You For Reaching Out!';
$message = $type === 'free_trial'
    ? 'We have received your free trial request. Our team will contact you shortly to confirm your session. Get ready to start your fitness journey!'
    : 'We have received your message and our team will get back to you as soon as possible.';

require __DIR__ . '/header.php';
?>
<section class="page-hero">
  <div class="container">
    <h1>Thank You</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Thank You</div>
  </div>
</section>

<section>
  <div class="container text-center" style="max-width:680px">
    <div style="font-size:4rem;margin-bottom:10px">✅</div>
    <h2 style="color:#fff;font-size:2.2rem;margin-bottom:14px"><?php echo e($heading); ?></h2>
    <div class="alert alert-success" style="text-align:left"><?php echo e($message); ?></div>
    <p style="color:var(--muted);margin:20px 0">In the meantime, feel free to reach out to us directly.</p>
    <div class="cta-actions">
      <a href="<?php echo e(tel_link()); ?>" class="btn btn-outline btn-lg">Call Now</a>
      <a href="<?php echo e(whatsapp_link()); ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg">WhatsApp Now</a>
      <a href="<?php echo e(url('index.php')); ?>" class="btn btn-primary btn-lg">Back To Home</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
