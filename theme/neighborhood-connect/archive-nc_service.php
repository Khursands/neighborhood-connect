<?php
/**
 * Society Services — multi-step request wizard.
 * Replaces the old "external vendor directory".
 */
get_header();

$is_logged_in = is_user_logged_in();
$current_user = wp_get_current_user();

// Load the catalog from the nc_service CPT
$services = get_posts([
  'post_type'      => 'nc_service',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order title',
  'order'          => 'ASC',
]);

// Recent requests by the current user (for the My Requests panel)
$my_requests = $is_logged_in ? get_posts([
  'post_type'      => 'nc_service_request',
  'post_status'    => 'publish',
  'posts_per_page' => 5,
  'author'         => get_current_user_id(),
  'orderby'        => 'date',
  'order'          => 'DESC',
]) : [];
?>

<!-- Hero -->
<section class="services-hero" style="background:linear-gradient(135deg,#4338ca 0%,#0d9488 100%);color:white;padding:var(--s-16) 0 var(--s-10);">
  <div class="container">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.75);">Home</a>
      <span style="margin:0 var(--s-2);color:rgba(255,255,255,.5);">/</span>
      <span style="color:white;">Services</span>
    </div>
    <h1 style="font-size:clamp(1.8rem,4vw,2.6rem);font-weight:var(--fw-bold);margin-bottom:var(--s-3);">
      Society Services On Demand
    </h1>
    <p style="font-size:var(--text-lg);opacity:.92;max-width:640px;line-height:1.6;">
      Need a plumber, electrician, tiffin or grocery run? Pick what you need below — the society's service team will be assigned to you and contact you shortly.
    </p>

    <div style="display:flex;gap:var(--s-6);margin-top:var(--s-6);flex-wrap:wrap;font-size:var(--text-sm);">
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-shield-halved"></i> Verified society staff</div>
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-clock"></i> Avg. response &lt; 2 hours</div>
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-check-circle"></i> No payment on platform — pay the team directly</div>
    </div>
  </div>
</section>

