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

    // 追踪查询 AJAX 脚本
    wp_enqueue_script(
        'freight-tracking',
        get_template_directory_uri() . '/assets/js/tracking.js',
        [],
        '1.0.0',
        true
    );

    wp_localize_script('freight-tracking', 'freight_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('freight_tracking_nonce'),
    ]);
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
    echo '<div class="bg-warning text-center py-2 fw-bold">🚢 新客户专享：首单运费 9 折优惠！</div>';
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
    register_sidebar([
    'name'          => __('WooCommerce 侧边栏', 'corporate-theme'),
    'id'            => 'sidebar-woocommerce',
    'description'   => __('商店、商品分类、商品详情页面的侧边栏', 'corporate-theme'),
    'before_widget' => '<div id="%1$s" class="widget card mb-4 %2$s">',
    'after_widget'  => '</div></div>',
    'before_title'  => '<h4 class="widget-title card-header">',
    'after_title'   => '</h4><div class="card-body">',
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
function corporate_register_portfolio_taxonomies()
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
add_action('init', 'corporate_register_portfolio_taxonomies');
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
        'default'           => __('&copy; 2026 Freight Forwarder Pro。保留所有权利。', 'corporate-theme'),
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

// ========== WooCommerce 商品信息定制 ==========
// 在商品摘要下方插入发货信息
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
// WooCommerce 商品促销横幅
// ==========================================
function corporate_woo_promo_banner() {
    echo '<div class="alert alert-info text-center mb-3">';
    echo '🚢 新客户首单运费享 <strong>9 折</strong> 优惠！';
    echo '</div>';
}
add_action('woocommerce_before_single_product', 'corporate_woo_promo_banner');
// ==========================================
// 商品详情页布局重排
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
// ACF 商品附加信息 —— 在商品摘要区输出
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
// 注意：corporate_display_product_acf_fields 函数保留但取消挂载
// 由 freight_product_shipping_info 统一展示货运信息（优先级 25）
// 保留函数代码以供参考，如需启用取消下面注释
// add_action( 'woocommerce_single_product_summary', 'corporate_display_product_acf_fields', 27 );
// ==========================================
// 相关商品推荐定制
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
// 商店页 — 商品列数 + 每页数量控制
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
// 自定义商品排序选项
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
    //满2000元门槛
    $free_shipping_threshold = 2000;
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

    // 门槛：满2000免运费
    $free_threshold = 2000;
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
 * Block 5：满5000打95折
 * 
 * 挂载 woocommerce_before_calculate_totals 动作钩子
 * 遍历购物车每件商品，把价格 ×0.95
 * 
 * @param WC_Cart $cart 购物车对象
 */
function corporate_discount($cart) {
    // 防止后台重复执行
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    // 获取购物车小计（看原始总价是否 ≥ 5000）
    $subtotal = $cart->subtotal;    // ← 要点2：用 $cart->subtotal 而不是 WC()->cart->subtotal

    if($subtotal>=5000) {
        // 要点3：遍历购物车每件商品
        foreach ( $cart->get_cart() as $cart_item ) {
            // $cart_item['data'] 是 WC_Product 对象
            // 获取原始价格
            $original_price = $cart_item['data']->get_price();
            // 打95折
            $new_price = $original_price * 0.95;
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
/**
 * 订单状态变化日志记录
 * 
 * 每次订单状态变化时，自动记录一条日志到 post meta
 * 方便客服/管理员查看订单状态变更历史
 */


/**
 * 注册团队成员 cpt + 部门分类法
 * 
 */
function corporate_register_team_cpt()
{
    $labels = [
        'name'               => _x('团队成员', 'post type general name', 'corporate-theme'),
        'singular_name'      => _x('团队成员', 'post type singular name', 'corporate-theme'),
        'add_new'            => __('添加成员', 'corporate-theme'),
        'add_new_item'       => __('添加新成员', 'corporate-theme'),
        'edit_item'          => __('编辑成员', 'corporate-theme'),
        'view_item'          => __('查看成员', 'corporate-theme'),
        'search_items'       => __('搜索成员', 'corporate-theme'),
        'not_found'          => __('没有找到成员', 'corporate-theme'),
        'not_found_in_trash' => __('回收站中没有成员', 'corporate-theme'),
        'all_items'          => __('全部成员', 'corporate-theme'),
    ];
    $args = [
        'labels' => $labels,
        'public' => true, //true则前后台都可以通过url访问，false后台看不到菜单
        'has_archive' => true,//前台能否用archive-team访问到
        'rewrite' => ['slug'=>'team'],
        'supports' => ['title','editor','thumbnail'],
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ];

    register_post_type('team',$args);
}
add_action('init','corporate_register_team_cpt');

/**
 * 注册团队成员部门分类法
 */
function corporate_register_team_taxonomy()
{
    $labels = [
        'name'              => _x('部门', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('部门', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('搜索部门', 'corporate-theme'),
        'all_items'         => __('全部部门', 'corporate-theme'),
        'parent_item'       => __('上级部门', 'corporate-theme'),
        'parent_item_colon' => __('上级部门：', 'corporate-theme'),
        'edit_item'         => __('编辑部门', 'corporate-theme'),
        'update_item'       => __('更新部门', 'corporate-theme'),
        'add_new_item'      => __('添加新部门', 'corporate-theme'),
        'new_item_name'     => __('新部门名称', 'corporate-theme'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => 'department',
        'show_in_rest' => true,
    ];
    register_taxonomy('department','team',$args);
}
add_action('init','corporate_register_team_taxonomy');
/**
 * 注册团队成员技能标签
 */
function corporate_register_team_tag()
{
    $labels = [
        'name'                       => _x('技能标签', 'taxonomy general name', 'corporate-theme'),
        'singular_name'              => _x('技能标签', 'taxonomy singular name', 'corporate-theme'),
        'search_items'               => __('搜索技能标签', 'corporate-theme'),
        'popular_items'              => __('热门技能', 'corporate-theme'),
        'all_items'                  => __('全部技能标签', 'corporate-theme'),
        'edit_item'                  => __('编辑技能标签', 'corporate-theme'),
        'update_item'                => __('更新技能标签', 'corporate-theme'),
        'add_new_item'               => __('添加新技能', 'corporate-theme'),
        'new_item_name'              => __('新技能名称', 'corporate-theme'),
        'separate_items_with_commas' => __('用逗号分隔技能', 'corporate-theme'),
        'add_or_remove_items'        => __('添加或删除技能', 'corporate-theme'),
        'choose_from_most_used'      => __('从常用技能中选择', 'corporate-theme'),
    ];

    $args = [
        'labels'       => $labels,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'skill'],
        'show_in_rest' => true,
    ];

    register_taxonomy('team_tag', 'team', $args);
}
add_action('init', 'corporate_register_team_tag');
/**
 * 演示 WP_Query + tax_query 高级查询
 * 
 * @param int $offset 跳过的数量
 * @param int $count  每页数量
 * @return WP_Query
 */
function corporate_query_team_by_department($offset=3,$count=6) 
{
    $args = [
        'post_type' => 'team',
        'posts_per_page' => $count,
        'offest' => $offset,
        'orderby' => 'date',
        'order' => 'DESC',
        'tax_query' => [
            [
                'taxonomy' => 'department',
                'field' => 'slug',
                'terms' => 'marketing',
            ]
        ],
    ];
    $query = new WP_Query($args);
    return $query;
}
/**
 * 优化版 WP_Query 列表查询
 * 
 * @param int $paged 当前页码
 * @return WP_Query
 */
function corporate_optimized_news_query($paged = 1) 
{
    $args = [
        'post_type' => 'post',
        'posts_per_page' => 10,
        'paged' => $paged,
        'cat' => get_cat_ID('news'),
        'no_found_rows' => false,
    ];
    return New WP_Query($args);
}

/**
 * [latest_team] 短代码
 * 
 * 输出最新团队成员列表
 * @param array $atts 短代码属性
 * @return string 输出的 HTML
 */
function corporate_latest_team_shortcode($atts) 
{
    //合并默认值
    $atts = shortcode_atts([
        'count' => 3
    ],
    $atts,
    'latest_team'
    );
    //将 count 转化为整数
    $count = intval($atts['count']);
    // 限制最大数量，防止恶意传参
    if ($count < 1 || $count > 20) {
        $count = 3;
    }
    //查询最新团队成员
    $team_query = new WP_Query([
        'post_type' => 'team',
        'posts_per_page' => $count,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    ]);
        // 没有成员时返回提示
    if (!$team_query->have_posts()) {
        return '<p>' . esc_html__('暂无团队成员', 'corporate-theme') . '</p>';
    }
    //开始构建html
    $html = '<ul>';
    while($team_query->have_posts()) :
        $team_query->the_post();
        $html .= '<li>';
        $html .= get_the_post_thumbnail(get_the_ID(),'thumbnail');
        $html .='<a href="' . esc_url(get_permalink()) . '">';
        $html .= esc_html(get_the_title());
        $html .= '</a></li>';
    endwhile;
    $html .= '</ul>';    
    // 恢复全局 $post
    wp_reset_postdata();

    return $html;
}

add_shortcode('latest_team','corporate_latest_team_shortcode');



/**
 * 文章发布后推送到crm
 * 
 * 钩子：transition_post_status
 * 参数：$new_status（新状态）、$old_status（旧状态）、$post（文章对象）
 * 
 * 生命周期：
 * 用户点击"发布" → WP 更新 wp_posts 表 post_status='publish'
 * → WP 触发 transition_post_status 钩子
 * → 我们的回调 post_to_crm 被调用
 */
add_action( 'transition_post_status', 'post_to_crm', 10, 3 );

/**
 * 回调函数 ： 把文章推送到crm
 * 
 * 参数说明
 * @param string $new_status 新状态：如 publish
 * @param string $old_status 旧状态 如 draft
 * @param WP_Post $post wp_post 文章对象（含id，post_title）
 */
function post_to_crm($new_status,$old_status,$post) 
{
    // 【关键过滤】 只处理“从非发布”->“发布”的文章
    //因为每次文章保存都会触发钩子，精准命中发布瞬间，才能避免多余的请求
    if('publish' !== $new_status || 'publish' === $old_status) {
        return;
    }
    //类型过滤  只处理文章“post”类型，排除 cpt，页面，修订
    //题目只要求文章推送，不是所有内容
    if ('post' !== $post->post_type) {
        return;
    }

    //【防重发检测】 检测是否已经推送过
    //用 ‘_crm_sent’这个post_meta标记，防止重复推送
    if (get_post_meta($post->ID,'_crm_sent',true)) {
        return;
    }

    // ── 到这里，确认是"新发布的文章"──
    // ── 准备要推送的数据 ──
    // 组装成 CRM 需要的 JSON 格式
    // 注意：这里先拼成数组，后面用 json_encode 转成字符串
    $author_name = get_the_author_meta('display_name',$post->post_author);
    $body = array(
        'title' => $post->post_title,//文章标题
        'author' => $author_name,
        'date' => $post->post_date,
        'url' => get_permalink($post->ID),
    );
    // ── wp_remote_post 的参数配置 ──
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',//告诉 CRM：我给你的是 JSON
            'X-API-Key' => 'your-crm-api-key',//API 密钥（正式项目放 wp-config）
        ),
        'body' => wp_json_encode($body), //把数组转为json字符串
        'timeout' => 15, //超时时间，单位s
    );
    // ── 发送请求 ──
    // 目标 URL（正式项目放 wp-config 常量里）
    $crm_url = 'https://crm.example.com/api/posts';
    
    $response = wp_remote_post($crm_url,$args);

    //检测结果
    if (!is_wp_error($response) && 200 ===wp_remote_retrieve_response_code( $response ) ) {
        //发送成功，贴标签，防止重复发送
        update_post_meta($post->ID,'_crm_sent',true);
    } else {
        // 发送失败 → 记日志，方便排查
        $error_message = is_wp_error( $response ) 
            ? $response->get_error_message() 
            : 'HTTP 状态码：' . wp_remote_retrieve_response_code( $response );
        error_log( 'CRM 推送失败 - 文章 ID：' . $post->ID . ' - 错误：' . $error_message );
        $failed_ids = get_option( 'crm_failed_post_ids', array() );
        $failed_ids[] = $post->ID;
        update_option( 'crm_failed_post_ids', array_unique( $failed_ids ) );
    }
}

/**
 * ── WP Cron 定时重试：每 30 分钟跑一次 ──
 * 
 * 思路：发送失败时，把文章 ID 存到一个 option 数组里
 *       WP Cron 每 30 分钟检查一次这个数组，重新发送失败的
 */

// 第 1 步：注册一个自定义 Cron 钩子
// 告诉 WP："我要创建一个定时任务叫 'crm_retry_push'"
add_action( 'init', 'register_crm_retry_cron' );
function register_crm_retry_cron() 
{
    // 如果这个定时任务还没注册过，就注册它
    if (!wp_next_scheduled('crm_retry_push')) {
        wp_schedule_event(time(),'hourly','crm_retry_push');
    }
}

// 第 2 步：当 Cron 触发时，执行这个重试函数
add_action( 'crm_retry_push', 'crm_retry_failed_posts' );

function crm_retry_failed_posts() 
{
    // 从 option 表里取出"待重试的文章 ID 列表"
    $failed_ids = get_option( 'crm_failed_post_ids', array() );
    
    // 没有失败的，直接返回
    if ( empty( $failed_ids ) ) {
        return;
    }
    // 遍历每个失败的文章 ID，重新发送
    foreach ($failed_ids as $index => $post_id) {
        //如果别人发送成功，则下一个循环
        if (get_post_meta ($post_id,'_crm_sent',true)) {
            unset ($failed_ids[$index]);
            continue;
        }
        // ── 重新发送逻辑（复用之前的组装代码） ──
        $post = get_post( $post_id );
        if ( ! $post ) {
            unset( $failed_ids[ $index ] );
            continue;
        }
        
        $author_name = get_the_author_meta( 'display_name', $post->post_author );
        $body = array(
            'title'  => $post->post_title,
            'author' => $author_name,
            'date'   => $post->post_date,
            'url'    => get_permalink( $post->ID ),
        );
        
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-API-Key'    => 'your-crm-api-key',
            ),
            'body'    => wp_json_encode( $body ),
            'timeout' => 15,
        );
        
        $response = wp_remote_post( 'https://crm.example.com/api/posts', $args );
        
        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
            // 重试成功 → 贴便签 + 从失败列表移除
            update_post_meta( $post_id, '_crm_sent', true );
            unset( $failed_ids[ $index ] );
            error_log( 'CRM 重试成功 - 文章 ID：' . $post_id );
        }
    }
    // 更新失败列表（移除已成功的，保留仍然失败的）
    update_option( 'crm_failed_post_ids', array_values( $failed_ids ) );
}

/**
 * 批量数据迁移脚本
 * 
 * 触发方式：访问 https://你的网站/?migrate_news=1
 * 
 * 注意：这段代码放在 functions.php 里
 *       线上环境建议做成 WP CLI 命令，这里为了演示用 URL 触发
 */
add_action('init','run_news_migration');
function run_news_migration()
{
    // 只有 URL 带 ?migrate_news=1 才执行
    // 防止普通用户访问时误触
    if ( ! isset( $_GET['migrate_news'] ) || '1' !== $_GET['migrate_news'] ) {
        return;
    }

    //只允许管理员执行
    //current_user_can() 检测当前用户是否有manage_options 权限
    if(!current_user_can('manage_options')) {
        wp_die('只有管理员才能执行迁移');
    }

    // 设置每批的数量
    $batch_size = 50; //每批50条
    // ── 读取当前进度 ──
    // 用 option 表存"已经迁移到第几页了"
    // 第 1 次跑时，paged = 1
    $paged = get_option('news_migration_paged',1);
    //查询旧文章
    $old_posts = get_posts([
        'post_type' => 'post',
        'category_name' => 'news',
        'posts_per_page' => $batch_size,
        'paged' => $paged,
        'orderby' => 'ID',
        'order' => 'ASC'
    ]);
    //没有更多文章说明迁移完成
    if (empty($old_posts)) {
        //清理进度标记
        delete_option('news_migration_paged');
        echo '迁移完成';
        exit;
    }

    //遍历处理迁移文章
    foreach ($old_posts as $old_post) {
        //检查是否已经迁移过，用post_meta数据表
        if(get_post_meta($old_post->ID,'_migrated_to_news_center',true)){
            continue;
        }

        //改文章类型
        // wp_update_post() 作用和 wp_insert_post() 一样
        // 区别：这是更新已有文章，不是新建
        $updated_post_id = wp_update_post([
            'ID' => $old_post->ID,
            'post_type' => 'news_center', 
        ]);
        // 如果更新失败，跳过这条
        if ( is_wp_error( $updated_post_id ) ) {
            error_log( '迁移失败 - 文章 ID：' . $old_post->ID );
            continue;
        }
        // ── ③ 迁移分类法关系 ──
        // 获取旧文章的分类，然后设置到新 CPT 的分类法上
        // 场景：旧分类是 'category' 分类法下的 'news'
        //       新 CPT 对应的是 'news_category' 分类法
        $old_categories = wp_get_post_categories($old_post->ID,['fields'=> 'ids']);
        if(!empty($old_categories)) {
            // wp_set_object_terms() 给文章设置分类法关系
            // 参数：文章ID, 分类ID数组, 分类法名称, 是否追加（true=追加, false=替换）
            wp_set_object_terms($old_post->ID,$old_categories,'new_category',false);
        }
        update_post_meta($old_post->ID,'_migrated_to_news_center',true);
        // ── ⑥ 记录日志 ──
        error_log( '已迁移 - 文章 ID：' . $old_post->ID . ' → news_center' );
    }
        // ── 迁移完这一批后处理 ──
    // 注意：flush_rewrite_rules() 很耗性能，不能每批都跑
    // 所以我们只在"所有批次都跑完"时才刷新
    
    // ── 读取总文章数，判断是否还有下一页 ──
    // 如果这一批查到的文章数 < batch_size，说明没有更多了
    if ( count( $old_posts ) < $batch_size ) {
        
        // ① 刷新 permalink 规则
        // 因为新的 'news_center' CPT 需要生成对应的 URL 规则
        // flush_rewrite_rules() 会重新生成 wp_rewrite 规则并写入数据库
        // 注意：这个函数很重，只在迁移彻底完成后执行一次
        flush_rewrite_rules();
        
        // ② 清理进度标记
        delete_option( 'news_migration_paged' );
        
        // ③ 抽样验证
        // 随机查 5 篇已迁移的文章，确认 post_type 是对的
        $sample = get_posts( [
            'post_type'      => 'news_center',
            'posts_per_page' => 5,
            'orderby'        => 'rand',  // 随机排序
        ] );
        
        echo '<h3>迁移完成！验证结果：</h3>';
        echo '<ul>';
        foreach ( $sample as $post ) {
            echo '<li>✅ ID：' . intval( $post->ID ) 
                 . ' | 标题：' . esc_html( $post->post_title )
                 . ' | type：' . esc_html( $post->post_type )
                 . ' | 分类：' . esc_html( implode( ', ', wp_get_post_categories( $post->ID, [ 'fields' => 'names' ] ) ) )
                 . '</li>';
        }
        echo '</ul>';
        echo '<p>共 ' . intval( $sample[0]->ID ?? 0 ) . ' 条数据验证通过</p>';
        exit;
    }
    // 进度+1，paged向前移
    update_option('news_migration_paged',$paged+1);
    //刷新页面，继续跑下一批
    // 用 JS 跳转实现"自动继续"，不用手动刷新
    echo '<meta http-equiv="refresh" content="1;url=?migrate_news=1">';
    echo '第 ' . intval( $paged ) . ' 批迁移完成，继续下一批...';
    exit;
}


/**
 * ACF 字段组 —— 客户案例
 * 
 * 用 acf_add_local_field_group() 在代码中注册字段组
 * 这样字段组会随着主题部署，不需要在后台手动创建
 * 
 * 注意：这个函数需要 ACF Pro 插件激活才能生效
 */
add_action( 'acf/init', 'register_case_study_fields' );
function register_case_study_fields() {
    
    // 仅在 ACF 函数存在时执行（防止 ACF 未激活时报错）
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }
    
    acf_add_local_field_group( [
        'key'      => 'group_case_study',
        'title'    => '客户案例详情',
        'fields'   => [
            // ── 字段 1：客户 Logo（图片） ──
            // 返回格式选 'id'，前端用 wp_get_attachment_image() 输出
            // 比直接存 URL 更灵活（可以指定图片尺寸）
            [
                'key'           => 'field_case_logo',
                'label'         => '客户 Logo',
                'name'          => 'client_logo',
                'type'          => 'image',
                'return_format' => 'id',  // 返回图片 ID
                'preview_size'  => 'medium',
            ],
            
            // ── 字段 2：一句话简介（文本） ──
            // 纯文本，限制 80 字符
            [
                'key'           => 'field_case_intro',
                'label'         => '一句话简介',
                'name'          => 'case_intro',
                'type'          => 'text',
                'maxlength'     => 80,
            ],
            
            // ── 字段 3：所属行业（复选框，多选） ──
            // 返回数组，如 ['电商', '金融']
            [
                'key'           => 'field_case_industry',
                'label'         => '所属行业',
                'name'          => 'industry',
                'type'          => 'checkbox',
                'choices'       => [
                    'ecommerce' => '电商',
                    'finance'   => '金融',
                    'education' => '教育',
                    'medical'   => '医疗',
                    'manufacturing' => '制造',
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'case',  // 显示在 'case' 文章类型的编辑页
                ],
            ],
        ],
        'menu_order' => 0,
    ] );
}

/**
 * 注册自定义 rest api 端点
 * 挂载在 rest_api_init 钩子上， 这是WP REST API 初始化时唯一的注册时机
 */
add_action('rest_api_init',function(){
    //register_rest_route 三个参数
    // 参数1：命名空间 （像公司部门名称，防止和别人冲突）
    //参数2：路由路径（窗口编号）
    //参数3：配置数组（窗口的营业规则）
    register_rest_route('myapp/v1','/top-products',[
        'methods' => 'GET',
        'callback' => 'myapp_get_top_products',
        'permission_callback' => 'return_true',
    ]);
});

/**
 * REST API 回调函数
 * 
 * 查询销量最高的前5个商品，格式化返回
 * 
 * @param WP_REST_Request $request  WP 自动传入的请求对象
 * @return WP_REST_Response         WP 自动转为 JSON 输出
 */
function myapp_get_top_products($request)
{
    //查询：按 total_sales 降序，只拿商品ID
    $args = [
        'post_type' => 'product',
        'posts_per_page' => 5,
        'post_status' => 'publish',
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'fields' => 'ids',
    ];
    $query = new WP_Query($args);

    //如果没查到任何商品，返回空数组
    if (empty($query->posts)) {
        return rest_ensure_response([
            'products' => [],
            'count' => 0,
        ]);
    }

    $products_data = [];

    foreach ($query->posts as $product_id) {
        //wc_get_product()是wc标准的商品工厂函数
        $product = wc_get_product($product_id);
        if(!$product) continue;
        $products_data[] = [
            'id' => $product_id,
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'total_sales' => (int) $product->get_total_sales(),
            'permalink' => get_permalink($product_id),
        ];
    }
    // rest_ensure_response() 确保返回的是 WP_REST_Response 对象
    return rest_ensure_response([
        'products' => $products_data,
        'count'    => count($products_data),
    ]);
}

/**
 * 下单时记录订单来源
 * 
 * 挂载在woocommerce_checkout_order_processed 上
 * 这是在订单创建后，用户跳转到支付页之前的钩子
 * @param int $order_id 刚刚创建的订单ID
 * @param array $posted_data 用户提交的表单数据
 * @param WC_Order $order 订单对象
 */
add_action('woocommerce_checkout_order_processed',function($order_id,$posted_data,$order) {
   // 判断来源：通过 URL 参数 ?from=app 判断是否来自 App
    // 如果没有这个参数，默认为 "web"（网站）
    $source = (isset($_GET['from']) && $_GET['from'] === 'app') ? 'app' : 'web';

    // update_post_meta：写入 wp_postmeta 表
    // _order_source 是自定义 meta key，加下划线前缀表示"私有"字段
    update_post_meta($order_id, '_order_source', $source); 
},10,3);

add_action('pre_get_posts','filter_products_by_price_range');

/**
 * 商品分类页价格筛选
 * 
 * 在商品分类页（product_cat），通过 URL 参数 ?min_price= &max_price= 筛选价格区间
 * 挂载在 pre_get_posts 钩子上——在 SQL 查询执行之前拦截并修改
 *
 * @param WP_Query $query 当前查询对象（引用传递）
 */

function filter_products_by_price_range($query)
{
    //安全三件套，只影响主查询+只影响商品分类页
    if(is_admin()) return;  //不在后台生效
    if (!$query->is_main_query()) return; //只改主查询（不是副循环）
    if (!$query->is_tax('product_cat')) return;
    //从url参数获取价格值
    // floatval() 把非法输入（如 “abc”）转为0，天然防注入
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;

    // 边界判断：如果两个参数都没传 或都是0，不干预查询
    if ($min_price <= 0 && $max_price <= 0) return;

    //获取已有的meta_query (保留其他插件可能加的条件)
    $meta_query = $query->get('meta_query',[]);

    // ④ 构造价格区间条件
    if ($min_price > 0) {
        $meta_query[] = [
            'key'     => '_price',        // WC 存储当前价格的 meta key
            'value'   => $min_price,      // 最小值
            'compare' => '>=',            // 大于等于
            'type'    => 'DECIMAL(10,2)', // 按小数比较，不是字符串！
        ];
    }

    if ($max_price > 0) {
        $meta_query[] = [
            'key'     => '_price',
            'value'   => $max_price,
            'compare' => '<=',
            'type'    => 'DECIMAL(10,2)',
        ];
    }

    // ⑤ 如果同时有最小和最大价格，设置 relation 为 AND
    if ($min_price > 0 && $max_price > 0) {
        $meta_query['relation'] = 'AND';
    }

    // ⑥ 把修改后的 meta_query 写回查询对象
    $query->set('meta_query', $meta_query);

}

/**
 * Q12：ACF 字段组 —— 产品手册 PDF
 * 
 * 给 WC 商品添加"产品手册 PDF"文件上传字段
 * 顾客可以在商品详情页下载
 * 
 * 挂载在 acf/init 钩子上——ACF 初始化时注册
 */
add_action('acf/init','register_product_pdf_field');

function register_product_pdf_field()
{
    //安全防护：acf未激活不报错
    if(!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_product_pdf',
        'title' => '产品手册下载',

        //location: 当编辑的文章类型是product时，显示字段组
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ],
            ]
        ],
        'fields' => [
            [
                'key' => 'field_product_manual',
                'label' => '产品手册pdf',
                'name' => 'product_manual',
                'type' => 'file',
                // return_format = 'array' → 返回文件数组
                // ['url', 'filename', 'filesize', 'mime_type', ...]
                // 比 'url' 更灵活，可以同时拿到文件名和文件大小
                'return_format' => 'array',

                // 只允许 PDF
                'mime_types'    => 'pdf',

                // 单个文件（不是多文件）
                'multiple'      => 0,
            ],
        ],
        // 高级设置
        'menu_order' => 0,
        'position'   => 'acf_after_title', // 在标题下方显示
        'style'      => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active'     => true,
    ]);
}

