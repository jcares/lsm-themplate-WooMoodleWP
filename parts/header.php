<?php if (!defined('ABSPATH')) { exit; } ?>
<header class="site-header">
    <div class="container header-grid">
        <div class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        </div>

        <nav class="main-nav">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'menu-principal',
                'container' => false,
                'items_wrap' => '<ul>%3$s</ul>',
            ));
            ?>
        </nav>

        <div class="header-actions">
            <a class="btn-aula-virtual" href="<?php echo esc_url(function_exists('cursos_online_aula_virtual_url') ? cursos_online_aula_virtual_url() : '#'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Aula Virtual', 'cursos-online-wp'); ?></a>
        </div>
    </div>
</header>
