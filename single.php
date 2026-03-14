<?php
/**
 * Single Template
 * Muestra artículos individuales/productos con metadata completa
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","right":"20px","bottom":"80px","left":"20px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<main class="wp-block-group single-content" style="padding-top: 80px; padding-right: 20px; padding-bottom: 80px; padding-left: 20px;">

	<?php if (have_posts()) : the_post(); ?>

		<!-- Portada/hero section con imagen destacada -->
		<?php if (has_post_thumbnail()) : ?>
			<div class="post-featured-image" style="margin-bottom: 40px; border-radius: 15px; overflow: hidden;">
				<?php the_post_thumbnail('full', ['style' => 'width: 100%; height: auto; display: block;']); ?>
			</div>
		<?php endif; ?>

		<!-- Metadatos: Autor, Fecha, Categorías -->
		<div class="post-metadata" style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap; font-size: 14px; color: #666;">
			<?php if (is_singular('post')) : ?>
				<div class="post-author">
					<strong><?php esc_html_e('Por:', 'cursos-online-wp'); ?></strong>
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
						<?php the_author(); ?>
					</a>
				</div>
				<div class="post-date">
					<strong><?php esc_html_e('Publicado:', 'cursos-online-wp'); ?></strong>
					<time datetime="<?php the_time('c'); ?>">
						<?php the_time(get_option('date_format')); ?>
					</time>
				</div>
				<div class="post-categories">
					<strong><?php esc_html_e('Categoría:', 'cursos-online-wp'); ?></strong>
					<?php the_category(', '); ?>
				</div>
				<div class="post-tags">
					<strong><?php esc_html_e('Etiquetas:', 'cursos-online-wp'); ?></strong>
					<?php the_tags('', ', ', ''); ?>
				</div>
			<?php elseif (is_singular('product')) : ?>
				<?php if (function_exists('wc_get_product')) : ?>
					<?php 
					$product = wc_get_product(get_the_ID());
					if ($product) : 
					?>
						<div class="product-price">
							<strong><?php esc_html_e('Precio:', 'cursos-online-wp'); ?></strong>
							<?php echo wp_kses_post($product->get_price_html()); ?>
						</div>
						<div class="product-stock">
							<strong><?php esc_html_e('Disponibilidad:', 'cursos-online-wp'); ?></strong>
							<?php echo $product->get_availability() ? $product->get_availability()['class'] : esc_html_e('No disponible', 'cursos-online-wp'); ?>
						</div>
						<div class="product-sku">
							<strong><?php esc_html_e('SKU:', 'cursos-online-wp'); ?></strong>
							<?php echo esc_html($product->get_sku()); ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<!-- Título -->
		<h1 class="post-title" style="font-size: 48px; margin: 30px 0 20px 0; line-height: 1.2;">
			<?php the_title(); ?>
		</h1>

		<!-- Contenido principal -->
		<article id="post-<?php the_ID(); ?>" <?php post_class('entry-content'); ?> 
			style="font-size: 18px; line-height: 1.8; color: #333; margin-bottom: 40px;">
			<?php the_content(); ?>
		</article>

		<!-- Navegación entre posts -->
		<?php if (is_singular('post')) : ?>
			<div class="post-navigation" style="display: flex; justify-content: space-between; margin: 40px 0; padding-top: 40px; border-top: 1px solid #eee;">
				<div class="prev-post" style="flex: 1;">
					<?php previous_post_link('%link', '← Artículo Anterior'); ?>
				</div>
				<div class="next-post" style="flex: 1; text-align: right;">
					<?php next_post_link('%link', 'Siguiente Artículo →'); ?>
				</div>
			</div>
		<?php endif; ?>

		<!-- Comentarios (solo para posts) -->
		<?php if (comments_open() || get_comments_number()) : ?>
			<section class="comments-section" style="margin-top: 60px; padding-top: 40px; border-top: 1px solid #eee;">
				<?php comments_template(); ?>
			</section>
		<?php endif; ?>

	<?php else : ?>

		<!-- Página no encontrada -->
		<div class="entry-content" style="text-align: center; padding: 60px 20px;">
			<h1><?php esc_html_e('Página no encontrada', 'cursos-online-wp'); ?></h1>
			<p><?php esc_html_e('Lo sentimos, el contenido que buscas no existe.', 'cursos-online-wp'); ?></p>
			<a href="<?php echo esc_url(home_url()); ?>" class="button" style="background: #667eea; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px;">
				<?php esc_html_e('Volver al Inicio', 'cursos-online-wp'); ?>
			</a>
		</div>

	<?php endif; ?>

</main>
<!-- /wp:group -->

<?php get_footer(); ?>