/**
 * 在商品详情页输出"产品手册 PDF"下载链接
 * 
 * 位置： woocommerce_single_product_summary钩子
 * 优先级： 25（在加购按钮 20之后，sku/分类 30之前）
 * 条件：仅当商品上传了pdf时才显示
 */
add_action('woocommerce_single_product_summary', 'display_product_manual_link', 25);

function display_product_manual_link() 
{
    //确保在商品页面内
    global $product;
    if(!$product || !is_a($product,'WC_Product')) return;

    // get_field() 是acf提供的函数
    //第一个参数： 字段名（和注册时的name一致）
    //第二个参数，文章id，不传默认当前post
    //返回数组： 【'url','title','filename','filesize','mime_type',】
    $manual = get_field('product_manual',$product->get_id());
    //没上传pdf就不显示
    if (!$manual || empty($manual['url'])) return;

    ?>
<div class="product-manual-download" style="margin-top: 15px;">
        <a href="<?php echo esc_url($manual['url']); ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="button manual-download-btn"
           download>
            <?php
            // 如果存了文件名，显示文件名；否则显示通用文字
            $link_text = !empty($manual['filename'])
                ? sprintf(__('📄 下载产品手册（%s）', 'corporate-theme'), $manual['filename'])
                : __('📄 下载产品手册', 'corporate-theme');

            echo esc_html($link_text);
            ?>
        </a>
    </div>
    <?php
}

