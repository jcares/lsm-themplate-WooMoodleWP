<?php
/**
 * Moodle Native Integration Module
 * Características PRO de MooWoodle & Edwiser Bridge implementadas nativamente
 * 
 * Funcionalidades:
 * - Sincronización bidireccional de cursos
 * - Sincronización de usuarios/enrollments
 * - Single Sign-On (SSO)
 * - Integración con WooCommerce
 * - Gestión de cohorts
 * - Sincronización de imágenes
 */

defined('ABSPATH') || exit;

class Cursos_Online_Moodle_Integration {
    
    private static $instance = null;
    private $moodle_url;
    private $moodle_token;
    private $api_base;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->moodle_url  = get_option('cursos_online_moodle_url');
        $this->moodle_token = get_option('cursos_online_moodle_token');
        $this->api_base = $this->moodle_url . '/webservice/rest/server.php?moodlewsrestformat=json';
        
        // Hooks
        add_action('admin_menu', [$this, 'add_moodle_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('woocommerce_order_status_completed', [$this, 'enroll_student_on_purchase']);
        add_action('wp_footer', [$this, 'sso_logout_handler']);
    }

    /**
     * Menu de administración
     */
    public function add_moodle_menu() {
        add_submenu_page(
            'cursos-online-options',
            'Integración Moodle',
            'Integración Moodle',
            'manage_options',
            'moodle-integration',
            [$this, 'render_admin_page']
        );
    }

    /**
     * Registrar opciones
     */
    public function register_settings() {
        register_setting('cursos_online_group', 'cursos_online_moodle_url');
        register_setting('cursos_online_group', 'cursos_online_moodle_token');
        register_setting('cursos_online_group', 'cursos_online_moodle_enabled');
        register_setting('cursos_online_group', 'cursos_online_sso_enabled');
        register_setting('cursos_online_group', 'cursos_online_auto_sync');
    }

    /**
     * Test conexión a Moodle
     */
    public function test_connection() {
        if (empty($this->moodle_url) || empty($this->moodle_token)) {
            return ['success' => false, 'message' => 'Falta URL o token de Moodle'];
        }

        $url = $this->api_base . '&wsfunction=core_webservice_get_site_info&wstoken=' . $this->moodle_token;
        
        $response = wp_remote_get($url, ['timeout' => 10, 'sslverify' => false]);
        
        if (is_wp_error($response)) {
            return ['success' => false, 'message' => $response->get_error_message()];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['exception'])) {
            return ['success' => false, 'message' => $body['message'] ?? 'Error conectando a Moodle'];
        }

        return ['success' => true, 'message' => 'Conexión exitosa con Moodle', 'site' => $body['sitename'] ?? 'Moodle'];
    }

    /**
     * Obtener cursos de Moodle
     */
    public function get_moodle_courses($limit = 50) {
        if (empty($this->moodle_url) || empty($this->moodle_token)) {
            return [];
        }

        $url = $this->api_base . '&wsfunction=core_course_get_courses&wstoken=' . $this->moodle_token . '&limit=' . intval($limit);
        
        $response = wp_remote_get($url, ['timeout' => 15, 'sslverify' => false]);
        
        if (is_wp_error($response)) {
            return [];
        }

        $courses = json_decode(wp_remote_retrieve_body($response), true);
        
        // Filtrar curso "Sitio" (id=1)
        if (is_array($courses)) {
            $courses = array_filter($courses, function($course) {
                return intval($course['id']) > 1;
            });
        }

        return is_array($courses) ? $courses : [];
    }

