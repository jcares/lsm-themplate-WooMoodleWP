<?php get_header(); ?>

<section class="elementor-section hero-section" role="banner">
    <div class="elementor-container">
        <div class="hero-slide active" style="background-image:url('<?php echo esc_url(get_template_directory_uri() . '/images/slide1.jpg'); ?>');">
            <div class="hero-slide-content">
                <h1><?php echo esc_html(get_theme_mod('cursos_online_hero_text', 'Formación online con certificación')); ?></h1>
                <p><?php esc_html_e('Accede a cursos profesionales con camino de carrera y soporte docente.', 'cursos-online-wp'); ?></p>
                <a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" class="btn-principal"><?php echo esc_html(cursos_online_get_course_button_text()); ?></a>
            </div>
        </div>
        <div class="hero-cta-container">
            <div class="hero-cta-content">
                <h2><?php esc_html_e('¿Listo para iniciar tu aprendizaje 2050?', 'cursos-online-wp'); ?></h2>
                <p><?php esc_html_e('Prueba el sistema con demo en vivo, integrando Moodle + WooCommerce + Elementor.', 'cursos-online-wp'); ?></p>
                <a href="<?php echo esc_url(get_option('cursos_online_aula_virtual_url', '#')); ?>" class="btn-secondary" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Ingresar Aula Virtual', 'cursos-online-wp'); ?></a>
            </div>
        </div>
    </div>
</section>

<main class="elementor-section template-main" role="main">
    <div class="elementor-container">
        <div class="cards-grid">
            <article class="course-card">
                <h3><?php esc_html_e('Plan de Estudios Dinámico', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Organiza cursos con Moodle y vende como productos en WooCommerce con ruta de aprendizaje.', 'cursos-online-wp'); ?></p>
            </article>
            <article class="course-card">
                <h3><?php esc_html_e('Integración con Elementor', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Diseña páginas con contenedores y widgets de Elementor; cambia el contenido con un solo clic.', 'cursos-online-wp'); ?></p>
            </article>
            <article class="course-card">
                <h3><?php esc_html_e('Sincronización a un clic', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Sincroniza cursos Moodle desde el panel y crea producto con precio, categoría e imagen automáticamente.', 'cursos-online-wp'); ?></p>
            </article>
        </div>
    </div>
</main>

<?php get_footer(); ?>
