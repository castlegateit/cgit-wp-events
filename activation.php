<?php

/**
 * Plugin activation hook to check that required plugins are installed and to
 * flush rewrite rules so custom rules will take effect.
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_activate() {

    /**
     * Check if custom-meta-boxes plugin is installed
     */
    if (!cgit_wp_events_check_cmb()) {

        $message = 'This plugin requires the <code>custom meta boxes</code> ';
        $message.= ' plugin. Please ensure you\'ve installed ';
        $message.= 'it in the correct location: <code>';
        $message.= 'plugins/custom-meta-boxes</code><br /><br />When ';
        $message.= 'download via GitHub\'s web interface, the installation ';
        $message.= 'directory may be <code>plugins/Custom-Meta-Boxes-master';
        $message.=  '</code>. Be sure to remove <code>master</code> from the ';
        $message.= 'directory and convert to lower case.<br /><br />Download ';
        $message.= 'from <a target="_blank" href="https://github.com/humanmade';
        $message.= '/Custom-Meta-Boxes">GitHub</a>';
        wp_die($message);
    }

    /**
     * Check if cgit-wp-cmb-ukdate is installed
     */
    if (!cgit_wp_events_check_cgit_wp_cmb_uk_date()) {

        $message = 'This plugin requires the <code>cgit-cmb-ukdate</code> ';
        $message.= ' plugin. Please ensure you\'ve installed ';
        $message.= 'it in the correct location: <code>';
        $message.= 'plugins/cgit-wp-cmb-ukdate</code><br /><br />When ';
        $message.= 'download via GitHub\'s web interface, the installation ';
        $message.= 'directory may be <code>plugins/cgit-wp-cmb-ukdate-master';
        $message.=  '</code>. Be sure to remove <code>master</code> from the ';
        $message.= 'directory.<br /><br />Download ';
        $message.= 'from <a target="_blank" href="https://github.com/';
        $message.= 'castlegateit/cgit-wp-cmb-ukdate">GitHub</a>';
        wp_die($message);
    }

    // Set default options
    cgit_wp_events_default_options();

    // Flush rewrite rules
    add_filter('wp_loaded', 'cgit_wp_events_flush_rules');
}


/**
 * Checks that custom-meta-boxes is installed
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return boolean
 */
function cgit_wp_events_check_cmb() {
    return is_plugin_active('custom-meta-boxes/custom-meta-boxes.php');
}


/**
 * Checks that cgit-wp-cmb-ukdate plugin is installed
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return boolean
 */
function cgit_wp_events_check_cgit_wp_cmb_uk_date() {
    return is_plugin_active('cgit-wp-cmb-ukdate/cgit-wp-cmb-ukdate.php');
}


/**
 * Configure warning notices on the admin screens if one or more required
 * plugins have been deactivated or deleted.
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_check_installed_plugims() {

    global $default_options;

    // Only display warnings to those who can manage plugins
    if (current_user_can('install_plugins')) {

        // If custom-meta-boxes is no longer installed...
        if (!cgit_wp_events_check_cmb()) {

            function cgit_wp_events_admin_notice_cmb() {

                echo '<div class="error">';
                echo '    <p>CGIT Event requires <a target="_blank" ';
                echo 'href="https://github.com/humanmade/Custom-Meta-Boxes">';
                echo 'Custom Meta Boxes</a> to be installed and will not ';
                echo 'function correctly without it.</p>';
                echo '</div>';
            }
            add_action('admin_notices', 'cgit_wp_events_admin_notice_cmb');
        }

        // if cgit-wp-cmb-ukdate is no longer installed...
        if (!cgit_wp_events_check_cgit_wp_cmb_uk_date()) {

            function cgit_wp_events_admin_notice_ukdate() {

                echo '<div class="error">';
                echo '    <p>CGIT Event requires <a target="_blank" ';
                echo 'href="https://github.com/castlegateit/cgit-wp-cmb-ukdate">';
                echo 'cgit-cmb-ukdate</a> to be installed and will not ';
                echo 'function correctly without it.</p>';
                echo '</div>';
            }
            add_action('admin_notices', 'cgit_wp_events_admin_notice_ukdate');
        }
    }

}
add_action('admin_init', 'cgit_wp_events_check_installed_plugims');


/**
 * Assign the default option values
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_default_options() {

    // Set default options
    foreach (cgit_wp_events::$options as $option => $value) {

        if (empty(get_option($option))) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    update_option($option, $v);
                    break;
                }
            } else {
                update_option($option, $value);
            }
        }

    }

}


/**
 * Remove all options
 *
 * @author Castlgate IT <info@castlegateit.co.uk>
 * @author Andy Reading
 *
 * @return void
 */
function cgit_wp_events_uninstall() {

    global $default_options;

    // Delete saved options
    foreach ($default_options as $option => $value) {
        delete_option($option);
    }

    // Flush rewrite rules
    cgit_wp_events_flush_rules();

}