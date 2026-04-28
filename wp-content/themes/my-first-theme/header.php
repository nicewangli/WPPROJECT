<!DOCTYPE html>
<head>
    ...
    <!-- 自定义logo -->
    <?php if ( has_custom_logo() ) : the_custom_logo(); endif; ?>
    <!-- 自定义logo结束 -->
    <?php wp_head(); ?>
</head>
<?php wp_nav_menu( 
    array(
        'theme_location' => 'primary',
        'container' => 'nav',
        'menu_class' => 'primary-menu',
    )
); ?>
<?php do_action('my_theme_after_header'); ?>