<?php

/**
 * Static classes for global variable access
 */
class cgit_wp_events {

    public static $options = array(
        'cgit_wp_events_format_day' => array(
            'd' => '01 to 31',
            'j' => '1 to 31'
        ),
        'cgit_wp_events_format_current_month' => array(
            'M \<\s\p\a\n\>Y\</\s\p\a\n\>' => 'M Y',
            'F \<\s\p\a\n\>Y\</\s\p\a\n\>' => 'F Y',
            'M \<\s\p\a\n\>y\</\s\p\a\n\>' => 'M y',
            'F \<\s\p\a\n\>y\</\s\p\a\n\>' => 'F y'
        ),
        'cgit_wp_events_format_next_year' => '&raquo;',
        'cgit_wp_events_format_prev_year' => '&laquo;',
        'cgit_wp_events_format_next_month' => '&rsaquo;',
        'cgit_wp_events_format_prev_month' => '&lsaquo;',
        'cgit_wp_events_class_prefix' => 'cgit-events-',
        'cgit_wp_events_post_type_menu_position' => array(
            5 => 'below Posts',
            10 => 'below Media',
            15 => ' below Links',
            20 => ' below Pages',
            25 => ' below comments',
            60  => 'below first separator',
            65  => 'below Plugins',
            70 => 'below Users',
            75  => 'below Tools',
            80  => 'below Settings',
            100 => 'below second separator',
        ),
        'cgit_wp_events_post_type_support_category' => '1',
        'cgit_wp_events_post_type_support_tag' => '',
        'cgit_wp_events_post_type_support_editor' => '1',
        'cgit_wp_events_post_type_support_author' => '',
        'cgit_wp_events_post_type_support_thumbnail' => '',
        'cgit_wp_events_post_type_support_comments' => '',
        'cgit_wp_events_post_type_support_page-attributes' => ''
    );
}


