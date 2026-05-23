<?php get_header(); ?>

<?php
/* ── helpers ─────────────────────────────────── */
$colors   = ['#4f46e5','#0d9488','#f59e0b','#7c3aed','#ef4444','#16a34a'];
$emojis   = ['community'=>'🎉','sports'=>'⚽','food'=>'🥦','arts'=>'🎨','health'=>'🧘','environment'=>'♻️','education'=>'📚'];

$event_count   = (int) wp_count_posts('nc_event')->publish;
$service_count = (int) wp_count_posts('nc_service')->publish;
$issue_count   = (int) wp_count_posts('nc_issue')->publish;
$member_count  = (int) count_users()['total_users'];
?>

<!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
<section class="hero" aria-labelledby="hero-heading">
  <div class="container">
    <div class="hero-inner">

      <!-- Left: Copy -->
      <div class="hero-text">

        <div class="hero-pill">
          <span class="hero-pill-dot"><i class="fa-solid fa-location-dot" style="font-size:9px;"></i></span>
          <?php echo esc_html(get_theme_mod('nc_neighborhood', 'Canal View Society')); ?>
        </div>

        <h1 class="hero-heading" id="hero-heading">
          <?php echo esc_html(get_theme_mod('nc_neighborhood', 'Canal View Society')); ?>,<br><span class="line-accent">online</span>.
        </h1>

        <p class="hero-sub">
          <?php echo esc_html(get_theme_mod('nc_hero_description', 'Book in-society services, RSVP to community events, report issues, and discover everything around Canal View — all in one place.')); ?>
        </p>

        <div class="hero-actions">
          <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-calendar-days"></i>
            <?php esc_html_e('Browse Events', 'neighborhood-connect'); ?>
          </a>
          <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-ghost btn-lg">
            <?php esc_html_e('Join the community', 'neighborhood-connect'); ?>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>

        <!-- Social proof -->
        <div class="hero-proof">
          <div class="proof-avatars">
            <?php
            $proof_colors = ['#4f46e5','#0d9488','#f59e0b','#7c3aed'];
            $proof_names  = ['J','M','S','A'];
            foreach ($proof_names as $i => $n) :
            ?>
              <div class="proof-avatar" style="background:<?php echo esc_attr($proof_colors[$i]); ?>;">
                <?php echo esc_html($n); ?>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="proof-text">
            <strong><?php echo esc_html(number_format($member_count + 247)); ?>+ residents</strong><br>
            already connected
          </div>

          <div class="proof-divider"></div>

          <div class="proof-stat">
            <span class="proof-stat-num" data-count="<?php echo esc_attr($event_count); ?>"><?php echo esc_html($event_count); ?></span>
            <div class="proof-stat-label">Events this month</div>
          </div>

          <div class="proof-stat">
            <span class="proof-stat-num" data-count="<?php echo esc_attr($service_count); ?>"><?php echo esc_html($service_count); ?></span>
            <div class="proof-stat-label">Local services</div>
          </div>
        </div>

      </div>

      <!-- Right: Visual card -->
      <div class="hero-visual" aria-hidden="true">

        <!-- <div class="hero-float-badge">
          <i class="fa-solid fa-circle" style="font-size:7px;"></i> Live in your area
        </div> -->

        <div class="hero-float-card">
          <div class="hero-card-top">
            <div class="hero-card-icon-wrap">
              <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
              <div class="hero-card-label">Upcoming Events</div>
              <div class="hero-card-sub">This week near you</div>
            </div>
          </div>

          <?php
          $hero_events = new WP_Query([
            'post_type'      => 'nc_event',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'meta_key'       => '_nc_event_date',
            'orderby'        => 'meta_value',
            'order'           => 'ASC',
            'meta_query'     => [[
              'key'     => '_nc_event_date',
              'value'   => current_time('Y-m-d'),
              'compare' => '>=',
              'type'    => 'DATE',
            ]],
          ]);
          ?>

          <div class="hero-event-list">
            <?php if ($hero_events->have_posts()) :
              while ($hero_events->have_posts()) : $hero_events->the_post();
                $meta = nc_get_event_meta(get_the_ID());
                $ts   = $meta['date'] ? strtotime($meta['date']) : time();
                $rsvp = count($meta['rsvps']);
            ?>
              <div class="hero-event-row">
                <div class="event-cal">
                  <div class="event-cal-month"><?php echo esc_html(date('M', $ts)); ?></div>
                  <div class="event-cal-day"><?php echo esc_html(date('d', $ts)); ?></div>
                </div>
                <div class="event-row-info">
                  <div class="event-row-title"><?php the_title(); ?></div>
                  <div class="event-row-meta"><?php echo esc_html($meta['location'] ?: 'Community Area'); ?></div>
                </div>
                <div class="event-row-going"><?php echo esc_html($rsvp); ?> going</div>
              </div>
            <?php endwhile; wp_reset_postdata();
            else : ?>
              <?php foreach ([
                ['mo'=>'MAY','d'=>'24','t'=>'Block Party','l'=>'Park Avenue','g'=>'24'],
                ['mo'=>'MAY','d'=>'25','t'=>'Farmers Market','l'=>'Town Square','g'=>'56'],
                ['mo'=>'MAY','d'=>'27','t'=>'Soccer League','l'=>'Central Park','g'=>'18'],
              ] as $de) : ?>
              <div class="hero-event-row">
                <div class="event-cal">
                  <div class="event-cal-month"><?php echo esc_html($de['mo']); ?></div>
                  <div class="event-cal-day"><?php echo esc_html($de['d']); ?></div>
                </div>
                <div class="event-row-info">
                  <div class="event-row-title"><?php echo esc_html($de['t']); ?></div>
                  <div class="event-row-meta"><?php echo esc_html($de['l']); ?></div>
                </div>
                <div class="event-row-going"><?php echo esc_html($de['g']); ?> going</div>
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- Floating mini cards -->
        <div class="hero-mini-card card-a">
          <div class="mc-icon" style="background:var(--c-red-soft);color:var(--c-red);">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <div>
            <div style="font-size:11px;">New issue reported</div>
            <div style="font-size:10px;color:var(--c-muted);font-weight:400;">Canal Bank Road</div>
          </div>
        </div>

        <div class="hero-mini-card card-b">
          <div class="mc-icon" style="background:var(--c-teal-soft);color:var(--c-teal);">
            <i class="fa-solid fa-star"></i>
          </div>
          <div>
            <div style="font-size:11px;">Top-rated plumber</div>
            <div style="font-size:10px;color:var(--c-muted);font-weight:400;">⭐ 4.9 · 47 reviews</div>
          </div>
        </div>

      </div><!-- .hero-visual -->
    </div><!-- .hero-inner -->
  </div>
