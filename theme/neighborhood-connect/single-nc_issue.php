<?php get_header(); ?>

<?php
the_post();
$iid      = get_the_ID();
$status   = get_post_meta($iid, '_nc_status', true) ?: 'open';
$votes    = (int) get_post_meta($iid, '_nc_votes', true);
$voters   = get_post_meta($iid, '_nc_voters', true) ?: [];
$location = get_post_meta($iid, '_nc_location', true);
$type     = get_post_meta($iid, '_nc_issue_type', true);
$lat      = (float) get_post_meta($iid, '_nc_lat', true);
$lng      = (float) get_post_meta($iid, '_nc_lng', true);
$user_voted = is_user_logged_in() && in_array(get_current_user_id(), $voters);

$status_labels = ['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'];
$status_colors = ['open' => '#dc2626', 'in_progress' => '#d97706', 'resolved' => '#16a34a'];
$status_label  = $status_labels[$status] ?? 'Open';
$status_color  = $status_colors[$status] ?? '#dc2626';

$type_icons = [
  'pothole'   => 'fa-road-circle-exclamation', 'streetlight' => 'fa-lightbulb',
  'waste'     => 'fa-trash-can', 'noise' => 'fa-volume-high',
  'safety'    => 'fa-shield-exclamation', 'vandalism' => 'fa-spray-can',
  'other'     => 'fa-circle-exclamation',
];
$type_icon = $type_icons[strtolower(str_replace([' ', '-'], '', $type ?? ''))] ?? 'fa-triangle-exclamation';
?>

<!-- Issue Hero -->
<div class="single-hero issue-single-hero" style="background:linear-gradient(135deg,<?php echo esc_attr($status_color); ?>12 0%,var(--c-bg) 100%);border-bottom:1px solid var(--c-border);">
  <div class="container" style="padding-top:var(--s-10);padding-bottom:var(--s-10);">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
      <span>/</span>
      <a href="<?php echo esc_url(home_url('/issues/')); ?>">Issues</a>
      <span>/</span>
      <span><?php the_title(); ?></span>
    </div>

    <div class="issue-single-header">
      <div class="issue-single-vote">
        <?php if (is_user_logged_in()) : ?>
        <button class="issue-vote-btn-lg <?php echo $user_voted ? 'voted' : ''; ?>"
                id="single-vote-btn"
                data-issue-id="<?php echo esc_attr($iid); ?>">
          <i class="fa-solid fa-chevron-up"></i>
          <span class="vote-count"><?php echo esc_html($votes); ?></span>
          <small><?php echo $votes === 1 ? 'vote' : 'votes'; ?></small>
        </button>
        <?php else : ?>
        <a href="<?php echo esc_url(home_url('/login/')); ?>" class="issue-vote-btn-lg" title="Log in to vote">
          <i class="fa-solid fa-chevron-up"></i>
          <span><?php echo esc_html($votes); ?></span>
          <small>votes</small>
        </a>
        <?php endif; ?>
      </div>

      <div class="issue-single-content">
        <div style="display:flex;align-items:center;gap:var(--s-3);margin-bottom:var(--s-3);flex-wrap:wrap;">
          <span style="display:flex;align-items:center;gap:var(--s-2);background:<?php echo esc_attr($status_color); ?>1a;color:<?php echo esc_attr($status_color); ?>;padding:var(--s-1) var(--s-3);border-radius:100px;font-size:.85rem;font-weight:500;">
            <span class="status-dot <?php echo esc_attr($status); ?>"></span>
            <?php echo esc_html($status_label); ?>
          </span>
          <?php if ($type) : ?>
          <span style="display:flex;align-items:center;gap:var(--s-2);background:var(--c-sand-100);color:var(--c-text-muted);padding:var(--s-1) var(--s-3);border-radius:100px;font-size:.85rem;">
            <i class="fa-solid <?php echo esc_attr($type_icon); ?>"></i>
            <?php echo esc_html(ucfirst($type)); ?>
          </span>
          <?php endif; ?>
        </div>

        <h1 class="single-title" style="font-size:clamp(1.4rem,3vw,2rem);"><?php the_title(); ?></h1>

        <div class="issue-meta-row" style="margin-top:var(--s-3);">
          <?php if ($location) : ?>
          <span><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></span>
          <?php endif; ?>
          <span><i class="fa-regular fa-user"></i> Reported by <?php echo esc_html(get_the_author()); ?></span>
          <span><i class="fa-regular fa-clock"></i> <?php echo esc_html(get_the_date('F j, Y')); ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Issue Body -->
