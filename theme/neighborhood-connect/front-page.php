<?php get_header(); ?>

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="hero" aria-labelledby="hero-heading">
  <div class="container">
    <div class="hero-content">

      <div class="hero-text">
        <div class="hero-badge">
          <i class="fa-solid fa-circle"></i>
          <?php echo esc_html(get_theme_mod('nc_neighborhood', 'Downtown Community')); ?>
        </div>

        <h1 class="hero-title" id="hero-heading">
          <?php
          $title = get_theme_mod('nc_hero_title', 'Your Neighborhood, Connected');
          $parts = explode(',', $title, 2);
          echo esc_html($parts[0]);
          if (!empty($parts[1])) {
              echo ',<br><span class="highlight">' . esc_html(ltrim($parts[1])) . '</span>';
          }
          ?>
        </h1>

        <p class="hero-description">
          <?php echo esc_html(get_theme_mod('nc_hero_description', 'Discover events, find local services, report issues, and connect with your neighbors in one place.')); ?>
        </p>

        <div class="hero-actions">
          <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-calendar-days"></i>
            <?php esc_html_e('Explore Events', 'neighborhood-connect'); ?>
          </a>
          <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-outline btn-lg">
            <?php esc_html_e('Join the Community', 'neighborhood-connect'); ?>
          </a>
        </div>

        <div class="hero-stats">
          <?php
          $event_count   = wp_count_posts('nc_event')->publish;
          $service_count = wp_count_posts('nc_service')->publish;
          $member_count  = count_users()['total_users'];
          ?>
          <div class="stat-item">
            <span class="stat-number" data-count="<?php echo esc_attr($event_count); ?>" data-suffix=""><?php echo esc_html($event_count); ?></span>
            <span class="stat-label"><?php esc_html_e('Active Events', 'neighborhood-connect'); ?></span>
          </div>
          <div class="stat-item">
            <span class="stat-number" data-count="<?php echo esc_attr($service_count); ?>" data-suffix=""><?php echo esc_html($service_count); ?></span>
            <span class="stat-label"><?php esc_html_e('Local Services', 'neighborhood-connect'); ?></span>
          </div>
          <div class="stat-item">
            <span class="stat-number" data-count="<?php echo esc_attr($member_count); ?>" data-suffix=""><?php echo esc_html($member_count); ?></span>
            <span class="stat-label"><?php esc_html_e('Residents', 'neighborhood-connect'); ?></span>
          </div>
        </div>
      </div>

      <!-- Hero Visual Card -->
      <div class="hero-visual" aria-hidden="true">
        <div class="hero-card">
          <div class="hero-card-header">
            <div class="hero-card-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
              <div class="hero-card-title"><?php esc_html_e('Upcoming Events', 'neighborhood-connect'); ?></div>
              <div class="hero-card-subtitle"><?php esc_html_e('This week in your area', 'neighborhood-connect'); ?></div>
            </div>
          </div>

          <?php
          $hero_events = new WP_Query([
            'post_type'      => 'nc_event',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_key'       => '_nc_event_date',
          ]);
          ?>

          <div class="hero-event-list">
            <?php if ($hero_events->have_posts()) : ?>
              <?php while ($hero_events->have_posts()) : $hero_events->the_post();
                $meta = nc_get_event_meta(get_the_ID());
                $ts   = $meta['date'] ? strtotime($meta['date']) : time();
                $rsvp_count = count($meta['rsvps']);
              ?>
              <div class="hero-event-item">
                <div class="event-date-badge">
                  <span class="event-date-day"><?php echo esc_html(date('d', $ts)); ?></span>
                  <span class="event-date-mon"><?php echo esc_html(date('M', $ts)); ?></span>
                </div>
                <div class="event-info">
                  <div class="event-info-title"><?php the_title(); ?></div>
                  <div class="event-info-meta"><?php echo esc_html($meta['location'] ?: __('Community Center', 'neighborhood-connect')); ?></div>
                </div>
                <div class="event-attendees">
                  <?php for ($i = 0; $i < min(3, $rsvp_count); $i++) : ?>
                    <div class="attendee-avatar" style="background:<?php echo esc_attr(nc_avatar_color($i)); ?>;"><?php echo esc_html(chr(65 + $i)); ?></div>
                  <?php endfor; ?>
                </div>
              </div>
              <?php endwhile; wp_reset_postdata(); ?>

            <?php else : ?>
              <div class="hero-event-item">
                <div class="event-date-badge">
                  <span class="event-date-day">24</span>
                  <span class="event-date-mon">May</span>
                </div>
                <div class="event-info">
                  <div class="event-info-title">Block Party Planning</div>
                  <div class="event-info-meta">Park Avenue</div>
                </div>
                <div class="event-attendees">
                  <div class="attendee-avatar" style="background:#2563eb;">A</div>
                  <div class="attendee-avatar" style="background:#059669;">B</div>
                  <div class="attendee-avatar" style="background:#d97706;">C</div>
                </div>
              </div>
              <div class="hero-event-item">
                <div class="event-date-badge">
                  <span class="event-date-day">27</span>
                  <span class="event-date-mon">May</span>
                </div>
                <div class="event-info">
                  <div class="event-info-title">Farmers Market</div>
                  <div class="event-info-meta">Town Square</div>
                </div>
                <div class="event-attendees">
                  <div class="attendee-avatar" style="background:#7c3aed;">D</div>
                  <div class="attendee-avatar" style="background:#db2777;">E</div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ============================================================
     SEARCH & FILTER
     ============================================================ -->
