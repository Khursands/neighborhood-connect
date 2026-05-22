<?php get_header(); ?>

<?php
the_post();
$eid       = get_the_ID();
$meta      = nc_get_event_meta($eid);
$rsvps     = $meta['rsvps'];
$rsvp_count= count($rsvps);
$capacity  = $meta['capacity'];
$user_id   = get_current_user_id();
$user_rsvpd= is_user_logged_in() && nc_user_has_rsvpd($eid, $user_id);
$pct       = $capacity > 0 ? min(100, round($rsvp_count / $capacity * 100)) : 0;
$date_ts   = $meta['date'] ? strtotime($meta['date']) : 0;
$is_past   = $date_ts && $date_ts < time();
$category  = $meta['category'] ?: 'Community';
?>

<!-- Event Hero -->
<div class="single-hero event-single-hero">
  <div class="container">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
      <span>/</span>
      <a href="<?php echo esc_url(home_url('/events/')); ?>">Events</a>
      <span>/</span>
      <span><?php the_title(); ?></span>
    </div>

    <div class="single-hero-grid">
      <div class="single-hero-content">
        <span class="event-cat-pill" style="margin-bottom:var(--s-3);display:inline-block;"><?php echo esc_html($category); ?></span>
        <?php if ($is_past) : ?><span class="event-status-pill past" style="margin-left:var(--s-2);">Past Event</span><?php endif; ?>
        <h1 class="single-title"><?php the_title(); ?></h1>
        <div class="event-meta-grid">
          <?php if ($meta['date']) : ?>
          <div class="event-meta-item">
            <i class="fa-solid fa-calendar-days"></i>
            <div>
              <strong>Date</strong>
              <span><?php echo esc_html(date('l, F j, Y', $date_ts)); ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($meta['time']) : ?>
          <div class="event-meta-item">
            <i class="fa-solid fa-clock"></i>
            <div>
              <strong>Time</strong>
              <span><?php echo esc_html($meta['time']); ?><?php if ($meta['end_time']) echo ' – ' . esc_html($meta['end_time']); ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($meta['location']) : ?>
          <div class="event-meta-item">
            <i class="fa-solid fa-location-dot"></i>
            <div>
              <strong>Location</strong>
              <span><?php echo esc_html($meta['location']); ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($capacity > 0) : ?>
          <div class="event-meta-item">
            <i class="fa-solid fa-users"></i>
            <div>
              <strong>Capacity</strong>
              <span><?php echo esc_html($rsvp_count . ' / ' . $capacity . ' spots'); ?></span>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- RSVP Card -->
      <div class="event-rsvp-card">
        <?php if ($date_ts) : ?>
        <div class="event-big-date">
          <span class="event-date-day" style="font-size:3rem;font-weight:var(--fw-bold);color:var(--c-primary);line-height:1;"><?php echo date('d', $date_ts); ?></span>
          <span style="font-size:1.1rem;font-weight:500;color:var(--c-text-muted);"><?php echo date('F Y', $date_ts); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($capacity > 0) : ?>
        <div class="rsvp-progress">
          <div style="display:flex;justify-content:space-between;margin-bottom:var(--s-2);">
            <span style="font-size:.875rem;font-weight:500;"><?php echo esc_html($rsvp_count); ?> going</span>
            <span style="font-size:.875rem;color:var(--c-text-muted);"><?php echo esc_html($capacity - $rsvp_count); ?> spots left</span>
          </div>
          <div class="event-capacity-track" style="height:8px;">
            <div class="event-capacity-fill" style="width:<?php echo esc_attr($pct); ?>%;"></div>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!$is_past) : ?>
          <?php if (is_user_logged_in()) : ?>
          <button class="btn btn-primary btn-full rsvp-btn <?php echo $user_rsvpd ? 'btn-success rsvpd' : ''; ?>"
                  id="single-rsvp-btn"
                  data-event-id="<?php echo esc_attr($eid); ?>"
                  data-type="<?php echo $user_rsvpd ? 'cancel' : 'join'; ?>">
            <?php if ($user_rsvpd) : ?>
              <i class="fa-solid fa-check"></i> You're Going! (Cancel)
            <?php else : ?>
              <i class="fa-solid fa-calendar-plus"></i> RSVP — I'll Be There
            <?php endif; ?>
          </button>
          <?php else : ?>
          <a href="<?php echo esc_url(home_url('/login/')); ?>" class="btn btn-primary btn-full">
            <i class="fa-solid fa-calendar-plus"></i> Log In to RSVP
          </a>
          <?php endif; ?>
        <?php else : ?>
          <p style="text-align:center;color:var(--c-text-muted);font-size:.9rem;padding:var(--s-3) 0;">This event has already taken place.</p>
        <?php endif; ?>

        <?php if ($date_ts) : ?>
        <a href="<?php echo esc_url(home_url('/events/' . $eid . '/ical')); ?>" class="btn btn-ghost btn-full btn-sm" style="margin-top:var(--s-3);">
          <i class="fa-solid fa-calendar-arrow-down"></i> Add to Calendar
        </a>
        <?php endif; ?>

        <?php if ($rsvp_count > 0) : ?>
        <div class="attendee-faces" style="margin-top:var(--s-4);padding-top:var(--s-4);border-top:1px solid var(--c-border);">
          <p style="font-size:.8rem;color:var(--c-text-muted);margin-bottom:var(--s-2);">Who's going</p>
          <div style="display:flex;flex-wrap:wrap;gap:var(--s-1);">
            <?php
            $sample = array_slice($rsvps, 0, 8);
            foreach ($sample as $rid) {
                $rname = get_userdata($rid);
                if ($rname) {
                    $display = $rname->display_name;
                    $initials = nc_avatar_initials($display);
                    $bg = nc_avatar_color($rid);
                    echo '<div class="avatar-initials" title="' . esc_attr($display) . '" style="background:' . esc_attr($bg) . ';width:32px;height:32px;font-size:.65rem;">' . esc_html($initials) . '</div>';
                }
            }
            if ($rsvp_count > 8) {
                echo '<div class="avatar-initials" style="background:var(--c-sand-200);color:var(--c-text-muted);width:32px;height:32px;font-size:.65rem;">+' . ($rsvp_count - 8) . '</div>';
            }
            ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Event Body -->