<div class="container" style="padding-top:var(--s-10);padding-bottom:var(--s-16);">
  <div class="single-body-grid">
    <div class="single-body-main">

      <?php if (has_post_thumbnail()) : ?>
      <figure>
        <?php the_post_thumbnail('nc-hero', ['style' => 'border-radius:var(--radius-xl);width:100%;height:300px;object-fit:cover;margin-bottom:var(--s-6);display:block;']); ?>
      </figure>
      <?php endif; ?>

      <div class="post-content">
        <?php the_content(); ?>
        <?php if (!get_the_content()) : ?>
        <p style="color:var(--c-text-muted);">No additional description provided.</p>
        <?php endif; ?>
      </div>

      <!-- Status Timeline -->
      <div class="sidebar-card" style="margin-top:var(--s-8);">
        <h3 style="font-size:1rem;font-weight:var(--fw-semibold);margin-bottom:var(--s-5);">
          <i class="fa-solid fa-timeline" style="color:var(--c-primary);"></i> Status History
        </h3>
        <div class="status-timeline">
          <div class="timeline-item active">
            <div class="timeline-dot" style="background:var(--c-primary);"></div>
            <div>
              <strong>Issue Reported</strong>
              <p><?php echo esc_html(get_the_date('F j, Y \a\t g:i A')); ?></p>
            </div>
          </div>
          <?php if ($status === 'in_progress' || $status === 'resolved') : ?>
          <div class="timeline-item active">
            <div class="timeline-dot" style="background:#d97706;"></div>
            <div>
              <strong>Under Review</strong>
              <p>Being investigated by the local authority</p>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($status === 'resolved') : ?>
          <div class="timeline-item active">
            <div class="timeline-dot" style="background:#16a34a;"></div>
            <div>
              <strong>Resolved</strong>
              <p>Issue has been addressed</p>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($status === 'open') : ?>
          <div class="timeline-item pending">
            <div class="timeline-dot" style="background:var(--c-sand-300);"></div>
            <div>
              <strong style="color:var(--c-text-muted);">Awaiting Action</strong>
              <p style="color:var(--c-text-muted);">Pending review from local authority</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <?php comments_template(); ?>
    </div>

    <!-- Sidebar -->
    <aside class="single-sidebar">
      <!-- Status + Vote Card -->
      <div class="sidebar-card">
        <h4 class="sidebar-title">Current Status</h4>
        <div style="display:flex;align-items:center;gap:var(--s-3);padding:var(--s-4);background:<?php echo esc_attr($status_color); ?>0d;border-radius:var(--radius-lg);margin-bottom:var(--s-4);">
          <span class="status-dot <?php echo esc_attr($status); ?>" style="width:12px;height:12px;"></span>
          <strong style="color:<?php echo esc_attr($status_color); ?>;"><?php echo esc_html($status_label); ?></strong>
        </div>
        <div style="text-align:center;padding:var(--s-3) 0;">
          <div style="font-size:2rem;font-weight:var(--fw-bold);color:var(--c-primary);"><?php echo esc_html($votes); ?></div>
          <div style="font-size:.85rem;color:var(--c-text-muted);">community votes</div>
          <p style="font-size:.8rem;color:var(--c-text-muted);margin-top:var(--s-2);">High-vote issues get priority attention</p>
        </div>
        <?php if (is_user_logged_in()) : ?>
        <button class="btn btn-full <?php echo $user_voted ? 'btn-success' : 'btn-primary'; ?>"
                id="sidebar-vote-btn"
                data-issue-id="<?php echo esc_attr($iid); ?>">
          <i class="fa-solid <?php echo $user_voted ? 'fa-check' : 'fa-thumbs-up'; ?>"></i>
          <?php echo $user_voted ? 'Voted' : 'Vote for This Issue'; ?>
        </button>
        <?php else : ?>
        <a href="<?php echo esc_url(home_url('/login/')); ?>" class="btn btn-primary btn-full">Log In to Vote</a>
        <?php endif; ?>
      </div>

      <!-- Share -->
      <div class="sidebar-card">
        <h4 class="sidebar-title">Share This Issue</h4>
        <p style="font-size:.85rem;color:var(--c-text-muted);margin-bottom:var(--s-3);">Help raise awareness and get this fixed faster.</p>
        <div style="display:flex;gap:var(--s-2);">
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm" style="flex:1;text-align:center;">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode('Community issue: ' . get_the_title()); ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-sm" style="flex:1;text-align:center;">
            <i class="fa-brands fa-x-twitter"></i>
          </a>
          <button onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>').then(() => NcToast.show('Link copied!','success'))" class="btn btn-ghost btn-sm" style="flex:1;text-align:center;">
            <i class="fa-solid fa-link"></i>
          </button>
        </div>
      </div>

      <!-- Other Issues -->
      <div class="sidebar-card">
        <h4 class="sidebar-title">Other Open Issues</h4>
        <?php
        $related = new WP_Query([
          'post_type'      => 'nc_issue',
          'posts_per_page' => 4,
          'post__not_in'   => [$iid],
          'post_status'    => 'publish',
          'meta_key'       => '_nc_votes',
          'orderby'        => 'meta_value_num',
          'order'          => 'DESC',
          'meta_query'     => [['key' => '_nc_status', 'value' => 'open', 'compare' => '=']],
        ]);
        while ($related->have_posts()) : $related->the_post();
          $r_votes = (int) get_post_meta(get_the_ID(), '_nc_votes', true);
        ?>
        <div style="padding:var(--s-3) 0;border-bottom:1px solid var(--c-border);">
          <a href="<?php the_permalink(); ?>" style="font-weight:500;font-size:.9rem;color:var(--c-text);text-decoration:none;"><?php the_title(); ?></a>
          <p style="font-size:.8rem;color:var(--c-text-muted);margin:var(--s-1) 0 0;">
            <i class="fa-solid fa-thumbs-up"></i> <?php echo esc_html($r_votes); ?> votes
          </p>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
        <a href="<?php echo esc_url(home_url('/issues/')); ?>" class="btn btn-ghost btn-sm btn-full" style="margin-top:var(--s-3);">All Issues</a>
      </div>
    </aside>
  </div>
