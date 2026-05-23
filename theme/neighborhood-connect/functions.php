<?php
defined('ABSPATH') || exit;

define('NC_VERSION', '5.0.0');
define('NC_DIR', get_template_directory());
define('NC_URI', get_template_directory_uri());

/* ============================================================
   Theme Setup
   ============================================================ */
function nc_setup() {
    load_theme_textdomain('neighborhood-connect', NC_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('custom-logo', [
        'height'      => 40,
        'width'       => 160,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('post-formats', ['aside', 'gallery', 'quote', 'link', 'image', 'video']);

    add_image_size('nc-card', 600, 350, true);
    add_image_size('nc-hero', 1200, 600, true);
    add_image_size('nc-avatar', 100, 100, true);

    register_nav_menus([
        'primary'    => __('Primary Navigation', 'neighborhood-connect'),
        'footer-one' => __('Footer Column 1', 'neighborhood-connect'),
        'footer-two' => __('Footer Column 2', 'neighborhood-connect'),
        'social'     => __('Social Links', 'neighborhood-connect'),
    ]);
}
add_action('after_setup_theme', 'nc_setup');

/* ============================================================
   Scripts & Styles
   ============================================================ */
function nc_enqueue_assets() {
    // Google Fonts preconnect
    wp_enqueue_style('nc-fonts-preconnect-1', 'https://fonts.googleapis.com', [], null);
    wp_enqueue_style('nc-fonts-preconnect-2', 'https://fonts.gstatic.com',    [], null);
    add_filter('style_loader_tag', function($html, $handle) {
        if ($handle === 'nc-fonts-preconnect-2') {
            return str_replace("rel='stylesheet'", "rel='preconnect' crossorigin", $html);
        }
        if ($handle === 'nc-fonts-preconnect-1') {
            return str_replace("rel='stylesheet'", "rel='preconnect'", $html);
        }
        return $html;
    }, 10, 2);

    // Google Fonts — Inter for body, Nunito for headings (friendlier, rounder display face)
    wp_enqueue_style(
        'nc-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800;1,14..32,400&family=Nunito:wght@600;700;800;900&display=swap',
        [],
        null
    );

    // Font Awesome
    wp_enqueue_style(
        'nc-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        [],
        '6.5.0'
    );

    // Theme base styles (style.css)
    wp_enqueue_style('nc-theme', get_stylesheet_uri(), ['nc-google-fonts'], NC_VERSION);

    // Main stylesheet
    wp_enqueue_style('nc-main', NC_URI . '/assets/css/main.css', ['nc-theme'], NC_VERSION);

    // Main JS
    wp_enqueue_script('nc-main', NC_URI . '/assets/js/main.js', [], NC_VERSION, true);

    // Localize script
    wp_localize_script('nc-main', 'ncData', [
        'ajaxUrl'    => admin_url('admin-ajax.php'),
        'nonce'      => wp_create_nonce('nc_nonce'),
        'siteUrl'    => get_site_url(),
        'isLoggedIn' => is_user_logged_in(),
        'userId'     => get_current_user_id(),
        'i18n'       => [
            'rsvpJoined'   => __('RSVP Confirmed!', 'neighborhood-connect'),
            'rsvpCancelled'=> __('RSVP Cancelled', 'neighborhood-connect'),
            'loginRequired'=> __('Please log in to continue.', 'neighborhood-connect'),
        ],
    ]);

    // Comments thread
    if (is_singular() && comments_open()) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'nc_enqueue_assets');

/* ============================================================
   Widgets / Sidebars
   ============================================================ */
function nc_register_sidebars() {
    $defaults = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar(array_merge($defaults, [
        'name'        => __('Blog Sidebar', 'neighborhood-connect'),
        'id'          => 'sidebar-blog',
        'description' => __('Widgets in the blog sidebar.', 'neighborhood-connect'),
    ]));

    register_sidebar(array_merge($defaults, [
        'name'        => __('Events Sidebar', 'neighborhood-connect'),
        'id'          => 'sidebar-events',
        'description' => __('Widgets shown on event pages.', 'neighborhood-connect'),
    ]));

    register_sidebar(array_merge($defaults, [
        'name'        => __('Footer Col 1', 'neighborhood-connect'),
        'id'          => 'footer-1',
        'description' => __('Footer widget area 1.', 'neighborhood-connect'),
    ]));
}
add_action('widgets_init', 'nc_register_sidebars');

/* ============================================================
   Custom REST API Endpoints
   ============================================================ */
function nc_register_rest_routes() {
    $ns = 'nc/v1';

    register_rest_route($ns, '/events', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'nc_rest_get_events',
        'permission_callback' => '__return_true',
        'args'                => [
            'per_page' => ['default' => 10, 'sanitize_callback' => 'absint'],
            'page'     => ['default' => 1,  'sanitize_callback' => 'absint'],
            'category' => ['sanitize_callback' => 'sanitize_text_field'],
        ],
    ]);

    register_rest_route($ns, '/events/(?P<id>\d+)', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'nc_rest_get_event',
        'permission_callback' => '__return_true',
        'args'                => ['id' => ['validate_callback' => fn($v) => is_numeric($v)]],
    ]);

    register_rest_route($ns, '/events/(?P<id>\d+)/rsvp', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'nc_rest_rsvp',
        'permission_callback' => 'is_user_logged_in',
    ]);

    register_rest_route($ns, '/services', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'nc_rest_get_services',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route($ns, '/issues', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'nc_rest_get_issues',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route($ns, '/issues', [
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => 'nc_rest_create_issue',
        'permission_callback' => 'is_user_logged_in',
    ]);
}
add_action('rest_api_init', 'nc_register_rest_routes');

function nc_rest_get_events(WP_REST_Request $req) {
    $args = [
        'post_type'      => 'nc_event',
        'posts_per_page' => $req->get_param('per_page'),
        'paged'          => $req->get_param('page'),
        'post_status'    => 'publish',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_key'       => '_nc_event_date',
        'meta_query'     => [[
            'key'     => '_nc_event_date',
            'value'   => current_time('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
        ]],
    ];

    $q = new WP_Query($args);
    $events = array_map('nc_format_event_rest', $q->posts);

    return new WP_REST_Response([
        'data'       => $events,
        'total'      => $q->found_posts,
        'totalPages' => $q->max_num_pages,
    ]);
}

function nc_rest_get_event(WP_REST_Request $req) {
    $post = get_post($req->get_param('id'));
    if (!$post || $post->post_type !== 'nc_event') {
        return new WP_Error('not_found', 'Event not found', ['status' => 404]);
    }
    return new WP_REST_Response(nc_format_event_rest($post));
}

function nc_rest_rsvp(WP_REST_Request $req) {
    $event_id = $req->get_param('id');
    $user_id  = get_current_user_id();
    $rsvps    = get_post_meta($event_id, '_nc_rsvps', true) ?: [];

    if (in_array($user_id, $rsvps)) {
        $rsvps = array_values(array_diff($rsvps, [$user_id]));
        $type = 'cancelled';
    } else {
        $rsvps[] = $user_id;
        $type = 'joined';
    }

    update_post_meta($event_id, '_nc_rsvps', $rsvps);
    return new WP_REST_Response(['type' => $type, 'count' => count($rsvps)]);
}

function nc_rest_get_services(WP_REST_Request $req) {
    $q = new WP_Query(['post_type' => 'nc_service', 'posts_per_page' => 12, 'post_status' => 'publish']);
    return new WP_REST_Response(array_map('nc_format_service_rest', $q->posts));
}

function nc_rest_get_issues(WP_REST_Request $req) {
    $q = new WP_Query(['post_type' => 'nc_issue', 'posts_per_page' => 20, 'post_status' => 'publish']);
    return new WP_REST_Response(array_map('nc_format_issue_rest', $q->posts));
}

function nc_rest_create_issue(WP_REST_Request $req) {
    $title   = sanitize_text_field($req->get_param('title'));
    $content = sanitize_textarea_field($req->get_param('description'));
    $lat     = (float) $req->get_param('lat');
    $lng     = (float) $req->get_param('lng');

    if (!$title) return new WP_Error('missing_fields', 'Title is required.', ['status' => 400]);

    $id = wp_insert_post([
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => 'nc_issue',
        'post_author'  => get_current_user_id(),
    ]);

    if (is_wp_error($id)) return $id;

    update_post_meta($id, '_nc_status', 'open');
    if ($lat) update_post_meta($id, '_nc_lat', $lat);
    if ($lng) update_post_meta($id, '_nc_lng', $lng);

    return new WP_REST_Response(['id' => $id, 'status' => 'open'], 201);
}

/* ============================================================
   REST Format Helpers
   ============================================================ */
function nc_format_event_rest(WP_Post $post): array {
    return [
        'id'       => $post->ID,
        'title'    => $post->post_title,
        'excerpt'  => get_the_excerpt($post),
        'url'      => get_permalink($post),
        'image'    => get_the_post_thumbnail_url($post, 'nc-card') ?: null,
        'date'     => get_post_meta($post->ID, '_nc_event_date', true),
        'location' => get_post_meta($post->ID, '_nc_location', true),
        'capacity' => (int) get_post_meta($post->ID, '_nc_capacity', true),
        'rsvps'    => count(get_post_meta($post->ID, '_nc_rsvps', true) ?: []),
    ];
}

function nc_format_service_rest(WP_Post $post): array {
    return [
        'id'       => $post->ID,
        'title'    => $post->post_title,
        'excerpt'  => get_the_excerpt($post),
        'url'      => get_permalink($post),
        'image'    => get_the_post_thumbnail_url($post, 'nc-avatar') ?: null,
        'category' => get_post_meta($post->ID, '_nc_service_category', true),
        'price'    => get_post_meta($post->ID, '_nc_price', true),
        'rating'   => (float) get_post_meta($post->ID, '_nc_rating', true),
    ];
}

function nc_format_issue_rest(WP_Post $post): array {
    return [
        'id'      => $post->ID,
        'title'   => $post->post_title,
        'excerpt' => get_the_excerpt($post),
        'url'     => get_permalink($post),
        'status'  => get_post_meta($post->ID, '_nc_status', true) ?: 'open',
        'votes'   => (int) get_post_meta($post->ID, '_nc_votes', true),
        'lat'     => (float) get_post_meta($post->ID, '_nc_lat', true),
        'lng'     => (float) get_post_meta($post->ID, '_nc_lng', true),
    ];
}

/* ============================================================
   AJAX Handlers
   ============================================================ */
function nc_verify_nonce() {
    if (!check_ajax_referer('nc_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.'], 403);
    }
}

// RSVP
add_action('wp_ajax_nc_rsvp', 'nc_ajax_rsvp');
function nc_ajax_rsvp() {
    nc_verify_nonce();
    $event_id = absint($_POST['event_id'] ?? 0);
    $type     = sanitize_text_field($_POST['type'] ?? 'join');
    $user_id  = get_current_user_id();

    if (!$event_id) wp_send_json_error(['message' => 'Invalid event.']);

    $rsvps = get_post_meta($event_id, '_nc_rsvps', true) ?: [];

    if ($type === 'join') {
        if (!in_array($user_id, $rsvps)) $rsvps[] = $user_id;
    } else {
        $rsvps = array_values(array_diff($rsvps, [$user_id]));
    }

    update_post_meta($event_id, '_nc_rsvps', $rsvps);
    wp_send_json_success(['count' => count($rsvps), 'type' => $type]);
}

add_action('wp_ajax_nopriv_nc_rsvp', function () {
    wp_send_json_error(['message' => 'Please log in to RSVP.'], 401);
});

// Vote on issue
add_action('wp_ajax_nc_vote_issue', 'nc_ajax_vote_issue');
function nc_ajax_vote_issue() {
    nc_verify_nonce();
    $issue_id = absint($_POST['issue_id'] ?? 0);
    $user_id  = get_current_user_id();

    if (!$issue_id) wp_send_json_error(['message' => 'Invalid issue.']);

    $voters = get_post_meta($issue_id, '_nc_voters', true) ?: [];

    if (in_array($user_id, $voters)) {
        $voters = array_values(array_diff($voters, [$user_id]));
    } else {
        $voters[] = $user_id;
    }

    update_post_meta($issue_id, '_nc_voters', $voters);
    update_post_meta($issue_id, '_nc_votes', count($voters));
    wp_send_json_success(['votes' => count($voters)]);
}

add_action('wp_ajax_nopriv_nc_vote_issue', function () {
    wp_send_json_error(['message' => 'Login required.'], 401);
});

// Live Search
add_action('wp_ajax_nc_search',        'nc_ajax_search');
add_action('wp_ajax_nopriv_nc_search', 'nc_ajax_search');
function nc_ajax_search() {
    nc_verify_nonce();
    $q = sanitize_text_field($_POST['q'] ?? '');
    if (strlen($q) < 2) wp_send_json_error();

    $results = [];
    $icon_map = ['nc_event' => 'fa-calendar', 'nc_service' => 'fa-briefcase', 'nc_issue' => 'fa-triangle-exclamation', 'post' => 'fa-newspaper'];

    $query = new WP_Query([
        's'              => $q,
        'post_type'      => ['post', 'nc_event', 'nc_service', 'nc_issue'],
        'posts_per_page' => 6,
        'post_status'    => 'publish',
    ]);

    foreach ($query->posts as $post) {
        $results[] = [
            'title' => $post->post_title,
            'url'   => get_permalink($post),
            'type'  => $post->post_type,
            'icon'  => $icon_map[$post->post_type] ?? 'fa-file',
        ];
    }

    wp_send_json_success($results);
}

// Newsletter subscribe
add_action('wp_ajax_nc_newsletter',        'nc_ajax_newsletter');
add_action('wp_ajax_nopriv_nc_newsletter', 'nc_ajax_newsletter');
function nc_ajax_newsletter() {
    nc_verify_nonce();
    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) wp_send_json_error(['message' => 'Invalid email.']);

    $subscribers = get_option('nc_newsletter_subscribers', []);
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('nc_newsletter_subscribers', $subscribers);
    }

    wp_send_json_success(['message' => 'Subscribed successfully.']);
}