<section class="search-section" aria-labelledby="search-heading">
  <div class="container">
    <div class="search-wrapper">
      <h2 class="search-title" id="search-heading" style="font-size:1.125rem;">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--color-primary);margin-right:.5rem;"></i>
        <?php esc_html_e('Find Something in Your Neighborhood', 'neighborhood-connect'); ?>
      </h2>
      <div class="search-bar">
        <div class="search-input-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input
            type="search"
            class="search-input"
            placeholder="<?php esc_attr_e('Search events, services, issues...', 'neighborhood-connect'); ?>"
            data-autocomplete="true"
            aria-label="<?php esc_attr_e('Search', 'neighborhood-connect'); ?>"
          >
        </div>
        <select class="search-select" aria-label="<?php esc_attr_e('Filter by type', 'neighborhood-connect'); ?>">
          <option value=""><?php esc_html_e('All types', 'neighborhood-connect'); ?></option>
          <option value="nc_event"><?php esc_html_e('Events', 'neighborhood-connect'); ?></option>
          <option value="nc_service"><?php esc_html_e('Services', 'neighborhood-connect'); ?></option>
          <option value="nc_issue"><?php esc_html_e('Issues', 'neighborhood-connect'); ?></option>
        </select>
        <button type="button" class="btn btn-primary">
          <i class="fa-solid fa-magnifying-glass"></i>
          <?php esc_html_e('Search', 'neighborhood-connect'); ?>
        </button>
      </div>

      <div class="filter-chips" data-target="#events-grid">
        <button class="chip active" data-filter="all"><?php esc_html_e('All', 'neighborhood-connect'); ?></button>
        <button class="chip" data-filter="social"><i class="fa-solid fa-users"></i> <?php esc_html_e('Social', 'neighborhood-connect'); ?></button>
        <button class="chip" data-filter="sports"><i class="fa-solid fa-futbol"></i> <?php esc_html_e('Sports', 'neighborhood-connect'); ?></button>
        <button class="chip" data-filter="education"><i class="fa-solid fa-graduation-cap"></i> <?php esc_html_e('Education', 'neighborhood-connect'); ?></button>
        <button class="chip" data-filter="arts"><i class="fa-solid fa-palette"></i> <?php esc_html_e('Arts', 'neighborhood-connect'); ?></button>
        <button class="chip" data-filter="food"><i class="fa-solid fa-utensils"></i> <?php esc_html_e('Food', 'neighborhood-connect'); ?></button>
        <button class="chip" data-filter="health"><i class="fa-solid fa-heart-pulse"></i> <?php esc_html_e('Health', 'neighborhood-connect'); ?></button>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     UPCOMING EVENTS
     ============================================================ -->
