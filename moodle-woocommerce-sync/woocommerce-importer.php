<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Convierte cursos de Moodle en productos WooCommerce.
 */

function moodlewc_sync_import_courses($courses) {
    if (!class_exists('WooCommerce')) {
        return;
    }

    foreach ($courses as $course) {
        $external_id = 'moodle-course-' . intval($course['id']);

        $existing = get_posts(array(
            'post_type' => 'product',
            'meta_key' => '_moodle_course_id',
            'meta_value' => $course['id'],
            'posts_per_page' => 1,
            'fields' => 'ids',
        ));

        $product_data = array(
            'post_title' => wp_strip_all_tags($course['fullname']),
            'post_content' => wp_kses_post($course['summary']),
            'post_status' => 'publish',
            'post_type' => 'product',
        );

        if (!empty($existing)) {
            $product_id = $existing[0];
            wp_update_post(array_merge(array('ID' => $product_id), $product_data));
        } else {
            $product_id = wp_insert_post($product_data);
        }

        if (!$product_id || is_wp_error($product_id)) {
            continue;
        }

        // Datos estándar WooCommerce
        update_post_meta($product_id, '_moodle_course_id', intval($course['id']));
        update_post_meta($product_id, '_price', floatval($course['price']));
        update_post_meta($product_id, '_regular_price', floatval($course['price']));
        update_post_meta($product_id, '_stock_status', 'instock');
        update_post_meta($product_id, '_visibility', 'visible');
        update_post_meta($product_id, '_downloadable', 'no');
        update_post_meta($product_id, '_virtual', 'yes');
        update_post_meta($product_id, '_moodle_course_link', esc_url_raw($course['moodle_link']));

        // Taxonomía product_cat y etiquetas
        if (!empty($course['categoryname'])) {
            $cat = term_exists(sanitize_text_field($course['categoryname']), 'product_cat');
            if (!$cat) {
                $cat = wp_insert_term(sanitize_text_field($course['categoryname']), 'product_cat');
            }
            if (!is_wp_error($cat) && !empty($cat['term_id'])) {
                wp_set_object_terms($product_id, intval($cat['term_id']), 'product_cat');
            }
        }

        // IMG destacado de curso
        if (!empty($course['image'])) {
            moodlewc_sync_set_featured_image($course['image'], $product_id);
        }

        // Añadir a la descripción un link de acceso Moodle
        $content = get_post_field('post_content', $product_id);
        $content .= "<p><strong>Acceso Moodle:</strong> <a href='" . esc_url($course['moodle_link']) . "' target='_blank'>Entrar al curso en Moodle</a></p>";
        wp_update_post(array('ID' => $product_id, 'post_content' => $content));
    }
}

function moodlewc_sync_set_featured_image($image_url, $product_id) {
    if (empty($image_url) || !filter_var($image_url, FILTER_VALIDATE_URL)) {
        return;
    }

    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) {
        return;
    }

    $file = array(
        'name' => basename($image_url),
        'type' => mime_content_type($tmp),
        'tmp_name' => $tmp,
        'error' => 0,
        'size' => filesize($tmp),
    );

    $sideload = wp_handle_sideload($file, array('test_form' => false));
    if (isset($sideload['error'])) {
        @unlink($tmp);
        return;
    }

    $attachment = array(
        'post_mime_type' => $sideload['type'],
        'post_title' => sanitize_file_name($sideload['file']),
        'post_content' => '',
        'post_status' => 'inherit',
    );

    $attach_id = wp_insert_attachment($attachment, $sideload['file'], $product_id);
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $sideload['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);
    set_post_thumbnail($product_id, $attach_id);
}
