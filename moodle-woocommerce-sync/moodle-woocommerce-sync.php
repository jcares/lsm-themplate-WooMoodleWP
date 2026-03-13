
<?php
/**
 * Plugin Name: Moodle WooCommerce Sync
 * Plugin URI:  https://pccurico.cl
 * Description: Sincroniza cursos Moodle con productos WooCommerce (by JCares, PCCurico.cl).
 * Version:     1.0.0
 * Author:      JCares - PCCurico.cl
 * Author URI:  https://pccurico.cl
 * Text Domain: moodle-woocommerce-sync
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MOODLE_WC_SYNC_VERSION', '1.0.0');
define('MOODLE_WC_SYNC_PATH', plugin_dir_path(__FILE__));
define('MOODLE_WC_SYNC_URL', plugin_dir_url(__FILE__));

require_once MOODLE_WC_SYNC_PATH . 'admin-page.php';
require_once MOODLE_WC_SYNC_PATH . 'moodle-api.php';
require_once MOODLE_WC_SYNC_PATH . 'woocommerce-importer.php';
require_once MOODLE_WC_SYNC_PATH . 'cron-sync.php';

register_activation_hook(__FILE__, 'moodlewc_sync_activate');
register_deactivation_hook(__FILE__, 'moodlewc_sync_deactivate');

function moodlewc_sync_activate() {
    if (!wp_next_scheduled('moodlewc_sync_hourly_event')) {
        wp_schedule_event(time(), 'hourly', 'moodlewc_sync_hourly_event');
    }
}

function moodlewc_sync_deactivate() {
    wp_clear_scheduled_hook('moodlewc_sync_hourly_event');
}

add_action('admin_menu', 'moodlewc_sync_admin_menu');
add_action('admin_init', 'moodlewc_sync_register_settings');
add_action('moodlewc_sync_hourly_event', 'moodlewc_sync_auto_update');

function moodlewc_sync_auto_update() {
    $options = get_option('moodlewc_sync_options', array());
    $url = !empty($options['moodle_url']) ? esc_url_raw($options['moodle_url']) : '';
    $token = !empty($options['moodle_token']) ? sanitize_text_field($options['moodle_token']) : '';
    $categories = !empty($options['moodle_categories']) ? array_map('sanitize_text_field', $options['moodle_categories']) : array();

    if (empty($url) || empty($token)) {
        return;
    }

    $courses = moodlewc_sync_get_courses($url, $token, $categories);

    if (!empty($courses) && is_array($courses)) {
        moodlewc_sync_import_courses($courses);
    }
}

function moodlewc_sync_admin_menu() {
    add_menu_page(
        __('Moodle -> WooCommerce', 'moodle-woocommerce-sync'),
        __('Moodle Sync', 'moodle-woocommerce-sync'),
        'manage_options',
        'moodlewc-sync',
        'moodlewc_sync_admin_page',
        'dashicons-update',
        58
    );
}

function moodlewc_sync_register_settings() {
    register_setting('moodlewc_sync_options_group', 'moodlewc_sync_options');
}
