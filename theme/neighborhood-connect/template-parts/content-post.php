<?php
$author_id = get_post_field('post_author', get_the_ID());
$author    = get_userdata($author_id);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('event-card'); ?>>

  <?php if (has_post_thumbnail()) : ?>
    <div class="event-card-image">
      <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
        <?php the_post_thumbnail('nc-card', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
      </a>
      <?php if (get_post_format()) : ?>
        <div class="event-card-badge">
          <span class="badge badge-gray"><?php echo esc_html(ucfirst(get_post_format())); ?></span>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="event-card-content">
    <div class="event-meta">
      <div class="event-meta-item">
        <i class="fa-solid fa-calendar"></i>
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
      </div>
      <?php if ($author) : ?>
        <div class="event-meta-item">
          <i class="fa-solid fa-user"></i>
          <span><?php echo esc_html($author->display_name); ?></span>
        </div>
      <?php endif; ?>
      <?php
      $cats = get_the_category();
      if ($cats) :
      ?>
        <div class="event-meta-item">
          <i class="fa-solid fa-tag"></i>
          <span><?php echo esc_html($cats[0]->name); ?></span>
        </div>
      <?php endif; ?>
    </div>

    <h3 class="event-card-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <p class="event-card-excerpt"><?php the_excerpt(); ?></p>

    <div class="event-card-footer">
      <div class="rsvp-count">
        <i class="fa-regular fa-comment" style="color:var(--color-primary);"></i>
        <?php echo esc_html(get_comments_number()); ?> <?php esc_html_e('comments', 'neighborhood-connect'); ?>
      </div>
      <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">
        <?php esc_html_e('Read More', 'neighborhood-connect'); ?>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>

</article>
