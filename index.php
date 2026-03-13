<?php get_header(); ?>

<section class="hero-inicio">
    <div class="container">
        <h1>Bienvenido a Nuestra Plataforma de Cursos</h1>
        <p>Aprende habilidades prácticas con instructores expertos y alcanza tus metas profesionales</p>
        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn-principal">Ver Todos los Cursos</a>
    </div>
</section>

<?php get_footer(); ?>
