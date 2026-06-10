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
        '1.1.0'
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

/**
 * [cta] 短代码 —— 行动号召按钮
 * 
 * @param array $atts 用户传入的属性 
 * @param string $content 包裹内容 （[cta]内容[/cta]）
 * @return string 生成的html
 */
function corporate_cta_shortcode($atts,$content = null)
{
    // 合并默认属性
    $atts = shortcode_atts(
        [
            'title' => '点击咨询',
            'url'   => '#',
            'bg'    => '#0073aa',
            'color' => '#ffffff',
        ],
        $atts,
        'cta'
    );

    // 对每个输出进行安全转义
    // $safe_title = esc_html($atts['title']);
    $safe_url   = esc_url($atts['url']);
    $safe_bg    = esc_attr($atts['bg']);
    $safe_color = esc_attr($atts['color']);
    // 处理包裹内容：如果用户写了 [cta]内容[/cta]，优先用 $content
    if (!empty(trim($content))) {
        // 先从实体解码，再允许安全HTML标签
        $safe_title = wp_kses_post(html_entity_decode(trim($content)));
    } else {
        $safe_title = esc_html($atts['title']);
    }
    // 构建 HTML（用 sprintf 拼接）
    $html = sprintf(
        '<div class="cta-wrapper text-center my-4 p-4" style="background-color:%s;border-radius:8px;">
            <a href="%s" class="btn btn-light btn-lg fw-bold" style="color:%s;">
                %s
            </a>
        </div>',
        $safe_bg,
        $safe_url,
        $safe_color,
        $safe_title
    );

    return $html;
}
add_shortcode('cta','corporate_cta_shortcode');

/**
 * 自定义小工具：公司联系信息
 */
class Corporate_Contact_Widget extends WP_Widget
{
    /**
     * 1. 构造函数：注册小工具基本信息
     */
    public function __construct()
    {
        // 先空着，下一步填写
        parent::__construct(
            'corporate_contact_widget',          // $id_base — 小工具的唯一 ID
            __('公司联系信息', 'corporate-theme'), // $name   — 后台显示的名称
            [
                'description' => __('显示公司电话、邮箱、地址等联系信息', 'corporate-theme'),
            ]
        );
    }

    /**
     * 2. 前台输出：在网页上渲染 HTML
     *
     * @param array $args     register_sidebar() 定义的包裹 HTML
     * @param array $instance 后台保存的数据
     */
    public function widget($args, $instance)
    {
        // 提取包裹 HTML（来自 register_sidebar() 的定义）
        echo $args['before_widget'];
        // 输出标题（如果有的话）
        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
        }
        // 开始输出联系信息内容
        echo '<div class="contact-widget-content">';
        // 公司名称
        if (!empty($instance['company'])) {
            echo '<p class="contact-company fw-bold mb-2">' . esc_html($instance['company']) . '</p>';
        }
        // 电话
        if (!empty($instance['phone'])) {
            echo '<p class="contact-phone mb-1"><i class="bi bi-telephone"></i> '
                . esc_html($instance['phone']) . '</p>';
        }

        // 邮箱
        if (!empty($instance['email'])) {
            echo '<p class="contact-email mb-1"><i class="bi bi-envelope"></i> '
                . esc_html($instance['email']) . '</p>';
        }

        // 地址
        if (!empty($instance['address'])) {
            echo '<p class="contact-address mb-0"><i class="bi bi-geo-alt"></i> '
                . esc_html($instance['address']) . '</p>';
        }

        echo '</div>';

