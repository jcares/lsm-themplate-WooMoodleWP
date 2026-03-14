<?php
/**
 * Archive Template
 * Muestra archivos de posts, categorías, autores, fechas
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- wp:group {"tagName":"main","layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group archive-content" style="padding: 40px 20px;">

	<!-- Encabezado del archivo -->
	<section class="archive-header" style="margin-bottom: 50px; text-align: center;">
		<?php if (is_category()) : ?>
			<h1 class="archive-title" style="font-size: 36px; margin: 0 0 20px 0;">
				<?php single_cat_title(); ?>
			</h1>
			<div class="archive-description" style="color: #666; font-size: 16px;">
				<?php echo wp_kses_post(category_description()); ?>
			</div>

		<?php elseif (is_tag()) : ?>
			<h1 class="archive-title" style="font-size: 36px; margin: 0 0 20px 0;">
				<?php single_tag_title(); ?>
			</h1>
			<p style="color: #666; font-size: 16px;">
				<?php esc_html_e('Artículos etiquetados como:', 'cursos-online-wp'); ?>
				<strong><?php single_tag_title(); ?></strong>
			</p>

		<?php elseif (is_author()) : ?>
			<h1 class="archive-title" style="font-size: 36px; margin: 0 0 20px 0;">
				<?php esc_html_e('Artículos de ', 'cursos-online-wp'); ?>
				<?php the_author(); ?>
			</h1>
			<?php 
			$author_bio = get_the_author_meta('description');
			if ($author_bio) : 
			?>
				<div class="author-bio" style="color: #555; margin-top: 20px;">
					<?php echo wp_kses_post($author_bio); ?>
				</div>
			<?php endif; ?>

		<?php elseif (is_archive()) : ?>
			<h1 class="archive-title" style="font-size: 36px; margin: 0 0 20px 0;">
				<?php the_archive_title(); ?>
			</h1>
			<?php 
			$archive_desc = get_the_archive_description();
			if ($archive_desc) : 
			?>
				<div class="archive-description" style="color: #666; font-size: 16px;">
					<?php echo wp_kses_post($archive_desc); ?>
				</div>
			<?php endif; ?>

		<?php endif; ?>
	</section>

	<!-- Grid de resultados -->
	<?php if (have_posts()) : ?>

		<div class="posts-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 40px;">
			<?php while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('archive-post-card'); ?> 
					style="background: #f9f9f9; border-radius: 15px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease;">

					<!-- Thumbnail -->
					<?php if (has_post_thumbnail()) : ?>
						<div class="post-thumbnail" style="overflow: hidden; height: 250px;">
							<a href="<?php the_permalink(); ?>" style="display: block; height: 100%; overflow: hidden;">
								<?php the_post_thumbnail('medium', ['style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;']); ?>
							</a>
						</div>
					<?php endif; ?>

					<!-- Contenido -->
					<div class="post-info" style="padding: 25px;">
						<!-- Categoría/Autor -->
						<div class="post-meta" style="font-size: 12px; color: #999; margin-bottom: 12px; text-transform: uppercase;">
							<?php if (is_category()) : ?>
								<?php echo esc_html(get_the_author()); ?>
							<?php elseif (is_author()) : ?>
								<?php echo get_the_date(get_option('date_format')); ?>
							<?php else : ?>
								<?php the_category(', '); ?> • <?php echo esc_html(get_the_date(get_option('date_format'))); ?>
							<?php endif; ?>
						</div>

						<!-- Título -->
						<h2 class="post-title" style="margin: 0 0 15px 0; font-size: 20px;">
							<a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none; transition: color 0.3s ease;">
								<?php the_title(); ?>
							</a>
						</h2>

						<!-- Excerpt -->
						<div class="post-excerpt" style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
							<?php echo wp_trim_words(get_the_excerpt(), 20); ?>
						</div>

						<!-- Button -->
						<a href="<?php the_permalink(); ?>" class="read-more-btn" 
							style="color: #667eea; text-decoration: none; font-weight: 600; display: inline-block; transition: color 0.3s ease;">
							<?php esc_html_e('Leer Artículo →', 'cursos-online-wp'); ?>
						</a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<!-- Paginación -->
		<div class="archive-pagination" style="text-align: center; margin-top: 60px;">
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
				<?php esc_html_e('No hay artículos', 'cursos-online-wp'); ?>
			</h2>
			<p style="color: #666; margin-bottom: 30px;">
				<?php esc_html_e('No se encontraron artículos en esta sección. Intenta explorar otras categorías.', 'cursos-online-wp'); ?>
			</p>
		</div>
	<?php endif; ?>

</main>
<!-- /wp:group -->

<?php get_footer(); ?>