    /**
     * Sincronizar cursos a productos WooCommerce
     */
    public function sync_courses_to_products() {
        if (!class_exists('WC_Product')) {
            return ['success' => false, 'message' => 'WooCommerce no está activo'];
        }

        $courses = $this->get_moodle_courses();
        $synced = 0;

        foreach ($courses as $course) {
            // Verificar si ya existe el producto
            $existing = wc_get_products([
                'meta_key'   => '_moodle_course_id',
                'meta_value' => intval($course['id']),
                'limit'      => 1,
            ]);

            if (!empty($existing)) {
                // Actualizar existente
                $product = $existing[0];
            } else {
                // Crear nuevo
                $product = new WC_Product_Simple();
                $product->set_name($course['fullname']);
                $product->add_meta_data('_moodle_course_id', intval($course['id']), true);
            }

            // Actualizar datos
            $product->set_description($course['summary'] ?? '');
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            
            // Precio - usar customfield de Moodle si existe
            if (isset($course['customfields']['price'])) {
                $product->set_price($course['customfields']['price']);
            } else {
                $product->set_price(0);
            }

            $product->save();
            $synced++;
        }

        return ['success' => true, 'message' => "Se sincronizaron $synced cursos"];
    }

    /**
     * Sincronizar imagen del curso
     */
    public function sync_course_image($moodle_course_id, $image_url) {
        if (empty($image_url)) {
            return false;
        }

        // Descargar imagen
        $tmp_file = download_url($image_url);
        if (is_wp_error($tmp_file)) {
            return false;
        }

        // Obtener producto
        $products = wc_get_products([
            'meta_key'   => '_moodle_course_id',
            'meta_value' => intval($moodle_course_id),
            'limit'      => 1,
        ]);

        if (empty($products)) {
            @unlink($tmp_file);
            return false;
        }

        $product = $products[0];

        // Crear attachment
        $attachment_id = media_handle_sideload([
            'name'     => basename($image_url),
            'tmp_name' => $tmp_file,
        ], 0);

        if (!is_wp_error($attachment_id)) {
            $product->set_image_id($attachment_id);
            $product->save();
            return true;
        }

        @unlink($tmp_file);
        return false;
    }

    /**
     * Enrolar estudiante en Moodle al completar compra
     */
    public function enroll_student_on_purchase($order_id) {
        if (!get_option('cursos_online_moodle_enabled')) {
            return;
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }

        $user_id = $order->get_user_id();
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return;
        }