</div>

<script>
(function () {
  function handleVote(btn) {
    if (!btn) return;
    btn.addEventListener('click', async function () {
      const issueId = this.dataset.issueId;
      const data = new FormData();
      data.append('action', 'nc_vote_issue');
      data.append('issue_id', issueId);
      data.append('nonce', ncData.nonce);
      try {
        const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: data });
        const json = await res.json();
        if (json.success) {
          const newVotes = json.data.votes;
          // Update all vote counts
          document.querySelectorAll('.vote-count, #single-vote-btn .vote-count').forEach(el => el.textContent = newVotes);
          document.querySelector('.sidebar-card .text-2xl, [style*="font-size:2rem"]').textContent = newVotes;

          // Toggle voted state
          const singleBtn = document.getElementById('single-vote-btn');
          const sidebarBtn = document.getElementById('sidebar-vote-btn');
          const isVoted = this.classList.contains('voted');
          [singleBtn, sidebarBtn].forEach(b => {
            if (b) {
              b.classList.toggle('voted', !isVoted);
              b.classList.toggle('btn-success', !isVoted);
              b.classList.toggle('btn-primary', isVoted);
            }
          });
          if (sidebarBtn) {
            sidebarBtn.innerHTML = !isVoted
              ? '<i class="fa-solid fa-check"></i> Voted'
              : '<i class="fa-solid fa-thumbs-up"></i> Vote for This Issue';
          }
        }
      } catch (e) {}
    });
  }
  handleVote(document.getElementById('single-vote-btn'));
  handleVote(document.getElementById('sidebar-vote-btn'));
}());
</script>

<?php get_footer(); ?>
