<?php
$meta       = nc_get_event_meta(get_the_ID());
$ts         = $meta['date'] ? strtotime($meta['date']) : null;
$rsvp_count = count($meta['rsvps']);
$user_rsvpd = is_user_logged_in() && nc_user_has_rsvpd(get_the_ID(), get_current_user_id());
$category   = $meta['category'] ?: 'Event';
$colors     = ['#2563eb','#059669','#d97706','#dc2626','#7c3aed'];
$card_color = $colors[get_the_ID() % count($colors)];
?>

<div class="event-card" data-category="<?php echo esc_attr($category); ?>">

  <div class="event-card-image" <?php if (!has_post_thumbnail()) echo 'style="background:' . esc_attr($card_color) . ';"'; ?>>
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('nc-card', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
    <?php else : ?>
      <div class="no-image"><i class="fa-solid fa-calendar-days"></i></div>
    <?php endif; ?>

    <div class="event-card-badge">
      <span class="badge badge-primary"><?php echo esc_html($category); ?></span>
    </div>
  </div>

  <div class="event-card-content">
    <div class="event-meta">
      <?php if ($ts) : ?>
        <div class="event-meta-item">
          <i class="fa-solid fa-calendar"></i>
          <span><?php echo esc_html(date_i18n(get_option('date_format'), $ts)); ?></span>
        </div>
      <?php endif; ?>
      <?php if ($meta['time']) : ?>
        <div class="event-meta-item">
          <i class="fa-solid fa-clock"></i>
          <span><?php echo esc_html($meta['time']); ?></span>
        </div>
      <?php endif; ?>
      <?php if ($meta['location']) : ?>
        <div class="event-meta-item">
          <i class="fa-solid fa-location-dot"></i>
          <span><?php echo esc_html($meta['location']); ?></span>
        </div>
      <?php endif; ?>
    </div>

    <h3 class="event-card-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <p class="event-card-excerpt"><?php the_excerpt(); ?></p>

    <div class="event-card-footer">
      <div class="rsvp-count">
        <?php if ($rsvp_count > 0) : ?>
          <div class="rsvp-avatars">
            <?php
            $colors_list = ['#2563eb','#059669','#d97706','#7c3aed','#db2777'];
            for ($i = 0; $i < min(3, $rsvp_count); $i++) :
            ?>
              <div class="rsvp-avatar" style="background:<?php echo esc_attr($colors_list[$i % count($colors_list)]); ?>;">
                <?php echo esc_html(chr(65 + $i)); ?>
              </div>
            <?php endfor; ?>
          </div>
        <?php endif; ?>
        <span>
          <span class="rsvp-number"><?php echo esc_html($rsvp_count); ?></span>
          <?php esc_html_e('going', 'neighborhood-connect'); ?>
        </span>
      </div>

      <?php if (is_user_logged_in()) : ?>
        <button
          class="rsvp-btn<?php echo $user_rsvpd ? ' rsvp-joined' : ''; ?>"
          data-event-id="<?php echo esc_attr(get_the_ID()); ?>"
          aria-label="<?php echo $user_rsvpd ? esc_attr__('Cancel RSVP', 'neighborhood-connect') : esc_attr__('RSVP to this event', 'neighborhood-connect'); ?>"
        >
          <?php if ($user_rsvpd) : ?>
            <i class="fa-solid fa-check"></i> <?php esc_html_e('Joined', 'neighborhood-connect'); ?>
          <?php else : ?>
            <i class="fa-solid fa-plus"></i> <?php esc_html_e('RSVP', 'neighborhood-connect'); ?>
          <?php endif; ?>
        </button>
      <?php else : ?>
        <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="rsvp-btn">
          <i class="fa-solid fa-plus"></i> <?php esc_html_e('RSVP', 'neighborhood-connect'); ?>
        </a>
      <?php endif; ?>
    </div>
  </div>

</div>
