# Rebrandable Gym & Fitness Website

A complete, premium, fully rebrandable gym / fitness club website with a powerful admin panel.
Built with **PHP 8+, MySQL, HTML5, CSS3 and vanilla JavaScript** — no frameworks, no Node.js.
Ready to upload to shared hosting or a VPS.

Suitable for any gym, fitness club, yoga studio, personal trainer, health club, CrossFit centre
or fitness academy — everything is editable from the admin panel.

---

## ✨ Features

- **Fully rebrandable** from the admin panel: name, logo, favicon, tagline, colors, hero, contact details, social links, business hours, footer, and SEO.
- **Lead generation:** sticky WhatsApp button, sticky call button, contact form, membership enquiry and free-trial booking forms (all saved to the database).
- **Public pages:** Home, About, Services, Membership Plans, Trainers, Classes, Gallery, Testimonials, Contact, Blog, Single Blog Post, Thank You.
- **Admin panel:** Dashboard, Site Settings, Contact Settings, SEO Manager, Membership Plans, Trainers, Classes, Gallery, Testimonials, Transformations, Blog manager, Enquiries (with CSV export).
- **SEO ready:** per-page meta, canonical, robots, Open Graph, Twitter cards, JSON-LD schema, dynamic `sitemap.xml`, dynamic `robots.txt`, Google Analytics, Search Console verification and Facebook Pixel fields.
- **Security:** PDO prepared statements, `password_hash`/`password_verify`, session-protected admin, output escaping, input validation, image-only uploads (jpg/jpeg/png/webp/gif), CSRF protection on all forms.
- **Premium dark UI** with bold typography, fully responsive mobile-first design.

---

## 🚀 Installation

### Requirements
- PHP 8.0 or higher (with `pdo_mysql` and `fileinfo` extensions)
- MySQL 5.7+ / MariaDB 10.3+
- Apache (recommended, `.htaccess` included) or Nginx

### Option A — Automatic installer (recommended)

1. Upload the entire project to your web root (e.g. `public_html/`).
2. Create a MySQL database (or have your credentials ready — the installer can create it for you on most setups).
3. In your browser, open **`https://yourdomain.com/install.php`**.
4. Enter your database credentials and choose an admin username/password.
5. Click **Run Installation**. The installer will:
   - import `database.sql` (schema + sample content),
   - create your admin account,
   - write `config.local.php` with your DB credentials,
   - lock itself (`install.lock`).
6. **Delete `install.php`** afterwards for security, then visit your site and log in at `/admin/login.php`.

### Option B — Manual installation

1. Upload the project to your web root.
2. Create a MySQL database (e.g. `gym_website`) and import **`database.sql`**:
   ```bash
   mysql -u YOUR_USER -p gym_website < database.sql
   ```
3. Edit **`config.php`** (or create `config.local.php`) and set your credentials:
   ```php
   define('DB_HOST', '127.0.0.1');
   define('DB_NAME', 'gym_website');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   ```
4. Make the `uploads/` directory writable:
   ```bash
   chmod -R 755 uploads
   ```
5. Visit the public site at `/` and the admin panel at `/admin/login.php`.

> With manual installation, the default admin login is **admin / admin123** (see below).
> The automatic installer lets you set your own admin credentials during setup.

---

## 🔐 Default Admin Login

| Field    | Value      |
|----------|------------|
| Username | `admin`    |
| Password | `admin123` |

> **Important:** Change the password after your first login by updating the
> `admin_users` table (store a new hash generated with `password_hash()`),
> or by adding a user-management feature.

---

## 🎨 Rebranding (no code needed)

Log in to the admin panel and open **Site Settings** to change:
the gym name, logo, favicon, tagline, primary / secondary / button colors,
hero heading, subheading and background image, about content, contact details,
social links, business hours, footer text, analytics codes and default SEO.

Use **Contact Settings** for quick edits to phone, WhatsApp, email, address, map and socials,
and **SEO Manager** to control SEO for every page individually.

---

## 🌐 SEO Setup

- `sitemap.xml` and `robots.txt` are generated dynamically (via `.htaccess` rewrites to
  `sitemap.php` / `robots.php`). On Nginx, point those URLs to the PHP files manually.
- Submit `https://yourdomain.com/sitemap.xml` to Google Search Console.
- Add your Google Analytics ID, Search Console verification and Facebook Pixel ID in **Site Settings**.

---

## 📁 Project Structure

```
index.php, about.php, services.php, plans.php, trainers.php, classes.php,
gallery.php, testimonials.php, blog.php, post.php, contact.php, thank-you.php
sitemap.php, robots.php
config.php, functions.php, header.php, footer.php, database.sql, .htaccess
assets/css/style.css, assets/css/admin.css, assets/js/main.js
uploads/                      (user-uploaded images)
admin/
  login.php, logout.php, dashboard.php, settings.php, contact-settings.php,
  seo-manager.php, plans.php, plan-add.php, plan-edit.php,
  trainers.php, trainer-add.php, trainer-edit.php,
  classes.php, class-add.php, class-edit.php,
  gallery.php, testimonials.php, transformations.php,
  blog-manager.php, blog-add.php, blog-edit.php, enquiries.php,
  includes/admin-header.php, includes/admin-footer.php
```

---

## 🛡️ Production Checklist

- [ ] **Delete `install.php`** after a successful installation.
- [ ] Change the default admin password (or set your own via the installer).
- [ ] Confirm DB credentials in `config.local.php` / `config.php`.
- [ ] Ensure `uploads/` is writable but cannot execute scripts (handled by `uploads/.htaccess`).
- [ ] Force HTTPS at the server / host level.
- [ ] Keep `display_errors` off in production (already disabled in `config.php`).

Enjoy your new gym website! 💪
