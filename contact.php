<?php
require_once __DIR__ . '/config.php';
$page_key = 'contact';
$heroImg  = media_url(setting('hero_image'));

$errors = [];

if (is_post()) {
    if (!verify_csrf()) {
        $errors[] = 'Your session expired. Please try submitting the form again.';
    } else {
        $type = input('enquiry_type') === 'free_trial' ? 'free_trial' : 'contact';
        $name = input('name');
        $phone = input('phone');
        $email = input('email');

        if ($name === '') {
            $errors[] = 'Please enter your name.';
        }
        if ($phone === '' && $email === '') {
            $errors[] = 'Please provide a phone number or email so we can reach you.';
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!$errors) {
            $stmt = db()->prepare(
                'INSERT INTO enquiries (enquiry_type, name, phone, whatsapp, email, age, gender, fitness_goal, preferred_time, message, status)
                 VALUES (:type, :name, :phone, :whatsapp, :email, :age, :gender, :goal, :time, :msg, "new")'
            );
            $stmt->execute([
                ':type'     => $type,
                ':name'     => $name,
                ':phone'    => $phone,
                ':whatsapp' => input('whatsapp'),
                ':email'    => $email,
                ':age'      => input('age'),
                ':gender'   => input('gender'),
                ':goal'     => input('fitness_goal'),
                ':time'     => input('preferred_time'),
                ':msg'      => input('message'),
            ]);
            header('Location: ' . url('thank-you.php?type=' . $type));
            exit;
        }
    }
}

require __DIR__ . '/header.php';
?>
<section class="page-hero" <?php if ($heroImg): ?>style="--hero-img:url('<?php echo e($heroImg); ?>')"<?php endif; ?>>
  <div class="container">
    <h1>Contact Us</h1>
    <div class="breadcrumbs"><a href="<?php echo e(url('index.php')); ?>">Home</a> / Contact</div>
  </div>
</section>

<section>
  <div class="container">
    <?php if ($errors): ?>
      <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
    <?php endif; ?>

    <div class="contact-wrap">
      <div class="contact-info">
        <div class="contact-box">
          <h4>📍 Visit Us</h4>
          <p><?php echo nl2br(e(setting('address'))); ?></p>
        </div>
        <div class="contact-box">
          <h4>📞 Call / WhatsApp</h4>
          <p><a href="<?php echo e(tel_link()); ?>"><?php echo e(setting('phone_number')); ?></a></p>
          <p><a href="<?php echo e(whatsapp_link()); ?>" target="_blank" rel="noopener">WhatsApp: <?php echo e(setting('whatsapp_number')); ?></a></p>
        </div>
        <div class="contact-box">
          <h4>✉️ Email</h4>
          <p><a href="mailto:<?php echo e(setting('email')); ?>"><?php echo e(setting('email')); ?></a></p>
        </div>
        <?php if ($h = setting('business_hours')): ?>
        <div class="contact-box">
          <h4>🕒 Business Hours</h4>
          <p><?php echo nl2br(e($h)); ?></p>
        </div>
        <?php endif; ?>
      </div>

      <div>
        <!-- Contact form -->
        <div class="form-card">
          <h2 style="color:#fff;margin-bottom:6px;font-size:1.6rem">Send Us A Message</h2>
          <p style="color:var(--muted);margin-bottom:20px">We usually respond within a few hours.</p>
          <form method="post" action="<?php echo e(url('contact.php')); ?>" data-validate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="enquiry_type" value="contact">
            <div class="form-row">
              <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
              <div class="form-group"><label>Phone *</label><input type="text" name="phone" required></div>
            </div>
            <div class="form-group"><label>Email</label><input type="email" name="email"></div>
            <div class="form-group"><label>Message</label><textarea name="message" placeholder="How can we help you?"></textarea></div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Send Message</button>
          </form>
        </div>

        <!-- Free trial booking form -->
        <div class="form-card mt-3" id="trial">
          <h2 style="color:#fff;margin-bottom:6px;font-size:1.6rem">Book A Free Trial</h2>
          <p style="color:var(--muted);margin-bottom:20px">Experience our gym before you join. No commitment required.</p>
          <form method="post" action="<?php echo e(url('contact.php#trial')); ?>" data-validate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="enquiry_type" value="free_trial">
            <div class="form-row">
              <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
              <div class="form-group"><label>Phone *</label><input type="text" name="phone" required></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>WhatsApp</label><input type="text" name="whatsapp"></div>
              <div class="form-group"><label>Email</label><input type="email" name="email"></div>
            </div>
            <div class="form-row">
              <div class="form-group"><label>Age</label><input type="number" name="age" min="10" max="100"></div>
              <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                  <option value="">Select</option>
                  <option>Male</option>
                  <option>Female</option>
                  <option>Other</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Fitness Goal</label>
                <select name="fitness_goal">
                  <option value="">Select</option>
                  <option>Weight Loss</option>
                  <option>Muscle Gain</option>
                  <option>General Fitness</option>
                  <option>Strength</option>
                  <option>Personal Training</option>
                </select>
              </div>
              <div class="form-group">
                <label>Preferred Time</label>
                <select name="preferred_time">
                  <option value="">Select</option>
                  <option>Early Morning</option>
                  <option>Morning</option>
                  <option>Afternoon</option>
                  <option>Evening</option>
                  <option>Night</option>
                </select>
              </div>
            </div>
            <div class="form-group"><label>Message</label><textarea name="message" placeholder="Anything we should know?"></textarea></div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Book My Free Trial</button>
          </form>
        </div>
      </div>
    </div>

    <?php if ($map = setting('map_embed')): ?>
      <div class="map-embed mt-4"><?php echo $map; ?></div>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