/**
 * Register plugin settings
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_register_settings() {

    foreach (cgit_wp_events::$options as $option => $v) {
        register_setting('cgit-events', $option);
    }
}


/**
 * Register plugin settings menu item
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_add_settings_page() {

    // Add page
    add_menu_page(
        'Event Settings',
        'Event Settings',
        'manage_options',
        'cgit-events',
        'cgit_wp_events_render_settings_page'
    );

    // Register settings
    add_action('admin_init', 'cgit_wp_events_register_settings');

}
add_action('admin_menu', 'cgit_wp_events_add_settings_page');


/**
 * Render settings page content
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_render_settings_page() {
?>

<div class="wrap">

    <h2>CGIT Events Settings</h2>

    <form action="options.php" method="post">

        <?php settings_fields('cgit-events'); ?>

        <h3>Calendar formatting &amp; presentation</h3>

        <table class="form-table">

            <tr>
                <th>
                    Days
                </th>
                <td>
                    <label>
                        <select name="cgit_wp_events_format_day">

                        <?php $day = get_option('cgit_wp_events_format_day'); ?>

                        <?php foreach (cgit_wp_events::$options['cgit_wp_events_format_day'] as $key => $value) : ?>
                            <option value="<?=$key?>" <?php if ($day == $key) { echo 'selected'; } ?>><?=$value?></option>
                        <?php endforeach; ?>

                        </select><br />
                        <small>How to format day numbers on the event calendar.</small>
                    </label>
                </td>
            </tr>

            <tr>
                <th>
                    Current month/year
                </th>
                <td>
                    <label>
                        <select name="cgit_wp_events_format_current_month">
                        <?php $month_year = get_option('cgit_wp_events_ormat_current_month'); ?>

                        <?php foreach (cgit_wp_events::$options['cgit_wp_events_format_current_month'] as $key => $value) : ?>
                            <option value="<?=$key?>" <?php if ($month_year == $key) { echo 'selected'; } ?>><?=date($value, strtotime('1st December 2015'))?></option>
                        <?php endforeach; ?>

                        </select>
                        <br /><small>How to format the current month/year.</small>
                    </label>
                </td>
            </tr>

            <tr>
                <th>
                    <label for="cgit_wp_events_format_next_year">Next year button</label>
                </th>
                <td>
                    <input type="text" name="cgit_wp_events_format_next_year" id="cgit_wp_events_format_next_year" value="<?php echo get_option('cgit_wp_events_format_next_year'); ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="cgit_wp_events_format_prev_year">Previous year button</label>
                </th>
                <td>
                    <input type="text" name="cgit_wp_events_format_prev_year" id="cgit_wp_events_format_prev_year" value="<?php echo get_option('cgit_wp_events_format_prev_year'); ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="cgit_wp_events_format_next_month">Next month button</label>
                </th>
                <td>
                    <input type="text" name="cgit_wp_events_format_next_month" id="cgit_wp_events_format_next_month" value="<?php echo get_option('cgit_wp_events_format_next_month'); ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="cgit_wp_events_format_prev_month">Previous month button</label>
                </th>
                <td>
                    <input type="text" name="cgit_wp_events_format_prev_month" id="cgit_wp_events_format_prev_month" value="<?php echo get_option('cgit_wp_events_format_prev_month'); ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="cgit_wp_events_class_prefix">CSS class prefix</label>
                </th>
                <td>
                    <input type="text" name="cgit_wp_events_class_prefix" id="cgit_wp_events_class_prefix" value="<?php echo get_option('cgit_wp_events_class_prefix'); ?>" />
                </td>
            </tr>

        </table>


        <h3>Post type options</h3>

        <table class="form-table">

            <tr>
                <th>
                    <label for="class_prefix">Display events</label>
                </th>
                <td>
                    <select name="cgit_wp_events_post_type_menu_position">

                    <?php $day = get_option('cgit_wp_events_post_type_menu_position'); ?>

                    <?php foreach (cgit_wp_events::$options['cgit_wp_events_post_type_menu_position'] as $key => $value) : ?>
                        <option value="<?=$key?>" <?php if ($day == $key) { echo 'selected'; } ?>><?=$value?></option>
                    <?php endforeach; ?>

                    </select><br />
                </td>
            </tr>

        </table>

        <table class="form-table">

            <tr>
                <th rowspan="10">
                    Enable or disable
                </th>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_category" value="1"<?php echo get_option('cgit_wp_events_post_type_support_category') ? ' checked="checked"' : ''; ?> />
                        Categories
                    </label>
                </td>
            </tr>

            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_tag" value="1"<?php echo get_option('cgit_wp_events_post_type_support_tag') ? ' checked="checked"' : ''; ?> />
                        Tags
                    </label>
                </td>
            </tr>

            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_editor" value="1"<?php echo get_option('cgit_wp_events_post_type_support_editor') ? ' checked="checked"' : ''; ?> />
                        Editor field
                    </label>
                </td>
            </tr>

            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_author" value="1"<?php echo get_option('cgit_wp_events_post_type_support_author') ? ' checked="checked"' : ''; ?> />
                        Author
                    </label>
                </td>
            </tr>

            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_thumbnail" value="1"<?php echo get_option('cgit_wp_events_post_type_support_thumbnail') ? ' checked="checked"' : ''; ?> />
                        Thumbnail
                    </label>
                </td>
            </tr>

            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_comments" value="1"<?php echo get_option('cgit_wp_events_post_type_support_comments') ? ' checked="checked"' : ''; ?> />
                        Comments
                    </label>
                </td>
            </tr>

            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="cgit_wp_events_post_type_support_page-attributes" value="1"<?php echo get_option('cgit_wp_events_post_type_support_page-attributes') ? ' checked="checked"' : ''; ?> />
                        Page attributes
                    </label>
                </td>
            </tr>

        </table>

        <?php submit_button(); ?>

    </form>

</div>

<?php
}