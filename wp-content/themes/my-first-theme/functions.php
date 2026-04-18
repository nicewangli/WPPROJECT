<?php
function my_first_theme_enqueue_assets() {
    wp_enqueue_style('my-first-theme-style', get_stylesheet_uri(), array(), '1.0.0');
}
function my_first_modify_main_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( is_home() ) {
			$query->set( 'posts_per_page', 5 );
		} elseif ( is_category() ) {
			$query->set( 'posts_per_page', 3 );
		}
	}
}

function my_first_theme_posts_pagination() {
	the_posts_pagination(
		array(
			'mid_size' => 1,
			'prev_text' => __('上一页','my_first_theme'),
			'next_text' => __('下一页','my_first_theme'),
		)
		);
}

add_action('pre_get_posts', 'my_first_modify_main_query');
add_action('wp_enqueue_scripts', 'my_first_theme_enqueue_assets');