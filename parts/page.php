<?php if (!defined('ABSPATH')) { exit; } ?>
<section class="page-section">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="page-article">
                <header class="page-title"><h1><?php the_title(); ?></h1></header>
                <div class="page-content"><?php the_content(); ?></div>
            </article>
        <?php endwhile; endif; ?>
    </div>
</section>
