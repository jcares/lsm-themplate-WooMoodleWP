<?php
/**
 * Curso Online WP Theme - functions.php
 * Tema multiuso optimizado para WooCommerce, Elementor, Moodle y cursos online.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Carga modular de archivos de inc/
if (file_exists(get_template_directory() . '/inc/setup.php')) {
    require_once get_template_directory() . '/inc/setup.php';
}

// Integración nativa con Moodle (reemplaza plugins MooWoodle & Edwiser Bridge)
if (file_exists(get_template_directory() . '/inc/moodle-integration.php')) {
    require_once get_template_directory() . '/inc/moodle-integration.php';
}

function cursos_online_setup() {
    // Soporte básico
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    // WooCommerce y productos
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Soporte para Elementor
    add_theme_support('elementor');
    add_theme_support('elementor-pro');

    // Soporte para Gutenberg
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');

    // Menús
    register_nav_menus(array(
        'menu-principal' => __('Menú Principal', 'cursos-online-wp'),
        'menu-footer' => __('Menú Pie de Página', 'cursos-online-wp'),
    ));
}
add_action('after_setup_theme', 'cursos_online_setup');

function cursos_online_widgets_init() {
    register_sidebar(array(
        'name' => __('Áreas Widget Header', 'cursos-online-wp'),
        'id' => 'header-sidebar',
        'description' => __('Widget en header para Elementor / shortcodes.', 'cursos-online-wp'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Área Footer', 'cursos-online-wp'),
        'id' => 'footer-sidebar',
        'description' => __('Widgets en footer del tema.', 'cursos-online-wp'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'cursos_online_widgets_init');

function cursos_online_assets() {
    wp_enqueue_style('cursos-online-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    wp_enqueue_style('cursos-online-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap', array(), null);

    $main_js_file = get_template_directory() . '/js/main.js';
    if (file_exists($main_js_file)) {
        wp_enqueue_script('cursos-online-main', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0', true);
    } else {
        // Evita error 404 si main.js falta accidentalmente en el tema activo.
        error_log('cursos_online_assets: js/main.js no encontrado en el tema activo.');
    }
}
add_action('wp_enqueue_scripts', 'cursos_online_assets');

// Texto de botón WooCommerce personalizado para cursos
function cursos_online_boton_compra_texto() {
    return __('Comprar Curso', 'cursos-online-wp');
}
add_filter('woocommerce_product_add_to_cart_text', 'cursos_online_boton_compra_texto');
add_filter('woocommerce_product_single_add_to_cart_text', 'cursos_online_boton_compra_texto');

// Opciones de tema para Elementor y customizer
function cursos_online_customize_register($wp_customize) {
    $wp_customize->add_section('cursos_online_section', array(
        'title' => __('Configuración de Cursos Online', 'cursos-online-wp'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('cursos_online_hero_text', array(
        'default' => __('Aprende en línea con los mejores cursos', 'cursos-online-wp'),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('cursos_online_hero_text', array(
        'label' => __('Texto Hero', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'text',
    ));

    $social_options = array(
        'facebook' => __('Facebook', 'cursos-online-wp'),
        'instagram' => __('Instagram', 'cursos-online-wp'),
        'twitter' => __('Twitter', 'cursos-online-wp'),
        'linkedin' => __('LinkedIn', 'cursos-online-wp'),
        'whatsapp' => __('WhatsApp (número internacional sin +)', 'cursos-online-wp'),
    );

    foreach ($social_options as $slug => $label) {
        $setting_id = 'cursos_online_' . $slug . '_url';
        $default = '';
        $sanitize = 'esc_url_raw';

        if ($slug === 'whatsapp') {
            $setting_id = 'cursos_online_whatsapp_phone';
            $sanitize = 'sanitize_text_field';
            $default = '';
        }

        $wp_customize->add_setting($setting_id, array(
            'default' => $default,
            'sanitize_callback' => $sanitize,
        ));

        $wp_customize->add_control($setting_id, array(
            'label' => $label,
            'section' => 'cursos_online_section',
            'type' => 'text',
        ));
    }

    // WhatsApp flotante configurado por el usuario
    $wp_customize->add_setting('cursos_online_whatsapp_message', array(
        'default' => __('¡Hola! Estoy interesado en sus cursos.', 'cursos-online-wp'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('cursos_online_whatsapp_message', array(
        'label' => __('Texto bienvenida WhatsApp', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('cursos_online_whatsapp_button_text', array(
        'default' => __('Chatear por WhatsApp', 'cursos-online-wp'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('cursos_online_whatsapp_button_text', array(
        'label' => __('Texto botón WhatsApp', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting('cursos_online_whatsapp_button_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'cursos_online_whatsapp_button_image', array(
        'label' => __('Imagen botón WhatsApp (flotante)', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'settings' => 'cursos_online_whatsapp_button_image',
    )));

    // Opciones de tema base
    $wp_customize->add_setting('cursos_online_header_bg', array(
        'default' => '#10213F',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cursos_online_header_bg', array(
        'label' => __('Color fondo header', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'settings' => 'cursos_online_header_bg',
    )));

    $wp_customize->add_setting('cursos_online_nav_color', array(
        'default' => '#dfe9ff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'cursos_online_nav_color', array(
        'label' => __('Color texto navegación', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'settings' => 'cursos_online_nav_color',
    )));

    $wp_customize->add_setting('cursos_online_courses_columns', array(
        'default' => 3,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('cursos_online_courses_columns', array(
        'label' => __('Cursos por columna', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 6,
        ),
    ));

    $wp_customize->add_setting('cursos_online_course_button_text', array(
        'default' => __('Comprar Curso', 'cursos-online-wp'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('cursos_online_course_button_text', array(
        'label' => __('Texto botón del curso', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting('cursos_online_course_show_description', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('cursos_online_course_show_description', array(
        'label' => __('Mostrar descripción del curso', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'checkbox',
    ));

    $wp_customize->add_setting('cursos_online_course_show_price', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('cursos_online_course_show_price', array(
        'label' => __('Mostrar precio del curso', 'cursos-online-wp'),
        'section' => 'cursos_online_section',
        'type' => 'checkbox',
    ));
}
add_action('customize_register', 'cursos_online_customize_register');

function cursos_online_get_social_links() {
    return array(
        'facebook' => esc_url(get_theme_mod('cursos_online_facebook_url', '')),
        'instagram' => esc_url(get_theme_mod('cursos_online_instagram_url', '')),
        'twitter' => esc_url(get_theme_mod('cursos_online_twitter_url', '')),
        'linkedin' => esc_url(get_theme_mod('cursos_online_linkedin_url', '')),
    );
}

function cursos_online_get_whatsapp_url() {
    $phone = sanitize_text_field(get_theme_mod('cursos_online_whatsapp_phone', '')); 
    if (empty($phone)) {
        return '';
    }
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return 'https://wa.me/' . $phone;
}

function cursos_online_output_social_links() {
    $links = cursos_online_get_social_links();
    $whatsapp = cursos_online_get_whatsapp_url();

    if (empty(array_filter($links)) && empty($whatsapp)) {
        return;
    }

    echo '<div class="social-links-footer">';
    foreach ($links as $name => $url) {
        if (!$url) continue;
        echo '<a class="social-icon social-' . esc_attr($name) . '" href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">' . esc_html(ucfirst($name)) . '</a>';
    }
    if ($whatsapp) {
        echo '<a class="social-icon social-whatsapp" href="' . esc_url($whatsapp) . '" target="_blank" rel="noopener noreferrer">WhatsApp</a>';
    }
    echo '</div>';
}

function cursos_online_get_whatsapp_message() {
    return sanitize_text_field(get_theme_mod('cursos_online_whatsapp_message', __('¡Hola! Estoy interesado en sus cursos.', 'cursos-online-wp')));
}

function cursos_online_get_whatsapp_button_text() {
    return sanitize_text_field(get_theme_mod('cursos_online_whatsapp_button_text', __('Chatear por WhatsApp', 'cursos-online-wp')));
}

function cursos_online_get_whatsapp_button_image() {
    return esc_url(get_theme_mod('cursos_online_whatsapp_button_image', ''));
}

function cursos_online_get_courses_columns() {
    $columns = absint(get_theme_mod('cursos_online_courses_columns', 3));
    return max(1, min(6, $columns));
}

function cursos_online_get_course_button_text() {
    return sanitize_text_field(get_theme_mod('cursos_online_course_button_text', __('Comprar Curso', 'cursos-online-wp')));
}

function cursos_online_get_shop_url() {
    if (function_exists('wc_get_page_id')) {
        $shop_id = wc_get_page_id('shop');
        if ($shop_id && $shop_id > 0) {
            return get_permalink($shop_id);
        }
    }
    return home_url('/');
}

function cursos_online_output_whatsapp_floating_button() {
    $phone = sanitize_text_field(get_theme_mod('cursos_online_whatsapp_phone', '')); // ya existe en customizer
    if (empty($phone)) {
        return;
    }

    $message = urlencode(cursos_online_get_whatsapp_message());
    $button_text = esc_html(cursos_online_get_whatsapp_button_text());
    $image = cursos_online_get_whatsapp_button_image();
    $url = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . $message;

    echo '<div class="whatsapp-float-container">';
    echo '<a class="whatsapp-float" href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
    if ($image) {
        echo '<img src="' . esc_url($image) . '" alt="WhatsApp" class="whatsapp-float-img" />';
    } else {
        echo '<span class="whatsapp-float-icon">💬</span>';
    }
    echo '<span class="whatsapp-float-text">' . $button_text . '</span>';
    echo '</a>';
    echo '<div class="whatsapp-float-welcome">' . esc_html(cursos_online_get_whatsapp_message()) . '</div>';
    echo '</div>';
}
add_action('wp_footer', 'cursos_online_output_whatsapp_floating_button');

function cursos_online_theme_admin_menu_entry() {
    add_theme_page(
        __('Opciones del Tema', 'cursos-online-wp'),
        __('Opciones del Tema', 'cursos-online-wp'),
        'manage_options',
        'cursos-online-theme-options',
        'cursos_online_theme_options_page'
    );

    add_theme_page(
        __('Moodle Sync', 'cursos-online-wp'),
        __('Moodle Sync', 'cursos-online-wp'),
        'manage_options',
        'cursos-online-moodle-sync',
        'cursos_online_theme_sync_admin_page'
    );
}

function cursos_online_admin_page_slug_exists($slug) {
    // Best-effort detection after menus are built (admin_menu has run).
    global $menu, $submenu;

    if (is_array($menu)) {
        foreach ($menu as $item) {
            if (isset($item[2]) && $item[2] === $slug) {
                return true;
            }
        }
    }

    if (is_array($submenu)) {
        foreach ($submenu as $items) {
            if (!is_array($items)) {
                continue;
            }
            foreach ($items as $item) {
                if (isset($item[2]) && $item[2] === $slug) {
                    return true;
                }
            }
        }
    }

    return false;
}

function cursos_online_register_moodlewc_sync_alias_page() {
    // If the plugin page isn't registered, create a compatibility alias to avoid 403s.
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    if (cursos_online_admin_page_slug_exists('moodlewc-sync')) {
        return;
    }

    add_theme_page(
        __('Moodle Sync', 'cursos-online-wp'),
        __('Moodle Sync', 'cursos-online-wp'),
        'manage_options',
        'moodlewc-sync',
        'cursos_online_moodlewc_sync_alias_page'
    );
}
add_action('admin_menu', 'cursos_online_register_moodlewc_sync_alias_page', PHP_INT_MAX);

function cursos_online_moodlewc_sync_alias_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('No tienes permisos para ver esta página.', 'cursos-online-wp'));
    }

    wp_safe_redirect(admin_url('themes.php?page=moodle-integration'));
    exit;
}

function cursos_online_theme_sync_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('No tienes permisos para ver esta página.', 'cursos-online-wp'));
    }

    echo '<div class="wrap"><h1>' . esc_html__('Moodle Sync (Atajo del Tema)', 'cursos-online-wp') . '</h1>';
    echo '<p>' . esc_html__('Gestiona la integración y sincronización desde la página nativa del tema.', 'cursos-online-wp') . '</p>';
    echo '<a class="button button-primary" href="' . esc_url(admin_url('themes.php?page=moodle-integration')) . '">' . esc_html__('Abrir Integración Moodle', 'cursos-online-wp') . '</a>';
    echo '</div>';
}

function cursos_online_theme_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('No tienes permisos para ver esta página.', 'cursos-online-wp'));
    }

    if (isset($_POST['cursos_online_theme_options_nonce']) && wp_verify_nonce($_POST['cursos_online_theme_options_nonce'], 'cursos_online_theme_options_save')) {
        set_theme_mod('cursos_online_header_bg', sanitize_hex_color($_POST['cursos_online_header_bg']));
        set_theme_mod('cursos_online_nav_color', sanitize_hex_color($_POST['cursos_online_nav_color']));
        set_theme_mod('cursos_online_courses_columns', absint($_POST['cursos_online_courses_columns']));
        set_theme_mod('cursos_online_course_button_text', sanitize_text_field($_POST['cursos_online_course_button_text']));
        set_theme_mod('cursos_online_course_show_description', isset($_POST['cursos_online_course_show_description']) ? true : false);
        set_theme_mod('cursos_online_course_show_price', isset($_POST['cursos_online_course_show_price']) ? true : false);

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Configuración del tema guardada.', 'cursos-online-wp') . '</p></div>';
    }

    $header_bg = get_theme_mod('cursos_online_header_bg', '#10213F');
    $nav_color = get_theme_mod('cursos_online_nav_color', '#dfe9ff');
    $columns = get_theme_mod('cursos_online_courses_columns', 3);
    $button_text = get_theme_mod('cursos_online_course_button_text', __('Comprar Curso', 'cursos-online-wp'));
    $show_description = get_theme_mod('cursos_online_course_show_description', true);
    $show_price = get_theme_mod('cursos_online_course_show_price', true);

    echo '<div class="wrap"><h1>' . esc_html__('Opciones básicas del Tema', 'cursos-online-wp') . '</h1>';
    echo '<form method="post" action="">';
    wp_nonce_field('cursos_online_theme_options_save', 'cursos_online_theme_options_nonce');

    echo '<table class="form-table"><tbody>';
    echo '<tr><th scope="row"><label for="cursos_online_header_bg">' . esc_html__('Color de fondo del header', 'cursos-online-wp') . '</label></th><td><input type="text" id="cursos_online_header_bg" name="cursos_online_header_bg" value="' . esc_attr($header_bg) . '" class="regular-text" /></td></tr>';
    echo '<tr><th scope="row"><label for="cursos_online_nav_color">' . esc_html__('Color del texto de navegación', 'cursos-online-wp') . '</label></th><td><input type="text" id="cursos_online_nav_color" name="cursos_online_nav_color" value="' . esc_attr($nav_color) . '" class="regular-text" /></td></tr>';
    echo '<tr><th scope="row"><label for="cursos_online_courses_columns">' . esc_html__('Cursos por columna', 'cursos-online-wp') . '</label></th><td><input type="number" id="cursos_online_courses_columns" name="cursos_online_courses_columns" value="' . esc_attr($columns) . '" min="1" max="6" /></td></tr>';
    echo '<tr><th scope="row"><label for="cursos_online_course_button_text">' . esc_html__('Texto del botón en curso', 'cursos-online-wp') . '</label></th><td><input type="text" id="cursos_online_course_button_text" name="cursos_online_course_button_text" value="' . esc_attr($button_text) . '" class="regular-text" /></td></tr>';
    echo '<tr><th scope="row">' . esc_html__('Mostrar campos del curso', 'cursos-online-wp') . '</th><td>';
    echo '<label><input type="checkbox" name="cursos_online_course_show_description" ' . checked($show_description, true, false) . ' /> ' . esc_html__('Descripción', 'cursos-online-wp') . '</label><br />';
    echo '<label><input type="checkbox" name="cursos_online_course_show_price" ' . checked($show_price, true, false) . ' /> ' . esc_html__('Precio', 'cursos-online-wp') . '</label>';
    echo '</td></tr>';
    echo '</tbody></table>';

    submit_button(__('Guardar opciones del tema', 'cursos-online-wp'));
    echo '</form></div>';
}

add_action('admin_menu', 'cursos_online_theme_admin_menu_entry');

function cursos_online_handle_contact_form() {
    if (empty($_POST['cursos_online_contact_submit'])) {
        return;
    }

    if (!isset($_POST['cursos_online_contact_nonce']) || !wp_verify_nonce($_POST['cursos_online_contact_nonce'], 'cursos_online_contact_form')) {
        return;
    }

    $name = sanitize_text_field($_POST['contact_name'] ?? '');
    $email = sanitize_email($_POST['contact_email'] ?? '');
    $subject = sanitize_text_field($_POST['contact_subject'] ?? 'Contacto desde sitio web');
    $message = sanitize_textarea_field($_POST['contact_message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        wp_safe_redirect(add_query_arg('contact_form_status', 'error', wp_get_referer()));
        exit;
    }

    $to = get_option('admin_email');
    $mail_subject = sprintf('Formulario de contacto: %s', $subject);
    $body = "<strong>Nombre:</strong> " . esc_html($name) . "<br/><strong>Email:</strong> " . esc_html($email) . "<br/><strong>Mensaje:</strong><br/>" . nl2br(esc_html($message));
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $sent = wp_mail($to, $mail_subject, $body, $headers);
    $redirect_key = $sent ? 'success' : 'failed';
    wp_safe_redirect(add_query_arg('contact_form_status', $redirect_key, wp_get_referer()));
    exit;
}
add_action('init', 'cursos_online_handle_contact_form');

// Carga de plantillas Elementor para header/footer
function cursos_online_elementor_locations($elementor_theme_manager) {
    if (method_exists($elementor_theme_manager, 'register_all_core_location')) {
        $elementor_theme_manager->register_all_core_location();
    }
}
add_action('elementor/theme/register_locations', 'cursos_online_elementor_locations');

// Evita errores de WooCommerce si no hay productos
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'cursos_online_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'cursos_online_wrapper_end', 10 );

function cursos_online_wrapper_start() {
    echo '<section class="container woocommerce-shop-wrapper">';
}

function cursos_online_wrapper_end() {
    echo '</section>';
}

// Verificación de plugins necesarios
function cursos_online_requerimientos_admin_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $missing = array();
    if (!class_exists('WooCommerce')) {
        $missing[] = 'WooCommerce';
    }
    if (!defined('ELEMENTOR_VERSION')) {
        $missing[] = 'Elementor';
    }
    if (!defined('ELEMENTOR_PRO_VERSION')) {
        $missing[] = 'Elementor Pro';
    }
    if (!function_exists('tutor')) {
        $missing[] = 'Tutor LMS (opcional)';
    }

    if (!empty($missing)) {
        echo '<div class="notice notice-warning"><p><strong>' . esc_html__('Curso Online WP: faltan plugins requeridos:', 'cursos-online-wp') . '</strong> ' . esc_html(implode(', ', $missing)) . '.</p></div>';
    }
}
add_action('admin_notices', 'cursos_online_requerimientos_admin_notice');

/**
 * Crear páginas base (Inicio, Sobre Nosotros, Cursos, Contacto) al activar el tema.
 */