<div class="container" style="padding:var(--s-10) 0 var(--s-16);">

  <!-- WIZARD -->
  <div class="sr-wizard" id="srWizard">

    <!-- progress bar -->
    <div class="sr-progress" aria-label="Wizard progress">
      <div class="sr-step-dot active" data-step="1"><span>1</span><small>Category</small></div>
      <div class="sr-step-line"></div>
      <div class="sr-step-dot" data-step="2"><span>2</span><small>Details</small></div>
      <div class="sr-step-line"></div>
      <div class="sr-step-dot" data-step="3"><span>3</span><small>Schedule</small></div>
      <div class="sr-step-line"></div>
      <div class="sr-step-dot" data-step="4"><span>4</span><small>Confirm</small></div>
    </div>

    <!-- STEP 1: pick service category -->
    <section class="sr-pane is-active" data-pane="1">
      <header class="sr-pane-head">
        <h2>What do you need help with?</h2>
        <p>Pick a category to get started. You can add details on the next step.</p>
      </header>
      <div class="sr-category-grid">
        <?php foreach ($services as $svc) :
          $svc_icon  = get_post_meta($svc->ID, '_nc_service_icon', true) ?: '🛠';
          $svc_fa    = get_post_meta($svc->ID, '_nc_service_fa', true);
          $svc_color = get_post_meta($svc->ID, '_nc_service_color', true) ?: '#4f46e5';
          $svc_tag   = get_post_meta($svc->ID, '_nc_service_tagline', true) ?: $svc->post_excerpt;
          $svc_subs  = get_post_meta($svc->ID, '_nc_service_subitems', true) ?: [];
        ?>
        <button type="button"
                class="sr-cat-card"
                data-service-id="<?php echo esc_attr($svc->ID); ?>"
                data-service-title="<?php echo esc_attr($svc->post_title); ?>"
                data-service-color="<?php echo esc_attr($svc_color); ?>"
                data-subitems="<?php echo esc_attr(wp_json_encode(array_values($svc_subs))); ?>"
                style="--cat-color:<?php echo esc_attr($svc_color); ?>;">
          <span class="sr-cat-icon" style="background:<?php echo esc_attr($svc_color); ?>20;color:<?php echo esc_attr($svc_color); ?>;">
            <?php if ($svc_fa) : ?>
              <i class="fa-solid <?php echo esc_attr($svc_fa); ?>"></i>
            <?php else : ?>
              <?php echo esc_html($svc_icon); ?>
            <?php endif; ?>
          </span>
          <span class="sr-cat-title"><?php echo esc_html($svc->post_title); ?></span>
          <span class="sr-cat-tag"><?php echo esc_html($svc_tag); ?></span>
          <span class="sr-cat-arrow"><i class="fa-solid fa-arrow-right"></i></span>
        </button>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- STEP 2: details -->
    <section class="sr-pane" data-pane="2">
      <header class="sr-pane-head">
        <button type="button" class="sr-back" data-back="1"><i class="fa-solid fa-arrow-left"></i> Back</button>
        <h2>Tell us what's needed</h2>
        <p>Choose the closest match — you can add anything specific in the description below.</p>
      </header>

      <div class="sr-selected-pill" id="srSelectedPill"></div>

      <div class="sr-subitem-list" id="srSubitems"></div>

      <label class="form-label" for="srDescription" style="margin-top:var(--s-5);">Anything else we should know? <small style="color:var(--c-muted);font-weight:400;">(optional)</small></label>
      <textarea id="srDescription" class="form-input" rows="4" placeholder="e.g. The kitchen tap drips even when fully closed. Already tried tightening it."></textarea>

      <div class="sr-actions">
        <button type="button" class="btn btn-ghost" data-back="1"><i class="fa-solid fa-arrow-left"></i> Back</button>
        <button type="button" class="btn btn-primary" id="srToStep3">Continue <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </section>

    <!-- STEP 3: schedule + location -->
    <section class="sr-pane" data-pane="3">
      <header class="sr-pane-head">
        <button type="button" class="sr-back" data-back="2"><i class="fa-solid fa-arrow-left"></i> Back</button>
        <h2>Where and when?</h2>
        <p>So the team knows where to come and how urgent it is.</p>
      </header>

      <div class="sr-form-grid">
        <div class="form-group">
          <label class="form-label" for="srFlat">Flat / Unit number <span class="req">*</span></label>
          <input type="text" id="srFlat" class="form-input" placeholder="e.g. B-204" required>
        </div>
        <div class="form-group">
          <label class="form-label" for="srPhone">Phone (so the team can call)</label>
          <input type="tel" id="srPhone" class="form-input" placeholder="+92 300 1234567" value="<?php echo esc_attr($is_logged_in ? get_user_meta($current_user->ID, '_nc_phone', true) : ''); ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="srTime">Preferred time</label>
          <select id="srTime" class="form-input">
            <option value="ASAP">As soon as possible</option>
            <option value="Today morning">Today — morning (9 AM – 12 PM)</option>
            <option value="Today afternoon">Today — afternoon (12 PM – 4 PM)</option>
            <option value="Today evening">Today — evening (4 PM – 8 PM)</option>
            <option value="Tomorrow morning">Tomorrow — morning</option>
            <option value="Tomorrow afternoon">Tomorrow — afternoon</option>
            <option value="This weekend">This weekend</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Urgency</label>
          <div class="sr-urgency">
            <label><input type="radio" name="srUrgency" value="low"> <span>Low — whenever convenient</span></label>
            <label><input type="radio" name="srUrgency" value="normal" checked> <span>Normal — within a day</span></label>
            <label><input type="radio" name="srUrgency" value="urgent"> <span>Urgent — within an hour</span></label>
          </div>
        </div>
      </div>

      <div class="sr-actions">
        <button type="button" class="btn btn-ghost" data-back="2"><i class="fa-solid fa-arrow-left"></i> Back</button>
        <button type="button" class="btn btn-primary" id="srSubmit">
          <span class="btn-text"><i class="fa-solid fa-paper-plane"></i> Submit Request</span>
          <span class="btn-loading" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i> Submitting…</span>
        </button>
      </div>

      <div id="srError" class="sr-error" style="display:none;"></div>

      <?php if (!$is_logged_in) : ?>
      <div class="sr-login-hint">
        <i class="fa-solid fa-lock"></i>
        You need to be logged in to submit. <a href="<?php echo esc_url(home_url('/login/?redirect_to=' . urlencode(home_url('/services/')))); ?>">Sign in</a> or <a href="<?php echo esc_url(home_url('/register/')); ?>">create an account</a> — it's free.
      </div>
      <?php endif; ?>
    </section>

    <!-- STEP 4: confirmation -->
    <section class="sr-pane sr-pane-confirm" data-pane="4">
      <div class="sr-success">
        <div class="sr-success-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h2>Your request is in!</h2>
        <p class="sr-success-sub">A member of the society's service team has been notified and will reach out shortly.</p>

        <div class="sr-receipt">
          <div class="sr-receipt-row"><span>Reference</span><strong id="srRef">—</strong></div>
          <div class="sr-receipt-row"><span>Service</span><strong id="srSvc">—</strong></div>
          <div class="sr-receipt-row"><span>Sub-service</span><strong id="srSub">—</strong></div>
          <div class="sr-receipt-row"><span>Status</span><strong><span class="sr-status-pill sr-pill-assigned" id="srStatus">Assigned</span></strong></div>
          <div class="sr-receipt-row sr-receipt-team"><span>Assigned team member</span>
            <strong>
              <span id="srTeamName">—</span><br>
              <small id="srTeamPhone" style="color:var(--c-muted);font-weight:400;"></small>
            </strong>
          </div>
        </div>

        <p class="sr-next">
          <i class="fa-solid fa-circle-info"></i>
          You can also track this request under <strong>My Requests</strong> below.
        </p>

        <div class="sr-actions" style="justify-content:center;">
          <button type="button" class="btn btn-primary" id="srNew"><i class="fa-solid fa-plus"></i> New Request</button>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-ghost">Back to home</a>
        </div>
      </div>
    </section>
  </div>

  <!-- MY REQUESTS -->
  <?php if ($is_logged_in) : ?>
  <section class="sr-history" id="srHistory" style="margin-top:var(--s-12);">
    <h3>My Requests</h3>
    <?php if (empty($my_requests)) : ?>
      <p class="sr-history-empty">You haven't made any service requests yet. Pick a category above to get started.</p>
    <?php else : ?>
      <div class="sr-history-list">
        <?php foreach ($my_requests as $r) :
          $svc_id   = (int) get_post_meta($r->ID, '_nc_sr_service_id', true);
          $svc_t    = $svc_id ? get_the_title($svc_id) : 'Service';
          $sub      = get_post_meta($r->ID, '_nc_sr_subservice', true);
          $status   = get_post_meta($r->ID, '_nc_sr_status', true) ?: 'pending';
          $urgency  = get_post_meta($r->ID, '_nc_sr_urgency', true) ?: 'normal';
          $assigned = (int) get_post_meta($r->ID, '_nc_sr_assigned_to', true);
          $au       = $assigned ? get_userdata($assigned) : null;
          $flat     = get_post_meta($r->ID, '_nc_sr_flat', true);
          $status_label = [
            'pending'     => 'Pending',
            'assigned'    => 'Assigned',
            'in_progress' => 'In Progress',
            'completed'   => 'Completed',
            'cancelled'   => 'Cancelled',
          ][$status] ?? ucfirst($status);
        ?>
        <article class="sr-history-card">
          <div class="sr-history-top">
            <div>
              <h4><?php echo esc_html($svc_t); ?><?php if ($sub) : ?> <small style="color:var(--c-muted);font-weight:400;">— <?php echo esc_html($sub); ?></small><?php endif; ?></h4>
              <div class="sr-history-meta">
                <span>SR-<?php echo esc_html(str_pad($r->ID, 4, '0', STR_PAD_LEFT)); ?></span>
                <span>•</span>
                <span><?php echo esc_html(human_time_diff(get_post_time('U', false, $r), current_time('timestamp'))); ?> ago</span>
                <?php if ($flat) : ?><span>•</span><span>Flat <?php echo esc_html($flat); ?></span><?php endif; ?>
              </div>
            </div>
            <span class="sr-status-pill sr-pill-<?php echo esc_attr($status); ?>"><?php echo esc_html($status_label); ?></span>
          </div>
          <?php if ($au) : ?>
            <div class="sr-history-team">
              <i class="fa-solid fa-user-helmet-safety"></i>
              Assigned to <strong><?php echo esc_html($au->display_name); ?></strong>
              <?php $aphone = get_user_meta($assigned, '_nc_team_phone', true); ?>
              <?php if ($aphone) : ?><span style="color:var(--c-muted);">· <?php echo esc_html($aphone); ?></span><?php endif; ?>
            </div>
          <?php endif; ?>
        </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
  <?php endif; ?>

