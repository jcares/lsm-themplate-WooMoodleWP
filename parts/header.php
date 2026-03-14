<?php if (!defined('ABSPATH')) { exit; } ?>
<header class="header-sec">
    <div class="elementor-container">
        <div class="header-row">
            <div class="logo-box">
                <?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
                    <div class="site-logo"><?php the_custom_logo(); ?></div>
                <?php endif; ?>
                <div class="site-branding">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title"><?php bloginfo('name'); ?></a>
                    <p class="site-tagline"><?php bloginfo('description'); ?></p>
                </div>
            </div>

            <div class="menu-box">
                <nav class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu-principal',
                        'container' => false,
                        'menu_class' => 'main-nav-list',
                        'fallback_cb' => false,
                    ));
                    ?>
                </nav>
            </div>

            <div class="top-right-col">
                <div class="search-box"><?php get_search_form(); ?></div>
                <div class="header-btns">
                    <a class="header-btn dashboard-btn" href="<?php echo esc_url(home_url('/wp-admin')); ?>"><?php esc_html_e('Dashboard', 'cursos-online-wp'); ?></a>
                    <a class="header-btn login-btn" href="<?php echo esc_url(wp_login_url()); ?>"><?php esc_html_e('Login', 'cursos-online-wp'); ?></a>
                    <a class="header-btn aula-btn" href="<?php echo esc_url(cursos_online_aula_virtual_url()); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Aula Virtual', 'cursos-online-wp'); ?></a>
                </div>
            </div>
        </div>
    </div>
</header>
