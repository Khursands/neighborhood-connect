<?php get_header(); ?>

<div class="archive-hero" style="background:linear-gradient(135deg,#881337 0%,#6f1028 100%);color:white;padding:var(--s-16) 0 var(--s-12);">
  <div class="container">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.75);">Home</a>
      <span style="margin:0 var(--s-2);color:rgba(255,255,255,.5);">/</span>
      <span style="color:white;">Issues</span>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--s-4);">
      <div>
        <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:var(--fw-bold);margin-bottom:var(--s-3);">Issue Tracker</h1>
        <p style="font-size:var(--text-lg);opacity:.9;max-width:560px;">Report and track neighborhood problems — potholes, broken lights, and more.</p>
      </div>
      <?php if (is_user_logged_in()) : ?>
      <a href="#report-issue-modal" class="btn btn-primary" id="open-report-issue" style="background:white;color:#881337;border:none;">
        <i class="fa-solid fa-plus"></i> Report an Issue
      </a>
      <?php else : ?>
      <a href="<?php echo esc_url(home_url('/login/')); ?>" class="btn" style="background:white;color:#881337;">
        <i class="fa-solid fa-plus"></i> Report an Issue
      </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="container" style="padding-top:var(--s-8);padding-bottom:var(--s-16);">

  <!-- Status Tabs -->
  <div class="issue-status-tabs">
    <button class="issue-tab active" data-status="all">All Issues</button>
    <button class="issue-tab" data-status="open">
      <span class="status-dot open"></span> Open
    </button>
    <button class="issue-tab" data-status="in_progress">
      <span class="status-dot in_progress"></span> In Progress
    </button>
    <button class="issue-tab" data-status="resolved">
      <span class="status-dot resolved"></span> Resolved
    </button>
  </div>

  <?php
  $paged = get_query_var('paged') ?: 1;
  $issues_query = new WP_Query([
      'post_type'      => 'nc_issue',
      'posts_per_page' => 20,
      'paged'          => $paged,
      'post_status'    => 'publish',
      'meta_key'       => '_nc_votes',
      'orderby'        => 'meta_value_num',
      'order'          => 'DESC',
  ]);

  $status_labels = ['open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'];
  $status_colors = ['open' => '#dc2626', 'in_progress' => '#d97706', 'resolved' => '#16a34a'];
  ?>

  <div class="archive-toolbar" style="margin-bottom:var(--s-6);">
    <p class="archive-count" id="issue-count">
      <?php
      $total_issues = wp_count_posts('nc_issue')->publish;
      echo esc_html($total_issues . ' issue' . ($total_issues !== 1 ? 's' : '') . ' reported');
      ?>
    </p>
    <select class="form-select" id="issue-sort" style="width:auto;font-size:.875rem;">
      <option value="votes">Most Voted</option>
      <option value="newest">Newest</option>
      <option value="oldest">Oldest</option>
    </select>
  </div>

  <?php if ($issues_query->have_posts()) : ?>
  <div class="issues-list" id="issues-list">
    <?php while ($issues_query->have_posts()) : $issues_query->the_post();
      $iid      = get_the_ID();
      $status   = get_post_meta($iid, '_nc_status', true) ?: 'open';
      $votes    = (int) get_post_meta($iid, '_nc_votes', true);
      $location = get_post_meta($iid, '_nc_location', true);
      $type     = get_post_meta($iid, '_nc_issue_type', true);
      $voters   = get_post_meta($iid, '_nc_voters', true) ?: [];
      $user_voted = is_user_logged_in() && in_array(get_current_user_id(), $voters);
      $status_color = $status_colors[$status] ?? '#dc2626';
      $author = get_the_author();
      $posted = get_the_date('M j, Y');

      $type_icons = [
        'pothole' => 'fa-road-circle-exclamation', 'streetlight' => 'fa-lightbulb',
        'waste'   => 'fa-trash-can', 'noise' => 'fa-volume-high',
        'safety'  => 'fa-shield-exclamation', 'vandalism' => 'fa-spray-can',
      ];
      $type_icon = $type_icons[strtolower(str_replace(' ', '', $type ?? ''))] ?? 'fa-triangle-exclamation';
    ?>
    <article class="issue-row" data-status="<?php echo esc_attr($status); ?>" data-votes="<?php echo esc_attr($votes); ?>" data-date="<?php echo esc_attr(get_the_date('Y-m-d')); ?>">

      <!-- Vote Column -->
      <div class="issue-vote-col">
        <?php if (is_user_logged_in()) : ?>
        <button class="issue-vote-btn <?php echo $user_voted ? 'voted' : ''; ?>"
                data-issue-id="<?php echo esc_attr($iid); ?>"
                aria-label="Vote for this issue">
          <i class="fa-solid fa-chevron-up"></i>
          <span class="vote-count"><?php echo esc_html($votes); ?></span>
        </button>
        <?php else : ?>
        <a href="<?php echo esc_url(home_url('/login/')); ?>" class="issue-vote-btn" title="Log in to vote">
          <i class="fa-solid fa-chevron-up"></i>
          <span class="vote-count"><?php echo esc_html($votes); ?></span>
        </a>
        <?php endif; ?>
      </div>

      <!-- Content -->
      <div class="issue-row-content">
        <div class="issue-row-header">
          <span class="issue-type-icon"><i class="fa-solid <?php echo esc_attr($type_icon); ?>"></i></span>
          <h3 class="issue-row-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <span class="issue-status-badge" style="background:<?php echo esc_attr($status_color); ?>1a;color:<?php echo esc_attr($status_color); ?>;border:1px solid <?php echo esc_attr($status_color); ?>33;">
            <span class="status-dot <?php echo esc_attr($status); ?>"></span>
            <?php echo esc_html($status_labels[$status] ?? 'Open'); ?>
          </span>
        </div>

        <p class="issue-row-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 25)); ?></p>

        <div class="issue-row-meta">
          <?php if ($location) : ?>
          <span><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></span>
          <?php endif; ?>
          <span><i class="fa-regular fa-user"></i> <?php echo esc_html($author); ?></span>
          <span><i class="fa-regular fa-clock"></i> <?php echo esc_html($posted); ?></span>
          <?php if ($type) : ?>
          <span><i class="fa-solid fa-tag"></i> <?php echo esc_html(ucfirst($type)); ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="issue-row-action">
        <a href="<?php the_permalink(); ?>" class="btn btn-ghost btn-sm">View <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>

  <!-- Pagination -->
  <?php if ($issues_query->max_num_pages > 1) : ?>
  <div class="pagination-wrap">
    <?php echo paginate_links([
      'total'     => $issues_query->max_num_pages,
      'current'   => $paged,
      'prev_text' => '<i class="fa-solid fa-arrow-left"></i> Prev',
      'next_text' => 'Next <i class="fa-solid fa-arrow-right"></i>',
    ]); ?>
  </div>
  <?php endif; ?>

  <?php else : ?>
  <div class="empty-state">
    <i class="fa-solid fa-circle-check" style="color:#16a34a;"></i>
    <h3>No issues reported</h3>
    <p>Your neighborhood is looking great! Report an issue if you spot something that needs attention.</p>
    <?php if (is_user_logged_in()) : ?>
    <button class="btn btn-primary" id="open-report-issue-empty">Report an Issue</button>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<!-- Report Issue Modal -->