function cursos_online_crear_paginas_base() {
    // Avoid hard dependency on /screenshot.png (theme thumbnail) for front-end content.
    $banner_candidates = array(
        get_template_directory() . '/images/slide1.jpg' => get_template_directory_uri() . '/images/slide1.jpg',
        get_template_directory() . '/images/slide1.jpeg' => get_template_directory_uri() . '/images/slide1.jpeg',
        get_template_directory() . '/images/slide1.png' => get_template_directory_uri() . '/images/slide1.png',
        get_template_directory() . '/screenshot.png' => get_template_directory_uri() . '/screenshot.png',
    );

    $banner_url = '';
    foreach ($banner_candidates as $path => $url) {
        if (file_exists($path)) {
            $banner_url = $url;
            break;
        }
    }

    $banner_html = '';
    if (!empty($banner_url)) {
        $banner_html = '<img src="' . esc_url($banner_url) . '" alt="Banner Curso" style="max-width:100%;height:auto;margin:1rem 0;" />';
    }
    $pages = array(
        'inicio' => array(
            'title' => 'Inicio',
            'content' => '<h2>Bienvenido a tu plataforma de formación online</h2><p>Con PCCurico.cl obtén cursos certificados, soporte docente y acceso a aula virtual integrado con Moodle. Diseña cada sección con Elementor para obtener una web profesional.</p>' . $banner_html,
            'template' => 'page-home.php',
        ),
        'sobre-nosotros' => array(
            'title' => 'Sobre Nosotros',
            'content' => '<h2>Sobre Nosotros</h2><p>PCCurico.cl es un proyecto de capacitación e-learning para OTEC, creado por JCares. Nuestra misión es ofrecer formación de calidad y convertir tu organización en líder educativo.</p><p>Historia y valores: innovación, inclusión y resultados medibles.</p>',
            'template' => 'page-about.php',
        ),
        'cursos' => array(
            'title' => 'Cursos',
            'content' => '<h2>Cursos Disponibles</h2><p>Revisa el catálogo de cursos, ficha el plan de estudios y elige tu ruta formativa.</p><ul><li>Curso de Marketing Digital</li><li>Curso de Desarrollo Web</li><li>Curso de Gestión de Proyectos</li></ul>',
            'template' => 'page-courses.php',
        ),
        'contacto' => array(
            'title' => 'Contacto',
            'content' => '<h2>Contacto</h2><p>Usa el formulario de contacto para solicitar información, asesoría o demo de la plataforma.</p><p>También puedes escribir al WhatsApp que configures en personalización.</p>',
            'template' => 'page-contact.php',
        ),
    );

    foreach ($pages as $slug => $data) {
        if (!get_page_by_path($slug)) {
            $page_id = wp_insert_post(array(
                'post_title' => wp_strip_all_tags($data['title']),
                'post_content' => $data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug,
            ));

            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $data['template']);
            }
        }
    }

    // Establecer inicio estático si no está definido.
    $home_page = get_page_by_path('inicio');
    if ($home_page && !get_option('page_on_front')) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $home_page->ID);
    }
}
add_action('after_switch_theme', 'cursos_online_crear_paginas_base');

