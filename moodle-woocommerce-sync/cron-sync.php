<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cron y sincronización periódica.
 */

function moodlewc_sync_cron_schedules($schedules) {
    if (!isset($schedules['every_fifteen_minutes'])) {
        $schedules['every_fifteen_minutes'] = array(
            'interval' => 900,
            'display' => __('Cada 15 minutos', 'moodle-woocommerce-sync'),
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'moodlewc_sync_cron_schedules');

// Ya se añade la acción en el archivo principal moodle-woocommerce-sync.php

function moodlewc_sync_manual_trigger() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!empty($_GET['moodlewc_manual_sync']) && check_admin_referer('moodlewc_manual_sync_action', 'moodlewc_manual_sync_nonce')) {
        moodlewc_sync_auto_update();
        wp_redirect(remove_query_arg(array('moodlewc_manual_sync', 'moodlewc_manual_sync_nonce')));
        exit;
    }
}
add_action('admin_init', 'moodlewc_sync_manual_trigger');
