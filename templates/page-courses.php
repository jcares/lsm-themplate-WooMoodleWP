<?php if (!defined('ABSPATH')) exit; ?>
<main class="template-main">
    <div class="container">
        <h1><?php esc_html_e('Catálogo de Cursos', 'cursos-online-wp'); ?></h1>
        <p><?php esc_html_e('Optimiza tu aprendizaje y encuentra el curso ideal para tu desarrollo profesional.', 'cursos-online-wp'); ?></p>

        <?php
            $columns = cursos_online_get_courses_columns();
            $is_show_desc = get_theme_mod('cursos_online_course_show_description', true);
            $is_show_price = get_theme_mod('cursos_online_course_show_price', true);
            $ct_text = cursos_online_get_course_button_text();
        ?>

        <div class="courses-grid courses-grid-cols-<?php echo esc_attr($columns); ?>">
            <?php
            $curso_ficticios = array(
                array('title' => 'Marketing Digital 2050', 'desc' => 'Estrategias en metaverso, datos, IA y storytelling con resultados medibles.', 'price' => '$89.990'),
                array('title' => 'UX/UI para Interfaces de Aprendizaje', 'desc' => 'Diseña experiencias inmersivas para estudiantes de diversos dispositivos y contextos.', 'price' => '$79.990'),
                array('title' => 'Gestión de Proyectos Agile + Digital', 'desc' => 'Metodologías ágiles para proyectos de capacitación, tecnología y equipos distribuidos.', 'price' => '$99.990'),
            );

            foreach ($curso_ficticios as $item) : ?>
                <article class="course-card">
                    <h3><?php echo esc_html($item['title']); ?></h3>
                    <?php if ($is_show_desc): ?><p><?php echo esc_html($item['desc']); ?></p><?php endif; ?>
                    <?php if ($is_show_price): ?><span class="course-price"><?php echo esc_html($item['price']); ?></span><?php endif; ?>
                    <a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" class="btn-course"><?php echo esc_html($ct_text); ?></a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="cta-section">
            <a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" class="btn-principal"><?php echo esc_html(cursos_online_get_course_button_text()); ?></a>
        </div>
    </div>
</main>
