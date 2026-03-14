<?php
/**
 * Main Index Template (Fallback)
 * Plantilla de fallback para todas las páginas
 */
defined('ABSPATH') || exit;

get_header();
?>

<main class="index-template" style="padding: 60px 20px;">
    <div class="elementor-container" style="max-width: 1000px; margin: 0 auto;">
        
        <!-- Título dinámico -->
        <h1 style="font-size: 36px; margin: 0 0 30px 0; color: #333;">
            <?php
            if (is_home() && !is_front_page()) :
                echo esc_html(single_post_title());
            elseif (is_search()) :
                printf(esc_html__('Resultados de búsqueda: %s', 'cursos-online-wp'), '<strong>' . esc_html(get_search_query()) . '</strong>');
            else :
                esc_html_e('Últimas Actualizaciones', 'cursos-online-wp');
            endif;
            ?>
        </h1>

        <!-- Contenido -->
        <?php if (have_posts()) : ?>

            <div class="posts-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?> 
                        style="background: #fff; border: 1px solid #eee; border-radius: 10px; overflow: hidden; transition: box-shadow 0.3s ease;">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div style="overflow: hidden; height: 200px; background: #f0f0f0;">
                                <a href="<?php the_permalink(); ?>" style="display: block; height: 100%;">
                                    <?php the_post_thumbnail('medium', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div style="padding: 20px;">
                            <!-- Categoría/Tipo -->
                            <div style="font-size: 11px; color: #999; text-transform: uppercase; margin-bottom: 10px;">
                                <?php
                                if (is_singular('post')) {
                                    the_category(', ');
                                } else {
                                    $type = get_post_type_object(get_post_type());
                                    echo $type->labels->singular_name;
                                }
                                ?>
                            </div>

                            <!-- Título -->
                            <h2 style="margin: 0 0 10px 0; font-size: 18px;">
                                <a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <!-- Fecha -->
                            <time style="font-size: 12px; color: #999;" datetime="<?php the_time('c'); ?>">
                                <?php the_time(get_option('date_format')); ?>
                            </time>

                            <!-- Contenido -->
                            <div style="margin: 15px 0; color: #555; line-height: 1.6; font-size: 14px;">
                                <?php the_excerpt(); ?>
                            </div>

                            <!-- Botón leer más -->
                            <a href="<?php the_permalink(); ?>" style="color: #667eea; text-decoration: none; font-weight: 600; font-size: 14px;">
                                <?php esc_html_e('Ver más →', 'cursos-online-wp'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>

            </div>

            <!-- Paginación -->
            <div style="text-align: center; margin: 60px 0;">
                <?php the_posts_pagination([
                    'mid_size' => 3,
                    'prev_text' => __('← Anterior', 'cursos-online-wp'),
                    'next_text' => __('Siguiente →', 'cursos-online-wp'),
                ]); ?>
            </div>

        <?php else : ?>

            <!-- Sin contenido -->
            <div style="text-align: center; padding: 40px 20px;">
                <h2 style="color: #666; margin-bottom: 15px;">
                    <?php esc_html_e('No hay contenido disponible', 'cursos-online-wp'); ?>
                </h2>
                <p style="color: #999;">
                    <?php esc_html_e('Intenta con otros términos de búsqueda o explora nuestras categorías.', 'cursos-online-wp'); ?>
                </p>
                <a href="<?php echo esc_url(home_url()); ?>" style="background: #667eea; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px;">
                    <?php esc_html_e('Volver al inicio', 'cursos-online-wp'); ?>
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
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
