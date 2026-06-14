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
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
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

// ========== D36 - WooCommerce 钩子测试 ==========
// 在商品摘要下方插入自定义信息块
add_action('woocommerce_single_product_summary','corporate_add_product_extra_info',25);
function corporate_add_product_extra_info()
{
    //安全判断：只在商品单页显示
    if (!is_product()) {
        return;
    }
    echo '<div class="product-extra-info mt-3 p-3 bg-light rounded">';
    echo '<p class="mb-1"><strong>📦 预计发货：</strong>下单后 2 个工作日内发货</p>';
    echo '<p class="mb-0"><strong>💳 支付方式：</strong>支持支付宝 / 微信 / 银行转账</p>';
    echo '</div>';
}
// ==========================================
// WooCommerce 自定义钩子
// ==========================================
/**
 * 在所有商品详情页标题上方显示促销横幅
 * 钩子：woocommerce_before_single_product
 * 优先级：10（默认），越小越先执行
 */
function corporate_woo_promo_banner() {
    echo '<div class="alert alert-info text-center mb-3">';
    echo '🎉 全场满 <strong>￥299</strong> 包邮！';
    echo '</div>';
}
add_action('woocommerce_before_single_product', 'corporate_woo_promo_banner');
// ==========================================
// D38：商品详情页布局重排
// ==========================================
/**
 * 重排商品摘要区域（woocommerce_single_product_summary）的板块顺序
 * 
 * 默认顺序（按优先级）：
 *   标题(5) → 价格(10) → 短描述(15) → 加购按钮(20) → 元信息(30) → 分享(40)
 * 
 * 改为：
 *   标题(5) → 价格(10) → 加购按钮(20) → 短描述(25) → 元信息(30)
 * 
 * 原理：
 *   1. 先把短描述从默认的优先级 15 上移除
 *   2. 再把它挂到优先级 25 上（即加购按钮之后）
 */
function corporate_reorder_product_summary()
{
    //第一步：移除默认挂在优先级15上的短描述
    remove_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_excerpt',15
    );
    // 第2步：把短描述挂回同一个钩子，但改为优先级 25（在加购按钮 20 之后）
    add_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_excerpt',
        25
    );
    remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
    add_action('woocommerce_single_product_summary','woocommerce_template_single_title',35);
}
add_action('wp', 'corporate_reorder_product_summary');
// ==========================================
// D38：ACF 商品附加信息 —— 在商品摘要区输出
// ==========================================
/**
 * 在商品摘要区显示 ACF 自定义字段
 * 
 * 挂载到 woocommerce_single_product_summary 钩子
 * 优先级 27（在短描述 25 之后、元信息 30 之前）
 */
function corporate_display_product_acf_fields()
{
    //安全检查，只在商品单页执行
    if (!is_product()) {
        return;
    }
    //获取当前商品id
    $product_id = get_the_ID();
    //用get_field()读取acf字段值
    //用第二个参数必须传入当前文章/页面的id
    $material = get_field('material',$product_id);
    $care_instructions = get_field('care_instructions',$product_id);
    $designer_note = get_field('designer_note',$product_id);

    // 如果三个字段都为空，就不输出任何内容
    if ( empty( $material ) && empty( $care_instructions ) && empty( $designer_note ) ) {
        return;
    }
    // 开始构建输出 HTML
    echo '<div class="product-acf-fields mt-4 p-3 border rounded bg-light">';
    echo '<h5 class="mb-3 fw-bold">' . esc_html__( '商品附加信息', 'corporate-theme' ) . '</h5>';

    // 字段1：工艺材质（纯文本 → esc_html）
    if ( ! empty( $material ) ) {
        echo '<p class="mb-2">';
        echo '<strong>' . esc_html__( '工艺材质：', 'corporate-theme' ) . '</strong> ';
        echo esc_html( $material );
        echo '</p>';
    }

    // 字段2：使用说明（文本域 → 允许换行，用 nl2br + esc_html）
    if ( ! empty( $care_instructions ) ) {
        echo '<p class="mb-2">';
        echo '<strong>' . esc_html__( '使用说明：', 'corporate-theme' ) . '</strong><br>';
        echo nl2br( esc_html( $care_instructions ) );
        echo '</p>';
    }

    // 字段3：设计师手记（WYSIWYG → 可能有 HTML 标签，用 wp_kses_post）
    if ( ! empty( $designer_note ) ) {
        echo '<div class="designer-note mt-3">';
        echo '<strong>' . esc_html__( '设计师手记：', 'corporate-theme' ) . '</strong>';
        echo '<div class="mt-1">' . wp_kses_post( $designer_note ) . '</div>';
        echo '</div>';
    }

    echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'corporate_display_product_acf_fields', 27 );
