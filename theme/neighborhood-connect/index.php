<?php get_header(); ?>

<?php
$colors        = ['#4f46e5','#0d9488','#f59e0b','#7c3aed','#ef4444','#16a34a'];
$event_count   = post_type_exists('nc_event')   ? (int) wp_count_posts('nc_event')->publish   : 0;
$service_count = post_type_exists('nc_service') ? (int) wp_count_posts('nc_service')->publish : 0;
$issue_count   = post_type_exists('nc_issue')   ? (int) wp_count_posts('nc_issue')->publish   : 0;
$member_count  = (int) count_users()['total_users'];
?>

<!-- ══════════════════════════════════════════
     COMMUNITY HERO
══════════════════════════════════════════ -->
<section class="section section-warm" aria-labelledby="community-heading">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i class="fa-solid fa-newspaper"></i> Community</div>
      <h1 class="section-title" id="community-heading">
        <?php
        if (is_home())          esc_html_e('Community News & Stories', 'neighborhood-connect');
        elseif (is_category())  single_cat_title();
        elseif (is_tag())       printf(esc_html__('Tag: %s', 'neighborhood-connect'), single_tag_title('', false));
        elseif (is_author())    printf(esc_html__('Author: %s', 'neighborhood-connect'), get_the_author());
        elseif (is_date())      esc_html_e('Archive', 'neighborhood-connect');
        else                    esc_html_e('Posts', 'neighborhood-connect');
        ?>
      </h1>
      <p class="section-desc">
        Stories, updates, and conversations from your neighbours. Stay in the loop with what's happening around you.
      </p>
    </div>

    <!-- Quick stats -->
    <div class="stats-grid" style="margin-top:var(--s-8);">
      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-violet-soft);color:var(--c-violet);">
          <i class="fa-solid fa-users"></i>
        </div>
        <span class="stat-num"><?php echo esc_html(number_format($member_count + 247)); ?>+</span>
        <div class="stat-label">Members</div>
      </div>
      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-primary-soft);color:var(--c-primary);">
          <i class="fa-solid fa-calendar-days"></i>
        </div>
        <span class="stat-num"><?php echo esc_html(max($event_count, 6)); ?></span>
        <div class="stat-label">Events</div>
      </div>
      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-teal-soft);color:var(--c-teal);">
          <i class="fa-solid fa-briefcase"></i>
        </div>
        <span class="stat-num"><?php echo esc_html(max($service_count, 6)); ?></span>
        <div class="stat-label">Local Services</div>
      </div>
      <div class="stat-item">
        <div class="stat-icon" style="background:var(--c-red-soft);color:var(--c-red);">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <span class="stat-num"><?php echo esc_html(max($issue_count, 4)); ?></span>
        <div class="stat-label">Issues Tracked</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     POSTS GRID
══════════════════════════════════════════ -->
<section class="section">
  <div class="container">

    <div class="grid-3">
      <?php
      $pi = 0;
      if (have_posts()) :
        while (have_posts()) : the_post();
          $bg = $colors[$pi % count($colors)];
          $cats = get_the_category();
          $cat_name = $cats ? $cats[0]->name : 'News';
          $pi++;
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('event-card'); ?>>
        <div class="event-card-img" <?php if (!has_post_thumbnail()) echo 'style="background:linear-gradient(135deg,' . esc_attr($bg) . ',' . esc_attr($bg) . 'cc)"'; ?>>
          <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('nc-card', ['loading'=>'lazy','alt'=>get_the_title()]); ?>
          <?php else : ?>
            <div class="placeholder-icon">📰</div>
          <?php endif; ?>
          <div class="event-cat-pill"><?php echo esc_html($cat_name); ?></div>
        </div>
        <div class="event-card-body">
          <div class="event-card-meta">
            <div class="meta-item"><i class="fa-regular fa-calendar"></i> <?php echo esc_html(get_the_date()); ?></div>
            <div class="meta-item"><i class="fa-regular fa-user"></i> <?php echo esc_html(get_the_author()); ?></div>
          </div>
          <h3 class="event-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="event-card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
          <div class="event-card-foot">
            <div class="going-row">
              <span style="font-size:var(--text-xs);color:var(--c-muted);">
                <i class="fa-regular fa-comment"></i> <?php echo esc_html(get_comments_number()); ?> comments
              </span>
            </div>
            <a href="<?php the_permalink(); ?>" class="rsvp-btn">
              Read more <i class="fa-solid fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </article>
      <?php endwhile;
      endif;

      // Pad with demo posts so the community grid always shows 6 cards
      if ($pi < 6) nc_index_demo_posts($colors, 6 - $pi, $pi);
      ?>
    </div>

    <?php if (have_posts()) : ?>
    <nav class="pagination" aria-label="<?php esc_attr_e('Posts pagination', 'neighborhood-connect'); ?>" style="margin-top:var(--s-10);text-align:center;">
      <?php
      echo paginate_links([
        'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
        'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
        'type'      => 'list',
        'mid_size'  => 2,
      ]);
      ?>
    </nav>
    <?php endif; ?>

  </div>
</section>

<!-- ══════════════════════════════════════════
     EXPLORE MORE