</section>

<!-- ══════════════════════════════════════════
     STATS STRIP
══════════════════════════════════════════ -->
<div class="stats-strip">
  <div class="container">
    <div class="stats-grid">

      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-primary-soft);color:var(--c-primary);">
          <i class="fa-solid fa-calendar-days"></i>
        </div>
        <span class="stat-num" data-count="<?php echo esc_attr($event_count); ?>"><?php echo esc_html($event_count); ?></span>
        <div class="stat-label">Events</div>
      </div>

      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-teal-soft);color:var(--c-teal);">
          <i class="fa-solid fa-briefcase"></i>
        </div>
        <span class="stat-num" data-count="<?php echo esc_attr($service_count); ?>"><?php echo esc_html($service_count); ?></span>
        <div class="stat-label">Services</div>
      </div>

      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-red-soft);color:var(--c-red);">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <span class="stat-num" data-count="<?php echo esc_attr($issue_count); ?>"><?php echo esc_html($issue_count); ?></span>
        <div class="stat-label">Issues tracked</div>
      </div>

      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-violet-soft);color:var(--c-violet);">
          <i class="fa-solid fa-users"></i>
        </div>
        <span class="stat-num" data-count="<?php echo esc_attr($member_count); ?>"><?php echo esc_html($member_count); ?></span>
        <div class="stat-label">Residents</div>
      </div>

    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════
     SEARCH
══════════════════════════════════════════ -->
<section style="padding: var(--s-12) 0;">
  <div class="container">
    <div class="search-box">
      <div style="font-size:var(--text-sm);font-weight:var(--fw-semibold);color:var(--c-text);margin-bottom:var(--s-3);">
        <i class="fa-solid fa-magnifying-glass" style="color:var(--c-primary);margin-right:6px;"></i>
        <?php esc_html_e('What are you looking for?', 'neighborhood-connect'); ?>
      </div>
      <div class="search-bar">
        <div class="search-field-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="search" class="search-input" placeholder="<?php esc_attr_e('Events, services, issues…', 'neighborhood-connect'); ?>" data-autocomplete="true" aria-label="Search">
        </div>
        <select class="search-select" aria-label="Filter by type">
          <option value=""><?php esc_html_e('All types', 'neighborhood-connect'); ?></option>
          <option value="nc_event"><?php esc_html_e('Events', 'neighborhood-connect'); ?></option>
          <option value="nc_service"><?php esc_html_e('Services', 'neighborhood-connect'); ?></option>
          <option value="nc_issue"><?php esc_html_e('Issues', 'neighborhood-connect'); ?></option>
        </select>
        <button class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
      </div>
      <div class="filter-chips" data-target="#events-grid">
        <button class="chip active" data-filter="all">All</button>
        <button class="chip" data-filter="community">🎉 Community</button>
        <button class="chip" data-filter="sports">⚽ Sports</button>
        <button class="chip" data-filter="food">🥦 Food</button>
        <button class="chip" data-filter="arts">🎨 Arts</button>
        <button class="chip" data-filter="health">🧘 Health</button>
        <button class="chip" data-filter="environment">♻️ Environment</button>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     UPCOMING EVENTS
