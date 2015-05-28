<?php

/**
 * Enqueue the calendar AJAX javascript
 */
wp_enqueue_script('cgit-events-calendar', plugins_url('/js/calendar.js', __FILE__ ), array('jquery'));


/**
 * Define the AJAX handler
 */
wp_localize_script(
    'cgit-events-calendar',
    'ajax_object',
    array(
        'ajax_url' => admin_url('admin-ajax.php')
    )
);


/**
 * AJAX handler function
 */
function cgit_events_calendar_callback() {

    if (isset($_POST['year']) && isset($_POST['month'])) {

        $calendar = new Cgit_event_calendar(
            $_POST['year'],
            $_POST['month']
        );

        echo json_encode($calendar->get_ajax());

    }
    wp_die();
}


/**
 * Filter must be applied to front end requests only, privileged and non privileged users
 */
add_action('wp_ajax_cgit_events_calendar', 'cgit_events_calendar_callback');
add_action('wp_ajax_nopriv_cgit_events_calendar', 'cgit_events_calendar_callback');

