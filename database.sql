-- =====================================================================
-- Rebrandable Gym Website - Database Schema & Seed Data
-- MySQL 5.7+ / 8.0+  | Charset: utf8mb4
-- =====================================================================
-- Import this file via phpMyAdmin or:
--   mysql -u USER -p DATABASE_NAME < database.sql
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- ---------------------------------------------------------------------
-- 1. admin_users
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(60) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default login -> username: admin | password: admin123
-- The hash below is a bcrypt hash for "admin123".
INSERT INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$12$wj9ccPS1PpDmkjjGzAgBR.WMEunafKYJHXASQgVUOG5RrDYNoAMTW');

-- ---------------------------------------------------------------------
-- 2. site_settings (key/value)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(80) NOT NULL,
  `setting_value` LONGTEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('brand_name', 'IRON PULSE FITNESS'),
('logo', ''),
('favicon', ''),
('tagline', 'Train Hard. Live Strong.'),
('hero_heading', 'Transform Your Body, Transform Your Life'),
('hero_subheading', 'Join our premium fitness club and achieve your fitness goals with expert trainers, modern equipment and personalized workout plans.'),
('hero_image', ''),
('about_title', 'Where Champions Are Built'),
('about_content', 'We are more than just a gym. We are a community of dedicated individuals committed to becoming the strongest, healthiest versions of themselves. With state-of-the-art equipment, certified trainers and personalized programs, we help every member reach their full potential. Whether your goal is weight loss, muscle gain or simply living a healthier life, our expert team is here to guide you every step of the way.'),
('about_years', '12'),
('about_members', '5000'),
('about_trainers', '25'),
('about_classes', '40'),
('primary_color', '#e11d2a'),
('secondary_color', '#0d0d0d'),
('button_color', '#e11d2a'),
('whatsapp_number', '919999999999'),
('phone_number', '+91 99999 99999'),
('email', 'info@ironpulsefitness.com'),
('address', '123 Fitness Avenue, Sector 18, New Delhi, 110001, India'),
('map_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.0!2d77.0!3d28.6!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjjCsDM2JzAwLjAiTiA3N8KwMDAnMDAuMCJF!5e0!3m2!1sen!2sin!4v1600000000000" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>'),
('business_hours', 'Mon - Sat: 5:00 AM - 11:00 PM\nSunday: 6:00 AM - 2:00 PM'),
('instagram_link', 'https://instagram.com'),
('facebook_link', 'https://facebook.com'),
('youtube_link', 'https://youtube.com'),
('footer_text', 'IRON PULSE FITNESS is your partner in building a stronger, healthier life. Join us today and start your transformation journey.'),
('google_analytics', ''),
('search_console', ''),
('facebook_pixel', ''),
('default_meta_title', 'Premium Gym Website | Fitness Club, Personal Training & Membership Plans'),
('default_meta_description', 'Join our modern gym and fitness club for personal training, weight loss, muscle gain, cardio, strength training and expert fitness guidance.'),
('default_meta_keywords', 'gym near me, fitness club, personal trainer, weight loss gym, muscle gain, gym membership, fitness center, cardio training, strength training'),
('cta_trial_heading', 'Start Your Fitness Journey Today'),
('cta_trial_text', 'Book your free trial session and experience our gym before joining.'),
('cta_final_heading', 'Ready to Join the Best Gym Near You?');

-- ---------------------------------------------------------------------
-- 3. seo_settings (per page)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_key` VARCHAR(60) NOT NULL,
  `page_name` VARCHAR(120) NOT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  `slug` VARCHAR(160) DEFAULT NULL,
  `canonical_url` VARCHAR(255) DEFAULT NULL,
  `robots_meta` VARCHAR(80) DEFAULT 'index, follow',
  `og_title` VARCHAR(255) DEFAULT NULL,
  `og_description` TEXT,
  `og_image` VARCHAR(255) DEFAULT NULL,
  `twitter_title` VARCHAR(255) DEFAULT NULL,
  `twitter_description` TEXT,
  `twitter_image` VARCHAR(255) DEFAULT NULL,
  `schema_json` LONGTEXT,
  `focus_keyword` VARCHAR(160) DEFAULT NULL,
  `custom_header_scripts` LONGTEXT,
  `custom_footer_scripts` LONGTEXT,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_key` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `seo_settings` (`page_key`, `page_name`, `meta_title`, `meta_description`, `meta_keywords`, `slug`, `robots_meta`) VALUES
