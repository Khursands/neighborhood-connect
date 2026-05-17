<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#2563eb">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content', 'neighborhood-connect'); ?></a>

<!-- Mobile Nav Overlay -->
<nav class="mobile-nav" aria-label="<?php esc_attr_e('Mobile navigation', 'neighborhood-connect'); ?>">
  <div class="mobile-nav-panel">
    <button class="mobile-nav-close" aria-label="<?php esc_attr_e('Close menu', 'neighborhood-connect'); ?>">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <div style="margin-bottom:1.5rem;">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" style="font-size:1.125rem;">
        <span class="logo-icon"><i class="fa-solid fa-house-chimney"></i></span>
        <span><?php bloginfo('name'); ?></span>
      </a>
    </div>

    <?php
    wp_nav_menu([
      'theme_location' => 'primary',
      'menu_class'     => '',
      'container'      => false,
      'fallback_cb'    => 'nc_mobile_nav_fallback',
    ]);
    ?>

    <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--color-border);display:flex;flex-direction:column;gap:.75rem;">
      <?php if (is_user_logged_in()) : ?>
        <a href="<?php echo esc_url(get_edit_user_link()); ?>" class="btn btn-outline btn-full">
          <i class="fa-solid fa-user"></i> <?php esc_html_e('My Profile', 'neighborhood-connect'); ?>
        </a>
        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="btn btn-ghost btn-full">
          <?php esc_html_e('Log Out', 'neighborhood-connect'); ?>
        </a>
      <?php else : ?>
        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-outline btn-full">
          <i class="fa-solid fa-right-to-bracket"></i> <?php esc_html_e('Log In', 'neighborhood-connect'); ?>
        </a>
        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-primary btn-full">
          <?php esc_html_e('Join Now', 'neighborhood-connect'); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Site Header -->
<header class="site-header" role="banner">
  <div class="container">
    <div class="nav-wrapper">

      <!-- Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home" aria-label="<?php bloginfo('name'); ?> – <?php esc_attr_e('Go to homepage', 'neighborhood-connect'); ?>">
        <?php if (has_custom_logo()) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <span class="logo-icon"><i class="fa-solid fa-house-chimney"></i></span>
          <span><?php bloginfo('name'); ?></span>
        <?php endif; ?>
      </a>

      <!-- Desktop Nav -->
      <nav class="primary-nav" aria-label="<?php esc_attr_e('Primary navigation', 'neighborhood-connect'); ?>">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container'      => false,
          'fallback_cb'    => 'nc_primary_nav_fallback',
        ]);
        ?>
      </nav>

      <!-- Nav Actions -->
      <div class="nav-actions">
        <!-- Search -->
        <button class="theme-toggle" data-modal="search-modal" aria-label="<?php esc_attr_e('Open search', 'neighborhood-connect'); ?>">
          <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        <!-- Theme Toggle -->
        <button class="theme-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'neighborhood-connect'); ?>">
          <i class="fa-solid fa-moon"></i>
        </button>

        <?php if (is_user_logged_in()) : ?>
          <a href="<?php echo esc_url(get_edit_user_link()); ?>" class="btn btn-outline btn-sm" style="display:none;">
            <i class="fa-solid fa-user"></i>
          </a>
        <?php else : ?>
          <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-ghost btn-sm" style="display:none;">
            <?php esc_html_e('Log In', 'neighborhood-connect'); ?>
          </a>
          <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-primary btn-sm" style="display:none;">
            <?php esc_html_e('Join Now', 'neighborhood-connect'); ?>
          </a>
        <?php endif; ?>

        <!-- Mobile Toggle -->
        <button class="menu-toggle" aria-label="<?php esc_attr_e('Open menu', 'neighborhood-connect'); ?>" aria-expanded="false" aria-controls="mobile-nav">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>
</header>

<!-- Search Modal -->
<div class="modal-overlay" id="search-modal" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Search', 'neighborhood-connect'); ?>">
  <div class="modal" style="max-width:600px;">
    <div class="modal-header">
      <h2 class="modal-title"><?php esc_html_e('Search Neighborhood Connect', 'neighborhood-connect'); ?></h2>
      <button class="modal-close" aria-label="<?php esc_attr_e('Close search', 'neighborhood-connect'); ?>"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <?php get_search_form(); ?>
      <p style="font-size:.75rem;color:var(--color-text-muted);margin-top:.75rem;">
        <?php esc_html_e('Try searching for events, services, or issues in your neighborhood.', 'neighborhood-connect'); ?>
      </p>
    </div>
  </div>
</div>

<main id="main-content" role="main">

<?php
function nc_primary_nav_fallback() {
    echo '<ul>
      <li><a href="' . esc_url(home_url('/')) . '"><i class="fa-solid fa-house"></i> Home</a></li>
      <li><a href="' . esc_url(home_url('/events/')) . '"><i class="fa-solid fa-calendar-days"></i> Events</a></li>
      <li><a href="' . esc_url(home_url('/services/')) . '"><i class="fa-solid fa-briefcase"></i> Services</a></li>
      <li><a href="' . esc_url(home_url('/issues/')) . '"><i class="fa-solid fa-triangle-exclamation"></i> Issues</a></li>
      <li><a href="' . esc_url(home_url('/blog/')) . '"><i class="fa-solid fa-newspaper"></i> Community</a></li>
    </ul>';
}

function nc_mobile_nav_fallback() {
    nc_primary_nav_fallback();
}
