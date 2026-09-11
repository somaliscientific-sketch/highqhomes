/* HighQ Homes — Frontend JS */

// ─── Navbar scroll effect ────────────────────────────────────
const navbar = document.getElementById('main-navbar');
const headerWrap = document.getElementById('main-header-wrapper');
if (navbar || headerWrap) {
  const updateNav = () => {
    const on = window.scrollY > 12;
    navbar?.classList.toggle('scrolled', on);
    headerWrap?.classList.toggle('is-scrolled', on);
  };
  window.addEventListener('scroll', updateNav, { passive: true });
  updateNav();
}

// ─── Mobile menu ────────────────────────────────────────────
(function initMobileMenu() {
  const menuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const menuClose = document.getElementById('mobile-menu-close');
  if (!menuBtn || !mobileMenu) return;

  const desktopQuery = window.matchMedia('(min-width: 1024px)');
  let lastFocus = null;
  let lastScrollY = 0;

  const isOpen = () => mobileMenu.classList.contains('is-open');

  const focusable = () => Array.from(
    mobileMenu.querySelectorAll('a[href], button:not([disabled])')
  ).filter((el) => el.getAttribute('tabindex') !== '-1');

  const setInert = (on) => {
    if ('inert' in mobileMenu) {
      mobileMenu.inert = on;
    }
  };

  const lockScroll = () => {
    lastScrollY = window.scrollY;
    document.documentElement.classList.add('hq-menu-open');
    document.body.classList.add('hq-menu-open');
    document.body.style.top = `-${lastScrollY}px`;
  };

  const unlockScroll = () => {
    document.documentElement.classList.remove('hq-menu-open');
    document.body.classList.remove('hq-menu-open');
    document.body.style.top = '';
    window.scrollTo(0, lastScrollY);
  };

  const openMenu = () => {
    if (isOpen() || desktopQuery.matches) return;
    lastFocus = document.activeElement;
    mobileMenu.classList.add('is-open');
    menuBtn.classList.add('is-active');
    menuBtn.setAttribute('aria-expanded', 'true');
    menuBtn.setAttribute('aria-label', 'Close menu');
    mobileMenu.setAttribute('aria-hidden', 'false');
    setInert(false);
    lockScroll();
    window.requestAnimationFrame(() => {
      (menuClose || mobileMenu.querySelector('.hq-mobile__links a'))?.focus();
    });
  };

  const closeMenu = () => {
    if (!isOpen()) return;
    mobileMenu.classList.remove('is-open');
    menuBtn.classList.remove('is-active');
    menuBtn.setAttribute('aria-expanded', 'false');
    menuBtn.setAttribute('aria-label', 'Open menu');
    mobileMenu.setAttribute('aria-hidden', 'true');
    setInert(true);
    unlockScroll();
    if (lastFocus && typeof lastFocus.focus === 'function') {
      lastFocus.focus();
    } else {
      menuBtn.focus();
    }
  };

  menuBtn.addEventListener('click', () => {
    if (isOpen()) closeMenu();
    else openMenu();
  });
  menuClose?.addEventListener('click', closeMenu);
  mobileMenu.querySelectorAll('.hq-mobile-backdrop, .hq-mobile__backdrop').forEach((el) => {
    el.addEventListener('click', closeMenu);
  });
  mobileMenu.querySelectorAll('.hq-mobile__links a, .hq-mobile__head a, .hq-mobile__footer a').forEach((link) => {
    link.addEventListener('click', () => closeMenu());
  });
  document.addEventListener('keydown', (e) => {
    if (!isOpen()) return;
    if (e.key === 'Escape') {
      e.preventDefault();
      closeMenu();
      return;
    }
    if (e.key !== 'Tab') return;
    const items = focusable();
    if (items.length === 0) return;
    const first = items[0];
    const last = items[items.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  });
  const onDesktopChange = (event) => {
    if (event.matches) closeMenu();
  };
  if (desktopQuery.addEventListener) {
    desktopQuery.addEventListener('change', onDesktopChange);
  } else {
    desktopQuery.addListener(onDesktopChange);
  }
  setInert(true);
})();

// ─── Hero parallax (cinematic + legacy) ──────────────────────
const heroParallaxRoot = document.querySelector('#hero.hq-hero--carousel');
const heroParallaxImg = heroParallaxRoot ? null : document.querySelector('.hq-hero__shot.is-active img, .hq-hero__photo.is-active img, .hq-hero__photo img, .hq-hero__frame img, .hq-hero__bg img, .lux-hero-bg img');
if (heroParallaxImg && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  let ticking = false;
  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        const scrolled = window.scrollY;
        const img = document.querySelector('.hq-hero__shot.is-active img, .hq-hero__photo.is-active img') || heroParallaxImg;
        if (scrolled < window.innerHeight && img) {
          img.style.transform = `scale(${1 + scrolled * 0.00018}) translateY(${scrolled * 0.1}px)`;
        }
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
}

// ─── Hero carousel ───────────────────────────────────────────
(function initHeroCarousel() {
  const hero = document.getElementById('hero');
  if (!hero || hero.dataset.heroCarousel !== '1') return;

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const autoplay = hero.dataset.autoplay === '1' && !reduceMotion;
  const interval = parseInt(hero.dataset.interval || '6000', 10);
  const pauseOnHover = hero.dataset.pauseHover === '1';
  const defaultTransition = hero.dataset.transition || 'kenburns';

  hero.style.setProperty('--hero-interval', `${interval}ms`);

  const copyWrap = hero.querySelector('.hq-hero__panes--copy');
  const copyPanes = Array.from(hero.querySelectorAll('.hq-hero__panes--copy [data-hero-pane]'));
  const mediaPanes = Array.from(hero.querySelectorAll('.hq-hero__panes--media [data-hero-pane]'));
  const dots = Array.from(hero.querySelectorAll('[data-hero-dot]'));
  const counterEl = hero.querySelector('[data-hero-current]');
  const prevBtn = hero.querySelector('[data-hero-prev]');
  const nextBtn = hero.querySelector('[data-hero-next]');
  const count = copyPanes.length;
  if (count < 2) return;

  let current = 0;
  let timer = null;
  let paused = false;
  let touchStartX = 0;
  let touchStartY = 0;

  const pad = (n) => String(n + 1).padStart(2, '0');

  const slideDuration = (index) => {
    const pane = mediaPanes[index] || copyPanes[index];
    const custom = parseInt(pane?.dataset.heroDuration || '0', 10);
    return custom >= 3000 ? custom : interval;
  };

  const slideTransition = (index) => {
    const pane = mediaPanes[index];
    const value = pane?.dataset.heroTransition || defaultTransition;
    return value === 'inherit' ? defaultTransition : value;
  };

  const syncMedia = (index) => {
    mediaPanes.forEach((pane, i) => {
      const video = pane.querySelector('video');
      if (!video) return;
      if (i === index) {
        video.muted = true;
        const playPromise = video.play();
        if (playPromise && typeof playPromise.catch === 'function') {
          playPromise.catch(() => {});
        }
      } else {
        video.pause();
        try {
          video.currentTime = 0;
        } catch (err) {
          /* ignore */
        }
      }
    });
  };

  const syncCopyHeight = () => {
    if (!copyWrap) return;
    const active = copyWrap.querySelector('.hq-hero__pane.is-active');
    if (active) {
      copyWrap.style.minHeight = `${active.offsetHeight}px`;
    }
  };

  const resetDotProgress = () => {
    dots.forEach((dot) => {
      const fill = dot.querySelector('[data-hero-dot-fill]');
      if (!fill) return;
      fill.style.animation = 'none';
      void fill.offsetWidth;
      if (dot.classList.contains('is-active') && autoplay && !paused) {
        fill.style.animationDuration = `${slideDuration(current)}ms`;
        fill.style.animation = '';
      }
    });
  };

  const setSlide = (index) => {
    current = (index + count) % count;
    hero.dataset.transition = slideTransition(current);
    hero.style.setProperty('--hero-interval', `${slideDuration(current)}ms`);
    copyPanes.forEach((pane, i) => {
      const active = i === current;
      pane.classList.toggle('is-active', active);
      pane.setAttribute('aria-hidden', active ? 'false' : 'true');
    });
    mediaPanes.forEach((pane, i) => {
      const active = i === current;
      pane.classList.toggle('is-active', active);
      pane.setAttribute('aria-hidden', active ? 'false' : 'true');
    });
    dots.forEach((dot, i) => {
      const active = i === current;
      dot.classList.toggle('is-active', active);
      dot.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    if (counterEl) counterEl.textContent = pad(current);
    syncMedia(current);
    window.requestAnimationFrame(() => {
      syncCopyHeight();
      resetDotProgress();
    });
  };

  const stop = () => {
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
    resetDotProgress();
  };

  const start = () => {
    stop();
    if (!autoplay || paused) return;
    resetDotProgress();
    timer = window.setTimeout(() => {
      setSlide(current + 1);
      start();
    }, slideDuration(current));
  };

  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      setSlide(parseInt(dot.dataset.heroDot || '0', 10));
      start();
    });
  });

  prevBtn?.addEventListener('click', () => {
    setSlide(current - 1);
    start();
  });

  nextBtn?.addEventListener('click', () => {
    setSlide(current + 1);
    start();
  });

  if (pauseOnHover) {
    hero.addEventListener('mouseenter', () => {
      paused = true;
      stop();
    });
    hero.addEventListener('mouseleave', () => {
      paused = false;
      start();
    });
    hero.addEventListener('focusin', () => {
      paused = true;
      stop();
    });
    hero.addEventListener('focusout', (e) => {
      if (!hero.contains(e.relatedTarget)) {
        paused = false;
        start();
      }
    });
  }

  window.addEventListener('resize', syncCopyHeight);
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      paused = true;
      stop();
      mediaPanes.forEach((pane) => pane.querySelector('video')?.pause());
    } else {
      paused = false;
      syncMedia(current);
      start();
    }
  });
  document.addEventListener('keydown', (event) => {
    if (event.target.closest('input, textarea, select, [contenteditable="true"]')) return;
    const rect = hero.getBoundingClientRect();
    if (rect.bottom < 80 || rect.top > window.innerHeight - 80) return;
    if (event.key === 'ArrowRight') {
      event.preventDefault();
      setSlide(current + 1);
      start();
    } else if (event.key === 'ArrowLeft') {
      event.preventDefault();
      setSlide(current - 1);
      start();
    }
  });

  hero.addEventListener('touchstart', (event) => {
    const touch = event.changedTouches[0];
    if (!touch) return;
    touchStartX = touch.clientX;
    touchStartY = touch.clientY;
  }, { passive: true });

  hero.addEventListener('touchend', (event) => {
    const touch = event.changedTouches[0];
    if (!touch) return;
    const dx = touch.clientX - touchStartX;
    const dy = touch.clientY - touchStartY;
    if (Math.abs(dx) < 56 || Math.abs(dx) < Math.abs(dy)) return;
    setSlide(current + (dx < 0 ? 1 : -1));
    start();
  }, { passive: true });

  setSlide(0);
  start();
})();