══════════════════════════════════════════ -->
<section class="section section-warm" aria-labelledby="events-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fa-solid fa-calendar-days"></i> What's On</div>
      <h2 class="section-title" id="events-heading">Upcoming Events</h2>
      <p class="section-desc">Discover and join events happening right in your neighborhood this week.</p>
    </div>

    <?php
    $events_q = new WP_Query([
      'post_type'      => 'nc_event',
      'posts_per_page' => 6,
      'post_status'    => 'publish',
      'meta_key'       => '_nc_event_date',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
      'meta_query'     => [[
        'key'     => '_nc_event_date',
        'value'   => current_time('Y-m-d'),
        'compare' => '>=',
        'type'    => 'DATE',
      ]],
    ]);

    // If no upcoming events yet, fall back to any published events so the grid isn't empty
    if (!$events_q->have_posts()) {
      $events_q = new WP_Query([
        'post_type'      => 'nc_event',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
      ]);
    }
    ?>

    <?php
    $cat_slug_map = [
      'Community' => 'community', 'Food & Drink' => 'food',
      'Sports' => 'sports', 'Arts' => 'arts',
      'Health' => 'health', 'Environment' => 'environment',
      'Education' => 'education',
    ];
    ?>
    <div class="grid-3" id="events-grid">
      <?php
      $ei = 0;
      if ($events_q->have_posts()) :
        while ($events_q->have_posts()) : $events_q->the_post();
          $meta = nc_get_event_meta(get_the_ID());
          $ts   = $meta['date'] ? strtotime($meta['date']) : time();
          $rsvp_count = count($meta['rsvps']);
          $cat_raw  = $meta['category'] ?: 'Community';
          $cat_slug = $cat_slug_map[$cat_raw] ?? strtolower(preg_replace('/[^a-z0-9]/i', '', $cat_raw));
          $bg   = $colors[$ei % count($colors)];
          $icon = $emojis[$cat_slug] ?? '📅';
          $user_rsvpd = is_user_logged_in() && nc_user_has_rsvpd(get_the_ID(), get_current_user_id());
          $ei++;
      ?>
      <article class="event-card" data-category="<?php echo esc_attr($cat_slug); ?>">
        <div class="event-card-img" <?php if (!has_post_thumbnail()) echo 'style="background:linear-gradient(135deg,' . esc_attr($bg) . ','. esc_attr($bg) . 'cc)"'; ?>>
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('nc-card', ['loading'=>'lazy','alt'=>get_the_title()]); ?>
          <?php else : ?>
            <div class="placeholder-icon"><?php echo esc_html($icon); ?></div>
          <?php endif; ?>
          <div class="event-date-chip">
            <div class="date-chip-month"><?php echo esc_html(date('M', $ts)); ?></div>
            <div class="date-chip-day"><?php echo esc_html(date('d', $ts)); ?></div>
          </div>
          <div class="event-cat-pill"><?php echo esc_html($cat_raw); ?></div>
        </div>
        <div class="event-card-body">
          <div class="event-card-meta">
            <?php if ($meta['time']) : ?><div class="meta-item"><i class="fa-regular fa-clock"></i> <?php echo esc_html($meta['time']); ?></div><?php endif; ?>
            <?php if ($meta['location']) : ?><div class="meta-item"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($meta['location']); ?></div><?php endif; ?>
          </div>
          <h3 class="event-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="event-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
          <div class="event-card-foot">
            <div class="going-row">
              <?php if ($rsvp_count > 0) : ?>
                <div class="going-faces">
                  <?php for ($f=0; $f<min(3,$rsvp_count); $f++) :
                    $fc = $colors[$f % count($colors)];
                  ?>
                    <div class="going-face" style="background:<?php echo esc_attr($fc); ?>;"><?php echo esc_html(chr(65+$f)); ?></div>
                  <?php endfor; ?>
                </div>
                <span><strong class="rsvp-number"><?php echo esc_html($rsvp_count); ?></strong> going</span>
              <?php else : ?>
                <span style="font-size:var(--text-xs);color:var(--c-muted);">Be the first to join!</span>
              <?php endif; ?>
            </div>
            <?php if (is_user_logged_in()) : ?>
              <button class="rsvp-btn<?php echo $user_rsvpd ? ' rsvp-joined' : ''; ?>" data-event-id="<?php echo esc_attr(get_the_ID()); ?>">
                <?php if ($user_rsvpd) : ?><i class="fa-solid fa-check"></i> Joined<?php else : ?><i class="fa-solid fa-plus"></i> RSVP<?php endif; ?>
              </button>
            <?php else : ?>
              <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="rsvp-btn"><i class="fa-solid fa-plus"></i> RSVP</a>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata();
      endif;

      // Pad with demo events so the grid always shows 6 cards
      if ($ei < 6) nc_fp_demo_events($colors, $emojis, 6 - $ei, $ei);
      ?>
    </div>

    <div style="text-align:center;margin-top:var(--s-10);">
      <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-ghost btn-lg">
        View all events <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════ -->