//每天凌晨3点更新汇率
add_action('wp','schedule_exchange_rate_update');  //注册定时器

add_action('daily_exchange_rate_update','fetch_latest_rates');//触发定时器

function schedule_exchange_rate_update() {
    if(!wp_next_scheduled('daily_exchange_rate_update')) {
        wp_schedule_event(strtotime('03:00'),'daily','daily_exchange_rate_update');
    }
}

function fetch_latest_rates() {
    $response = wp_remote_get('https://api.exchangerate-api.com/v4/latest/USD');

    if(is_wp_error($response)) return;

    $rates = json_decode(wp_remote_retrieve_body($response),true);
    update_option('exchange_rates',$rates['rates']);
}

//价格换算过滤
add_filter('woocommerce_product_get_price','convert_product_price',10,2);


function convert_product_price(float $price, WC_Product $product){
    $target_currency = get_woocommerce_currency();

    // 如果目标货币是usd（基准） 直接返回
    if($target_currency === 'USD') return $price;
    
    $rates = get_option('exchange_rates',[]);
    $rate = $rates[$target_currency]??1;
    return round($price*$rate,2);
}

add_action('woocommerce_checkout_create_order','mark_order_origin',10,2);

//订单标记来源（记 1 个钩子）
function mark_order_origin( WC_Order $order, $data ) {
    $order->update_meta_data('_order_currency', get_woocommerce_currency());
    $order->update_meta_data('_order_language', get_locale());
}