        // 闭合包裹 HTML
        echo $args['after_widget'];
    }

    /**
     * 3. 后台表单：管理员看到的设置界面
     *
     * @param array $instance 当前保存的数据
     */
    public function form($instance)
    {
        // 先空着，下一步填写
        // 从 $instance 提取当前保存的值，没有则用默认值
        $title   = !empty($instance['title'])   ? $instance['title']   : __('联系我们', 'corporate-theme');
        $company = !empty($instance['company']) ? $instance['company'] : '';
        $phone   = !empty($instance['phone'])   ? $instance['phone']   : '';
        $email   = !empty($instance['email'])   ? $instance['email']   : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php esc_html_e('标题：', 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr($title); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('company'); ?>">
                <?php esc_html_e('公司名称：', 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('company'); ?>"
                   name="<?php echo $this->get_field_name('company'); ?>"
                   value="<?php echo esc_attr($company); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('phone'); ?>">
                <?php esc_html_e('电话：', 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('phone'); ?>"
                   name="<?php echo $this->get_field_name('phone'); ?>"
                   value="<?php echo esc_attr($phone); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>">
                <?php esc_html_e('邮箱：', 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('email'); ?>"
                   name="<?php echo $this->get_field_name('email'); ?>"
                   value="<?php echo esc_attr($email); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('address'); ?>">
                <?php esc_html_e('地址：', 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('address'); ?>"
                   name="<?php echo $this->get_field_name('address'); ?>"
                   value="<?php echo esc_attr($address); ?>"
                   class="widefat">
        </p>

        <?php
    }

    /**
     * 4. 数据更新：保存时清理数据
     *
     * @param array $new_instance 用户新提交的数据
     * @param array $old_instance 旧数据
     * @return array 清理后的数据
     */
    public function update($new_instance, $old_instance)
    {
        // 先空着，下一步填写
        $instance = [];

        $instance['title']   = sanitize_text_field($new_instance['title']);
        $instance['company'] = sanitize_text_field($new_instance['company']);
        $instance['phone']   = sanitize_text_field($new_instance['phone']);
        $instance['email']   = sanitize_text_field($new_instance['email']);
        $instance['address'] = sanitize_text_field($new_instance['address']);

        return $instance;
    }
}
// 注册自定义小工具
function corporate_register_widgets()
{
    register_widget('Corporate_Contact_Widget');
}
add_action('widgets_init', 'corporate_register_widgets');

/**
 * 注册主题定制器设置
 * 
 * @param WP_Customize_Manager $wp_customize 定制器管理器对象
 */
function corporate_customize_register($wp_customize)
{
    // ========== 第1个分区：公司颜色 ==========
    $wp_customize->add_section('corporate_colors', [
        'title'       => __('公司颜色', 'corporate-theme'),
        'description' => __('自定义网站的主色调和辅助颜色', 'corporate-theme'),
        'priority'    => 30,
    ]);
        // ========== 主色调颜色 ==========
    $wp_customize->add_setting('primary_color', [
        'default'           => '#0d6efd',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'primary_color',
        [
            'label'    => __('主色调颜色', 'corporate-theme'),
            'section'  => 'corporate_colors',
            'settings' => 'primary_color',
        ]
    ));
    // ========== 辅助颜色 ==========
    $wp_customize->add_setting('secondary_color', [
        'default'           => '#6c757d',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'secondary_color',
        [
            'label'    => __('辅助颜色', 'corporate-theme'),
            'section'  => 'corporate_colors',
            'settings' => 'secondary_color',
        ]
    ));
    // ========== 第2个分区：首页 Hero ==========
    $wp_customize->add_section('corporate_hero', [
        'title'       => __('首页 Hero', 'corporate-theme'),
        'description' => __('自定义首页英雄区域的标题文字', 'corporate-theme'),
        'priority'    => 35,
    ]);

    // Hero 标题
    $wp_customize->add_setting('hero_title', [
        'default'           => __('欢迎来到我们的公司', 'corporate-theme'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('hero_title', [
        'label'    => __('Hero 标题', 'corporate-theme'),
        'section'  => 'corporate_hero',
        'settings' => 'hero_title',
        'type'     => 'text',
    ]);

    // Hero 副标题
    $wp_customize->add_setting('hero_subtitle', [
        'default'           => __('我们致力于提供最优质的服务', 'corporate-theme'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('hero_subtitle', [
        'label'    => __('Hero 副标题', 'corporate-theme'),
        'section'  => 'corporate_hero',
        'settings' => 'hero_subtitle',
        'type'     => 'text',
    ]);
    // ========== 第3个分区：页脚设置 ==========
    $wp_customize->add_section('corporate_footer', [
        'title'       => __('页脚设置', 'corporate-theme'),
        'description' => __('自定义页脚版权文字', 'corporate-theme'),
        'priority'    => 40,
    ]);

    // 版权文字
    $wp_customize->add_setting('footer_copyright', [
        'default'           => __('&copy; 2026 公司名称。保留所有权利。', 'corporate-theme'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('footer_copyright', [
        'label'    => __('版权文字', 'corporate-theme'),
        'section'  => 'corporate_footer',
        'settings' => 'footer_copyright',
        'type'     => 'text',
    ]);
}
/**
 * 输出主题定制器的动态 CSS
 * 挂钩到 wp_head，以内联 <style> 形式输出
 */
function corporate_customizer_css()
{
    // 读取设置值，第二个参数是默认值（兜底）
    $primary_color   = get_theme_mod('primary_color', '#0d6efd');
    $secondary_color = get_theme_mod('secondary_color', '#6c757d');
    ?>
    <style id="corporate-customizer-css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
        }
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }
        .text-muted {
            color: var(--secondary-color) !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'corporate_customizer_css', 100);

add_action('customize_register', 'corporate_customize_register');