<?php
if (!defined('ABSPATH')) {
    exit;
}

function moodlewc_sync_admin_page() {
    $options = get_option('moodlewc_sync_options', array());
    $moodle_url = isset($options['moodle_url']) ? esc_url($options['moodle_url']) : '';
    $moodle_token = isset($options['moodle_token']) ? sanitize_text_field($options['moodle_token']) : '';
    $categories = isset($options['moodle_categories']) ? array_map('sanitize_text_field', (array)$options['moodle_categories']) : array();

    $detected_courses = array();
    if (!empty($moodle_url) && !empty($moodle_token)) {
        $detected_courses = moodlewc_sync_get_courses($moodle_url, $moodle_token, $categories);
    }

    if (isset($_POST['moodlewc_import']) && check_admin_referer('moodlewc_import_action', 'moodlewc_import_nonce')) {
        $selected_courses = isset($_POST['selected_courses']) ? (array) $_POST['selected_courses'] : array();
        if (!empty($selected_courses)) {
            $courses_to_import = array();
            foreach ($detected_courses as $course) {
                if (in_array($course['id'], $selected_courses)) {
                    $courses_to_import[] = $course;
                }
            }
            moodlewc_sync_import_courses($courses_to_import);
            add_settings_error('moodlewc_sync_messages', 'moodlewc_sync_message', __('Importación completa.', 'moodle-woocommerce-sync'), 'updated');
        }
    }

    settings_errors('moodlewc_sync_messages');

    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Sincronizar Moodle con WooCommerce', 'moodle-woocommerce-sync'); ?></h1>

        <form method="post" action="options.php">
            <?php settings_fields('moodlewc_sync_options_group'); ?>
            <?php do_settings_sections('moodlewc_sync_options_group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('URL Moodle', 'moodle-woocommerce-sync'); ?></th>
                    <td><input type="url" name="moodlewc_sync_options[moodle_url]" value="<?php echo esc_attr($moodle_url); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Token de API', 'moodle-woocommerce-sync'); ?></th>
                    <td><input type="text" name="moodlewc_sync_options[moodle_token]" value="<?php echo esc_attr($moodle_token); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Categorías de Moodle (ID)', 'moodle-woocommerce-sync'); ?></th>
                    <td><input type="text" name="moodlewc_sync_options[moodle_categories][]" value="<?php echo esc_attr(implode(',', $categories)); ?>" class="regular-text" placeholder="1,2,3" /> <p class="description"><?php esc_html_e('Separar IDs por coma para filtrar categorías.', 'moodle-woocommerce-sync'); ?></p></td>
                </tr>
            </table>

            <?php submit_button(__('Guardar configuración', 'moodle-woocommerce-sync')); ?>
        </form>

        <h2><?php esc_html_e('Cursos detectados en Moodle', 'moodle-woocommerce-sync'); ?></h2>
        <form method="post">
            <?php wp_nonce_field('moodlewc_import_action', 'moodlewc_import_nonce'); ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Importar', 'moodle-woocommerce-sync'); ?></th>
                        <th><?php esc_html_e('ID', 'moodle-woocommerce-sync'); ?></th>
                        <th><?php esc_html_e('Nombre', 'moodle-woocommerce-sync'); ?></th>
                        <th><?php esc_html_e('Categoría', 'moodle-woocommerce-sync'); ?></th>
                        <th><?php esc_html_e('Precio', 'moodle-woocommerce-sync'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($detected_courses) && is_array($detected_courses)): ?>
                        <?php foreach ($detected_courses as $course): ?>
                            <tr>
                                <td><input type="checkbox" name="selected_courses[]" value="<?php echo intval($course['id']); ?>"></td>
                                <td><?php echo intval($course['id']); ?></td>
                                <td><?php echo esc_html($course['fullname']); ?></td>
                                <td><?php echo esc_html($course['categoryname']); ?></td>
                                <td><?php echo esc_html(!empty($course['price']) ? $course['price'] : '0'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5"><?php esc_html_e('No se han detectado cursos. Verifique URL y token.', 'moodle-woocommerce-sync'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php submit_button(__('Importar cursos seleccionados', 'moodle-woocommerce-sync')); ?>
        </form>
    </div>
    <?php
}
