<?php get_header(); ?>

<div class="section" style="min-height:60vh;display:flex;align-items:center;">
  <div class="container" style="text-align:center;">
    <div style="font-size:6rem;font-weight:800;color:var(--color-primary);line-height:1;margin-bottom:1rem;">404</div>
    <h1 style="font-size:1.75rem;margin-bottom:1rem;"><?php esc_html_e('Page Not Found', 'neighborhood-connect'); ?></h1>
    <p style="color:var(--color-text-muted);max-width:400px;margin:0 auto 2rem;">
      <?php esc_html_e('The page you\'re looking for doesn\'t exist or has been moved. Let\'s get you back on track.', 'neighborhood-connect'); ?>
    </p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-lg">
        <i class="fa-solid fa-house"></i>
        <?php esc_html_e('Go Home', 'neighborhood-connect'); ?>
      </a>
      <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-outline btn-lg">
        <i class="fa-solid fa-calendar-days"></i>
        <?php esc_html_e('Browse Events', 'neighborhood-connect'); ?>
      </a>
    </div>
    <div style="margin-top:3rem;">
      <?php get_search_form(); ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