// ==========================================
// 货代专属：货运追踪 CPT
// ==========================================
/**
 * 注册货运追踪（Shipment）自定义文章类型
 * 
 * 用于存储每条货运订单的追踪信息
 * 支持追踪编号、起运港、目的港、货物状态等
 */
function freight_register_shipment_cpt() {
    $labels = [
        'name'               => _x('货运订单', 'post type general name', 'corporate-theme'),
        'singular_name'      => _x('货运订单', 'post type singular name', 'corporate-theme'),
        'add_new'            => __('新增货运', 'corporate-theme'),
        'add_new_item'       => __('新增货运订单', 'corporate-theme'),
        'edit_item'          => __('编辑货运订单', 'corporate-theme'),
        'view_item'          => __('查看货运订单', 'corporate-theme'),
        'search_items'       => __('搜索货运订单', 'corporate-theme'),
        'not_found'          => __('没有找到货运订单', 'corporate-theme'),
        'not_found_in_trash' => __('回收站中没有货运订单', 'corporate-theme'),
        'all_items'          => __('全部货运订单', 'corporate-theme'),
    ];
    $args = [
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'shipment'],
        'supports'     => ['title', 'editor', 'custom-fields'],
        'menu_icon'    => 'dashicons-shipping',
        'show_in_rest' => true,
    ];
    register_post_type('shipment', $args);
}
add_action('init', 'freight_register_shipment_cpt');

/**
 * 注册货运状态分类法
 */
function freight_register_status_taxonomy() {
    $labels = [
        'name'              => _x('货运状态', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('货运状态', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('搜索状态', 'corporate-theme'),
        'all_items'         => __('全部状态', 'corporate-theme'),
        'edit_item'         => __('编辑状态', 'corporate-theme'),
        'update_item'       => __('更新状态', 'corporate-theme'),
        'add_new_item'      => __('添加新状态', 'corporate-theme'),
        'new_item_name'     => __('新状态名称', 'corporate-theme'),
    ];
    $args = [
        'labels'       => $labels,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'shipment-status'],
        'show_in_rest' => true,
    ];
    register_taxonomy('shipment_status', 'shipment', $args);
}
add_action('init', 'freight_register_status_taxonomy');

/**
 * 注册运输方式标签
 */
function freight_register_mode_tag() {
    $labels = [
        'name'              => _x('运输方式', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('运输方式', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('搜索运输方式', 'corporate-theme'),
        'all_items'         => __('全部运输方式', 'corporate-theme'),
        'edit_item'         => __('编辑运输方式', 'corporate-theme'),
        'update_item'       => __('更新运输方式', 'corporate-theme'),
        'add_new_item'      => __('添加新运输方式', 'corporate-theme'),
        'new_item_name'     => __('新运输方式名称', 'corporate-theme'),
    ];
    $args = [
        'labels'       => $labels,
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'shipping-mode'],
        'show_in_rest' => true,
    ];
    register_taxonomy('shipping_mode', 'shipment', $args);
}
add_action('init', 'freight_register_mode_tag');

// ==========================================
// 货代专属：REST API 追踪端点
// ==========================================
/**
 * 注册货运追踪 REST API 端点
 * 
 * GET /wp-json/freight/v1/track?tracking_no=XXX
 * 返回该货运订单的当前状态信息
 */
add_action('rest_api_init', function() {
    register_rest_route('freight/v1', '/track', [
        'methods'             => 'GET',
        'callback'            => 'freight_track_shipment',
        'permission_callback' => '__return_true',
        'args'                => [
            'tracking_no' => [
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ],
    ]);
});

/**
 * REST API 追踪查询回调
 *
 * @param WP_REST_Request $request 请求对象
 * @return WP_REST_Response JSON 响应
 */
function freight_track_shipment($request) {
    $tracking_no = $request->get_param('tracking_no');

    // 通过自定义字段 _tracking_number 查询匹配的货运订单
    $shipments = get_posts([
        'post_type'      => 'shipment',
        'posts_per_page' => 1,
        'meta_key'       => '_tracking_number',
        'meta_value'     => $tracking_no,
        'fields'         => 'ids',
    ]);

    if (empty($shipments)) {
        return rest_ensure_response([
            'success' => false,
            'message' => '未找到该追踪编号的货物信息',
        ]);
    }

    $shipment_id = $shipments[0];
    $status_terms = wp_get_post_terms($shipment_id, 'shipment_status', ['fields' => 'names']);
    $mode_terms   = wp_get_post_terms($shipment_id, 'shipping_mode', ['fields' => 'names']);

    $data = [
        'success'      => true,
        'tracking_no'  => $tracking_no,
        'status'       => !empty($status_terms) ? $status_terms[0] : '待处理',
        'mode'         => !empty($mode_terms) ? $mode_terms[0] : '',
        'origin'       => get_post_meta($shipment_id, '_origin_port', true),
        'destination'  => get_post_meta($shipment_id, '_destination_port', true),
        'weight'       => get_post_meta($shipment_id, '_cargo_weight', true),
        'volume'       => get_post_meta($shipment_id, '_cargo_volume', true),
        'etd'          => get_post_meta($shipment_id, '_etd', true),
        'eta'          => get_post_meta($shipment_id, '_eta', true),
        'last_update'  => get_post_meta($shipment_id, '_last_update_time', true),
        'permalink'    => get_permalink($shipment_id),
    ];

    return rest_ensure_response($data);
}

// ==========================================
// 货代专属：ACF 货运订单字段组
// ==========================================
/**
 * 用 ACF 注册货运订单的详细信息字段
 * 包含：追踪编号、起运港、目的港、货物重量/体积、预计离港/到港时间
 */
add_action('acf/init', 'freight_register_acf_fields');
function freight_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'      => 'group_freight_shipment',
        'title'    => '货运详细信息',
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'shipment',
                ],
            ],
        ],
        'fields'   => [
            [
                'key'   => 'field_tracking_number',
                'label' => '追踪编号',
                'name'  => 'tracking_number',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_origin_port',
                'label' => '起运港',
                'name'  => 'origin_port',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_destination_port',
                'label' => '目的港',
                'name'  => 'destination_port',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_cargo_weight',
                'label' => '货物重量 (kg)',
                'name'  => 'cargo_weight',
                'type'  => 'number',
            ],
            [
                'key'   => 'field_cargo_volume',
                'label' => '货物体积 (CBM)',
                'name'  => 'cargo_volume',
                'type'  => 'number',
                'step'  => '0.01',
            ],
            [
                'key'   => 'field_etd',
                'label' => '预计离港时间 (ETD)',
                'name'  => 'etd',
                'type'  => 'date_picker',
            ],
            [
                'key'   => 'field_eta',
                'label' => '预计到港时间 (ETA)',
                'name'  => 'eta',
                'type'  => 'date_picker',
            ],
            [
                'key'   => 'field_last_update_time',
                'label' => '最后更新时间',
                'name'  => 'last_update_time',
                'type'  => 'date_time_picker',
            ],
        ],
        'menu_order' => 0,
        'position'   => 'acf_after_title',
        'style'      => 'default',
        'active'     => true,
    ]);
}

// ==========================================
// 货代专属：追踪查询短代码 [tracking_form]（AJAX 版）
// ==========================================
/**
 * 货运追踪查询表单短代码
 * 
 * 用法：[tracking_form]
 * 前端显示输入框 + 查询按钮，通过 AJAX 异步查询，不刷新页面
 * 
 * 安全机制：
 *   1. wp_nonce 验证请求来源
 *   2. sanitize_text_field 清理输入
 *   3. 输出用 esc_html 转义
 */
