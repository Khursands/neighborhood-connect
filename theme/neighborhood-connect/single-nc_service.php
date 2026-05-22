<?php get_header(); ?>

<?php
the_post();
$sid      = get_the_ID();
$category = get_post_meta($sid, '_nc_service_category', true);
$phone    = get_post_meta($sid, '_nc_phone', true);
$email    = get_post_meta($sid, '_nc_email', true);
$website  = get_post_meta($sid, '_nc_website', true);
$rating   = (float) get_post_meta($sid, '_nc_rating', true);
$reviews  = (int) get_post_meta($sid, '_nc_review_count', true);
$price    = get_post_meta($sid, '_nc_price', true);
$badge    = get_post_meta($sid, '_nc_badge', true);
$hours    = get_post_meta($sid, '_nc_hours', true);
$address  = get_post_meta($sid, '_nc_address', true);

// Icon & color map
$icons = [
  'Home Services' => 'fa-house-chimney-window', 'Education' => 'fa-graduation-cap',
  'Pet Care'      => 'fa-paw', 'Cleaning' => 'fa-broom', 'Electrical' => 'fa-bolt',
  'Beauty'        => 'fa-scissors', 'Carpentry' => 'fa-hammer', 'Gardening' => 'fa-seedling',
  'Plumbing'      => 'fa-wrench', 'Tech Support' => 'fa-laptop-code',
];
$colors = [
  'Home Services' => '#4f46e5', 'Education' => '#7c3aed', 'Pet Care' => '#059669',
  'Cleaning'      => '#0891b2', 'Electrical'=> '#d97706', 'Beauty'   => '#db2777',
  'Carpentry'     => '#92400e', 'Gardening' => '#16a34a', 'Plumbing' => '#1d4ed8',
  'Tech Support'  => '#475569',
];
$icon  = $icons[$category] ?? 'fa-briefcase';
$color = $colors[$category] ?? '#4f46e5';
?>

