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
    $pages = array(
        'inicio' => array(
            'title' => 'Inicio',
            'content' => '<h2>Bienvenido a tu plataforma de formación online</h2><p>Con PCCurico.cl obtén cursos certificados, soporte docente y acceso a aula virtual integrado con Moodle. Diseña cada sección con Elementor para obtener una web profesional.</p><img src="' . esc_url(get_template_directory_uri() . '/screenshot.png') . '" alt="Banner Curso" style="max-width:100%;height:auto;margin:1rem 0;" />',
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