add_shortcode('tracking_form', 'freight_tracking_form_shortcode');
function freight_tracking_form_shortcode() {
    ob_start();
    ?>
    <div class="tracking-form-wrapper p-4 bg-light rounded border">
        <h4 class="mb-3"><?php esc_html_e('货物追踪查询', 'corporate-theme'); ?></h4>
        <form class="tracking-form row g-2" id="freight-tracking-form">
            <div class="col-md-8">
                <input type="text"
                       id="tracking_no_input"
                       class="form-control form-control-lg"
                       placeholder="<?php esc_attr_e('请输入追踪编号：例如FRE-20260708-0001', 'corporate-theme'); ?>"
                       required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-lg w-100" id="tracking-submit-btn">
                    <?php esc_html_e('查询', 'corporate-theme'); ?>
                </button>
            </div>
        </form>
        <!-- AJAX 查询结果容器 -->
        <div id="tracking-result" class="mt-4"></div>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// 追踪查询 AJAX 处理函数
// ==========================================
/**
 * 处理 AJAX 追踪查询请求
 * 在 admin-ajax.php 中通过 wp_ajax_ 和 wp_ajax_nopriv_ 钩子注册
 * 返回 JSON 格式：{ success: true/false, html: "..." }
 */
add_action('wp_ajax_freight_track', 'freight_ajax_track_shipment');
add_action('wp_ajax_nopriv_freight_track', 'freight_ajax_track_shipment');
function freight_ajax_track_shipment() {

    // 验证 nonce
    if (!wp_verify_nonce($_POST['nonce'], 'freight_tracking_nonce')) {
        wp_send_json_error(['html' => '<div class="alert alert-danger">安全验证失败，请刷新页面重试。</div>']);
    }

    $tracking_no = isset($_POST['tracking_no']) ? sanitize_text_field($_POST['tracking_no']) : '';

    if (empty($tracking_no)) {
        wp_send_json_error(['html' => '<div class="alert alert-warning">请输入追踪编号。</div>']);
    }

    // 复用 REST API 查询函数
    $request = new WP_REST_Request('GET');
    $request->set_param('tracking_no', $tracking_no);
    $response = freight_track_shipment($request);
    $data = $response->get_data();

    ob_start();

    if ($data['success']) : ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong><?php esc_html_e('追踪编号：', 'corporate-theme'); ?></strong>
                <?php echo esc_html($data['tracking_no']); ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><?php esc_html_e('当前状态：', 'corporate-theme'); ?></strong>
                            <span class="badge bg-success"><?php echo esc_html($data['status']); ?></span>
                        </p>
                        <p><strong><?php esc_html_e('运输方式：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['mode'] ?: '—'); ?></p>
                        <p><strong><?php esc_html_e('起运港：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['origin'] ?: '—'); ?></p>
                        <p><strong><?php esc_html_e('目的港：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['destination'] ?: '—'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><?php esc_html_e('货物重量：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['weight'] ? $data['weight'] . ' kg' : '—'); ?></p>
                        <p><strong><?php esc_html_e('货物体积：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['volume'] ? $data['volume'] . ' CBM' : '—'); ?></p>
                        <p><strong><?php esc_html_e('预计离港：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['etd'] ?: '—'); ?></p>
                        <p><strong><?php esc_html_e('预计到港：', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['eta'] ?: '—'); ?></p>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    <?php esc_html_e('最后更新：', 'corporate-theme'); ?>
                    <?php echo esc_html($data['last_update'] ?: '—'); ?>
                </p>
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-warning mt-3">
            <?php echo esc_html($data['message']); ?>
        </div>
    <?php endif;

    $html = ob_get_clean();
    wp_send_json_success(['html' => $html]);
}

// ==========================================
// 货代专属：WooCommerce 商品详情增加货运信息
// ==========================================
/**
 * 在商品详情页显示货运说明
 * 替换原有通用内容为货代场景相关信息
 */
remove_action('woocommerce_single_product_summary', 'corporate_add_product_extra_info', 25);
add_action('woocommerce_single_product_summary', 'freight_product_shipping_info', 25);
function freight_product_shipping_info() {
    if (!is_product()) {
        return;
    }
    echo '<div class="freight-shipping-info mt-3 p-3 bg-light rounded border">';
    echo '<h6 class="fw-bold mb-2">🚢 货运服务说明</h6>';
    echo '<p class="mb-1 small"><strong>📦 运输方式：</strong>海运整柜 / 海运拼柜 / 空运 / 陆运</p>';
    echo '<p class="mb-1 small"><strong>🌏 服务范围：</strong>全球主要港口门到门服务</p>';
    echo '<p class="mb-1 small"><strong>📄 报关服务：</strong>提供一站式报关、报检、保险服务</p>';
    echo '<p class="mb-0 small"><strong>💡 在线追踪：</strong>下单后获取追踪编号，随时查询货物状态</p>';
    echo '</div>';
}

// ==========================================
// 货代专属：保存货运订单时自动生成追踪编号
// ==========================================
/**
 * 发布货运订单时，如果未填写追踪编号则自动生成
 */
add_action('save_post_shipment', 'freight_auto_generate_tracking_no', 10, 3);
function freight_auto_generate_tracking_no($post_id, $post, $update) {
    // 自动保存时跳过
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 已有追踪编号则跳过
    $existing_no = get_post_meta($post_id, '_tracking_number', true);
    if (!empty($existing_no)) {
        return;
    }

    // 生成唯一追踪编号：FRE-年月日-随机4位数字
    $tracking_no = 'FRE-' . date('Ymd') . '-' . wp_rand(1000, 9999);
    update_post_meta($post_id, '_tracking_number', $tracking_no);
    update_post_meta($post_id, '_last_update_time', current_time('mysql'));
}

// ==========================================
// SEO 优化：去除 WP 头部多余标签
// ==========================================
/**
 * 移除不必要的 wp_head 输出，减少 HTML 体积
 * 这些标签对 SEO 没有帮助，反而暴露技术细节
 */
remove_action('wp_head', 'wp_generator');           // 移除 WordPress 版本号
remove_action('wp_head', 'wlwmanifest_link');        // 移除 Windows Live Writer 协议
remove_action('wp_head', 'rsd_link');                // 移除 RSD (Really Simple Discovery)
remove_action('wp_head', 'wp_shortlink_wp_head');   // 移除短链接
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10); // 移除文章关系链接

// ==========================================
// SEO 优化：结构化数据（Schema.org JSON-LD）
// ==========================================
/**
 * 在页面头部输出 JSON-LD 结构化数据
 * 帮助搜索引擎理解网站内容，提升搜索结果展示效果
 * 
 * 输出以下 Schema 类型：
 *   - Organization：公司信息（名称、描述、服务范围）
 *   - WebSite：站点搜索功能
 *   - BreadcrumbList：面包屑导航路径（在面包屑函数中输出）
 *   - Article：文章内容页
 */
add_action('wp_head', 'freight_schema_structured_data');
function freight_schema_structured_data() {

    // ── 只在首页输出 Organization + WebSite Schema ──
    if (is_front_page() || is_home()) {
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
            "description": "<?php echo esc_js(get_bloginfo('description')); ?>",
            "url": "<?php echo esc_js(home_url()); ?>",
            "areaServed": ["中国", "全球"],
            "serviceType": ["国际海运", "空运货运", "陆运运输", "报关代理", "仓储配送"]
        }
        </script>

        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "url": "<?php echo esc_js(home_url()); ?>",
            "potentialAction": {
                "@type": "SearchAction",
                "target": {
                    "@type": "EntryPoint",
                    "urlTemplate": "<?php echo esc_js(home_url('/?s={search_term_string}')); ?>"
                },
                "query-input": "required name=search_term_string"
            }
        }
        </script>
        <?php
    }

    // ── 文章详情页输出 Article Schema ──
    if (is_single()) {
        $post = get_post();
        $author_name = get_the_author_meta('display_name', $post->post_author);
        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "headline": "<?php echo esc_js(get_the_title()); ?>",
            "author": {
                "@type": "Person",
                "name": "<?php echo esc_js($author_name); ?>"
            },
            "datePublished": "<?php echo esc_js(get_the_date('c')); ?>",
            "dateModified": "<?php echo esc_js(get_the_modified_date('c')); ?>",
            "image": "<?php echo esc_js($thumbnail ?: ''); ?>",
            "description": "<?php echo esc_js(wp_trim_words(get_the_excerpt() ?: get_the_content(), 30)); ?>"
        }
        </script>
        <?php
    }
}

// ==========================================
// SEO 优化：Open Graph / Twitter Card 标签
// ==========================================
/**
 * 在页面头部输出 OG 和 Twitter Card 元标签
 * 
 * 作用：当页面被分享到微信、LinkedIn、Twitter、Facebook 时，
 *       自动显示标题、描述和图片，提升品牌曝光和点击率
 */
add_action('wp_head', 'freight_og_tags');
function freight_og_tags() {

    // 默认值：首页用站点信息
    $og_title       = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url         = home_url();
    $og_image       = get_site_icon_url() ?: '';
    $og_type        = 'website';

    // 文章/页面详情页：用文章信息覆盖默认值
    if (is_single() || is_page()) {
        $og_title       = get_the_title();
        $excerpt        = get_the_excerpt() ?: get_the_content();
        $og_description = wp_trim_words($excerpt, 30);
        $og_url         = get_permalink();
        $thumbnail_id   = get_post_thumbnail_id();
        $og_image       = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'large') : $og_image;
        $og_type        = is_single() ? 'article' : 'website';
    }

    // 分类/标签页
    if (is_category() || is_tax()) {
        $og_title       = single_term_title('', false);
        $og_description = category_description() ?: $og_description;
        $og_url         = get_term_link(get_queried_object());
    }

    ?>
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo esc_attr($og_title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($og_description); ?>" />
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>" />
    <meta property="og:url" content="<?php echo esc_url($og_url); ?>" />
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>" />
    <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($og_description); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" />
    <?php
}

// ==========================================
// SEO 优化：Meta Description 自动生成
// ==========================================
/**
 * 为每个页面自动生成合适的 meta description
 * 不依赖 SEO 插件，通过 wp_head 钩子输出
 * 
 * 优先级：文章摘要 → 内容截取 → 分类描述 → 站点描述
 */
add_action('wp_head', 'freight_meta_description');
function freight_meta_description() {
    $description = '';

    if (is_single() || is_page()) {
        // 文章/页面：优先用摘要，没有摘录则截取正文前 30 字
        $excerpt = get_the_excerpt();
        $description = $excerpt
            ? wp_trim_words($excerpt, 30)
            : wp_trim_words(get_the_content(), 30);
    } elseif (is_home() || is_front_page()) {
        // 首页：用站点描述
        $description = get_bloginfo('description');
    } elseif (is_category() || is_tax()) {
        // 分类/标签页：用分类描述
        $description = category_description();
        if (!$description) {
            $description = single_term_title('', false) . ' — 相关货运物流资讯';
        }
    } elseif (is_post_type_archive('shipment')) {
        // 货运追踪归档页：固定描述
        $description = '实时查询货物运输状态，输入追踪编号获取最新物流信息。';
    } elseif (is_search()) {
        // 搜索结果页
        $description = '搜索"' . get_search_query() . '"的相关货运物流内容';
    }

    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    }
}

// ==========================================
// SEO 优化：Canonical URL
// ==========================================
/**
 * 输出 canonical 标签，防止重复内容问题
 * 
 * 货运服务页面可能通过不同 URL 访问（如带排序参数、追踪参数），
 * canonical 告诉搜索引擎哪个是权威版本，避免被判定为重复内容降权
 */
add_action('wp_head', 'freight_canonical_url');
function freight_canonical_url() {
    $canonical = '';

    if (is_single() || is_page()) {
        $canonical = get_permalink();
    } elseif (is_category() || is_tax() || is_tag()) {
        $canonical = get_term_link(get_queried_object());
    } elseif (is_post_type_archive()) {
        $canonical = get_post_type_archive_link(get_post_type());
    } elseif (is_home() || is_front_page()) {
        $canonical = home_url();
    } elseif (is_search()) {
        $canonical = home_url('/?s=' . urlencode(get_search_query()));
    }

    if ($canonical) {
        echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";
    }
}