<div class="container" style="padding-top:var(--s-10);padding-bottom:var(--s-16);">
  <div class="single-body-grid">
    <div class="single-body-main">
      <?php if (has_post_thumbnail()) : ?>
      <figure class="single-featured-img">
        <?php the_post_thumbnail('nc-hero', ['class' => 'w-full', 'style' => 'border-radius:var(--radius-xl);width:100%;height:320px;object-fit:cover;']); ?>
      </figure>
      <?php endif; ?>

      <div class="post-content" style="margin-top:var(--s-6);">
        <?php the_content(); ?>
      </div>

      <?php comments_template(); ?>
    </div>

    <!-- Sidebar -->
    <aside class="single-sidebar">
      <!-- Organizer -->
      <div class="sidebar-card">
        <h4 class="sidebar-title">Organizer</h4>
        <?php
        $author_id = get_post_field('post_author', $eid);
        $author = get_userdata($author_id);
        if ($author) :
          $initials = nc_avatar_initials($author->display_name);
          $bg = nc_avatar_color($author_id);
        ?>
        <div style="display:flex;align-items:center;gap:var(--s-3);">
          <div class="avatar-initials" style="background:<?php echo esc_attr($bg); ?>;width:44px;height:44px;flex-shrink:0;"><?php echo esc_html($initials); ?></div>
          <div>
            <strong><?php echo esc_html($author->display_name); ?></strong>
            <p style="font-size:.8rem;color:var(--c-text-muted);margin:0;">Event Organizer</p>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Share -->
      <div class="sidebar-card">
        <h4 class="sidebar-title">Share This Event</h4>
        <div style="display:flex;gap:var(--s-2);flex-wrap:wrap;">
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm" style="flex:1;min-width:80px;text-align:center;">
            <i class="fa-brands fa-facebook-f"></i> Facebook
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm" style="flex:1;min-width:80px;text-align:center;">
            <i class="fa-brands fa-x-twitter"></i> Twitter
          </a>
        </div>
        <button onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>').then(() => NcToast.show('Link copied!','success'))"
                class="btn btn-ghost btn-sm btn-full" style="margin-top:var(--s-2);">
          <i class="fa-solid fa-link"></i> Copy Link
        </button>
      </div>

      <!-- More Events -->
      <div class="sidebar-card">
        <h4 class="sidebar-title">More Events</h4>
        <?php
        $related = new WP_Query([
          'post_type'      => 'nc_event',
          'posts_per_page' => 3,
          'post__not_in'   => [$eid],
          'post_status'    => 'publish',
          'meta_key'       => '_nc_event_date',
          'orderby'        => 'meta_value',
          'order'          => 'ASC',
        ]);
        while ($related->have_posts()) : $related->the_post();
          $r_date = get_post_meta(get_the_ID(), '_nc_event_date', true);
          $r_ts   = $r_date ? strtotime($r_date) : 0;
        ?>
        <div style="padding:var(--s-3) 0;border-bottom:1px solid var(--c-border);">
          <a href="<?php the_permalink(); ?>" style="font-weight:500;font-size:.9rem;color:var(--c-text);text-decoration:none;"><?php the_title(); ?></a>
          <?php if ($r_ts) : ?>
          <p style="font-size:.8rem;color:var(--c-text-muted);margin:var(--s-1) 0 0;"><?php echo esc_html(date('M j', $r_ts)); ?></p>
          <?php endif; ?>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
        <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn btn-ghost btn-sm btn-full" style="margin-top:var(--s-3);">View All Events</a>
      </div>
    </aside>
  </div>
</div>

<?php get_footer(); ?>
