<?php
$status   = get_post_meta(get_the_ID(), '_nc_status', true) ?: 'open';
$votes    = (int) get_post_meta(get_the_ID(), '_nc_votes', true);
$location = get_post_meta(get_the_ID(), '_nc_location', true);
$user_voted = is_user_logged_in() && in_array(get_current_user_id(), get_post_meta(get_the_ID(), '_nc_voters', true) ?: []);

$status_classes = ['open' => 'status-open badge-danger', 'in-progress' => 'status-in-progress badge-warning', 'resolved' => 'status-resolved badge-success'];
$status_labels  = ['open' => __('Open', 'neighborhood-connect'), 'in-progress' => __('In Progress', 'neighborhood-connect'), 'resolved' => __('Resolved', 'neighborhood-connect')];
?>

<div class="issue-card">

  <div class="issue-status-dot status-<?php echo esc_attr($status); ?>"></div>

  <div class="issue-content">
    <h3 class="issue-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <p class="issue-description"><?php the_excerpt(); ?></p>

    <div class="issue-footer">
      <?php if ($location) : ?>
        <span><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></span>
      <?php endif; ?>

      <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" style="display:flex;align-items:center;gap:.25rem;">
        <i class="fa-regular fa-clock"></i>
        <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp'))); ?>
        <?php esc_html_e('ago', 'neighborhood-connect'); ?>
      </time>

      <span class="badge <?php echo esc_attr(isset($status_classes[$status]) ? explode(' ', $status_classes[$status])[1] : 'badge-gray'); ?>">
        <?php echo esc_html($status_labels[$status] ?? ucfirst($status)); ?>
      </span>

      <button
        class="vote-btn<?php echo $user_voted ? ' voted' : ''; ?>"
        data-issue-id="<?php echo esc_attr(get_the_ID()); ?>"
        aria-label="<?php echo $user_voted ? esc_attr__('Remove vote', 'neighborhood-connect') : esc_attr__('Vote for this issue', 'neighborhood-connect'); ?>"
      >
        <i class="fa-solid fa-thumbs-up"></i>
        <span class="vote-count"><?php echo esc_html($votes); ?></span>
      </button>
    </div>
  </div>

</div>
