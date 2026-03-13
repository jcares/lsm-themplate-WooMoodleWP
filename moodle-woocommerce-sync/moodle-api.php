<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Llamadas a la API REST de Moodle.
 */

function moodlewc_sync_request($url, $token, $function, $params = array()) {
    $endpoint = untrailingslashit($url) . '/webservice/rest/server.php';
    $default = array(
        'wstoken' => $token,
        'moodlewsrestformat' => 'json',
        'wsfunction' => $function,
    );
    $query = wp_parse_args($params, $default);
    $response = wp_remote_get($endpoint . '?' . http_build_query($query), array('timeout' => 30));

    if (is_wp_error($response)) {
        return array('error' => $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $decoded = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return array('error' => __('Respuesta JSON inválida desde Moodle.', 'moodle-woocommerce-sync'));
    }

    return $decoded;
}

function moodlewc_sync_get_courses($url, $token, $category_ids = array()) {
    if (is_string($category_ids)) {
        $category_ids = array_filter(array_map('trim', explode(',', $category_ids)));
    }

    $params = array();
    if (!empty($category_ids)) {
        $params['options[0][name]'] = 'catid';
        $params['options[0][value]'] = implode(',', array_map('intval', $category_ids));
    }

    $courses = moodlewc_sync_request($url, $token, 'core_course_get_courses', $params);
    if (isset($courses['error'])) {
        return array();
    }

    // Agregar datos faltantes
    $result = array();
    foreach ($courses as $course) {
        if (empty($course['id'])) {
            continue;
        }
        $course = wp_parse_args($course, array(
            'summary' => '',
            'category' => '',
            'fullname' => '',
            'id' => 0,
            'summaryfiles' => array(),
            'categoryname' => '',
            'price' => 0,
        ));

        // Extraer imagen desde summaryfiles si existe
        $image = '';
        if (!empty($course['summaryfiles']) && is_array($course['summaryfiles'])) {
            foreach ($course['summaryfiles'] as $file) {
                if (!empty($file['fileurl'])) {
                    $image = $file['fileurl'];
                    break;
                }
            }
        }

        $result[] = array(
            'id' => intval($course['id']),
            'fullname' => sanitize_text_field($course['fullname']),
            'summary' => wp_kses_post($course['summary']),
            'categoryid' => intval($course['category']),
            'categoryname' => isset($course['categoryname']) ? sanitize_text_field($course['categoryname']) : '',
            'image' => esc_url_raw($image),
            'price' => isset($course['price']) ? floatval($course['price']) : 0,
            'moodle_link' => untrailingslashit($url) . '/course/view.php?id=' . intval($course['id']),
        );
    }

    return $result;
}