</div>

<style>
/* ── Wizard scaffolding ───────────────────────── */
.sr-wizard {
  max-width: 920px;
  margin: 0 auto;
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius-xl);
  padding: var(--s-8) var(--s-6) var(--s-10);
  box-shadow: 0 2px 4px rgba(0,0,0,.03);
}

/* progress dots */
.sr-progress { display: flex; align-items: center; justify-content: center; gap: var(--s-2); margin-bottom: var(--s-8); flex-wrap: wrap; }
.sr-step-dot { display: flex; flex-direction: column; align-items: center; min-width: 70px; opacity: .45; transition: .2s; }
.sr-step-dot span:first-child { width: 36px; height: 36px; border-radius: 50%; background: var(--c-border); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .9rem; margin-bottom: 4px; }
.sr-step-dot small { font-size: .72rem; font-weight: 600; color: var(--c-muted); }
.sr-step-dot.active { opacity: 1; }
.sr-step-dot.active span:first-child { background: var(--c-primary); }
.sr-step-dot.done span:first-child { background: #16a34a; }
.sr-step-dot.done span:first-child::after { content: ""; }
.sr-step-line { flex: 0 0 32px; height: 2px; background: var(--c-border); }

/* panes */
.sr-pane { display: none; }
.sr-pane.is-active { display: block; animation: srFade .25s ease-out; }
@keyframes srFade { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
.sr-pane-head { text-align: center; margin-bottom: var(--s-7); position: relative; }
.sr-pane-head h2 { font-size: clamp(1.3rem, 2vw, 1.6rem); margin: 0 0 var(--s-2); font-weight: 700; }
.sr-pane-head p { color: var(--c-muted); margin: 0; }
.sr-back { position: absolute; left: 0; top: 0; background: transparent; border: 0; color: var(--c-muted); font-size: .85rem; cursor: pointer; padding: 4px 8px; }
.sr-back:hover { color: var(--c-primary); }

/* category grid */
.sr-category-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: var(--s-3); }
.sr-cat-card {
  text-align: left; background: var(--c-card); border: 2px solid var(--c-border); border-radius: var(--radius-lg);
  padding: var(--s-5) var(--s-4); display: flex; flex-direction: column; gap: var(--s-2); cursor: pointer;
  transition: .15s ease-out; position: relative; min-height: 150px; font-family: inherit;
}
.sr-cat-card:hover { border-color: var(--cat-color, var(--c-primary)); transform: translateY(-2px); box-shadow: 0 12px 24px -12px rgba(0,0,0,.15); }
.sr-cat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
.sr-cat-title { font-weight: 700; font-size: 1.05rem; color: var(--c-text); }
.sr-cat-tag { font-size: .8rem; color: var(--c-muted); line-height: 1.4; }
.sr-cat-arrow { position: absolute; right: var(--s-4); bottom: var(--s-4); color: var(--cat-color, var(--c-primary)); opacity: 0; transition: .15s; }
.sr-cat-card:hover .sr-cat-arrow { opacity: 1; transform: translateX(2px); }

/* sub-items */
.sr-selected-pill { display: inline-flex; align-items: center; gap: var(--s-2); background: var(--c-primary-soft, #eef2ff); color: var(--c-primary); padding: 8px 14px; border-radius: 100px; font-weight: 600; margin-bottom: var(--s-4); font-size: .9rem; }
.sr-subitem-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: var(--s-2); }
.sr-subitem-list label { display: flex; align-items: center; gap: var(--s-2); padding: 14px 16px; border: 1px solid var(--c-border); border-radius: var(--radius-md); cursor: pointer; transition: .12s; font-weight: 500; background: var(--c-card); }
.sr-subitem-list label:hover { border-color: var(--c-primary); background: var(--c-primary-soft, #eef2ff); }
.sr-subitem-list label input { accent-color: var(--c-primary); }
.sr-subitem-list label.is-active { border-color: var(--c-primary); background: var(--c-primary-soft, #eef2ff); color: var(--c-primary); }

/* form grid */
.sr-form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: var(--s-4) var(--s-5); }
.sr-form-grid .form-group { margin: 0; }
.sr-urgency { display: flex; flex-direction: column; gap: var(--s-2); }
.sr-urgency label { display: flex; align-items: center; gap: var(--s-2); padding: 8px 12px; border: 1px solid var(--c-border); border-radius: var(--radius-md); cursor: pointer; font-size: .9rem; }
.sr-urgency label:has(input:checked) { border-color: var(--c-primary); background: var(--c-primary-soft, #eef2ff); }

.sr-actions { display: flex; justify-content: space-between; gap: var(--s-3); margin-top: var(--s-8); flex-wrap: wrap; }
.sr-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; padding: 12px 16px; border-radius: var(--radius-md); margin-top: var(--s-4); font-size: .9rem; }
.sr-login-hint { background: #fff7ed; color: #9a3412; border: 1px solid #fed7aa; padding: 12px 16px; border-radius: var(--radius-md); margin-top: var(--s-4); font-size: .88rem; }
.sr-login-hint a { color: #9a3412; text-decoration: underline; font-weight: 600; }

/* confirmation */
.sr-pane-confirm { text-align: center; }
.sr-success-icon { font-size: 4rem; color: #16a34a; margin-bottom: var(--s-4); line-height: 1; }
.sr-success h2 { font-size: clamp(1.5rem, 3vw, 2rem); margin: 0 0 var(--s-2); }
.sr-success-sub { color: var(--c-muted); max-width: 480px; margin: 0 auto var(--s-7); }
.sr-receipt { max-width: 480px; margin: 0 auto; text-align: left; border: 1px solid var(--c-border); border-radius: var(--radius-lg); padding: var(--s-5); background: var(--c-bg, #fafafa); }
.sr-receipt-row { display: flex; justify-content: space-between; gap: var(--s-3); padding: 10px 0; border-bottom: 1px dashed var(--c-border); font-size: .92rem; }
.sr-receipt-row:last-child { border-bottom: 0; }
.sr-receipt-row span:first-child { color: var(--c-muted); }
.sr-receipt-team { align-items: flex-start; }
.sr-next { margin-top: var(--s-6); color: var(--c-muted); font-size: .9rem; }
.sr-next i { color: var(--c-primary); }

/* status pills */
.sr-status-pill { display: inline-block; padding: 3px 12px; border-radius: 100px; font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; }
.sr-pill-pending     { background: #fff7ed; color: #c2410c; }
.sr-pill-assigned    { background: #ecfeff; color: #0e7490; }
.sr-pill-in_progress { background: #f5f3ff; color: #6d28d9; }
.sr-pill-completed   { background: #f0fdf4; color: #15803d; }
.sr-pill-cancelled   { background: #f3f4f6; color: #4b5563; }

/* My Requests */
.sr-history h3 { font-size: 1.25rem; font-weight: 700; margin: 0 0 var(--s-4); }
.sr-history-empty { color: var(--c-muted); }
.sr-history-list { display: flex; flex-direction: column; gap: var(--s-3); }
.sr-history-card { border: 1px solid var(--c-border); border-radius: var(--radius-lg); padding: var(--s-4) var(--s-5); background: var(--c-card); }
.sr-history-top { display: flex; justify-content: space-between; align-items: flex-start; gap: var(--s-3); }
.sr-history-top h4 { margin: 0 0 4px; font-size: 1rem; font-weight: 600; }
.sr-history-meta { display: flex; gap: 6px; font-size: .78rem; color: var(--c-muted); }
.sr-history-team { margin-top: var(--s-2); font-size: .85rem; color: var(--c-text); padding-top: var(--s-3); border-top: 1px dashed var(--c-border); }
.sr-history-team i { color: var(--c-primary); margin-right: 6px; }

@media (max-width: 640px) {
  .sr-step-dot { min-width: 50px; }
  .sr-step-dot small { font-size: .65rem; }
  .sr-step-line { flex: 0 0 16px; }
  .sr-wizard { padding: var(--s-6) var(--s-4); }
}
</style>

<script>
(function () {
  const wizard = document.getElementById('srWizard');
  if (!wizard) return;

  const state = {
    serviceId: 0,
    serviceTitle: '',
    serviceColor: '#4f46e5',
    subItems: [],
    selectedSub: '',
    description: '',
    flat: '',
    phone: '',
    preferredTime: 'ASAP',
    urgency: 'normal',
  };

  const panes = wizard.querySelectorAll('.sr-pane');
  const dots  = wizard.querySelectorAll('.sr-step-dot');

  function go(step) {
    panes.forEach(p => p.classList.toggle('is-active', Number(p.dataset.pane) === step));
    dots.forEach(d => {
      const n = Number(d.dataset.step);
      d.classList.toggle('active', n === step);
      d.classList.toggle('done',   n  < step);
    });
    wizard.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  // STEP 1 -> 2
  wizard.querySelectorAll('.sr-cat-card').forEach(btn => {
    btn.addEventListener('click', () => {
      state.serviceId    = Number(btn.dataset.serviceId);
      state.serviceTitle = btn.dataset.serviceTitle;
      state.serviceColor = btn.dataset.serviceColor;
      try {
        state.subItems = JSON.parse(btn.dataset.subitems || '[]');
      } catch (e) { state.subItems = []; }

      // selected pill
      document.getElementById('srSelectedPill').innerHTML =
        `<i class="fa-solid fa-check-circle"></i> ${escapeHtml(state.serviceTitle)} ` +
        `<button type="button" style="background:none;border:0;cursor:pointer;color:inherit;font-weight:600;text-decoration:underline;margin-left:6px;font-size:.8rem;" data-back="1">change</button>`;
      document.querySelectorAll('[data-back="1"]').forEach(b => b.addEventListener('click', () => go(1)));

      // render sub-items
      const wrap = document.getElementById('srSubitems');
      wrap.innerHTML = state.subItems.map((s, i) =>
        `<label><input type="radio" name="srSub" value="${escapeHtml(s)}" ${i === 0 ? 'checked' : ''}> <span>${escapeHtml(s)}</span></label>`
      ).join('');
      if (state.subItems.length) state.selectedSub = state.subItems[0];

      wrap.querySelectorAll('input[type=radio]').forEach(r => {
        r.addEventListener('change', e => {
          state.selectedSub = e.target.value;
          wrap.querySelectorAll('label').forEach(lab => lab.classList.toggle('is-active', lab.contains(e.target) && e.target.checked));
        });
      });
      // initial active state
      const firstLabel = wrap.querySelector('label');
      if (firstLabel) firstLabel.classList.add('is-active');

      go(2);
    });
  });

  // STEP 2 -> 3
  document.getElementById('srToStep3').addEventListener('click', () => {
    state.description = document.getElementById('srDescription').value.trim();
    go(3);
  });

  // back buttons
  wizard.addEventListener('click', e => {
    const back = e.target.closest('[data-back]');
    if (back) {
      const target = Number(back.dataset.back);
      if (target >= 1 && target <= 4) go(target);
    }
  });

  // STEP 3 submit
  document.getElementById('srSubmit').addEventListener('click', async function () {
    const submitBtn = this;
    const errBox  = document.getElementById('srError');
    errBox.style.display = 'none';
    state.flat          = document.getElementById('srFlat').value.trim();
    state.phone         = document.getElementById('srPhone').value.trim();
    state.preferredTime = document.getElementById('srTime').value;
    const urg = wizard.querySelector('input[name="srUrgency"]:checked');
    state.urgency = urg ? urg.value : 'normal';

    if (!state.flat) {
      errBox.textContent = 'Please enter your flat / unit number.';
      errBox.style.display = '';
      document.getElementById('srFlat').focus();
      return;
    }

    submitBtn.disabled = true;
    submitBtn.querySelector('.btn-text').style.display = 'none';
    submitBtn.querySelector('.btn-loading').style.display = '';

    const fd = new FormData();
    fd.append('action',         'nc_submit_service_request');
    fd.append('nonce',           ncData.nonce);
    fd.append('service_id',      state.serviceId);
    fd.append('sub_service',     state.selectedSub);
    fd.append('description',     state.description);
    fd.append('flat',            state.flat);
    fd.append('phone',           state.phone);
    fd.append('preferred_time',  state.preferredTime);
    fd.append('urgency',         state.urgency);

    try {
      const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: fd });
      const json = await res.json();
      submitBtn.disabled = false;
      submitBtn.querySelector('.btn-text').style.display = '';
      submitBtn.querySelector('.btn-loading').style.display = 'none';

      if (!json.success) {
        if (json.data?.redirect) {
          window.location.href = json.data.redirect;
          return;
        }
        errBox.textContent = json.data?.message || 'Could not submit your request. Please try again.';
        errBox.style.display = '';
        return;
      }

      const d = json.data;
      document.getElementById('srRef').textContent       = d.reference || ('SR-' + d.request_id);
      document.getElementById('srSvc').textContent       = d.service || state.serviceTitle;
      document.getElementById('srSub').textContent       = d.sub_service || state.selectedSub || '—';
      document.getElementById('srStatus').textContent    = d.status === 'assigned' ? 'Assigned' : 'Pending';
      document.getElementById('srStatus').className      = 'sr-status-pill sr-pill-' + (d.status === 'assigned' ? 'assigned' : 'pending');
      document.getElementById('srTeamName').textContent  = d.assigned_name || 'Will be assigned shortly';
      document.getElementById('srTeamPhone').textContent = d.assigned_phone || '';

      go(4);
    } catch (err) {
      submitBtn.disabled = false;
      submitBtn.querySelector('.btn-text').style.display = '';
      submitBtn.querySelector('.btn-loading').style.display = 'none';
      errBox.textContent = 'Network error. Please try again.';
      errBox.style.display = '';
    }
  });

  // new request
  document.getElementById('srNew').addEventListener('click', () => {
    state.serviceId = 0; state.selectedSub = ''; state.description = '';
    document.getElementById('srDescription').value = '';
    document.getElementById('srFlat').value = '';
    go(1);
  });

  // util
  function escapeHtml(s) { return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
}());
</script>

<?php get_footer(); ?>
