/**
 * Neighborhood Connect — Main JavaScript
 */

(function () {
  'use strict';

  /* ============================================================
     Theme Toggle (Dark / Light Mode)
     ============================================================ */
  const ThemeManager = {
    STORAGE_KEY: 'nc-theme',

    init() {
      const saved = localStorage.getItem(this.STORAGE_KEY);
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      this.apply(saved || (prefersDark ? 'dark' : 'light'));

      document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.addEventListener('click', () => this.toggle());
      });

      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem(this.STORAGE_KEY)) {
          this.apply(e.matches ? 'dark' : 'light');
        }
      });
    },

    apply(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.querySelector('i').className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        btn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
      });
    },

    toggle() {
      const current = document.documentElement.getAttribute('data-theme');
      const next = current === 'dark' ? 'light' : 'dark';
      localStorage.setItem(this.STORAGE_KEY, next);
      this.apply(next);
    }
  };

  /* ============================================================
     Mobile Navigation
     ============================================================ */
  const MobileNav = {
    init() {
      const toggle = document.querySelector('.menu-toggle');
      const nav = document.querySelector('.mobile-nav');
      const close = document.querySelector('.mobile-nav-close');
      const overlay = nav;

      if (!toggle || !nav) return;

      const open = () => {
        toggle.classList.add('is-active');
        nav.classList.add('is-open');
        document.body.style.overflow = 'hidden';
        toggle.setAttribute('aria-expanded', 'true');
      };

      const closeFn = () => {
        toggle.classList.remove('is-active');
        nav.classList.remove('is-open');
        document.body.style.overflow = '';
        toggle.setAttribute('aria-expanded', 'false');
      };

      toggle.addEventListener('click', () => {
        toggle.classList.contains('is-active') ? closeFn() : open();
      });

      close && close.addEventListener('click', closeFn);

      overlay.addEventListener('click', e => {
        if (e.target === overlay) closeFn();
      });

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeFn();
      });
    }
  };

  /* ============================================================
     Back to Top Button
     ============================================================ */
  const BackToTop = {
    init() {
      const btn = document.querySelector('.back-to-top');
      if (!btn) return;

      window.addEventListener('scroll', () => {
        btn.classList.toggle('visible', window.scrollY > 400);
      }, { passive: true });

      btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
  };

  /* ============================================================
     Toast Notifications
     ============================================================ */
  const Toast = {
    container: null,

    init() {
      this.container = document.querySelector('.toast-container');
      if (!this.container) {
        this.container = document.createElement('div');
        this.container.className = 'toast-container';
        document.body.appendChild(this.container);
      }
    },

    show(title, message = '', type = 'info', duration = 4000) {
      const icons = { success: 'fa-check', error: 'fa-xmark', info: 'fa-info' };
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `
        <div class="toast-icon"><i class="fa-solid ${icons[type] || icons.info}"></i></div>
        <div class="toast-content">
          <div class="toast-title">${title}</div>
          ${message ? `<div class="toast-message">${message}</div>` : ''}
        </div>
        <button class="toast-dismiss" aria-label="Dismiss"><i class="fa-solid fa-xmark"></i></button>
      `;

      this.container.appendChild(toast);
      toast.querySelector('.toast-dismiss').addEventListener('click', () => this.dismiss(toast));

      if (duration > 0) {
        setTimeout(() => this.dismiss(toast), duration);
      }
    },

    dismiss(toast) {
      toast.style.animation = 'toast-in 0.3s ease reverse';
      setTimeout(() => toast.remove(), 300);
    }
  };

  /* ============================================================
     RSVP Functionality (AJAX)
     ============================================================ */
  const RSVP = {
    init() {
      document.addEventListener('click', e => {
        const btn = e.target.closest('.rsvp-btn');
        if (!btn) return;
        e.preventDefault();
        this.toggle(btn);
      });
    },

    toggle(btn) {
      const eventId = btn.dataset.eventId;
      const isJoined = btn.classList.contains('rsvp-joined');

      if (!eventId) return;

      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

      fetch(ncData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'nc_rsvp',
          event_id: eventId,
          type: isJoined ? 'cancel' : 'join',
          nonce: ncData.nonce
        })
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            btn.classList.toggle('rsvp-joined', !isJoined);
            btn.innerHTML = !isJoined
              ? '<i class="fa-solid fa-check"></i> Joined'
              : '<i class="fa-solid fa-plus"></i> RSVP';
            const countEl = btn.closest('.event-card-footer')?.querySelector('.rsvp-number');
            if (countEl && data.data.count !== undefined) {
              countEl.textContent = data.data.count;
            }
            Toast.show(
              !isJoined ? 'RSVP Confirmed!' : 'RSVP Cancelled',
              !isJoined ? "You're on the list." : 'Your spot has been released.',
              !isJoined ? 'success' : 'info'
            );
          } else {
            Toast.show('Error', data.data?.message || 'Please log in to RSVP.', 'error');
            btn.innerHTML = isJoined ? '<i class="fa-solid fa-check"></i> Joined' : '<i class="fa-solid fa-plus"></i> RSVP';
          }
        })
        .catch(() => {
          Toast.show('Network Error', 'Please try again.', 'error');
          btn.innerHTML = isJoined ? '<i class="fa-solid fa-check"></i> Joined' : '<i class="fa-solid fa-plus"></i> RSVP';
        })
        .finally(() => {
          btn.disabled = false;
        });
    }
  };

  /* ============================================================
     Issue Voting (AJAX)
     ============================================================ */
  const IssueVote = {
    init() {
      document.addEventListener('click', e => {
        const btn = e.target.closest('.vote-btn');
        if (!btn) return;
        e.preventDefault();
        this.vote(btn);
      });
    },

    vote(btn) {
      const issueId = btn.dataset.issueId;
      if (!issueId) return;

      btn.disabled = true;

      fetch(ncData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          action: 'nc_vote_issue',
          issue_id: issueId,
          nonce: ncData.nonce
        })
      })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            btn.classList.toggle('voted');
            const count = btn.querySelector('.vote-count');
            if (count) count.textContent = data.data.votes;
          } else {
            Toast.show('Login Required', 'Please log in to vote.', 'info');
          }
        })
        .catch(() => Toast.show('Error', 'Please try again.', 'error'))
        .finally(() => btn.disabled = false);
    }
  };

  /* ============================================================
     Filter Chips
     ============================================================ */
  const FilterChips = {
    init() {
      document.querySelectorAll('.filter-chips').forEach(container => {
        container.addEventListener('click', e => {
          const chip = e.target.closest('.chip');
          if (!chip) return;

          const multiSelect = container.dataset.multiSelect === 'true';

          if (!multiSelect) {
            container.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
          }

          chip.classList.toggle('active');
          this.applyFilter(container);
        });
      });
    },

    applyFilter(container) {
      const active = [...container.querySelectorAll('.chip.active')].map(c => c.dataset.filter);
      const target = document.querySelector(container.dataset.target);
      if (!target) return;

      target.querySelectorAll('[data-category]').forEach(item => {
        const cats = item.dataset.category?.split(',') || [];
        const show = active.length === 0 || active.includes('all') || cats.some(c => active.includes(c));
        item.style.display = show ? '' : 'none';
      });
    }
  };

  /* ============================================================
     Modal Manager
     ============================================================ */
  const Modal = {
    init() {
      document.addEventListener('click', e => {
        const trigger = e.target.closest('[data-modal]');
        if (trigger) {
          e.preventDefault();
          const modal = document.getElementById(trigger.dataset.modal);
          if (modal) this.open(modal);
        }

        const closeBtn = e.target.closest('[data-modal-close]') || e.target.closest('.modal-close');
        if (closeBtn) {
          const overlay = closeBtn.closest('.modal-overlay');
          if (overlay) this.close(overlay);
        }

        if (e.target.classList.contains('modal-overlay')) {
          this.close(e.target);
        }
      });

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          const open = document.querySelector('.modal-overlay.is-open');
          if (open) this.close(open);
        }
      });
    },

    open(overlay) {
      overlay.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    },

    close(overlay) {
      overlay.classList.remove('is-open');
      document.body.style.overflow = '';
    }
  };

  /* ============================================================
     Scroll Animations (Intersection Observer)
     ============================================================ */
  const ScrollAnimations = {
    init() {
      if (!window.IntersectionObserver) return;

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '';
            entry.target.style.transform = '';
            entry.target.classList.add('animate-fade-in-up');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

      document.querySelectorAll('.event-card, .service-card, .feature-card, .testimonial-card').forEach(el => {
        observer.observe(el);
      });
    }
  };

  /* ============================================================
     Newsletter Form
     ============================================================ */
  const Newsletter = {
    init() {
      const forms = document.querySelectorAll('.newsletter-form');
      forms.forEach(form => {
        form.addEventListener('submit', e => {
          e.preventDefault();
          const input = form.querySelector('input[type="email"]');
          const email = input?.value.trim();

          if (!email || !this.isValid(email)) {
            Toast.show('Invalid Email', 'Please enter a valid email address.', 'error');
            return;
          }

          const btn = form.querySelector('button[type="submit"]');
          if (btn) { btn.disabled = true; btn.textContent = 'Subscribing...'; }

          fetch(ncData.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'nc_newsletter', email, nonce: ncData.nonce })
          })
            .then(r => r.json())
            .then(data => {
              if (data.success) {
                Toast.show('Subscribed!', 'Welcome to the Neighborhood Connect newsletter.', 'success');
                input.value = '';
              } else {
                Toast.show('Error', data.data?.message || 'Please try again.', 'error');
              }
            })
            .catch(() => Toast.show('Network Error', 'Please try again later.', 'error'))
            .finally(() => {
              if (btn) { btn.disabled = false; btn.textContent = 'Subscribe'; }
            });
        });
      });
    },

    isValid(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
  };

  /* ============================================================
     Smooth Scroll for Anchor Links
     ============================================================ */
  const SmoothScroll = {
    init() {
      document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
          const target = document.querySelector(link.getAttribute('href'));
          if (target) {
            e.preventDefault();
            const offset = 80;
            const top = target.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
          }
        });
      });
    }
  };

  /* ============================================================
     Search Autocomplete (Live AJAX Search)
     ============================================================ */
  const SearchAC = {
    init() {
      const inputs = document.querySelectorAll('.search-input[data-autocomplete]');
      inputs.forEach(input => {
        let timer;
        let dropdown;

        input.addEventListener('input', () => {
          clearTimeout(timer);
          const q = input.value.trim();
          if (q.length < 2) { dropdown && dropdown.remove(); return; }

          timer = setTimeout(() => this.fetch(input, q, dropdown => {
            this.showDropdown(input, dropdown);
          }), 300);
        });

        input.addEventListener('blur', () => {
          setTimeout(() => dropdown && dropdown.remove(), 200);
        });
      });
    },

    fetch(input, query, cb) {
      fetch(ncData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'nc_search', q: query, nonce: ncData.nonce })
      })
        .then(r => r.json())
        .then(data => { if (data.success) cb(data.data); })
        .catch(() => {});
    },

    showDropdown(input, results) {
      const existing = document.querySelector('.search-dropdown');
      if (existing) existing.remove();

      if (!results.length) return;

      const dropdown = document.createElement('div');
      dropdown.className = 'search-dropdown';
      dropdown.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--color-bg-card);
        border: 1px solid var(--color-border);
        border-top: none;
        border-radius: 0 0 var(--radius) var(--radius);
        box-shadow: var(--shadow-lg);
        z-index: var(--z-dropdown);
        max-height: 300px;
        overflow-y: auto;
      `;

      results.forEach(item => {
        const el = document.createElement('a');
        el.href = item.url;
        el.style.cssText = `
          display: flex;
          align-items: center;
          gap: var(--space-3);
          padding: var(--space-3) var(--space-4);
          font-size: var(--text-sm);
          color: var(--color-text);
          transition: background var(--transition-fast);
        `;
        el.innerHTML = `<i class="fa-solid ${item.icon || 'fa-file'}" style="color:var(--color-primary);width:16px"></i><span>${item.title}</span>`;
        el.addEventListener('mouseenter', () => el.style.background = 'var(--color-primary-light)');
        el.addEventListener('mouseleave', () => el.style.background = '');
        dropdown.appendChild(el);
      });

      const wrap = input.closest('.search-input-wrap') || input.parentElement;
      wrap.style.position = 'relative';
      wrap.appendChild(dropdown);
    }
  };

  /* ============================================================
     Sticky Header Shadow
     ============================================================ */
  const StickyHeader = {
    init() {
      const header = document.querySelector('.site-header');
      if (!header) return;

      window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 10);
      }, { passive: true });
    }
  };

  /* ============================================================
     Counter Animation (Stats)
     ============================================================ */
  const CounterAnimation = {
    init() {
      if (!window.IntersectionObserver) return;

      const counters = document.querySelectorAll('[data-count]');
      if (!counters.length) return;

      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            this.animate(entry.target);
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.5 });

      counters.forEach(el => observer.observe(el));
    },

    animate(el) {
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.suffix || '';
      const duration = 1500;
      const start = performance.now();

      const step = (now) => {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.floor(eased * target).toLocaleString() + suffix;
        if (progress < 1) requestAnimationFrame(step);
      };

      requestAnimationFrame(step);
    }
  };

  /* ============================================================
     Language Manager (English / Urdu)
     ============================================================ */
  const LanguageManager = {
    STORAGE_KEY: 'nc-lang',
    current: 'en',

    // Full translation dictionary
    dict: {
      en: {
        // Navigation
        'nav-home':       'Home',
        'nav-events':     ' Events',
        'nav-services':   ' Services',
        'nav-issues':     ' Issues',
        'nav-community':  ' Community',
        'nav-login':      'Log In',
        'nav-join':       'Join Free',
        'nav-profile':    'My Profile',
        'nav-logout':     'Log Out',
        // Archive heroes
        'services-title': 'Local Services Directory',
        'services-sub':   'Trusted professionals and businesses from your neighborhood — vetted by your neighbors.',
        // Buttons & labels
        'btn-rsvp':       'RSVP',
        'btn-view-all':   'View All',
        'btn-report':     'Report an Issue',
        'btn-subscribe':  'Subscribe',
        'btn-send':       'Send Message',
        'btn-login':      'Sign In',
        'btn-register':   'Create Account',
        // Auth pages
        'login-title':    'Sign in to your account',
        'login-sub':      "Don't have an account?",
        'register-title': 'Create your free account',
        'register-sub':   'Already have an account?',
        // Footer
        'footer-stay':    'Stay Connected',
        'footer-desc':    'Weekly neighborhood updates and event announcements.',
        'footer-legal':   'No spam. Unsubscribe anytime.',
        // Misc
        'search-placeholder': 'Search plumbing, tutoring, cleaning…',
        'all-filter':     'All',
        'top-rated':      'Top Rated',
        'newest':         'Newest',
        'a-z':            'A–Z',
      },
      ur: {
        // Navigation
        'nav-home':       'ہوم',
        'nav-events':     ' تقریبات',
        'nav-services':   ' خدمات',
        'nav-issues':     ' مسائل',
        'nav-community':  ' کمیونٹی',
        'nav-login':      'لاگ ان',
        'nav-join':       'مفت شامل ہوں',
        'nav-profile':    'میری پروفائل',
        'nav-logout':     'لاگ آؤٹ',
        // Archive heroes
        'services-title': 'مقامی خدمات کی ڈائریکٹری',
        'services-sub':   'آپ کے محلے کے قابل اعتماد پیشہ ور — آپ کے پڑوسیوں کی طرف سے تصدیق شدہ۔',
        // Buttons & labels
        'btn-rsvp':       'حاضری',
        'btn-view-all':   'سب دیکھیں',
        'btn-report':     'مسئلہ رپورٹ کریں',
        'btn-subscribe':  'سبسکرائب کریں',
        'btn-send':       'پیغام بھیجیں',
        'btn-login':      'سائن ان',
        'btn-register':   'اکاؤنٹ بنائیں',
        // Auth pages
        'login-title':    'اپنے اکاؤنٹ میں سائن ان کریں',
        'login-sub':      'اکاؤنٹ نہیں ہے؟',
        'register-title': 'مفت اکاؤنٹ بنائیں',
        'register-sub':   'پہلے سے اکاؤنٹ ہے؟',
        // Footer
        'footer-stay':    'جڑے رہیں',
        'footer-desc':    'ہفتہ وار محلے کی اپڈیٹس اور تقریبات کی اطلاعات۔',
        'footer-legal':   'کوئی سپیم نہیں۔ کبھی بھی ان سبسکرائب کریں۔',
        // Misc
        'search-placeholder': 'پلمبنگ، ٹیوشن، صفائی تلاش کریں…',
        'all-filter':     'سب',
        'top-rated':      'بہترین درجہ بند',
        'newest':         'تازہ ترین',
        'a-z':            'الف–ی',
      }
    },

    init() {
      const saved = localStorage.getItem(this.STORAGE_KEY) || 'en';
      this.apply(saved, false);

      document.getElementById('lang-toggle')?.addEventListener('click', () => {
        this.toggle();
      });
    },

    toggle() {
      const next = this.current === 'en' ? 'ur' : 'en';
      localStorage.setItem(this.STORAGE_KEY, next);
      this.apply(next, true);
    },

    apply(lang, animate) {
      this.current = lang;
      const html = document.documentElement;
      html.setAttribute('data-lang', lang);
      html.setAttribute('lang', lang === 'ur' ? 'ur' : 'en');
      html.setAttribute('dir', lang === 'ur' ? 'rtl' : 'ltr');

      // Update toggle to show only the OTHER language
      const toggleLabel = document.getElementById('lang-label-text');
      if (toggleLabel) toggleLabel.textContent = lang === 'en' ? 'اردو' : 'EN';

      // Load Urdu font on first use
      if (lang === 'ur' && !document.getElementById('nc-urdu-font')) {
        const link = document.createElement('link');
        link.id = 'nc-urdu-font';
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;600;700&display=swap';
        document.head.appendChild(link);
      }

      // Translate all data-i18n elements
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.dataset.i18n;
        const text = this.dict[lang]?.[key];
        if (text !== undefined) el.textContent = text;
      });

      // Translate placeholder attributes
      document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.dataset.i18nPlaceholder;
        const text = this.dict[lang]?.[key];
        if (text !== undefined) el.placeholder = text;
      });

      // Translate archive search input placeholder
      const svcSearch = document.getElementById('service-search-input');
      if (svcSearch) svcSearch.placeholder = lang === 'ur' ? 'خدمات تلاش کریں…' : 'Search plumbing, tutoring, cleaning…';

      const evtSearch = document.getElementById('event-search-input');
      if (evtSearch) evtSearch.placeholder = lang === 'ur' ? 'تقریبات تلاش کریں…' : 'Search events…';

      // Translate sort select options
      document.querySelectorAll('#service-sort option').forEach(opt => {
        const map = { rating: { en: 'Top Rated', ur: 'بہترین درجہ بند' }, newest: { en: 'Newest', ur: 'تازہ ترین' }, alpha: { en: 'A–Z', ur: 'الف–ی' } };
        if (map[opt.value]) opt.textContent = map[opt.value][lang];
      });

      document.querySelectorAll('#event-sort option').forEach(opt => {
        const map = { 'date-asc': { en: 'Soonest First', ur: 'قریب ترین پہلے' }, 'date-desc': { en: 'Latest First', ur: 'تازہ ترین پہلے' }, alpha: { en: 'A–Z', ur: 'الف–ی' } };
        if (map[opt.value]) opt.textContent = map[opt.value][lang];
      });

      document.querySelectorAll('#issue-sort option').forEach(opt => {
        const map = { votes: { en: 'Most Voted', ur: 'سب سے زیادہ ووٹ' }, newest: { en: 'Newest', ur: 'تازہ ترین' }, oldest: { en: 'Oldest', ur: 'پرانے' } };
        if (map[opt.value]) opt.textContent = map[opt.value][lang];
      });

      // Translate filter chip "All" buttons
      document.querySelectorAll('.filter-chip[data-cat="all"], .event-filter-btn[data-cat="all"]').forEach(btn => {
        // Preserve icon if any
        const icon = btn.querySelector('i');
        const text = lang === 'ur' ? ' تمام' : ' All Events';
        if (icon) {
          btn.innerHTML = '';
          btn.appendChild(icon);
          btn.appendChild(document.createTextNode(btn.closest('.event-filter-bar') ? text : (lang === 'ur' ? 'تمام' : 'All')));
        }
      });

      // Translate issue status tabs
      const tabMap = { all: { en: 'All Issues', ur: 'تمام مسائل' }, open: { en: 'Open', ur: 'کھلے' }, in_progress: { en: 'In Progress', ur: 'جاری' }, resolved: { en: 'Resolved', ur: 'حل شدہ' } };
      document.querySelectorAll('.issue-tab').forEach(tab => {
        const status = tab.dataset.status;
        if (tabMap[status]) {
          const dot = tab.querySelector('.status-dot');
          tab.textContent = tabMap[status][lang];
          if (dot) tab.prepend(dot);
        }
      });

      // Translate "All" filter chip in sidebar
      document.querySelectorAll('.filter-chip[data-cat="all"]').forEach(btn => {
        btn.textContent = lang === 'ur' ? 'تمام' : 'All';
      });

      // Toast confirmation
      if (animate) {
        const label = lang === 'ur' ? 'اردو میں تبدیل ہو گیا' : 'Switched to English';
        Toast.show(label, '', 'info', 2000);
      }
    }
  };

  /* ============================================================
     Initialize Everything
     ============================================================ */
  document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
    MobileNav.init();
    BackToTop.init();
    Toast.init();
    RSVP.init();
    IssueVote.init();
    FilterChips.init();
    Modal.init();
    ScrollAnimations.init();
    Newsletter.init();
    SmoothScroll.init();
    SearchAC.init();
    StickyHeader.init();
    CounterAnimation.init();
    LanguageManager.init();
  });

  // Expose Toast globally for PHP-rendered inline scripts
  window.NcToast = Toast;

})();