// ==========================================
// SEO 优化：延迟加载非关键 JS
// ==========================================
/**
 * 给 Bootstrap JS 加上 defer 属性
 * 让 JS 在 HTML 解析完毕后再执行，不阻塞页面渲染
 * 提升 Core Web Vitals 中的 LCP（最大内容渲染）评分
 */
add_filter('script_loader_tag', 'freight_defer_scripts', 10, 3);
function freight_defer_scripts($tag, $handle, $src) {
    // 只对 Bootstrap JS 生效，不影响其他脚本
    if ('bootstrap-bundle' === $handle) {
        return '<script src="' . esc_url($src) . '" defer></script>' . "\n";
    }
    return $tag;
}

// ==========================================
// SEO 优化：面包屑导航函数
// ==========================================
/**
 * 生成面包屑导航 HTML，支持以下页面类型：
 *   - 首页 → 不显示面包屑
 *   - 文章页 → 首页 / 分类 / 文章标题
 *   - 页面 → 首页 / 页面标题
 *   - 分类页 → 首页 / 分类名称
 *   - 货运追踪归档页 → 首页 / 货物追踪
 *   - 自定义文章类型 → 首页 / 归档 / 单篇标题
 *   - 搜索页 → 首页 / 搜索结果
 * 
 * 使用方式：在模板中调用 <?php freight_breadcrumb(); ?>
 * 配合 BreadcrumbList Schema 结构化数据输出
 */
function freight_breadcrumb() {
    // 首页不显示面包屑
    if (is_front_page()) {
        return;
    }

    $home_url  = home_url('/');
    $separator = '<span class="breadcrumb-separator mx-2 text-muted">/</span>';
    $items     = [];

    // 第一项：首页链接
    $items[] = '<a href="' . esc_url($home_url) . '" class="text-muted text-decoration-none">'
               . esc_html__('首页', 'corporate-theme') . '</a>';

    // ── 文章详情页 ──
    if (is_single()) {
        $post_type = get_post_type();
        if ('post' === $post_type) {
            // 普通文章：显示分类
            $categories = get_the_category();
            if (!empty($categories)) {
                $items[] = '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '"
                           class="text-muted text-decoration-none">'
                           . esc_html($categories[0]->name) . '</a>';
            }
        } elseif ('shipment' === $post_type) {
            // 货运订单：显示归档链接
            $items[] = '<a href="' . esc_url(get_post_type_archive_link('shipment')) . '"
                           class="text-muted text-decoration-none">'
                           . esc_html__('货物追踪', 'corporate-theme') . '</a>';
        } elseif ('portfolio' === $post_type) {
            $items[] = '<a href="' . esc_url(get_post_type_archive_link('portfolio')) . '"
                           class="text-muted text-decoration-none">'
                           . esc_html__('客户案例', 'corporate-theme') . '</a>';
        }
        // 当前文章标题（不可点击）
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html(get_the_title()) . '</span>';
    }

    // ── 页面 ──
    elseif (is_page()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html(get_the_title()) . '</span>';
    }

    // ── 分类/标签/自定义分类法页 ──
    elseif (is_category() || is_tax() || is_tag()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html(single_term_title('', false)) . '</span>';
    }

    // ── 自定义文章类型归档页 ──
    elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        $label = 'shipment' === $post_type
            ? __('货物追踪', 'corporate-theme')
            : post_type_archive_title('', false);
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html($label) . '</span>';
    }

    // ── 搜索页 ──
    elseif (is_search()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . sprintf(__('搜索结果：%s', 'corporate-theme'), get_search_query()) . '</span>';
    }

    // ── 404 页 ──
    elseif (is_404()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html__('页面未找到', 'corporate-theme') . '</span>';
    }

    // 输出面包屑
    echo '<nav class="breadcrumb-container py-2" aria-label="breadcrumb">';
    echo implode($separator, $items);
    echo '</nav>';
}

// ==========================================
// 第一档：演示数据种子脚本
// ==========================================
/**
 * 一键填充演示数据
 * 
 * 用法：管理员登录后访问 https://你的网站/?seed_demo=1
 * 会自动创建示例货运订单、文章、页面、WooCommerce 商品
 * 
 * 安全保护：
 *   1. 仅管理员可触发（current_user_can('manage_options')）
 *   2. 仅执行一次（创建 option 标记位）
 *   3. 用 wp_insert_post 而非直接 SQL 操作
 */
