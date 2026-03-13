<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <div class="logo">
                    <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
                </div>
                <nav class="main-nav">
                    <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-principal',
                            'container' => false,
                            'items_wrap' => '<ul>%3$s</ul>'
                        ));
                    ?>
                </nav>
            </div>
        </div>
    </header>
