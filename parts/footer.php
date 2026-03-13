<?php if (!defined('ABSPATH')) { exit; } ?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-info">
            <h4><?php bloginfo('name'); ?></h4>
            <p><?php esc_html_e('Propiedad de PCCurico.cl. Desarrollo por JCares.', 'cursos-online-wp'); ?></p>
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> - <?php esc_html_e('Todos los derechos reservados.', 'cursos-online-wp'); ?></p>
        </div>

        <div class="footer-social">
            <?php cursos_online_output_social_links(); ?>
        </div>
    </div>
</footer>