/* ============================================================
   Excerpt Length
   ============================================================ */
add_filter('excerpt_length', fn() => 20);
add_filter('excerpt_more', fn() => '&hellip;');

/* ============================================================
   Body Classes
   ============================================================ */
function nc_body_classes($classes) {
    if (!is_singular()) $classes[] = 'hfeed';
    if (is_user_logged_in()) $classes[] = 'logged-in-user';
    return $classes;
}
add_filter('body_class', 'nc_body_classes');

/* ============================================================
   Theme Customizer
   ============================================================ */
function nc_customize(WP_Customize_Manager $wp_customize) {
    $wp_customize->add_section('nc_settings', [
        'title'    => __('Neighborhood Connect', 'neighborhood-connect'),
        'priority' => 30,
    ]);

    $settings = [
        'nc_hero_title'        => ['default' => 'Canal View Society, online.', 'label' => 'Hero Title'],
        'nc_hero_description'  => ['default' => 'Book in-society services, RSVP to community events, report issues, and discover everything around Canal View — all in one place.', 'label' => 'Hero Description', 'type' => 'textarea'],
        'nc_neighborhood'      => ['default' => 'Canal View Society', 'label' => 'Society Name'],
        'nc_google_maps_key'   => ['default' => '', 'label' => 'Google Maps API Key'],
        'nc_footer_tagline'    => ['default' => 'Built for the residents of Canal View Cooperative Housing Society, Lahore.', 'label' => 'Footer Tagline', 'type' => 'textarea'],
        'nc_society_tagline'   => ['default' => 'A walkable, well-connected residential community along the BRB Canal in Lahore.', 'label' => 'Society Tagline', 'type' => 'textarea'],
        'nc_society_founded'   => ['default' => '1986',        'label' => 'Founded (year)'],
        'nc_society_units'     => ['default' => '1,200+',      'label' => 'Total Plots / Units'],
        'nc_society_residents' => ['default' => '5,400+',      'label' => 'Total Residents'],
        'nc_society_area'      => ['default' => '450+ kanals', 'label' => 'Total Area'],
        'nc_society_blocks'    => ['default' => 'Phase 1 · Phase 2 · Block A · Block B · Block C', 'label' => 'Blocks / Phases'],
    ];

    foreach ($settings as $id => $config) {
        $wp_customize->add_setting($id, [
            'default'           => $config['default'],
            'sanitize_callback' => isset($config['type']) && $config['type'] === 'textarea'
                ? 'sanitize_textarea_field'
                : 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);

        $wp_customize->add_control($id, [
            'label'   => __($config['label'], 'neighborhood-connect'),
            'section' => 'nc_settings',
            'type'    => $config['type'] ?? 'text',
        ]);
    }
}
add_action('customize_register', 'nc_customize');

/* ============================================================
   Helper Functions
   ============================================================ */
function nc_get_event_meta(int $id): array {
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

function nc_user_has_rsvpd(int $event_id, int $user_id): bool {
    $rsvps = get_post_meta($event_id, '_nc_rsvps', true) ?: [];
    return in_array($user_id, $rsvps);
}

function nc_format_date(string $date): string {
    $ts = strtotime($date);
    return $ts ? date_i18n(get_option('date_format'), $ts) : $date;
}

function nc_star_rating(float $rating, int $max = 5): string {
    $html = '<span class="stars" aria-label="' . esc_attr($rating . ' out of ' . $max) . '">';
    for ($i = 1; $i <= $max; $i++) {
        if ($rating >= $i) {
            $html .= '<i class="fa-solid fa-star"></i>';
        } elseif ($rating >= $i - 0.5) {
            $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
        } else {
            $html .= '<i class="fa-regular fa-star"></i>';
        }
    }
    return $html . '</span>';
}

function nc_avatar_initials(string $name): string {
    $words = explode(' ', trim($name));
    $initials = '';
    foreach (array_slice($words, 0, 2) as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return $initials ?: '?';
}

$avatar_colors = ['#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed', '#db2777', '#0891b2'];
function nc_avatar_color(int $user_id): string {
    global $avatar_colors;
    return $avatar_colors[$user_id % count($avatar_colors)];
}

/* ============================================================
   Auth URL Filters
   ============================================================ */
add_filter('login_url', function ($url, $redirect) {
    $page = get_page_by_path('login');
    if ($page) {
        $url = get_permalink($page->ID);
        if ($redirect) $url = add_query_arg('redirect_to', urlencode($redirect), $url);
    }
    return $url;
}, 10, 2);

add_filter('register_url', function ($url) {
    $page = get_page_by_path('register');
    return $page ? get_permalink($page->ID) : $url;
}, 10, 1);

/* ============================================================
   AJAX: Login
   ============================================================ */
add_action('wp_ajax_nopriv_nc_ajax_login', 'nc_ajax_login');
function nc_ajax_login() {
    if (!check_ajax_referer('nc_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.'], 403);
    }

    $credentials = [
        'user_login'    => sanitize_text_field($_POST['log'] ?? ''),
        'user_password' => $_POST['pwd'] ?? '',
        'remember'      => !empty($_POST['rememberme']),
    ];

    if (empty($credentials['user_login']) || empty($credentials['user_password'])) {
        wp_send_json_error(['message' => 'Please enter your username and password.']);
    }

    $user = wp_signon($credentials, false);

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Invalid username or password. Please try again.']);
    }

    $redirect = sanitize_url($_POST['redirect_to'] ?? home_url('/'));
    wp_send_json_success(['message' => 'Welcome back, ' . esc_html($user->display_name) . '!', 'redirect' => $redirect]);
}

/* ============================================================
   AJAX: Register
   ============================================================ */
add_action('wp_ajax_nopriv_nc_ajax_register', 'nc_ajax_register');
function nc_ajax_register() {
    if (!check_ajax_referer('nc_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.'], 403);
    }

    if (!get_option('users_can_register')) {
        wp_send_json_error(['message' => 'Registration is currently disabled.']);
    }

    $username   = sanitize_user($_POST['user_login'] ?? '');
    $email      = sanitize_email($_POST['user_email'] ?? '');
    $password   = $_POST['user_pass'] ?? '';
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');

    if (!$username || !$email || !$password) {
        wp_send_json_error(['message' => 'Please fill in all required fields.']);
    }

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Please enter a valid email address.']);
    }

    if (username_exists($username)) {
        wp_send_json_error(['message' => 'That username is already taken. Please choose another.']);
    }

    if (email_exists($email)) {
        wp_send_json_error(['message' => 'An account with that email already exists.']);
    }

    if (strlen($password) < 8) {
        wp_send_json_error(['message' => 'Password must be at least 8 characters long.']);
    }

    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }

    wp_update_user(['ID' => $user_id, 'first_name' => $first_name, 'last_name' => $last_name, 'display_name' => trim($first_name . ' ' . $last_name) ?: $username]);

    // Auto login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, false);

    wp_send_json_success(['message' => 'Account created! Welcome, ' . esc_html($first_name ?: $username) . '!', 'redirect' => home_url('/')]);
}