<!-- Service Hero -->
<div class="single-hero service-single-hero" style="background:linear-gradient(135deg,<?php echo esc_attr($color); ?>15 0%,var(--c-bg) 100%);border-bottom:1px solid var(--c-border);">
  <div class="container" style="padding-top:var(--s-10);padding-bottom:var(--s-10);">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
      <span>/</span>
      <a href="<?php echo esc_url(home_url('/services/')); ?>">Services</a>
      <span>/</span>
      <span><?php the_title(); ?></span>
    </div>

    <div class="single-hero-grid">
      <div class="single-hero-content">
        <div style="display:flex;align-items:center;gap:var(--s-3);margin-bottom:var(--s-4);">
          <div class="service-icon-wrap-lg" style="background:<?php echo esc_attr($color); ?>1a;border:2px solid <?php echo esc_attr($color); ?>33;width:72px;height:72px;border-radius:var(--radius-xl);display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid <?php echo esc_attr($icon); ?>" style="color:<?php echo esc_attr($color); ?>;font-size:2rem;"></i>
          </div>
          <div>
            <span class="service-category-pill" style="background:<?php echo esc_attr($color); ?>1a;color:<?php echo esc_attr($color); ?>;margin-bottom:var(--s-1);display:inline-block;"><?php echo esc_html($category ?: 'General'); ?></span>
            <?php if ($badge) : ?>
              <span class="service-badge" style="margin-left:var(--s-2);"><?php echo esc_html($badge); ?></span>
            <?php endif; ?>
          </div>
        </div>

        <h1 class="single-title"><?php the_title(); ?></h1>

        <?php if ($rating > 0) : ?>
        <div class="service-rating-row" style="margin-bottom:var(--s-4);">
          <span class="stars" style="color:var(--c-gold);font-size:1.1rem;"><?php echo nc_star_rating($rating); ?></span>
          <strong style="font-size:1.1rem;"><?php echo number_format($rating, 1); ?></strong>
          <?php if ($reviews) : ?>
          <span style="color:var(--c-text-muted);">(<?php echo esc_html($reviews); ?> reviews)</span>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <p class="single-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
      </div>

      <!-- Contact Card -->
      <div class="service-contact-card">
        <h3 style="font-size:1rem;font-weight:var(--fw-semibold);margin-bottom:var(--s-4);">Get in Touch</h3>

        <?php if ($price) : ?>
        <div class="contact-info-row" style="margin-bottom:var(--s-4);padding:var(--s-3);background:<?php echo esc_attr($color); ?>0d;border-radius:var(--radius-lg);">
          <i class="fa-solid fa-tag" style="color:<?php echo esc_attr($color); ?>;"></i>
          <div>
            <small style="color:var(--c-text-muted);">Starting price</small>
            <strong style="font-size:1.1rem;color:<?php echo esc_attr($color); ?>;"><?php echo esc_html($price); ?></strong>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($phone) : ?>
        <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $phone)); ?>" class="contact-btn" style="--btn-color:<?php echo esc_attr($color); ?>;">
          <i class="fa-solid fa-phone"></i>
          <div>
            <small>Call Now</small>
            <strong><?php echo esc_html($phone); ?></strong>
          </div>
        </a>
        <?php endif; ?>

        <?php if ($email) : ?>
        <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-btn" style="--btn-color:<?php echo esc_attr($color); ?>;">
          <i class="fa-solid fa-envelope"></i>
          <div>
            <small>Email</small>
            <strong><?php echo esc_html($email); ?></strong>
          </div>
        </a>
        <?php endif; ?>

        <?php if ($website) : ?>
        <a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener" class="contact-btn" style="--btn-color:<?php echo esc_attr($color); ?>;">
          <i class="fa-solid fa-globe"></i>
          <div>
            <small>Website</small>
            <strong><?php echo esc_html(preg_replace('#https?://#', '', rtrim($website, '/'))); ?></strong>
          </div>
        </a>
        <?php endif; ?>

        <?php if ($hours) : ?>
        <div class="contact-info-row">
          <i class="fa-solid fa-clock" style="color:var(--c-text-muted);"></i>
          <div>
            <small style="color:var(--c-text-muted);">Hours</small>
            <span style="font-size:.9rem;"><?php echo esc_html($hours); ?></span>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($address) : ?>
        <div class="contact-info-row">
          <i class="fa-solid fa-location-dot" style="color:var(--c-text-muted);"></i>
          <div>
            <small style="color:var(--c-text-muted);">Address</small>
            <span style="font-size:.9rem;"><?php echo esc_html($address); ?></span>
          </div>
        </div>
        <?php endif; ?>

        <button class="btn btn-primary btn-full" style="margin-top:var(--s-4);background:<?php echo esc_attr($color); ?>;border-color:<?php echo esc_attr($color); ?>;" onclick="document.getElementById('contact-form-section').scrollIntoView({behavior:'smooth'})">
          <i class="fa-solid fa-paper-plane"></i> Send a Message
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Service Body -->
<div class="container" style="padding-top:var(--s-10);padding-bottom:var(--s-16);">
  <div class="single-body-grid">
    <div class="single-body-main">

      <?php if (has_post_thumbnail()) : ?>
      <figure>
        <?php the_post_thumbnail('nc-hero', ['style' => 'border-radius:var(--radius-xl);width:100%;height:300px;object-fit:cover;margin-bottom:var(--s-6);display:block;']); ?>
      </figure>
      <?php endif; ?>

      <div class="post-content">
        <?php the_content(); ?>
      </div>

      <!-- Contact Form -->
      <div id="contact-form-section" class="sidebar-card" style="margin-top:var(--s-8);">
        <h3 style="font-size:1.1rem;font-weight:var(--fw-semibold);margin-bottom:var(--s-5);">
          <i class="fa-solid fa-envelope" style="color:<?php echo esc_attr($color); ?>;"></i> Send a Message
        </h3>

        <?php if (is_user_logged_in()) : ?>
        <form id="service-contact-form">
          <?php wp_nonce_field('nc_nonce', 'nc_contact_nonce'); ?>
          <input type="hidden" name="service_id" value="<?php echo esc_attr($sid); ?>">
          <div class="form-row-2col">
            <div class="form-group">
              <label class="form-label">Your Name</label>
              <input type="text" name="contact_name" class="form-input" value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Your Email</label>
              <input type="email" name="contact_email" class="form-input" value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Message</label>
            <textarea name="contact_message" class="form-input" rows="5" placeholder="Describe what you need…" required style="resize:vertical;"></textarea>
          </div>
          <div id="contact-form-msg" class="auth-alert" style="display:none;"></div>
          <button type="submit" class="btn btn-primary" style="background:<?php echo esc_attr($color); ?>;border-color:<?php echo esc_attr($color); ?>;">
            <i class="fa-solid fa-paper-plane"></i> Send Message
          </button>
        </form>
        <?php else : ?>
        <div style="text-align:center;padding:var(--s-6) 0;">
          <i class="fa-solid fa-lock" style="font-size:2rem;color:var(--c-text-muted);margin-bottom:var(--s-3);display:block;"></i>
          <p style="color:var(--c-text-muted);margin-bottom:var(--s-4);">Log in to send a message to this service provider.</p>
          <a href="<?php echo esc_url(home_url('/login/')); ?>" class="btn btn-primary" style="background:<?php echo esc_attr($color); ?>;border-color:<?php echo esc_attr($color); ?>;">Log In</a>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Sidebar -->
    <aside class="single-sidebar">
      <div class="sidebar-card">
        <h4 class="sidebar-title">Quick Facts</h4>
        <div class="sidebar-facts">
          <?php if ($category) : ?>
          <div class="sidebar-fact"><i class="fa-solid fa-tag"></i> <span><?php echo esc_html($category); ?></span></div>
          <?php endif; ?>
          <?php if ($price) : ?>
          <div class="sidebar-fact"><i class="fa-solid fa-dollar-sign"></i> <span><?php echo esc_html($price); ?></span></div>
          <?php endif; ?>
          <?php if ($rating > 0) : ?>
          <div class="sidebar-fact"><i class="fa-solid fa-star" style="color:var(--c-gold);"></i> <span><?php echo number_format($rating, 1); ?>/5 (<?php echo $reviews; ?> reviews)</span></div>
          <?php endif; ?>
          <div class="sidebar-fact"><i class="fa-regular fa-calendar-check"></i> <span>Listed <?php echo esc_html(get_the_date('M Y')); ?></span></div>
        </div>
      </div>

      <div class="sidebar-card">
        <h4 class="sidebar-title">More Services</h4>
        <?php
        $related = new WP_Query([
          'post_type'      => 'nc_service',
          'posts_per_page' => 4,
          'post__not_in'   => [$sid],
          'post_status'    => 'publish',
          'orderby'        => 'rand',
        ]);
        while ($related->have_posts()) : $related->the_post();
          $r_cat = get_post_meta(get_the_ID(), '_nc_service_category', true);
          $r_rating = (float) get_post_meta(get_the_ID(), '_nc_rating', true);
        ?>
        <div style="padding:var(--s-3) 0;border-bottom:1px solid var(--c-border);">
          <a href="<?php the_permalink(); ?>" style="font-weight:500;font-size:.9rem;color:var(--c-text);text-decoration:none;"><?php the_title(); ?></a>
          <p style="font-size:.8rem;color:var(--c-text-muted);margin:var(--s-1) 0 0;">
            <?php echo esc_html($r_cat); ?>
            <?php if ($r_rating > 0) : ?>
              · <i class="fa-solid fa-star" style="color:var(--c-gold);font-size:.7rem;"></i> <?php echo number_format($r_rating, 1); ?>
            <?php endif; ?>
          </p>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn btn-ghost btn-sm btn-full" style="margin-top:var(--s-3);">All Services</a>
      </div>
    </aside>
  </div>
</div>

<script>
document.getElementById('service-contact-form')?.addEventListener('submit', async function (e) {
  e.preventDefault();
  const msgEl = document.getElementById('contact-form-msg');
  const data = new FormData(this);
  data.append('action', 'nc_ajax_contact_service');
  data.append('nonce', ncData.nonce);
  const btn = this.querySelector('[type="submit"]');
  btn.disabled = true;
  btn.textContent = 'Sending…';
  try {
    const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: data });
    const json = await res.json();
    msgEl.className = 'auth-alert ' + (json.success ? 'auth-alert-success' : 'auth-alert-error');
    msgEl.textContent = json.success ? 'Message sent! They will be in touch soon.' : (json.data?.message || 'Failed to send.');
    msgEl.style.display = '';
    if (json.success) this.reset();
  } catch {
    msgEl.className = 'auth-alert auth-alert-error';
    msgEl.textContent = 'Network error. Please try again.';
    msgEl.style.display = '';
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Send Message';
});
</script>

<?php get_footer(); ?>
