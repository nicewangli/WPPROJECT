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

function my_first_theme_db_debug() {
	global $wpdb;
	echo '<div style="background:#f5f5f5; padding:20px; margin:20px; border:2px solid #333; font-family:monospace; font-size:13px;">';
    echo '<h2>🗄️ WP 数据架构调试面板</h2>';
	$latest_post = $wpdb->get_row("SELECT * FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
	echo '<h3>wp_posts表的最新文章：</h3>';
	echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">';
    echo '<tr style="background:#ddd;"><th>字段名</th><th>值</th></tr>';
    if ($latest_post) {
        foreach ($latest_post as $key => $value) {
            $display_value = strlen($value) > 100 ? urldecode(substr($value, 0, 100)) . '...' : urldecode($value);
            echo '<tr><td><strong>' . esc_html($key) . '</strong></td><td>' . esc_html($display_value) . '</td></tr>';
        }
    }
    echo '</table>';
	    if ($latest_post) {
        $post_meta = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->postmeta} WHERE post_id = %d",
            $latest_post->ID
        ));

        echo '<h3>📝 wp_postmeta 表（这篇文章的所有"便利贴"）</h3>';
        echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">';
        echo '<tr style="background:#ddd;"><th>meta_id</th><th>post_id</th><th>meta_key</th><th>meta_value</th></tr>';
        if ($post_meta) {
            foreach ($post_meta as $meta) {
                $display_value = strlen($meta->meta_value) > 100 ? substr($meta->meta_value, 0, 100) . '...' : $meta->meta_value;
                echo '<tr>';
                echo '<td>' . esc_html($meta->meta_id) . '</td>';
                echo '<td>' . esc_html($meta->post_id) . '</td>';
                echo '<td><strong>' . esc_html($meta->meta_key) . '</strong></td>';
                echo '<td>' . esc_html($display_value) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">这篇文章没有任何 postmeta 记录</td></tr>';
        }
        echo '</table>';

        $post_terms = $wpdb->get_results($wpdb->prepare(
            "SELECT t.name, t.slug, tt.taxonomy, tt.description
             FROM {$wpdb->term_relationships} AS tr
             INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
             INNER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
             WHERE tr.object_id = %d
             ORDER BY tt.taxonomy, t.name",
            $latest_post->ID
        ));

        echo '<h3>🏷️ 分类关联（三表联查结果）</h3>';
        echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%; background:#fff;">';
        echo '<tr style="background:#ddd;"><th>分类名(name)</th><th>别名(slug)</th><th>分类法(taxonomy)</th><th>描述</th></tr>';
        if ($post_terms) {
            foreach ($post_terms as $term) {
                echo '<tr>';
                echo '<td><strong>' . esc_html($term->name) . '</strong></td>';
                echo '<td>' . esc_html($term->slug) . '</td>';
                echo '<td>' . esc_html($term->taxonomy) . '</td>';
                echo '<td>' . esc_html($term->description) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">这篇文章没有关联任何分类或标签</td></tr>';
        }
        echo '</table>';
    }
	echo '</div>';
}
add_action('wp_footer','my_first_theme_db_debug');
//D7汇总，精选区域
//注册精选分类导航
function my_theme_register_featured_menu() {
    register_nav_menus(
        array(
            'featured-categories' => __('精选分类导航','my_first_theme')
        )
    );
}
add_action('after_setup_theme','my_theme_register_featured_menu');
//注册小工具
function my_theme_register_featured_sidebar() {
    register_sidebar(
        array(
            'name'  => '精选区域小工具',
            'id'  => 'featured-sidebar',
            'description'  => '在首页精选推荐区域显示的小工具',
            'before_widget'  => '<div id="%1$s" class="featured-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="featured-widget-title">',
            'after_title'  => '</h4>',
        )
    );
}
add_action('widgets_init','my_theme_register_featured_sidebar');
//大综合-精选区域推荐
function my_theme_render_featured_posts() {
    ?>
    <section class="featured-posts">
        <h2>
            <?php echo apply_filters('my_theme_featured_title','🔥 精选推荐') ?>
        </h2>
        <?php dynamic_sidebar('featured-sidebar') ?>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'featured-categories',
            'container' => 'nav',
            'menu_class' => 'featured-menu',
            'depth' => 1,
        )); 
         ?>
         <div class="featured-grid">
            <?php 
            $featured_query = new WP_Query(array(
                'posts_per_page' => 3,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            if ($featured_query->have_posts()) :
                while($featured_query->have_posts()) :
                $featured_query->the_post(); ?>
                    <article class="featured-item">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-thumb">
                                <?php 
                                    the_post_thumbnail('medium')
                                ?>
                            </div>
                            <?php endif; ?>
                            <h3>
                                <a href="<?php the_permalink();?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(),20,'...');?></p>
                    </article>
                <?php endwhile;
                wp_reset_postdata();
                        endif;    
                ?>    
            <?php
                    global $wpdb;
                    $post_count   = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'post'");
                    $category_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->terms} t INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category'");
                    ?>
                    <div class="featured-stats">
                        <span>📄 文章总数：<?php echo esc_html($post_count); ?> 篇</span>
                        <span>📂 分类总数：<?php echo esc_html($category_count); ?> 个</span>
                    </div>
         </div>
    </section>
    <?php 
}
add_action('my_theme_featured_posts','my_theme_render_featured_posts');
function my_theme_custom_featured_title($title) {
    return '⭐ ' . $title . ' ⭐';
}
add_filter('my_theme_featured_title', 'my_theme_custom_featured_title');