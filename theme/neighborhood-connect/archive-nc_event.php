<?php get_header(); ?>

<div class="archive-hero" style="background:linear-gradient(135deg,#0e7490 0%,#155e75 100%);color:white;padding:var(--s-16) 0 var(--s-12);">
  <div class="container">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.75);">Home</a>
      <span style="margin:0 var(--s-2);color:rgba(255,255,255,.5);">/</span>
      <span style="color:white;">Events</span>
    </div>
    <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:var(--fw-bold);margin-bottom:var(--s-3);">Community Events</h1>
    <p style="font-size:var(--text-lg);opacity:.9;max-width:560px;margin-bottom:var(--s-6);">Block parties, workshops, markets, and more — all happening in your neighborhood.</p>

    <div class="archive-search-bar">
      <i class="fa-solid fa-magnifying-glass" style="color:var(--c-sand-400);"></i>
      <input type="search" id="event-search-input" placeholder="Search events…" class="archive-search-field">
    </div>
  </div>
</div>

<div class="container" style="padding-top:var(--s-8);padding-bottom:var(--s-16);">

  <!-- Category Filter Tabs -->
  <div class="event-filter-bar">
    <button class="event-filter-btn active" data-cat="all">
      <i class="fa-solid fa-th-large"></i> All Events
    </button>
    <?php
    $event_cats = ['Community', 'Sports', 'Education', 'Arts', 'Food & Drink', 'Health', 'Environment'];
    foreach ($event_cats as $cat) :
    $icons2 = [
      'Community' => 'fa-users', 'Sports' => 'fa-futbol', 'Education' => 'fa-graduation-cap',
      'Arts' => 'fa-palette', 'Food & Drink' => 'fa-utensils', 'Health' => 'fa-heart-pulse',
      'Environment' => 'fa-leaf',
    ];
    $icon2 = $icons2[$cat] ?? 'fa-calendar';
    ?>
    <button class="event-filter-btn" data-cat="<?php echo esc_attr(strtolower(str_replace([' ', '&', '/'], ['-', '', ''], $cat))); ?>">
      <i class="fa-solid <?php echo esc_attr($icon2); ?>"></i> <?php echo esc_html($cat); ?>
    </button>
    <?php endforeach; ?>
  </div>

  <!-- Sort + Count Bar -->
  <div class="archive-toolbar" style="margin-bottom:var(--s-6);">
    <p class="archive-count" id="event-count">
      <?php
      $total_events = wp_count_posts('nc_event')->publish;
      echo esc_html($total_events . ' event' . ($total_events !== 1 ? 's' : '') . ' found');
      ?>
    </p>
    <select class="form-select" id="event-sort" style="width:auto;font-size:.875rem;">
      <option value="date-asc">Soonest First</option>
      <option value="date-desc">Latest First</option>
      <option value="alpha">A–Z</option>
    </select>
  </div>

  <?php
  $paged = get_query_var('paged') ?: 1;
  $events_query = new WP_Query([
      'post_type'      => 'nc_event',
      'posts_per_page' => 12,
      'paged'          => $paged,
      'post_status'    => 'publish',
      'meta_key'       => '_nc_event_date',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
  ]);
  ?>

  <?php if ($events_query->have_posts()) : ?>
  <div class="events-grid-full" id="events-grid">
    <?php while ($events_query->have_posts()) : $events_query->the_post();
      $eid      = get_the_ID();
      $meta     = nc_get_event_meta($eid);
      $category = $meta['category'] ?: 'Community';
      $rsvps    = count($meta['rsvps']);
      $capacity = $meta['capacity'];
      $user_rsvpd = is_user_logged_in() && nc_user_has_rsvpd($eid, get_current_user_id());

      // Parse date
      $date_ts = $meta['date'] ? strtotime($meta['date']) : 0;
      $day     = $date_ts ? date('d', $date_ts) : '';
      $mon     = $date_ts ? date('M', $date_ts) : '';
      $dow     = $date_ts ? date('D', $date_ts) : '';

      $cat_slug = strtolower(str_replace([' ', '&', '/'], ['-', '', ''], $category));
      $is_past  = $date_ts && $date_ts < time();

      // Category colors
      $cat_colors = [
        'community' => '#4f46e5', 'sports' => '#059669', 'education' => '#7c3aed',
        'arts' => '#db2777', 'fooddrink' => '#d97706', 'health' => '#0891b2', 'environment' => '#16a34a',
      ];
      $cat_color = $cat_colors[str_replace('-', '', $cat_slug)] ?? '#4f46e5';

      $pct = $capacity > 0 ? min(100, round($rsvps / $capacity * 100)) : 0;
    ?>
    <article class="event-card-full <?php echo $is_past ? 'past-event' : ''; ?>"
             data-category="<?php echo esc_attr($cat_slug); ?>"
             data-date="<?php echo esc_attr($meta['date']); ?>"
             data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">

      <div class="event-card-date-badge" style="background:<?php echo esc_attr($cat_color); ?>;">
        <span class="event-date-day"><?php echo esc_html($day); ?></span>
        <span class="event-date-mon"><?php echo esc_html($mon); ?></span>
      </div>

      <div class="event-card-body">
        <div class="event-card-top">
          <span class="event-cat-pill" style="background:<?php echo esc_attr($cat_color); ?>1a;color:<?php echo esc_attr($cat_color); ?>;">
            <?php echo esc_html($category); ?>
          </span>
          <?php if ($is_past) : ?>
            <span class="event-status-pill past">Past</span>
          <?php endif; ?>
          <?php if ($capacity > 0 && $rsvps >= $capacity) : ?>
            <span class="event-status-pill full">Full</span>
          <?php endif; ?>
        </div>

        <h3 class="event-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="event-card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>

        <div class="event-card-details">
          <?php if ($meta['date']) : ?>
          <span class="event-detail"><i class="fa-solid fa-calendar"></i> <?php echo esc_html($dow . ', ' . date('M j, Y', $date_ts)); ?></span>
          <?php endif; ?>
          <?php if ($meta['time']) : ?>
          <span class="event-detail"><i class="fa-solid fa-clock"></i> <?php echo esc_html($meta['time']); ?><?php if ($meta['end_time']) echo ' – ' . esc_html($meta['end_time']); ?></span>
          <?php endif; ?>
          <?php if ($meta['location']) : ?>
          <span class="event-detail"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($meta['location']); ?></span>
          <?php endif; ?>
        </div>

        <?php if ($capacity > 0) : ?>
        <div class="event-capacity-bar">
          <div class="event-capacity-track">
            <div class="event-capacity-fill" style="width:<?php echo esc_attr($pct); ?>%;background:<?php echo esc_attr($cat_color); ?>;"></div>
          </div>
          <span class="event-capacity-label"><?php echo esc_html($rsvps . ' / ' . $capacity . ' spots'); ?></span>
        </div>
        <?php endif; ?>

        <div class="event-card-footer">
          <?php if ($rsvps > 0) : ?>
          <span class="event-going"><i class="fa-solid fa-user-check"></i> <?php echo esc_html($rsvps); ?> going</span>
          <?php endif; ?>

          <div class="event-card-actions">
            <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-sm">Details</a>
            <?php if (!$is_past) : ?>
              <?php if (is_user_logged_in()) : ?>
              <button class="btn btn-sm rsvp-btn <?php echo $user_rsvpd ? 'btn-success rsvpd' : 'btn-primary'; ?>"
                      data-event-id="<?php echo esc_attr($eid); ?>"
                      data-type="<?php echo $user_rsvpd ? 'cancel' : 'join'; ?>">
                <?php echo $user_rsvpd ? '<i class="fa-solid fa-check"></i> Going' : '<i class="fa-solid fa-plus"></i> RSVP'; ?>
              </button>
              <?php else : ?>
              <a href="<?php echo esc_url(home_url('/login/')); ?>" class="btn btn-primary btn-sm">RSVP</a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>

  <!-- Pagination -->
  <?php if ($events_query->max_num_pages > 1) : ?>
  <div class="pagination-wrap">
    <?php echo paginate_links([
      'total'     => $events_query->max_num_pages,
      'current'   => $paged,
      'prev_text' => '<i class="fa-solid fa-arrow-left"></i> Prev',
      'next_text' => 'Next <i class="fa-solid fa-arrow-right"></i>',
    ]); ?>
  </div>
  <?php endif; ?>

  <?php else : ?>
  <div class="empty-state">
    <i class="fa-solid fa-calendar-xmark"></i>
    <h3>No events found</h3>
    <p>Check back soon for upcoming neighborhood events!</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">Back to Home</a>
  </div>
  <?php endif; ?>

