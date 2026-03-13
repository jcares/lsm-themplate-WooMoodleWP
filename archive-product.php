<?php
defined('ABSPATH') || exit;
get_header('shop');
?>

<div class="container">
    <div class="woocommerce-products-header">
        <h1 class="page-title">Cursos Disponibles</h1>
        <p>Elige el curso que se adapte a tus necesidades y comienza a aprender hoy mismo</p>
    </div>

    <div class="woocommerce-shop-wrapper">
        <!-- Barra lateral -->
        <aside class="sidebar-shop">
            <?php dynamic_sidebar('sidebar-shop'); ?>
        </aside>

        <!-- Listado de cursos -->
        <main class="shop-main">
            <?php if (woocommerce_product_loop()) : ?>
                <div class="course-grid">
                    <?php
                        woocommerce_product_loop_start();
                        if (wc_get_loop_prop('total')) :
                            while (have_posts()) : the_post();
                                wc_get_template_part('content', 'product-course');
                            endwhile;
                        endif;
                        woocommerce_product_loop_end();
                    ?>
                </div>

                <?php do_action('woocommerce_after_shop_loop'); ?>
            <?php else : ?>
                <p>No hay cursos disponibles en este momento</p>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php get_footer('shop'); ?>