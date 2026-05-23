<?php
/**
 * Template Name: Login Page
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
      <h1 class="auth-brand-headline">Welcome back, Canal View resident</h1>
      <p class="auth-brand-sub">Sign in to request the in-house service team, RSVP to society events, track issues, and browse everything around your block.</p>

      <div class="auth-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
          <div>
            <strong>RSVP to Society Events</strong>
            <span>Block parties, Sunday bazaar, sports leagues</span>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
          <div>
            <strong>In-House Services</strong>
            <span>Plumber, electrician, tiffin — at your door</span>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div>
            <strong>Report Society Issues</strong>
            <span>Streetlights, potholes, security concerns</span>
          </div>
        </div>
      </div>

      <div class="auth-testimonial">
        <p>"Canal View finally feels like a managed community. The Sunday bazaar, the in-house plumber a tap away, and a live list of every nearby school and pharmacy — it's like a welcome book that's always up to date."</p>
        <div class="auth-testimonial-author">
          <div class="avatar-initials" style="background:#4f46e5;width:36px;height:36px;font-size:.8rem;">SA</div>
          <div>
            <strong>Saima A.</strong>
            <span>Canal View · Phase 1</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Form Panel -->
  <div class="auth-form-panel">
    <div class="auth-form-wrap">
      <h2 class="auth-form-title">Sign in to your account</h2>
      <p class="auth-form-sub">Don't have an account? <a href="<?php echo esc_url(home_url('/register/')); ?>">Join free</a></p>

      <div id="login-message" class="auth-alert" style="display:none;"></div>

      <form id="nc-login-form" class="auth-form" novalidate>
        <?php wp_nonce_field('nc_login_nonce', 'nc_login_nonce_field'); ?>

        <div class="form-group">
          <label for="login-email" class="form-label">Email or Username</label>
          <div class="input-icon-wrap">
            <i class="fa-solid fa-envelope input-icon"></i>
            <input type="text" id="login-email" name="log" class="form-input input-with-icon" placeholder="you@example.com" required autocomplete="username">
          </div>
        </div>

        <div class="form-group">
          <label for="login-password" class="form-label">Password</label>
          <div class="input-icon-wrap">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" id="login-password" name="pwd" class="form-input input-with-icon" placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="input-toggle-pw" aria-label="Toggle password visibility">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-row-flex">
          <label class="form-checkbox">
            <input type="checkbox" name="rememberme" value="forever">
            <span>Remember me</span>
          </label>
          <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="auth-forgot">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-full" id="login-submit">
          <span class="btn-text">Sign In</span>
          <span class="btn-loading" style="display:none;"><i class="fa-solid fa-spinner fa-spin"></i> Signing in…</span>
        </button>
      </form>

      <div class="auth-divider"><span>or continue with</span></div>

      <div class="auth-social-btns">
        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-ghost btn-social">
          <i class="fa-brands fa-google"></i> Google
        </a>
        <a href="<?php echo esc_url(wp_login_url()); ?>" class="btn btn-ghost btn-social">
          <i class="fa-brands fa-facebook-f"></i> Facebook
        </a>
      </div>

      <p class="auth-legal">By signing in you agree to our <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a> and <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>.</p>
    </div>
  </div>

</div>

<script>
(function () {
  const form = document.getElementById('nc-login-form');
  const msg = document.getElementById('login-message');
  const submitBtn = document.getElementById('login-submit');

  // Password toggle
  document.querySelector('.input-toggle-pw')?.addEventListener('click', function () {
    const pw = document.getElementById('login-password');
    const icon = this.querySelector('i');
    if (pw.type === 'password') {
      pw.type = 'text';
      icon.className = 'fa-solid fa-eye-slash';
    } else {
      pw.type = 'password';
      icon.className = 'fa-solid fa-eye';
    }
  });

  form?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoad = submitBtn.querySelector('.btn-loading');
    btnText.style.display = 'none';
    btnLoad.style.display = '';
    submitBtn.disabled = true;
    msg.style.display = 'none';

    const data = new FormData(form);
    data.append('action', 'nc_ajax_login');
    data.append('nonce', ncData.nonce);

    try {
      const res = await fetch(ncData.ajaxUrl, { method: 'POST', body: data });
      const json = await res.json();
      if (json.success) {
        msg.className = 'auth-alert auth-alert-success';
        msg.textContent = json.data.message || 'Logged in! Redirecting…';
        msg.style.display = '';
        setTimeout(() => { window.location.href = json.data.redirect || '<?php echo esc_js(home_url('/')); ?>'; }, 800);
      } else {
        msg.className = 'auth-alert auth-alert-error';
        msg.textContent = json.data?.message || 'Login failed. Please try again.';
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
