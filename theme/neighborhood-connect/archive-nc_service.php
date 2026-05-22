<?php get_header(); ?>

<div class="archive-hero" style="background:linear-gradient(135deg,#4338ca 0%,#312e81 100%);color:white;padding:var(--s-16) 0 var(--s-12);">
  <div class="container">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.75);">Home</a>
      <span style="margin:0 var(--s-2);color:rgba(255,255,255,.5);">/</span>
      <span style="color:white;" data-i18n="nav-services">Services</span>
    </div>
    <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:var(--fw-bold);margin-bottom:var(--s-3);" data-i18n="services-title">Local Services Directory</h1>
    <p style="font-size:var(--text-lg);opacity:.9;max-width:560px;margin-bottom:var(--s-6);" data-i18n="services-sub">Trusted professionals and businesses from your neighborhood — vetted by your neighbors.</p>

    <div class="archive-search-bar">
      <i class="fa-solid fa-magnifying-glass" style="color:var(--c-sand-400);"></i>
      <input type="search" id="service-search-input" placeholder="Search plumbing, tutoring, cleaning…" class="archive-search-field">
    </div>
  </div>
</div>

<div class="container" style="padding-top:var(--s-10);padding-bottom:var(--s-16);">
  <div class="archive-layout">

    <!-- Sidebar -->
    <aside class="archive-sidebar">
      <div class="sidebar-card">
        <h3 class="sidebar-title">Category</h3>
        <div class="filter-chips" id="category-filter">
          <button class="filter-chip active" data-cat="all">All</button>
          <?php
          $cats = ['Home Services', 'Education', 'Pet Care', 'Cleaning', 'Electrical', 'Beauty', 'Carpentry', 'Gardening', 'Plumbing', 'Tech Support'];
          foreach ($cats as $cat) :
          ?>
          <button class="filter-chip" data-cat="<?php echo esc_attr(strtolower(str_replace(' ', '-', $cat))); ?>"><?php echo esc_html($cat); ?></button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="sidebar-card">
        <h3 class="sidebar-title">Minimum Rating</h3>
        <div class="rating-filter" id="rating-filter">
          <?php for ($r = 5; $r >= 1; $r--) : ?>
          <label class="rating-option">
            <input type="radio" name="min_rating" value="<?php echo $r; ?>" <?php echo $r === 0 ? 'checked' : ''; ?>>
            <span class="stars" style="color:var(--c-gold);">
              <?php for ($s = 1; $s <= 5; $s++) echo $s <= $r ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
            </span>
            <span style="font-size:.85rem;">& up</span>
          </label>
          <?php endfor; ?>
          <label class="rating-option">
            <input type="radio" name="min_rating" value="0" checked>
            <span style="font-size:.85rem;color:var(--c-text-muted);">Any rating</span>
          </label>
        </div>
      </div>

      <div class="sidebar-card">
        <h3 class="sidebar-title">Quick Links</h3>
        <ul class="sidebar-links">
          <li><a href="<?php echo esc_url(home_url('/events/')); ?>"><i class="fa-solid fa-calendar-days"></i> Upcoming Events</a></li>
          <li><a href="<?php echo esc_url(home_url('/issues/')); ?>"><i class="fa-solid fa-triangle-exclamation"></i> Report an Issue</a></li>
          <?php if (is_user_logged_in()) : ?>
          <li><a href="<?php echo esc_url(home_url('/submit-service/')); ?>"><i class="fa-solid fa-plus"></i> List Your Service</a></li>
          <?php else : ?>
          <li><a href="<?php echo esc_url(home_url('/register/')); ?>"><i class="fa-solid fa-plus"></i> List Your Service</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </aside>

    <!-- Main Grid -->
    <div class="archive-main">
      <div class="archive-toolbar">
        <p class="archive-count" id="service-count">
          <?php
          $total_services = wp_count_posts('nc_service')->publish;
          echo esc_html($total_services . ' service' . ($total_services !== 1 ? 's' : '') . ' available');
          ?>
        </p>
        <select class="form-select" id="service-sort" style="width:auto;font-size:.875rem;">
          <option value="rating">Top Rated</option>
          <option value="newest">Newest</option>
          <option value="alpha">A–Z</option>
        </select>
      </div>

      <?php
      $paged = get_query_var('paged') ?: 1;
      $services_query = new WP_Query([
          'post_type'      => 'nc_service',
          'posts_per_page' => 12,
          'paged'          => $paged,
          'post_status'    => 'publish',
          'meta_key'       => '_nc_rating',
          'orderby'        => 'meta_value_num',
          'order'          => 'DESC',
      ]);
      ?>

      <?php if ($services_query->have_posts()) : ?>
      <div class="services-grid" id="services-grid">
        <?php while ($services_query->have_posts()) : $services_query->the_post();
          $sid       = get_the_ID();
          $category  = get_post_meta($sid, '_nc_service_category', true);
          $phone     = get_post_meta($sid, '_nc_phone', true);
          $email     = get_post_meta($sid, '_nc_email', true);
          $rating    = (float) get_post_meta($sid, '_nc_rating', true);
          $reviews   = (int) get_post_meta($sid, '_nc_review_count', true);
          $price     = get_post_meta($sid, '_nc_price', true);
          $badge     = get_post_meta($sid, '_nc_badge', true);
          $cat_slug  = strtolower(str_replace(' ', '-', $category));

          // Icon map
          $icons = [
            'Home Services' => 'fa-house-chimney-window',
            'Education'     => 'fa-graduation-cap',
            'Pet Care'      => 'fa-paw',
            'Cleaning'      => 'fa-broom',
            'Electrical'    => 'fa-bolt',
            'Beauty'        => 'fa-scissors',
            'Carpentry'     => 'fa-hammer',
            'Gardening'     => 'fa-seedling',
            'Plumbing'      => 'fa-wrench',
            'Tech Support'  => 'fa-laptop-code',
          ];
          $icon = $icons[$category] ?? 'fa-briefcase';

          // Color map
          $colors = [
            'Home Services' => '#4f46e5', 'Education' => '#7c3aed', 'Pet Care' => '#059669',
            'Cleaning'      => '#0891b2', 'Electrical'=> '#d97706', 'Beauty'   => '#db2777',
            'Carpentry'     => '#92400e', 'Gardening' => '#16a34a', 'Plumbing' => '#1d4ed8',
            'Tech Support'  => '#475569',
          ];
          $color = $colors[$category] ?? '#4f46e5';
        ?>
        <article class="service-card-v2" data-category="<?php echo esc_attr($cat_slug); ?>" data-rating="<?php echo esc_attr($rating); ?>" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
          <?php if ($badge) : ?>
            <span class="service-badge"><?php echo esc_html($badge); ?></span>
          <?php endif; ?>

          <div class="service-card-header">
            <div class="service-icon-wrap" style="background:<?php echo esc_attr($color); ?>1a;border:2px solid <?php echo esc_attr($color); ?>33;">
              <i class="fa-solid <?php echo esc_attr($icon); ?>" style="color:<?php echo esc_attr($color); ?>;font-size:1.4rem;"></i>
            </div>
            <div class="service-card-meta">
              <span class="service-category-pill" style="background:<?php echo esc_attr($color); ?>1a;color:<?php echo esc_attr($color); ?>;">
                <?php echo esc_html($category ?: 'General'); ?>
              </span>
              <?php if ($price) : ?>
                <span class="service-price"><i class="fa-solid fa-tag" style="font-size:.7rem;"></i> <?php echo esc_html($price); ?></span>
              <?php endif; ?>
            </div>
          </div>

          <h3 class="service-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>

          <p class="service-card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?></p>

          <?php if ($rating > 0) : ?>
          <div class="service-rating-row">
            <span class="stars" style="color:var(--c-gold);font-size:.85rem;">
              <?php echo nc_star_rating($rating); ?>
            </span>
            <strong style="font-size:.95rem;"><?php echo number_format($rating, 1); ?></strong>
            <?php if ($reviews) : ?>
              <span style="color:var(--c-text-muted);font-size:.8rem;">(<?php echo esc_html($reviews); ?> reviews)</span>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <div class="service-card-contacts">
            <?php if ($phone) : ?>
              <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $phone)); ?>" class="service-contact-link">
                <i class="fa-solid fa-phone"></i> <?php echo esc_html($phone); ?>
              </a>
            <?php endif; ?>
            <?php if ($email) : ?>
              <a href="mailto:<?php echo esc_attr($email); ?>" class="service-contact-link">
                <i class="fa-solid fa-envelope"></i> <?php echo esc_html($email); ?>
              </a>
            <?php endif; ?>
          </div>

          <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm service-cta">View Details <i class="fa-solid fa-arrow-right"></i></a>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>

      <!-- Pagination -->
      <?php if ($services_query->max_num_pages > 1) : ?>
      <div class="pagination-wrap">
        <?php echo paginate_links([
          'total'     => $services_query->max_num_pages,
          'current'   => $paged,
          'prev_text' => '<i class="fa-solid fa-arrow-left"></i> Prev',
          'next_text' => 'Next <i class="fa-solid fa-arrow-right"></i>',
        ]); ?>
      </div>
      <?php endif; ?>

      <?php else : ?>
      <div class="empty-state">
        <i class="fa-solid fa-briefcase-blank"></i>
        <h3>No services found</h3>
        <p>Be the first to list a service in your neighborhood!</p>
        <a href="<?php echo esc_url(home_url('/register/')); ?>" class="btn btn-primary">Get Started</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
