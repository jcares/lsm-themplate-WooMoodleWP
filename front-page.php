<?php
/**
 * Front Page Template
 * Página de inicio con hero customizable desde el Customizer
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- Hero Section personalizado -->
<section class="hero-section" style="
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); z-index: 0;"></div>
    
    <div class="elementor-container" style="position: relative; z-index: 1; max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 48px; margin: 0 0 20px 0; font-weight: bold;">
            <?php echo esc_html(get_theme_mod('cursos_online_hero_text', 'Formación Online de Calidad')); ?>
        </h1>
        
        <p style="font-size: 20px; margin: 0 0 30px 0; opacity: 0.95;">
            <?php echo esc_html(get_theme_mod('cursos_online_hero_subtitle', 'Plataforma integral de e-learning con WooCommerce, Moodle y Elementor')); ?>
        </p>
        
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" 
               class="btn-primary" 
               style="background: white; color: #667eea; padding: 15px 35px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: transform 0.3s ease;">
                <?php echo esc_html(get_theme_mod('cursos_online_button_text', 'Ver Cursos Disponibles')); ?>
            </a>
            
            <?php $virtual_classroom_url = get_option('cursos_online_aula_virtual_url'); ?>
            <?php if ($virtual_classroom_url) : ?>
                <a href="<?php echo esc_url($virtual_classroom_url); ?>" 
                   class="btn-secondary" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   style="background: transparent; color: white; padding: 15px 35px; border: 2px solid white; border-radius: 8px; text-decoration: none; font-weight: 600; transition: background 0.3s ease;">
                    <?php esc_html_e('Aula Virtual', 'cursos-online-wp'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (have_posts()) : the_post(); ?>
    
    <!-- Contenido editable desde el editor -->
    <main class="page-content" style="padding: 80px 20px;">
        <div class="elementor-container" style="max-width: 1000px; margin: 0 auto;">
            <?php the_content(); ?>
        </div>
    </main>

<?php endif; ?>

<!-- Stats Section -->
<section class="stats-section" style="background: #f8f9fa; padding: 60px 20px;">
    <div class="elementor-container" style="max-width: 1000px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; text-align: center;">
            <div class="stat-item">
                <h3 style="font-size: 36px; color: #667eea; margin: 0;">
                    <?php echo esc_html(get_theme_mod('cursos_online_stat1_number', '160+')); ?>
                </h3>
                <p style="color: #666; margin: 10px 0 0 0;">
                    <?php echo esc_html(get_theme_mod('cursos_online_stat1_label', 'Cursos Disponibles')); ?>
                </p>
            </div>
            <div class="stat-item">
                <h3 style="font-size: 36px; color: #667eea; margin: 0;">
                    <?php echo esc_html(get_theme_mod('cursos_online_stat2_number', '5000+')); ?>
                </h3>
                <p style="color: #666; margin: 10px 0 0 0;">
                    <?php echo esc_html(get_theme_mod('cursos_online_stat2_label', 'Estudiantes Activos')); ?>
                </p>
            </div>
            <div class="stat-item">
                <h3 style="font-size: 36px; color: #667eea; margin: 0;">
                    <?php echo esc_html(get_theme_mod('cursos_online_stat3_number', '98%')); ?>
                </h3>
                <p style="color: #666; margin: 10px 0 0 0;">
                    <?php echo esc_html(get_theme_mod('cursos_online_stat3_label', 'Satisfacción')); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="final-cta" style="
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    padding: 80px 20px;
    text-align: center;
">
    <div class="elementor-container" style="max-width: 600px; margin: 0 auto;">
        <h2 style="font-size: 36px; margin: 0 0 20px 0;">
            <?php esc_html_e('¿Listo para transformar tu aprendizaje?', 'cursos-online-wp'); ?>
        </h2>
        <p style="font-size: 18px; margin: 0 0 30px 0; opacity: 0.95;">
            <?php esc_html_e('Únete a miles de estudiantes que ya están aprendiendo con nosotros.', 'cursos-online-wp'); ?>
        </p>
        <a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" 
           class="btn-cta" 
           style="background: white; color: #667eea; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-block; transition: transform 0.3s ease;">
            <?php esc_html_e('Explorar Cursos Ahora', 'cursos-online-wp'); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
