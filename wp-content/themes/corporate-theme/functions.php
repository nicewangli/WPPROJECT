<?php
/**
 * Corporate Theme 核心功能
 * 
 * @package corporate-theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) {
    exit;
}

function corporate_theme_setup() 
{
    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');
    
    add_theme_support('custom-logo', [
    'height'      => 60,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
    ]);

    add_theme_support('html5',[
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ]);
    register_nav_menu('primary',__('主菜单','corporate-theme'));
    register_nav_menu('footer',__('底部菜单','corporate-theme'));
}

add_action('after_setup_theme','corporate_theme_setup');

function corporate_enqueue_assets()
{
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        [],
        '5.3.3'
    );

    wp_enqueue_style(
        'bootstrap-icons',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
        [],
        '1.11.3'
    );

    wp_enqueue_style(
        'corporate-custom',
        get_template_directory_uri() . '/assets/css/custom.css',
        ['bootstrap'],
        '1.0.0'
    );

    wp_enqueue_script(
        'bootstrap-bundle',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.3',
        true
    );
}
add_action('wp_enqueue_scripts', 'corporate_enqueue_assets');

function corporate_nav_link_class($atts,$item,$args) 
{
    if(isset($args->theme_location) && $args->theme_location === 'primary') {
        $atts['class'] = isset($atts['class'])? $atts['class'] . ' nav-link' : 'nav-link';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes','corporate_nav_link_class',10,3);

function corporate_nav_li_class($classes, $item, $args)
{
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'corporate_nav_li_class', 10, 3);

function corporate_promo_banner() {
    echo '<div class="bg-warning text-center py-2 fw-bold">🎉 全场8折！</div>';
}
add_action('corporate_after_header', 'corporate_promo_banner');

//注册侧边栏
function corporate_register_sidebars()
{
    register_sidebar([
        'name' => __('主侧边栏','corporate-theme'),
        'id' => 'sidebar-main',
        'description' => __('博客文章页面的侧边栏区域','corporate-theme'),
        'before_widget' => '<div id="%1$s" class="widget card mb-4 %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h4 class="widget-title card-header">',
        'after_title'   => '</h4><div class="card-body">',
    ]);
    register_sidebar([
        'name'          => __('页脚小工具区', 'corporate-theme'),
        'id'            => 'sidebar-footer',
        'description'   => __('页脚的 Newsletter 订阅等小工具区域', 'corporate-theme'),
        'before_widget' => '<div id="%1$s" class="col-lg-4 col-md-6 mb-4 widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title text-uppercase">',
        'after_title'   => '</h5>',
    ]);
}
add_action('widgets_init', 'corporate_register_sidebars');

/**
 * 注册acf选项页 -- hero 区动态字段
 * 
 */
function corporate_acf_options_page()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => __('主题设置','corporate-theme'),
            'menu_title' => __('主题设置','corporate-theme'),
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_theme_options',
            'redirect' => false,
        ]);
    }
}
add_action('acf/init','corporate_acf_options_page');

/**
 * 注册作品集 cpt
 */
function corporate_register_portfolio_cpt()
{
    $labels = [
        'name'               => _x('作品集结', 'post type general name', 'corporate-theme'),
        'singular_name'      => _x('作品', 'post type singular name', 'corporate-theme'),
        'add_new'            => __('添加作品', 'corporate-theme'),
        'add_new_item'       => __('添加新作品', 'corporate-theme'),
        'edit_item'          => __('编辑作品', 'corporate-theme'),
        'view_item'          => __('查看作品', 'corporate-theme'),
        'search_items'       => __('搜索作品', 'corporate-theme'),
        'not_found'          => __('没有找到作品', 'corporate-theme'),
        'not_found_in_trash' => __('回收站中没有作品', 'corporate-theme'),
        'all_items'          => __('全部作品', 'corporate-theme'),
    ];
        $args = [
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'portfolio'],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'    => 'dashicons-portfolio',
        'show_in_rest' => true,
    ];

    register_post_type('portfolio', $args);
}
add_action('init', 'corporate_register_portfolio_cpt');

/**
 * 注册作品集分类法和标签
 */
