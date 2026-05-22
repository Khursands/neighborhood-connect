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
            'title'    => 'Community Block Party',
            'excerpt'  => 'A wonderful block party for all residents of the neighbourhood. Music, food stalls, and games for the whole family. Come join us!',
            'date'     => '2026-05-24',
            'time'     => '10:00',
            'end_time' => '14:00',
            'location' => 'Gulberg III Park, Lahore',
            'cat'      => 'community',
            'capacity' => 150,
        ],
        [
            'title'    => 'Saturday Farmers Market',
            'excerpt'  => 'Fresh produce, homemade goods, and local artisans every Saturday morning. Support your neighbours and eat local!',
            'date'     => '2026-05-25',
            'time'     => '08:00',
            'end_time' => '14:00',
            'location' => 'Liberty Market, Lahore',
            'cat'      => 'food',
            'capacity' => 200,
        ],
        [
            'title'    => 'Kids Soccer League',
            'excerpt'  => 'Weekly soccer matches for kids aged 6–14. All skill levels welcome. Bring water and your team spirit!',
            'date'     => '2026-05-27',
            'time'     => '16:00',
            'end_time' => '18:00',
            'location' => 'DHA Phase 5, Lahore',
            'cat'      => 'sports',
            'capacity' => 50,
        ],
        [
            'title'    => 'Neighbourhood Clean-Up',
            'excerpt'  => 'Let\'s make our streets shine! Gloves and bags provided. A great way to meet neighbours while giving back to the community.',
            'date'     => '2026-05-31',
            'time'     => '09:00',
            'end_time' => '12:00',
            'location' => 'Johar Town Park, Lahore',
            'cat'      => 'environment',
            'capacity' => 80,
        ],
        [
            'title'    => 'Art Workshop',
            'excerpt'  => 'An afternoon of painting, sketching, and creativity. All materials provided. Open to adults and teenagers. No experience needed!',
            'date'     => '2026-06-02',
            'time'     => '14:00',
            'end_time' => '17:00',
            'location' => 'NCA Mall Road, Lahore',
            'cat'      => 'arts',
            'capacity' => 30,
        ],
        [
            'title'    => 'Morning Yoga in the Park',
            'excerpt'  => 'Start your day with a refreshing outdoor yoga session. Suitable for all fitness levels. Bring your own mat.',
            'date'     => '2026-06-05',
            'time'     => '06:30',
            'end_time' => '08:00',
            'location' => 'Jilani Park, Lahore',
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