/* ============================================================
   AJAX: Report Issue
   ============================================================ */
add_action('wp_ajax_nc_ajax_report_issue', 'nc_ajax_report_issue');
function nc_ajax_report_issue() {
    if (!check_ajax_referer('nc_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.'], 403);
    }

    $title    = sanitize_text_field($_POST['title'] ?? '');
    $desc     = sanitize_textarea_field($_POST['description'] ?? '');
    $location = sanitize_text_field($_POST['location'] ?? '');
    $type     = sanitize_text_field($_POST['issue_type'] ?? '');

    if (!$title) {
        wp_send_json_error(['message' => 'Please enter an issue title.']);
    }

    $id = wp_insert_post([
        'post_title'   => $title,
        'post_content' => $desc,
        'post_status'  => 'publish',
        'post_type'    => 'nc_issue',
        'post_author'  => get_current_user_id(),
    ]);

    if (is_wp_error($id)) {
        wp_send_json_error(['message' => 'Failed to create issue.']);
    }

    update_post_meta($id, '_nc_status', 'open');
    if ($location) update_post_meta($id, '_nc_location', $location);
    if ($type)     update_post_meta($id, '_nc_issue_type', $type);
    update_post_meta($id, '_nc_votes', 0);

    wp_send_json_success(['id' => $id, 'url' => get_permalink($id)]);
}