<section class="section section-alt" aria-labelledby="how-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Simple as 1-2-3</div>
      <h2 class="section-title" id="how-heading">How Neighborhood Connect Works</h2>
      <p class="section-desc">Getting started takes under two minutes.</p>
    </div>

    <div class="grid-3">
      <?php
      $steps = [
        ['num'=>'1','icon'=>'📍','title'=>'Join Your Neighborhood','desc'=>'Create a free account and verify you live in the area. Your data stays private — only neighbors can see you.'],
        ['num'=>'2','icon'=>'🔍','title'=>'Discover & Connect','desc'=>'Browse upcoming events, find local services, and see what issues neighbors are tracking near you.'],
        ['num'=>'3','icon'=>'🤝','title'=>'Participate & Improve','desc'=>'RSVP to events, hire services, vote on issues, and help make your neighborhood a better place for everyone.'],
      ];
      foreach ($steps as $s) :
      ?>
      <div class="step-card">
        <div class="step-num"><?php echo esc_html($s['num']); ?></div>
        <div class="step-icon"><?php echo esc_html($s['icon']); ?></div>
        <h3 class="step-title"><?php echo esc_html($s['title']); ?></h3>
        <p class="step-desc"><?php echo esc_html($s['desc']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     LOCAL SERVICES
══════════════════════════════════════════ -->
<section class="section" aria-labelledby="services-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fa-solid fa-briefcase"></i> Local Services</div>
      <h2 class="section-title" id="services-heading">Society Services On Demand</h2>
      <p class="section-desc">In-house teams for plumbing, electrical, cleaning, pest control, tiffin, groceries and more. Request one — the society dispatches a team member to your door.</p>
    </div>

    <?php
    $services_q = new WP_Query([
      'post_type'      => 'nc_service',
      'posts_per_page' => 6,
      'post_status'    => 'publish',
    ]);
    ?>

    <div class="grid-3">
      <?php
      $si = 0;
      if ($services_q->have_posts()) :
        while ($services_q->have_posts()) : $services_q->the_post();
          $cat      = get_post_meta(get_the_ID(), '_nc_service_category', true) ?: 'Service';
          $price    = get_post_meta(get_the_ID(), '_nc_price', true);
          $rating   = (float)(get_post_meta(get_the_ID(), '_nc_rating', true) ?: 4.5);
          $reviews  = (int) get_post_meta(get_the_ID(), '_nc_review_count', true);
          $bg       = $colors[$si % count($colors)];
          $initials = nc_avatar_initials(get_the_title());
          $si++;
      ?>
      <div class="service-card">
        <div class="service-card-top">
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('nc-avatar', ['class'=>'service-avatar','loading'=>'lazy']); ?>
          <?php else : ?>
            <div class="service-avatar-placeholder" style="background:<?php echo esc_attr($bg); ?>;"><?php echo esc_html($initials); ?></div>
          <?php endif; ?>
          <div class="service-info">
            <div class="service-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
            <div class="service-cat"><?php echo esc_html($cat); ?></div>
          </div>
        </div>
        <div class="service-rating">
          <span class="stars"><?php echo str_repeat('★', min(5, round($rating))); ?><?php echo str_repeat('☆', 5 - min(5, round($rating))); ?></span>
          <span><?php echo esc_html(number_format($rating,1)); ?></span>
          <?php if ($reviews) echo '<span>(' . esc_html($reviews) . ' reviews)</span>'; ?>
        </div>
        <p class="service-desc"><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="service-foot">
          <div class="service-price"><?php echo $price ? esc_html($price) : '<small>Contact for pricing</small>'; ?></div>
          <a href="<?php the_permalink(); ?>" class="btn btn-secondary btn-sm">Contact</a>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata();
      endif;

      // Pad with demo services so the grid always shows 6 cards
      if ($si < 6) nc_fp_demo_services($colors, 6 - $si, $si);
      ?>
    </div>

    <div style="text-align:center;margin-top:var(--s-10);">
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn btn-ghost btn-lg">
        Browse all services <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     FEATURES GRID
══════════════════════════════════════════ -->
<section class="section section-alt" aria-labelledby="features-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Why Neighborhood Connect</div>
      <h2 class="section-title" id="features-heading">Everything Your Community Needs</h2>
      <p class="section-desc">One platform built for the way real neighborhoods actually work.</p>
    </div>

    <div class="grid-3">
      <?php
      $features = [
        ['ic'=>'fa-calendar-days', 'color'=>'ic-orange', 'title'=>'Events & RSVPs',     'desc'=>'Create and discover events with RSVP tracking, capacity limits, and iCal export.'],
        ['ic'=>'fa-screwdriver-wrench','color'=>'ic-teal','title'=>'In-House Services','desc'=>'Plumber, electrician, cleaner, tiffin, groceries — the society dispatches a verified team member to your door.'],
        ['ic'=>'fa-triangle-exclamation','color'=>'ic-gold','title'=>'Issue Tracker',   'desc'=>'Report potholes, broken lights, and safety hazards. Track them until they\'re fixed.'],
        ['ic'=>'fa-store',         'color'=>'ic-violet', 'title'=>'Marketplace',        'desc'=>'Buy, sell, or give away goods within walking distance of your home.'],
        ['ic'=>'fa-map-location-dot','color'=>'ic-red',  'title'=>'Neighborhood Map',   'desc'=>'Interactive map showing events, services, and issues near your address.'],
        ['ic'=>'fa-bell',          'color'=>'ic-green',  'title'=>'Smart Alerts',       'desc'=>'Get notified about events you care about, issue updates, and neighborhood news.'],
      ];
      foreach ($features as $f) :
      ?>
      <div class="feature-card">
        <div class="feature-icon <?php echo esc_attr($f['color']); ?>">
          <i class="fa-solid <?php echo esc_attr($f['ic']); ?>"></i>
        </div>
        <h3 class="feature-title"><?php echo esc_html($f['title']); ?></h3>
        <p class="feature-desc"><?php echo esc_html($f['desc']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     COMMUNITY ISSUES
══════════════════════════════════════════ -->
<section class="section" aria-labelledby="issues-heading">
  <div class="container">
    <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:var(--s-4);margin-bottom:var(--s-10);">
      <div>
        <div class="section-tag" style="margin-bottom:var(--s-3);"><i class="fa-solid fa-triangle-exclamation"></i> Community Issues</div>
        <h2 class="section-title" id="issues-heading" style="margin-bottom:var(--s-2);">Help Fix Your Neighborhood</h2>
        <p class="section-desc" style="margin:0;">Report problems and vote on the issues that matter most.</p>
      </div>
      <button class="btn btn-primary" data-modal="report-issue-modal">
        <i class="fa-solid fa-plus"></i> Report an Issue
      </button>
    </div>

    <?php
    $issues_q = new WP_Query([
      'post_type'      => 'nc_issue',
      'posts_per_page' => 4,
      'post_status'    => 'publish',
      'orderby'        => 'date',
      'order'          => 'DESC',
    ]);
    ?>

    <div style="display:flex;flex-direction:column;gap:var(--s-3);">
      <?php
      $ii = 0;
      if ($issues_q->have_posts()) :
        while ($issues_q->have_posts()) : $issues_q->the_post();
          $status   = get_post_meta(get_the_ID(), '_nc_status', true) ?: 'open';
          $votes    = (int) get_post_meta(get_the_ID(), '_nc_votes', true);
          $location = get_post_meta(get_the_ID(), '_nc_location', true);
          $user_voted = is_user_logged_in() && in_array(get_current_user_id(), get_post_meta(get_the_ID(), '_nc_voters', true) ?: []);
          $status_display = str_replace('_', '-', $status); // in_progress → in-progress
          $pill_class = 'pill-' . $status_display;
          $status_labels = ['open'=>'Open','in-progress'=>'In Progress','resolved'=>'Resolved'];
          $ii++;
      ?>
      <div class="issue-card">
        <div class="issue-status status-<?php echo esc_attr($status_display); ?>"></div>
        <div class="issue-body">
          <h3 class="issue-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="issue-desc"><?php echo esc_html(get_the_excerpt()); ?></p>
          <div class="issue-foot">
            <?php if ($location) : ?>
              <div class="issue-foot-item"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></div>
            <?php endif; ?>
            <div class="issue-foot-item"><i class="fa-regular fa-clock"></i> <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp'))); ?> ago</div>
            <span class="status-pill <?php echo esc_attr($pill_class); ?>"><?php echo esc_html($status_labels[$status_display] ?? ucfirst(str_replace('-',' ',$status_display))); ?></span>
            <button class="vote-btn<?php echo $user_voted ? ' voted' : ''; ?>" data-issue-id="<?php echo esc_attr(get_the_ID()); ?>">
              <i class="fa-solid fa-thumbs-up"></i> <span class="vote-count"><?php echo esc_html($votes); ?></span>
            </button>
          </div>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata();
      endif;

      // Pad with demo issues so the list always shows 4 cards
      if ($ii < 4) nc_fp_demo_issues(4 - $ii);
      ?>
    </div>

    <div style="text-align:center;margin-top:var(--s-8);">
      <a href="<?php echo esc_url(home_url('/issues/')); ?>" class="btn btn-ghost">
        View all issues <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════ -->
<section class="section section-warm" aria-labelledby="testimonials-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Real Stories</div>
      <h2 class="section-title" id="testimonials-heading">Loved by Residents</h2>
    </div>

    <div class="grid-3">
      <?php
      $testimonials = [
        ['stars'=>5,'text'=>'Booked a plumber through the app at 9 PM — Bilal called me back within 20 minutes and fixed the kitchen leak the next morning. Felt like the society actually works for us now.','name'=>'Saima A.','role'=>'Phase 1, House 224','color'=>'#4f46e5'],
        ['stars'=>5,'text'=>'I reported a broken streetlight on Canal Bank Road that had been out for weeks. Three days later it was fixed and the status updated to Resolved on the app. Finally.','name'=>'Imran R.','role'=>'Block B resident','color'=>'#0d9488'],
        ['stars'=>5,'text'=>'The amenities page is brilliant — I just moved into Canal View and I knew where every school, pharmacy and ATM was within ten minutes. Saved me hours of asking the guard.','name'=>'Hira K.','role'=>'Phase 2, new resident','color'=>'#f59e0b'],
      ];
      foreach ($testimonials as $t) :
        $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $t['name']), 0, 2)));
      ?>
      <div class="testimonial-card">
        <div class="testimonial-stars"><?php echo str_repeat('★', $t['stars']); ?></div>
        <p class="testimonial-text">"<?php echo esc_html($t['text']); ?>"</p>
        <div class="testimonial-author">
          <div class="author-avi" style="background:<?php echo esc_attr($t['color']); ?>;"><?php echo esc_html($initials); ?></div>
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

