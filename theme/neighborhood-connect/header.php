<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#0d9488" media="(prefers-color-scheme: light)">
  <meta name="theme-color" content="#1c1917" media="(prefers-color-scheme: dark)">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='22' fill='%230d9488'/><text y='72' x='50' text-anchor='middle' font-size='58' font-family='Arial'>🏘</text></svg>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e('Skip to content', 'neighborhood-connect'); ?></a>

<!-- Mobile Nav -->
<div class="mobile-nav" id="mobile-nav">
  <div class="mobile-nav-panel">
    <button class="mobile-nav-close" aria-label="<?php esc_attr_e('Close menu', 'neighborhood-connect'); ?>">
      <i class="fa-solid fa-xmark"></i>
    </button>

    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" style="font-size:1rem;">
      <span class="logo-mark"><i class="fa-solid fa-house-chimney"></i></span>
      <span><?php bloginfo('name'); ?></span>
    </a>

    <nav>
      <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>"><i class="fa-solid fa-house" style="width:16px;color:var(--c-primary);"></i> <span data-i18n="nav-home"><?php esc_html_e('Home', 'neighborhood-connect'); ?></span></a></li>
        <li><a href="<?php echo esc_url(home_url('/events/')); ?>"><i class="fa-solid fa-calendar-days" style="width:16px;color:var(--c-primary);"></i> <span data-i18n="nav-events"><?php esc_html_e('Events', 'neighborhood-connect'); ?></span></a></li>
        <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><i class="fa-solid fa-briefcase" style="width:16px;color:var(--c-primary);"></i> <span data-i18n="nav-services"><?php esc_html_e('Services', 'neighborhood-connect'); ?></span></a></li>
        <li><a href="<?php echo esc_url(home_url('/issues/')); ?>"><i class="fa-solid fa-triangle-exclamation" style="width:16px;color:var(--c-primary);"></i> <span data-i18n="nav-issues"><?php esc_html_e('Issues', 'neighborhood-connect'); ?></span></a></li>
        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><i class="fa-solid fa-newspaper" style="width:16px;color:var(--c-primary);"></i> <span data-i18n="nav-community"><?php esc_html_e('Community', 'neighborhood-connect'); ?></span></a></li>
      </ul>
    </nav>

    <div class="mobile-nav-actions">
      <?php if (is_user_logged_in()) : ?>
        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="btn btn-ghost btn-full btn-sm"><span data-i18n="nav-logout"><?php esc_html_e('Log Out', 'neighborhood-connect'); ?></span></a>
      <?php else : ?>
        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-ghost btn-full btn-sm"><span data-i18n="nav-login"><?php esc_html_e('Log In', 'neighborhood-connect'); ?></span></a>
        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-primary btn-full btn-sm"><span data-i18n="nav-join"><?php esc_html_e('Join Free', 'neighborhood-connect'); ?></span></a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Header -->
<header class="site-header" role="banner">
  <div class="container">
    <div class="nav-wrapper">

      <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
        <span class="logo-mark"><i class="fa-solid fa-house-chimney"></i></span>
        <span><?php echo esc_html(get_theme_mod('nc_neighborhood', 'Neighborhood')); ?> <em>Connect</em></span>
      </a>

      <nav class="primary-nav" aria-label="Primary">
        <ul>
          <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php if (is_front_page()) echo 'class="current"'; ?>><i class="fa-solid fa-house nav-icon"></i><span data-i18n="nav-home"> Home</span></a></li>
          <li><a href="<?php echo esc_url(home_url('/events/')); ?>"><i class="fa-solid fa-calendar-days nav-icon"></i><span data-i18n="nav-events"> Events</span></a></li>
          <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><i class="fa-solid fa-briefcase nav-icon"></i><span data-i18n="nav-services"> Services</span></a></li>
          <li><a href="<?php echo esc_url(home_url('/issues/')); ?>"><i class="fa-solid fa-triangle-exclamation nav-icon"></i><span data-i18n="nav-issues"> Issues</span></a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><i class="fa-solid fa-newspaper nav-icon"></i><span data-i18n="nav-community"> Community</span></a></li>
        </ul>
      </nav>

      <div class="nav-actions">
        <button class="icon-btn theme-toggle" aria-label="Toggle theme">
          <i class="fa-solid fa-moon"></i>
        </button>

        <button class="lang-toggle" id="lang-toggle" aria-label="Switch language">
          <span id="lang-label-text">اردو</span>
        </button>

        <?php if (!is_user_logged_in()) : ?>
          <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-ghost btn-sm" style="display:none;" id="nav-login"><span data-i18n="nav-login"><?php esc_html_e('Log in', 'neighborhood-connect'); ?></span></a>
          <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-primary btn-sm" id="nav-join"><span data-i18n="nav-join"><?php esc_html_e('Join Free', 'neighborhood-connect'); ?></span></a>
        <?php else : ?>
          <a href="<?php echo esc_url(get_edit_user_link()); ?>" class="btn btn-ghost btn-sm"><span data-i18n="nav-profile"><?php esc_html_e('My Profile', 'neighborhood-connect'); ?></span></a>
        <?php endif; ?>

        <button class="menu-toggle" aria-label="Open menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>
</header>

<!-- Search modal -->
<div class="modal-overlay" id="search-modal" role="dialog" aria-modal="true" aria-label="Search">
  <div class="modal" style="max-width:580px;">
    <div class="modal-header">
      <span class="modal-title"><?php esc_html_e('Search', 'neighborhood-connect'); ?></span>
      <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <?php get_search_form(); ?>
    </div>
  </div>
</div>

<main id="main-content" role="main">
