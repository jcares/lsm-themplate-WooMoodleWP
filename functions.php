<?php
/**
 * Configuración del tema
 */

// Soportes básicos
function cursos_online_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Registrar menú
    register_nav_menus(array(
        'menu-principal' => __('Menú Principal', 'cursos-online-wp')
    ));
}
add_action('after_setup_theme', 'cursos_online_setup');

// Cargar estilos y scripts
function cursos_online_assets() {
    wp_enqueue_style('style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'cursos_online_assets');

// Personalizar texto del botón de compra
function cursos_online_boton_compra_texto($text) {
    return __('Comprar Curso', 'cursos-online-wp');
}
add_filter('woocommerce_product_add_to_cart_text', 'cursos_online_boton_compra_texto');
add_filter('woocommerce_product_single_add_to_cart_text', 'cursos_online_boton_compra_texto');