// ─── Testimonials carousel (homepage) ───────────────────────
(function initTestimonialsCarousel() {
  const carousel = document.querySelector('[data-testimonials-carousel]');
  if (!carousel) return;

  const slides = [...carousel.querySelectorAll('[data-testimonial-slide]')];
  const dots = [...document.querySelectorAll('[data-testimonial-dot]')];
  const thumbs = [...document.querySelectorAll('[data-testimonial-thumb]')];
  if (slides.length <= 1) return;

  let index = 0;
  let timer = null;

  const show = (nextIndex) => {
    index = (nextIndex + slides.length) % slides.length;
    slides.forEach((slide, i) => {
      const active = i === index;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', active ? 'false' : 'true');
    });
    dots.forEach((dot, i) => {
      dot.classList.toggle('is-active', i === index);
      dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
    });
    thumbs.forEach((thumb, i) => thumb.classList.toggle('is-active', i === index));
  };

  const restart = () => {
    if (timer) clearInterval(timer);
    timer = window.setInterval(() => show(index + 1), 7000);
  };

  document.querySelector('[data-testimonial-prev]')?.addEventListener('click', () => {
    show(index - 1);
    restart();
  });
  document.querySelector('[data-testimonial-next]')?.addEventListener('click', () => {
    show(index + 1);
    restart();
  });
  dots.forEach((dot, i) => dot.addEventListener('click', () => {
    show(i);
    restart();
  }));
  thumbs.forEach((thumb, i) => thumb.addEventListener('click', () => {
    show(i);
    restart();
  }));

  restart();
})();

