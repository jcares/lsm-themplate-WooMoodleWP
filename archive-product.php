<?php
/**
 * Archive Product Template
 * Muestra listado de productos/cursos con filtros y paginación dinámicos
 */
defined('ABSPATH') || exit;

get_header();
?>

<!-- wp:group {"tagName":"main","layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group archive-product">
	
	<!-- Breadcrumbs -->
	<?php if (function_exists('woocommerce_breadcrumb')) : ?>
		<div class="elementor-container breadcrumb-container">
			<?php woocommerce_breadcrumb(); ?>
		</div>
	<?php endif; ?>

	<!-- Título y descripción del archivo -->
	<section class="archive-header elementor-container" style="padding: 40px 20px; text-align: center;">
		<h1 class="archive-title">
			<?php 
			if (is_product_category()) : 
				single_term_title(); 
			elseif (is_product_tag()) : 
				single_term_title(); 
			else : 
				esc_html_e('Productos Disponibles', 'cursos-online-wp'); 
			endif; 
			?>
		</h1>
		<?php 
		if (is_product_category() || is_product_tag()) {
			echo '<div class="archive-description">';
			echo term_description();
			echo '</div>';
		}
		?>
	</section>

	<!-- Filtros y ordenamiento -->
	<div class="elementor-container filter-bar" style="margin-bottom: 30px;">
		<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
			<?php if (function_exists('woocommerce_product_results_count')) : ?>
				<div class="woocommerce-results-count">
					<?php woocommerce_product_results_count(); ?>
				</div>
			<?php endif; ?>

			<?php if (function_exists('woocommerce_catalog_ordering')) : ?>
				<div class="woocommerce-ordering">
					<?php woocommerce_catalog_ordering(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<!-- Grid de productos -->
	<section class="elementor-container products-grid">
		<?php
		if (woocommerce_product_loop()) {
			$columns = intval(get_theme_mod('cursos_online_shop_columns', 3));
			$columns = min(6, max(1, $columns)); // Limita entre 1-6
			
			echo '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">';
			
			woocommerce_product_loop_start();

			if (wc_get_loop_prop('total')) {
				while (have_posts()) {
					the_post();
					?>
					<article class="product-item elementor-card" style="border-radius: 15px; overflow: hidden; transition: transform 0.3s ease;">
						<?php 
						// Imagen del producto
						if (has_post_thumbnail()) {
							echo '<div class="product-image" style="position: relative; overflow: hidden; height: 250px;">';
							the_post_thumbnail('woocommerce_thumbnail', ['style' => 'width: 100%; height: 100%; object-fit: cover;']);
							
							// Badge de descuento
							if (function_exists('wc_get_product')) {
								$product = wc_get_product(get_the_ID());
								if ($product instanceof WC_Product && $product->is_on_sale()) {
									echo '<span class="sale-badge" style="position: absolute; top: 15px; right: 15px; background: #ff6b6b; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">';
									echo esc_html__('Sale', 'cursos-online-wp');
									echo '</span>';
								}
							}
							echo '</div>';
						}
						?>
						
						<div class="product-info" style="padding: 20px;">
							<!-- Título del producto -->
							<h2 class="product-title" style="margin: 0 0 10px 0; font-size: 18px;">
								<a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;">
									<?php the_title(); ?>
								</a>
							</h2>

							<!-- Precio -->
							<?php if (function_exists('wc_get_product')) : ?>
								<?php 
								$product = wc_get_product(get_the_ID());
								if ($product) {
									echo '<div class="product-price" style="font-size: 20px; font-weight: bold; color: #667eea; margin-bottom: 15px;">';
									echo wp_kses_post($product->get_price_html());
									echo '</div>';
								}
								?>
							<?php endif; ?>

							<!-- Botón de compra -->
							<div class="product-button" style="display: flex; gap: 10px;">
								<?php 
								echo apply_filters('woocommerce_loop_add_to_cart_link',
									sprintf('<a href="%s" data-quantity="1" class="button product_type_simple add_to_cart_button" rel="nofollow">%s</a>',
										esc_url(get_permalink()),
										esc_html__('Ver Detalles', 'cursos-online-wp')
									),
									get_the_ID()
								);
								?>
							</div>
						</div>
					</article>
					<?php
				}
			}

			woocommerce_product_loop_end();
			echo '</div>';

			// Paginación
			echo '<div style="text-align: center; margin-top: 40px;">';
			the_posts_pagination([
				'mid_size'  => 2,
				'prev_text' => __('← Anterior', 'cursos-online-wp'),
				'next_text' => __('Siguiente →', 'cursos-online-wp'),
			]);
			echo '</div>';
		} else {
			// Sin productos
			echo '<div class="elementor-container empty-state" style="text-align: center; padding: 60px 20px;">';
			echo '<p>' . esc_html__('No se encontraron productos en este momento.', 'cursos-online-wp') . '</p>';
			echo '</div>';
		}
		?>
	</section>

</main>
<!-- /wp:group -->

<?php get_footer(); ?>