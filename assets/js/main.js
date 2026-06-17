/* =====================================================================
   Rebrandable Gym Website - Front-end interactions
   ===================================================================== */
(function () {
  'use strict';

  /* ---- Mobile nav toggle ---- */
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    });
    // close on link click
    nav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        nav.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });
  }

  /* ---- Header shadow on scroll ---- */
  var header = document.getElementById('siteHeader');
  if (header) {
    var onScroll = function () {
      header.classList.toggle('scrolled', window.scrollY > 30);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---- FAQ accordion ---- */
  document.querySelectorAll('.faq-q').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.closest('.faq-item');
      var isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(function (i) {
        if (i !== item) i.classList.remove('open');
      });
      item.classList.toggle('open', !isOpen);
    });
  });

  /* ---- Gallery lightbox ---- */
  var lightbox = document.getElementById('lightbox');
  if (lightbox) {
    var lbImg = lightbox.querySelector('img');
    document.querySelectorAll('[data-lightbox]').forEach(function (el) {
      el.addEventListener('click', function () {
        var src = el.getAttribute('data-lightbox');
        if (src && lbImg) {
          lbImg.src = src;
          lightbox.classList.add('open');
          document.body.style.overflow = 'hidden';
        }
      });
    });
    var close = function () {
      lightbox.classList.remove('open');
      document.body.style.overflow = '';
    };
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox || e.target.classList.contains('lb-close')) close();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') close();
    });
  }

  /* ---- Simple client-side form validation hook ---- */
  document.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var ok = true;
      form.querySelectorAll('[required]').forEach(function (field) {
        if (!field.value.trim()) {
          ok = false;
          field.style.borderColor = '#ef4444';
        } else {
          field.style.borderColor = '';
        }
      });
      if (!ok) {
        e.preventDefault();
      }
    });
  });
})();
