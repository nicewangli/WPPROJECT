<?php
//加载样式表
function my_first_theme_enqueue_assets() {
    wp_enqueue_style('my-first-theme-style', get_stylesheet_uri(), array(), '1.0.0');
}
//修改主查询
function my_first_modify_main_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( is_home() ) {
			$query->set( 'posts_per_page', 5 );
		} elseif ( is_category() ) {
			$query->set( 'posts_per_page', 3 );
		}
	}
}

//分页导航
function my_first_theme_posts_pagination() {
	the_posts_pagination(
		array(
			'mid_size' => 1,
			'prev_text' => __('上一页','my_first_theme'),
			'next_text' => __('下一页','my_first_theme'),
		)
		);
}
//显示文章阅读时间
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

//添加文字banner
function my_theme_header_banner() {
	echo '<div class="header-banner">🌟 欢迎来到我的网站！</div>';
}

add_action('my_theme_after_header', 'my_theme_header_banner');

//自定义页脚文本
function my_theme_custom_footer_text( $text ) {
    $text .= ' | 用 ❤️ 和 WordPress 制作';
    return $text;
}
add_filter( 'my_theme_footer_text', 'my_theme_custom_footer_text' );

//主题功能注册
function my_first_theme_setup() {
	//注册缩略图
	add_theme_support('post-thumbnails');
	//注册自定义logo
	add_theme_support('custom-logo');
	//启动菜单功能
	add_theme_support('menus');

}
add_action('after_setup_theme', 'my_first_theme_setup');

//注册导航栏菜单位置
function my_first_theme_register_menus() {
	register_nav_menus(
		array(
			'primary' => __('主导航菜单','my_first_theme'),
			'footer' => __('页脚菜单','my_first_theme'),
		)
	);
}
add_action('after_setup_theme', 'my_first_theme_register_menus');

//注册侧边栏
function my_first_theme_register_sidebars() {
	register_sidebar(
		array(
			'name' => '主侧边栏',
			'id' => 'sidebar-main',
			'description' => '用于显示博客文章或页面的侧边栏内容',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		)
	);
}
add_action('widgets_init', 'my_first_theme_register_sidebars');

//注册页脚侧边栏小工具
function my_first_theme_register_footer_sidebar() {
	register_sidebar(
		array(
			'name' => '页脚侧边栏',
			'id' => 'footer-sidebar',
			'description' => '页脚侧边栏'
		)
	);
}
add_action('widgets_init','my_first_theme_register_footer_sidebar');

//恢复经典小工具界面
add_action('after_setup_theme', function() {
    remove_theme_support('widgets-block-editor');
});