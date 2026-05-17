<?php
/**
 * Plugin Name:       NC Events
 * Plugin URI:        https://github.com/khursandsohail/neighborhood-connect
 * Description:       Event management for Neighborhood Connect — iCal export, email reminders, and single event template.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Khursand Sohail
 * Text Domain:       nc-events
 * License:           MIT
 */

defined('ABSPATH') || exit;

/* ============================================================
   Single Event Template Override
   ============================================================ */
add_filter('single_template', function ($template) {
    global $post;
    if ($post->post_type === 'nc_event') {
        $custom = plugin_dir_path(__FILE__) . 'templates/single-nc_event.php';
        if (file_exists($custom)) return $custom;
    }
    return $template;
});

add_filter('archive_template', function ($template) {
    if (is_post_type_archive('nc_event') || is_tax('nc_event_category')) {
        $custom = plugin_dir_path(__FILE__) . 'templates/archive-nc_event.php';
        if (file_exists($custom)) return $custom;
    }
    return $template;
});

/* ============================================================
   iCal Export Endpoint
   ============================================================ */
add_action('init', function () {
    add_rewrite_rule('^events/([0-9]+)/ical/?$', 'index.php?nc_ical_event=$matches[1]', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'nc_ical_event';
    return $vars;
});

add_action('template_redirect', function () {
    $event_id = get_query_var('nc_ical_event');
    if (!$event_id) return;

    $post = get_post((int) $event_id);
    if (!$post || $post->post_type !== 'nc_event') {
        wp_die('Event not found', 404);
    }

    $date     = get_post_meta($post->ID, '_nc_event_date', true);
    $time     = get_post_meta($post->ID, '_nc_event_time', true) ?: '00:00';
    $end_time = get_post_meta($post->ID, '_nc_event_end_time', true) ?: '23:59';
    $location = get_post_meta($post->ID, '_nc_location', true);
    $dt_start = date('Ymd\THis\Z', strtotime("$date $time"));
    $dt_end   = date('Ymd\THis\Z', strtotime("$date $end_time"));
    $dt_stamp = date('Ymd\THis\Z');

    $title       = $post->post_title;
    $description = wp_strip_all_tags($post->post_content);
    $url         = get_permalink($post);
    $uid         = 'nc-event-' . $post->ID . '@' . parse_url(home_url(), PHP_URL_HOST);

    // Fold long lines per RFC 5545
    $fold = fn($s) => chunk_split($s, 74, "\r\n ");

    $ical  = "BEGIN:VCALENDAR\r\n";
    $ical .= "VERSION:2.0\r\n";
    $ical .= "PRODID:-//Neighborhood Connect//Events//EN\r\n";
    $ical .= "CALSCALE:GREGORIAN\r\n";
    $ical .= "METHOD:PUBLISH\r\n";
    $ical .= "BEGIN:VEVENT\r\n";
    $ical .= "UID:$uid\r\n";
    $ical .= "DTSTAMP:$dt_stamp\r\n";
    $ical .= "DTSTART:$dt_start\r\n";
    $ical .= "DTEND:$dt_end\r\n";
    $ical .= "SUMMARY:" . $fold(str_replace(["\r\n", "\n"], ' ', $title)) . "\r\n";
    if ($description) $ical .= "DESCRIPTION:" . $fold(str_replace(["\r\n", "\n"], '\n', $description)) . "\r\n";
    if ($location) $ical .= "LOCATION:" . $fold($location) . "\r\n";
    $ical .= "URL:$url\r\n";
    $ical .= "END:VEVENT\r\n";
    $ical .= "END:VCALENDAR\r\n";

    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="event-' . $post->ID . '.ics"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    echo $ical; // phpcs:ignore WordPress.Security.EscapeOutput
    exit;
});

/* ============================================================
   Email Reminders (Daily Cron)
   ============================================================ */
add_action('nc_send_event_reminders', 'nc_process_event_reminders');

function nc_process_event_reminders() {
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    $events = get_posts([
        'post_type'   => 'nc_event',
        'post_status' => 'publish',
        'meta_query'  => [
            ['key' => '_nc_event_date', 'value' => $tomorrow, 'compare' => '='],
        ],
        'numberposts' => -1,
    ]);

    foreach ($events as $event) {
        $rsvps = get_post_meta($event->ID, '_nc_rsvps', true) ?: [];

        foreach ($rsvps as $user_id) {
            $user = get_userdata((int) $user_id);
            if (!$user || !$user->user_email) continue;

            $sent_key = '_nc_reminder_sent_' . $event->ID;
            if (get_user_meta($user_id, $sent_key, true)) continue;

            $location = get_post_meta($event->ID, '_nc_location', true);
            $time     = get_post_meta($event->ID, '_nc_event_time', true);
            $subject  = sprintf('[%s] Reminder: %s is tomorrow!', get_bloginfo('name'), $event->post_title);
            $message  = sprintf(
                "Hi %s,\n\nThis is a friendly reminder that you have an upcoming event:\n\n📅 %s\n📍 %s\n🕐 %s\n\nView event: %s\n\nSee you there!\n– The Neighborhood Connect Team",
                esc_html($user->display_name),
                esc_html($event->post_title),
                esc_html($location ?: 'TBD'),
                esc_html($time ?: 'TBD'),
                esc_url(get_permalink($event))
            );

            if (wp_mail($user->user_email, $subject, $message)) {
                update_user_meta($user_id, $sent_key, true);
            }
        }
    }
}

if (!wp_next_scheduled('nc_send_event_reminders')) {
    wp_schedule_event(strtotime('tomorrow 08:00:00'), 'daily', 'nc_send_event_reminders');
}

/* ============================================================
   Shortcodes
   ============================================================ */

// [nc_events count="6" category="social"]
add_shortcode('nc_events', function ($atts) {
    $atts = shortcode_atts(['count' => 6, 'category' => ''], $atts, 'nc_events');

    $args = [
        'post_type'      => 'nc_event',
        'posts_per_page' => absint($atts['count']),
        'post_status'    => 'publish',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_key'       => '_nc_event_date',
    ];

    if ($atts['category']) {
        $args['tax_query'] = [['taxonomy' => 'nc_event_category', 'field' => 'slug', 'terms' => $atts['category']]];
    }

    $q = new WP_Query($args);
    if (!$q->have_posts()) return '<p>' . esc_html__('No upcoming events.', 'nc-events') . '</p>';

    ob_start();
    echo '<div class="card-grid">';
    while ($q->have_posts()) { $q->the_post(); get_template_part('template-parts/content', 'event'); }
    wp_reset_postdata();
    echo '</div>';
    return ob_get_clean();
});

// [nc_event_count] — displays total published events
add_shortcode('nc_event_count', function () {
    return wp_count_posts('nc_event')->publish;
});

/* ============================================================
   Activation
   ============================================================ */
register_activation_hook(__FILE__, function () {
    if (!wp_next_scheduled('nc_send_event_reminders')) {
        wp_schedule_event(strtotime('tomorrow 08:00:00'), 'daily', 'nc_send_event_reminders');
    }
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function () {
    wp_clear_scheduled_hook('nc_send_event_reminders');
    flush_rewrite_rules();
});