function cursos_online_redirect_legacy_admin_php_pages() {
    // These pages live under Appearance (themes.php). If accessed via admin.php they can 403.
    global $pagenow;
    if ($pagenow !== 'admin.php') {
        return;
    }

    $page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';
    if ($page !== 'moodle-integration' && $page !== 'moodlewc-sync') {
        return;
    }

    wp_safe_redirect(admin_url('themes.php?page=' . $page));
    exit;
}
add_action('admin_init', 'cursos_online_redirect_legacy_admin_php_pages', 1);

function cursos_online_fix_home_banner_screenshot_once() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    $flag = 'cursos_online_fix_home_banner_screenshot_20260314';
    if (get_option($flag)) {
        return;
    }

    $home_page = get_page_by_path('inicio');
    if (!$home_page || empty($home_page->ID)) {
        update_option($flag, 1);
        return;
    }

    // Only touch the theme-created home page template.
    $tpl = get_post_meta($home_page->ID, '_wp_page_template', true);
    if ($tpl !== 'page-home.php') {
        update_option($flag, 1);
        return;
    }

    $content = (string) $home_page->post_content;
    if (stripos($content, 'screenshot.png') === false) {
        update_option($flag, 1);
        return;
    }

    // Replace any <img> referencing screenshot.png with nothing (prevents 404 noise).
    $updated = preg_replace('~<img[^>]+screenshot\\.png[^>]*\\/?>(?:\\s*)~i', '', $content);
    if ($updated !== null && $updated !== $content) {
        wp_update_post(array(
            'ID' => $home_page->ID,
            'post_content' => $updated,
        ));
    }

    update_option($flag, 1);
}
add_action('admin_init', 'cursos_online_fix_home_banner_screenshot_once');

/**
 * URL de aula virtual configurable
 */
function cursos_online_aula_virtual_url() {
    $url = get_option('cursos_online_aula_virtual_url', 'https://tusitio.com/aulavirtual/login/index.php');
    return esc_url($url);
}

/**
 * ACF-like settings page para URL aula virtual
 */
function cursos_online_ajustes_aula_virtual() {
    register_setting('general', 'cursos_online_aula_virtual_url', array('type' => 'string', 'sanitize_callback' => 'esc_url_raw'));
    add_settings_field('cursos_online_aula_virtual_url', 'URL Aula Virtual', 'cursos_online_aula_virtual_url_field_html', 'general');
}
function cursos_online_aula_virtual_url_field_html() {
    $value = get_option('cursos_online_aula_virtual_url', 'https://tusitio.com/aulavirtual/login/index.php');
    echo '<input type="url" name="cursos_online_aula_virtual_url" value="' . esc_attr($value) . '" class="regular-text" />';
}
add_action('admin_init', 'cursos_online_ajustes_aula_virtual');

?>
