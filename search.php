<?php
/**
 * Search Template
 * Página de resultados de búsqueda
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","right":"20px","bottom":"80px","left":"20px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<main class="wp-block-group search-results">

	<!-- Encabezado de búsqueda -->
	<section class="search-header" style="margin-bottom: 50px;">
		<h1 class="search-title" style="font-size: 36px; margin: 0 0 20px 0;">
			<?php printf(
				esc_html__('Resultados de búsqueda para: "%s"', 'cursos-online-wp'),
				'<span style="color: #667eea;">' . esc_html(get_search_query()) . '</span>'
			); ?>
		</h1>
		<p style="color: #666; font-size: 16px;">
			<?php printf(
				esc_html__('Se encontraron %d resultados', 'cursos-online-wp'),
				$GLOBALS['wp_query']->found_posts
			); ?>
		</p>
	</section>

	<!-- Filas de búsqueda actualizada -->
	<div class="search-form" style="margin-bottom: 40px;">
		<?php get_search_form(); ?>
	</div>

	<!-- Resultados -->
	<?php if (have_posts()) : ?>

		<div class="search-results-list" style="margin-bottom: 40px;">
			<?php while (have_posts()) : the_post(); ?>
				<article class="search-result-item" style="margin-bottom: 40px; padding-bottom: 40px; border-bottom: 1px solid #eee;">
					<!-- Imagen (si existe) -->
					<?php if (has_post_thumbnail()) : ?>
						<div class="result-thumbnail" style="margin-bottom: 15px;">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('medium', ['style' => 'border-radius: 10px; max-width: 200px; height: auto;']); ?>
							</a>
						</div>
					<?php endif; ?>

					<!-- Título -->
					<h2 class="result-title" style="margin: 0 0 10px 0; font-size: 24px;">
						<a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;">
							<?php the_title(); ?>
						</a>
					</h2>

					<!-- Metadata -->
					<div class="result-meta" style="font-size: 14px; color: #666; margin-bottom: 15px;">
						<?php 
						// Post type
						$post_type = get_post_type();
						$post_type_label = get_post_type_object($post_type)->labels->singular_name;
						echo esc_html($post_type_label) . ' • ';
						
						// Fecha
						echo get_the_date(get_option('date_format'));
						?>
					</div>

					<!-- Excerpt -->
					<div class="result-excerpt" style="color: #555; line-height: 1.6; margin-bottom: 15px;">
						<?php the_excerpt(); ?>
					</div>

					<!-- Enlace de lectura -->
					<a href="<?php the_permalink(); ?>" class="read-more" style="color: #667eea; text-decoration: none; font-weight: 600;">
						<?php esc_html_e('Leer más →', 'cursos-online-wp'); ?>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<!-- Paginación -->
		<div class="search-pagination" style="text-align: center; margin-top: 40px;">
			<?php the_posts_pagination([
				'mid_size'  => 2,
				'prev_text' => __('← Anterior', 'cursos-online-wp'),
				'next_text' => __('Siguiente →', 'cursos-online-wp'),
			]); ?>
		</div>

	<?php else : ?>

		<!-- Sin resultados -->
		<div class="no-results" style="text-align: center; padding: 60px 20px;">
			<h2 style="color: #333; margin-bottom: 20px;">
				<?php esc_html_e('No se encontraron resultados', 'cursos-online-wp'); ?>
			</h2>
			<p style="color: #666; margin-bottom: 30px;">
				<?php esc_html_e('Intenta con otros términos de búsqueda o navega por nuestros cursos.', 'cursos-online-wp'); ?>
			</p>
			<a href="<?php echo esc_url(cursos_online_get_shop_url()); ?>" class="btn-primary" style="background: #667eea; color: white; padding: 12px 24px; border-radius: 5px; text-decoration: none; display: inline-block;">
				<?php esc_html_e('Ver Todos los Cursos', 'cursos-online-wp'); ?>
			</a>
		</div>

	<?php endif; ?>

</main>
<!-- /wp:group -->

<?php get_footer(); ?>