// ─── Testimonials Slider (legacy) ───────────────────────────
const testimonialSwiper = document.getElementById('testimonial-swiper');
if (testimonialSwiper && typeof Swiper !== 'undefined') {
  new Swiper('#testimonial-swiper', {
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: { el: '#testimonial-swiper .swiper-pagination', clickable: true },
    breakpoints: {
      0:   { slidesPerView: 1, spaceBetween: 20 },
      640: { slidesPerView: 2, spaceBetween: 24 },
      1024:{ slidesPerView: 3, spaceBetween: 28 },
    },
  });
}

// ─── Counter animation ──────────────────────────────────────
function animateCounter(el) {
  const target = parseFloat(el.dataset.target || '0');
  const isFloat = el.dataset.float === '1';
  const suffix  = el.dataset.suffix || '';
  const duration = 2000;
  const step = target / (duration / 16);
  let current = 0;
  const timer = setInterval(() => {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    el.textContent = (isFloat ? current.toFixed(1) : Math.floor(current)) + suffix;
  }, 16);
}

const counters = document.querySelectorAll('[data-counter]');
if (counters.length) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        animateCounter(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  counters.forEach(c => observer.observe(c));
}

// ─── Scroll animations ──────────────────────────────────────
const animEls = document.querySelectorAll('[data-anim]');
if (animEls.length) {
  const animObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        const anim = el.dataset.anim;
        const delay = el.dataset.delay || '0';
        el.style.transitionDelay = delay + 'ms';
        el.classList.add('anim-in', `anim-${anim}`);
        animObserver.unobserve(el);
      }
    });
  }, { threshold: 0.15 });
  animEls.forEach(el => {
    el.classList.add('anim-ready');
    animObserver.observe(el);
  });
}

