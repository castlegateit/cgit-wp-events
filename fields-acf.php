<?php

/**
 * Add custom fields using Advanced Custom Fields
 *
 * Advanced Custom Fields uses functions to add fields, so this file should
 * return if the Advanced Custom Fields plugin is not installed.
 */


/**
 * Check dependencies
 */
if (!function_exists('acf_add_local_field_group')) {
    return;
}


/**
 * Generate list of times
 */
$start = mktime(0, 0, 0);
$times = array();

for ($i = 0; $i < 86400; $i += 1800) {
    $time = date('H:i', $start + $i);
    $times[$time] = $time;
}


/**
 * Add date and time fields
 */
acf_add_local_field_group(array(
    'key' => 'cgit_wp_events_when',
    'title' => 'When',
    'fields' => array(
        array(
            'key' => 'start_date',
            'name' => 'start_date',
            'label' => 'Start date',
            'type' => 'date_picker',
            'required' => true,
        ),
        array(
            'key' => 'start_time',
            'name' => 'start_time',
            'label' => 'Start time',
            'type' => 'select',
            'choices' => $times,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'all_day',
                        'operator' => '!=',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'end_date',
            'name' => 'end_date',
            'label' => 'End date',
            'type' => 'date_picker',
        ),
        array(
            'key' => 'end_time',
            'name' => 'end_time',
            'label' => 'End time',
            'type' => 'select',
            'choices' => $times,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'all_day',
                        'operator' => '!=',
                        'value' => '1',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'all_day',
            'name' => 'all_day',
            'label' => 'All day event?',
            'type' => 'true_false',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => CGIT_EVENTS_POST_TYPE,
            ),
        ),
    ),
    'position' => 'side',
));


/**
 * Add location fields
 */
acf_add_local_field_group(array(
    'key' => 'cgit_wp_events_where',
    'title' => 'Where',
    'fields' => array(
        array(
            'key' => 'location_name',
            'name' => 'location_name',
            'label' => 'Location name',
            'type' => 'text',
        ),
        array(
            'key' => 'location_address',
            'name' => 'location_address',
            'label' => 'Address',
            'type' => 'textarea',
        ),
        array(
            'key' => 'location',
            'name' => 'location',
            'label' => 'Location',
            'type' => 'google_map',
        ),
        array(
            'key' => 'price',
            'name' => 'price',
            'label' => 'Price',
            'type' => 'number',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => CGIT_EVENTS_POST_TYPE,
            ),
        ),
    ),
));
