</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
          <span class="logo-icon"><i class="fa-solid fa-house-chimney"></i></span>
          <span><?php bloginfo('name'); ?></span>
        </a>
        <p class="footer-tagline">
          <?php echo esc_html(get_theme_mod('nc_footer_tagline', 'Building stronger, more connected communities one neighborhood at a time.')); ?>
        </p>
        <div class="social-links">
          <a href="#" class="social-link" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#" class="social-link" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="social-link" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-link" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
      </div>

      <!-- Platform Links -->
      <nav class="footer-nav" aria-label="<?php esc_attr_e('Platform links', 'neighborhood-connect'); ?>">
        <h4><?php esc_html_e('Platform', 'neighborhood-connect'); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/events/')); ?>"><?php esc_html_e('Events', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php esc_html_e('Services', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/issues/')); ?>"><?php esc_html_e('Report Issue', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/marketplace/')); ?>"><?php esc_html_e('Marketplace', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/forum/')); ?>"><?php esc_html_e('Forum', 'neighborhood-connect'); ?></a></li>
        </ul>
      </nav>

      <!-- Company Links -->
      <nav class="footer-nav" aria-label="<?php esc_attr_e('Company links', 'neighborhood-connect'); ?>">
        <h4><?php esc_html_e('Company', 'neighborhood-connect'); ?></h4>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/about/')); ?>"><?php esc_html_e('About', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/careers/')); ?>"><?php esc_html_e('Careers', 'neighborhood-connect'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/press/')); ?>"><?php esc_html_e('Press', 'neighborhood-connect'); ?></a></li>
        </ul>
      </nav>

      <!-- Newsletter -->
      <div class="footer-newsletter">
        <h4><?php esc_html_e('Stay Connected', 'neighborhood-connect'); ?></h4>
        <p><?php esc_html_e('Get weekly neighborhood updates, event announcements, and community news.', 'neighborhood-connect'); ?></p>
        <form class="newsletter-form" novalidate>
          <input
            type="email"
            class="newsletter-input"
            placeholder="<?php esc_attr_e('your@email.com', 'neighborhood-connect'); ?>"
            required
            aria-label="<?php esc_attr_e('Email address for newsletter', 'neighborhood-connect'); ?>"
          >
          <button type="submit" class="btn btn-primary btn-sm">
            <?php esc_html_e('Subscribe', 'neighborhood-connect'); ?>
          </button>
        </form>
        <p style="font-size:.7rem;color:#6b7280;margin-top:.75rem;">
          <?php esc_html_e('No spam. Unsubscribe anytime.', 'neighborhood-connect'); ?>
        </p>
      </div>

    </div><!-- .footer-grid -->

    <div class="footer-bottom">
      <p>
        &copy; <?php echo esc_html(date('Y')); ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" style="color:var(--color-gray-400);"><?php bloginfo('name'); ?></a>.
        <?php esc_html_e('All rights reserved.', 'neighborhood-connect'); ?>
      </p>
      <div class="footer-bottom-links">
        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy', 'neighborhood-connect'); ?></a>
        <a href="<?php echo esc_url(home_url('/terms/')); ?>"><?php esc_html_e('Terms', 'neighborhood-connect'); ?></a>
        <a href="<?php echo esc_url(home_url('/cookies/')); ?>"><?php esc_html_e('Cookies', 'neighborhood-connect'); ?></a>
        <a href="<?php echo esc_url(rest_url('nc/v1')); ?>"><?php esc_html_e('API', 'neighborhood-connect'); ?></a>
      </div>
    </div>

  </div><!-- .container -->
</footer>

<!-- Back to Top -->
<button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'neighborhood-connect'); ?>">
  <i class="fa-solid fa-chevron-up"></i>
</button>

<!-- Toast Container -->
<div class="toast-container" aria-live="polite" aria-atomic="false"></div>

<?php wp_footer(); ?>
</body>
</html>