// ─── FAQ accordion (homepage) ───────────────────────────────
document.querySelectorAll('[data-faq-toggle]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const item = btn.closest('.hq-faq__item');
    if (!item) return;
    const isOpen = item.classList.contains('is-open');
    item.closest('.hq-faq__list, .hq-svc-faq__list')?.querySelectorAll('.hq-faq__item.is-open').forEach((openItem) => {
      if (openItem !== item) {
        openItem.classList.remove('is-open');
        openItem.querySelector('[data-faq-toggle]')?.setAttribute('aria-expanded', 'false');
      }
    });
    item.classList.toggle('is-open', !isOpen);
    btn.setAttribute('aria-expanded', String(!isOpen));
  });
});

// ─── Lightbox ───────────────────────────────────────────────
const lightboxItems = document.querySelectorAll('[data-lightbox]');
const lightbox = document.getElementById('lightbox-modal');
if (lightbox && lightboxItems.length) {
  let currentIdx = 0;
  const images = Array.from(lightboxItems).map(el => ({
    src: el.dataset.src || el.querySelector('img')?.src || '',
    alt: el.dataset.alt || '',
  }));
  const lbImg = lightbox.querySelector('.lightbox-img');

  const open = (idx) => {
    currentIdx = (idx + images.length) % images.length;
    lbImg.src = images[currentIdx].src;
    lbImg.alt = images[currentIdx].alt;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  };
  const close = () => {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  };

  lightboxItems.forEach((el, idx) => el.addEventListener('click', () => open(idx)));
  lightbox.querySelector('.lightbox-close')?.addEventListener('click', close);
  lightbox.querySelector('.lightbox-prev')?.addEventListener('click', () => open(currentIdx - 1));
  lightbox.querySelector('.lightbox-next')?.addEventListener('click', () => open(currentIdx + 1));
  lightbox.addEventListener('click', e => { if (e.target === lightbox) close(); });
  document.addEventListener('keydown', e => {
    if (!lightbox.classList.contains('active')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') open(currentIdx - 1);
    if (e.key === 'ArrowRight') open(currentIdx + 1);
  });
}

// ─── Gallery category filter ────────────────────────────────
const filterBtns = document.querySelectorAll('[data-filter-btn]');
const filterItems = document.querySelectorAll('[data-filter-item]');
filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const val = btn.dataset.filterBtn;
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterItems.forEach(item => {
      const cat = item.dataset.filterItem;
      item.style.display = (!val || val === 'all' || cat === val) ? '' : 'none';
    });
  });
});

