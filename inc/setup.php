<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Archivo de configuración modular del tema (se carga desde functions.php).
 */

function cursos_online_inc_setup() {
    // Esto se mantiene simple: ya se define en functions.php.
}
add_action('after_setup_theme', 'cursos_online_inc_setup');
