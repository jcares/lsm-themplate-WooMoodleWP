<?php if (!defined('ABSPATH')) exit; ?>
<main class="template-main">
    <div class="container">
        <h1><?php esc_html_e('Catálogo de Cursos', 'cursos-online-wp'); ?></h1>
        <p><?php esc_html_e('Optimiza tu aprendizaje y encuentra el curso ideal para tu desarrollo profesional.', 'cursos-online-wp'); ?></p>

        <div class="courses-grid">
            <article class="course-card">
                <h3><?php esc_html_e('Marketing Digital 2050', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Estrategias en metaverso, datos, IA y storytelling con resultados medibles.', 'cursos-online-wp'); ?></p>
                <span class="course-price">$89.990</span>
            </article>
            <article class="course-card">
                <h3><?php esc_html_e('UX/UI para Interfaces de Aprendizaje', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Diseña experiencias inmersivas para estudiantes de diversos dispositivos y contextos.', 'cursos-online-wp'); ?></p>
                <span class="course-price">$79.990</span>
            </article>
            <article class="course-card">
                <h3><?php esc_html_e('Gestión de Proyectos Agile + Digital', 'cursos-online-wp'); ?></h3>
                <p><?php esc_html_e('Metodologías ágiles para proyectos de capacitación, tecnología y equipos distribuidos.', 'cursos-online-wp'); ?></p>
                <span class="course-price">$99.990</span>
            </article>
        </div>

        <div class="cta-section">
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn-principal"><?php esc_html_e('Ver todos los cursos', 'cursos-online-wp'); ?></a>
        </div>
    </div>
</main>