<!-- ══════════════════════════════════════════
     CTA
══════════════════════════════════════════ -->
<section class="cta-section" aria-labelledby="cta-heading">
  <div class="container cta-inner">
    <span class="cta-emoji">🏘️</span>
    <h2 class="cta-title" id="cta-heading">Ready to connect with your neighborhood?</h2>
    <p class="cta-desc">Join thousands of residents already making their communities stronger, safer, and more connected.</p>
    <div class="cta-actions">
      <a href="<?php echo esc_url(wp_registration_url()); ?>" class="btn btn-white btn-xl">
        <i class="fa-solid fa-user-plus"></i> Create Free Account
      </a>
      <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-xl" style="border:2px solid rgba(255,255,255,0.4);color:white;background:rgba(255,255,255,0.1);">
        Browse Events First
      </a>
    </div>
  </div>
</section>

<!-- Report Issue Modal -->
<div class="modal-overlay" id="report-issue-modal" role="dialog" aria-modal="true" aria-labelledby="ri-title">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title" id="ri-title">Report a Community Issue</span>
      <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <?php if (is_user_logged_in()) : ?>
        <form id="issue-report-form" novalidate>
          <div class="form-group">
            <label class="form-label" for="issue-title">Issue Title <span class="req">*</span></label>
            <input type="text" id="issue-title" class="form-control" placeholder="e.g. Broken streetlight on Oak St." required>
          </div>
          <div class="form-group">
            <label class="form-label" for="issue-type">Type</label>
            <select id="issue-type" class="form-control">
              <option>Infrastructure</option><option>Safety</option><option>Environment</option><option>Noise</option><option>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="issue-desc">Description</label>
            <textarea id="issue-desc" class="form-control" placeholder="Describe the issue in detail…"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="issue-loc">Location</label>
            <input type="text" id="issue-loc" class="form-control" placeholder="Nearest street or landmark">
          </div>
          <?php wp_nonce_field('nc_report_issue','nc_issue_nonce'); ?>
          <button type="submit" class="btn btn-primary btn-full">
            <i class="fa-solid fa-paper-plane"></i> Submit Report
          </button>
        </form>
      <?php else : ?>
        <div style="text-align:center;padding:var(--s-8) 0;">
          <i class="fa-solid fa-lock" style="font-size:2.5rem;color:var(--c-muted);margin-bottom:var(--s-4);display:block;"></i>
          <p style="color:var(--c-muted);margin-bottom:var(--s-5);">You need to be logged in to report an issue.</p>
          <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="btn btn-primary">
            <i class="fa-solid fa-right-to-bracket"></i> Log In to Report
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php

