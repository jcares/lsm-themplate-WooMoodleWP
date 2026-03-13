<?php get_header(); ?>

<div class="container">
    <h1><?php post_type_archive_title(); ?></h1>

    <?php if (have_posts()) : ?>
        <div class="archive-listing">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('archive-item'); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="archive-excerpt"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>

            <?php the_posts_pagination(); ?>
        </div>
    <?php else: ?>
        <p><?php esc_html_e('No se encontraron resultados.', 'cursos-online-wp'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>