/* ============================================================
   AJAX: Submit Service Request (society in-house wizard)
   ============================================================ */
add_action('wp_ajax_nopriv_nc_submit_service_request', function () {
    wp_send_json_error(['message' => 'Please log in to request a service.', 'redirect' => home_url('/login/')], 401);
});
add_action('wp_ajax_nc_submit_service_request', 'nc_ajax_submit_service_request');
function nc_ajax_submit_service_request() {
    if (!check_ajax_referer('nc_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.'], 403);
    }

    $service_id = absint($_POST['service_id'] ?? 0);
    $sub        = sanitize_text_field($_POST['sub_service'] ?? '');
    $desc       = sanitize_textarea_field($_POST['description'] ?? '');
    $flat       = sanitize_text_field($_POST['flat'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $time_pref  = sanitize_text_field($_POST['preferred_time'] ?? '');
    $urgency    = sanitize_text_field($_POST['urgency'] ?? 'normal');

    if (!in_array($urgency, ['low', 'normal', 'urgent'], true)) $urgency = 'normal';

    $service = get_post($service_id);
    if (!$service || $service->post_type !== 'nc_service') {
        wp_send_json_error(['message' => 'Please pick a service category.']);
    }
    if (!$flat) {
        wp_send_json_error(['message' => 'Please enter your flat / unit number so the team can find you.']);
    }

    $cat_slug = get_post_meta($service_id, '_nc_service_slug', true) ?: sanitize_title($service->post_title);
    $assigned = nc_pick_team_member($cat_slug);

    $title = sprintf('%s — %s · Flat %s', $service->post_title, $sub ?: 'general', $flat);
    $req_id = wp_insert_post([
        'post_title'   => $title,
        'post_content' => $desc,
        'post_status'  => 'publish',
        'post_type'    => 'nc_service_request',
        'post_author'  => get_current_user_id(),
    ]);
    if (is_wp_error($req_id)) wp_send_json_error(['message' => 'Could not create your request. Please try again.']);

    update_post_meta($req_id, '_nc_sr_service_id',     $service_id);
    update_post_meta($req_id, '_nc_sr_service_slug',   $cat_slug);
    update_post_meta($req_id, '_nc_sr_subservice',     $sub);
    update_post_meta($req_id, '_nc_sr_flat',           $flat);
    update_post_meta($req_id, '_nc_sr_phone',          $phone);
    update_post_meta($req_id, '_nc_sr_preferred_time', $time_pref);
    update_post_meta($req_id, '_nc_sr_urgency',        $urgency);
    update_post_meta($req_id, '_nc_sr_assigned_to',    $assigned);
    update_post_meta($req_id, '_nc_sr_status',         $assigned ? 'assigned' : 'pending');

    $assigned_user = $assigned ? get_userdata($assigned) : null;

    wp_send_json_success([
        'request_id'    => $req_id,
        'reference'     => sprintf('SR-%04d', $req_id),
        'status'        => $assigned ? 'assigned' : 'pending',
        'service'       => $service->post_title,
        'sub_service'   => $sub,
        'assigned_name' => $assigned_user ? $assigned_user->display_name : null,
        'assigned_phone'=> $assigned_user ? get_user_meta($assigned, '_nc_team_phone', true) : null,
        'urgency'       => $urgency,
    ]);
}

/* ============================================================
   AJAX: Contact Service
   ============================================================ */
add_action('wp_ajax_nc_ajax_contact_service', 'nc_ajax_contact_service');
function nc_ajax_contact_service() {
    if (!check_ajax_referer('nc_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.'], 403);
    }

    $service_id = absint($_POST['service_id'] ?? 0);
    $name       = sanitize_text_field($_POST['contact_name'] ?? '');
    $email      = sanitize_email($_POST['contact_email'] ?? '');
    $message    = sanitize_textarea_field($_POST['contact_message'] ?? '');

    if (!$service_id || !$name || !$email || !$message) {
        wp_send_json_error(['message' => 'Please fill in all fields.']);
    }

    // Get service owner email
    $service = get_post($service_id);
    $owner_email = get_post_meta($service_id, '_nc_email', true);
    if (!$owner_email) {
        $owner = get_userdata($service ? $service->post_author : 0);
        $owner_email = $owner ? $owner->user_email : get_option('admin_email');
    }

    $subject = 'New inquiry for ' . get_the_title($service_id) . ' from ' . $name;
    $body    = "Name: $name\nEmail: $email\n\n$message";
    wp_mail($owner_email, $subject, $body, ['Reply-To: ' . $email]);

    wp_send_json_success(['message' => 'Message sent successfully.']);
}
