<?php
/**
 * Neighborhood — About the society + nearby amenities directory.
 * Replaces the old "Community News & Stories" view.
 */
get_header();

$cats = function_exists('nc_amenity_categories') ? nc_amenity_categories() : [];

// All amenities, grouped by category
$amenities = get_posts([
    'post_type'      => 'nc_amenity',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
]);

$grouped = [];
foreach ($amenities as $a) {
    $cat = get_post_meta($a->ID, '_nc_am_category', true) ?: 'other';
    $grouped[$cat][] = $a;
}

// Society "About" — pulls from theme mods so chairman can edit in Customizer
$society_name      = get_theme_mod('nc_neighborhood', 'Canal View Society');
$society_tagline   = get_theme_mod('nc_society_tagline', 'A walkable, well-connected residential community along the BRB Canal in Lahore.');
$society_founded   = get_theme_mod('nc_society_founded', '1986');
$society_units     = get_theme_mod('nc_society_units', '1,200+');
$society_residents = get_theme_mod('nc_society_residents', '5,400+');
$society_area      = get_theme_mod('nc_society_area', '450+ kanals');
$society_blocks    = get_theme_mod('nc_society_blocks', 'Phase 1 · Phase 2 · Block A · Block B · Block C');

$member_count = (int) count_users()['total_users'];
?>

<!-- ABOUT HERO -->
<section class="nb-hero" style="background:linear-gradient(135deg,#0f766e 0%,#1e40af 100%);color:white;padding:var(--s-16) 0 var(--s-10);">
  <div class="container">
    <div class="breadcrumb" style="margin-bottom:var(--s-4);">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="color:rgba(255,255,255,.75);">Home</a>
      <span style="margin:0 var(--s-2);color:rgba(255,255,255,.5);">/</span>
      <span style="color:white;">Neighborhood</span>
    </div>

    <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:var(--s-10);align-items:center;">
      <div>
        <div class="hero-pill" style="background:rgba(255,255,255,.18);color:white;display:inline-flex;margin-bottom:var(--s-4);">
          <i class="fa-solid fa-location-dot"></i> <?php echo esc_html($society_name); ?>
        </div>
        <h1 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:var(--fw-bold);line-height:1.15;margin-bottom:var(--s-4);">
          About <?php echo esc_html($society_name); ?>
        </h1>
        <p style="font-size:var(--text-lg);opacity:.92;line-height:1.6;max-width:540px;">
          <?php echo esc_html($society_tagline); ?>
        </p>
        <p style="font-size:var(--text-base);opacity:.85;line-height:1.7;margin-top:var(--s-4);max-width:540px;">
          Below is a quick guide to everything within walking and driving distance of the society — parks, schools, hospitals, banks, groceries and more — so new residents and guests always know where to go.
        </p>
      </div>

      <!-- Society Facts Card -->
      <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:var(--radius-xl);padding:var(--s-6);backdrop-filter:blur(6px);">
        <div style="font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;opacity:.7;margin-bottom:var(--s-3);">Society at a glance</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--s-4) var(--s-3);">
          <div><div style="font-size:1.4rem;font-weight:700;"><?php echo esc_html($society_founded); ?></div><div style="font-size:.78rem;opacity:.75;">Founded</div></div>
          <div><div style="font-size:1.4rem;font-weight:700;"><?php echo esc_html($society_units); ?></div><div style="font-size:.78rem;opacity:.75;">Apartments</div></div>
          <div><div style="font-size:1.4rem;font-weight:700;"><?php echo esc_html($society_residents); ?></div><div style="font-size:.78rem;opacity:.75;">Residents</div></div>
          <div><div style="font-size:1.4rem;font-weight:700;"><?php echo esc_html($society_area); ?></div><div style="font-size:.78rem;opacity:.75;">Area</div></div>
          <div style="grid-column:1/-1;border-top:1px dashed rgba(255,255,255,.25);padding-top:var(--s-3);">
            <div style="font-size:.78rem;opacity:.75;margin-bottom:4px;">Blocks</div>
            <div style="font-weight:600;letter-spacing:.05em;"><?php echo esc_html($society_blocks); ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Hero stats row -->
    <div style="display:flex;flex-wrap:wrap;gap:var(--s-6);margin-top:var(--s-8);padding-top:var(--s-6);border-top:1px solid rgba(255,255,255,.18);font-size:var(--text-sm);">
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-shield-halved"></i> 24/7 gate security &amp; CCTV</div>
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-road"></i> 80ft Main Boulevard, 60/40ft streets</div>
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-water"></i> Underground water &amp; sewerage</div>
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-trash"></i> Daily door-to-door waste pickup</div>
      <div style="display:flex;align-items:center;gap:var(--s-2);"><i class="fa-solid fa-bolt"></i> Underground electrification</div>
    </div>
  </div>