/* ── Demo data helpers ─────────────────────── */
function nc_fp_demo_events(array $colors, array $emojis, int $limit = 6, int $offset = 0) {
  $demos = [
    ['t'=>'Phase 1 Block Party & Iftar','d'=>'2026-05-24','tm'=>'6:00 PM','l'=>'Phase 1 Community Lawn','cat'=>'community','rsvp'=>54,'desc'=>'A community get-together for all Canal View residents — live music, dinner stalls, and games for the whole family.'],
    ['t'=>'Sunday Bachat Bazaar','d'=>'2026-05-25','tm'=>'8:00 AM','l'=>'Society Commercial Block','cat'=>'food','rsvp'=>71,'desc'=>'Fresh produce, homemade tiffin and crafts — by Canal View residents, for Canal View residents. Every Sunday.'],
    ['t'=>'Kids Cricket League — Phase 2','d'=>'2026-05-27','tm'=>'4:30 PM','l'=>'Phase 2 Sports Ground','cat'=>'sports','rsvp'=>24,'desc'=>'Tape-ball cricket for kids aged 8–14. Coaches from the society sports committee. Helmets and tape balls provided.'],
    ['t'=>'Canal Bank Clean-Up Drive','d'=>'2026-05-31','tm'=>'7:30 AM','l'=>'Canal Bank Walking Track','cat'=>'environment','rsvp'=>18,'desc'=>'Help keep the canal-side walking track clean. Gloves and bags provided by the maintenance desk.'],
    ['t'=>'Quran & Calligraphy Workshop','d'=>'2026-06-02','tm'=>'3:00 PM','l'=>'Jamia Masjid Canal View Hall','cat'=>'arts','rsvp'=>14,'desc'=>'Calligraphy session for adults and teenagers, hosted at the Phase 1 masjid hall. All materials provided.'],
    ['t'=>'Morning Yoga at Ladies Park','d'=>'2026-06-05','tm'=>'6:30 AM','l'=>'Ladies Park, Phase 1','cat'=>'health','rsvp'=>22,'desc'=>'Start your day with outdoor yoga at the in-society Ladies Park. Suitable for all levels. Bring your own mat.'],
  ];
  $events_url = esc_url(home_url('/events/'));
  $demos = array_slice($demos, 0, $limit);
  foreach ($demos as $i => $d) {
    $ts  = strtotime($d['d']);
    $bg  = $colors[($offset + $i) % count($colors)];
    $ico = $emojis[$d['cat']] ?? '📅';
    echo '<article class="event-card" data-category="' . esc_attr($d['cat']) . '">';
    echo '<div class="event-card-img" style="background:linear-gradient(135deg,' . esc_attr($bg) . ',' . esc_attr($bg) . 'cc)">';
    echo '<div class="placeholder-icon">' . esc_html($ico) . '</div>';
    echo '<div class="event-date-chip"><div class="date-chip-month">' . esc_html(date('M',$ts)) . '</div><div class="date-chip-day">' . esc_html(date('d',$ts)) . '</div></div>';
    echo '<div class="event-cat-pill">' . esc_html(ucfirst($d['cat'])) . '</div>';
    echo '</div>';
    echo '<div class="event-card-body">';
    echo '<div class="event-card-meta">';
    echo '<div class="meta-item"><i class="fa-regular fa-clock"></i> ' . esc_html($d['tm']) . '</div>';
    echo '<div class="meta-item"><i class="fa-solid fa-location-dot"></i> ' . esc_html($d['l']) . '</div>';
    echo '</div>';
    echo '<h3 class="event-card-title"><a href="' . $events_url . '">' . esc_html($d['t']) . '</a></h3>';
    echo '<p class="event-card-excerpt">' . esc_html($d['desc']) . '</p>';
    echo '<div class="event-card-foot">';
    echo '<div class="going-row"><div class="going-faces">';
    for ($f=0;$f<3;$f++) echo '<div class="going-face" style="background:' . esc_attr($colors[$f % count($colors)]) . ';">' . chr(65+$f) . '</div>';
    echo '</div><span><strong class="rsvp-number">' . esc_html($d['rsvp']) . '</strong> going</span></div>';
    echo '<a href="' . $events_url . '" class="rsvp-btn"><i class="fa-solid fa-plus"></i> RSVP</a>';
    echo '</div></div></article>';
  }
}

