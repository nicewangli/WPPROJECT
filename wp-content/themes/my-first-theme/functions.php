<?php
function my_first_theme_enqueue_assets() {
    wp_enqueue_style('my-first-theme-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'my_first_theme_enqueue_assets');