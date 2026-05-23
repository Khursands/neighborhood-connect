</main>

<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">

      <div>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
          <span class="logo-mark"><i class="fa-solid fa-house-chimney"></i></span>
          <span><?php bloginfo('name'); ?></span>
        </a>
        <p class="footer-tagline">
          <?php echo esc_html(get_theme_mod('nc_footer_tagline', 'Built for the residents of Canal View Cooperative Housing Society, Lahore.')); ?>
        </p>
        <div class="social-links">
          <a href="#" class="social-link" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#" class="social-link" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-link" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Platform</h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/events/')); ?>">Events</a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>">Services</a></li>
          <li><a href="<?php echo esc_url(home_url('/issues/')); ?>">Report Issue</a></li>
          <li><a href="<?php echo esc_url(home_url('/amenities/')); ?>">Neighborhood</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/amenities/')); ?>">About the society</a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
          <li><a href="<?php echo esc_url(rest_url('nc/v1')); ?>">API</a></li>
        </ul>
      </div>

      <div>
        <h4 style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:white;margin-bottom:var(--s-4);" data-i18n="footer-stay">Stay Connected</h4>
        <p style="font-size:var(--text-sm);margin-bottom:var(--s-4);" data-i18n="footer-desc">Weekly neighborhood updates and event announcements.</p>
        <form class="newsletter-form" novalidate>
          <input type="email" class="newsletter-input" placeholder="your@email.com" required aria-label="Email for newsletter">
          <button type="submit" class="btn btn-primary btn-sm" data-i18n="btn-subscribe">Subscribe</button>
        </form>
        <p style="font-size:11px;color:var(--c-sand-600);margin-top:var(--s-3);" data-i18n="footer-legal">No spam. Unsubscribe anytime.</p>
      </div>

    </div>

    <div class="footer-bottom">
      <p>&copy; <?php echo esc_html(date('Y')); ?> <a href="<?php echo esc_url(home_url('/')); ?>" style="color:var(--c-sand-500);"><?php bloginfo('name'); ?></a>. All rights reserved.</p>
      <div class="footer-bottom-links">
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy</a>
        <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a>
        <a href="<?php echo esc_url(home_url('/cookies/')); ?>">Cookies</a>
      </div>
    </div>

  </div>
</footer>

<button class="back-to-top" aria-label="Back to top"><i class="fa-solid fa-chevron-up"></i></button>
<div class="toast-container" aria-live="polite"></div>

<?php wp_footer(); ?>
</body>
</html>