('home', 'Home', 'Premium Gym Website | Fitness Club, Personal Training & Membership Plans', 'Join our modern gym and fitness club for personal training, weight loss, muscle gain, cardio, strength training and expert fitness guidance.', 'gym near me, fitness club, personal trainer, weight loss gym, muscle gain, gym membership, fitness center, cardio training, strength training', 'home', 'index, follow'),
('about', 'About', 'About Us | Premium Fitness Club & Gym', 'Learn about our premium gym, certified trainers, modern equipment and our mission to transform lives through fitness.', 'about gym, fitness club, certified trainers', 'about', 'index, follow'),
('services', 'Services', 'Our Services | Strength, Cardio & Personal Training', 'Explore our fitness services including strength training, cardio, personal training, weight loss and diet guidance.', 'gym services, personal training, weight loss program, diet guidance', 'services', 'index, follow'),
('plans', 'Membership Plans', 'Membership Plans & Pricing | Affordable Gym Memberships', 'Choose from our flexible gym membership plans. Monthly, quarterly and yearly options with personal training support.', 'gym membership, gym pricing, membership plans, gym fees', 'plans', 'index, follow'),
('trainers', 'Trainers', 'Meet Our Certified Personal Trainers', 'Train with our certified, experienced personal trainers dedicated to helping you reach your fitness goals.', 'personal trainers, certified trainers, fitness coach', 'trainers', 'index, follow'),
('classes', 'Classes', 'Group Classes & Schedule | Gym Classes', 'View our group fitness class schedule including strength training, cardio, HIIT, yoga and weight loss batches.', 'gym classes, group fitness, hiit, yoga, class schedule', 'classes', 'index, follow'),
('gallery', 'Gallery', 'Gym Gallery | Photos of Our Fitness Center', 'Take a look inside our modern gym and fitness center through our photo gallery.', 'gym gallery, gym photos, fitness center photos', 'gallery', 'index, follow'),
('testimonials', 'Testimonials', 'Member Testimonials & Reviews', 'Read what our members say about their fitness transformation journey with us.', 'gym reviews, testimonials, member reviews', 'testimonials', 'index, follow'),
('blog', 'Blog', 'Fitness Blog | Workout Tips, Diet & Health Advice', 'Read our fitness blog for workout tips, diet plans, nutrition advice and health guidance from experts.', 'fitness blog, workout tips, diet plans, health advice', 'blog', 'index, follow'),
('contact', 'Contact', 'Contact Us | Visit Our Gym Today', 'Get in touch with our gym. Find our address, phone, WhatsApp, email and business hours.', 'contact gym, gym address, gym phone number', 'contact', 'index, follow');

-- ---------------------------------------------------------------------
-- 4. membership_plans
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `membership_plans` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `plan_name` VARCHAR(120) NOT NULL,
  `monthly_price` VARCHAR(40) DEFAULT NULL,
  `quarterly_price` VARCHAR(40) DEFAULT NULL,
  `yearly_price` VARCHAR(40) DEFAULT NULL,
  `features` TEXT,
  `is_highlighted` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `membership_plans` (`plan_name`, `monthly_price`, `quarterly_price`, `yearly_price`, `features`, `is_highlighted`, `is_active`, `sort_order`) VALUES
('Basic Plan', '999', '2699', '9999', 'Gym access\nCardio section\nBasic workout guidance\nLocker facility', 0, 1, 1),
('Standard Plan', '1499', '3999', '14999', 'Full gym access\nCardio + strength training\nGroup classes\nBasic diet guidance\nTrainer support', 1, 1, 2),
('Premium Plan', '2499', '6999', '24999', 'Full gym access\nPersonal trainer support\nCustom workout plan\nCustom diet plan\nBody progress tracking\nPriority support', 0, 1, 3);