function nc_fp_demo_services(array $colors, int $limit = 6, int $offset = 0) {
  $demos = [
    ['n'=>'Plumbing',      'cat'=>'On-call society team','r'=>4.9,'rv'=>47,'p'=>'Request via wizard','i'=>'🔧'],
    ['n'=>'Electrical',    'cat'=>'On-call society team','r'=>4.8,'rv'=>32,'p'=>'Request via wizard','i'=>'💡'],
    ['n'=>'Carpentry',     'cat'=>'On-call society team','r'=>4.7,'rv'=>18,'p'=>'Request via wizard','i'=>'🪚'],
    ['n'=>'Cleaning',      'cat'=>'On-call society team','r'=>4.8,'rv'=>63,'p'=>'Request via wizard','i'=>'🧹'],
    ['n'=>'Pest Control',  'cat'=>'On-call society team','r'=>4.6,'rv'=>25,'p'=>'Request via wizard','i'=>'🐜'],
    ['n'=>'Tiffin / Food', 'cat'=>'Daily home-style meals','r'=>4.9,'rv'=>41,'p'=>'Pay the team directly','i'=>'🍱'],
  ];
  $services_url = esc_url(home_url('/services/'));
  $demos = array_slice($demos, 0, $limit);
  foreach ($demos as $i => $d) {
    $bg  = $colors[($offset + $i) % count($colors)];
    $stars = str_repeat('★', round($d['r'])) . str_repeat('☆', 5-round($d['r']));
    echo '<div class="service-card">';
    echo '<div class="service-card-top">';
    echo '<div class="service-avatar-placeholder" style="background:' . esc_attr($bg) . ';">' . esc_html($d['i']) . '</div>';
    echo '<div class="service-info"><div class="service-name"><a href="' . $services_url . '">' . esc_html($d['n']) . '</a></div><div class="service-cat">' . esc_html($d['cat']) . '</div></div>';
    echo '</div>';
    echo '<div class="service-rating"><span class="stars">' . $stars . '</span><span>' . esc_html($d['r']) . '</span><span>(' . esc_html($d['rv']) . ' residents served)</span></div>';
    echo '<p class="service-desc">In-house team for Canal View Society residents. Trusted by ' . esc_html($d['rv']) . ' neighbours and counting.</p>';
    echo '<div class="service-foot"><div class="service-price">' . esc_html($d['p']) . '</div><a href="' . $services_url . '" class="btn btn-secondary btn-sm">Request</a></div>';
    echo '</div>';
  }
}