// ==========================================
// D38：相关商品推荐定制
// ==========================================
/**
 * 修改相关商品数量 + 排列列数
 * 
 * 默认显示 4 个商品、4 列排列
 * 改为显示 3 个商品、3 列排列
 * 
 * woocommerce_output_related_products_args 是一个过滤器
 * 专门用来修改 woocommerce_output_related_products() 函数的参数
 * 
 * @param array $args 默认参数
 * @return array 修改后的参数
 */
function corporate_customize_related_products($args) 
{
    //posts_per_page -> 显示多少个相关商品
    // columns -> 每行几列
    $args['posts_per_page'] = 2;
    $args['columns'] = 2;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'corporate_customize_related_products' );
// ==========================================
// D39：商店页 — 商品列数 + 每页数量控制
// ==========================================
/**
 * 控制商品存档页每行显示几列
 * 
 * 钩子：loop_shop_columns（过滤器）
 * 
 * 默认值：4（4列网格）
 * 参数 $columns：当前列数（整数）
 * 返回值：修改后的列数
 * 
 * WC 内部会根据这个返回值给每个商品 li 加上对应的 class
 * 比如 3 列 → .products.columns-3
 * 然后 WC 自带的 CSS 自动适配宽度为 33.333%
 */
function corporate_shop_columns($columns) 
{
    // 把默认的 4 列改为 3 列
    // 适合企业主题的宽版布局，商品卡片更大气
    return 3;
}
add_filter('loop_shop_columns', 'corporate_shop_columns');
/**
 * 控制每个存档页显示多少个商品
 * 
 * 钩子：loop_shop_per_page（过滤器）
 * 
 * 默认值：12（由 WC 后台设置 → 显示 → 每页显示数量）
 * 如果我们在这个过滤器中返回值，会覆盖后台设置
 * 
 * @param int $per_page 当前每页数量
 * @return int 修改后的每页数量
 */
function corporate_shop_per_page($per_page) 
{
    // 改为每页显示 9 个商品
    // 3 列 × 3 行 = 9 个，刚好铺满一屏
    return 9;
}
add_filter('loop_shop_per_page', 'corporate_shop_per_page');
// ==========================================
// D39：自定义商品排序选项
// ==========================================
/**
 * 修改商品排序下拉框的选项
 * 
 * 钩子：woocommerce_catalog_orderby（过滤器）
 * 
 * 默认选项：
 *   menu_order  → 默认排序
 *   popularity  → 按热度排序
 *   rating      → 按评分排序
 *   date        → 按最新排序
 *   price       → 按价格从低到高
 *   price-desc  → 按价格从高到低
 * 
 * @param array $orderby 排序选项数组
 * @return array 修改后的排序选项
 */
function corporate_custom_orderby($orderby) 
{
    // 移除"按评分排序"（如果你的商品没有评分功能）
    unset($orderby['rating']);
    
    // 在数组末尾添加一个自定义选项
    $orderby['discount'] = __('按折扣排序', 'corporate-theme');

    return $orderby;
}
add_filter('woocommerce_catalog_orderby', 'corporate_custom_orderby');
/**
 * 让自定义排序选项（discount）真正生效
 * 
 * 钩子：woocommerce_get_catalog_ordering_args（过滤器）
 * 
 * 当用户在下拉框中选择"按折扣排序"时，WC 会触发这个钩子
 * 我们需要告诉 WC：用什么字段排序、升序还是降序
 * 
 * @param array $args WC 内部排序参数
 * @return array 修改后的排序参数
 */
function corporate_discount_ordering_args($args) 
{
    // 获取当前的排序方式
    // $_GET 参数 'orderby' 就是用户在页面上选择的值
    $orderby_value = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';

    if ('discount' === $orderby_value) {
        // 按自定义的 '_sale_price' meta 字段排序
        // meta_key：要排序的自定义字段名
        // order：DESC（降序，折扣越大越靠前）
        // meta_type：'NUMERIC'（数值比较，不是字符串）
        $args['meta_key'] = '_sale_price';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    }

    return $args;
}
add_filter('woocommerce_get_catalog_ordering_args', 'corporate_discount_ordering_args');

