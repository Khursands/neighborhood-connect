<?php
$category = get_post_meta(get_the_ID(), '_nc_service_category', true) ?: __('Service', 'neighborhood-connect');
$price    = get_post_meta(get_the_ID(), '_nc_price', true);
$rating   = (float) get_post_meta(get_the_ID(), '_nc_rating', true) ?: 4.5;
$reviews  = (int) get_post_meta(get_the_ID(), '_nc_review_count', true) ?: 0;
$tags     = get_post_meta(get_the_ID(), '_nc_tags', true) ?: [];
$author   = get_userdata(get_post_field('post_author', get_the_ID()));
$initials = $author ? nc_avatar_initials($author->display_name) : '?';
$colors   = ['#2563eb','#059669','#d97706','#dc2626','#7c3aed','#db2777'];
$color    = $colors[get_the_ID() % count($colors)];
?>

<div class="service-card">

  <div class="service-card-header">
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail('nc-avatar', ['class' => 'service-avatar', 'loading' => 'lazy', 'alt' => get_the_title()]); ?>
    <?php else : ?>
      <div class="service-avatar-placeholder" style="background:<?php echo esc_attr($color); ?>;">
        <?php echo esc_html($initials); ?>
      </div>
    <?php endif; ?>

    <div class="service-info">
      <div class="service-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
      <div class="service-category"><?php echo esc_html($category); ?></div>
    </div>
  </div>

  <div class="service-rating">
    <?php echo wp_kses_post(nc_star_rating($rating)); ?>
    <span><?php echo esc_html(number_format($rating, 1)); ?></span>
    <?php if ($reviews > 0) : ?>
      <span>(<?php echo esc_html($reviews); ?> <?php esc_html_e('reviews', 'neighborhood-connect'); ?>)</span>
    <?php endif; ?>
  </div>

  <p class="service-description"><?php the_excerpt(); ?></p>

  <?php if (!empty($tags)) : ?>
    <div class="service-tags">
      <?php foreach ((array) $tags as $tag) : ?>
        <span class="tag"><?php echo esc_html($tag); ?></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="service-footer">
    <div class="service-price">
      <?php if ($price) : ?>
        <?php echo esc_html($price); ?>
        <span class="price-label"><?php esc_html_e('starting', 'neighborhood-connect'); ?></span>
      <?php else : ?>
        <?php esc_html_e('Contact for pricing', 'neighborhood-connect'); ?>
      <?php endif; ?>
    </div>
    <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
      <?php esc_html_e('Contact', 'neighborhood-connect'); ?>
    </a>
  </div>

</div>