-- ---------------------------------------------------------------------
-- 5. trainers
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `trainers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `specialty` VARCHAR(160) DEFAULT NULL,
  `experience` VARCHAR(80) DEFAULT NULL,
  `bio` TEXT,
  `instagram_link` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `trainers` (`name`, `photo`, `specialty`, `experience`, `bio`, `instagram_link`, `is_active`, `sort_order`) VALUES
('Rahul Verma', '', 'Strength & Conditioning', '10 Years', 'A national-level powerlifter turned coach, Rahul specializes in building raw strength and helping members break their plateaus with science-backed programming.', 'https://instagram.com', 1, 1),
('Priya Nair', '', 'Weight Loss & Nutrition', '8 Years', 'Priya combines smart training with sustainable nutrition coaching. She has guided hundreds of members through successful weight-loss transformations.', 'https://instagram.com', 1, 2),
('Arjun Mehta', '', 'Bodybuilding & Hypertrophy', '12 Years', 'A competitive bodybuilder, Arjun helps members sculpt muscle and refine physique through precise training splits and recovery protocols.', 'https://instagram.com', 1, 3),
('Sneha Kapoor', '', 'Yoga & Mobility', '7 Years', 'Sneha brings calm and control to the gym floor, leading yoga and mobility sessions that improve flexibility, posture and injury resilience.', 'https://instagram.com', 1, 4);

-- ---------------------------------------------------------------------
-- 6. classes
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `classes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_name` VARCHAR(120) NOT NULL,
  `trainer_name` VARCHAR(120) DEFAULT NULL,
  `class_time` VARCHAR(120) DEFAULT NULL,
  `class_days` VARCHAR(120) DEFAULT NULL,
  `duration` VARCHAR(80) DEFAULT NULL,
  `description` TEXT,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `classes` (`class_name`, `trainer_name`, `class_time`, `class_days`, `duration`, `description`, `is_active`, `sort_order`) VALUES
('Morning Strength Training', 'Rahul Verma', '6:00 AM - 7:00 AM', 'Mon, Wed, Fri', '60 min', 'Start your day strong with compound lifts and progressive overload designed to build full-body strength.', 1, 1),
('Evening Cardio', 'Priya Nair', '6:30 PM - 7:15 PM', 'Mon - Sat', '45 min', 'High-energy cardio sessions to torch calories, boost stamina and improve cardiovascular health.', 1, 2),
('Weight Loss Batch', 'Priya Nair', '7:00 AM - 8:00 AM', 'Mon - Sat', '60 min', 'A focused fat-loss program combining circuit training, conditioning and accountability coaching.', 1, 3),
('Personal Training Slot', 'Arjun Mehta', 'Flexible Booking', 'All Days', '60 min', 'One-on-one coaching tailored to your goals, with a custom plan and dedicated trainer attention.', 1, 4),
('Yoga & Mobility', 'Sneha Kapoor', '8:00 AM - 9:00 AM', 'Tue, Thu, Sat', '60 min', 'Improve flexibility, posture and recovery with guided yoga and mobility drills.', 1, 5),
('HIIT Workout', 'Arjun Mehta', '7:30 PM - 8:15 PM', 'Tue, Thu', '45 min', 'Short bursts of intense effort for maximum calorie burn and metabolic conditioning.', 1, 6);

-- ---------------------------------------------------------------------
-- 7. gallery
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(160) DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `category` VARCHAR(80) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery` (`title`, `image`, `category`, `is_active`) VALUES
('Free Weights Zone', 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=800&q=70', 'Equipment', 1),
('Cardio Floor', 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=800&q=70', 'Equipment', 1),
('Group Training', 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=800&q=70', 'Classes', 1),
('Strength Workout', 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=800&q=70', 'Training', 1),
('Functional Area', 'https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=800&q=70', 'Equipment', 1),
('Personal Training', 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=800&q=70', 'Training', 1);

-- ---------------------------------------------------------------------
-- 8. transformations
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `transformations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_name` VARCHAR(120) NOT NULL,
  `before_image` VARCHAR(255) DEFAULT NULL,
  `after_image` VARCHAR(255) DEFAULT NULL,
  `duration` VARCHAR(80) DEFAULT NULL,
  `description` TEXT,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transformations` (`member_name`, `before_image`, `after_image`, `duration`, `description`, `is_active`) VALUES
('Amit S.', 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=600&q=70', 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=600&q=70', '6 Months', 'Lost 18 kg and gained lean muscle through a structured weight-loss and strength program.', 1),
('Neha R.', 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?auto=format&fit=crop&w=600&q=70', 'https://images.unsplash.com/photo-1550345332-09e3ac987658?auto=format&fit=crop&w=600&q=70', '8 Months', 'Transformed her physique and strength with consistent training and personalised nutrition.', 1);

-- ---------------------------------------------------------------------
-- 9. testimonials
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` VARCHAR(120) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `rating` TINYINT NOT NULL DEFAULT 5,
  `review` TEXT,
  `city` VARCHAR(80) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` (`client_name`, `photo`, `rating`, `review`, `city`, `is_active`) VALUES
('Vikram Singh', '', 5, 'The trainers genuinely care about your progress. The equipment is top-notch and the atmosphere keeps me motivated every single day.', 'New Delhi', 1),
('Anjali Sharma', '', 5, 'I finally found a gym that feels like a community. The weight-loss batch changed my life and I feel more confident than ever.', 'Gurugram', 1),
('Rohan Gupta', '', 5, 'Best personal training I have experienced. My strength has doubled in a few months and the diet guidance is excellent.', 'Noida', 1),
('Meera Iyer', '', 5, 'Clean facility, friendly staff and amazing classes. The yoga and mobility sessions are the perfect balance to strength days.', 'New Delhi', 1);

-- ---------------------------------------------------------------------
-- 10. enquiries
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enquiry_type` VARCHAR(40) NOT NULL DEFAULT 'contact',
  `name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `whatsapp` VARCHAR(40) DEFAULT NULL,
  `email` VARCHAR(160) DEFAULT NULL,
  `age` VARCHAR(10) DEFAULT NULL,
  `gender` VARCHAR(20) DEFAULT NULL,
  `fitness_goal` VARCHAR(160) DEFAULT NULL,
  `preferred_time` VARCHAR(80) DEFAULT NULL,
  `message` TEXT,
  `status` VARCHAR(30) NOT NULL DEFAULT 'new',
  `notes` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`enquiry_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 11. blog_posts
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL,
  `category` VARCHAR(80) DEFAULT NULL,
  `author` VARCHAR(120) DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `excerpt` TEXT,
  `content` LONGTEXT,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT,
  `meta_keywords` TEXT,
  `canonical_url` VARCHAR(255) DEFAULT NULL,
  `schema_json` LONGTEXT,
  `status` VARCHAR(20) NOT NULL DEFAULT 'published',
  `published_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_slug` (`slug`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_posts` (`title`, `slug`, `category`, `author`, `featured_image`, `excerpt`, `content`, `meta_title`, `meta_description`, `meta_keywords`, `status`, `published_at`) VALUES
('5 Beginner Mistakes to Avoid at the Gym', '5-beginner-mistakes-to-avoid-at-the-gym', 'Training', 'Rahul Verma', 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1000&q=70', 'Starting your fitness journey is exciting, but small mistakes can slow your progress. Here are five common beginner errors and how to fix them.', '<p>Walking into a gym for the first time can feel overwhelming. The good news is that avoiding a few common mistakes will set you up for steady, lasting progress.</p><h2>1. Skipping the warm-up</h2><p>Jumping straight into heavy lifts increases injury risk. Spend 5-10 minutes on light cardio and mobility before training.</p><h2>2. Lifting too heavy too soon</h2><p>Form comes first. Master the movement pattern with lighter loads before adding weight.</p><h2>3. Ignoring nutrition</h2><p>You cannot out-train a poor diet. Prioritise protein, whole foods and adequate hydration.</p><h2>4. No consistency</h2><p>Three quality sessions every week beats one intense session followed by a week off.</p><h2>5. Not asking for help</h2><p>Our trainers are here to guide you. Ask questions and book an assessment to get a plan that fits your goals.</p>', '5 Beginner Gym Mistakes to Avoid | Fitness Tips', 'Avoid these 5 common beginner gym mistakes to train safely and see faster results. Expert fitness tips for new gym members.', 'gym mistakes, beginner fitness tips, gym for beginners', 'published', NOW()),
('How to Build Muscle: A Simple Nutrition Guide', 'how-to-build-muscle-simple-nutrition-guide', 'Nutrition', 'Arjun Mehta', 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=1000&q=70', 'Building muscle is as much about what you eat as how you train. This simple guide covers the nutrition fundamentals for muscle gain.', '<p>Muscle is built in the kitchen as much as the gym. Here is a straightforward approach to eating for growth.</p><h2>Eat enough protein</h2><p>Aim for roughly 1.6-2.2g of protein per kg of bodyweight, spread across the day.</p><h2>Maintain a slight calorie surplus</h2><p>To gain muscle you need a small surplus of energy. Track your intake and adjust based on progress.</p><h2>Do not fear carbs</h2><p>Carbohydrates fuel hard training and recovery. Build meals around quality sources like rice, oats and fruit.</p><h2>Prioritise recovery</h2><p>Sleep and rest days are when muscle actually grows. Aim for 7-9 hours of sleep.</p>', 'How to Build Muscle: Simple Nutrition Guide', 'Learn how to build muscle with this simple nutrition guide covering protein, calories, carbs and recovery for muscle gain.', 'build muscle, muscle gain nutrition, protein guide, bulking diet', 'published', NOW()),
('The Ultimate Guide to Fat Loss That Actually Works', 'ultimate-guide-to-fat-loss-that-works', 'Weight Loss', 'Priya Nair', 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=1000&q=70', 'Forget fad diets. Sustainable fat loss comes down to a few proven principles you can follow for life.', '<p>Lasting fat loss is not about extreme diets. It is about consistent habits that you can maintain.</p><h2>Create a moderate calorie deficit</h2><p>A deficit of 300-500 calories per day promotes steady fat loss without crashing your energy.</p><h2>Keep protein high</h2><p>Protein preserves muscle and keeps you full, making the deficit easier to sustain.</p><h2>Train with weights</h2><p>Resistance training maintains muscle so the weight you lose is mostly fat.</p><h2>Stay active daily</h2><p>Steps add up. Aim for a daily walking target alongside your structured workouts.</p>', 'Ultimate Fat Loss Guide That Works | Weight Loss Tips', 'A practical fat loss guide based on proven principles: calorie deficit, protein, strength training and daily activity for lasting weight loss.', 'fat loss, weight loss tips, how to lose fat, sustainable weight loss', 'published', NOW());

SET foreign_key_checks = 1;
-- =====================================================================
-- End of schema
-- =====================================================================