/**
 * Block 1：购物车页面顶部 - 满额优惠提示
 * 
 * 挂载 woocommerce_before_cart 动作钩子
 * WC()->cart->subtotal 获取当前购物车小计
 * WC()->cart->total 获取总价（含税含运费）
 */
function corporate_cart_notice()
{
    // 只有购物车页面才显示
    if ( ! is_cart() ) {
        return;
    }

    // 获取购物车小计 （不含运费）
    $subtotal = WC()->cart->subtotal;
    //满300元门槛
    $free_shipping_threshold = 300;
    // 如果小计<300,显示差多少免运费
    if ($subtotal < $free_shipping_threshold) {
        $remaining = $free_shipping_threshold - $subtotal;
        echo '<div class="woocommerce-message" style="background:#f0f8ff;padding:12px 20px;margin-bottom:20px;border-left:4px solid #0073aa;">';
        echo '🎉 再购 <strong>' . wc_price( $remaining ) . '</strong> 即可享受包邮！';
        echo '</div>';
    } else {
        echo '<div class="woocommerce-message" style="background:#e8f5e9;padding:12px 20px;margin-bottom:20px;border-left:4px solid #4caf50;">';
        echo '✅ 您已满足包邮条件！';
        echo '</div>';
    }
}
add_action('woocommerce_before_cart','corporate_cart_notice');
/**
 * Block 2：精简结算页账单字段
 * 
 * 挂载 woocommerce_checkout_fields 过滤器
 * 接收一个三维数组，返回修改后的数组
 * 
 * @param array $fields WooCommerce 结算字段数组
 * @return array
 */
function corporate_customize_checkout_fields( $fields ) {
    // 1. 删除"公司名"字段（用 unset 移除数组元素）
    // unset( $fields['billing']['billing_company'] );
    //更新"公司名"字段，将他排在最后输出
    $fields['billing']['billing_company']['priority']=99999;
    // 2. 把"电话"改为非必填（默认是必填的）
    $fields['billing']['billing_phone']['required'] = false;

    // 3. 把"地址2"的标签改为中文"楼层/门牌号"
    $fields['billing']['billing_address_2']['label'] = '楼层/门牌号';

    // 4. 给"邮箱"字段加一个占位提示文字
    $fields['billing']['billing_email']['placeholder'] = 'example@company.com';

    // 5. 给"订单备注"调整标签
    $fields['order']['order_comments']['label'] = '订单备注（选填）';

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'corporate_customize_checkout_fields' );
/**
 * Block 3：自定义运费 — 满200免运费，不满200收15元
 * 
 * 挂载 woocommerce_package_rates 过滤器
 * 在 WC 输出运费之前，修改所有可用运费费率
 * 
 * @param array  $rates   可用运费费率数组
 * @param array  $package 当前包裹信息（含商品、目的地等）
 * @return array
 */
function corporate_custom_shipping_rates( $rates, $package ) {
    // 获取购物车小计（WC()->cart 是全局购物车对象）
    $subtotal = WC()->cart->subtotal;

    // 门槛：满200免运费
    $free_threshold = 200;
    foreach ( $rates as $rate_id => $rate ) {
        // $rate 是 WC_Shipping_Rate 对象
        // $rate->label 是费率名称（比如"Flat Rate"）
        // $rate->cost  是费率价格
        if ( $subtotal >= $free_threshold ) {
            // 满200：把运费设为 0
            $rates[ $rate_id ]->cost = 0;
            // 改标签名：显示"免费送货"
            $rates[ $rate_id ]->label = '免费送货';
        } else {
            // 不满200：改固定运费 15 元
            $rates[ $rate_id ]->cost = 15;
            // 改标签名：显示"标准配送 ¥15.00"
            $rates[ $rate_id ]->label = '标准配送';
        }
        
        // 处理税费（如果有按税率计算的话，这里简单清空 taxes）
        $rates[ $rate_id ]->taxes = [];
    }

    return $rates;
}
add_filter( 'woocommerce_package_rates', 'corporate_custom_shipping_rates', 10, 2 );

/**
 * Block 6：货到付款（COD）额外加收 10 元手续费
 * 
 * 挂载 woocommerce_cart_calculate_fees 动作钩子
 * 判断用户是否选择了 COD 支付方式，是则加收 10 元
 * 
 * @param WC_Cart $cart 购物车对象
 */
