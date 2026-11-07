/* ======================================================
   Red Feather Club — theme JavaScript
   Mobile nav, social tabs, gallery filters, lightbox,
   scroll reveals and smooth anchor scrolling.
   ====================================================== */

(function () {
  'use strict';

  // --- Mobile nav ---
  var burger = document.querySelector('.nav__burger');
  var mobileNav = document.querySelector('.nav__mobile');

  function closeMobileNav() {
    if (!mobileNav || !burger) return;
    mobileNav.classList.remove('open');
    burger.setAttribute('aria-expanded', 'false');
    burger.querySelectorAll('span').forEach(function (s) {
      s.style.transform = '';
      s.style.opacity = '';
    });
  }

  if (burger && mobileNav) {
    burger.addEventListener('click', function () {
      mobileNav.classList.toggle('open');
      var spans = burger.querySelectorAll('span');
      var isOpen = mobileNav.classList.contains('open');
      burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (isOpen) {
        spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
        spans[1].style.opacity = '0';
        spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
      } else {
        spans.forEach(function (s) {
          s.style.transform = '';
          s.style.opacity = '';
        });
      }
    });

    mobileNav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', closeMobileNav);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeMobileNav();
    });
  }

  // --- Social feed tabs ---
  var tabs = document.querySelectorAll('.social-tab');
  var panels = document.querySelectorAll('.social-tab-panel');

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      tabs.forEach(function (t) {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');
      var target = tab.dataset.tab;
      panels.forEach(function (p) {
        p.classList.toggle('active', p.dataset.panel === target);
      });
    });
  });

  // --- Gallery category filters ---
  var filterBtns = document.querySelectorAll('.filter-btn');
  var photoItems = document.querySelectorAll('.photo-item');

  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var filter = btn.dataset.filter || 'all';
      filterBtns.forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');

      photoItems.forEach(function (item) {
        var cats = (item.dataset.category || '').split(/\s+/);
        var show = filter === 'all' || cats.indexOf(filter) !== -1;
        item.classList.toggle('is-hidden', !show);
      });
    });
  });

  // --- Lightbox ---
  var lightbox = document.getElementById('lightbox');
  var lightboxImg = document.getElementById('lightbox-img');

  function openLightbox(src) {
    if (!src || !lightbox) return;
    lightboxImg.src = src;
    lightbox.classList.add('open');
  }

  if (lightbox) {
    document.querySelectorAll('[data-lightbox]').forEach(function (el) {
      el.addEventListener('click', function () {
        var img = el.querySelector('img');
        openLightbox(el.dataset.lightbox || (img && img.src));
      });
    });

    // WordPress core galleries and images open in the same lightbox.
    document.querySelectorAll(
      '.wp-block-gallery img, .wp-block-image img, .gallery img'
    ).forEach(function (img) {
      var wrapper = img.closest('figure, .gallery-item') || img.parentNode;
      if (wrapper.closest('[data-lightbox]')) return;

      img.style.cursor = 'zoom-in';
      img.addEventListener('click', function (e) {
        var link = img.closest('a');
        var href = link ? link.getAttribute('href') : null;

        // Only hijack links that point at the image file itself.
        if (href && !/\.(jpe?g|png|gif|webp|avif)$/i.test(href)) return;
        e.preventDefault();
        openLightbox(href || img.currentSrc || img.src);
      });
    });

    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox || e.target.classList.contains('lightbox__close')) {
        lightbox.classList.remove('open');
        lightboxImg.src = '';
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        lightbox.classList.remove('open');
        lightboxImg.src = '';
      }
    });
  }

  // --- Fade-up on scroll ---
  var fadeEls = document.querySelectorAll('.observe-fade');

  if (fadeEls.length && 'IntersectionObserver' in window &&
      !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'none';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    fadeEls.forEach(function (el) {
      el.style.opacity = '0';
      el.style.transform = 'translateY(24px)';
      el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
      observer.observe(el);
    });
  }

  // --- Smooth scroll for in-page anchors ---
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var hash = this.getAttribute('href');
      if (!hash || hash === '#' || hash.length < 2) return;

      var target = document.querySelector(hash);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
})();
