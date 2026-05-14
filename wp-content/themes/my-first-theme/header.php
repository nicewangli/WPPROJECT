<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    ...
    <!-- 自定义logo -->
    <!-- <?php if ( has_custom_logo() ) : the_custom_logo(); endif; ?> -->
    <!-- 自定义logo结束 -->
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php 
    $announcement = get_field('announcement_text','option');
    $phone = get_field('contact_phone','option');
    if($announcement || $phone) : ?>
    <div class="site-announcement">
        <?php if($announcement) :?>
            <span class="announcement-text">📢 <?php echo esc_html( $announcement ); ?></span>
        <?php endif; ?>
        <?php if ( $phone ) : ?>
            <span class="announcement-phone">📞 <?php echo esc_html( $phone ); ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php wp_nav_menu( 
    array(
        'theme_location' => 'primary',
        'container' => 'nav',
        'menu_class' => 'primary-menu',
    )
); ?>
<?php do_action('my_theme_after_header'); ?>