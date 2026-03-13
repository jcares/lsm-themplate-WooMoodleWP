<?php get_header(); ?>

<section class="hero-slider">
    <?php $template_url = get_template_directory_uri(); ?>
    <div class="hero-slide active" style="background-image:url('<?php echo esc_url($template_url . '/images/slide1.jpg'); ?>');">
        <div class="hero-slide-content">
            <h1>Formación online con certificación</h1>
            <p>Accede a cursos profesionales con camino de carrera y soporte docente.</p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-principal"><?php esc_html_e('Ver Todos los Cursos', 'cursos-online-wp'); ?></a>
        </div>
    </div>

    <div class="hero-slide" style="background-image:url('<?php echo esc_url($template_url . '/images/slide2.jpg'); ?>');">
        <div class="hero-slide-content">
            <h1>Especialízate en habilidades digitales</h1>
            <p>Programación, marketing, negocios y más con planes para empresas.</p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-principal"><?php esc_html_e('Explorar Cursos', 'cursos-online-wp'); ?></a>
        </div>
    </div>

    <div class="hero-slide" style="background-image:url('<?php echo esc_url($template_url . '/images/slide3.jpg'); ?>');">
        <div class="hero-slide-content">
            <h1>Academia y consultoría para OTEC</h1>
            <p>Herramientas escalables para gestionar formación abierta y cerrada.</p>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-principal"><?php esc_html_e('Ir a la Tienda', 'cursos-online-wp'); ?></a>
        </div>
    </div>

    <div class="hero-dots">
        <span class="hero-dot active"></span>
        <span class="hero-dot"></span>
        <span class="hero-dot"></span>
    </div>
</section>

<?php get_footer(); ?>