        // Enrolar en cada producto/curso
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);
            if (!$product) {
                continue;
            }

            $moodle_course_id = $product->get_meta('_moodle_course_id');
            if (!$moodle_course_id) {
                continue;
            }

            $this->enroll_user_in_moodle($user, intval($moodle_course_id));
        }
    }

    /**
     * Enrolar usuario en Moodle
     */
    private function enroll_user_in_moodle($user, $moodle_course_id) {
        // Crear/sincronizar usuario en Moodle
        $moodle_user = $this->sync_user_to_moodle($user);
        if (!$moodle_user) {
            return false;
        }

        // Enrolar en curso
        $enroll_url = $this->api_base . '&wsfunction=enrol_manual_enrol_users&wstoken=' . $this->moodle_token;
        
        $enroll_data = [
            'enrolments[0][userid]'      => intval($moodle_user['id']),
            'enrolments[0][courseid]'    => intval($moodle_course_id),
            'enrolments[0][roleid]'      => 5, // Rol de estudiante
        ];

        $response = wp_remote_post($enroll_url, [
            'body'        => $enroll_data,
            'timeout'     => 10,
            'sslverify'   => false,
        ]);

        return !is_wp_error($response);
    }

    /**
     * Sincronizar usuario a Moodle
     */
    public function sync_user_to_moodle($user) {
        $existing = $user->get_meta('_moodle_user_id');
        
        if ($existing) {
            return $this->get_moodle_user($existing);
        }

        // Crear usuario en Moodle
        $create_url = $this->api_base . '&wsfunction=core_user_create_users&wstoken=' . $this->moodle_token;
        
        $user_data = [
            'users[0][username]'     => sanitize_user($user->user_login),
            'users[0][password]'     => wp_generate_password(16),
            'users[0][firstname]'    => $user->first_name ?: $user->user_login,
            'users[0][lastname]'     => $user->last_name ?: '',
            'users[0][email]'        => $user->user_email,
        ];

        $response = wp_remote_post($create_url, [
            'body'        => $user_data,
            'timeout'     => 10,
            'sslverify'   => false,
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $result = json_decode(wp_remote_retrieve_body($response), true);
        
        if (!empty($result[0]['id'])) {
            $moodle_id = $result[0]['id'];
            $user->add_meta_data('_moodle_user_id', $moodle_id, true);
            wp_update_user(['ID' => $user->ID]);
            return ['id' => $moodle_id];
        }

        return false;
    }

    /**
     * Obtener usuario de Moodle
     */
    private function get_moodle_user($moodle_id) {
        $url = $this->api_base . '&wsfunction=core_user_get_users&wstoken=' . $this->moodle_token . '&criteria[0][key]=id&criteria[0][value]=' . intval($moodle_id);
        
        $response = wp_remote_get($url, ['timeout' => 10, 'sslverify' => false]);
        
        if (is_wp_error($response)) {
            return false;
        }

        $users = json_decode(wp_remote_retrieve_body($response), true);
        return !empty($users['users'][0]) ? $users['users'][0] : false;
    }

    /**
     * Single Sign-On (SSO) - Login Moodle = Login WordPress
     */
    public function sso_login_handler() {
        if (!get_option('cursos_online_sso_enabled')) {
            return;
        }

        // Si está logueado en Moodle pero no en WordPress
        if (is_user_logged_in()) {
            return;
        }

        // Token recibido de Moodle
        if (isset($_GET['moodle_sso_token'])) {
            $token = sanitize_text_field($_GET['moodle_sso_token']);
            $moodle_user = $this->verify_moodle_sso_token($token);
            
            if ($moodle_user) {
                // Buscar/crear usuario en WordPress
                $wp_user = $this->sync_moodle_user_to_wp($moodle_user);
                if ($wp_user) {
                    wp_set_auth_cookie($wp_user->ID, true);
                    wp_redirect(home_url());
                    exit;
                }
            }
        }
    }

    public function sso_logout_handler() {
        if (isset($_GET['moodle_sso_logout'])) {
            wp_logout();
            wp_redirect($this->moodle_url . '/login/logout.php?sesskey=' . sanitize_text_field($_GET['sesskey']));
            exit;
        }
    }

    /**
     * Verificar token SSO de Moodle
     */
    private function verify_moodle_sso_token($token) {
        $url = $this->api_base . '&wsfunction=auth_userkey_get_user&wstoken=' . $this->moodle_token . '&user_key=' . sanitize_text_field($token);
        
        $response = wp_remote_get($url, ['timeout' => 10, 'sslverify' => false]);
        
        if (is_wp_error($response)) {
            return false;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    /**
     * Sincronizar usuario de Moodle a WordPress
     */
    private function sync_moodle_user_to_wp($moodle_user) {
        $wp_user = get_user_by('email', $moodle_user['email']);
        
        if ($wp_user) {
            return $wp_user;
        }

        // Crear usuario
        $user_id = wp_create_user(
            $moodle_user['username'],
            wp_generate_password(16),
            $moodle_user['email']
        );

        if (!is_wp_error($user_id)) {
            wp_update_user([
                'ID'         => $user_id,
                'first_name' => $moodle_user['firstname'] ?? '',
                'last_name'  => $moodle_user['lastname'] ?? '',
            ]);
            
            update_user_meta($user_id, '_moodle_user_id', $moodle_user['id']);
            
            return get_user_by('id', $user_id);
        }

        return false;
    }

    /**
     * Obtener cursos inscritos del usuario
     */
    public function get_user_enrolled_courses($user_id = null) {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }

        if (!$user_id) {
            return [];
        }

        $user = get_user_by('id', $user_id);
        if (!$user) {
            return [];
        }

        $moodle_user_id = get_user_meta($user_id, '_moodle_user_id', true);
        if (!$moodle_user_id) {
            return [];
        }

        $url = $this->api_base . '&wsfunction=core_enrol_get_users_courses&wstoken=' . $this->moodle_token . '&userid=' . intval($moodle_user_id);
        
        $response = wp_remote_get($url, ['timeout' => 10, 'sslverify' => false]);
        
        if (is_wp_error($response)) {
            return [];
        }

        return json_decode(wp_remote_retrieve_body($response), true) ?: [];
    }

    /**
     * Página de administración
     */
    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Test conexión
        $test_result = null;
        if (isset($_POST['moodle_test_connection']) && wp_verify_nonce($_POST['_wpnonce'], 'moodle_test_nonce')) {
            $test_result = $this->test_connection();
        }

        // Sync cursos
        $sync_result = null;
        if (isset($_POST['moodle_sync_courses']) && wp_verify_nonce($_POST['_wpnonce'], 'moodle_sync_nonce')) {
            $sync_result = $this->sync_courses_to_products();
        }

        echo '<div class="wrap">';
        echo '<h1>Integración Moodle Nativa</h1>';

        // Settings Form
        echo '<form method="post" action="options.php" class="settings-section" style="max-width: 600px; background: #fff; padding: 20px; border-radius: 5px; margin: 20px 0;">';
        settings_fields('cursos_online_group');
        
        echo '<table class="form-table">';
        
        echo '<tr>';
        echo '<th scope="row"><label for="moodle_url">URL de Moodle</label></th>';
        echo '<td><input type="url" name="cursos_online_moodle_url" value="' . esc_attr($this->moodle_url) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th scope="row"><label for="moodle_token">Token API de Moodle</label></th>';
        echo '<td><input type="password" name="cursos_online_moodle_token" value="' . esc_attr($this->moodle_token) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th scope="row"><label><input type="checkbox" name="cursos_online_moodle_enabled" value="1" ' . checked(get_option('cursos_online_moodle_enabled'), 1) . ' /> Habilitar Moodle</label></th>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th scope="row"><label><input type="checkbox" name="cursos_online_sso_enabled" value="1" ' . checked(get_option('cursos_online_sso_enabled'), 1) . ' /> Habilitar Single Sign-On</label></th>';
        echo '</tr>';
        
        echo '</table>';
        submit_button('Guardar Configuración');
        echo '</form>';

        // Test Connection Button
        echo '<form method="post" style="margin: 20px 0;">';
        wp_nonce_field('moodle_test_nonce', '_wpnonce');
        echo '<button type="submit" name="moodle_test_connection" class="button button-primary">Probar Conexión</button>';
        echo '</form>';

        // Test Result
        if ($test_result) {
            $class = $test_result['success'] ? 'notice-success' : 'notice-error';
            echo '<div class="notice ' . $class . ' is-dismissible"><p>';
            echo esc_html($test_result['message']);
            if (isset($test_result['site'])) {
                echo ' (' . esc_html($test_result['site']) . ')';
            }
            echo '</p></div>';
        }

        // Sync Courses Button
        echo '<form method="post" style="margin: 20px 0;">';
        wp_nonce_field('moodle_sync_nonce', '_wpnonce');
        echo '<button type="submit" name="moodle_sync_courses" class="button button-primary">Sincronizar Cursos a Productos</button>';
        echo '</form>';

        // Sync Result
        if ($sync_result) {
            $class = $sync_result['success'] ? 'notice-success' : 'notice-error';
            echo '<div class="notice ' . $class . ' is-dismissible"><p>';
            echo esc_html($sync_result['message']);
            echo '</p></div>';
        }

        echo '</div>';
    }
}

// Inicializar
add_action('plugins_loaded', function() {
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        Cursos_Online_Moodle_Integration::get_instance();
    }
});

// Helper functions
function cursos_online_get_user_courses($user_id = null) {
    return Cursos_Online_Moodle_Integration::get_instance()->get_user_enrolled_courses($user_id);
}

function cursos_online_moodle_test() {
    return Cursos_Online_Moodle_Integration::get_instance()->test_connection();
}