(function () {
  const cards = Array.from(document.querySelectorAll('.service-card-v2'));
  const searchInput = document.getElementById('service-search-input');
  const categoryBtns = document.querySelectorAll('#category-filter .filter-chip');
  const ratingInputs = document.querySelectorAll('[name="min_rating"]');
  const countEl = document.getElementById('service-count');
  const sortSelect = document.getElementById('service-sort');
  const grid = document.getElementById('services-grid');

  let activeCategory = 'all';
  let minRating = 0;
  let searchTerm = '';

  function filterCards() {
    let visible = 0;
    cards.forEach(card => {
      const cat = card.dataset.category;
      const rating = parseFloat(card.dataset.rating) || 0;
      const title = card.dataset.title || '';
      const excerpt = card.querySelector('.service-card-excerpt')?.textContent.toLowerCase() || '';

      const catMatch = activeCategory === 'all' || cat === activeCategory;
      const ratingMatch = rating >= minRating;
      const searchMatch = !searchTerm || title.includes(searchTerm) || excerpt.includes(searchTerm);

      if (catMatch && ratingMatch && searchMatch) {
        card.style.display = '';
        visible++;
      } else {
        card.style.display = 'none';
      }
    });
    countEl.textContent = visible + ' service' + (visible !== 1 ? 's' : '') + ' available';
  }

  function sortCards() {
    const val = sortSelect.value;
    const sorted = [...cards].sort((a, b) => {
      if (val === 'rating') return (parseFloat(b.dataset.rating) || 0) - (parseFloat(a.dataset.rating) || 0);
      if (val === 'alpha') return a.dataset.title.localeCompare(b.dataset.title);
      return 0;
    });
    sorted.forEach(card => grid.appendChild(card));
  }

  searchInput?.addEventListener('input', function () {
    searchTerm = this.value.toLowerCase().trim();
    filterCards();
  });

  categoryBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      categoryBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      activeCategory = this.dataset.cat;
      filterCards();
    });
  });

  ratingInputs.forEach(input => {
    input.addEventListener('change', function () {
      minRating = parseFloat(this.value) || 0;
      filterCards();
    });
  });

  sortSelect?.addEventListener('change', function () {
    sortCards();
    filterCards();
  });
}());
</script>

<?php get_footer(); ?>
