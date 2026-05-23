<?php
/**
 * Plugin Name:       NC Core
 * Plugin URI:        https://github.com/khursandsohail/neighborhood-connect
 * Description:       Core custom post types, taxonomies, and meta fields for Neighborhood Connect.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Khursand Sohail
 * Text Domain:       nc-core
 * License:           MIT
 */

defined('ABSPATH') || exit;

/* ============================================================
   Custom Post Types
   ============================================================ */
function nc_register_post_types() {

    // Events
    register_post_type('nc_event', [
        'labels' => [
            'name'               => __('Events', 'nc-core'),
            'singular_name'      => __('Event', 'nc-core'),
            'add_new'            => __('Add New Event', 'nc-core'),
            'add_new_item'       => __('Add New Event', 'nc-core'),
            'edit_item'          => __('Edit Event', 'nc-core'),
            'view_item'          => __('View Event', 'nc-core'),
            'search_items'       => __('Search Events', 'nc-core'),
            'not_found'          => __('No events found.', 'nc-core'),
            'all_items'          => __('All Events', 'nc-core'),
        ],
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => 'events',
        'rewrite'            => ['slug' => 'events', 'with_front' => false],
        'menu_icon'          => 'dashicons-calendar-alt',
        'menu_position'      => 5,
        'supports'           => ['title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments'],
        'show_in_nav_menus'  => true,
        'capability_type'    => 'post',
    ]);

    // Services
    register_post_type('nc_service', [
        'labels' => [
            'name'          => __('Services', 'nc-core'),
            'singular_name' => __('Service', 'nc-core'),
            'add_new'       => __('Add New Service', 'nc-core'),
            'add_new_item'  => __('Add New Service', 'nc-core'),
            'edit_item'     => __('Edit Service', 'nc-core'),
            'view_item'     => __('View Service', 'nc-core'),
            'search_items'  => __('Search Services', 'nc-core'),
            'all_items'     => __('All Services', 'nc-core'),
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'has_archive'       => 'services',
        'rewrite'           => ['slug' => 'services', 'with_front' => false],
        'menu_icon'         => 'dashicons-businessperson',
        'menu_position'     => 6,
        'supports'          => ['title', 'editor', 'excerpt', 'thumbnail', 'author'],
        'show_in_nav_menus' => true,
        'capability_type'   => 'post',
    ]);

    // Service Requests (resident-submitted tickets)
    register_post_type('nc_service_request', [
        'labels' => [
            'name'          => __('Service Requests', 'nc-core'),
            'singular_name' => __('Service Request', 'nc-core'),
            'add_new'       => __('Add Request', 'nc-core'),
            'add_new_item'  => __('Add New Request', 'nc-core'),
            'edit_item'     => __('Edit Request', 'nc-core'),
            'view_item'     => __('View Request', 'nc-core'),
            'all_items'     => __('Service Requests', 'nc-core'),
            'search_items'  => __('Search Requests', 'nc-core'),
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_rest'      => false,
        'menu_icon'         => 'dashicons-clipboard',
        'menu_position'     => 8,
        'supports'          => ['title', 'editor', 'author'],
        'capability_type'   => 'post',
    ]);

    // Neighborhood Amenities
    register_post_type('nc_amenity', [
        'labels' => [
            'name'          => __('Amenities', 'nc-core'),
            'singular_name' => __('Amenity', 'nc-core'),
            'add_new'       => __('Add Amenity', 'nc-core'),
            'add_new_item'  => __('Add New Amenity', 'nc-core'),
            'edit_item'     => __('Edit Amenity', 'nc-core'),
            'view_item'     => __('View Amenity', 'nc-core'),
            'all_items'     => __('All Amenities', 'nc-core'),
            'search_items'  => __('Search Amenities', 'nc-core'),
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'has_archive'       => 'amenities',
        'rewrite'           => ['slug' => 'amenities', 'with_front' => false],
        'menu_icon'         => 'dashicons-location',
        'menu_position'     => 9,
        'supports'          => ['title', 'editor', 'excerpt', 'thumbnail'],
        'show_in_nav_menus' => true,
        'capability_type'   => 'post',
    ]);

    // Community Issues
    register_post_type('nc_issue', [
        'labels' => [
            'name'          => __('Community Issues', 'nc-core'),
            'singular_name' => __('Issue', 'nc-core'),
            'add_new'       => __('Report Issue', 'nc-core'),
            'add_new_item'  => __('Report New Issue', 'nc-core'),
            'edit_item'     => __('Edit Issue', 'nc-core'),
            'view_item'     => __('View Issue', 'nc-core'),
            'search_items'  => __('Search Issues', 'nc-core'),
            'all_items'     => __('All Issues', 'nc-core'),
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'has_archive'       => 'issues',
        'rewrite'           => ['slug' => 'issues', 'with_front' => false],
        'menu_icon'         => 'dashicons-warning',
        'menu_position'     => 7,
        'supports'          => ['title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments'],
        'show_in_nav_menus' => true,
        'capability_type'   => 'post',
    ]);
}
add_action('init', 'nc_register_post_types');

/* ============================================================
   Custom Taxonomies
   ============================================================ */
function nc_register_taxonomies() {

    // Event Categories
    register_taxonomy('nc_event_category', 'nc_event', [
        'labels' => [
            'name'          => __('Event Categories', 'nc-core'),
            'singular_name' => __('Event Category', 'nc-core'),
            'all_items'     => __('All Categories', 'nc-core'),
            'edit_item'     => __('Edit Category', 'nc-core'),
            'add_new_item'  => __('Add New Category', 'nc-core'),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'event-category'],
        'show_admin_column' => true,
    ]);

    // Service Categories
    register_taxonomy('nc_service_category', 'nc_service', [
        'labels' => [
            'name'          => __('Service Categories', 'nc-core'),
            'singular_name' => __('Service Category', 'nc-core'),
            'all_items'     => __('All Categories', 'nc-core'),
            'add_new_item'  => __('Add New Category', 'nc-core'),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'service-category'],
        'show_admin_column' => true,
    ]);

    // Issue Types
    register_taxonomy('nc_issue_type', 'nc_issue', [
        'labels' => [
            'name'          => __('Issue Types', 'nc-core'),
            'singular_name' => __('Issue Type', 'nc-core'),
            'all_items'     => __('All Types', 'nc-core'),
            'add_new_item'  => __('Add New Type', 'nc-core'),
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'issue-type'],
        'show_admin_column' => true,
    ]);

    // Neighborhoods (shared)
    register_taxonomy('nc_neighborhood', ['nc_event', 'nc_service', 'nc_issue'], [
        'labels' => [
            'name'          => __('Neighborhoods', 'nc-core'),
            'singular_name' => __('Neighborhood', 'nc-core'),
            'all_items'     => __('All Neighborhoods', 'nc-core'),
            'add_new_item'  => __('Add Neighborhood', 'nc-core'),
        ],
        'hierarchical'      => false,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'neighborhood'],
        'show_admin_column' => true,
    ]);
}
add_action('init', 'nc_register_taxonomies');

/* ============================================================
   Meta Boxes
   ============================================================ */
function nc_add_meta_boxes() {
    add_meta_box('nc_event_details',   __('Event Details', 'nc-core'),   'nc_event_meta_box',   'nc_event',   'normal', 'high');
    add_meta_box('nc_service_details', __('Service Details', 'nc-core'), 'nc_service_meta_box', 'nc_service', 'normal', 'high');
    add_meta_box('nc_issue_details',   __('Issue Details', 'nc-core'),   'nc_issue_meta_box',   'nc_issue',   'normal', 'high');
}
add_action('add_meta_boxes', 'nc_add_meta_boxes');

function nc_event_meta_box(WP_Post $post) {
    wp_nonce_field('nc_event_meta', 'nc_event_nonce');
    $m = nc_core_get_event_meta($post->ID);
    ?>
    <style>.nc-meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem;} .nc-field{margin-bottom:.5rem;} .nc-field label{display:block;font-weight:600;margin-bottom:.25rem;} .nc-field input,.nc-field select,.nc-field textarea{width:100%;padding:.375rem .5rem;border:1px solid #ddd;border-radius:4px;}</style>
    <div class="nc-meta-grid">
      <div class="nc-field">
        <label for="nc_event_date"><?php esc_html_e('Event Date', 'nc-core'); ?></label>
        <input type="date" id="nc_event_date" name="nc_event_date" value="<?php echo esc_attr($m['date']); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_event_time"><?php esc_html_e('Start Time', 'nc-core'); ?></label>
        <input type="time" id="nc_event_time" name="nc_event_time" value="<?php echo esc_attr($m['time']); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_event_end_time"><?php esc_html_e('End Time', 'nc-core'); ?></label>
        <input type="time" id="nc_event_end_time" name="nc_event_end_time" value="<?php echo esc_attr($m['end_time']); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_capacity"><?php esc_html_e('Capacity', 'nc-core'); ?></label>
        <input type="number" id="nc_capacity" name="nc_capacity" value="<?php echo esc_attr($m['capacity']); ?>" min="0">
      </div>
      <div class="nc-field" style="grid-column:1/-1;">
        <label for="nc_location"><?php esc_html_e('Location / Address', 'nc-core'); ?></label>
        <input type="text" id="nc_location" name="nc_location" value="<?php echo esc_attr($m['location']); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_lat"><?php esc_html_e('Latitude', 'nc-core'); ?></label>
        <input type="text" id="nc_lat" name="nc_lat" value="<?php echo esc_attr($m['lat']); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_lng"><?php esc_html_e('Longitude', 'nc-core'); ?></label>
        <input type="text" id="nc_lng" name="nc_lng" value="<?php echo esc_attr($m['lng']); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_event_category"><?php esc_html_e('Category', 'nc-core'); ?></label>
        <select id="nc_event_category" name="nc_event_category">
          <?php
          foreach (['Social','Sports','Education','Arts','Food','Health','Environment','Other'] as $cat) {
              printf('<option value="%s"%s>%s</option>', esc_attr(strtolower($cat)), selected($m['category'], strtolower($cat), false), esc_html($cat));
          }
          ?>
        </select>
      </div>
    </div>
    <?php
}

function nc_service_meta_box(WP_Post $post) {
    wp_nonce_field('nc_service_meta', 'nc_service_nonce');
    $category = get_post_meta($post->ID, '_nc_service_category', true);
    $price    = get_post_meta($post->ID, '_nc_price', true);
    $rating   = get_post_meta($post->ID, '_nc_rating', true);
    $phone    = get_post_meta($post->ID, '_nc_phone', true);
    $email    = get_post_meta($post->ID, '_nc_email', true);
    $website  = get_post_meta($post->ID, '_nc_website', true);
    ?>
    <div class="nc-meta-grid">
      <div class="nc-field">
        <label for="nc_service_category"><?php esc_html_e('Service Category', 'nc-core'); ?></label>
        <select id="nc_service_category" name="nc_service_category">
          <?php foreach (['Plumbing','Electrical','Gardening','Cleaning','Tutoring','Pet Care','IT Support','Carpentry','Beauty','Other'] as $c) :?>
            <option value="<?php echo esc_attr($c); ?>" <?php selected($category, $c); ?>><?php echo esc_html($c); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="nc-field">
        <label for="nc_price"><?php esc_html_e('Starting Price', 'nc-core'); ?></label>
        <input type="text" id="nc_price" name="nc_price" value="<?php echo esc_attr($price); ?>" placeholder="e.g. From $60/hr">
      </div>
      <div class="nc-field">
        <label for="nc_rating"><?php esc_html_e('Rating (0–5)', 'nc-core'); ?></label>
        <input type="number" id="nc_rating" name="nc_rating" value="<?php echo esc_attr($rating); ?>" min="0" max="5" step="0.1">
      </div>
      <div class="nc-field">
        <label for="nc_phone"><?php esc_html_e('Phone', 'nc-core'); ?></label>
        <input type="tel" id="nc_phone" name="nc_phone" value="<?php echo esc_attr($phone); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_email"><?php esc_html_e('Email', 'nc-core'); ?></label>
        <input type="email" id="nc_email" name="nc_email" value="<?php echo esc_attr($email); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_website"><?php esc_html_e('Website', 'nc-core'); ?></label>
        <input type="url" id="nc_website" name="nc_website" value="<?php echo esc_attr($website); ?>">
      </div>
    </div>
    <?php
}

function nc_issue_meta_box(WP_Post $post) {
    wp_nonce_field('nc_issue_meta', 'nc_issue_nonce');
    $status   = get_post_meta($post->ID, '_nc_status', true) ?: 'open';
    $location = get_post_meta($post->ID, '_nc_location', true);
    $lat      = get_post_meta($post->ID, '_nc_lat', true);
    $lng      = get_post_meta($post->ID, '_nc_lng', true);
    $votes    = (int) get_post_meta($post->ID, '_nc_votes', true);
    $voters   = count(get_post_meta($post->ID, '_nc_voters', true) ?: []);
    ?>
    <div class="nc-meta-grid">
      <div class="nc-field">
        <label for="nc_status"><?php esc_html_e('Status', 'nc-core'); ?></label>
        <select id="nc_status" name="nc_status">
          <option value="open" <?php selected($status, 'open'); ?>><?php esc_html_e('Open', 'nc-core'); ?></option>
          <option value="in-progress" <?php selected($status, 'in-progress'); ?>><?php esc_html_e('In Progress', 'nc-core'); ?></option>
          <option value="resolved" <?php selected($status, 'resolved'); ?>><?php esc_html_e('Resolved', 'nc-core'); ?></option>
        </select>
      </div>
      <div class="nc-field">
        <label><?php esc_html_e('Votes', 'nc-core'); ?></label>
        <input type="text" value="<?php echo esc_attr($votes . ' votes, ' . $voters . ' unique voters'); ?>" readonly>
      </div>
      <div class="nc-field" style="grid-column:1/-1;">
        <label for="nc_location"><?php esc_html_e('Location / Address', 'nc-core'); ?></label>
        <input type="text" id="nc_location" name="nc_location" value="<?php echo esc_attr($location); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_lat"><?php esc_html_e('Latitude', 'nc-core'); ?></label>
        <input type="text" id="nc_lat" name="nc_lat" value="<?php echo esc_attr($lat); ?>">
      </div>
      <div class="nc-field">
        <label for="nc_lng"><?php esc_html_e('Longitude', 'nc-core'); ?></label>
        <input type="text" id="nc_lng" name="nc_lng" value="<?php echo esc_attr($lng); ?>">
      </div>
    </div>
    <?php
}

/* ============================================================
   Save Meta Box Data
   ============================================================ */
function nc_save_event_meta(int $post_id) {
    if (!isset($_POST['nc_event_nonce']) || !wp_verify_nonce($_POST['nc_event_nonce'], 'nc_event_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_nc_event_date'     => 'nc_event_date',
        '_nc_event_time'     => 'nc_event_time',
        '_nc_event_end_time' => 'nc_event_end_time',
        '_nc_capacity'       => 'nc_capacity',
        '_nc_location'       => 'nc_location',
        '_nc_lat'            => 'nc_lat',
        '_nc_lng'            => 'nc_lng',
        '_nc_event_category' => 'nc_event_category',
    ];

    foreach ($fields as $meta_key => $post_key) {
        if (isset($_POST[$post_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$post_key]));
        }
    }
}
add_action('save_post_nc_event', 'nc_save_event_meta');

function nc_save_service_meta(int $post_id) {
    if (!isset($_POST['nc_service_nonce']) || !wp_verify_nonce($_POST['nc_service_nonce'], 'nc_service_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_nc_service_category' => 'nc_service_category',
        '_nc_price'            => 'nc_price',
        '_nc_rating'           => 'nc_rating',
        '_nc_phone'            => 'nc_phone',
        '_nc_website'          => 'nc_website',
    ];

    foreach ($fields as $meta_key => $post_key) {
        if (isset($_POST[$post_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$post_key]));
        }
    }

    if (isset($_POST['nc_email'])) {
        update_post_meta($post_id, '_nc_email', sanitize_email($_POST['nc_email']));
    }
}
add_action('save_post_nc_service', 'nc_save_service_meta');

function nc_save_issue_meta(int $post_id) {
    if (!isset($_POST['nc_issue_nonce']) || !wp_verify_nonce($_POST['nc_issue_nonce'], 'nc_issue_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['nc_status'])) {
        update_post_meta($post_id, '_nc_status', sanitize_text_field($_POST['nc_status']));
    }
    if (isset($_POST['nc_location'])) {
        update_post_meta($post_id, '_nc_location', sanitize_text_field($_POST['nc_location']));
    }
    if (isset($_POST['nc_lat'])) {
        update_post_meta($post_id, '_nc_lat', (float) $_POST['nc_lat']);
    }
    if (isset($_POST['nc_lng'])) {
        update_post_meta($post_id, '_nc_lng', (float) $_POST['nc_lng']);
    }
}
add_action('save_post_nc_issue', 'nc_save_issue_meta');

/* ============================================================
   Admin Columns
   ============================================================ */
add_filter('manage_nc_event_posts_columns', function($cols) {
    return array_merge(
        array_slice($cols, 0, 2),
        ['nc_date' => __('Event Date', 'nc-core'), 'nc_location' => __('Location', 'nc-core'), 'nc_rsvps' => __('RSVPs', 'nc-core')],
        array_slice($cols, 2)
    );
});

add_action('manage_nc_event_posts_custom_column', function($col, $post_id) {
    if ($col === 'nc_date') echo esc_html(get_post_meta($post_id, '_nc_event_date', true) ?: '—');
    if ($col === 'nc_location') echo esc_html(get_post_meta($post_id, '_nc_location', true) ?: '—');
    if ($col === 'nc_rsvps') echo esc_html(count(get_post_meta($post_id, '_nc_rsvps', true) ?: []));
}, 10, 2);

add_filter('manage_nc_issue_posts_columns', function($cols) {
    return array_merge(
        array_slice($cols, 0, 2),
        ['nc_status' => __('Status', 'nc-core'), 'nc_votes' => __('Votes', 'nc-core')],
        array_slice($cols, 2)
    );
});

add_action('manage_nc_issue_posts_custom_column', function($col, $post_id) {
    if ($col === 'nc_status') {
        $status = get_post_meta($post_id, '_nc_status', true) ?: 'open';
        $colors = ['open' => '#ef4444', 'in-progress' => '#f59e0b', 'resolved' => '#10b981'];
        $color  = $colors[$status] ?? '#6b7280';
        printf('<span style="background:%s;color:white;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;">%s</span>', esc_attr($color), esc_html(ucwords(str_replace('-', ' ', $status))));
    }
    if ($col === 'nc_votes') echo esc_html(get_post_meta($post_id, '_nc_votes', true) ?: 0);
}, 10, 2);

/* ============================================================
   Helpers (reusable within plugin scope)
   ============================================================ */
function nc_core_get_event_meta(int $id): array {
    return [
        'date'     => get_post_meta($id, '_nc_event_date', true),
        'time'     => get_post_meta($id, '_nc_event_time', true),
        'end_time' => get_post_meta($id, '_nc_event_end_time', true),
        'location' => get_post_meta($id, '_nc_location', true),
        'capacity' => (int) get_post_meta($id, '_nc_capacity', true),
        'rsvps'    => get_post_meta($id, '_nc_rsvps', true) ?: [],
        'category' => get_post_meta($id, '_nc_event_category', true),
        'lat'      => (float) get_post_meta($id, '_nc_lat', true),
        'lng'      => (float) get_post_meta($id, '_nc_lng', true),
    ];
}

/* ============================================================
   Default Event Seeding
   ============================================================ */
add_action('init', function () {
    if (get_option('nc_default_events_seeded')) return;

    // Only skip if events with a date meta already exist (events without date meta won't show in the front-page query)
    $has_dated = get_posts([
        'post_type'   => 'nc_event',
        'post_status' => 'publish',
        'numberposts' => 1,
        'fields'      => 'ids',
        'meta_query'  => [['key' => '_nc_event_date', 'compare' => 'EXISTS']],
    ]);
    if (!empty($has_dated)) {
        update_option('nc_default_events_seeded', true);
        return;
    }

    $defaults = [
        [
            'title'    => 'Phase 1 Block Party & Iftar',
            'excerpt'  => 'A community get-together for all Canal View residents — live music, dinner stalls, and games for the whole family. Hosted at the Phase 1 community lawn.',
            'date'     => '2026-05-24',
            'time'     => '18:00',
            'end_time' => '22:00',
            'location' => 'Phase 1 Community Lawn, Canal View Society',
            'cat'      => 'community',
            'capacity' => 200,
        ],
        [
            'title'    => 'Sunday Bachat Bazaar',
            'excerpt'  => 'Fresh produce, homemade tiffin, baked goods and crafts — by Canal View residents, for Canal View residents. Every Sunday morning.',
            'date'     => '2026-05-25',
            'time'     => '08:00',
            'end_time' => '13:00',
            'location' => 'Society Commercial Block, Canal View',
            'cat'      => 'food',
            'capacity' => 250,
        ],
        [
            'title'    => 'Kids Cricket League — Phase 2',
            'excerpt'  => 'Tape-ball cricket league for kids aged 8–14. Coaches from the society sports committee. Helmets and tape balls provided.',
            'date'     => '2026-05-27',
            'time'     => '16:30',
            'end_time' => '19:00',
            'location' => 'Phase 2 Sports Ground, Canal View',
            'cat'      => 'sports',
            'capacity' => 60,
        ],
        [
            'title'    => 'Canal Bank Clean-Up Drive',
            'excerpt'  => 'Help keep the canal-side walking track clean. Gloves and bags provided by the society maintenance desk. A great way to meet your neighbours.',
            'date'     => '2026-05-31',
            'time'     => '07:30',
            'end_time' => '10:00',
            'location' => 'Canal Bank Walking Track, Canal View',
            'cat'      => 'environment',
            'capacity' => 80,
        ],
        [
            'title'    => 'Quran & Calligraphy Workshop',
            'excerpt'  => 'Calligraphy session for adults and teenagers, hosted at the Phase 1 masjid hall. All materials provided. Free for residents.',
            'date'     => '2026-06-02',
            'time'     => '15:00',
            'end_time' => '17:30',
            'location' => 'Jamia Masjid Canal View — Hall',
            'cat'      => 'arts',
            'capacity' => 40,
        ],
        [
            'title'    => 'Morning Yoga at Ladies Park',
            'excerpt'  => 'Start your day with a refreshing outdoor yoga session at the in-society Ladies Park. Suitable for all fitness levels. Bring your own mat.',
            'date'     => '2026-06-05',
            'time'     => '06:30',
            'end_time' => '08:00',
            'location' => 'Ladies Park, Phase 1 Canal View',
            'cat'      => 'health',
            'capacity' => 40,
        ],
    ];

    foreach ($defaults as $event) {
        $post_id = wp_insert_post([
            'post_title'   => $event['title'],
            'post_excerpt' => $event['excerpt'],
            'post_content' => $event['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'nc_event',
            'post_author'  => 1,
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_nc_event_date',     $event['date']);
            update_post_meta($post_id, '_nc_event_time',     $event['time']);
            update_post_meta($post_id, '_nc_event_end_time', $event['end_time']);
            update_post_meta($post_id, '_nc_location',       $event['location']);
            update_post_meta($post_id, '_nc_event_category', $event['cat']);
            update_post_meta($post_id, '_nc_capacity',       $event['capacity']);
            update_post_meta($post_id, '_nc_rsvps',          []);
        }
    }

    update_option('nc_default_events_seeded', true);
}, 99);

/* ============================================================
   Activation: Flush rewrite rules
   ============================================================ */
register_activation_hook(__FILE__, function () {
    nc_register_post_types();
    nc_register_taxonomies();
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

/* ============================================================
   Society Service Catalog (replaces external vendor model)
   ============================================================
   Each nc_service post is now an in-house service the society
   offers. Residents pick a category, the wizard collects details,
   and nc_service_request stores the ticket assigned to a
   service_team user.
   ============================================================ */

/**
 * Canonical list of services the society offers. Slug is the
 * identifier used to match team members to categories.
 */
function nc_service_catalog(): array {
    return [
        ['slug' => 'plumbing',      'title' => 'Plumbing',           'icon' => '🔧', 'fa' => 'fa-faucet-drip',         'color' => '#0891b2', 'tagline' => 'Leaks, taps, drains, water tank',
         'sub' => ['Leak repair', 'Tap / faucet replacement', 'Drain unblocking', 'Water tank cleaning', 'Bathroom fitting']],
        ['slug' => 'electrical',    'title' => 'Electrical',         'icon' => '💡', 'fa' => 'fa-bolt',                'color' => '#f59e0b', 'tagline' => 'Wiring, fans, lights, switches',
         'sub' => ['Light / fan installation', 'Switchboard repair', 'Wiring fault', 'Power outage', 'Inverter / UPS issue']],
        ['slug' => 'carpentry',     'title' => 'Carpentry',          'icon' => '🪚', 'fa' => 'fa-hammer',              'color' => '#92400e', 'tagline' => 'Doors, locks, furniture',
         'sub' => ['Door / lock repair', 'Furniture assembly', 'Hinge / handle fix', 'Custom shelving', 'Window repair']],
        ['slug' => 'cleaning',      'title' => 'Cleaning',           'icon' => '🧹', 'fa' => 'fa-broom',               'color' => '#7c3aed', 'tagline' => 'Apartment & common-area cleaning',
         'sub' => ['Full home deep clean', 'Sofa / carpet shampoo', 'Kitchen deep clean', 'Bathroom sanitisation', 'Post-event cleanup']],
        ['slug' => 'pest-control',  'title' => 'Pest Control',       'icon' => '🐜', 'fa' => 'fa-bug-slash',           'color' => '#dc2626', 'tagline' => 'Cockroaches, termites, rodents',
         'sub' => ['Cockroach treatment', 'Termite control', 'Rodent removal', 'Mosquito fogging', 'Bed bug treatment']],
        ['slug' => 'appliance',     'title' => 'Appliance Repair',   'icon' => '📺', 'fa' => 'fa-blender',             'color' => '#0d9488', 'tagline' => 'AC, fridge, washing machine',
         'sub' => ['AC service / gas refill', 'Refrigerator repair', 'Washing machine repair', 'Microwave / oven repair', 'Geyser repair']],
        ['slug' => 'painting',      'title' => 'Painting',           'icon' => '🎨', 'fa' => 'fa-paint-roller',        'color' => '#db2777', 'tagline' => 'Interior & exterior paint jobs',
         'sub' => ['Single room repaint', 'Full apartment repaint', 'Touch-up & patch work', 'Waterproofing', 'Texture / finishing']],
        ['slug' => 'tiffin',        'title' => 'Tiffin / Food',      'icon' => '🍱', 'fa' => 'fa-bowl-food',           'color' => '#16a34a', 'tagline' => 'Daily home-style meals',
         'sub' => ['Lunch tiffin (veg)', 'Lunch tiffin (non-veg)', 'Dinner tiffin', 'Event catering', 'Special diet meal']],
        ['slug' => 'groceries',     'title' => 'Grocery Delivery',   'icon' => '🛒', 'fa' => 'fa-cart-shopping',       'color' => '#ea580c', 'tagline' => 'Doorstep grocery from local stores',
         'sub' => ['Daily essentials', 'Vegetables & fruit', 'Dairy & bakery', 'Monthly ration', 'Pharmacy run']],
        ['slug' => 'maintenance',   'title' => 'General Maintenance','icon' => '🛠️', 'fa' => 'fa-screwdriver-wrench',  'color' => '#4f46e5', 'tagline' => 'Society-wide upkeep & odd jobs',
         'sub' => ['Lift / elevator issue', 'Common-area light', 'Gate / intercom fault', 'Water motor issue', 'Other (describe)']],
    ];
}

/**
 * Make sure the service_team role exists. Cheap to call on every init.
 */
function nc_ensure_service_team_role(): void {
    if (!get_role('service_team')) {
        add_role('service_team', __('Service Team', 'nc-core'), [
            'read'         => true,
            'edit_posts'   => false,
            'upload_files' => false,
        ]);
    }
}
add_action('init', 'nc_ensure_service_team_role');

/**
 * Seed default service catalog once. Idempotent via option flag.
 */
add_action('init', function () {
    if (get_option('nc_default_services_seeded')) return;

    // Wipe any pre-existing vendor-style entries so the catalog stays clean
    $existing = get_posts(['post_type' => 'nc_service', 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids']);
    foreach ($existing as $id) wp_delete_post($id, true);

    foreach (nc_service_catalog() as $svc) {
        $id = wp_insert_post([
            'post_title'   => $svc['title'],
            'post_name'    => $svc['slug'],
            'post_excerpt' => $svc['tagline'],
            'post_content' => $svc['tagline'],
            'post_status'  => 'publish',
            'post_type'    => 'nc_service',
            'post_author'  => 1,
        ]);
        if (!$id || is_wp_error($id)) continue;
        update_post_meta($id, '_nc_service_slug',     $svc['slug']);
        update_post_meta($id, '_nc_service_icon',     $svc['icon']);
        update_post_meta($id, '_nc_service_fa',       $svc['fa']);
        update_post_meta($id, '_nc_service_color',    $svc['color']);
        update_post_meta($id, '_nc_service_tagline',  $svc['tagline']);
        update_post_meta($id, '_nc_service_subitems', $svc['sub']);
        update_post_meta($id, '_nc_service_category', $svc['title']);
    }

    update_option('nc_default_services_seeded', true);
}, 99);

/**
 * Seed default service-team users (one per category, plus a couple of multi-category).
 * Each user has _nc_team_categories = [list of service slugs they handle].
 */
add_action('init', function () {
    if (get_option('nc_default_team_seeded')) return;

    $team = [
        ['login' => 'team.plumbing',    'name' => 'Bilal Ahmed',      'phone' => '+92 300 1112201', 'cats' => ['plumbing']],
        ['login' => 'team.electrical',  'name' => 'Faisal Khan',      'phone' => '+92 300 1112202', 'cats' => ['electrical']],
        ['login' => 'team.carpentry',   'name' => 'Imran Hussain',    'phone' => '+92 300 1112203', 'cats' => ['carpentry']],
        ['login' => 'team.cleaning',    'name' => 'Sadia Riaz',       'phone' => '+92 300 1112204', 'cats' => ['cleaning']],
        ['login' => 'team.pest',        'name' => 'Hamza Tariq',      'phone' => '+92 300 1112205', 'cats' => ['pest-control']],
        ['login' => 'team.appliance',   'name' => 'Adeel Mahmood',    'phone' => '+92 300 1112206', 'cats' => ['appliance']],
        ['login' => 'team.painting',    'name' => 'Naveed Iqbal',     'phone' => '+92 300 1112207', 'cats' => ['painting']],
        ['login' => 'team.tiffin',      'name' => 'Ammi’s Kitchen',   'phone' => '+92 300 1112208', 'cats' => ['tiffin']],
        ['login' => 'team.grocery',     'name' => 'Rapid Mart',       'phone' => '+92 300 1112209', 'cats' => ['groceries']],
        ['login' => 'team.maintenance', 'name' => 'Society Maintenance Desk', 'phone' => '+92 300 1112210', 'cats' => ['maintenance']],
        // overflow handlers
        ['login' => 'team.handyman',    'name' => 'Yasir Mehmood',    'phone' => '+92 300 1112211', 'cats' => ['plumbing','electrical','carpentry']],
    ];

    foreach ($team as $t) {
        if (username_exists($t['login'])) continue;
        $uid = wp_create_user($t['login'], wp_generate_password(20), $t['login'] . '@society.local');
        if (is_wp_error($uid)) continue;
        $u = new WP_User($uid);
        $u->set_role('service_team');
        wp_update_user(['ID' => $uid, 'display_name' => $t['name'], 'first_name' => $t['name']]);
        update_user_meta($uid, '_nc_team_categories', $t['cats']);
        update_user_meta($uid, '_nc_team_phone',      $t['phone']);
    }

    update_option('nc_default_team_seeded', true);
}, 99);

/**
 * Round-robin pick: return a service_team user_id who handles $category_slug.
 * Tracks last-assigned per category in a site option so the load is shared.
 * Returns 0 if no team member is available.
 */
function nc_pick_team_member(string $category_slug): int {
    $users = get_users([
        'role'       => 'service_team',
        'meta_query' => [[
            'key'     => '_nc_team_categories',
            'value'   => sprintf(':"%s";', $category_slug), // serialized array contains the slug
            'compare' => 'LIKE',
        ]],
        'fields'     => 'ID',
        'orderby'    => 'ID',
        'order'      => 'ASC',
    ]);
    if (empty($users)) return 0;

    $cursor_opt = 'nc_team_cursor_' . $category_slug;
    $cursor = (int) get_option($cursor_opt, 0);
    $pick   = $users[$cursor % count($users)];
    update_option($cursor_opt, $cursor + 1);
    return (int) $pick;
}

/* ============================================================
   Neighborhood Amenities — categories + seeded entries
   ============================================================ */

function nc_amenity_categories(): array {
    return [
        'parks'      => ['label' => 'Parks',         'fa' => 'fa-tree',                  'color' => '#16a34a'],
        'schools'    => ['label' => 'Schools',       'fa' => 'fa-school',                'color' => '#7c3aed'],
        'hospitals'  => ['label' => 'Hospitals',     'fa' => 'fa-house-medical',         'color' => '#dc2626'],
        'pharmacies' => ['label' => 'Pharmacies',    'fa' => 'fa-prescription-bottle-medical', 'color' => '#0ea5e9'],
        'banks'      => ['label' => 'Banks & ATMs',  'fa' => 'fa-building-columns',      'color' => '#1d4ed8'],
        'grocery'    => ['label' => 'Grocery',       'fa' => 'fa-cart-shopping',         'color' => '#ea580c'],
        'food'       => ['label' => 'Restaurants',   'fa' => 'fa-utensils',              'color' => '#db2777'],
        'cafes'      => ['label' => 'Cafés',         'fa' => 'fa-mug-saucer',            'color' => '#92400e'],
        'gyms'       => ['label' => 'Gyms',          'fa' => 'fa-dumbbell',              'color' => '#0d9488'],
        'salons'     => ['label' => 'Salons',        'fa' => 'fa-scissors',              'color' => '#be185d'],
        'fuel'       => ['label' => 'Fuel Stations', 'fa' => 'fa-gas-pump',              'color' => '#854d0e'],
        'religious'  => ['label' => 'Worship',       'fa' => 'fa-mosque',                'color' => '#4338ca'],
        'transport'  => ['label' => 'Transport',     'fa' => 'fa-train-subway',          'color' => '#0891b2'],
    ];
}

function nc_amenity_seed(): array {
    // All distances are measured from Canal View main gate (Canal Bank Road, Lahore).
    return [
        // Parks
        ['t' => 'Ladies Park (in-society)',         'cat' => 'parks',      'dist' => '0.2 km', 'addr' => 'Phase 1, Canal View Society',           'hours' => '5 AM – 10 PM',      'phone' => ''],
        ['t' => 'Canal Bank Walking Track',         'cat' => 'parks',      'dist' => '0.1 km', 'addr' => 'BRB Canal, opposite main gate',         'hours' => 'Open 24 hours',     'phone' => ''],
        ['t' => 'Wapda Town Park',                  'cat' => 'parks',      'dist' => '2.5 km', 'addr' => 'Sector G-1, Wapda Town',                'hours' => '5 AM – 10 PM',      'phone' => ''],

        // Schools
        ['t' => 'Allied School (Canal Campus)',     'cat' => 'schools',    'dist' => '0.3 km', 'addr' => 'Phase 2, Canal View Society',           'hours' => 'Mon–Fri 7:30 AM – 2 PM', 'phone' => '+92 42 35423001'],
        ['t' => 'Beaconhouse Canal Side Campus',    'cat' => 'schools',    'dist' => '1.4 km', 'addr' => 'Canal Bank Road, near Thokar',          'hours' => 'Mon–Fri 7:30 AM – 2 PM', 'phone' => '+92 42 35291100'],
        ['t' => 'The City School (Johar Town)',     'cat' => 'schools',    'dist' => '2.1 km', 'addr' => 'Block L, Johar Town',                   'hours' => 'Mon–Fri 7:30 AM – 2 PM', 'phone' => '+92 42 35291222'],
        ['t' => 'LGS Johar Town',                   'cat' => 'schools',    'dist' => '2.8 km', 'addr' => 'Block J3, Johar Town',                  'hours' => 'Mon–Fri 7:30 AM – 2 PM', 'phone' => '+92 42 35201234'],
        ['t' => 'Punjab College of IT (PUCIT)',     'cat' => 'schools',    'dist' => '0.4 km', 'addr' => 'Adjacent to Canal View',                 'hours' => 'Mon–Fri 8 AM – 4 PM',    'phone' => '+92 42 99232085'],
        ['t' => 'Punjab University (New Campus)',   'cat' => 'schools',    'dist' => '3.1 km', 'addr' => 'Quaid-i-Azam Campus, Canal Bank',       'hours' => 'Mon–Fri 8 AM – 4 PM',    'phone' => '+92 42 99231581'],

        // Hospitals
        ['t' => 'Saleem Memorial Hospital',         'cat' => 'hospitals',  'dist' => '2.3 km', 'addr' => 'Faisal Town, Lahore',                   'hours' => 'Open 24 hours',         'phone' => '+92 42 35167891'],
        ['t' => 'Doctors Hospital (Johar Town)',    'cat' => 'hospitals',  'dist' => '2.9 km', 'addr' => 'Block-E1, Johar Town',                  'hours' => 'Open 24 hours',         'phone' => '+92 42 35302701'],
        ['t' => 'Hameed Latif Hospital',            'cat' => 'hospitals',  'dist' => '3.7 km', 'addr' => 'Abu Bakar Block, Garden Town',          'hours' => 'Open 24 hours',         'phone' => '+92 42 111-000-043'],
        ['t' => 'Punjab Social Security Hospital',  'cat' => 'hospitals',  'dist' => '1.6 km', 'addr' => 'Multan Road, Lahore',                   'hours' => 'Open 24 hours',         'phone' => '+92 42 35411888'],
        ['t' => 'Sheikh Zayed Hospital',            'cat' => 'hospitals',  'dist' => '3.4 km', 'addr' => 'University Avenue, Lahore',             'hours' => 'Open 24 hours',         'phone' => '+92 42 99231400'],

        // Pharmacies
        ['t' => 'Servaid Pharmacy (Thokar)',        'cat' => 'pharmacies', 'dist' => '1.1 km', 'addr' => 'Thokar Niaz Baig',                       'hours' => '9 AM – 12 AM',          'phone' => '+92 42 111-738-743'],
        ['t' => 'D. Watson (Johar Town)',           'cat' => 'pharmacies', 'dist' => '2.4 km', 'addr' => 'Block H, Johar Town',                    'hours' => '9 AM – 11 PM',          'phone' => '+92 42 35201199'],
        ['t' => 'Society Pharmacy (in-house)',      'cat' => 'pharmacies', 'dist' => '0.05 km','addr' => 'Society commercial floor',               'hours' => '9 AM – 11 PM',          'phone' => '+92 300 1112209'],
        ['t' => 'Clinix Pharmacy',                  'cat' => 'pharmacies', 'dist' => '0.8 km', 'addr' => 'Multan Road, near Hanjarwal',            'hours' => 'Open 24 hours',         'phone' => '+92 42 35291777'],

        // Banks & ATMs
        ['t' => 'HBL (Canal Bank Branch)',          'cat' => 'banks',      'dist' => '0.6 km', 'addr' => 'Canal Bank Road',                        'hours' => 'Mon–Fri 9 AM – 5 PM · ATM 24h', 'phone' => '+92 21 111-111-425'],
        ['t' => 'MCB Bank (Thokar)',                'cat' => 'banks',      'dist' => '1.2 km', 'addr' => 'Thokar Niaz Baig',                       'hours' => 'Mon–Fri 9 AM – 5 PM · ATM 24h', 'phone' => '+92 42 111-000-622'],
        ['t' => 'Allied Bank (Johar Town)',         'cat' => 'banks',      'dist' => '2.2 km', 'addr' => 'Block H, Johar Town',                    'hours' => 'Mon–Fri 9 AM – 5 PM · ATM 24h', 'phone' => '+92 42 111-225-225'],
        ['t' => 'UBL ATM (Standalone)',             'cat' => 'banks',      'dist' => '0.05 km','addr' => 'Society main gate',                      'hours' => 'Open 24 hours',                  'phone' => ''],
        ['t' => 'Bank Alfalah ATM',                 'cat' => 'banks',      'dist' => '0.3 km', 'addr' => 'Canal View commercial block',            'hours' => 'Open 24 hours',                  'phone' => ''],

        // Grocery
        ['t' => 'Metro Cash & Carry (Thokar)',      'cat' => 'grocery',    'dist' => '1.3 km', 'addr' => 'Thokar Niaz Baig, Multan Road',          'hours' => '7 AM – 11 PM',           'phone' => '+92 42 111-468-374'],
        ['t' => 'Imtiaz Super Market (Johar Town)', 'cat' => 'grocery',    'dist' => '2.6 km', 'addr' => 'Block C2, Johar Town',                   'hours' => '9 AM – 12 AM',           'phone' => '+92 42 111-468-429'],
        ['t' => 'Al-Fatah (Wapda Town)',            'cat' => 'grocery',    'dist' => '2.4 km', 'addr' => 'Wapda Town',                             'hours' => '9 AM – 11 PM',           'phone' => '+92 42 35780801'],
        ['t' => 'Canal View Mart (in-house)',       'cat' => 'grocery',    'dist' => '0.05 km','addr' => 'Society commercial floor',               'hours' => '8 AM – 11 PM',           'phone' => '+92 300 1112209'],

        // Restaurants
        ['t' => 'KFC (Thokar Niaz Baig)',           'cat' => 'food',       'dist' => '1.1 km', 'addr' => 'Multan Road, Thokar',                    'hours' => '11 AM – 1 AM',           'phone' => '+92 42 111-532-532'],
        ['t' => 'Bundu Khan (Canal Bank)',          'cat' => 'food',       'dist' => '0.9 km', 'addr' => 'Canal Bank Road',                        'hours' => '12 PM – 12 AM',          'phone' => '+92 42 35864001'],
        ['t' => 'Khabbay Tikka & BBQ',              'cat' => 'food',       'dist' => '1.4 km', 'addr' => 'Thokar Niaz Baig',                       'hours' => '6 PM – 1 AM',            'phone' => '+92 42 35291515'],
        ['t' => 'McDonald\'s (Thokar)',             'cat' => 'food',       'dist' => '1.2 km', 'addr' => 'Multan Road, Thokar',                    'hours' => 'Open 24 hours',          'phone' => '+92 42 111-244-622'],

        // Cafés
        ['t' => 'Tehzeeb Bakers (Wapda Town)',      'cat' => 'cafes',      'dist' => '2.3 km', 'addr' => 'Wapda Town Boulevard',                   'hours' => '7 AM – 12 AM',           'phone' => '+92 42 35185555'],
        ['t' => 'Gloria Jean\'s Coffees',           'cat' => 'cafes',      'dist' => '2.8 km', 'addr' => 'Emporium Mall, Johar Town',              'hours' => '10 AM – 12 AM',          'phone' => '+92 42 32310000'],

        // Gyms
        ['t' => 'Structure Gym (Society)',          'cat' => 'gyms',       'dist' => '0.15 km','addr' => 'Canal View commercial block',            'hours' => '6 AM – 11 PM',           'phone' => '+92 300 8456789'],
        ['t' => 'Shapes Health Club (Johar Town)',  'cat' => 'gyms',       'dist' => '2.7 km', 'addr' => 'Block H, Johar Town',                    'hours' => '6 AM – 10 PM',           'phone' => '+92 42 35772424'],

        // Salons
        ['t' => 'Saaya Beauty Salon',               'cat' => 'salons',     'dist' => '0.4 km', 'addr' => 'Canal View commercial block',            'hours' => '11 AM – 9 PM',           'phone' => '+92 300 7654321'],
        ['t' => 'Nabila Salon (Johar Town)',        'cat' => 'salons',     'dist' => '2.5 km', 'addr' => 'Block H, Johar Town',                    'hours' => '11 AM – 8 PM',           'phone' => '+92 42 35754040'],

        // Fuel
        ['t' => 'Shell (Canal Bank Road)',          'cat' => 'fuel',       'dist' => '0.7 km', 'addr' => 'Canal Bank Road',                        'hours' => 'Open 24 hours',          'phone' => ''],
        ['t' => 'Total PARCO (Thokar)',             'cat' => 'fuel',       'dist' => '1.1 km', 'addr' => 'Multan Road, Thokar',                    'hours' => 'Open 24 hours',          'phone' => ''],

        // Religious
        ['t' => 'Jamia Masjid Canal View',          'cat' => 'religious',  'dist' => '0.1 km', 'addr' => 'Inside Canal View, Phase 1',             'hours' => 'Open for 5 prayers',     'phone' => ''],
        ['t' => 'Masjid-e-Quba (Phase 2)',          'cat' => 'religious',  'dist' => '0.3 km', 'addr' => 'Phase 2, Canal View',                    'hours' => 'Open for 5 prayers',     'phone' => ''],

        // Transport
        ['t' => 'Thokar Niaz Baig Metrobus Terminus','cat' => 'transport', 'dist' => '1.2 km', 'addr' => 'Multan Road, Thokar',                    'hours' => '6 AM – 11 PM',           'phone' => ''],
        ['t' => 'Orange Line — Thokar Station',     'cat' => 'transport',  'dist' => '1.3 km', 'addr' => 'Multan Road, Thokar',                    'hours' => '5:30 AM – 9:30 PM',      'phone' => ''],
        ['t' => 'Careem / Bykea Pickup Point',      'cat' => 'transport',  'dist' => '0.05 km','addr' => 'Society main gate, Canal Bank Road',     'hours' => 'Open 24 hours',          'phone' => ''],
    ];
}

/**
 * Seed amenities once. Idempotent via option flag.
 */
add_action('init', function () {
    if (get_option('nc_default_amenities_seeded')) return;

    foreach (nc_amenity_seed() as $a) {
        $id = wp_insert_post([
            'post_title'   => $a['t'],
            'post_status'  => 'publish',
            'post_type'    => 'nc_amenity',
            'post_excerpt' => $a['addr'],
            'post_content' => $a['addr'],
        ]);
        if (!$id || is_wp_error($id)) continue;
        update_post_meta($id, '_nc_am_category', $a['cat']);
        update_post_meta($id, '_nc_am_distance', $a['dist']);
        update_post_meta($id, '_nc_am_address',  $a['addr']);
        update_post_meta($id, '_nc_am_phone',    $a['phone']);
        update_post_meta($id, '_nc_am_hours',    $a['hours']);
    }

    update_option('nc_default_amenities_seeded', true);
}, 99);

/* ============================================================
   Service Request admin columns (so chairman can demo a triage view)
   ============================================================ */
add_filter('manage_nc_service_request_posts_columns', function ($cols) {
    $new = [];
    foreach ($cols as $k => $v) {
        $new[$k] = $v;
        if ($k === 'title') {
            $new['nc_sr_service']   = __('Service', 'nc-core');
            $new['nc_sr_flat']      = __('Flat', 'nc-core');
            $new['nc_sr_urgency']   = __('Urgency', 'nc-core');
            $new['nc_sr_assigned']  = __('Assigned To', 'nc-core');
            $new['nc_sr_status']    = __('Status', 'nc-core');
        }
    }
    return $new;
});

add_action('manage_nc_service_request_posts_custom_column', function ($col, $post_id) {
    if ($col === 'nc_sr_service') {
        $sid = (int) get_post_meta($post_id, '_nc_sr_service_id', true);
        echo $sid ? esc_html(get_the_title($sid)) : '—';
    } elseif ($col === 'nc_sr_flat') {
        echo esc_html(get_post_meta($post_id, '_nc_sr_flat', true) ?: '—');
    } elseif ($col === 'nc_sr_urgency') {
        $u = get_post_meta($post_id, '_nc_sr_urgency', true) ?: 'normal';
        $colors = ['low' => '#16a34a', 'normal' => '#0891b2', 'urgent' => '#dc2626'];
        printf('<span style="background:%s;color:#fff;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;">%s</span>',
            esc_attr($colors[$u] ?? '#6b7280'), esc_html(ucfirst($u)));
    } elseif ($col === 'nc_sr_assigned') {
        $uid = (int) get_post_meta($post_id, '_nc_sr_assigned_to', true);
        if ($uid && ($u = get_userdata($uid))) echo esc_html($u->display_name);
        else echo '<em>Unassigned</em>';
    } elseif ($col === 'nc_sr_status') {
        $s = get_post_meta($post_id, '_nc_sr_status', true) ?: 'pending';
        $colors = ['pending' => '#f59e0b', 'assigned' => '#0891b2', 'in_progress' => '#7c3aed', 'completed' => '#16a34a', 'cancelled' => '#6b7280'];
        printf('<span style="background:%s;color:#fff;padding:2px 8px;border-radius:999px;font-size:11px;font-weight:600;">%s</span>',
            esc_attr($colors[$s] ?? '#6b7280'), esc_html(ucwords(str_replace('_', ' ', $s))));
    }
}, 10, 2);
