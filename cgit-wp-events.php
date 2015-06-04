<?php

/*
Plugin Name: Castlegate IT WP Events
Plugin URI: https://github.com/castlegateit/cgit-wp-events/
Description: A simple and easy to use events interface with complete developer
control.
Version: 1.1
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
*/


/**
 * Constants
 */
define('CGIT_EVENTS_POST_TYPE', 'event');
define('CGIT_EVENTS_POST_TYPE_CATEGORY', 'event-category');


/**
 * Include
 */
include('admin-options.php');
include('activation.php');
include('post-type.php');
include('rewrite.php');
include('query.php');
include('calendar.php');
include('widget.php');
include('ajax.php');


/**
 * Activate/Uninstall hooks
 */
register_activation_hook(__FILE__, 'cgit_wp_events_activate');
register_uninstall_hook(__FILE__, 'cgit_wp_events_uninstall');


/**
 * Retuns a calendar view of all events in the WordPress installation. The
 * calendar is AJAX powered. Presentation can be customised through the admin
 * settings page.
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return string
 */
function cgit_wp_events_calendar() {

    // Get the current year and month or set a default
    $year = isset($_GET['cgit-year']) ? $_GET['cgit-year'] : date('Y');
    $month = isset($_GET['cgit-month']) ? $_GET['cgit-month'] : date('m');

    // New calendar instance
    $events_calendar = new Cgit_event_calendar($year, $month);

    return  $events_calendar->render();

}


/**
 * Get the latest upcoming events. Automagically gets the meta values for start
 * and end dates/time.
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return string
 */
function cgit_wp_events_latest($limit = 3) {

    $now = new DateTime('now');

    // Get events that are not in the past, ordered by start date.
    $args = array(
        'post_type' => CGIT_EVENTS_POST_TYPE,
        'post_status' => 'publish',
        'meta_key' => 'start_date',
        'orderby' => 'start_date',
        'order' => 'ASC',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => 'start_date',
                'value' => $now->format('U'),
                'type' => 'NUMERIC',
                'compare' => '>='
            )
       )
    );

    $query = new WP_Query($args);

    if ($query->posts) {

        // For each post - get the meta data
        foreach ($query->posts as $key => $event) {

            // Start date
            $query->posts[$key]->start_date = get_post_meta(
                $event->ID, 'start_date', true
            );

            // End date
            $query->posts[$key]->end_date = get_post_meta(
                $event->ID, 'end_date', true
            );

            // Start time
             $query->posts[$key]->start_time = get_post_meta(
                $event->ID, 'start_time', true
            );

            // End time
            $query->posts[$key]->end_time = get_post_meta(
                $event->ID, 'end_time', true
            );

            // End time
            $query->posts[$key]->price = (float)get_post_meta(
                $event->ID, 'price', true
            );
        }

        return $query->posts;
    }

    return array();

}


/**
 * Convert 12 hour time strings into 24 hour
 *
 * @param  $hour12 12 hour time string
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return string
 */
function cgit_wp_events_to_24_hour_time($hour12) {
    return  date("H:i", strtotime($hour12));
}


/**
 * Returns a title for the events archive which displays the category name, or
 * day, month, year, depending on the view.
 *
 * @param $title Main title
 * @param $seperator Separator for the event title
 * @param $year_format Format for the year view
 * @param $month_format Format for the month view
 * @param $day_format Format for the day view
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return string
 */
function cgit_wp_events_archive_title(
    $title = 'Events',
    $seperator = ' - ',
    $year_format = 'Y',
    $month_format = 'F Y',
    $day_format = 'jS M Y'
) {

    $return = $title . $seperator;

    if (is_day()) {

        $time = mktime(
            0,
            0,
            0,
            CGIT_WP_EVENTS_MONTH,
            CGIT_WP_EVENTS_DAY,
            CGIT_WP_EVENTS_YEAR
        );
        return $return . date($day_format, $time);

    } elseif (is_month()) {

        $time = mktime(
            0,
            0,
            0,
            CGIT_WP_EVENTS_MONTH,
            1,
            CGIT_WP_EVENTS_YEAR
        );

        return $return . date($month_format, $time);

    } elseif (is_year()) {

        $time = mktime(
            0,
            0,
            0,
            1,
            1,
            CGIT_WP_EVENTS_YEAR
        );

        return $return . date($year_format, $time);

    } else {

        return $title;

    }

}
