<?php
/**
 * Page Template
 * Muestra páginas estáticas con soporte para sidebar, comentarios y contenido completo
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- wp:group {"style":{"spacing":{"padding":{"top":"80px","right":"20px","bottom":"80px","left":"20px"}}},"layout":{"type":"constrained","contentSize":"80%"}} -->
<main class="wp-block-group page-wrapper" style="padding-top: 80px; padding-right: 20px; padding-bottom: 80px; padding-left: 20px;">

	<?php if (have_posts()) : the_post(); ?>

		<!-- Portada con imagen destacada (opcional) -->
		<?php if (has_post_thumbnail()) : ?>
			<div class="page-featured-image" style="margin-bottom: 40px; border-radius: 15px; overflow: hidden; max-height: 500px;">
				<?php the_post_thumbnail('full', ['style' => 'width: 100%; height: auto; display: block; object-fit: cover;']); ?>
			</div>
		<?php endif; ?>

		<!-- Título de la página -->
		<h1 class="page-title" style="font-size: 48px; margin: 30px 0 20px 0; line-height: 1.2;">
			<?php the_title(); ?>
		</h1>

		<!-- Contenido principal -->
		<article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> 
			style="font-size: 18px; line-height: 1.8; color: #333; margin-bottom: 40px;">
			<?php the_content(); ?>
		</article>

		<!-- Soporte para galerías y bloques personalizados -->
		<?php if (shortcode_exists('gallery')) : ?>
			<div class="page-galleries" style="margin: 40px 0;">
				<!-- Las galerías del editor se mostrarán arriba -->
			</div>
		<?php endif; ?>

		<!-- Comentarios (si están habilitados) -->
		<?php if (comments_open() || get_comments_number()) : ?>
			<section class="comments-section" style="margin-top: 60px; padding-top: 40px; border-top: 1px solid #eee;">
				<?php comments_template(); ?>
			</section>
		<?php endif; ?>

	<?php else : ?>

		<!-- Página no encontrada -->
		<div class="entry-content" style="text-align: center; padding: 60px 20px;">
			<h1><?php esc_html_e('Página no encontrada', 'cursos-online-wp'); ?></h1>
			<p><?php esc_html_e('Lo sentimos, la página que buscas no existe.', 'cursos-online-wp'); ?></p>
			<a href="<?php echo esc_url(home_url()); ?>" class="button" style="background: #667eea; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 20px;">
				<?php esc_html_e('Volver al Inicio', 'cursos-online-wp'); ?>
			</a>
		</div>

	<?php endif; ?>

</main>
<!-- /wp:group -->

<?php get_footer(); ?>