</div>

<script>
(function () {
  const cards = Array.from(document.querySelectorAll('.event-card-full'));
  const searchInput = document.getElementById('event-search-input');
  const filterBtns = document.querySelectorAll('.event-filter-btn');
  const sortSelect = document.getElementById('event-sort');
  const countEl = document.getElementById('event-count');
  const grid = document.getElementById('events-grid');

  let activeCategory = 'all';
  let searchTerm = '';

  function filterCards() {
    let visible = 0;
    cards.forEach(card => {
      const cat = card.dataset.category;
      const title = card.dataset.title || '';
      const excerpt = card.querySelector('.event-card-excerpt')?.textContent.toLowerCase() || '';
      const catMatch = activeCategory === 'all' || cat === activeCategory;
      const searchMatch = !searchTerm || title.includes(searchTerm) || excerpt.includes(searchTerm);

      if (catMatch && searchMatch) { card.style.display = ''; visible++; }
      else card.style.display = 'none';
    });
    countEl.textContent = visible + ' event' + (visible !== 1 ? 's' : '') + ' found';
  }

  function sortCards() {
    const val = sortSelect.value;
    const sorted = [...cards].sort((a, b) => {
      if (val === 'alpha') return a.dataset.title.localeCompare(b.dataset.title);
      const da = a.dataset.date || '';
      const db = b.dataset.date || '';
      return val === 'date-asc' ? da.localeCompare(db) : db.localeCompare(da);
    });
    sorted.forEach(card => grid.appendChild(card));
  }

  searchInput?.addEventListener('input', function () { searchTerm = this.value.toLowerCase().trim(); filterCards(); });
  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      activeCategory = this.dataset.cat;
      filterCards();
    });
  });
  sortSelect?.addEventListener('change', function () { sortCards(); filterCards(); });
}());
</script>

<?php get_footer(); ?>