</section>

<!-- AMENITIES DIRECTORY -->
<section class="section" style="padding-top:var(--s-12);padding-bottom:var(--s-16);">
  <div class="container">

    <div class="section-header" style="text-align:left;margin-bottom:var(--s-6);">
      <div class="section-tag" style="margin-bottom:var(--s-3);"><i class="fa-solid fa-map-location-dot"></i> What's nearby</div>
      <h2 class="section-title" style="margin-bottom:var(--s-2);">Everything around your home</h2>
      <p class="section-desc" style="margin:0;max-width:640px;">
        Curated list of amenities residents use most. Distances are measured from the society's main gate.
        Filter by category below or scroll to browse.
      </p>
    </div>

    <!-- Category filter tabs -->
    <div class="am-tabs" role="tablist">
      <button class="am-tab is-active" data-cat="all" type="button">
        <i class="fa-solid fa-grip"></i> All <span class="am-tab-count"><?php echo esc_html(count($amenities)); ?></span>
      </button>
      <?php foreach ($cats as $slug => $meta) :
        $count = isset($grouped[$slug]) ? count($grouped[$slug]) : 0;
        if (!$count) continue;
      ?>
      <button class="am-tab" data-cat="<?php echo esc_attr($slug); ?>" type="button" style="--am-color:<?php echo esc_attr($meta['color']); ?>;">
        <i class="fa-solid <?php echo esc_attr($meta['fa']); ?>"></i>
        <?php echo esc_html($meta['label']); ?>
        <span class="am-tab-count"><?php echo esc_html($count); ?></span>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Amenity grid -->
    <div class="am-grid" id="amGrid">
      <?php
      foreach ($cats as $slug => $meta) :
        if (empty($grouped[$slug])) continue;
        foreach ($grouped[$slug] as $a) :
          $dist  = get_post_meta($a->ID, '_nc_am_distance', true);
          $addr  = get_post_meta($a->ID, '_nc_am_address', true);
          $phone = get_post_meta($a->ID, '_nc_am_phone', true);
          $hours = get_post_meta($a->ID, '_nc_am_hours', true);
          $map_q = urlencode($a->post_title . ', ' . ($addr ?: $meta['label']));
      ?>
      <article class="am-card" data-cat="<?php echo esc_attr($slug); ?>" style="--am-color:<?php echo esc_attr($meta['color']); ?>;">
        <div class="am-card-icon" style="background:<?php echo esc_attr($meta['color']); ?>18;color:<?php echo esc_attr($meta['color']); ?>;">
          <i class="fa-solid <?php echo esc_attr($meta['fa']); ?>"></i>
        </div>
        <div class="am-card-body">
          <div class="am-card-cat" style="color:<?php echo esc_attr($meta['color']); ?>;"><?php echo esc_html($meta['label']); ?></div>
          <h3 class="am-card-title"><?php echo esc_html($a->post_title); ?></h3>
          <?php if ($addr) : ?>
            <div class="am-card-meta"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($addr); ?></div>
          <?php endif; ?>
          <?php if ($hours) : ?>
            <div class="am-card-meta"><i class="fa-regular fa-clock"></i> <?php echo esc_html($hours); ?></div>
          <?php endif; ?>
          <?php if ($phone) : ?>
            <div class="am-card-meta"><i class="fa-solid fa-phone"></i> <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></div>
          <?php endif; ?>
          <div class="am-card-foot">
            <?php if ($dist) : ?><span class="am-distance"><i class="fa-solid fa-route"></i> <?php echo esc_html($dist); ?></span><?php endif; ?>
            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo esc_attr($map_q); ?>" class="am-map-link" target="_blank" rel="noopener">
              <i class="fa-regular fa-map"></i> Open in Maps
            </a>
          </div>
        </div>
      </article>
      <?php endforeach; endforeach; ?>
    </div>

    <!-- Empty state placeholder -->
    <div id="amEmpty" class="am-empty" style="display:none;">
      <i class="fa-regular fa-face-thinking" style="font-size:2.5rem;color:var(--c-muted);margin-bottom:var(--s-3);"></i>
      <p style="color:var(--c-muted);">No amenities in this category yet.</p>
    </div>

  </div>
</section>