function corporate_cod_fee( $cart ) {
    // 防止后台重复执行
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // 空车不加
    if ( $cart->get_cart_contents_count() === 0 ) {
        return;
    }

    // 从 WC Session 获取用户选择的支付方式
    // 'cod' 是 WooCommerce 货到付款的默认 method ID
    $chosen_payment = WC()->session->get( 'chosen_payment_method' );

    // 只有在结算页用户选了"货到付款"才加收
    if ( $chosen_payment === 'cod' ) {
        $cart->add_fee(
            __( '货到付款手续费', 'corporate-theme' ),  // 名称
            10,                                           // 金额 10 元
            false                                         // 是否计税
        );
    }
}
add_action( 'woocommerce_cart_calculate_fees', 'corporate_cod_fee' );
/**
 * 清除运费缓存（开发调试用）
 * 每次更新购物车时强制刷新运费
 * 上线后可注释掉以提高性能
 */
function corporate_clear_shipping_cache() {
    if ( is_cart() || is_checkout() ) {
        $packages = WC()->cart->get_shipping_packages();
        foreach ( $packages as $key => $package ) {
            WC()->session->set( 'shipping_for_package_' . $key, null );
        }
    }
}
/**
 * Block 5：满500打9折
 * 
 * 挂载 woocommerce_before_calculate_totals 动作钩子
 * 遍历购物车每件商品，把价格 ×0.9
 * 
 * @param WC_Cart $cart 购物车对象
 */
function corporate_discount($cart) {
    // 防止后台重复执行
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    // 获取购物车小计（看原始总价是否 ≥ 500）
    $subtotal = $cart->subtotal;    // ← 要点2：用 $cart->subtotal 而不是 WC()->cart->subtotal

    if($subtotal>=500) {
        // 要点3：遍历购物车每件商品
        foreach ( $cart->get_cart() as $cart_item ) {
            // $cart_item['data'] 是 WC_Product 对象
            // 获取原始价格
            $original_price = $cart_item['data']->get_price();
            // 打9折
            $new_price = $original_price * 0.9;
            // 设置新价格（WC 会自动重新算总价）
            $cart_item['data']->set_price( $new_price );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'corporate_discount' );
// add_action( 'woocommerce_before_calculate_totals', 'corporate_clear_shipping_cache' );
/**
 * Block 4：加收包装处理费
 * 
 * 挂载 woocommerce_cart_calculate_fees 动作钩子
 * 用 WC()->cart->add_fee() 方法添加自定义费用
 * 
 * @param WC_Cart $cart 购物车对象（注意：这里是传引用！）
 */
function corporate_add_packaging_fee( $cart ) {
    // 确保只在主购物车计算时执行，避免重复调用
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // 只有购物车有商品才加费用
    if ( $cart->get_cart_contents_count() === 0 ) {
        return;
    }

    // 包装处理费 5 元
    $fee_amount = 5;

    // WC()->cart->add_fee() 三个参数：
    // 参数1：费用名称（前台显示的文字）
    // 参数2：费用金额
    // 参数3：是否要收税（true/false）
    $cart->add_fee(
        __( '包装处理费', 'corporate-theme' ),
        $fee_amount,
        false
    );
}
add_action( 'woocommerce_cart_calculate_fees', 'corporate_add_packaging_fee' );

/**
 * MVP 1：订单状态变化钩子 —— 记录订单状态变化日志
 * 
 * 每次订单状态变化时，自动写入一条自定义 post meta
 * 方便后台查看订单的"状态变化历史"
 */
function corporate_track_order_status($order_id,$old_status,$new_status,$order) 
{
    //获取当前时间
    $timestamp = current_time('mysql');

    //获取当前管理员用户（如果有）
    $user_id = get_current_user_id();

    // 组装状态变化记录
    $log_entry = sprintf(
        '[%s] 状态从 %s → %s (操作人ID: %d)',
        $timestamp,
        $old_status,
        $new_status,
        $user_id
    );
    // 获取已有的状态变化日志（数组）
    $status_log = get_post_meta($order_id, '_corporate_status_log', true);
    if (!is_array($status_log)) {
        $status_log = [];
    }
    
    // 把新记录追加到数组头部
    array_unshift($status_log, $log_entry);
    
    // 最多保留20条，防止数据膨胀
    if (count($status_log) > 20) {
        $status_log = array_slice($status_log, 0, 20);
    }
    
    // 保存回 post meta
    update_post_meta($order_id, '_corporate_status_log', $status_log);
}
add_action('woocommerce_order_status_changed', 'corporate_track_order_status', 10, 4);