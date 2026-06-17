<?php
/**
 * footer.php  -  Shared public site footer + sticky CTA buttons.
 */
$brand   = setting('brand_name', 'Gym');
$wa      = whatsapp_link('Hi, I would like to know more about your gym memberships.');
$tel     = tel_link();
$hours   = setting('business_hours');
$year    = date('Y');
$pageKeyForFooter = $page_key ?? 'home';
?>
</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-col footer-about">
      <h3 class="footer-brand"><?php echo e($brand); ?></h3>
      <p><?php echo nl2br(e(setting('footer_text'))); ?></p>
      <div class="footer-socials">
        <?php if ($l = setting('facebook_link')): ?><a href="<?php echo e($l); ?>" target="_blank" rel="noopener" aria-label="Facebook">Facebook</a><?php endif; ?>
        <?php if ($l = setting('instagram_link')): ?><a href="<?php echo e($l); ?>" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a><?php endif; ?>
        <?php if ($l = setting('youtube_link')): ?><a href="<?php echo e($l); ?>" target="_blank" rel="noopener" aria-label="YouTube">YouTube</a><?php endif; ?>
      </div>
    </div>

    <div class="footer-col">
      <h4>Quick Links</h4>
      <ul class="footer-links">
        <li><a href="<?php echo e(url('about.php')); ?>">About Us</a></li>
        <li><a href="<?php echo e(url('services.php')); ?>">Services</a></li>
        <li><a href="<?php echo e(url('plans.php')); ?>">Membership Plans</a></li>
        <li><a href="<?php echo e(url('trainers.php')); ?>">Trainers</a></li>
        <li><a href="<?php echo e(url('classes.php')); ?>">Classes</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Explore</h4>
      <ul class="footer-links">
        <li><a href="<?php echo e(url('gallery.php')); ?>">Gallery</a></li>
        <li><a href="<?php echo e(url('testimonials.php')); ?>">Testimonials</a></li>
        <li><a href="<?php echo e(url('blog.php')); ?>">Blog</a></li>
        <li><a href="<?php echo e(url('contact.php')); ?>">Contact</a></li>
        <li><a href="<?php echo e(url('contact.php#trial')); ?>">Book Free Trial</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Get In Touch</h4>
      <ul class="footer-contact">
        <li><strong>Address:</strong><br><?php echo nl2br(e(setting('address'))); ?></li>
        <li><strong>Phone:</strong> <a href="<?php echo e($tel); ?>"><?php echo e(setting('phone_number')); ?></a></li>
        <li><strong>Email:</strong> <a href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a></li>
        <?php if ($hours): ?><li><strong>Hours:</strong><br><?php echo nl2br(e($hours)); ?></li><?php endif; ?>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <p>&copy; <?php echo $year; ?> <?php echo e($brand); ?>. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- Sticky lead-gen buttons -->
<div class="sticky-cta">
  <a href="<?php echo e($wa); ?>" target="_blank" rel="noopener" class="sticky-btn sticky-whatsapp" aria-label="WhatsApp Now">
    <svg viewBox="0 0 32 32" width="26" height="26" aria-hidden="true"><path fill="currentColor" d="M16 3C9.4 3 4 8.4 4 15c0 2.3.7 4.5 1.9 6.4L4 29l7.8-1.8C13.6 28 14.8 28.2 16 28.2c6.6 0 12-5.4 12-12S22.6 3 16 3zm0 22c-1.1 0-2.2-.2-3.2-.6l-.5-.2-4.6 1.1 1.2-4.5-.3-.5C7.7 19 7.2 17 7.2 15 7.2 10.1 11.1 6.2 16 6.2S24.8 10.1 24.8 15 20.9 25 16 25zm5.2-7.2c-.3-.1-1.7-.8-1.9-.9-.3-.1-.5-.1-.7.1-.2.3-.7.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4-.8-.7-1.4-1.6-1.6-1.9-.2-.3 0-.5.1-.6.1-.1.3-.3.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1 2.8 1.2 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.4.2-.7.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3z"/></svg>
    <span>WhatsApp</span>
  </a>
  <a href="<?php echo e($tel); ?>" class="sticky-btn sticky-call" aria-label="Call Now">
    <svg viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.5 21 3 13.5 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.2.2 2.4.6 3.6.1.4 0 .8-.3 1l-2.2 2.2z"/></svg>
    <span>Call</span>
  </a>
</div>

<?php $__jsPath = (basename(dirname($_SERVER['SCRIPT_NAME'] ?? '/')) === 'admin') ? '../assets/js/main.js' : 'assets/js/main.js'; ?>
<script src="<?php echo e($__jsPath); ?>" defer></script>
<?php echo seo_footer_scripts($pageKeyForFooter); ?>
</body>
</html>