add_action('init', 'freight_seed_demo_data');
function freight_seed_demo_data() {

    // 仅当 URL 参数 seed_demo=1 且是管理员时触发
    if (!isset($_GET['seed_demo']) || '1' !== $_GET['seed_demo']) {
        return;
    }
    if (!current_user_can('manage_options')) {
        wp_die('仅管理员可执行数据填充操作。');
    }

    // 防止重复执行
    if (get_option('freight_demo_seeded')) {
        wp_die('演示数据已填充，如需重新填充请删除 wp_options 表中的 freight_demo_seeded 选项。');
    }

    // ── 1. 创建示例货运订单（Shipment CPT） ──
    $shipments = [
        [
            'title'      => 'FRE-20260708-0001 — 上海→汉堡',
            'status'     => '运输中',
            'mode'       => '海运',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260708-0001',
                '_origin_port'      => '上海港',
                '_destination_port' => '汉堡港',
                '_cargo_weight'     => '8500',
                '_cargo_volume'     => '42.5',
                '_etd'              => '2026-07-10',
                '_eta'              => '2026-08-05',
                '_last_update_time' => current_time('mysql'),
            ],
        ],
        [
            'title'      => 'FRE-20260707-0002 — 深圳→洛杉矶',
            'status'     => '已到港',
            'mode'       => '空运',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260707-0002',
                '_origin_port'      => '深圳宝安机场',
                '_destination_port' => '洛杉矶国际机场',
                '_cargo_weight'     => '1200',
                '_cargo_volume'     => '8.2',
                '_etd'              => '2026-07-07',
                '_eta'              => '2026-07-09',
                '_last_update_time' => '2026-07-09 08:30:00',
            ],
        ],
        [
            'title'      => 'FRE-20260706-0003 — 宁波→鹿特丹',
            'status'     => '已签收',
            'mode'       => '海运',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260706-0003',
                '_origin_port'      => '宁波舟山港',
                '_destination_port' => '鹿特丹港',
                '_cargo_weight'     => '22000',
                '_cargo_volume'     => '85.0',
                '_etd'              => '2026-06-15',
                '_eta'              => '2026-07-06',
                '_last_update_time' => '2026-07-06 16:00:00',
            ],
        ],
        [
            'title'      => 'FRE-20260705-0004 — 广州→新加坡',
            'status'     => '运输中',
            'mode'       => '陆运',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260705-0004',
                '_origin_port'      => '广州南沙港',
                '_destination_port' => '新加坡港',
                '_cargo_weight'     => '5600',
                '_cargo_volume'     => '28.3',
                '_etd'              => '2026-07-05',
                '_eta'              => '2026-07-12',
                '_last_update_time' => '2026-07-08 10:15:00',
            ],
        ],
        [
            'title'      => 'FRE-20260704-0005 — 青岛→釜山',
            'status'     => '待处理',
            'mode'       => '海运',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260704-0005',
                '_origin_port'      => '青岛港',
                '_destination_port' => '釜山港',
                '_cargo_weight'     => '3500',
                '_cargo_volume'     => '18.0',
                '_etd'              => '2026-07-15',
                '_eta'              => '2026-07-18',
                '_last_update_time' => '2026-07-04 09:00:00',
            ],
        ],
    ];

    foreach ($shipments as $item) {
        $post_id = wp_insert_post([
            'post_type'    => 'shipment',
            'post_title'   => $item['title'],
            'post_status'  => 'publish',
            'post_author'  => 1,
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // 保存自定义字段
            foreach ($item['meta'] as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }
            // 设置分类法
            wp_set_object_terms($post_id, $item['status'], 'shipment_status');
            wp_set_object_terms($post_id, $item['mode'], 'shipping_mode');
        }
    }

    // ── 2. 创建示例物流资讯文章 ──
    $articles = [
        [
            'title'   => '2026 年全球海运市场趋势分析',
            'content' => '随着全球贸易格局的持续变化，2026 年海运市场呈现出几个重要趋势。首先，数字化变革正在加速推进，越来越多的船公司采用区块链技术提升供应链透明度。其次，绿色航运成为主流，国际海事组织（IMO）的新规推动船公司加速淘汰老旧船舶，转向 LNG 双燃料动力船。对于货代企业而言，提前布局数字化和绿色物流将是在激烈竞争中脱颖而出的关键。',
            'category' => '行业动态',
        ],
        [
            'title'   => '国际空运货物包装规范指南',
            'content' => '空运货物包装与海运有显著不同，核心原则是"轻便、牢固、防震"。本文详细介绍国际空运的包装标准：一、外包装应选用高强度瓦楞纸箱或木箱，确保能够承受堆码压力；二、内部填充物应使用泡沫塑料或气垫膜，防止货物在运输过程中移位；三、易碎品必须在外箱标注"易碎物品"和"向上"标识；四、锂电池等危险品需遵循 IATA DGR 第 63 版规定，提供 MSDS 报告。正确的包装不仅能降低货损率，还能避免因包装不当导致的运输延误。',
            'category' => '货运知识',
        ],
        [
            'title'   => '中欧班列：2026 年最新运营动态',
            'content' => '中欧班列作为一带一路倡议的重要载体，2026 年继续保持增长态势。今年上半年，中欧班列累计开行超过 8000 列，同比增长 12%。主要变化包括：一、新线路持续开通，成都至波兰马拉舍维奇的运输时间缩短至 12 天；二、回程班列载货率提升至 75%，更多欧洲优质产品通过班列进入中国市场；三、数字化服务升级，货主可通过实时追踪系统查询集装箱位置。对于货代企业，中欧班列提供了比海运更快、比空运更经济的中间选项。',
            'category' => '行业动态',
        ],
        [
            'title'   => '跨境电商物流：海外仓 vs 直邮模式对比',
            'content' => '跨境电商卖家在物流模式选择上，主要面临海外仓和直邮两种方案。海外仓模式的优点是配送速度快（2-3 天可达），退换货方便，但需要提前备货，占用资金较大。直邮模式则灵活性强，无需提前备货，适合测试新品，但运输周期较长（7-15 天），且受国际物流波动影响较大。建议卖家根据产品品类和销售策略灵活组合：热销爆款用海外仓，新品测试用直邮，实现效率与风险的最佳平衡。',
            'category' => '货运知识',
        ],
        [
            'title'   => '报关常见问题解答：HS Code 归类技巧',
            'content' => 'HS Code（海关编码）归类是报关环节中最容易出错的一环。归类错误可能导致查验率上升、关税多缴甚至货物被扣。本文分享几个实用技巧：一、利用 WCO 协调制度的前 6 位进行大类定位，后续位数根据产品具体特征确定；二、注意功能与用途的区别，同一产品可能因用途不同而归入不同编码；三、善用各国海关的预裁定服务，提前确认 HS Code 的准确性；四、建议企业建立内部 HS Code 数据库，记录常用产品的归类依据，降低出错率。',
            'category' => '报关指南',
        ],
    ];

    foreach ($articles as $article) {
        $post_id = wp_insert_post([
            'post_type'    => 'post',
            'post_title'   => $article['title'],
            'post_content' => $article['content'],
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_excerpt' => mb_substr($article['content'], 0, 150) . '...',
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            // 设置文章分类（不存在则自动创建）
            $term = term_exists($article['category'], 'category');
            if (!$term) {
                $term = wp_insert_term($article['category'], 'category');
            }
            if (!is_wp_error($term)) {
                wp_set_post_terms($post_id, $term['term_id'], 'category');
            }
        }
    }

    // ── 3. 创建示例 WooCommerce 商品（货运服务） ──
    if (class_exists('WooCommerce')) {
        $products = [
            [
                'name'        => '上海→汉堡 20GP 整柜海运',
                'description' => '上海港至汉堡港 20 英尺标准集装箱海运服务，船期每周一班，运输时间约 28 天。包含港口操作费、文件费、基本运费。适用品类：机械设备、纺织品、电子产品。',
                'price'       => '2800',
                'weight'      => '22000',
            ],
            [
                'name'        => '深圳→洛杉矶 空运普货服务',
                'description' => '深圳宝安至洛杉矶国际机场空运服务，每周五班。运输时间 3-5 天。包含燃油附加费、安检费。适用品类：高价值电子产品、样品、紧急货物。',
                'price'       => '8500',
                'weight'      => '100',
            ],
            [
                'name'        => '宁波→鹿特丹 40HQ 整柜海运',
                'description' => '宁波舟山港至鹿特丹港 40 英尺高柜海运服务，船期每周两班，运输时间约 32 天。包含港口操作费、文件费、基本运费。适用品类：家具、机械、化工品。',
                'price'       => '4200',
                'weight'      => '26000',
            ],
            [
                'name'        => '广州→新加坡 拼柜海运服务',
                'description' => '广州南沙港至新加坡港拼柜（LCL）海运服务，每周一班。按立方米计费，最低起运 1 CBM。包含港口操作费、文件费。适用品类：小批量贸易货物、样品。',
                'price'       => '150',
                'weight'      => '0',
            ],
            [
                'name'        => '全国主要港口→中亚 陆运服务',
                'description' => '中国主要港口经霍尔果斯/阿拉山口至中亚五国（哈萨克斯坦、乌兹别克斯坦等）的陆运服务。运输时间 10-15 天，可提供整车（FTL）和零担（LTL）两种模式。',
                'price'       => '3200',
                'weight'      => '20000',
            ],
        ];

        foreach ($products as $product_data) {
            $product = new WC_Product_Simple();
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_regular_price($product_data['price']);
            $product->set_weight($product_data['weight']);
            $product->set_catalog_visibility('visible');
            $product->save();
        }
    }

    // 标记已填充
    update_option('freight_demo_seeded', true);

    // 跳转回首页并显示成功消息
    wp_redirect(add_query_arg('seeded', '1', home_url()));
    exit;
}

// ==========================================
// 第一档：自定义询价表单短代码 [inquiry_form]
// ==========================================
/**
 * 货运询价表单短代码
 * 
 * 用法：[inquiry_form]
 * 在前端显示一个完整的询价表单，包含：
 *   发货地/目的地/货物类型/重量/体积/联系人信息
 * 表单提交后通过 wp_mail() 发送到管理员邮箱
 * 
 * 安全措施：
 *   1. wp_nonce 防 CSRF 攻击
 *   2. sanitize_text_field 清理所有文本输入
 *   3. esc_textarea 清理多行文本
 *   4. 验证码简单防机器人
 */
add_shortcode('inquiry_form', 'freight_inquiry_form_shortcode');
function freight_inquiry_form_shortcode() {

    ob_start();

    // 处理表单提交
    $submitted = false;
    $errors    = [];
    $success   = false;

    if (isset($_POST['freight_inquiry_submit']) && wp_verify_nonce($_POST['_wpnonce'], 'freight_inquiry_action')) {

        // 收集并清理数据
        $company   = isset($_POST['company'])   ? sanitize_text_field($_POST['company'])   : '';
        $contact   = isset($_POST['contact'])   ? sanitize_text_field($_POST['contact'])   : '';
        $phone     = isset($_POST['phone'])     ? sanitize_text_field($_POST['phone'])     : '';
        $email     = isset($_POST['email'])     ? sanitize_email($_POST['email'])          : '';
        $origin    = isset($_POST['origin'])    ? sanitize_text_field($_POST['origin'])    : '';
        $dest      = isset($_POST['dest'])      ? sanitize_text_field($_POST['dest'])      : '';
        $cargo     = isset($_POST['cargo'])     ? sanitize_text_field($_POST['cargo'])     : '';
        $weight    = isset($_POST['weight'])    ? sanitize_text_field($_POST['weight'])    : '';
        $volume    = isset($_POST['volume'])    ? sanitize_text_field($_POST['volume'])    : '';
        $message   = isset($_POST['message'])   ? sanitize_textarea_field($_POST['message']) : '';
        $honeypot  = isset($_POST['website'])   ? sanitize_text_field($_POST['website'])   : '';

        // 验证必填字段
        if (empty($contact)) {
            $errors[] = __('请输入联系人姓名', 'corporate-theme');
        }
        if (empty($phone) && empty($email)) {
            $errors[] = __('请至少填写电话或邮箱', 'corporate-theme');
        }
        if (empty($origin) || empty($dest)) {
            $errors[] = __('请填写发货地和目的地', 'corporate-theme');
        }
        if (!empty($email) && !is_email($email)) {
            $errors[] = __('邮箱格式不正确', 'corporate-theme');
        }
        // 蜜罐验证：如果 honeypot 被填了，说明是机器人
        if (!empty($honeypot)) {
            $errors[] = __('提交失败', 'corporate-theme');
        }

        if (empty($errors)) {
            // 构建邮件内容
            $to = get_option('admin_email');
            $subject = sprintf(__('新询价：%s → %s', 'corporate-theme'), $origin, $dest);
            $email_body = "=== 货运询价信息 ===\n\n";
            $email_body .= "公司名称：{$company}\n";
            $email_body .= "联系人：{$contact}\n";
            $email_body .= "电话：{$phone}\n";
            $email_body .= "邮箱：{$email}\n";
            $email_body .= "发货地：{$origin}\n";
            $email_body .= "目的地：{$dest}\n";
            $email_body .= "货物名称：{$cargo}\n";
            $email_body .= "重量：{$weight} kg\n";
            $email_body .= "体积：{$volume} CBM\n";
            $email_body .= "备注：\n{$message}\n";
            $email_body .= "\n---\n";
            $email_body .= "来源：{$_SERVER['HTTP_HOST']}\n";
            $email_body .= "时间：" . current_time('Y-m-d H:i:s') . "\n";

            $headers = ['Content-Type: text/plain; charset=UTF-8'];

            $mail_sent = wp_mail($to, $subject, $email_body, $headers);

            if ($mail_sent) {
                $success = true;
            } else {
                $errors[] = __('邮件发送失败，请稍后重试', 'corporate-theme');
            }
        }

        $submitted = true;
    }

    ?>
    <div class="inquiry-form-wrapper p-4 bg-light rounded border">
        <h4 class="mb-3"><?php esc_html_e('货运询价', 'corporate-theme'); ?></h4>
        <p class="text-muted mb-4"><?php esc_html_e('填写以下信息，我们将在 2 小时内给您报价', 'corporate-theme'); ?></p>

        <?php if ($submitted && $success) : ?>
            <div class="alert alert-success">
                <strong><?php esc_html_e('✓ 提交成功！', 'corporate-theme'); ?></strong>
                <?php esc_html_e('我们已收到您的询价，将在 2 小时内与您联系。', 'corporate-theme'); ?>
            </div>
        <?php elseif ($submitted && !empty($errors)) : ?>
            <div class="alert alert-danger">
                <strong><?php esc_html_e('请修正以下错误：', 'corporate-theme'); ?></strong>
                <ul class="mb-0 mt-1">
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="inquiry-form row g-3">
            <?php wp_nonce_field('freight_inquiry_action', '_wpnonce'); ?>

            <!-- 蜜罐字段（对用户不可见，机器人会自动填写） -->
            <div style="display:none;">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <!-- 公司信息 -->
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('公司名称', 'corporate-theme'); ?></label>
                <input type="text" name="company" class="form-control"
                       value="<?php echo isset($_POST['company']) ? esc_attr($_POST['company']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：上海贸易有限公司', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('联系人 *', 'corporate-theme'); ?></label>
                <input type="text" name="contact" class="form-control" required
                       value="<?php echo isset($_POST['contact']) ? esc_attr($_POST['contact']) : ''; ?>"
                       placeholder="<?php esc_attr_e('姓名', 'corporate-theme'); ?>">
            </div>

            <!-- 联系方式 -->
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('电话', 'corporate-theme'); ?></label>
                <input type="tel" name="phone" class="form-control"
                       value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>"
                       placeholder="<?php esc_attr_e('手机或座机', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('邮箱', 'corporate-theme'); ?></label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>"
                       placeholder="<?php esc_attr_e('example@company.com', 'corporate-theme'); ?>">
            </div>

            <!-- 运输信息 -->
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('发货地 *', 'corporate-theme'); ?></label>
                <input type="text" name="origin" class="form-control" required
                       value="<?php echo isset($_POST['origin']) ? esc_attr($_POST['origin']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：上海、深圳', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('目的地 *', 'corporate-theme'); ?></label>
                <input type="text" name="dest" class="form-control" required
                       value="<?php echo isset($_POST['dest']) ? esc_attr($_POST['dest']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：汉堡、洛杉矶', 'corporate-theme'); ?>">
            </div>

            <!-- 货物信息 -->
            <div class="col-md-4">
                <label class="form-label"><?php esc_html_e('货物名称', 'corporate-theme'); ?></label>
                <input type="text" name="cargo" class="form-control"
                       value="<?php echo isset($_POST['cargo']) ? esc_attr($_POST['cargo']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：电子产品', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php esc_html_e('预估重量 (kg)', 'corporate-theme'); ?></label>
                <input type="number" name="weight" class="form-control" step="0.1" min="0"
                       value="<?php echo isset($_POST['weight']) ? esc_attr($_POST['weight']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：500', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php esc_html_e('预估体积 (CBM)', 'corporate-theme'); ?></label>
                <input type="number" name="volume" class="form-control" step="0.01" min="0"
                       value="<?php echo isset($_POST['volume']) ? esc_attr($_POST['volume']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：2.5', 'corporate-theme'); ?>">
            </div>

            <!-- 备注 -->
            <div class="col-12">
                <label class="form-label"><?php esc_html_e('备注说明', 'corporate-theme'); ?></label>
                <textarea name="message" class="form-control" rows="3"
                          placeholder="<?php esc_attr_e('特殊要求、运输时间要求等', 'corporate-theme'); ?>"><?php echo isset($_POST['message']) ? esc_textarea($_POST['message']) : ''; ?></textarea>
            </div>

            <!-- 提交按钮 -->
            <div class="col-12">
                <button type="submit" name="freight_inquiry_submit" class="btn btn-primary btn-lg">
                    <?php esc_html_e('提交询价', 'corporate-theme'); ?>
                </button>
            </div>
        </form>
    </div>
    <?php

    return ob_get_clean();
}

// ==========================================
// 第一档：更新 page-about.php 为货代场景
// ==========================================
/**
 * 在 init 钩子中通过 add_rewrite_rule 确保页面路由正常
 * 页面模板由 page-about.php 文件本身处理（WordPress 模板层级自动匹配）
 * 此处无需额外代码
 */

// ==========================================
// 第三档：FAQ 折叠面板短代码 [faq]
// ==========================================
/**
 * FAQ 折叠面板短代码
 * 
 * 用法：[faq title="问题标题" open="true"]回答内容[/faq]
 * 支持多个短代码组合使用，通过 Bootstrap 折叠面板实现
 * 
 * 属性：
 *   title  - 问题标题（必填）
 *   open   - 是否默认展开，true/false（可选，默认 false）
 * 
 * 示例：
 *   [faq title="海运需要多久？"]海运一般需要 15-35 天，具体取决于航线。[/faq]
 *   [faq title="如何计算运费？" open="true"]运费根据重量、体积、运输方式综合计算。[/faq]
 */
add_shortcode('faq', 'freight_faq_shortcode');
function freight_faq_shortcode($atts, $content = null) {

    $atts = shortcode_atts([
        'title' => __('常见问题', 'corporate-theme'),
        'open'  => 'false',
    ], $atts, 'faq');

    $safe_title = esc_html($atts['title']);
    $safe_content = wp_kses_post($content);
    $is_open = 'true' === $atts['open'];

    // 生成唯一 ID，防止多个 FAQ 冲突
    static $faq_counter = 0;
    $faq_counter++;
    $faq_id = 'faq-item-' . $faq_counter;
    $collapse_id = 'faq-collapse-' . $faq_counter;

    $html = '<div class="accordion-item border rounded mb-2">';
    $html .= '<h2 class="accordion-header" id="' . esc_attr($faq_id) . '">';
    $html .= '<button class="accordion-button' . ($is_open ? '' : ' collapsed') . '" type="button" '
           . 'data-bs-toggle="collapse" data-bs-target="#' . esc_attr($collapse_id) . '" '
           . 'aria-expanded="' . ($is_open ? 'true' : 'false') . '" '
           . 'aria-controls="' . esc_attr($collapse_id) . '">';
    $html .= $safe_title;
    $html .= '</button></h2>';
    $html .= '<div id="' . esc_attr($collapse_id) . '" class="accordion-collapse collapse' . ($is_open ? ' show' : '') . '" '
           . 'aria-labelledby="' . esc_attr($faq_id) . '" data-bs-parent="#faq-accordion">';
    $html .= '<div class="accordion-body">' . $safe_content . '</div>';
    $html .= '</div></div>';

    return $html;
}

/**
 * 包装 FAQ 短代码的容器
 * 在页面中先调用 [faq_wrapper]，然后放多个 [faq]，最后 [/faq_wrapper]
 */
add_shortcode('faq_wrapper', 'freight_faq_wrapper');
function freight_faq_wrapper($atts, $content = null) {
    return '<div class="accordion" id="faq-accordion">' . do_shortcode($content) . '</div>';
}

// ==========================================
// 第三档：在线运费估算短代码 [quote_calculator]
// ==========================================
/**
 * 在线运费估算短代码
 * 
 * 用法：[quote_calculator]
 * 前端显示一个运费估算表单，用户选择运输方式和填写重量后
 * 自动计算预估运费（不发送邮件，仅前端展示估值）
 * 
 * 运价标准（演示用，非实时报价）：
 *   海运：$8/kg（最低消费 $100）
 *   空运：$25/kg（最低消费 $50）
 *   陆运：$5/kg（最低消费 $80）
 */
add_shortcode('quote_calculator', 'freight_quote_calculator');
function freight_quote_calculator() {

    ob_start();

    $estimated_price = '';
    $show_result = false;

    if (isset($_POST['calc_quote']) && wp_verify_nonce($_POST['_wpnonce'], 'calc_quote_action')) {

        $mode   = isset($_POST['shipping_mode']) ? sanitize_text_field($_POST['shipping_mode']) : 'sea';
        $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
        $weight = max(0, $weight);

        // 演示运价表
        $rates = [
            'sea' => ['label' => '海运', 'rate' => 8,  'min' => 100],
            'air' => ['label' => '空运', 'rate' => 25, 'min' => 50],
            'land' => ['label' => '陆运', 'rate' => 5,  'min' => 80],
        ];

        if (isset($rates[$mode]) && $weight > 0) {
            $rate = $rates[$mode];
            $calc = $rate['rate'] * $weight;
            $total = max($calc, $rate['min']);
            $estimated_price = sprintf(
                __('约 ¥%s（%s：%s × %s kg，最低消费 ¥%s）', 'corporate-theme'),
                number_format($total, 2),
                $rate['label'],
                '¥' . number_format($rate['rate'], 2),
                $weight,
                number_format($rate['min'], 2)
            );
            $show_result = true;
        } elseif ($weight <= 0) {
            $estimated_price = __('请输入有效重量', 'corporate-theme');
        }
    }

    ?>
    <div class="quote-calculator-wrapper p-4 bg-light rounded border">
        <h4 class="mb-3"><?php esc_html_e('在线运费估算', 'corporate-theme'); ?></h4>
        <p class="text-muted mb-4"><?php esc_html_e('选择运输方式并输入重量，快速获取运费估算参考', 'corporate-theme'); ?></p>

        <form method="post" class="row g-3">
            <?php wp_nonce_field('calc_quote_action', '_wpnonce'); ?>

            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('运输方式', 'corporate-theme'); ?></label>
                <select name="shipping_mode" class="form-select form-select-lg">
                    <option value="sea" <?php selected($mode ?? '', 'sea'); ?>>
                        <?php esc_html_e('海运 ¥8/kg', 'corporate-theme'); ?>
                    </option>
                    <option value="air" <?php selected($mode ?? '', 'air'); ?>>
                        <?php esc_html_e('空运 ¥25/kg', 'corporate-theme'); ?>
                    </option>
                    <option value="land" <?php selected($mode ?? '', 'land'); ?>>
                        <?php esc_html_e('陆运 ¥5/kg', 'corporate-theme'); ?>
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('货物重量 (kg)', 'corporate-theme'); ?></label>
                <input type="number" name="weight" class="form-control form-control-lg" step="0.1" min="0"
                       value="<?php echo isset($_POST['weight']) ? esc_attr($_POST['weight']) : ''; ?>"
                       placeholder="<?php esc_attr_e('如：500', 'corporate-theme'); ?>" required>
            </div>

            <div class="col-12">
                <button type="submit" name="calc_quote" class="btn btn-primary btn-lg w-100">
                    <?php esc_html_e('估算运费', 'corporate-theme'); ?>
                </button>
            </div>
        </form>

        <?php if ($show_result && $estimated_price) : ?>
            <div class="alert alert-success mt-4">
                <h5 class="alert-heading"><?php esc_html_e('运费估算结果', 'corporate-theme'); ?></h5>
                <p class="mb-0 fs-5 fw-bold"><?php echo esc_html($estimated_price); ?></p>
                <small class="text-muted"><?php esc_html_e('* 此为参考估算，实际运费以正式报价为准', 'corporate-theme'); ?></small>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

// ==========================================
// 第三档：更新案例展示文案
// ==========================================
/**
 * archive-portfolio.php 的文案已经在模板文件中直接修改
 * 此处无需额外代码
 */