// ─── Admin sidebar toggle ───────────────────────────────────
const adminToggle = document.getElementById('admin-sidebar-toggle');
const adminSidebar = document.getElementById('admin-sidebar');
const adminOverlay = document.getElementById('admin-overlay');
if (adminToggle && adminSidebar) {
  adminToggle.addEventListener('click', () => {
    const isOpen = adminSidebar.classList.toggle('open');
    adminOverlay?.classList.toggle('active');
    document.body.style.overflow = isOpen ? 'hidden' : '';
  });
  adminOverlay?.addEventListener('click', () => {
    adminSidebar.classList.remove('open');
    adminOverlay.classList.remove('active');
    document.body.style.overflow = '';
  });
}

// ─── Image preview for file inputs ─────────────────────────
document.querySelectorAll('[data-preview-for]').forEach(input => {
  const targetId = input.dataset.previewFor;
  const preview = document.getElementById(targetId);
  if (!preview) return;
  input.addEventListener('change', () => {
    const file = input.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      preview.style.display = 'block';
      // Hide any sibling placeholder text
      preview.parentElement?.querySelectorAll('[id$="-placeholder"]').forEach(el => el.style.display = 'none');
    };
    reader.readAsDataURL(file);
  });
});

// ─── Flash auto-dismiss ─────────────────────────────────────
document.querySelectorAll('[data-flash]').forEach(el => {
  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transition = 'opacity 0.5s';
    setTimeout(() => el.remove(), 500);
  }, 5000);
});

// ─── Confirm delete ─────────────────────────────────────────
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    const msg = el.dataset.confirm || 'Are you sure you want to delete this?';
    if (!confirm(msg)) e.preventDefault();
  });
});

// ─── Project gallery remove ─────────────────────────────────
document.querySelectorAll('.remove-gallery-img').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.closest('[data-gallery-item]')?.remove();
  });
});

// ─── Premium public reveal interactions ─────────────────────
if (!document.querySelector('.admin-main')) {
  const revealTargets = document.querySelectorAll([
    '.home-band',
    '.subpage-section',
    '.contact-band',
    '.home-service-card',
    '.home-project-card',
    '.process-card',
    '.testimonial-card',
    '.home-team-card',
    '.content-card'
  ].join(','));

  if (revealTargets.length && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        revealObserver.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    revealTargets.forEach((el, index) => {
      el.classList.add('premium-reveal');
      el.style.transitionDelay = `${Math.min(index % 6, 5) * 55}ms`;
      revealObserver.observe(el);
    });
  }
}

// ─── Back-to-Top Button ──────────────────────────────────────
(function initBackToTop() {
  const btn = document.getElementById('hq-back-to-top');
  if (!btn) return;

  const THRESHOLD = 320;

  const onScroll = () => {
    btn.classList.toggle('is-visible', window.scrollY > THRESHOLD);
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // sync on load

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();