<section class="section" id="events-section" aria-labelledby="events-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-eyebrow"><i class="fa-solid fa-calendar-days"></i> <?php esc_html_e('What\'s On', 'neighborhood-connect'); ?></div>
      <h2 class="section-title" id="events-heading"><?php esc_html_e('Upcoming Events', 'neighborhood-connect'); ?></h2>
      <p class="section-description"><?php esc_html_e('Discover and join events happening right in your neighborhood.', 'neighborhood-connect'); ?></p>
    </div>

    <?php
    $events = new WP_Query([
      'post_type'      => 'nc_event',
      'posts_per_page' => 6,
      'post_status'    => 'publish',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
      'meta_key'       => '_nc_event_date',
    ]);
    ?>

    <div class="card-grid" id="events-grid">
      <?php if ($events->have_posts()) : ?>
        <?php while ($events->have_posts()) : $events->the_post(); ?>
          <?php get_template_part('template-parts/content', 'event'); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <?php nc_render_demo_events(); ?>
      <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:2.5rem;">
      <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-outline btn-lg">
        <?php esc_html_e('View All Events', 'neighborhood-connect'); ?>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ============================================================
     FEATURES
     ============================================================ -->
<section class="section section-alt" aria-labelledby="features-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-eyebrow"><?php esc_html_e('Why Neighborhood Connect', 'neighborhood-connect'); ?></div>
      <h2 class="section-title" id="features-heading"><?php esc_html_e('Everything Your Community Needs', 'neighborhood-connect'); ?></h2>
      <p class="section-description"><?php esc_html_e('One platform built for the way neighborhoods actually work.', 'neighborhood-connect'); ?></p>
    </div>

    <div class="features-grid">
      <?php
      $features = [
        ['icon' => 'fa-calendar-days',      'color' => 'icon-blue',   'title' => 'Community Events',   'desc' => 'Create and discover local events with RSVP tracking, calendar sync, and automated reminders.'],
        ['icon' => 'fa-briefcase',           'color' => 'icon-green',  'title' => 'Service Directory',  'desc' => 'Find trusted local plumbers, tutors, babysitters, and more — all verified by your neighbors.'],
        ['icon' => 'fa-triangle-exclamation','color' => 'icon-yellow', 'title' => 'Issue Tracker',      'desc' => 'Report potholes, broken streetlights, and safety concerns. Track them until they\'re resolved.'],
        ['icon' => 'fa-store',               'color' => 'icon-purple', 'title' => 'Marketplace',        'desc' => 'Buy, sell, or give away goods within your community. Reduce waste, support neighbors.'],
        ['icon' => 'fa-comments',            'color' => 'icon-pink',   'title' => 'Community Forum',    'desc' => 'Structured discussions by topic — announcements, safety, recommendations, and more.'],
        ['icon' => 'fa-map-location-dot',    'color' => 'icon-red',    'title' => 'Neighborhood Map',   'desc' => 'Interactive map showing events, services, issues, and points of interest near you.'],
      ];
      foreach ($features as $f) :
      ?>
      <div class="feature-card">
        <div class="feature-icon <?php echo esc_attr($f['color']); ?>">
          <i class="fa-solid <?php echo esc_attr($f['icon']); ?>"></i>
        </div>
        <h3 class="feature-title"><?php echo esc_html($f['title']); ?></h3>
        <p class="feature-description"><?php echo esc_html($f['desc']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     LOCAL SERVICES
     ============================================================ -->
<section class="section" aria-labelledby="services-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-eyebrow"><i class="fa-solid fa-briefcase"></i> <?php esc_html_e('Local Services', 'neighborhood-connect'); ?></div>
      <h2 class="section-title" id="services-heading"><?php esc_html_e('Trusted Neighborhood Professionals', 'neighborhood-connect'); ?></h2>
      <p class="section-description"><?php esc_html_e('Vetted local service providers recommended by your neighbors.', 'neighborhood-connect'); ?></p>
    </div>

    <?php
    $services = new WP_Query([
      'post_type'      => 'nc_service',
      'posts_per_page' => 6,
      'post_status'    => 'publish',
    ]);
    ?>

    <div class="card-grid">
      <?php if ($services->have_posts()) : ?>
        <?php while ($services->have_posts()) : $services->the_post(); ?>
          <?php get_template_part('template-parts/content', 'service'); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <?php nc_render_demo_services(); ?>
      <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:2.5rem;">
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn btn-outline btn-lg">
        <?php esc_html_e('Browse All Services', 'neighborhood-connect'); ?>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ============================================================
     COMMUNITY ISSUES
     ============================================================ -->
<section class="section section-alt" aria-labelledby="issues-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-eyebrow"><i class="fa-solid fa-triangle-exclamation"></i> <?php esc_html_e('Community Issues', 'neighborhood-connect'); ?></div>
      <h2 class="section-title" id="issues-heading"><?php esc_html_e('Help Fix Your Neighborhood', 'neighborhood-connect'); ?></h2>
      <p class="section-description"><?php esc_html_e('Report and track local issues until they\'re resolved. Your voice matters.', 'neighborhood-connect'); ?></p>
    </div>

    <?php
    $issues = new WP_Query([
      'post_type'      => 'nc_issue',
      'posts_per_page' => 4,
      'post_status'    => 'publish',
      'orderby'        => 'meta_value_num',
      'order'          => 'DESC',
      'meta_key'       => '_nc_votes',
    ]);
    ?>

    <div style="display:flex;flex-direction:column;gap:1rem;margin-bottom:2.5rem;" id="issues-list">
      <?php if ($issues->have_posts()) : ?>
        <?php while ($issues->have_posts()) : $issues->the_post(); ?>
          <?php get_template_part('template-parts/content', 'issue'); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <?php nc_render_demo_issues(); ?>
      <?php endif; ?>
    </div>

    <div style="display:flex;gap:1rem;flex-wrap:wrap;justify-content:center;">
      <a href="<?php echo esc_url(home_url('/issues/')); ?>" class="btn btn-outline">
        <?php esc_html_e('View All Issues', 'neighborhood-connect'); ?>
      </a>
      <button class="btn btn-primary" data-modal="report-issue-modal">
        <i class="fa-solid fa-plus"></i>
        <?php esc_html_e('Report an Issue', 'neighborhood-connect'); ?>
      </button>
    </div>
  </div>
</section>

<!-- Report Issue Modal -->
<div class="modal-overlay" id="report-issue-modal" role="dialog" aria-modal="true" aria-labelledby="report-issue-title">
  <div class="modal">
    <div class="modal-header">
      <h2 class="modal-title" id="report-issue-title"><?php esc_html_e('Report a Community Issue', 'neighborhood-connect'); ?></h2>
      <button class="modal-close" aria-label="<?php esc_attr_e('Close', 'neighborhood-connect'); ?>"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <?php if (is_user_logged_in()) : ?>
        <form id="issue-report-form" novalidate>
          <div class="form-group">
            <label class="form-label" for="issue-title"><?php esc_html_e('Issue Title', 'neighborhood-connect'); ?> <span class="required">*</span></label>
            <input type="text" id="issue-title" class="form-control" placeholder="<?php esc_attr_e('e.g., Broken streetlight on Oak St.', 'neighborhood-connect'); ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="issue-type"><?php esc_html_e('Issue Type', 'neighborhood-connect'); ?></label>
            <select id="issue-type" class="form-control">
              <option value="infrastructure"><?php esc_html_e('Infrastructure', 'neighborhood-connect'); ?></option>
              <option value="safety"><?php esc_html_e('Safety', 'neighborhood-connect'); ?></option>
              <option value="environment"><?php esc_html_e('Environment', 'neighborhood-connect'); ?></option>
              <option value="noise"><?php esc_html_e('Noise', 'neighborhood-connect'); ?></option>
              <option value="other"><?php esc_html_e('Other', 'neighborhood-connect'); ?></option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="issue-description"><?php esc_html_e('Description', 'neighborhood-connect'); ?></label>
            <textarea id="issue-description" class="form-control" placeholder="<?php esc_attr_e('Describe the issue in detail...', 'neighborhood-connect'); ?>"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="issue-location"><?php esc_html_e('Location / Address', 'neighborhood-connect'); ?></label>
            <input type="text" id="issue-location" class="form-control" placeholder="<?php esc_attr_e('Nearest street or landmark', 'neighborhood-connect'); ?>">
          </div>
          <?php wp_nonce_field('nc_report_issue', 'nc_issue_nonce'); ?>
          <button type="submit" class="btn btn-primary btn-full">
            <i class="fa-solid fa-paper-plane"></i>
            <?php esc_html_e('Submit Report', 'neighborhood-connect'); ?>
          </button>
        </form>
      <?php else : ?>
        <div style="text-align:center;padding:2rem 0;">
          <i class="fa-solid fa-lock" style="font-size:2.5rem;color:var(--color-text-muted);margin-bottom:1rem;display:block;"></i>
          <p style="margin-bottom:1.5rem;color:var(--color-text-muted);"><?php esc_html_e('You need to be logged in to report an issue.', 'neighborhood-connect'); ?></p>
          <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="btn btn-primary">
            <i class="fa-solid fa-right-to-bracket"></i>
            <?php esc_html_e('Log In to Report', 'neighborhood-connect'); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ============================================================
     MAP SECTION
     ============================================================ -->
<section class="map-section" aria-labelledby="map-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-eyebrow"><i class="fa-solid fa-map-location-dot"></i> <?php esc_html_e('Neighborhood Map', 'neighborhood-connect'); ?></div>
      <h2 class="section-title" id="map-heading"><?php esc_html_e('See What\'s Around You', 'neighborhood-connect'); ?></h2>
    </div>

    <div class="map-layout">
      <div class="map-container">
        <div class="map-placeholder">
          <i class="fa-solid fa-map-location-dot"></i>
          <p style="font-weight:600;"><?php esc_html_e('Interactive Map', 'neighborhood-connect'); ?></p>
          <p style="font-size:.875rem;"><?php esc_html_e('Add your Google Maps API key in the Customizer to enable the map.', 'neighborhood-connect'); ?></p>
          <a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=nc_settings')); ?>" class="btn btn-primary btn-sm">
            <?php esc_html_e('Configure Map', 'neighborhood-connect'); ?>
          </a>
        </div>
        <div id="nc-map"></div>
      </div>

      <div class="map-sidebar">
        <div class="map-legend">
          <div class="legend-title"><?php esc_html_e('Map Legend', 'neighborhood-connect'); ?></div>
          <div class="legend-items">
            <div class="legend-item"><div class="legend-dot" style="background:#2563eb;"></div><?php esc_html_e('Events', 'neighborhood-connect'); ?></div>
            <div class="legend-item"><div class="legend-dot" style="background:#10b981;"></div><?php esc_html_e('Services', 'neighborhood-connect'); ?></div>
            <div class="legend-item"><div class="legend-dot" style="background:#ef4444;"></div><?php esc_html_e('Open Issues', 'neighborhood-connect'); ?></div>
            <div class="legend-item"><div class="legend-dot" style="background:#f59e0b;"></div><?php esc_html_e('In Progress', 'neighborhood-connect'); ?></div>
            <div class="legend-item"><div class="legend-dot" style="background:#6b7280;"></div><?php esc_html_e('Resolved', 'neighborhood-connect'); ?></div>
          </div>
        </div>

        <div class="widget" style="margin:0;">
          <h3 class="widget-title"><?php esc_html_e('Quick Stats', 'neighborhood-connect'); ?></h3>
          <div style="display:flex;flex-direction:column;gap:.75rem;">
            <div style="display:flex;justify-content:space-between;font-size:.875rem;">
              <span style="color:var(--color-text-muted);"><?php esc_html_e('This week\'s events', 'neighborhood-connect'); ?></span>
              <strong><?php echo esc_html(wp_count_posts('nc_event')->publish); ?></strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;">
              <span style="color:var(--color-text-muted);"><?php esc_html_e('Active services', 'neighborhood-connect'); ?></span>
              <strong><?php echo esc_html(wp_count_posts('nc_service')->publish); ?></strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;">
              <span style="color:var(--color-text-muted);"><?php esc_html_e('Open issues', 'neighborhood-connect'); ?></span>
              <strong><?php echo esc_html(wp_count_posts('nc_issue')->publish); ?></strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.875rem;">
              <span style="color:var(--color-text-muted);"><?php esc_html_e('Community members', 'neighborhood-connect'); ?></span>
              <strong><?php echo esc_html(count_users()['total_users']); ?></strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     TESTIMONIALS
     ============================================================ -->
<section class="section section-alt" aria-labelledby="testimonials-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-eyebrow"><?php esc_html_e('What Residents Say', 'neighborhood-connect'); ?></div>
      <h2 class="section-title" id="testimonials-heading"><?php esc_html_e('Loved by Our Community', 'neighborhood-connect'); ?></h2>
    </div>

    <div class="testimonials-grid">
      <?php
      $testimonials = [
        ['text' => 'Neighborhood Connect completely changed how I engage with my community. I found out about the block party three hours after it was posted — and made 10 new friends that day!', 'name' => 'Sarah M.', 'role' => 'Resident, Oak Avenue', 'color' => '#2563eb'],
        ['text' => 'I reported a broken streetlight that had been ignored for months. Within a week of posting it on Neighborhood Connect, the city fixed it. The power of visibility!', 'name' => 'James T.', 'role' => 'Resident, Maple District', 'color' => '#059669'],
        ['text' => 'As a local plumber, the service directory brought me 12 new clients in my own neighborhood. Better than any advertising I have ever done.', 'name' => 'Mike R.', 'role' => 'Local Plumber', 'color' => '#d97706'],
      ];
      foreach ($testimonials as $t) :
      ?>
      <div class="testimonial-card">
        <p class="testimonial-text">"<?php echo esc_html($t['text']); ?>"</p>
        <div class="testimonial-author">
          <div class="author-avatar-placeholder" style="background:<?php echo esc_attr($t['color']); ?>;">
            <?php echo esc_html(nc_avatar_initials($t['name'])); ?>
          </div>
          <div>
            <div class="author-name"><?php echo esc_html($t['name']); ?></div>
            <div class="author-role"><?php echo esc_html($t['role']); ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     CTA
     ============================================================ -->
<section class="cta-section" aria-labelledby="cta-heading">
  <div class="container">
    <h2 class="cta-title" id="cta-heading">
      <?php esc_html_e('Ready to Connect with Your Neighborhood?', 'neighborhood-connect'); ?>
    </h2>
    <p class="cta-description">
      <?php esc_html_e('Join thousands of residents already making their communities stronger, safer, and more connected.', 'neighborhood-connect'); ?>
    </p>
    <div class="cta-actions">
      <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-lg" style="background:white;color:var(--color-primary);">
        <i class="fa-solid fa-user-plus"></i>
        <?php esc_html_e('Create Free Account', 'neighborhood-connect'); ?>
      </a>
      <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-outline btn-lg" style="border-color:rgba(255,255,255,0.5);color:white;">
        <?php esc_html_e('Browse Events First', 'neighborhood-connect'); ?>
      </a>
    </div>
  </div>
</section>

<?php

/* Demo content renderers for fresh installs */
function nc_render_demo_events() {
    $demos = [
        ['title' => 'Community Block Party', 'date' => '2026-05-24', 'location' => 'Park Avenue', 'cat' => 'Social', 'rsvp' => 24, 'icon' => '🎉', 'cat_key' => 'social'],
        ['title' => 'Farmers Market Saturday', 'date' => '2026-05-25', 'location' => 'Town Square', 'cat' => 'Food', 'rsvp' => 56, 'icon' => '🥦', 'cat_key' => 'food'],
        ['title' => 'Kids Soccer League', 'date' => '2026-05-27', 'location' => 'Central Park', 'cat' => 'Sports', 'rsvp' => 18, 'icon' => '⚽', 'cat_key' => 'sports'],
        ['title' => 'Neighborhood Clean-Up', 'date' => '2026-05-31', 'location' => 'Riverside Rd', 'cat' => 'Environment', 'rsvp' => 12, 'icon' => '♻️', 'cat_key' => 'social'],
        ['title' => 'Art Workshop for Adults', 'date' => '2026-06-02', 'location' => 'Community Hall', 'cat' => 'Arts', 'rsvp' => 9, 'icon' => '🎨', 'cat_key' => 'arts'],
        ['title' => 'Yoga in the Park', 'date' => '2026-06-05', 'location' => 'Greenfield Park', 'cat' => 'Health', 'rsvp' => 31, 'icon' => '🧘', 'cat_key' => 'health'],
    ];

    $colors = ['#2563eb','#059669','#d97706','#dc2626','#7c3aed','#db2777'];

    foreach ($demos as $i => $d) {
        $ts = strtotime($d['date']);
        $bg = $colors[$i % count($colors)];
        echo '<div class="event-card" data-category="' . esc_attr($d['cat_key']) . '">';
        echo '<div class="event-card-image" style="background:' . esc_attr($bg) . ';"><div class="no-image">' . esc_html($d['icon']) . '</div>';
        echo '<div class="event-card-badge"><span class="badge badge-primary">' . esc_html($d['cat']) . '</span></div>';
        echo '</div>';
        echo '<div class="event-card-content">';
        echo '<div class="event-meta"><div class="event-meta-item"><i class="fa-solid fa-calendar"></i>' . esc_html(date('M d, Y', $ts)) . '</div><div class="event-meta-item"><i class="fa-solid fa-location-dot"></i>' . esc_html($d['location']) . '</div></div>';
        echo '<h3 class="event-card-title"><a href="#">' . esc_html($d['title']) . '</a></h3>';
        echo '<p class="event-card-excerpt">A wonderful community event for all residents. Come join us and meet your neighbors!</p>';
        echo '<div class="event-card-footer"><div class="rsvp-count"><div class="rsvp-avatars">';
        for ($j = 0; $j < 3; $j++) {
            echo '<div class="rsvp-avatar" style="background:' . esc_attr($colors[$j]) . ';">' . chr(65 + $j) . '</div>';
        }
        echo '</div><span class="rsvp-number">' . esc_html($d['rsvp']) . '</span> going</div>';
        echo '<button class="rsvp-btn" data-event-id="' . esc_attr($i + 1) . '"><i class="fa-solid fa-plus"></i> RSVP</button>';
        echo '</div></div></div>';
    }
}

function nc_render_demo_services() {
    $demos = [
        ['name' => 'Ahmed\'s Plumbing', 'cat' => 'Plumbing', 'rating' => 4.9, 'reviews' => 47, 'price' => 'From $60/hr', 'icon' => '🔧'],
        ['name' => 'Green Thumb Garden', 'cat' => 'Gardening', 'rating' => 4.7, 'reviews' => 32, 'price' => 'From $40/hr', 'icon' => '🌿'],
        ['name' => 'Sarah\'s Tutoring', 'cat' => 'Education', 'rating' => 5.0, 'reviews' => 18, 'price' => '$35/session', 'icon' => '📚'],
        ['name' => 'Clean Sweep Co.', 'cat' => 'Cleaning', 'rating' => 4.8, 'reviews' => 63, 'price' => 'From $80/visit', 'icon' => '✨'],
        ['name' => 'Tech Help Hub', 'cat' => 'IT Support', 'rating' => 4.6, 'reviews' => 25, 'price' => '$50/hr', 'icon' => '💻'],
        ['name' => 'Paws & Claws', 'cat' => 'Pet Care', 'rating' => 4.9, 'reviews' => 41, 'price' => '$25/walk', 'icon' => '🐾'],
    ];
    $colors = ['#2563eb','#059669','#d97706','#dc2626','#7c3aed','#db2777'];

    foreach ($demos as $i => $d) {
        echo '<div class="service-card">';
        echo '<div class="service-card-header">';
        echo '<div class="service-avatar-placeholder" style="background:' . esc_attr($colors[$i % count($colors)]) . ';">' . esc_html($d['icon']) . '</div>';
        echo '<div class="service-info"><div class="service-name"><a href="#">' . esc_html($d['name']) . '</a></div><div class="service-category">' . esc_html($d['cat']) . '</div></div>';
        echo '</div>';
        echo '<div class="service-rating">' . nc_star_rating($d['rating']) . '<span>' . esc_html($d['rating']) . ' (' . esc_html($d['reviews']) . ' reviews)</span></div>';
        echo '<p class="service-description">Professional, reliable, and trusted by ' . esc_html($d['reviews']) . ' neighbors. Available for bookings in your area.</p>';
        echo '<div class="service-footer"><span class="service-price">' . esc_html($d['price']) . '</span><a href="#" class="btn btn-primary btn-sm">Book Now</a></div>';
        echo '</div>';
    }
}

function nc_render_demo_issues() {
    $demos = [
        ['title' => 'Broken Streetlight — Oak & 5th', 'desc' => 'The streetlight at Oak Ave & 5th St has been out for 3 weeks. Safety hazard at night.', 'status' => 'open', 'votes' => 23, 'location' => 'Oak Ave & 5th St'],
        ['title' => 'Pothole on Riverside Road', 'desc' => 'Large pothole causing damage to vehicles. Multiple residents have reported flat tyres.', 'status' => 'in-progress', 'votes' => 18, 'location' => 'Riverside Rd'],
        ['title' => 'Overflowing Rubbish Bin at Park Entrance', 'desc' => 'Bin at main park entrance has not been emptied in 10 days. Attracting pests.', 'status' => 'open', 'votes' => 11, 'location' => 'Central Park'],
        ['title' => 'Speed Bump Needed Near School', 'desc' => 'Cars speeding near the primary school on Elm Street — children at risk.', 'status' => 'resolved', 'votes' => 47, 'location' => 'Elm Street'],
    ];

    foreach ($demos as $i => $d) {
        $status_class = 'status-' . str_replace('-', '-', $d['status']);
        $status_label = ucwords(str_replace('-', ' ', $d['status']));
        echo '<div class="issue-card">';
        echo '<div class="issue-status-dot ' . esc_attr($status_class) . '"></div>';
        echo '<div class="issue-content">';
        echo '<h3 class="issue-title"><a href="#">' . esc_html($d['title']) . '</a></h3>';
        echo '<p class="issue-description">' . esc_html($d['desc']) . '</p>';
        echo '<div class="issue-footer">';
        echo '<span><i class="fa-solid fa-location-dot"></i>' . esc_html($d['location']) . '</span>';
        echo '<span class="badge ' . ($d['status'] === 'resolved' ? 'badge-success' : ($d['status'] === 'in-progress' ? 'badge-warning' : 'badge-danger')) . '">' . esc_html($status_label) . '</span>';
        echo '<button class="vote-btn" data-issue-id="' . esc_attr($i + 1) . '"><i class="fa-solid fa-thumbs-up"></i><span class="vote-count">' . esc_html($d['votes']) . '</span></button>';
        echo '</div></div></div>';
    }
}
?>

<?php get_footer(); ?>
