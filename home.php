<?php
/**
 * Home Template
 * Página de inicio alternativa si no se define front-page.php
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- Encabezado -->
<section class="home-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 20px; text-align: center;">
    <div class="elementor-container" style="max-width: 800px; margin: 0 auto;">
        <h1 style="font-size: 42px; margin: 0 0 15px 0;">
            <?php bloginfo('name'); ?>
        </h1>
        <p style="font-size: 18px; margin: 0;">
            <?php bloginfo('description'); ?>
        </p>
    </div>
</section>

<!-- Contenido del blog -->
<main class="home-content" style="padding: 60px 20px;">
    <div class="elementor-container" style="max-width: 1000px; margin: 0 auto;">
        
        <?php if (have_posts()) : ?>
            <div class="posts-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
                
                <?php while (have_posts()) : the_post(); ?>
                    <article class="post-card" id="post-<?php the_ID(); ?>" <?php post_class(); ?> 
                        style="background: #f9f9f9; border-radius: 10px; overflow: hidden; transition: transform 0.3s ease;">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail" style="overflow: hidden; height: 200px;">
                                <a href="<?php the_permalink(); ?>" style="display: block; height: 100%; overflow: hidden;">
                                    <?php the_post_thumbnail('medium', ['style' => 'width: 100%; height: 100%; object-fit: cover;']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-inner" style="padding: 20px;">
                            <div class="post-meta" style="font-size: 12px; color: #999; margin-bottom: 10px;">
                                <time datetime="<?php the_time('c'); ?>">
                                    <?php the_time(get_option('date_format')); ?>
                                </time>
                                <?php if (get_the_category()) : ?>
                                    <span style="margin-left: 10px;">
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h2 class="post-title" style="margin: 0 0 12px 0; font-size: 20px;">
                                <a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="post-excerpt" style="color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                <?php esc_html_e('Leer más →', 'cursos-online-wp'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
                
            </div>
            
            <!-- Paginación -->
            <div style="text-align: center; margin: 40px 0;">
                <?php the_posts_pagination([
                    'mid_size' => 2,
                    'prev_text' => __('← Anterior', 'cursos-online-wp'),
                    'next_text' => __('Siguiente →', 'cursos-online-wp'),
                ]); ?>
            </div>
            
        <?php else : ?>
            <div class="no-posts" style="text-align: center; padding: 60px 20px;">
                <h2><?php esc_html_e('No hay artículos aún', 'cursos-online-wp'); ?></h2>
                <p><?php esc_html_e('Pronto habrá contenido nuevo aquí.', 'cursos-online-wp'); ?></p>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
