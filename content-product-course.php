<?php
defined('ABSPATH') || exit;
global $product;

if (!$product || !$product->is_visible()) return;
?>

<div class="course-card">
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium'); ?>
        </a>
    <?php endif; ?>

    <div class="course-content">
        <h2 class="course-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>

        <div class="course-description">
            <?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?>
        </div>

        <div class="course-price">
            <?php echo $product->get_price_html(); ?>
        </div>

        <div class="course-buy-button">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>
</div>