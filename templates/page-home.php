<?php if (!defined('ABSPATH')) exit; ?>
<section class="hero-global">
    <div class="hero-slide active" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/images/slide1.jpg'); ?>');"></div>
    <div class="hero-slide" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/images/slide2.jpg'); ?>');"></div>
    <div class="hero-slide" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/images/slide3.jpg'); ?>');"></div>

    <div class="hero-layer">
        <h1 class="hero-title"><?php echo esc_html(get_theme_mod('cursos_online_hero_text', 'Aprende en línea con los mejores cursos')); ?></h1>
        <p class="hero-subtitle"><?php esc_html_e('Plataforma de formación cargada a futuro, optimizada para OTEC, academias y ecosistemas e-learning.', 'cursos-online-wp'); ?></p>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-principal"><?php esc_html_e('Catálogo de Cursos', 'cursos-online-wp'); ?></a>
    </div>
</section>

<main class="template-main">
    <div class="container">
        <div class="wave-panel">
            <h2><?php esc_html_e('Nuestra visión 2050 de la formación', 'cursos-online-wp'); ?></h2>
            <p><?php esc_html_e('Innovación constante, micro-certificaciones, integración Moodle y WooCommerce, con soporte directo.', 'cursos-online-wp'); ?></p>
        </div>
        <div class="cards-grid">
            <article class="card">
                <h3><?php esc_html_e('Ecosistema integral', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Diseñamos tu plataforma para vender, formar y certificar sin complicaciones.', 'cursos-online-wp'); ?></p>
            </article>
            <article class="card">
                <h3><?php esc_html_e('Acceso inmediato', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Sincronización Moodle -> WooCommerce, creación de cursos y soporte repetible.', 'cursos-online-wp'); ?></p>
            </article>
            <article class="card">
                <h3><?php esc_html_e('Diseño del futuro', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Efectos neon, glassmorphism y navegación inteligente para usuarios y managers.', 'cursos-online-wp'); ?></p>
            </article>
        </div>
    </div>
</main>