function nc_fp_demo_issues(int $limit = 4) {
  $demos = [
    ['t'=>'Broken Streetlight — Phase 1 Boulevard','d'=>'The streetlight has been out for 3 weeks, creating a safety hazard for pedestrians and cyclists at night.','s'=>'open','v'=>23,'l'=>'Phase 1 Main Boulevard, Canal View','ago'=>'2 days'],
    ['t'=>'Pothole on Canal Bank Road','d'=>'Has caused multiple flat tyres near the main gate. Reported to maintenance but no action yet.','s'=>'in-progress','v'=>18,'l'=>'Canal Bank Road, opposite main gate','ago'=>'5 days'],
    ['t'=>'Overflowing Bin at Ladies Park Gate','d'=>'Not emptied in 4 days. Attracting stray cats and creating an unsanitary mess at the park entrance.','s'=>'open','v'=>11,'l'=>'Ladies Park, Phase 1','ago'=>'3 days'],
    ['t'=>'Speed Bump Needed Near Allied School','d'=>'Cars speeding past the in-society school at pick-up time. Multiple near misses reported.','s'=>'resolved','v'=>47,'l'=>'Allied School Campus, Phase 2','ago'=>'1 week'],
  ];
  $pl = ['open'=>'pill-open','in-progress'=>'pill-in-progress','resolved'=>'pill-resolved'];
  $sl = ['open'=>'Open','in-progress'=>'In Progress','resolved'=>'Resolved'];
  $issues_url = esc_url(home_url('/issues/'));
  $demos = array_slice($demos, 0, $limit);
  foreach ($demos as $i => $d) {
    echo '<div class="issue-card">';
    echo '<div class="issue-status status-' . esc_attr($d['s']) . '"></div>';
    echo '<div class="issue-body">';
    echo '<h3 class="issue-title"><a href="' . $issues_url . '">' . esc_html($d['t']) . '</a></h3>';
    echo '<p class="issue-desc">' . esc_html($d['d']) . '</p>';
    echo '<div class="issue-foot">';
    echo '<div class="issue-foot-item"><i class="fa-solid fa-location-dot"></i> ' . esc_html($d['l']) . '</div>';
    echo '<div class="issue-foot-item"><i class="fa-regular fa-clock"></i> ' . esc_html($d['ago']) . ' ago</div>';
    echo '<span class="status-pill ' . esc_attr($pl[$d['s']]) . '">' . esc_html($sl[$d['s']]) . '</span>';
    echo '<button class="vote-btn" data-issue-id="' . esc_attr($i+1) . '"><i class="fa-solid fa-thumbs-up"></i> <span class="vote-count">' . esc_html($d['v']) . '</span></button>';
    echo '</div></div></div>';
  }
}
?>

<?php get_footer(); ?>