<!-- MANAGEMENT CONTACT STRIP -->
<section class="section section-alt" style="padding-top:var(--s-10);padding-bottom:var(--s-10);">
  <div class="container" style="display:grid;grid-template-columns:1.2fr 1fr;gap:var(--s-8);align-items:center;">
    <div>
      <h2 style="font-size:clamp(1.3rem,2.2vw,1.8rem);margin:0 0 var(--s-3);font-weight:700;">Society management</h2>
      <p style="color:var(--c-muted);margin:0 0 var(--s-3);">For new resident onboarding, lease verification, common-area bookings, or anything that needs the office, reach out here.</p>
      <div style="display:flex;gap:var(--s-6);flex-wrap:wrap;font-size:.95rem;color:var(--c-text);">
        <div><i class="fa-solid fa-phone" style="color:var(--c-primary);margin-right:6px;"></i> +92 42 3700 0000</div>
        <div><i class="fa-solid fa-envelope" style="color:var(--c-primary);margin-right:6px;"></i> office@canalviewsociety.org</div>
        <div><i class="fa-solid fa-location-dot" style="color:var(--c-primary);margin-right:6px;"></i> Canal Bank Road, Lahore</div>
        <div><i class="fa-solid fa-clock" style="color:var(--c-primary);margin-right:6px;"></i> Mon–Sat · 9 AM – 6 PM</div>
      </div>
    </div>
    <div style="display:flex;gap:var(--s-3);flex-wrap:wrap;">
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn btn-primary">
        <i class="fa-solid fa-screwdriver-wrench"></i> Request a service
      </a>
      <a href="<?php echo esc_url(home_url('/issues/')); ?>" class="btn btn-ghost">
        <i class="fa-solid fa-triangle-exclamation"></i> Report an issue
      </a>
    </div>
  </div>
</section>

<style>
.am-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: var(--s-2);
  margin-bottom: var(--s-6);
  padding: var(--s-3);
  background: var(--c-card);
  border: 1px solid var(--c-border);
  border-radius: var(--radius-lg);
}
.am-tab {
  background: transparent;
  border: 1px solid transparent;
  padding: 8px 14px;
  border-radius: 100px;
  font-size: .87rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: var(--c-text-muted, var(--c-muted));
  transition: .15s;
  font-family: inherit;
}
.am-tab:hover { background: var(--c-bg, #f8fafc); color: var(--c-text); }
.am-tab.is-active {
  background: var(--am-color, var(--c-primary));
  color: white;
}
.am-tab.is-active .am-tab-count { background: rgba(255,255,255,.25); color: white; }
.am-tab-count {
  background: var(--c-bg, #f1f5f9);
  color: var(--c-muted);
  padding: 1px 8px;
  border-radius: 999px;
  font-size: .72rem;
  font-weight: 700;
}

.am-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: var(--s-4);
}
.am-card {
  display: flex;
  gap: var(--s-4);
  padding: var(--s-4) var(--s-5);
  border: 1px solid var(--c-border);
  border-radius: var(--radius-lg);
  background: var(--c-card);
  transition: .15s;
}
.am-card:hover {
  border-color: var(--am-color);
  transform: translateY(-2px);
  box-shadow: 0 12px 24px -12px rgba(0,0,0,.12);
}
.am-card-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
}
.am-card-body { flex: 1; min-width: 0; }
.am-card-cat {
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .06em;
  margin-bottom: 4px;
}
.am-card-title {
  font-size: 1.02rem;
  font-weight: 700;
  color: var(--c-text);
  margin: 0 0 var(--s-2);
  line-height: 1.3;
}
.am-card-meta {
  font-size: .82rem;
  color: var(--c-muted);
  margin-bottom: 4px;
  display: flex;
  gap: 6px;
  align-items: flex-start;
}
.am-card-meta i { color: var(--am-color, var(--c-primary)); margin-top: 3px; flex-shrink: 0; }
.am-card-meta a { color: var(--c-text); text-decoration: none; }
.am-card-meta a:hover { color: var(--am-color); text-decoration: underline; }
.am-card-foot {
  margin-top: var(--s-3);
  padding-top: var(--s-3);
  border-top: 1px dashed var(--c-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: .82rem;
  gap: var(--s-2);
  flex-wrap: wrap;
}
.am-distance {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: var(--am-color)15;
  color: var(--am-color);
  padding: 3px 10px;
  border-radius: 999px;
  font-weight: 600;
}
.am-map-link {
  color: var(--c-muted);
  text-decoration: none;
  font-weight: 500;
}
.am-map-link:hover { color: var(--am-color); }
.am-empty {
  text-align: center;
  padding: var(--s-12) var(--s-4);
}
</style>

<script>
(function () {
  const tabs = document.querySelectorAll('.am-tab');
  const cards = document.querySelectorAll('.am-card');
  const empty = document.getElementById('amEmpty');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const cat = tab.dataset.cat;
      tabs.forEach(t => t.classList.toggle('is-active', t === tab));
      let shown = 0;
      cards.forEach(card => {
        const match = cat === 'all' || card.dataset.cat === cat;
        card.style.display = match ? '' : 'none';
        if (match) shown++;
      });
      empty.style.display = shown ? 'none' : '';
    });
  });
}());
</script>

<?php get_footer(); ?>