<?php if (is_user_logged_in()) : ?>
<div class="modal-overlay" id="report-issue-modal" role="dialog" aria-modal="true" aria-label="Report an Issue">
  <div class="modal">
    <div class="modal-header">
      <span class="modal-title"><i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i> Report an Issue</span>
      <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="modal-body">
      <form id="report-issue-form">
        <?php wp_nonce_field('nc_nonce', 'nc_issue_nonce'); ?>
        <div class="form-group">
          <label class="form-label">Issue Title <span style="color:#dc2626;">*</span></label>
          <input type="text" name="title" class="form-input" placeholder="e.g. Pothole on Main Street" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-input" rows="4" placeholder="Describe the issue in detail…" style="resize:vertical;"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-input" placeholder="Street address or landmark">
        </div>
        <div class="form-group">
          <label class="form-label">Issue Type</label>
          <select name="issue_type" class="form-input">
            <option value="">Select a type</option>
            <option value="pothole">Pothole / Road Damage</option>
            <option value="streetlight">Streetlight Out</option>
            <option value="waste">Waste / Littering</option>
            <option value="noise">Noise Complaint</option>
            <option value="safety">Safety Concern</option>
            <option value="vandalism">Vandalism / Graffiti</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div id="issue-form-msg" class="auth-alert" style="display:none;"></div>
        <div style="display:flex;gap:var(--s-3);justify-content:flex-end;margin-top:var(--s-4);">
          <button type="button" class="btn btn-ghost modal-close">Cancel</button>
          <button type="submit" class="btn btn-primary" id="submit-issue-btn">
            <span class="btn-text"><i class="fa-solid fa-paper-plane"></i> Submit Issue</span>
            <span class="btn-loading" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i> Submitting…</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