function corporate_register_porfolio_taxonomies()
{
    //层级分类法：作品类型（portfolio_type）
    $type_labels = [
        'name'              => _x('作品类型', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('作品类型', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('搜索作品类型', 'corporate-theme'),
        'all_items'         => __('全部作品类型', 'corporate-theme'),
        'parent_item'       => __('父级类型', 'corporate-theme'),
        'parent_item_colon' => __('父级类型：', 'corporate-theme'),
        'edit_item'         => __('编辑作品类型', 'corporate-theme'),
        'update_item'       => __('更新作品类型', 'corporate-theme'),
        'add_new_item'      => __('添加新作品类型', 'corporate-theme'),
        'new_item_name'     => __('新作品类型名称', 'corporate-theme'),
    ];
    register_taxonomy('portfolio_type', 'portfolio', [
        'labels'       => $type_labels,
        'hierarchical' => true,       // 🔴 true = 像分类目录，有父子层级
        'rewrite'      => ['slug' => 'portfolio-type'],
        'show_in_rest' => true,
    ]);
    // 非层级标签：作品标签（portfolio_tag）
    $tag_labels = [
        'name'              => _x('作品标签', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('作品标签', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('搜索作品标签', 'corporate-theme'),
        'all_items'         => __('全部作品标签', 'corporate-theme'),
        'edit_item'         => __('编辑作品标签', 'corporate-theme'),
        'update_item'       => __('更新作品标签', 'corporate-theme'),
        'add_new_item'      => __('添加新作品标签', 'corporate-theme'),
        'new_item_name'     => __('新作品标签名称', 'corporat-theme'),
    ];

    register_taxonomy('portfolio_tag', 'portfolio', [
        'labels'       => $tag_labels,
        'hierarchical' => false,      // 🔴 false = 像标签，扁平的，没有父子关系
        'rewrite'      => ['slug' => 'portfolio-tag'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'corporate_register_porfolio_taxonomies');
/**
 * 计算文章阅读时间并显示
 * 
 * 挂载 the_content 过滤器上，在正文前输出
 * @param string $content 文章原始内容
 * @return string 追加阅读时间后的完整内容
 */
function corporate_reading_time($content) 
{
    //只在单篇文章页显示
    if (!is_single()) {
        return $content;
    }

    //获取当前文章内容，并去除html标签
    $plain_text = strip_tags($content);

    //用mb_strlen计算中文字数
    $word_count = mb_strlen($plain_text,'UTF-8');
    //中文阅读速度约 400字/分钟
    $minutes = ceil($word_count/400);
    //最少阅读1分钟
    if($minutes<1) {
        $minutes = 1;
    }
    // 构建阅读时间 HTML（放在正文前面）
    $reading_time_html = sprintf(
        '<div class="reading-time alert alert-info py-2 mb-4">
            <i class="bi bi-clock"></i>
            %s
        </div>',
        sprintf(
            /* translators: %d: 阅读分钟数 */
            esc_html__('阅读时间约 %d 分钟', 'corporate-theme'),
            $minutes
        )
    );

    // 把阅读时间拼接到正文前面
    return $reading_time_html . $content;
}
add_filter('the_content', 'corporate_reading_time', 10);

/**
 * 在文章末尾追加版权声明
 * 挂载the_content过滤器上，在正文后输出
 * @param string $content 
 * @return string
 */
function corporate_copyright_notice($content) 
{
    //只在单篇文章页显示
    if (!is_single()) {
        return $content;
    }
    $copyright_html = sprintf(
        '<div class="copyright-notice alert alert-warning mt-4 p-3">
            <i class="bi bi-c-circle"></i>
            <strong>%s</strong>
            <p class="mb-0 mt-1">
                %s
            </p>
        </div>',
        esc_html__('版权声明', 'corporate-theme'),
        sprintf(
            /* translators: 1: 站点名称, 2: 文章标题链接 */
            esc_html__('本文《%1$s》由 %2$s 发布，未经许可禁止转载。', 'corporate-theme'),
            esc_html(get_the_title()),
            '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_bloginfo('name')) . '</a>'
        )
    );

    return $content . $copyright_html;
}
add_filter('the_content', 'corporate_copyright_notice', 10);