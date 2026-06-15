<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action('corporate_before_header'); ?>
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo esc_html(get_bloginfo('name')); ?>
                </a>
            <?php endif; ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primary-nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="primary-nav">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'navbar-nav ms-auto',
                    'fallback_cb'    => false,
                    'depth'          => 2,
                ]);
                ?>
            </div>
        </div>
    </nav>
</header>
<?php do_action('corporate_after_header'); ?>
<main id="content" class="site-content">
<?php do_action('corporate_before_content'); ?>