(function () {
  // Status tabs
  const tabs = document.querySelectorAll('.issue-tab');
  const rows = Array.from(document.querySelectorAll('.issue-row'));
  const countEl = document.getElementById('issue-count');
  const sortSelect = document.getElementById('issue-sort');
  const list = document.getElementById('issues-list');
  let activeStatus = 'all';

  function filterIssues() {
    let visible = 0;
    rows.forEach(row => {
      const status = row.dataset.status;
      const match = activeStatus === 'all' || status === activeStatus;
      row.style.display = match ? '' : 'none';
      if (match) visible++;
    });
    countEl.textContent = visible + ' issue' + (visible !== 1 ? 's' : '') + ' reported';
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function () {
      tabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      activeStatus = this.dataset.status;
      filterIssues();
    });
  });

  sortSelect?.addEventListener('change', function () {
    const val = this.value;
    const sorted = [...rows].sort((a, b) => {
      if (val === 'votes') return parseInt(b.dataset.votes) - parseInt(a.dataset.votes);
      if (val === 'newest') return b.dataset.date.localeCompare(a.dataset.date);
      return a.dataset.date.localeCompare(b.dataset.date);
    });
    sorted.forEach(row => list.appendChild(row));
  });

  // Vote buttons
  document.querySelectorAll('.issue-vote-btn[data-issue-id]').forEach(btn => {
    btn.addEventListener('click', async function () {
      const issueId = this.dataset.issueId;
      const countEl = this.querySelector('.vote-count');
      const data = new FormData();
      data.append('action', 'nc_vote_issue');
      data.append('issue_id', issueId);
      data.append('nonce', ncData.nonce);
      try {
        const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: data });
        const json = await res.json();
        if (json.success) {
          countEl.textContent = json.data.votes;
          this.classList.toggle('voted');
        }
      } catch (e) {}
    });
  });

  // Report issue modal
  const reportBtn = document.getElementById('open-report-issue');
  const reportBtnEmpty = document.getElementById('open-report-issue-empty');
  const modal = document.getElementById('report-issue-modal');

  function openModal() {
    modal?.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  reportBtn?.addEventListener('click', function (e) { e.preventDefault(); openModal(); });
  reportBtnEmpty?.addEventListener('click', openModal);

  modal?.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', function () {
      modal.classList.remove('is-open');
      document.body.style.overflow = '';
    });
  });

  // Submit issue form
  const form = document.getElementById('report-issue-form');
  form?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const submitBtn = document.getElementById('submit-issue-btn');
    const msgEl = document.getElementById('issue-form-msg');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoad = submitBtn.querySelector('.btn-loading');
    btnText.style.display = 'none';
    btnLoad.style.display = '';
    submitBtn.disabled = true;
    msgEl.style.display = 'none';

    const data = new FormData(form);
    data.append('action', 'nc_ajax_report_issue');
    data.append('nonce', ncData.nonce);

    try {
      const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        msgEl.className = 'auth-alert auth-alert-success';
        msgEl.textContent = 'Issue reported! Thank you.';
        msgEl.style.display = '';
        setTimeout(() => window.location.reload(), 1500);
      } else {
        msgEl.className = 'auth-alert auth-alert-error';
        msgEl.textContent = json.data?.message || 'Failed to submit.';
        msgEl.style.display = '';
        btnText.style.display = '';
        btnLoad.style.display = 'none';
        submitBtn.disabled = false;
      }
    } catch {
      msgEl.className = 'auth-alert auth-alert-error';
      msgEl.textContent = 'Network error. Please try again.';
      msgEl.style.display = '';
      btnText.style.display = '';
      btnLoad.style.display = 'none';
      submitBtn.disabled = false;
    }
  });
}());
</script>

<?php get_footer(); ?>
