<?php
/**
 * 404 Template
 * Página de error 404 personalizada
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","right":"20px","bottom":"80px","left":"20px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<main class="wp-block-group error-404-page" style="padding-top: 80px; padding-right: 20px; padding-bottom: 80px; padding-left: 20px; text-align: center;">

	<div class="error-404-container" style="padding: 60px 20px;">
		<!-- Código de error grande -->
		<h1 style="font-size: 120px; margin: 0; color: #667eea; font-weight: bold; line-height: 1;">
			404
		</h1>

		<!-- Mensaje principal -->
		<h2 class="page-title" style="font-size: 36px; margin: 20px 0; color: #333;">
			<?php esc_html_e('Página No Encontrada', 'cursos-online-wp'); ?>
		</h2>

		<!-- Descripción -->
		<p class="error-description" style="font-size: 18px; color: #666; margin: 20px 0 40px 0; max-width: 600px; margin-left: auto; margin-right: auto;">
			<?php esc_html_e('Lo sentimos, la página que buscas no existe. Puede haber sido eliminada o el enlace es incorrecto.', 'cursos-online-wp'); ?>
		</p>

		<!-- Opciones de navegación -->
		<div class="error-actions" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin: 40px 0;">
			<a href="<?php echo esc_url(home_url()); ?>" class="btn-primary" style="background: #667eea; color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
				<?php esc_html_e('Ir al Inicio', 'cursos-online-wp'); ?>
			</a>
			<a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" class="btn-secondary" style="background: transparent; color: #667eea; padding: 15px 30px; border: 2px solid #667eea; border-radius: 8px; text-decoration: none; font-weight: 600;">
				<?php esc_html_e('Ver Cursos', 'cursos-online-wp'); ?>
			</a>
		</div>

		<!-- Búsqueda -->
		<div class="error-search" style="margin-top: 60px; max-width: 500px; margin-left: auto; margin-right: auto;">
			<h3 style="margin-bottom: 20px; color: #333;"><?php esc_html_e('¿Qué Buscas?', 'cursos-online-wp'); ?></h3>
			<?php get_search_form(); ?>
		</div>

		<!-- Posts recientes sugeridos -->
		<div class="recent-posts-suggestion" style="margin-top: 60px;">
			<h3 style="margin-bottom: 30px; color: #333;"><?php esc_html_e('Artículos Recientes', 'cursos-online-wp'); ?></h3>
			<?php
			$recent_posts = get_posts([
				'numberposts' => 3,
				'post_type'   => 'post',
			]);

			if (!empty($recent_posts)) : 
			?>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
					<?php
					foreach ($recent_posts as $post) :
						setup_postdata($post);
					?>
						<article class="recent-post-card" style="text-align: left; background: #f8f9fa; padding: 20px; border-radius: 10px; transition: transform 0.3s ease;">
							<h4 style="margin: 0 0 10px 0;">
								<a href="<?php the_permalink(); ?>" style="color: #667eea; text-decoration: none;">
									<?php the_title(); ?>
								</a>
							</h4>
							<p style="margin: 0; color: #666; font-size: 14px;">
								<?php echo wp_trim_words(get_the_excerpt(), 15); ?>
							</p>
						</article>
					<?php
					endforeach;
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>

</main>
<!-- /wp:group -->

<?php get_footer(); ?>
