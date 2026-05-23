<?php
/**
 * Template Name: Register Page
 */
if (is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}
get_header();
?>

<div class="auth-split">

  <!-- Left Branding Panel -->
  <div class="auth-brand">
    <div class="auth-brand-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="auth-logo">
        <span class="logo-mark"><i class="fa-solid fa-house-chimney"></i></span>
        <span><?php echo esc_html(get_theme_mod('nc_neighborhood', 'Neighborhood')); ?> <em>Connect</em></span>
      </a>
      <h1 class="auth-brand-headline">Join the Canal View Society app</h1>
      <p class="auth-brand-sub">Book in-society services, RSVP to community events, report issues, and find every school, pharmacy and ATM around the society — in one place.</p>

      <div class="auth-stats-row">
        <div class="auth-stat">
          <strong>5,400+</strong>
          <span>Residents</span>
        </div>
        <div class="auth-stat">
          <strong>10</strong>
          <span>On-call service teams</span>
        </div>
        <div class="auth-stat">
          <strong>37</strong>
          <span>Nearby amenities mapped</span>
        </div>
      </div>

      <div class="auth-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa-solid fa-users"></i></div>
          <div>
            <strong>One App, Whole Society</strong>
            <span>Service requests, events, issues — without WhatsApp chaos</span>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa-solid fa-bell"></i></div>
          <div>
            <strong>Real-Time Updates</strong>
            <span>Maintenance, security and AGM notices straight to your phone</span>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <strong>Residents Only</strong>
            <span>Verified Canal View residents — your data stays inside the society</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Form Panel -->
  <div class="auth-form-panel">
    <div class="auth-form-wrap">
      <h2 class="auth-form-title">Create your free account</h2>
      <p class="auth-form-sub">Already have an account? <a href="<?php echo esc_url(home_url('/login/')); ?>">Sign in</a></p>

      <div id="register-message" class="auth-alert" style="display:none;"></div>

      <form id="nc-register-form" class="auth-form" novalidate>
        <?php wp_nonce_field('nc_register_nonce', 'nc_register_nonce_field'); ?>

        <div class="form-row-2col">
          <div class="form-group">
            <label for="reg-firstname" class="form-label">First Name</label>
            <input type="text" id="reg-firstname" name="first_name" class="form-input" placeholder="Jane" required>
          </div>
          <div class="form-group">
            <label for="reg-lastname" class="form-label">Last Name</label>
            <input type="text" id="reg-lastname" name="last_name" class="form-input" placeholder="Doe" required>
          </div>
        </div>

        <div class="form-group">
          <label for="reg-email" class="form-label">Email Address</label>
          <div class="input-icon-wrap">
            <i class="fa-solid fa-envelope input-icon"></i>
            <input type="email" id="reg-email" name="user_email" class="form-input input-with-icon" placeholder="jane@example.com" required autocomplete="email">
          </div>
        </div>

        <div class="form-group">
          <label for="reg-username" class="form-label">Username</label>
          <div class="input-icon-wrap">
            <i class="fa-solid fa-at input-icon"></i>
            <input type="text" id="reg-username" name="user_login" class="form-input input-with-icon" placeholder="janedoe" required autocomplete="username" pattern="[a-zA-Z0-9_\-]+">
          </div>
          <span class="form-hint">Letters, numbers, underscores only</span>
        </div>

        <div class="form-group">
          <label for="reg-password" class="form-label">Password</label>
          <div class="input-icon-wrap">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" id="reg-password" name="user_pass" class="form-input input-with-icon" placeholder="Min. 8 characters" required minlength="8" autocomplete="new-password">
            <button type="button" class="input-toggle-pw" aria-label="Toggle password visibility">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
          <div class="password-strength" id="pw-strength"></div>
        </div>

        <label class="form-checkbox">
          <input type="checkbox" name="agree_terms" required>
          <span>I agree to the <a href="<?php echo esc_url(home_url('/terms/')); ?>" target="_blank">Terms of Service</a> and <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" target="_blank">Privacy Policy</a></span>
        </label>

        <button type="submit" class="btn btn-primary btn-full" id="register-submit" style="margin-top:var(--s-5);">
          <span class="btn-text">Create Account</span>
          <span class="btn-loading" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i> Creating…</span>
        </button>
      </form>

      <p class="auth-legal" style="margin-top:var(--s-5);">Free forever. No credit card required.</p>
    </div>
  </div>

</div>

<script>
(function () {
  // Password strength meter
  const pwInput = document.getElementById('reg-password');
  const strengthEl = document.getElementById('pw-strength');
  pwInput?.addEventListener('input', function () {
    const v = this.value;
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const levels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
    const colors = ['', '#ef4444', '#f59e0b', '#10b981', '#059669'];
    strengthEl.innerHTML = score > 0
      ? `<span style="color:${colors[score]};font-size:.8rem;font-weight:500;">${levels[score]} password</span>`
      : '';
  });

  // Password toggle
  document.querySelector('.input-toggle-pw')?.addEventListener('click', function () {
    const pw = document.getElementById('reg-password');
    const icon = this.querySelector('i');
    if (pw.type === 'password') {
      pw.type = 'text';
      icon.className = 'fa-solid fa-eye-slash';
    } else {
      pw.type = 'password';
      icon.className = 'fa-solid fa-eye';
    }
  });

  // Username auto-fill from first+last
  const fn = document.getElementById('reg-firstname');
  const ln = document.getElementById('reg-lastname');
  const un = document.getElementById('reg-username');
  function autoUsername() {
    if (un.value !== '' && !un.dataset.manual) return;
    const suggestion = (fn.value + ln.value).toLowerCase().replace(/[^a-z0-9]/g, '');
    if (suggestion) { un.value = suggestion; un.dataset.manual = ''; }
  }
  fn?.addEventListener('blur', autoUsername);
  ln?.addEventListener('blur', autoUsername);
  un?.addEventListener('input', function () { this.dataset.manual = 'yes'; });

  // Form submit
  const form = document.getElementById('nc-register-form');
  const msg = document.getElementById('register-message');
  const submitBtn = document.getElementById('register-submit');

  form?.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!form.querySelector('[name="agree_terms"]').checked) {
      msg.className = 'auth-alert auth-alert-error';
      msg.textContent = 'Please agree to the Terms of Service.';
      msg.style.display = '';
      return;
    }

    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoad = submitBtn.querySelector('.btn-loading');
    btnText.style.display = 'none';
    btnLoad.style.display = '';
    submitBtn.disabled = true;
    msg.style.display = 'none';

    const data = new FormData(form);
    data.append('action', 'nc_ajax_register');
    data.append('nonce', ncData.nonce);

    try {
      const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        msg.className = 'auth-alert auth-alert-success';
        msg.textContent = json.data.message || 'Account created! Redirecting…';
        msg.style.display = '';
        setTimeout(() => { window.location.href = json.data.redirect || '<?php echo esc_js(home_url('/')); ?>'; }, 1200);
      } else {
        msg.className = 'auth-alert auth-alert-error';
        msg.textContent = json.data?.message || 'Registration failed. Please try again.';
        msg.style.display = '';
        btnText.style.display = '';
        btnLoad.style.display = 'none';
        submitBtn.disabled = false;
      }
    } catch {
      msg.className = 'auth-alert auth-alert-error';
      msg.textContent = 'Network error. Please try again.';
      msg.style.display = '';
      btnText.style.display = '';
      btnLoad.style.display = 'none';
      submitBtn.disabled = false;
    }
  });
}());
</script>

<?php get_footer(); ?>
