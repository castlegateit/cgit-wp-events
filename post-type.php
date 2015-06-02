<?php

/**
 * Defines the event custom post type
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_post_type() {

    $labels = array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'new_item' => 'New Event',
        'view_item' => 'View Event',
        'search_items' => 'Search Events',
        'not_found' => 'No events found',
        'not_found_in_trash' => 'No events found in Trash',
    );

    // Get support from options
    $supports = array(
        'title',
        'excerpt',
        'revisions'
    );

    foreach (cgit_wp_events::$options as $option => $v) {

        if (substr($option, 0, 33) == 'cgit_wp_events_post_type_support_'
            && get_option($option) == 1
        ) {
            $supports[] = substr($option, 33);
        }
    }

    // Post type rewrite options
    $rewrite = array(
        'slug' => CGIT_EVENTS_POST_TYPE,
        'with_front' => false,
    );

    // Post type options
    $options = array(
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => $supports,
        'has_archive' => true,
        'rewrite' => $rewrite,
        'query_var' => CGIT_EVENTS_POST_TYPE,
    );

    // Should we include tags?
    if (get_option('cgit_wp_events_post_type_support_tag') == 1) {
        $options['taxonomies'] = array('post_tag');
    }

    register_post_type(CGIT_EVENTS_POST_TYPE, $options);

}
add_action('init', 'cgit_wp_events_post_type', 10);


/**
 * Defines the categories taxonomy
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_taxonomy() {

    if (get_option('cgit_wp_events_post_type_support_category') == 1) {
        $labels = array(
            'name' => 'Categories',
            'singular_name' => 'Category',
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
        );

        register_taxonomy(
            CGIT_EVENTS_POST_TYPE_CATEGORY,
            CGIT_EVENTS_POST_TYPE,
            $args
        );
    }

}
add_action('init', 'cgit_wp_events_taxonomy');


/**
 * Add custom meta box fields for our events
 *
 * @todo We ought to make UK_date use datetimes rather than unix timestamps
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return array
 */
function cgit_wp_events_fields($meta_boxes) {

    $date_type = 'date_unix';
    $types = _cmb_available_fields();

    if (array_key_exists('UK_date', $types)) {
        $date_type = 'UK_date';
    }

    $fields = array(

        array(
            'id' => 'price',
            'name' => 'Price',
            'type' => 'text',
            'cols' => 2
        ),
        array(
            'id' => 'start_date',
            'name' => 'Start Date',
            'type' => $date_type,
            'required' => true,
            'cols' => 2,
        ),
        array(
            'id' => 'start_time',
            'name' => 'Start Time',
            'type' => 'time',
            'cols' => 2,
        ),
        array(
            'id' => 'end_date',
            'name' => 'End Date',
            'type' => $date_type,
            'cols' => 2,
        ),
        array(
            'id' => 'end_time',
            'name' => 'End Time',
            'type' => 'time',
            'cols' => 2,
        ),
        array(
            'id' => 'location_name',
            'name' => 'Location name',
            'type' => 'text',
            'cols' => 2
        )
    );

    $meta_box = array(
        'id' => 'cgit-events',
        'title' => 'Event',
        'pages' => array('event'),
        'context' => 'normal',
        'fields' => $fields,
    );

    $meta_boxes[] = $meta_box;

    return $meta_boxes;

}
add_filter('cmb_meta_boxes', 'cgit_wp_events_fields');


/**
 * Hack: custom-meta-boxes does not currently allow for required fields. We
 * require an end date. Forces empty end dates to be the same as the start date
 *
 * @todo Should also check that end date is after start date here, it can be set
 * to start date if not. JS validation would improve this.
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_save_post() {

    global $post;

    // Get dates
    $start = esc_attr($_POST['start_date']);
    $end = esc_attr($_POST['end_date']);

    // Check they are arrays as expected
    if (is_array($start)) {
        $start =  reset($start);
    }
    if (is_array($end)) {
        $end =  reset($end);
    }

    // If the end date is empty, copy the start date.
    if (empty($end)) {
        $_POST['end_date'] = $_POST['start_date'];
    }

}
add_action('save_post', 'cgit_wp_events_save_post');
