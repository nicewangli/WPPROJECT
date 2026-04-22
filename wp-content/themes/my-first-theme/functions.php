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

function my_first_theme_display_reading_time($content) {
    if ( is_single() && is_main_query() ) {
        $post = get_post();
        $word_count = str_word_count(strip_tags($post->post_content));
        $reading_time = ceil($word_count / 300);
        $reading_time_html = '<div class="reading-time">';
        $reading_time_html .= '<span>📖 预计阅读时间：' . $reading_time . '分钟</span>';
        $reading_time_html .= '</div>';
        return $reading_time_html . $content;
    }
    return $content;
}

add_filter('the_content', 'my_first_theme_display_reading_time');

function my_first_theme_add_title_prefix($title) {
	if ( is_single() ) {
		return '【' . $title . '】';
	}
	return $title;
}


add_filter('the_title', 'my_first_theme_add_title_prefix');

add_action('pre_get_posts', 'my_first_modify_main_query');
add_action('wp_enqueue_scripts', 'my_first_theme_enqueue_assets');