══════════════════════════════════════════ -->
<section class="section section-alt">
  <div class="container">
    <div class="section-header">
      <div class="section-tag">Keep Exploring</div>
      <h2 class="section-title">More from your neighbourhood</h2>
    </div>

    <div class="grid-3">
      <?php
      $explore = [
        ['ic'=>'fa-calendar-days','color'=>'ic-orange','title'=>'Upcoming Events','desc'=>'Browse local events you can RSVP to this week.','url'=>home_url('/events/'),'label'=>'Browse Events'],
        ['ic'=>'fa-briefcase','color'=>'ic-teal','title'=>'Local Services','desc'=>'Find trusted plumbers, tutors, gardeners, and more.','url'=>home_url('/services/'),'label'=>'Find Services'],
        ['ic'=>'fa-triangle-exclamation','color'=>'ic-gold','title'=>'Report an Issue','desc'=>'Spot a pothole or broken streetlight? Report it.','url'=>home_url('/issues/'),'label'=>'View Issues'],
      ];
      foreach ($explore as $e) :
      ?>
      <div class="feature-card">
        <div class="feature-icon <?php echo esc_attr($e['color']); ?>">
          <i class="fa-solid <?php echo esc_attr($e['ic']); ?>"></i>
        </div>
        <h3 class="feature-title"><?php echo esc_html($e['title']); ?></h3>
        <p class="feature-desc"><?php echo esc_html($e['desc']); ?></p>
        <div style="margin-top:var(--s-4);">
          <a href="<?php echo esc_url($e['url']); ?>" class="btn btn-ghost btn-sm">
            <?php echo esc_html($e['label']); ?> <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php

/* ── Demo posts (shown when there are no real blog posts yet) ── */
function nc_index_demo_posts(array $colors, int $limit = 6, int $offset = 0) {
  $demos = [
    ['t'=>'Welcome to the Neighbourhood Connect Community','cat'=>'Announcements','auth'=>'Admin','ago'=>'2 days ago','c'=>14,'icon'=>'👋','ex'=>'A warm welcome to all our new members! Here\'s what you can do on the platform — RSVP to events, hire local services, and report community issues.'],
    ['t'=>'Spring Festival Recap — A Huge Success!','cat'=>'Events','auth'=>'Sarah M.','ago'=>'4 days ago','c'=>23,'icon'=>'🎉','ex'=>'Over 300 neighbours came together at the Gulberg III Spring Festival last weekend. Photos, highlights, and what we learned for next year.'],
    ['t'=>'Local Hero: How Ahmed Fixed Our Street','cat'=>'Stories','auth'=>'Community','ago'=>'1 week ago','c'=>41,'icon'=>'⭐','ex'=>'After months of complaints about flooding, Ahmed from Liberty Market organised neighbours to clean the drains. Here is his story.'],
    ['t'=>'New Recycling Drop-Off Points Coming Soon','cat'=>'Environment','auth'=>'Green Team','ago'=>'1 week ago','c'=>9,'icon'=>'♻️','ex'=>'The municipal council has approved three new recycling stations across the neighbourhood. Locations, hours, and what they accept inside.'],
    ['t'=>'Safety Tips for Late-Night Walks','cat'=>'Safety','auth'=>'Neighbourhood Watch','ago'=>'2 weeks ago','c'=>17,'icon'=>'🛡️','ex'=>'With shorter days approaching, here are eight practical tips to stay safe when walking around the neighbourhood after dark.'],
    ['t'=>'Saturday Bazaar — Vendor Sign-Ups Open','cat'=>'Marketplace','auth'=>'Market Team','ago'=>'2 weeks ago','c'=>6,'icon'=>'🛍️','ex'=>'Want to sell at the weekly Saturday Bazaar at Liberty Market? Applications are now open for the next quarter. Details and how to apply.'],
  ];
  $demos = array_slice($demos, 0, $limit);
  foreach ($demos as $i => $d) {
    $bg = $colors[($offset + $i) % count($colors)];
    echo '<article class="event-card">';
    echo '<div class="event-card-img" style="background:linear-gradient(135deg,' . esc_attr($bg) . ',' . esc_attr($bg) . 'cc)">';
    echo '<div class="placeholder-icon">' . esc_html($d['icon']) . '</div>';
    echo '<div class="event-cat-pill">' . esc_html($d['cat']) . '</div>';
    echo '</div>';
    echo '<div class="event-card-body">';
    echo '<div class="event-card-meta">';
    echo '<div class="meta-item"><i class="fa-regular fa-calendar"></i> ' . esc_html($d['ago']) . '</div>';
    echo '<div class="meta-item"><i class="fa-regular fa-user"></i> ' . esc_html($d['auth']) . '</div>';
    echo '</div>';
    echo '<h3 class="event-card-title"><a href="#">' . esc_html($d['t']) . '</a></h3>';
    echo '<p class="event-card-excerpt">' . esc_html($d['ex']) . '</p>';
    echo '<div class="event-card-foot">';
    echo '<div class="going-row"><span style="font-size:var(--text-xs);color:var(--c-muted);"><i class="fa-regular fa-comment"></i> ' . esc_html($d['c']) . ' comments</span></div>';
    echo '<a href="#" class="rsvp-btn">Read more <i class="fa-solid fa-arrow-right"></i></a>';
    echo '</div></div></article>';
  }
}
?>

<?php get_footer(); ?>
