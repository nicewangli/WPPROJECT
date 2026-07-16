<?php
/**
 * Corporate Theme 鏍稿績鍔熻兘
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

    // 娉ㄥ唽瀵艰埅鑿滃崟浣嶇疆
    register_nav_menus([
        'primary' => __('涓昏彍鍗?, 'corporate-theme'),
        'footer'  => __('椤佃剼鑿滃崟', 'corporate-theme'),
    ]);
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
        '1.1.1'
    );

    wp_enqueue_script(
        'bootstrap-bundle',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.3',
        true
    );

    // 杩借釜鏌ヨ AJAX 鑴氭湰
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
    echo '<div class="bg-warning text-center py-2 fw-bold">馃殺 鏂板鎴蜂笓浜細棣栧崟杩愯垂 9 鎶樹紭鎯狅紒</div>';
}
add_action('corporate_after_header', 'corporate_promo_banner');

//娉ㄥ唽渚ц竟鏍?function corporate_register_sidebars()
{
    register_sidebar([
        'name' => __('涓讳晶杈规爮','corporate-theme'),
        'id' => 'sidebar-main',
        'description' => __('鍗氬鏂囩珷椤甸潰鐨勪晶杈规爮鍖哄煙','corporate-theme'),
        'before_widget' => '<div id="%1$s" class="widget card mb-4 %2$s">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h4 class="widget-title card-header">',
        'after_title'   => '</h4><div class="card-body">',
    ]);
    register_sidebar([
        'name'          => __('椤佃剼灏忓伐鍏峰尯', 'corporate-theme'),
        'id'            => 'sidebar-footer',
        'description'   => __('椤佃剼鐨?Newsletter 璁㈤槄绛夊皬宸ュ叿鍖哄煙', 'corporate-theme'),
        'before_widget' => '<div id="%1$s" class="col-lg-4 col-md-6 mb-4 widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title text-uppercase">',
        'after_title'   => '</h5>',
    ]);
    register_sidebar([
    'name'          => __('WooCommerce 渚ц竟鏍?, 'corporate-theme'),
    'id'            => 'sidebar-woocommerce',
    'description'   => __('鍟嗗簵銆佸晢鍝佸垎绫汇€佸晢鍝佽鎯呴〉闈㈢殑渚ц竟鏍?, 'corporate-theme'),
    'before_widget' => '<div id="%1$s" class="widget card mb-4 %2$s">',
    'after_widget'  => '</div></div>',
    'before_title'  => '<h4 class="widget-title card-header">',
    'after_title'   => '</h4><div class="card-body">',
]);
}
add_action('widgets_init', 'corporate_register_sidebars');

/**
 * 娉ㄥ唽acf閫夐」椤?-- hero 鍖哄姩鎬佸瓧娈? * 
 */
function corporate_acf_options_page()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => __('涓婚璁剧疆','corporate-theme'),
            'menu_title' => __('涓婚璁剧疆','corporate-theme'),
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_theme_options',
            'redirect' => false,
        ]);
    }
}
add_action('acf/init','corporate_acf_options_page');

/**
 * 娉ㄥ唽浣滃搧闆?cpt
 */
function corporate_register_portfolio_cpt()
{
    $labels = [
        'name'               => _x('浣滃搧闆嗙粨', 'post type general name', 'corporate-theme'),
        'singular_name'      => _x('浣滃搧', 'post type singular name', 'corporate-theme'),
        'add_new'            => __('娣诲姞浣滃搧', 'corporate-theme'),
        'add_new_item'       => __('娣诲姞鏂颁綔鍝?, 'corporate-theme'),
        'edit_item'          => __('缂栬緫浣滃搧', 'corporate-theme'),
        'view_item'          => __('鏌ョ湅浣滃搧', 'corporate-theme'),
        'search_items'       => __('鎼滅储浣滃搧', 'corporate-theme'),
        'not_found'          => __('娌℃湁鎵惧埌浣滃搧', 'corporate-theme'),
        'not_found_in_trash' => __('鍥炴敹绔欎腑娌℃湁浣滃搧', 'corporate-theme'),
        'all_items'          => __('鍏ㄩ儴浣滃搧', 'corporate-theme'),
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
 * 娉ㄥ唽浣滃搧闆嗗垎绫绘硶鍜屾爣绛? */
function corporate_register_portfolio_taxonomies()
{
    //灞傜骇鍒嗙被娉曪細浣滃搧绫诲瀷锛坧ortfolio_type锛?    $type_labels = [
        'name'              => _x('浣滃搧绫诲瀷', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('浣滃搧绫诲瀷', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('鎼滅储浣滃搧绫诲瀷', 'corporate-theme'),
        'all_items'         => __('鍏ㄩ儴浣滃搧绫诲瀷', 'corporate-theme'),
        'parent_item'       => __('鐖剁骇绫诲瀷', 'corporate-theme'),
        'parent_item_colon' => __('鐖剁骇绫诲瀷锛?, 'corporate-theme'),
        'edit_item'         => __('缂栬緫浣滃搧绫诲瀷', 'corporate-theme'),
        'update_item'       => __('鏇存柊浣滃搧绫诲瀷', 'corporate-theme'),
        'add_new_item'      => __('娣诲姞鏂颁綔鍝佺被鍨?, 'corporate-theme'),
        'new_item_name'     => __('鏂颁綔鍝佺被鍨嬪悕绉?, 'corporate-theme'),
    ];
    register_taxonomy('portfolio_type', 'portfolio', [
        'labels'       => $type_labels,
        'hierarchical' => true,       // 馃敶 true = 鍍忓垎绫荤洰褰曪紝鏈夌埗瀛愬眰绾?        'rewrite'      => ['slug' => 'portfolio-type'],
        'show_in_rest' => true,
    ]);
    // 闈炲眰绾ф爣绛撅細浣滃搧鏍囩锛坧ortfolio_tag锛?    $tag_labels = [
        'name'              => _x('浣滃搧鏍囩', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('浣滃搧鏍囩', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('鎼滅储浣滃搧鏍囩', 'corporate-theme'),
        'all_items'         => __('鍏ㄩ儴浣滃搧鏍囩', 'corporate-theme'),
        'edit_item'         => __('缂栬緫浣滃搧鏍囩', 'corporate-theme'),
        'update_item'       => __('鏇存柊浣滃搧鏍囩', 'corporate-theme'),
        'add_new_item'      => __('娣诲姞鏂颁綔鍝佹爣绛?, 'corporate-theme'),
        'new_item_name'     => __('鏂颁綔鍝佹爣绛惧悕绉?, 'corporat-theme'),
    ];

    register_taxonomy('portfolio_tag', 'portfolio', [
        'labels'       => $tag_labels,
        'hierarchical' => false,      // 馃敶 false = 鍍忔爣绛撅紝鎵佸钩鐨勶紝娌℃湁鐖跺瓙鍏崇郴
        'rewrite'      => ['slug' => 'portfolio-tag'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'corporate_register_portfolio_taxonomies');
/**
 * 璁＄畻鏂囩珷闃呰鏃堕棿骞舵樉绀? * 
 * 鎸傝浇 the_content 杩囨护鍣ㄤ笂锛屽湪姝ｆ枃鍓嶈緭鍑? * @param string $content 鏂囩珷鍘熷鍐呭
 * @return string 杩藉姞闃呰鏃堕棿鍚庣殑瀹屾暣鍐呭
 */
function corporate_reading_time($content) 
{
    //鍙湪鍗曠瘒鏂囩珷椤垫樉绀?    if (!is_single()) {
        return $content;
    }

    //鑾峰彇褰撳墠鏂囩珷鍐呭锛屽苟鍘婚櫎html鏍囩
    $plain_text = strip_tags($content);

    //鐢╩b_strlen璁＄畻涓枃瀛楁暟
    $word_count = mb_strlen($plain_text,'UTF-8');
    //涓枃闃呰閫熷害绾?400瀛?鍒嗛挓
    $minutes = ceil($word_count/400);
    //鏈€灏戦槄璇?鍒嗛挓
    if($minutes<1) {
        $minutes = 1;
    }
    // 鏋勫缓闃呰鏃堕棿 HTML锛堟斁鍦ㄦ鏂囧墠闈級
    $reading_time_html = sprintf(
        '<div class="reading-time alert alert-info py-2 mb-4">
            <i class="bi bi-clock"></i>
            %s
        </div>',
        sprintf(
            /* translators: %d: 闃呰鍒嗛挓鏁?*/
            esc_html__('闃呰鏃堕棿绾?%d 鍒嗛挓', 'corporate-theme'),
            $minutes
        )
    );

    // 鎶婇槄璇绘椂闂存嫾鎺ュ埌姝ｆ枃鍓嶉潰
    return $reading_time_html . $content;
}
add_filter('the_content', 'corporate_reading_time', 10);

/**
 * 鍦ㄦ枃绔犳湯灏捐拷鍔犵増鏉冨０鏄? * 鎸傝浇the_content杩囨护鍣ㄤ笂锛屽湪姝ｆ枃鍚庤緭鍑? * @param string $content 
 * @return string
 */
function corporate_copyright_notice($content) 
{
    //鍙湪鍗曠瘒鏂囩珷椤垫樉绀?    if (!is_single()) {
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
        esc_html__('鐗堟潈澹版槑', 'corporate-theme'),
        sprintf(
            /* translators: 1: 绔欑偣鍚嶇О, 2: 鏂囩珷鏍囬閾炬帴 */
            esc_html__('鏈枃銆?1$s銆嬬敱 %2$s 鍙戝竷锛屾湭缁忚鍙姝㈣浆杞姐€?, 'corporate-theme'),
            esc_html(get_the_title()),
            '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_bloginfo('name')) . '</a>'
        )
    );

    return $content . $copyright_html;
}
add_filter('the_content', 'corporate_copyright_notice', 10);

/**
 * [cta] 鐭唬鐮?鈥斺€?琛屽姩鍙峰彫鎸夐挳
 * 
 * @param array $atts 鐢ㄦ埛浼犲叆鐨勫睘鎬?
 * @param string $content 鍖呰９鍐呭 锛圼cta]鍐呭[/cta]锛? * @return string 鐢熸垚鐨刪tml
 */
function corporate_cta_shortcode($atts,$content = null)
{
    // 鍚堝苟榛樿灞炴€?    $atts = shortcode_atts(
        [
            'title' => '鐐瑰嚮鍜ㄨ',
            'url'   => '#',
            'bg'    => '#0073aa',
            'color' => '#ffffff',
        ],
        $atts,
        'cta'
    );

    // 瀵规瘡涓緭鍑鸿繘琛屽畨鍏ㄨ浆涔?    // $safe_title = esc_html($atts['title']);
    $safe_url   = esc_url($atts['url']);
    $safe_bg    = esc_attr($atts['bg']);
    $safe_color = esc_attr($atts['color']);
    // 澶勭悊鍖呰９鍐呭锛氬鏋滅敤鎴峰啓浜?[cta]鍐呭[/cta]锛屼紭鍏堢敤 $content
    if (!empty(trim($content))) {
        // 鍏堜粠瀹炰綋瑙ｇ爜锛屽啀鍏佽瀹夊叏HTML鏍囩
        $safe_title = wp_kses_post(html_entity_decode(trim($content)));
    } else {
        $safe_title = esc_html($atts['title']);
    }
    // 鏋勫缓 HTML锛堢敤 sprintf 鎷兼帴锛?    $html = sprintf(
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
 * 鑷畾涔夊皬宸ュ叿锛氬叕鍙歌仈绯讳俊鎭? */
class Corporate_Contact_Widget extends WP_Widget
{
    /**
     * 1. 鏋勯€犲嚱鏁帮細娉ㄥ唽灏忓伐鍏峰熀鏈俊鎭?     */
    public function __construct()
    {
        parent::__construct(
            'corporate_contact_widget',          // $id_base 鈥?灏忓伐鍏风殑鍞竴 ID
            __('鍏徃鑱旂郴淇℃伅', 'corporate-theme'), // $name   鈥?鍚庡彴鏄剧ず鐨勫悕绉?            [
                'description' => __('鏄剧ず鍏徃鐢佃瘽銆侀偖绠便€佸湴鍧€绛夎仈绯讳俊鎭?, 'corporate-theme'),
            ]
        );
    }

    /**
     * 2. 鍓嶅彴杈撳嚭锛氬湪缃戦〉涓婃覆鏌?HTML
     *
     * @param array $args     register_sidebar() 瀹氫箟鐨勫寘瑁?HTML
     * @param array $instance 鍚庡彴淇濆瓨鐨勬暟鎹?     */
    public function widget($args, $instance)
    {
        // 鎻愬彇鍖呰９ HTML锛堟潵鑷?register_sidebar() 鐨勫畾涔夛級
        echo $args['before_widget'];
        // 杈撳嚭鏍囬锛堝鏋滄湁鐨勮瘽锛?        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
        }
        // 寮€濮嬭緭鍑鸿仈绯讳俊鎭唴瀹?        echo '<div class="contact-widget-content">';
        // 鍏徃鍚嶇О
        if (!empty($instance['company'])) {
            echo '<p class="contact-company fw-bold mb-2">' . esc_html($instance['company']) . '</p>';
        }
        // 鐢佃瘽
        if (!empty($instance['phone'])) {
            echo '<p class="contact-phone mb-1"><i class="bi bi-telephone"></i> '
                . esc_html($instance['phone']) . '</p>';
        }

        // 閭
        if (!empty($instance['email'])) {
            echo '<p class="contact-email mb-1"><i class="bi bi-envelope"></i> '
                . esc_html($instance['email']) . '</p>';
        }

        // 鍦板潃
        if (!empty($instance['address'])) {
            echo '<p class="contact-address mb-0"><i class="bi bi-geo-alt"></i> '
                . esc_html($instance['address']) . '</p>';
        }

        echo '</div>';

        // 闂悎鍖呰９ HTML
        echo $args['after_widget'];
    }

    /**
     * 3. 鍚庡彴琛ㄥ崟锛氱鐞嗗憳鐪嬪埌鐨勮缃晫闈?     *
     * @param array $instance 褰撳墠淇濆瓨鐨勬暟鎹?     */
    public function form($instance)
    {
        // 浠?$instance 鎻愬彇褰撳墠淇濆瓨鐨勫€硷紝娌℃湁鍒欑敤榛樿鍊?        $title   = !empty($instance['title'])   ? $instance['title']   : __('鑱旂郴鎴戜滑', 'corporate-theme');
        $company = !empty($instance['company']) ? $instance['company'] : '';
        $phone   = !empty($instance['phone'])   ? $instance['phone']   : '';
        $email   = !empty($instance['email'])   ? $instance['email']   : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                <?php esc_html_e('鏍囬锛?, 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>"
                   value="<?php echo esc_attr($title); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('company'); ?>">
                <?php esc_html_e('鍏徃鍚嶇О锛?, 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('company'); ?>"
                   name="<?php echo $this->get_field_name('company'); ?>"
                   value="<?php echo esc_attr($company); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('phone'); ?>">
                <?php esc_html_e('鐢佃瘽锛?, 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('phone'); ?>"
                   name="<?php echo $this->get_field_name('phone'); ?>"
                   value="<?php echo esc_attr($phone); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('email'); ?>">
                <?php esc_html_e('閭锛?, 'corporate-theme'); ?>
            </label>
            <input type="text"
                   id="<?php echo $this->get_field_id('email'); ?>"
                   name="<?php echo $this->get_field_name('email'); ?>"
                   value="<?php echo esc_attr($email); ?>"
                   class="widefat">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('address'); ?>">
                <?php esc_html_e('鍦板潃锛?, 'corporate-theme'); ?>
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
     * 4. 鏁版嵁鏇存柊锛氫繚瀛樻椂娓呯悊鏁版嵁
     *
     * @param array $new_instance 鐢ㄦ埛鏂版彁浜ょ殑鏁版嵁
     * @param array $old_instance 鏃ф暟鎹?     * @return array 娓呯悊鍚庣殑鏁版嵁
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
// 娉ㄥ唽鑷畾涔夊皬宸ュ叿
function corporate_register_widgets()
{
    register_widget('Corporate_Contact_Widget');
}
add_action('widgets_init', 'corporate_register_widgets');

/**
 * 娉ㄥ唽涓婚瀹氬埗鍣ㄨ缃? * 
 * @param WP_Customize_Manager $wp_customize 瀹氬埗鍣ㄧ鐞嗗櫒瀵硅薄
 */
function corporate_customize_register($wp_customize)
{
    // ========== 绗?涓垎鍖猴細鍏徃棰滆壊 ==========
    $wp_customize->add_section('corporate_colors', [
        'title'       => __('鍏徃棰滆壊', 'corporate-theme'),
        'description' => __('鑷畾涔夌綉绔欑殑涓昏壊璋冨拰杈呭姪棰滆壊', 'corporate-theme'),
        'priority'    => 30,
    ]);
        // ========== 涓昏壊璋冮鑹?==========
    $wp_customize->add_setting('primary_color', [
        'default'           => '#0d6efd',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'primary_color',
        [
            'label'    => __('涓昏壊璋冮鑹?, 'corporate-theme'),
            'section'  => 'corporate_colors',
            'settings' => 'primary_color',
        ]
    ));
    // ========== 杈呭姪棰滆壊 ==========
    $wp_customize->add_setting('secondary_color', [
        'default'           => '#6c757d',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control(
        $wp_customize,
        'secondary_color',
        [
            'label'    => __('杈呭姪棰滆壊', 'corporate-theme'),
            'section'  => 'corporate_colors',
            'settings' => 'secondary_color',
        ]
    ));
    // ========== 绗?涓垎鍖猴細棣栭〉 Hero ==========
    $wp_customize->add_section('corporate_hero', [
        'title'       => __('棣栭〉 Hero', 'corporate-theme'),
        'description' => __('鑷畾涔夐椤佃嫳闆勫尯鍩熺殑鏍囬鏂囧瓧', 'corporate-theme'),
        'priority'    => 35,
    ]);

    // Hero 鏍囬
    $wp_customize->add_setting('hero_title', [
        'default'           => __('娆㈣繋鏉ュ埌鎴戜滑鐨勫叕鍙?, 'corporate-theme'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('hero_title', [
        'label'    => __('Hero 鏍囬', 'corporate-theme'),
        'section'  => 'corporate_hero',
        'settings' => 'hero_title',
        'type'     => 'text',
    ]);

    // Hero 鍓爣棰?    $wp_customize->add_setting('hero_subtitle', [
        'default'           => __('鎴戜滑鑷村姏浜庢彁渚涙渶浼樿川鐨勬湇鍔?, 'corporate-theme'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('hero_subtitle', [
        'label'    => __('Hero 鍓爣棰?, 'corporate-theme'),
        'section'  => 'corporate_hero',
        'settings' => 'hero_subtitle',
        'type'     => 'text',
    ]);
    // ========== 绗?涓垎鍖猴細椤佃剼璁剧疆 ==========
    $wp_customize->add_section('corporate_footer', [
        'title'       => __('椤佃剼璁剧疆', 'corporate-theme'),
        'description' => __('鑷畾涔夐〉鑴氱増鏉冩枃瀛?, 'corporate-theme'),
        'priority'    => 40,
    ]);

    // 鐗堟潈鏂囧瓧
    $wp_customize->add_setting('footer_copyright', [
        'default'           => __('&copy; 2026 Freight Forwarder Pro銆備繚鐣欐墍鏈夋潈鍒┿€?, 'corporate-theme'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('footer_copyright', [
        'label'    => __('鐗堟潈鏂囧瓧', 'corporate-theme'),
        'section'  => 'corporate_footer',
        'settings' => 'footer_copyright',
        'type'     => 'text',
    ]);
}
/**
 * 杈撳嚭涓婚瀹氬埗鍣ㄧ殑鍔ㄦ€?CSS
 * 鎸傞挬鍒?wp_head锛屼互鍐呰仈 <style> 褰㈠紡杈撳嚭
 */
function corporate_customizer_css()
{
    // 璇诲彇璁剧疆鍊硷紝绗簩涓弬鏁版槸榛樿鍊硷紙鍏滃簳锛?    $primary_color   = get_theme_mod('primary_color', '#0d6efd');
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

// ========== WooCommerce 鍟嗗搧淇℃伅瀹氬埗 ==========
// 鍦ㄥ晢鍝佹憳瑕佷笅鏂规彃鍏ュ彂璐т俊鎭?add_action('woocommerce_single_product_summary','corporate_add_product_extra_info',25);
function corporate_add_product_extra_info()
{
    //瀹夊叏鍒ゆ柇锛氬彧鍦ㄥ晢鍝佸崟椤垫樉绀?    if (!is_product()) {
        return;
    }
    echo '<div class="product-extra-info mt-3 p-3 bg-light rounded">';
    echo '<p class="mb-1"><strong>馃摝 棰勮鍙戣揣锛?/strong>涓嬪崟鍚?2 涓伐浣滄棩鍐呭彂璐?/p>';
    echo '<p class="mb-0"><strong>馃挸 鏀粯鏂瑰紡锛?/strong>鏀寔鏀粯瀹?/ 寰俊 / 閾惰杞处</p>';
    echo '</div>';
}
// ==========================================
// WooCommerce 鍟嗗搧淇冮攢妯箙
// ==========================================
function corporate_woo_promo_banner() {
    echo '<div class="alert alert-info text-center mb-3">';
    echo '馃殺 鏂板鎴烽鍗曡繍璐逛韩 <strong>9 鎶?/strong> 浼樻儬锛?;
    echo '</div>';
}
add_action('woocommerce_before_single_product', 'corporate_woo_promo_banner');
// ==========================================
// 鍟嗗搧璇︽儏椤靛竷灞€閲嶆帓
// ==========================================
/**
 * 閲嶆帓鍟嗗搧鎽樿鍖哄煙锛坵oocommerce_single_product_summary锛夌殑鏉垮潡椤哄簭
 * 
 * 榛樿椤哄簭锛堟寜浼樺厛绾э級锛? *   鏍囬(5) 鈫?浠锋牸(10) 鈫?鐭弿杩?15) 鈫?鍔犺喘鎸夐挳(20) 鈫?鍏冧俊鎭?30) 鈫?鍒嗕韩(40)
 * 
 * 鏀逛负锛? *   鏍囬(5) 鈫?浠锋牸(10) 鈫?鍔犺喘鎸夐挳(20) 鈫?鐭弿杩?25) 鈫?鍏冧俊鎭?30)
 * 
 * 鍘熺悊锛? *   1. 鍏堟妸鐭弿杩颁粠榛樿鐨勪紭鍏堢骇 15 涓婄Щ闄? *   2. 鍐嶆妸瀹冩寕鍒颁紭鍏堢骇 25 涓婏紙鍗冲姞璐寜閽箣鍚庯級
 */
function corporate_reorder_product_summary()
{
    //绗竴姝ワ細绉婚櫎榛樿鎸傚湪浼樺厛绾?5涓婄殑鐭弿杩?    remove_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_excerpt',15
    );
    // 绗?姝ワ細鎶婄煭鎻忚堪鎸傚洖鍚屼竴涓挬瀛愶紝浣嗘敼涓轰紭鍏堢骇 25锛堝湪鍔犺喘鎸夐挳 20 涔嬪悗锛?    add_action(
        'woocommerce_single_product_summary',
        'woocommerce_template_single_excerpt',
        25
    );
    remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
    add_action('woocommerce_single_product_summary','woocommerce_template_single_title',35);
}
add_action('wp', 'corporate_reorder_product_summary');
// ==========================================
// ACF 鍟嗗搧闄勫姞淇℃伅 鈥斺€?鍦ㄥ晢鍝佹憳瑕佸尯杈撳嚭
// ==========================================
/**
 * 鍦ㄥ晢鍝佹憳瑕佸尯鏄剧ず ACF 鑷畾涔夊瓧娈? * 
 * 鎸傝浇鍒?woocommerce_single_product_summary 閽╁瓙
 * 浼樺厛绾?27锛堝湪鐭弿杩?25 涔嬪悗銆佸厓淇℃伅 30 涔嬪墠锛? */
function corporate_display_product_acf_fields()
{
    //瀹夊叏妫€鏌ワ紝鍙湪鍟嗗搧鍗曢〉鎵ц
    if (!is_product()) {
        return;
    }
    //鑾峰彇褰撳墠鍟嗗搧id
    $product_id = get_the_ID();
    //鐢╣et_field()璇诲彇acf瀛楁鍊?    //鐢ㄧ浜屼釜鍙傛暟蹇呴』浼犲叆褰撳墠鏂囩珷/椤甸潰鐨刬d
    $material = get_field('material',$product_id);
    $care_instructions = get_field('care_instructions',$product_id);
    $designer_note = get_field('designer_note',$product_id);

    // 濡傛灉涓変釜瀛楁閮戒负绌猴紝灏变笉杈撳嚭浠讳綍鍐呭
    if ( empty( $material ) && empty( $care_instructions ) && empty( $designer_note ) ) {
        return;
    }
    // 寮€濮嬫瀯寤鸿緭鍑?HTML
    echo '<div class="product-acf-fields mt-4 p-3 border rounded bg-light">';
    echo '<h5 class="mb-3 fw-bold">' . esc_html__( '鍟嗗搧闄勫姞淇℃伅', 'corporate-theme' ) . '</h5>';

    // 瀛楁1锛氬伐鑹烘潗璐紙绾枃鏈?鈫?esc_html锛?    if ( ! empty( $material ) ) {
        echo '<p class="mb-2">';
        echo '<strong>' . esc_html__( '宸ヨ壓鏉愯川锛?, 'corporate-theme' ) . '</strong> ';
        echo esc_html( $material );
        echo '</p>';
    }

    // 瀛楁2锛氫娇鐢ㄨ鏄庯紙鏂囨湰鍩?鈫?鍏佽鎹㈣锛岀敤 nl2br + esc_html锛?    if ( ! empty( $care_instructions ) ) {
        echo '<p class="mb-2">';
        echo '<strong>' . esc_html__( '浣跨敤璇存槑锛?, 'corporate-theme' ) . '</strong><br>';
        echo nl2br( esc_html( $care_instructions ) );
        echo '</p>';
    }

    // 瀛楁3锛氳璁″笀鎵嬭锛圵YSIWYG 鈫?鍙兘鏈?HTML 鏍囩锛岀敤 wp_kses_post锛?    if ( ! empty( $designer_note ) ) {
        echo '<div class="designer-note mt-3">';
        echo '<strong>' . esc_html__( '璁捐甯堟墜璁帮細', 'corporate-theme' ) . '</strong>';
        echo '<div class="mt-1">' . wp_kses_post( $designer_note ) . '</div>';
        echo '</div>';
    }

    echo '</div>';
}
// 娉ㄦ剰锛歝orporate_display_product_acf_fields 鍑芥暟淇濈暀浣嗗彇娑堟寕杞?// 鐢?freight_product_shipping_info 缁熶竴灞曠ず璐ц繍淇℃伅锛堜紭鍏堢骇 25锛?// 淇濈暀鍑芥暟浠ｇ爜浠ヤ緵鍙傝€冿紝濡傞渶鍚敤鍙栨秷涓嬮潰娉ㄩ噴
// add_action( 'woocommerce_single_product_summary', 'corporate_display_product_acf_fields', 27 );
// ==========================================
// 鐩稿叧鍟嗗搧鎺ㄨ崘瀹氬埗
// ==========================================
/**
 * 淇敼鐩稿叧鍟嗗搧鏁伴噺 + 鎺掑垪鍒楁暟
 * 
 * 榛樿鏄剧ず 4 涓晢鍝併€? 鍒楁帓鍒? * 鏀逛负鏄剧ず 3 涓晢鍝併€? 鍒楁帓鍒? * 
 * woocommerce_output_related_products_args 鏄竴涓繃婊ゅ櫒
 * 涓撻棬鐢ㄦ潵淇敼 woocommerce_output_related_products() 鍑芥暟鐨勫弬鏁? * 
 * @param array $args 榛樿鍙傛暟
 * @return array 淇敼鍚庣殑鍙傛暟
 */
function corporate_customize_related_products($args) 
{
    //posts_per_page -> 鏄剧ず澶氬皯涓浉鍏冲晢鍝?    // columns -> 姣忚鍑犲垪
    $args['posts_per_page'] = 2;
    $args['columns'] = 2;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'corporate_customize_related_products' );
// ==========================================
// 鍟嗗簵椤?鈥?鍟嗗搧鍒楁暟 + 姣忛〉鏁伴噺鎺у埗
// ==========================================
/**
 * 鎺у埗鍟嗗搧瀛樻。椤垫瘡琛屾樉绀哄嚑鍒? * 
 * 閽╁瓙锛歭oop_shop_columns锛堣繃婊ゅ櫒锛? * 
 * 榛樿鍊硷細4锛?鍒楃綉鏍硷級
 * 鍙傛暟 $columns锛氬綋鍓嶅垪鏁帮紙鏁存暟锛? * 杩斿洖鍊硷細淇敼鍚庣殑鍒楁暟
 * 
 * WC 鍐呴儴浼氭牴鎹繖涓繑鍥炲€肩粰姣忎釜鍟嗗搧 li 鍔犱笂瀵瑰簲鐨?class
 * 姣斿 3 鍒?鈫?.products.columns-3
 * 鐒跺悗 WC 鑷甫鐨?CSS 鑷姩閫傞厤瀹藉害涓?33.333%
 */
function corporate_shop_columns($columns) 
{
    // 鎶婇粯璁ょ殑 4 鍒楁敼涓?3 鍒?    // 閫傚悎浼佷笟涓婚鐨勫鐗堝竷灞€锛屽晢鍝佸崱鐗囨洿澶ф皵
    return 3;
}
add_filter('loop_shop_columns', 'corporate_shop_columns');
/**
 * 鎺у埗姣忎釜瀛樻。椤垫樉绀哄灏戜釜鍟嗗搧
 * 
 * 閽╁瓙锛歭oop_shop_per_page锛堣繃婊ゅ櫒锛? * 
 * 榛樿鍊硷細12锛堢敱 WC 鍚庡彴璁剧疆 鈫?鏄剧ず 鈫?姣忛〉鏄剧ず鏁伴噺锛? * 濡傛灉鎴戜滑鍦ㄨ繖涓繃婊ゅ櫒涓繑鍥炲€硷紝浼氳鐩栧悗鍙拌缃? * 
 * @param int $per_page 褰撳墠姣忛〉鏁伴噺
 * @return int 淇敼鍚庣殑姣忛〉鏁伴噺
 */
function corporate_shop_per_page($per_page) 
{
    // 鏀逛负姣忛〉鏄剧ず 9 涓晢鍝?    // 3 鍒?脳 3 琛?= 9 涓紝鍒氬ソ閾烘弧涓€灞?    return 9;
}
add_filter('loop_shop_per_page', 'corporate_shop_per_page');
// ==========================================
// 鑷畾涔夊晢鍝佹帓搴忛€夐」
// ==========================================
/**
 * 淇敼鍟嗗搧鎺掑簭涓嬫媺妗嗙殑閫夐」
 * 
 * 閽╁瓙锛歸oocommerce_catalog_orderby锛堣繃婊ゅ櫒锛? * 
 * 榛樿閫夐」锛? *   menu_order  鈫?榛樿鎺掑簭
 *   popularity  鈫?鎸夌儹搴︽帓搴? *   rating      鈫?鎸夎瘎鍒嗘帓搴? *   date        鈫?鎸夋渶鏂版帓搴? *   price       鈫?鎸変环鏍间粠浣庡埌楂? *   price-desc  鈫?鎸変环鏍间粠楂樺埌浣? * 
 * @param array $orderby 鎺掑簭閫夐」鏁扮粍
 * @return array 淇敼鍚庣殑鎺掑簭閫夐」
 */
function corporate_custom_orderby($orderby) 
{
    // 绉婚櫎"鎸夎瘎鍒嗘帓搴?锛堝鏋滀綘鐨勫晢鍝佹病鏈夎瘎鍒嗗姛鑳斤級
    unset($orderby['rating']);
    
    // 鍦ㄦ暟缁勬湯灏炬坊鍔犱竴涓嚜瀹氫箟閫夐」
    $orderby['discount'] = __('鎸夋姌鎵ｆ帓搴?, 'corporate-theme');

    return $orderby;
}
add_filter('woocommerce_catalog_orderby', 'corporate_custom_orderby');
/**
 * 璁╄嚜瀹氫箟鎺掑簭閫夐」锛坉iscount锛夌湡姝ｇ敓鏁? * 
 * 閽╁瓙锛歸oocommerce_get_catalog_ordering_args锛堣繃婊ゅ櫒锛? * 
 * 褰撶敤鎴峰湪涓嬫媺妗嗕腑閫夋嫨"鎸夋姌鎵ｆ帓搴?鏃讹紝WC 浼氳Е鍙戣繖涓挬瀛? * 鎴戜滑闇€瑕佸憡璇?WC锛氱敤浠€涔堝瓧娈垫帓搴忋€佸崌搴忚繕鏄檷搴? * 
 * @param array $args WC 鍐呴儴鎺掑簭鍙傛暟
 * @return array 淇敼鍚庣殑鎺掑簭鍙傛暟
 */
function corporate_discount_ordering_args($args) 
{
    // 鑾峰彇褰撳墠鐨勬帓搴忔柟寮?    // $_GET 鍙傛暟 'orderby' 灏辨槸鐢ㄦ埛鍦ㄩ〉闈笂閫夋嫨鐨勫€?    $orderby_value = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';

    if ('discount' === $orderby_value) {
        // 鎸夎嚜瀹氫箟鐨?'_sale_price' meta 瀛楁鎺掑簭
        // meta_key锛氳鎺掑簭鐨勮嚜瀹氫箟瀛楁鍚?        // order锛欴ESC锛堥檷搴忥紝鎶樻墸瓒婂ぇ瓒婇潬鍓嶏級
        // meta_type锛?NUMERIC'锛堟暟鍊兼瘮杈冿紝涓嶆槸瀛楃涓诧級
        $args['meta_key'] = '_sale_price';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    }

    return $args;
}
add_filter('woocommerce_get_catalog_ordering_args', 'corporate_discount_ordering_args');

/**
 * Block 1锛氳喘鐗╄溅椤甸潰椤堕儴 - 婊￠浼樻儬鎻愮ず
 * 
 * 鎸傝浇 woocommerce_before_cart 鍔ㄤ綔閽╁瓙
 * WC()->cart->subtotal 鑾峰彇褰撳墠璐墿杞﹀皬璁? * WC()->cart->total 鑾峰彇鎬讳环锛堝惈绋庡惈杩愯垂锛? */
function corporate_cart_notice()
{
    // 鍙湁璐墿杞﹂〉闈㈡墠鏄剧ず
    if ( ! is_cart() ) {
        return;
    }

    // 鑾峰彇璐墿杞﹀皬璁?锛堜笉鍚繍璐癸級
    $subtotal = WC()->cart->subtotal;
    //婊?000鍏冮棬妲?    $free_shipping_threshold = 2000;
    // 濡傛灉灏忚<300,鏄剧ず宸灏戝厤杩愯垂
    if ($subtotal < $free_shipping_threshold) {
        $remaining = $free_shipping_threshold - $subtotal;
        echo '<div class="woocommerce-message" style="background:#f0f8ff;padding:12px 20px;margin-bottom:20px;border-left:4px solid #0073aa;">';
        echo '馃帀 鍐嶈喘 <strong>' . wc_price( $remaining ) . '</strong> 鍗冲彲浜彈鍖呴偖锛?;
        echo '</div>';
    } else {
        echo '<div class="woocommerce-message" style="background:#e8f5e9;padding:12px 20px;margin-bottom:20px;border-left:4px solid #4caf50;">';
        echo '鉁?鎮ㄥ凡婊¤冻鍖呴偖鏉′欢锛?;
        echo '</div>';
    }
}
add_action('woocommerce_before_cart','corporate_cart_notice');
/**
 * Block 2锛氱簿绠€缁撶畻椤佃处鍗曞瓧娈? * 
 * 鎸傝浇 woocommerce_checkout_fields 杩囨护鍣? * 鎺ユ敹涓€涓笁缁存暟缁勶紝杩斿洖淇敼鍚庣殑鏁扮粍
 * 
 * @param array $fields WooCommerce 缁撶畻瀛楁鏁扮粍
 * @return array
 */
function corporate_customize_checkout_fields( $fields ) {
    // 1. 鍒犻櫎"鍏徃鍚?瀛楁锛堢敤 unset 绉婚櫎鏁扮粍鍏冪礌锛?    // unset( $fields['billing']['billing_company'] );
    //鏇存柊"鍏徃鍚?瀛楁锛屽皢浠栨帓鍦ㄦ渶鍚庤緭鍑?    $fields['billing']['billing_company']['priority']=99999;
    // 2. 鎶?鐢佃瘽"鏀逛负闈炲繀濉紙榛樿鏄繀濉殑锛?    $fields['billing']['billing_phone']['required'] = false;

    // 3. 鎶?鍦板潃2"鐨勬爣绛炬敼涓轰腑鏂?妤煎眰/闂ㄧ墝鍙?
    $fields['billing']['billing_address_2']['label'] = '妤煎眰/闂ㄧ墝鍙?;

    // 4. 缁?閭"瀛楁鍔犱竴涓崰浣嶆彁绀烘枃瀛?    $fields['billing']['billing_email']['placeholder'] = 'example@company.com';

    // 5. 缁?璁㈠崟澶囨敞"璋冩暣鏍囩
    $fields['order']['order_comments']['label'] = '璁㈠崟澶囨敞锛堥€夊～锛?;

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'corporate_customize_checkout_fields' );
/**
 * Block 3锛氳嚜瀹氫箟杩愯垂 鈥?婊?00鍏嶈繍璐癸紝涓嶆弧200鏀?5鍏? * 
 * 鎸傝浇 woocommerce_package_rates 杩囨护鍣? * 鍦?WC 杈撳嚭杩愯垂涔嬪墠锛屼慨鏀规墍鏈夊彲鐢ㄨ繍璐硅垂鐜? * 
 * @param array  $rates   鍙敤杩愯垂璐圭巼鏁扮粍
 * @param array  $package 褰撳墠鍖呰９淇℃伅锛堝惈鍟嗗搧銆佺洰鐨勫湴绛夛級
 * @return array
 */
function corporate_custom_shipping_rates( $rates, $package ) {
    // 鑾峰彇璐墿杞﹀皬璁★紙WC()->cart 鏄叏灞€璐墿杞﹀璞★級
    $subtotal = WC()->cart->subtotal;

    // 闂ㄦ锛氭弧2000鍏嶈繍璐?    $free_threshold = 2000;
    foreach ( $rates as $rate_id => $rate ) {
        // $rate 鏄?WC_Shipping_Rate 瀵硅薄
        // $rate->label 鏄垂鐜囧悕绉帮紙姣斿"Flat Rate"锛?        // $rate->cost  鏄垂鐜囦环鏍?        if ( $subtotal >= $free_threshold ) {
            // 婊?00锛氭妸杩愯垂璁句负 0
            $rates[ $rate_id ]->cost = 0;
            // 鏀规爣绛惧悕锛氭樉绀?鍏嶈垂閫佽揣"
            $rates[ $rate_id ]->label = '鍏嶈垂閫佽揣';
        } else {
            // 涓嶆弧200锛氭敼鍥哄畾杩愯垂 15 鍏?            $rates[ $rate_id ]->cost = 15;
            // 鏀规爣绛惧悕锛氭樉绀?鏍囧噯閰嶉€?楼15.00"
            $rates[ $rate_id ]->label = '鏍囧噯閰嶉€?;
        }
        
        // 澶勭悊绋庤垂锛堝鏋滄湁鎸夌◣鐜囪绠楃殑璇濓紝杩欓噷绠€鍗曟竻绌?taxes锛?        $rates[ $rate_id ]->taxes = [];
    }

    return $rates;
}
add_filter( 'woocommerce_package_rates', 'corporate_custom_shipping_rates', 10, 2 );

/**
 * Block 6锛氳揣鍒颁粯娆撅紙COD锛夐澶栧姞鏀?10 鍏冩墜缁垂
 * 
 * 鎸傝浇 woocommerce_cart_calculate_fees 鍔ㄤ綔閽╁瓙
 * 鍒ゆ柇鐢ㄦ埛鏄惁閫夋嫨浜?COD 鏀粯鏂瑰紡锛屾槸鍒欏姞鏀?10 鍏? * 
 * @param WC_Cart $cart 璐墿杞﹀璞? */
function corporate_cod_fee( $cart ) {
    // 闃叉鍚庡彴閲嶅鎵ц
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // 绌鸿溅涓嶅姞
    if ( $cart->get_cart_contents_count() === 0 ) {
        return;
    }

    // 浠?WC Session 鑾峰彇鐢ㄦ埛閫夋嫨鐨勬敮浠樻柟寮?    // 'cod' 鏄?WooCommerce 璐у埌浠樻鐨勯粯璁?method ID
    $chosen_payment = WC()->session->get( 'chosen_payment_method' );

    // 鍙湁鍦ㄧ粨绠楅〉鐢ㄦ埛閫変簡"璐у埌浠樻"鎵嶅姞鏀?    if ( $chosen_payment === 'cod' ) {
        $cart->add_fee(
            __( '璐у埌浠樻鎵嬬画璐?, 'corporate-theme' ),  // 鍚嶇О
            10,                                           // 閲戦 10 鍏?            false                                         // 鏄惁璁＄◣
        );
    }
}
add_action( 'woocommerce_cart_calculate_fees', 'corporate_cod_fee' );
/**
 * 娓呴櫎杩愯垂缂撳瓨锛堝紑鍙戣皟璇曠敤锛? * 姣忔鏇存柊璐墿杞︽椂寮哄埗鍒锋柊杩愯垂
 * 涓婄嚎鍚庡彲娉ㄩ噴鎺変互鎻愰珮鎬ц兘
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
 * Block 5锛氭弧5000鎵?5鎶? * 
 * 鎸傝浇 woocommerce_before_calculate_totals 鍔ㄤ綔閽╁瓙
 * 閬嶅巻璐墿杞︽瘡浠跺晢鍝侊紝鎶婁环鏍?脳0.95
 * 
 * @param WC_Cart $cart 璐墿杞﹀璞? */
function corporate_discount($cart) {
    // 闃叉鍚庡彴閲嶅鎵ц
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }
    // 鑾峰彇璐墿杞﹀皬璁★紙鐪嬪師濮嬫€讳环鏄惁 鈮?5000锛?    $subtotal = $cart->subtotal;    // 鈫?瑕佺偣2锛氱敤 $cart->subtotal 鑰屼笉鏄?WC()->cart->subtotal

    if($subtotal>=5000) {
        // 瑕佺偣3锛氶亶鍘嗚喘鐗╄溅姣忎欢鍟嗗搧
        foreach ( $cart->get_cart() as $cart_item ) {
            // $cart_item['data'] 鏄?WC_Product 瀵硅薄
            // 鑾峰彇鍘熷浠锋牸
            $original_price = $cart_item['data']->get_price();
            // 鎵?5鎶?            $new_price = $original_price * 0.95;
            // 璁剧疆鏂颁环鏍硷紙WC 浼氳嚜鍔ㄩ噸鏂扮畻鎬讳环锛?            $cart_item['data']->set_price( $new_price );
        }
    }
}
add_action( 'woocommerce_before_calculate_totals', 'corporate_discount' );
// add_action( 'woocommerce_before_calculate_totals', 'corporate_clear_shipping_cache' );
/**
 * Block 4锛氬姞鏀跺寘瑁呭鐞嗚垂
 * 
 * 鎸傝浇 woocommerce_cart_calculate_fees 鍔ㄤ綔閽╁瓙
 * 鐢?WC()->cart->add_fee() 鏂规硶娣诲姞鑷畾涔夎垂鐢? * 
 * @param WC_Cart $cart 璐墿杞﹀璞★紙娉ㄦ剰锛氳繖閲屾槸浼犲紩鐢紒锛? */
function corporate_add_packaging_fee( $cart ) {
    // 纭繚鍙湪涓昏喘鐗╄溅璁＄畻鏃舵墽琛岋紝閬垮厤閲嶅璋冪敤
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    // 鍙湁璐墿杞︽湁鍟嗗搧鎵嶅姞璐圭敤
    if ( $cart->get_cart_contents_count() === 0 ) {
        return;
    }

    // 鍖呰澶勭悊璐?5 鍏?    $fee_amount = 5;

    // WC()->cart->add_fee() 涓変釜鍙傛暟锛?    // 鍙傛暟1锛氳垂鐢ㄥ悕绉帮紙鍓嶅彴鏄剧ず鐨勬枃瀛楋級
    // 鍙傛暟2锛氳垂鐢ㄩ噾棰?    // 鍙傛暟3锛氭槸鍚﹁鏀剁◣锛坱rue/false锛?    $cart->add_fee(
        __( '鍖呰澶勭悊璐?, 'corporate-theme' ),
        $fee_amount,
        false
    );
}
add_action( 'woocommerce_cart_calculate_fees', 'corporate_add_packaging_fee' );

/**
 * MVP 1锛氳鍗曠姸鎬佸彉鍖栭挬瀛?鈥斺€?璁板綍璁㈠崟鐘舵€佸彉鍖栨棩蹇? * 
 * 姣忔璁㈠崟鐘舵€佸彉鍖栨椂锛岃嚜鍔ㄥ啓鍏ヤ竴鏉¤嚜瀹氫箟 post meta
 * 鏂逛究鍚庡彴鏌ョ湅璁㈠崟鐨?鐘舵€佸彉鍖栧巻鍙?
 */
function corporate_track_order_status($order_id,$old_status,$new_status,$order) 
{
    //鑾峰彇褰撳墠鏃堕棿
    $timestamp = current_time('mysql');

    //鑾峰彇褰撳墠绠＄悊鍛樼敤鎴凤紙濡傛灉鏈夛級
    $user_id = get_current_user_id();

    // 缁勮鐘舵€佸彉鍖栬褰?    $log_entry = sprintf(
        '[%s] 鐘舵€佷粠 %s 鈫?%s (鎿嶄綔浜篒D: %d)',
        $timestamp,
        $old_status,
        $new_status,
        $user_id
    );
    // 鑾峰彇宸叉湁鐨勭姸鎬佸彉鍖栨棩蹇楋紙鏁扮粍锛?    $status_log = get_post_meta($order_id, '_corporate_status_log', true);
    if (!is_array($status_log)) {
        $status_log = [];
    }
    
    // 鎶婃柊璁板綍杩藉姞鍒版暟缁勫ご閮?    array_unshift($status_log, $log_entry);
    
    // 鏈€澶氫繚鐣?0鏉★紝闃叉鏁版嵁鑶ㄨ儉
    if (count($status_log) > 20) {
        $status_log = array_slice($status_log, 0, 20);
    }
    
    // 淇濆瓨鍥?post meta
    update_post_meta($order_id, '_corporate_status_log', $status_log);
}
add_action('woocommerce_order_status_changed', 'corporate_track_order_status', 10, 4);
/**
 * 璁㈠崟鐘舵€佸彉鍖栨棩蹇楄褰? * 
 * 姣忔璁㈠崟鐘舵€佸彉鍖栨椂锛岃嚜鍔ㄨ褰曚竴鏉℃棩蹇楀埌 post meta
 * 鏂逛究瀹㈡湇/绠＄悊鍛樻煡鐪嬭鍗曠姸鎬佸彉鏇村巻鍙? */


/**
 * 娉ㄥ唽鍥㈤槦鎴愬憳 cpt + 閮ㄩ棬鍒嗙被娉? * 
 */
function corporate_register_team_cpt()
{
    $labels = [
        'name'               => _x('鍥㈤槦鎴愬憳', 'post type general name', 'corporate-theme'),
        'singular_name'      => _x('鍥㈤槦鎴愬憳', 'post type singular name', 'corporate-theme'),
        'add_new'            => __('娣诲姞鎴愬憳', 'corporate-theme'),
        'add_new_item'       => __('娣诲姞鏂版垚鍛?, 'corporate-theme'),
        'edit_item'          => __('缂栬緫鎴愬憳', 'corporate-theme'),
        'view_item'          => __('鏌ョ湅鎴愬憳', 'corporate-theme'),
        'search_items'       => __('鎼滅储鎴愬憳', 'corporate-theme'),
        'not_found'          => __('娌℃湁鎵惧埌鎴愬憳', 'corporate-theme'),
        'not_found_in_trash' => __('鍥炴敹绔欎腑娌℃湁鎴愬憳', 'corporate-theme'),
        'all_items'          => __('鍏ㄩ儴鎴愬憳', 'corporate-theme'),
    ];
    $args = [
        'labels' => $labels,
        'public' => true, //true鍒欏墠鍚庡彴閮藉彲浠ラ€氳繃url璁块棶锛宖alse鍚庡彴鐪嬩笉鍒拌彍鍗?        'has_archive' => true,//鍓嶅彴鑳藉惁鐢╝rchive-team璁块棶鍒?        'rewrite' => ['slug'=>'team'],
        'supports' => ['title','editor','thumbnail'],
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ];

    register_post_type('team',$args);
}
add_action('init','corporate_register_team_cpt');

/**
 * 娉ㄥ唽鍥㈤槦鎴愬憳閮ㄩ棬鍒嗙被娉? */
function corporate_register_team_taxonomy()
{
    $labels = [
        'name'              => _x('閮ㄩ棬', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('閮ㄩ棬', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('鎼滅储閮ㄩ棬', 'corporate-theme'),
        'all_items'         => __('鍏ㄩ儴閮ㄩ棬', 'corporate-theme'),
        'parent_item'       => __('涓婄骇閮ㄩ棬', 'corporate-theme'),
        'parent_item_colon' => __('涓婄骇閮ㄩ棬锛?, 'corporate-theme'),
        'edit_item'         => __('缂栬緫閮ㄩ棬', 'corporate-theme'),
        'update_item'       => __('鏇存柊閮ㄩ棬', 'corporate-theme'),
        'add_new_item'      => __('娣诲姞鏂伴儴闂?, 'corporate-theme'),
        'new_item_name'     => __('鏂伴儴闂ㄥ悕绉?, 'corporate-theme'),
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
 * 娉ㄥ唽鍥㈤槦鎴愬憳鎶€鑳芥爣绛? */
function corporate_register_team_tag()
{
    $labels = [
        'name'                       => _x('鎶€鑳芥爣绛?, 'taxonomy general name', 'corporate-theme'),
        'singular_name'              => _x('鎶€鑳芥爣绛?, 'taxonomy singular name', 'corporate-theme'),
        'search_items'               => __('鎼滅储鎶€鑳芥爣绛?, 'corporate-theme'),
        'popular_items'              => __('鐑棬鎶€鑳?, 'corporate-theme'),
        'all_items'                  => __('鍏ㄩ儴鎶€鑳芥爣绛?, 'corporate-theme'),
        'edit_item'                  => __('缂栬緫鎶€鑳芥爣绛?, 'corporate-theme'),
        'update_item'                => __('鏇存柊鎶€鑳芥爣绛?, 'corporate-theme'),
        'add_new_item'               => __('娣诲姞鏂版妧鑳?, 'corporate-theme'),
        'new_item_name'              => __('鏂版妧鑳藉悕绉?, 'corporate-theme'),
        'separate_items_with_commas' => __('鐢ㄩ€楀彿鍒嗛殧鎶€鑳?, 'corporate-theme'),
        'add_or_remove_items'        => __('娣诲姞鎴栧垹闄ゆ妧鑳?, 'corporate-theme'),
        'choose_from_most_used'      => __('浠庡父鐢ㄦ妧鑳戒腑閫夋嫨', 'corporate-theme'),
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
 * 婕旂ず WP_Query + tax_query 楂樼骇鏌ヨ
 * 
 * @param int $offset 璺宠繃鐨勬暟閲? * @param int $count  姣忛〉鏁伴噺
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
 * 浼樺寲鐗?WP_Query 鍒楄〃鏌ヨ
 * 
 * @param int $paged 褰撳墠椤电爜
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
 * [latest_team] 鐭唬鐮? * 
 * 杈撳嚭鏈€鏂板洟闃熸垚鍛樺垪琛? * @param array $atts 鐭唬鐮佸睘鎬? * @return string 杈撳嚭鐨?HTML
 */
function corporate_latest_team_shortcode($atts) 
{
    //鍚堝苟榛樿鍊?    $atts = shortcode_atts([
        'count' => 3
    ],
    $atts,
    'latest_team'
    );
    //灏?count 杞寲涓烘暣鏁?    $count = intval($atts['count']);
    // 闄愬埗鏈€澶ф暟閲忥紝闃叉鎭舵剰浼犲弬
    if ($count < 1 || $count > 20) {
        $count = 3;
    }
    //鏌ヨ鏈€鏂板洟闃熸垚鍛?    $team_query = new WP_Query([
        'post_type' => 'team',
        'posts_per_page' => $count,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    ]);
        // 娌℃湁鎴愬憳鏃惰繑鍥炴彁绀?    if (!$team_query->have_posts()) {
        return '<p>' . esc_html__('鏆傛棤鍥㈤槦鎴愬憳', 'corporate-theme') . '</p>';
    }
    //寮€濮嬫瀯寤篽tml
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
    // 鎭㈠鍏ㄥ眬 $post
    wp_reset_postdata();

    return $html;
}

add_shortcode('latest_team','corporate_latest_team_shortcode');



/**
 * 鏂囩珷鍙戝竷鍚庢帹閫佸埌crm
 * 
 * 閽╁瓙锛歵ransition_post_status
 * 鍙傛暟锛?new_status锛堟柊鐘舵€侊級銆?old_status锛堟棫鐘舵€侊級銆?post锛堟枃绔犲璞★級
 * 
 * 鐢熷懡鍛ㄦ湡锛? * 鐢ㄦ埛鐐瑰嚮"鍙戝竷" 鈫?WP 鏇存柊 wp_posts 琛?post_status='publish'
 * 鈫?WP 瑙﹀彂 transition_post_status 閽╁瓙
 * 鈫?鎴戜滑鐨勫洖璋?post_to_crm 琚皟鐢? */
add_action( 'transition_post_status', 'post_to_crm', 10, 3 );

/**
 * 鍥炶皟鍑芥暟 锛?鎶婃枃绔犳帹閫佸埌crm
 * 
 * 鍙傛暟璇存槑
 * @param string $new_status 鏂扮姸鎬侊細濡?publish
 * @param string $old_status 鏃х姸鎬?濡?draft
 * @param WP_Post $post wp_post 鏂囩珷瀵硅薄锛堝惈id锛宲ost_title锛? */
function post_to_crm($new_status,$old_status,$post) 
{
    // 銆愬叧閿繃婊ゃ€?鍙鐞嗏€滀粠闈炲彂甯冣€?>鈥滃彂甯冣€濈殑鏂囩珷
    //鍥犱负姣忔鏂囩珷淇濆瓨閮戒細瑙﹀彂閽╁瓙锛岀簿鍑嗗懡涓彂甯冪灛闂达紝鎵嶈兘閬垮厤澶氫綑鐨勮姹?    if('publish' !== $new_status || 'publish' === $old_status) {
        return;
    }
    //绫诲瀷杩囨护  鍙鐞嗘枃绔犫€減ost鈥濈被鍨嬶紝鎺掗櫎 cpt锛岄〉闈紝淇
    //棰樼洰鍙姹傛枃绔犳帹閫侊紝涓嶆槸鎵€鏈夊唴瀹?    if ('post' !== $post->post_type) {
        return;
    }

    //銆愰槻閲嶅彂妫€娴嬨€?妫€娴嬫槸鍚﹀凡缁忔帹閫佽繃
    //鐢?鈥榑crm_sent鈥欒繖涓猵ost_meta鏍囪锛岄槻姝㈤噸澶嶆帹閫?    if (get_post_meta($post->ID,'_crm_sent',true)) {
        return;
    }

    // 鈹€鈹€ 鍒拌繖閲岋紝纭鏄?鏂板彂甯冪殑鏂囩珷"鈹€鈹€
    // 鈹€鈹€ 鍑嗗瑕佹帹閫佺殑鏁版嵁 鈹€鈹€
    // 缁勮鎴?CRM 闇€瑕佺殑 JSON 鏍煎紡
    // 娉ㄦ剰锛氳繖閲屽厛鎷兼垚鏁扮粍锛屽悗闈㈢敤 json_encode 杞垚瀛楃涓?    $author_name = get_the_author_meta('display_name',$post->post_author);
    $body = array(
        'title' => $post->post_title,//鏂囩珷鏍囬
        'author' => $author_name,
        'date' => $post->post_date,
        'url' => get_permalink($post->ID),
    );
    // 鈹€鈹€ wp_remote_post 鐨勫弬鏁伴厤缃?鈹€鈹€
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',//鍛婅瘔 CRM锛氭垜缁欎綘鐨勬槸 JSON
            'X-API-Key' => 'your-crm-api-key',//API 瀵嗛挜锛堟寮忛」鐩斁 wp-config锛?        ),
        'body' => wp_json_encode($body), //鎶婃暟缁勮浆涓簀son瀛楃涓?        'timeout' => 15, //瓒呮椂鏃堕棿锛屽崟浣峴
    );
    // 鈹€鈹€ 鍙戦€佽姹?鈹€鈹€
    // 鐩爣 URL锛堟寮忛」鐩斁 wp-config 甯搁噺閲岋級
    $crm_url = 'https://crm.example.com/api/posts';
    
    $response = wp_remote_post($crm_url,$args);

    //妫€娴嬬粨鏋?    if (!is_wp_error($response) && 200 ===wp_remote_retrieve_response_code( $response ) ) {
        //鍙戦€佹垚鍔燂紝璐存爣绛撅紝闃叉閲嶅鍙戦€?        update_post_meta($post->ID,'_crm_sent',true);
    } else {
        // 鍙戦€佸け璐?鈫?璁版棩蹇楋紝鏂逛究鎺掓煡
        $error_message = is_wp_error( $response ) 
            ? $response->get_error_message() 
            : 'HTTP 鐘舵€佺爜锛? . wp_remote_retrieve_response_code( $response );
        error_log( 'CRM 鎺ㄩ€佸け璐?- 鏂囩珷 ID锛? . $post->ID . ' - 閿欒锛? . $error_message );
        $failed_ids = get_option( 'crm_failed_post_ids', array() );
        $failed_ids[] = $post->ID;
        update_option( 'crm_failed_post_ids', array_unique( $failed_ids ) );
    }
}

/**
 * 鈹€鈹€ WP Cron 瀹氭椂閲嶈瘯锛氭瘡 30 鍒嗛挓璺戜竴娆?鈹€鈹€
 * 
 * 鎬濊矾锛氬彂閫佸け璐ユ椂锛屾妸鏂囩珷 ID 瀛樺埌涓€涓?option 鏁扮粍閲? *       WP Cron 姣?30 鍒嗛挓妫€鏌ヤ竴娆¤繖涓暟缁勶紝閲嶆柊鍙戦€佸け璐ョ殑
 */

// 绗?1 姝ワ細娉ㄥ唽涓€涓嚜瀹氫箟 Cron 閽╁瓙
// 鍛婅瘔 WP锛?鎴戣鍒涘缓涓€涓畾鏃朵换鍔″彨 'crm_retry_push'"
add_action( 'init', 'register_crm_retry_cron' );
function register_crm_retry_cron() 
{
    // 濡傛灉杩欎釜瀹氭椂浠诲姟杩樻病娉ㄥ唽杩囷紝灏辨敞鍐屽畠
    if (!wp_next_scheduled('crm_retry_push')) {
        wp_schedule_event(time(),'hourly','crm_retry_push');
    }
}

// 绗?2 姝ワ細褰?Cron 瑙﹀彂鏃讹紝鎵ц杩欎釜閲嶈瘯鍑芥暟
add_action( 'crm_retry_push', 'crm_retry_failed_posts' );

function crm_retry_failed_posts() 
{
    // 浠?option 琛ㄩ噷鍙栧嚭"寰呴噸璇曠殑鏂囩珷 ID 鍒楄〃"
    $failed_ids = get_option( 'crm_failed_post_ids', array() );
    
    // 娌℃湁澶辫触鐨勶紝鐩存帴杩斿洖
    if ( empty( $failed_ids ) ) {
        return;
    }
    // 閬嶅巻姣忎釜澶辫触鐨勬枃绔?ID锛岄噸鏂板彂閫?    foreach ($failed_ids as $index => $post_id) {
        //濡傛灉鍒汉鍙戦€佹垚鍔燂紝鍒欎笅涓€涓惊鐜?        if (get_post_meta ($post_id,'_crm_sent',true)) {
            unset ($failed_ids[$index]);
            continue;
        }
        // 鈹€鈹€ 閲嶆柊鍙戦€侀€昏緫锛堝鐢ㄤ箣鍓嶇殑缁勮浠ｇ爜锛?鈹€鈹€
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
            // 閲嶈瘯鎴愬姛 鈫?璐翠究绛?+ 浠庡け璐ュ垪琛ㄧЩ闄?            update_post_meta( $post_id, '_crm_sent', true );
            unset( $failed_ids[ $index ] );
            error_log( 'CRM 閲嶈瘯鎴愬姛 - 鏂囩珷 ID锛? . $post_id );
        }
    }
    // 鏇存柊澶辫触鍒楄〃锛堢Щ闄ゅ凡鎴愬姛鐨勶紝淇濈暀浠嶇劧澶辫触鐨勶級
    update_option( 'crm_failed_post_ids', array_values( $failed_ids ) );
}

/**
 * 鎵归噺鏁版嵁杩佺Щ鑴氭湰
 * 
 * 瑙﹀彂鏂瑰紡锛氳闂?https://浣犵殑缃戠珯/?migrate_news=1
 * 
 * 娉ㄦ剰锛氳繖娈典唬鐮佹斁鍦?functions.php 閲? *       绾夸笂鐜寤鸿鍋氭垚 WP CLI 鍛戒护锛岃繖閲屼负浜嗘紨绀虹敤 URL 瑙﹀彂
 */
add_action('init','run_news_migration');
function run_news_migration()
{
    // 鍙湁 URL 甯??migrate_news=1 鎵嶆墽琛?    // 闃叉鏅€氱敤鎴疯闂椂璇Е
    if ( ! isset( $_GET['migrate_news'] ) || '1' !== $_GET['migrate_news'] ) {
        return;
    }

    //鍙厑璁哥鐞嗗憳鎵ц
    //current_user_can() 妫€娴嬪綋鍓嶇敤鎴锋槸鍚︽湁manage_options 鏉冮檺
    if(!current_user_can('manage_options')) {
        wp_die('鍙湁绠＄悊鍛樻墠鑳芥墽琛岃縼绉?);
    }

    // 璁剧疆姣忔壒鐨勬暟閲?    $batch_size = 50; //姣忔壒50鏉?    // 鈹€鈹€ 璇诲彇褰撳墠杩涘害 鈹€鈹€
    // 鐢?option 琛ㄥ瓨"宸茬粡杩佺Щ鍒扮鍑犻〉浜?
    // 绗?1 娆¤窇鏃讹紝paged = 1
    $paged = get_option('news_migration_paged',1);
    //鏌ヨ鏃ф枃绔?    $old_posts = get_posts([
        'post_type' => 'post',
        'category_name' => 'news',
        'posts_per_page' => $batch_size,
        'paged' => $paged,
        'orderby' => 'ID',
        'order' => 'ASC'
    ]);
    //娌℃湁鏇村鏂囩珷璇存槑杩佺Щ瀹屾垚
    if (empty($old_posts)) {
        //娓呯悊杩涘害鏍囪
        delete_option('news_migration_paged');
        echo '杩佺Щ瀹屾垚';
        exit;
    }

    //閬嶅巻澶勭悊杩佺Щ鏂囩珷
    foreach ($old_posts as $old_post) {
        //妫€鏌ユ槸鍚﹀凡缁忚縼绉昏繃锛岀敤post_meta鏁版嵁琛?        if(get_post_meta($old_post->ID,'_migrated_to_news_center',true)){
            continue;
        }

        //鏀规枃绔犵被鍨?        // wp_update_post() 浣滅敤鍜?wp_insert_post() 涓€鏍?        // 鍖哄埆锛氳繖鏄洿鏂板凡鏈夋枃绔狅紝涓嶆槸鏂板缓
        $updated_post_id = wp_update_post([
            'ID' => $old_post->ID,
            'post_type' => 'news_center', 
        ]);
        // 濡傛灉鏇存柊澶辫触锛岃烦杩囪繖鏉?        if ( is_wp_error( $updated_post_id ) ) {
            error_log( '杩佺Щ澶辫触 - 鏂囩珷 ID锛? . $old_post->ID );
            continue;
        }
        // 鈹€鈹€ 鈶?杩佺Щ鍒嗙被娉曞叧绯?鈹€鈹€
        // 鑾峰彇鏃ф枃绔犵殑鍒嗙被锛岀劧鍚庤缃埌鏂?CPT 鐨勫垎绫绘硶涓?        // 鍦烘櫙锛氭棫鍒嗙被鏄?'category' 鍒嗙被娉曚笅鐨?'news'
        //       鏂?CPT 瀵瑰簲鐨勬槸 'news_category' 鍒嗙被娉?        $old_categories = wp_get_post_categories($old_post->ID,['fields'=> 'ids']);
        if(!empty($old_categories)) {
            // wp_set_object_terms() 缁欐枃绔犺缃垎绫绘硶鍏崇郴
            // 鍙傛暟锛氭枃绔營D, 鍒嗙被ID鏁扮粍, 鍒嗙被娉曞悕绉? 鏄惁杩藉姞锛坱rue=杩藉姞, false=鏇挎崲锛?            wp_set_object_terms($old_post->ID,$old_categories,'new_category',false);
        }
        update_post_meta($old_post->ID,'_migrated_to_news_center',true);
        // 鈹€鈹€ 鈶?璁板綍鏃ュ織 鈹€鈹€
        error_log( '宸茶縼绉?- 鏂囩珷 ID锛? . $old_post->ID . ' 鈫?news_center' );
    }
        // 鈹€鈹€ 杩佺Щ瀹岃繖涓€鎵瑰悗澶勭悊 鈹€鈹€
    // 娉ㄦ剰锛歠lush_rewrite_rules() 寰堣€楁€ц兘锛屼笉鑳芥瘡鎵归兘璺?    // 鎵€浠ユ垜浠彧鍦?鎵€鏈夋壒娆￠兘璺戝畬"鏃舵墠鍒锋柊
    
    // 鈹€鈹€ 璇诲彇鎬绘枃绔犳暟锛屽垽鏂槸鍚﹁繕鏈変笅涓€椤?鈹€鈹€
    // 濡傛灉杩欎竴鎵规煡鍒扮殑鏂囩珷鏁?< batch_size锛岃鏄庢病鏈夋洿澶氫簡
    if ( count( $old_posts ) < $batch_size ) {
        
        // 鈶?鍒锋柊 permalink 瑙勫垯
        // 鍥犱负鏂扮殑 'news_center' CPT 闇€瑕佺敓鎴愬搴旂殑 URL 瑙勫垯
        // flush_rewrite_rules() 浼氶噸鏂扮敓鎴?wp_rewrite 瑙勫垯骞跺啓鍏ユ暟鎹簱
        // 娉ㄦ剰锛氳繖涓嚱鏁板緢閲嶏紝鍙湪杩佺Щ褰诲簳瀹屾垚鍚庢墽琛屼竴娆?        flush_rewrite_rules();
        
        // 鈶?娓呯悊杩涘害鏍囪
        delete_option( 'news_migration_paged' );
        
        // 鈶?鎶芥牱楠岃瘉
        // 闅忔満鏌?5 绡囧凡杩佺Щ鐨勬枃绔狅紝纭 post_type 鏄鐨?        $sample = get_posts( [
            'post_type'      => 'news_center',
            'posts_per_page' => 5,
            'orderby'        => 'rand',  // 闅忔満鎺掑簭
        ] );
        
        echo '<h3>杩佺Щ瀹屾垚锛侀獙璇佺粨鏋滐細</h3>';
        echo '<ul>';
        foreach ( $sample as $post ) {
            echo '<li>鉁?ID锛? . intval( $post->ID ) 
                 . ' | 鏍囬锛? . esc_html( $post->post_title )
                 . ' | type锛? . esc_html( $post->post_type )
                 . ' | 鍒嗙被锛? . esc_html( implode( ', ', wp_get_post_categories( $post->ID, [ 'fields' => 'names' ] ) ) )
                 . '</li>';
        }
        echo '</ul>';
        echo '<p>鍏?' . intval( $sample[0]->ID ?? 0 ) . ' 鏉℃暟鎹獙璇侀€氳繃</p>';
        exit;
    }
    // 杩涘害+1锛宲aged鍚戝墠绉?    update_option('news_migration_paged',$paged+1);
    //鍒锋柊椤甸潰锛岀户缁窇涓嬩竴鎵?    // 鐢?JS 璺宠浆瀹炵幇"鑷姩缁х画"锛屼笉鐢ㄦ墜鍔ㄥ埛鏂?    echo '<meta http-equiv="refresh" content="1;url=?migrate_news=1">';
    echo '绗?' . intval( $paged ) . ' 鎵硅縼绉诲畬鎴愶紝缁х画涓嬩竴鎵?..';
    exit;
}


/**
 * ACF 瀛楁缁?鈥斺€?瀹㈡埛妗堜緥
 * 
 * 鐢?acf_add_local_field_group() 鍦ㄤ唬鐮佷腑娉ㄥ唽瀛楁缁? * 杩欐牱瀛楁缁勪細闅忕潃涓婚閮ㄧ讲锛屼笉闇€瑕佸湪鍚庡彴鎵嬪姩鍒涘缓
 * 
 * 娉ㄦ剰锛氳繖涓嚱鏁伴渶瑕?ACF Pro 鎻掍欢婵€娲绘墠鑳界敓鏁? */
add_action( 'acf/init', 'register_case_study_fields' );
function register_case_study_fields() {
    
    // 浠呭湪 ACF 鍑芥暟瀛樺湪鏃舵墽琛岋紙闃叉 ACF 鏈縺娲绘椂鎶ラ敊锛?    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }
    
    acf_add_local_field_group( [
        'key'      => 'group_case_study',
        'title'    => '瀹㈡埛妗堜緥璇︽儏',
        'fields'   => [
            // 鈹€鈹€ 瀛楁 1锛氬鎴?Logo锛堝浘鐗囷級 鈹€鈹€
            // 杩斿洖鏍煎紡閫?'id'锛屽墠绔敤 wp_get_attachment_image() 杈撳嚭
            // 姣旂洿鎺ュ瓨 URL 鏇寸伒娲伙紙鍙互鎸囧畾鍥剧墖灏哄锛?            [
                'key'           => 'field_case_logo',
                'label'         => '瀹㈡埛 Logo',
                'name'          => 'client_logo',
                'type'          => 'image',
                'return_format' => 'id',  // 杩斿洖鍥剧墖 ID
                'preview_size'  => 'medium',
            ],
            
            // 鈹€鈹€ 瀛楁 2锛氫竴鍙ヨ瘽绠€浠嬶紙鏂囨湰锛?鈹€鈹€
            // 绾枃鏈紝闄愬埗 80 瀛楃
            [
                'key'           => 'field_case_intro',
                'label'         => '涓€鍙ヨ瘽绠€浠?,
                'name'          => 'case_intro',
                'type'          => 'text',
                'maxlength'     => 80,
            ],
            
            // 鈹€鈹€ 瀛楁 3锛氭墍灞炶涓氾紙澶嶉€夋锛屽閫夛級 鈹€鈹€
            // 杩斿洖鏁扮粍锛屽 ['鐢靛晢', '閲戣瀺']
            [
                'key'           => 'field_case_industry',
                'label'         => '鎵€灞炶涓?,
                'name'          => 'industry',
                'type'          => 'checkbox',
                'choices'       => [
                    'ecommerce' => '鐢靛晢',
                    'finance'   => '閲戣瀺',
                    'education' => '鏁欒偛',
                    'medical'   => '鍖荤枟',
                    'manufacturing' => '鍒堕€?,
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'case',  // 鏄剧ず鍦?'case' 鏂囩珷绫诲瀷鐨勭紪杈戦〉
                ],
            ],
        ],
        'menu_order' => 0,
    ] );
}

/**
 * 娉ㄥ唽鑷畾涔?rest api 绔偣
 * 鎸傝浇鍦?rest_api_init 閽╁瓙涓婏紝 杩欐槸WP REST API 鍒濆鍖栨椂鍞竴鐨勬敞鍐屾椂鏈? */
add_action('rest_api_init',function(){
    //register_rest_route 涓変釜鍙傛暟
    // 鍙傛暟1锛氬懡鍚嶇┖闂?锛堝儚鍏徃閮ㄩ棬鍚嶇О锛岄槻姝㈠拰鍒汉鍐茬獊锛?    //鍙傛暟2锛氳矾鐢辫矾寰勶紙绐楀彛缂栧彿锛?    //鍙傛暟3锛氶厤缃暟缁勶紙绐楀彛鐨勮惀涓氳鍒欙級
    register_rest_route('myapp/v1','/top-products',[
        'methods' => 'GET',
        'callback' => 'myapp_get_top_products',
        'permission_callback' => 'return_true',
    ]);
});

/**
 * REST API 鍥炶皟鍑芥暟
 * 
 * 鏌ヨ閿€閲忔渶楂樼殑鍓?涓晢鍝侊紝鏍煎紡鍖栬繑鍥? * 
 * @param WP_REST_Request $request  WP 鑷姩浼犲叆鐨勮姹傚璞? * @return WP_REST_Response         WP 鑷姩杞负 JSON 杈撳嚭
 */
function myapp_get_top_products($request)
{
    //鏌ヨ锛氭寜 total_sales 闄嶅簭锛屽彧鎷垮晢鍝両D
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

    //濡傛灉娌℃煡鍒颁换浣曞晢鍝侊紝杩斿洖绌烘暟缁?    if (empty($query->posts)) {
        return rest_ensure_response([
            'products' => [],
            'count' => 0,
        ]);
    }

    $products_data = [];

    foreach ($query->posts as $product_id) {
        //wc_get_product()鏄痺c鏍囧噯鐨勫晢鍝佸伐鍘傚嚱鏁?        $product = wc_get_product($product_id);
        if(!$product) continue;
        $products_data[] = [
            'id' => $product_id,
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'total_sales' => (int) $product->get_total_sales(),
            'permalink' => get_permalink($product_id),
        ];
    }
    // rest_ensure_response() 纭繚杩斿洖鐨勬槸 WP_REST_Response 瀵硅薄
    return rest_ensure_response([
        'products' => $products_data,
        'count'    => count($products_data),
    ]);
}

/**
 * 涓嬪崟鏃惰褰曡鍗曟潵婧? * 
 * 鎸傝浇鍦╳oocommerce_checkout_order_processed 涓? * 杩欐槸鍦ㄨ鍗曞垱寤哄悗锛岀敤鎴疯烦杞埌鏀粯椤典箣鍓嶇殑閽╁瓙
 * @param int $order_id 鍒氬垰鍒涘缓鐨勮鍗旾D
 * @param array $posted_data 鐢ㄦ埛鎻愪氦鐨勮〃鍗曟暟鎹? * @param WC_Order $order 璁㈠崟瀵硅薄
 */
add_action('woocommerce_checkout_order_processed',function($order_id,$posted_data,$order) {
   // 鍒ゆ柇鏉ユ簮锛氶€氳繃 URL 鍙傛暟 ?from=app 鍒ゆ柇鏄惁鏉ヨ嚜 App
    // 濡傛灉娌℃湁杩欎釜鍙傛暟锛岄粯璁や负 "web"锛堢綉绔欙級
    $source = (isset($_GET['from']) && $_GET['from'] === 'app') ? 'app' : 'web';

    // update_post_meta锛氬啓鍏?wp_postmeta 琛?    // _order_source 鏄嚜瀹氫箟 meta key锛屽姞涓嬪垝绾垮墠缂€琛ㄧず"绉佹湁"瀛楁
    update_post_meta($order_id, '_order_source', $source); 
},10,3);

add_action('pre_get_posts','filter_products_by_price_range');

/**
 * 鍟嗗搧鍒嗙被椤典环鏍肩瓫閫? * 
 * 鍦ㄥ晢鍝佸垎绫婚〉锛坧roduct_cat锛夛紝閫氳繃 URL 鍙傛暟 ?min_price= &max_price= 绛涢€変环鏍煎尯闂? * 鎸傝浇鍦?pre_get_posts 閽╁瓙涓娾€斺€斿湪 SQL 鏌ヨ鎵ц涔嬪墠鎷︽埅骞朵慨鏀? *
 * @param WP_Query $query 褰撳墠鏌ヨ瀵硅薄锛堝紩鐢ㄤ紶閫掞級
 */

function filter_products_by_price_range($query)
{
    //瀹夊叏涓変欢濂楋紝鍙奖鍝嶄富鏌ヨ+鍙奖鍝嶅晢鍝佸垎绫婚〉
    if(is_admin()) return;  //涓嶅湪鍚庡彴鐢熸晥
    if (!$query->is_main_query()) return; //鍙敼涓绘煡璇紙涓嶆槸鍓惊鐜級
    if (!$query->is_tax('product_cat')) return;
    //浠巙rl鍙傛暟鑾峰彇浠锋牸鍊?    // floatval() 鎶婇潪娉曡緭鍏ワ紙濡?鈥渁bc鈥濓級杞负0锛屽ぉ鐒堕槻娉ㄥ叆
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;

    // 杈圭晫鍒ゆ柇锛氬鏋滀袱涓弬鏁伴兘娌′紶 鎴栭兘鏄?锛屼笉骞查鏌ヨ
    if ($min_price <= 0 && $max_price <= 0) return;

    //鑾峰彇宸叉湁鐨刴eta_query (淇濈暀鍏朵粬鎻掍欢鍙兘鍔犵殑鏉′欢)
    $meta_query = $query->get('meta_query',[]);

    // 鈶?鏋勯€犱环鏍煎尯闂存潯浠?    if ($min_price > 0) {
        $meta_query[] = [
            'key'     => '_price',        // WC 瀛樺偍褰撳墠浠锋牸鐨?meta key
            'value'   => $min_price,      // 鏈€灏忓€?            'compare' => '>=',            // 澶т簬绛変簬
            'type'    => 'DECIMAL(10,2)', // 鎸夊皬鏁版瘮杈冿紝涓嶆槸瀛楃涓诧紒
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

    // 鈶?濡傛灉鍚屾椂鏈夋渶灏忓拰鏈€澶т环鏍硷紝璁剧疆 relation 涓?AND
    if ($min_price > 0 && $max_price > 0) {
        $meta_query['relation'] = 'AND';
    }

    // 鈶?鎶婁慨鏀瑰悗鐨?meta_query 鍐欏洖鏌ヨ瀵硅薄
    $query->set('meta_query', $meta_query);

}

/**
 * Q12锛欰CF 瀛楁缁?鈥斺€?浜у搧鎵嬪唽 PDF
 * 
 * 缁?WC 鍟嗗搧娣诲姞"浜у搧鎵嬪唽 PDF"鏂囦欢涓婁紶瀛楁
 * 椤惧鍙互鍦ㄥ晢鍝佽鎯呴〉涓嬭浇
 * 
 * 鎸傝浇鍦?acf/init 閽╁瓙涓娾€斺€擜CF 鍒濆鍖栨椂娉ㄥ唽
 */
add_action('acf/init','register_product_pdf_field');

function register_product_pdf_field()
{
    //瀹夊叏闃叉姢锛歛cf鏈縺娲讳笉鎶ラ敊
    if(!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_product_pdf',
        'title' => '浜у搧鎵嬪唽涓嬭浇',

        //location: 褰撶紪杈戠殑鏂囩珷绫诲瀷鏄痯roduct鏃讹紝鏄剧ず瀛楁缁?        'location' => [
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
                'label' => '浜у搧鎵嬪唽pdf',
                'name' => 'product_manual',
                'type' => 'file',
                // return_format = 'array' 鈫?杩斿洖鏂囦欢鏁扮粍
                // ['url', 'filename', 'filesize', 'mime_type', ...]
                // 姣?'url' 鏇寸伒娲伙紝鍙互鍚屾椂鎷垮埌鏂囦欢鍚嶅拰鏂囦欢澶у皬
                'return_format' => 'array',

                // 鍙厑璁?PDF
                'mime_types'    => 'pdf',

                // 鍗曚釜鏂囦欢锛堜笉鏄鏂囦欢锛?                'multiple'      => 0,
            ],
        ],
        // 楂樼骇璁剧疆
        'menu_order' => 0,
        'position'   => 'acf_after_title', // 鍦ㄦ爣棰樹笅鏂规樉绀?        'style'      => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active'     => true,
    ]);
}

/**
 * 鍦ㄥ晢鍝佽鎯呴〉杈撳嚭"浜у搧鎵嬪唽 PDF"涓嬭浇閾炬帴
 * 
 * 浣嶇疆锛?woocommerce_single_product_summary閽╁瓙
 * 浼樺厛绾э細 25锛堝湪鍔犺喘鎸夐挳 20涔嬪悗锛宻ku/鍒嗙被 30涔嬪墠锛? * 鏉′欢锛氫粎褰撳晢鍝佷笂浼犱簡pdf鏃舵墠鏄剧ず
 */
add_action('woocommerce_single_product_summary', 'display_product_manual_link', 25);

function display_product_manual_link() 
{
    //纭繚鍦ㄥ晢鍝侀〉闈㈠唴
    global $product;
    if(!$product || !is_a($product,'WC_Product')) return;

    // get_field() 鏄痑cf鎻愪緵鐨勫嚱鏁?    //绗竴涓弬鏁帮細 瀛楁鍚嶏紙鍜屾敞鍐屾椂鐨刵ame涓€鑷达級
    //绗簩涓弬鏁帮紝鏂囩珷id锛屼笉浼犻粯璁ゅ綋鍓峱ost
    //杩斿洖鏁扮粍锛?銆?url','title','filename','filesize','mime_type',銆?    $manual = get_field('product_manual',$product->get_id());
    //娌′笂浼爌df灏变笉鏄剧ず
    if (!$manual || empty($manual['url'])) return;

    ?>
<div class="product-manual-download" style="margin-top: 15px;">
        <a href="<?php echo esc_url($manual['url']); ?>"
           target="_blank"
           rel="noopener noreferrer"
           class="button manual-download-btn"
           download>
            <?php
            // 濡傛灉瀛樹簡鏂囦欢鍚嶏紝鏄剧ず鏂囦欢鍚嶏紱鍚﹀垯鏄剧ず閫氱敤鏂囧瓧
            $link_text = !empty($manual['filename'])
                ? sprintf(__('馃搫 涓嬭浇浜у搧鎵嬪唽锛?s锛?, 'corporate-theme'), $manual['filename'])
                : __('馃搫 涓嬭浇浜у搧鎵嬪唽', 'corporate-theme');

            echo esc_html($link_text);
            ?>
        </a>
    </div>
    <?php
}

//姣忓ぉ鍑屾櫒3鐐规洿鏂版眹鐜?add_action('wp','schedule_exchange_rate_update');  //娉ㄥ唽瀹氭椂鍣?
add_action('daily_exchange_rate_update','fetch_latest_rates');//瑙﹀彂瀹氭椂鍣?
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

//浠锋牸鎹㈢畻杩囨护
add_filter('woocommerce_product_get_price','convert_product_price',10,2);


function convert_product_price(float $price, WC_Product $product){
    $target_currency = get_woocommerce_currency();

    // 濡傛灉鐩爣璐у竵鏄痷sd锛堝熀鍑嗭級 鐩存帴杩斿洖
    if($target_currency === 'USD') return $price;
    
    $rates = get_option('exchange_rates',[]);
    $rate = $rates[$target_currency]??1;
    return round($price*$rate,2);
}

add_action('woocommerce_checkout_create_order','mark_order_origin',10,2);

//璁㈠崟鏍囪鏉ユ簮锛堣 1 涓挬瀛愶級
function mark_order_origin( WC_Order $order, $data ) {
    $order->update_meta_data('_order_currency', get_woocommerce_currency());
    $order->update_meta_data('_order_language', get_locale());
}

// ==========================================
// 璐т唬涓撳睘锛氳揣杩愯拷韪?CPT
// ==========================================
/**
 * 娉ㄥ唽璐ц繍杩借釜锛圫hipment锛夎嚜瀹氫箟鏂囩珷绫诲瀷
 * 
 * 鐢ㄤ簬瀛樺偍姣忔潯璐ц繍璁㈠崟鐨勮拷韪俊鎭? * 鏀寔杩借釜缂栧彿銆佽捣杩愭腐銆佺洰鐨勬腐銆佽揣鐗╃姸鎬佺瓑
 */
function freight_register_shipment_cpt() {
    $labels = [
        'name'               => _x('璐ц繍璁㈠崟', 'post type general name', 'corporate-theme'),
        'singular_name'      => _x('璐ц繍璁㈠崟', 'post type singular name', 'corporate-theme'),
        'add_new'            => __('鏂板璐ц繍', 'corporate-theme'),
        'add_new_item'       => __('鏂板璐ц繍璁㈠崟', 'corporate-theme'),
        'edit_item'          => __('缂栬緫璐ц繍璁㈠崟', 'corporate-theme'),
        'view_item'          => __('鏌ョ湅璐ц繍璁㈠崟', 'corporate-theme'),
        'search_items'       => __('鎼滅储璐ц繍璁㈠崟', 'corporate-theme'),
        'not_found'          => __('娌℃湁鎵惧埌璐ц繍璁㈠崟', 'corporate-theme'),
        'not_found_in_trash' => __('鍥炴敹绔欎腑娌℃湁璐ц繍璁㈠崟', 'corporate-theme'),
        'all_items'          => __('鍏ㄩ儴璐ц繍璁㈠崟', 'corporate-theme'),
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
 * 娉ㄥ唽璐ц繍鐘舵€佸垎绫绘硶
 */
function freight_register_status_taxonomy() {
    $labels = [
        'name'              => _x('璐ц繍鐘舵€?, 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('璐ц繍鐘舵€?, 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('鎼滅储鐘舵€?, 'corporate-theme'),
        'all_items'         => __('鍏ㄩ儴鐘舵€?, 'corporate-theme'),
        'edit_item'         => __('缂栬緫鐘舵€?, 'corporate-theme'),
        'update_item'       => __('鏇存柊鐘舵€?, 'corporate-theme'),
        'add_new_item'      => __('娣诲姞鏂扮姸鎬?, 'corporate-theme'),
        'new_item_name'     => __('鏂扮姸鎬佸悕绉?, 'corporate-theme'),
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
 * 娉ㄥ唽杩愯緭鏂瑰紡鏍囩
 */
function freight_register_mode_tag() {
    $labels = [
        'name'              => _x('杩愯緭鏂瑰紡', 'taxonomy general name', 'corporate-theme'),
        'singular_name'     => _x('杩愯緭鏂瑰紡', 'taxonomy singular name', 'corporate-theme'),
        'search_items'      => __('鎼滅储杩愯緭鏂瑰紡', 'corporate-theme'),
        'all_items'         => __('鍏ㄩ儴杩愯緭鏂瑰紡', 'corporate-theme'),
        'edit_item'         => __('缂栬緫杩愯緭鏂瑰紡', 'corporate-theme'),
        'update_item'       => __('鏇存柊杩愯緭鏂瑰紡', 'corporate-theme'),
        'add_new_item'      => __('娣诲姞鏂拌繍杈撴柟寮?, 'corporate-theme'),
        'new_item_name'     => __('鏂拌繍杈撴柟寮忓悕绉?, 'corporate-theme'),
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
// 璐т唬涓撳睘锛歊EST API 杩借釜绔偣
// ==========================================
/**
 * 娉ㄥ唽璐ц繍杩借釜 REST API 绔偣
 * 
 * GET /wp-json/freight/v1/track?tracking_no=XXX
 * 杩斿洖璇ヨ揣杩愯鍗曠殑褰撳墠鐘舵€佷俊鎭? */
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
 * REST API 杩借釜鏌ヨ鍥炶皟
 *
 * @param WP_REST_Request $request 璇锋眰瀵硅薄
 * @return WP_REST_Response JSON 鍝嶅簲
 */
function freight_track_shipment($request) {
    $tracking_no = $request->get_param('tracking_no');

    // 閫氳繃鑷畾涔夊瓧娈?_tracking_number 鏌ヨ鍖归厤鐨勮揣杩愯鍗?    $shipments = get_posts([
        'post_type'      => 'shipment',
        'posts_per_page' => 1,
        'meta_key'       => '_tracking_number',
        'meta_value'     => $tracking_no,
        'fields'         => 'ids',
    ]);

    if (empty($shipments)) {
        return rest_ensure_response([
            'success' => false,
            'message' => '鏈壘鍒拌杩借釜缂栧彿鐨勮揣鐗╀俊鎭?,
        ]);
    }

    $shipment_id = $shipments[0];
    $status_terms = wp_get_post_terms($shipment_id, 'shipment_status', ['fields' => 'names']);
    $mode_terms   = wp_get_post_terms($shipment_id, 'shipping_mode', ['fields' => 'names']);

    $data = [
        'success'      => true,
        'tracking_no'  => $tracking_no,
        'status'       => !empty($status_terms) ? $status_terms[0] : '寰呭鐞?,
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
// 璐т唬涓撳睘锛欰CF 璐ц繍璁㈠崟瀛楁缁?// ==========================================
/**
 * 鐢?ACF 娉ㄥ唽璐ц繍璁㈠崟鐨勮缁嗕俊鎭瓧娈? * 鍖呭惈锛氳拷韪紪鍙枫€佽捣杩愭腐銆佺洰鐨勬腐銆佽揣鐗╅噸閲?浣撶Н銆侀璁＄娓?鍒版腐鏃堕棿
 */
add_action('acf/init', 'freight_register_acf_fields');
function freight_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key'      => 'group_freight_shipment',
        'title'    => '璐ц繍璇︾粏淇℃伅',
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
                'label' => '杩借釜缂栧彿',
                'name'  => 'tracking_number',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_origin_port',
                'label' => '璧疯繍娓?,
                'name'  => 'origin_port',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_destination_port',
                'label' => '鐩殑娓?,
                'name'  => 'destination_port',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_cargo_weight',
                'label' => '璐х墿閲嶉噺 (kg)',
                'name'  => 'cargo_weight',
                'type'  => 'number',
            ],
            [
                'key'   => 'field_cargo_volume',
                'label' => '璐х墿浣撶Н (CBM)',
                'name'  => 'cargo_volume',
                'type'  => 'number',
                'step'  => '0.01',
            ],
            [
                'key'   => 'field_etd',
                'label' => '棰勮绂绘腐鏃堕棿 (ETD)',
                'name'  => 'etd',
                'type'  => 'date_picker',
            ],
            [
                'key'   => 'field_eta',
                'label' => '棰勮鍒版腐鏃堕棿 (ETA)',
                'name'  => 'eta',
                'type'  => 'date_picker',
            ],
            [
                'key'   => 'field_last_update_time',
                'label' => '鏈€鍚庢洿鏂版椂闂?,
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
// 璐т唬涓撳睘锛氳拷韪煡璇㈢煭浠ｇ爜 [tracking_form]锛圓JAX 鐗堬級
// ==========================================
/**
 * 璐ц繍杩借釜鏌ヨ琛ㄥ崟鐭唬鐮? * 
 * 鐢ㄦ硶锛歔tracking_form]
 * 鍓嶇鏄剧ず杈撳叆妗?+ 鏌ヨ鎸夐挳锛岄€氳繃 AJAX 寮傛鏌ヨ锛屼笉鍒锋柊椤甸潰
 * 
 * 瀹夊叏鏈哄埗锛? *   1. wp_nonce 楠岃瘉璇锋眰鏉ユ簮
 *   2. sanitize_text_field 娓呯悊杈撳叆
 *   3. 杈撳嚭鐢?esc_html 杞箟
 */
add_shortcode('tracking_form', 'freight_tracking_form_shortcode');
function freight_tracking_form_shortcode() {
    ob_start();
    ?>
    <div class="tracking-form-wrapper p-4 bg-light rounded border">
        <h4 class="mb-3"><?php esc_html_e('璐х墿杩借釜鏌ヨ', 'corporate-theme'); ?></h4>
        <form class="tracking-form row g-2" id="freight-tracking-form">
            <div class="col-md-8">
                <input type="text"
                       id="tracking_no_input"
                       class="form-control form-control-lg"
                       placeholder="<?php esc_attr_e('璇疯緭鍏ヨ拷韪紪鍙凤細渚嬪FRE-20260708-0001', 'corporate-theme'); ?>"
                       required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-lg w-100" id="tracking-submit-btn">
                    <?php esc_html_e('鏌ヨ', 'corporate-theme'); ?>
                </button>
            </div>
        </form>
        <!-- AJAX 鏌ヨ缁撴灉瀹瑰櫒 -->
        <div id="tracking-result" class="mt-4"></div>
    </div>
    <?php
    return ob_get_clean();
}

// ==========================================
// 杩借釜鏌ヨ AJAX 澶勭悊鍑芥暟
// ==========================================
/**
 * 澶勭悊 AJAX 杩借釜鏌ヨ璇锋眰
 * 鍦?admin-ajax.php 涓€氳繃 wp_ajax_ 鍜?wp_ajax_nopriv_ 閽╁瓙娉ㄥ唽
 * 杩斿洖 JSON 鏍煎紡锛歿 success: true/false, html: "..." }
 */
add_action('wp_ajax_freight_track', 'freight_ajax_track_shipment');
add_action('wp_ajax_nopriv_freight_track', 'freight_ajax_track_shipment');
function freight_ajax_track_shipment() {

    // 楠岃瘉 nonce
    if (!wp_verify_nonce($_POST['nonce'], 'freight_tracking_nonce')) {
        wp_send_json_error(['html' => '<div class="alert alert-danger">瀹夊叏楠岃瘉澶辫触锛岃鍒锋柊椤甸潰閲嶈瘯銆?/div>']);
    }

    $tracking_no = isset($_POST['tracking_no']) ? sanitize_text_field($_POST['tracking_no']) : '';

    if (empty($tracking_no)) {
        wp_send_json_error(['html' => '<div class="alert alert-warning">璇疯緭鍏ヨ拷韪紪鍙枫€?/div>']);
    }

    // 澶嶇敤 REST API 鏌ヨ鍑芥暟
    $request = new WP_REST_Request('GET');
    $request->set_param('tracking_no', $tracking_no);
    $response = freight_track_shipment($request);
    $data = $response->get_data();

    ob_start();

    if ($data['success']) : ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong><?php esc_html_e('杩借釜缂栧彿锛?, 'corporate-theme'); ?></strong>
                <?php echo esc_html($data['tracking_no']); ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><?php esc_html_e('褰撳墠鐘舵€侊細', 'corporate-theme'); ?></strong>
                            <span class="badge bg-success"><?php echo esc_html($data['status']); ?></span>
                        </p>
                        <p><strong><?php esc_html_e('杩愯緭鏂瑰紡锛?, 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['mode'] ?: '鈥?); ?></p>
                        <p><strong><?php esc_html_e('璧疯繍娓細', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['origin'] ?: '鈥?); ?></p>
                        <p><strong><?php esc_html_e('鐩殑娓細', 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['destination'] ?: '鈥?); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><?php esc_html_e('璐х墿閲嶉噺锛?, 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['weight'] ? $data['weight'] . ' kg' : '鈥?); ?></p>
                        <p><strong><?php esc_html_e('璐х墿浣撶Н锛?, 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['volume'] ? $data['volume'] . ' CBM' : '鈥?); ?></p>
                        <p><strong><?php esc_html_e('棰勮绂绘腐锛?, 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['etd'] ?: '鈥?); ?></p>
                        <p><strong><?php esc_html_e('棰勮鍒版腐锛?, 'corporate-theme'); ?></strong>
                            <?php echo esc_html($data['eta'] ?: '鈥?); ?></p>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    <?php esc_html_e('鏈€鍚庢洿鏂帮細', 'corporate-theme'); ?>
                    <?php echo esc_html($data['last_update'] ?: '鈥?); ?>
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
// 璐т唬涓撳睘锛歐ooCommerce 鍟嗗搧璇︽儏澧炲姞璐ц繍淇℃伅
// ==========================================
/**
 * 鍦ㄥ晢鍝佽鎯呴〉鏄剧ず璐ц繍璇存槑
 * 鏇挎崲鍘熸湁閫氱敤鍐呭涓鸿揣浠ｅ満鏅浉鍏充俊鎭? */
remove_action('woocommerce_single_product_summary', 'corporate_add_product_extra_info', 25);
add_action('woocommerce_single_product_summary', 'freight_product_shipping_info', 25);
function freight_product_shipping_info() {
    if (!is_product()) {
        return;
    }
    echo '<div class="freight-shipping-info mt-3 p-3 bg-light rounded border">';
    echo '<h6 class="fw-bold mb-2">馃殺 璐ц繍鏈嶅姟璇存槑</h6>';
    echo '<p class="mb-1 small"><strong>馃摝 杩愯緭鏂瑰紡锛?/strong>娴疯繍鏁存煖 / 娴疯繍鎷兼煖 / 绌鸿繍 / 闄嗚繍</p>';
    echo '<p class="mb-1 small"><strong>馃審 鏈嶅姟鑼冨洿锛?/strong>鍏ㄧ悆涓昏娓彛闂ㄥ埌闂ㄦ湇鍔?/p>';
    echo '<p class="mb-1 small"><strong>馃搫 鎶ュ叧鏈嶅姟锛?/strong>鎻愪緵涓€绔欏紡鎶ュ叧銆佹姤妫€銆佷繚闄╂湇鍔?/p>';
    echo '<p class="mb-0 small"><strong>馃挕 鍦ㄧ嚎杩借釜锛?/strong>涓嬪崟鍚庤幏鍙栬拷韪紪鍙凤紝闅忔椂鏌ヨ璐х墿鐘舵€?/p>';
    echo '</div>';
}

// ==========================================
// 璐т唬涓撳睘锛氫繚瀛樿揣杩愯鍗曟椂鑷姩鐢熸垚杩借釜缂栧彿
// ==========================================
/**
 * 鍙戝竷璐ц繍璁㈠崟鏃讹紝濡傛灉鏈～鍐欒拷韪紪鍙峰垯鑷姩鐢熸垚
 */
add_action('save_post_shipment', 'freight_auto_generate_tracking_no', 10, 3);
function freight_auto_generate_tracking_no($post_id, $post, $update) {
    // 鑷姩淇濆瓨鏃惰烦杩?    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 宸叉湁杩借釜缂栧彿鍒欒烦杩?    $existing_no = get_post_meta($post_id, '_tracking_number', true);
    if (!empty($existing_no)) {
        return;
    }

    // 鐢熸垚鍞竴杩借釜缂栧彿锛欶RE-骞存湀鏃?闅忔満4浣嶆暟瀛?    $tracking_no = 'FRE-' . date('Ymd') . '-' . wp_rand(1000, 9999);
    update_post_meta($post_id, '_tracking_number', $tracking_no);
    update_post_meta($post_id, '_last_update_time', current_time('mysql'));
}

// ==========================================
// SEO 浼樺寲锛氬幓闄?WP 澶撮儴澶氫綑鏍囩
// ==========================================
/**
 * 绉婚櫎涓嶅繀瑕佺殑 wp_head 杈撳嚭锛屽噺灏?HTML 浣撶Н
 * 杩欎簺鏍囩瀵?SEO 娌℃湁甯姪锛屽弽鑰屾毚闇叉妧鏈粏鑺? */
remove_action('wp_head', 'wp_generator');           // 绉婚櫎 WordPress 鐗堟湰鍙?remove_action('wp_head', 'wlwmanifest_link');        // 绉婚櫎 Windows Live Writer 鍗忚
remove_action('wp_head', 'rsd_link');                // 绉婚櫎 RSD (Really Simple Discovery)
remove_action('wp_head', 'wp_shortlink_wp_head');   // 绉婚櫎鐭摼鎺?remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10); // 绉婚櫎鏂囩珷鍏崇郴閾炬帴

// ==========================================
// SEO 浼樺寲锛氱粨鏋勫寲鏁版嵁锛圫chema.org JSON-LD锛?// ==========================================
/**
 * 鍦ㄩ〉闈㈠ご閮ㄨ緭鍑?JSON-LD 缁撴瀯鍖栨暟鎹? * 甯姪鎼滅储寮曟搸鐞嗚В缃戠珯鍐呭锛屾彁鍗囨悳绱㈢粨鏋滃睍绀烘晥鏋? * 
 * 杈撳嚭浠ヤ笅 Schema 绫诲瀷锛? *   - Organization锛氬叕鍙镐俊鎭紙鍚嶇О銆佹弿杩般€佹湇鍔¤寖鍥达級
 *   - WebSite锛氱珯鐐规悳绱㈠姛鑳? *   - BreadcrumbList锛氶潰鍖呭睉瀵艰埅璺緞锛堝湪闈㈠寘灞戝嚱鏁颁腑杈撳嚭锛? *   - Article锛氭枃绔犲唴瀹归〉
 */
add_action('wp_head', 'freight_schema_structured_data');
function freight_schema_structured_data() {

    // 鈹€鈹€ 鍙湪棣栭〉杈撳嚭 Organization + WebSite Schema 鈹€鈹€
    if (is_front_page() || is_home()) {
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
            "description": "<?php echo esc_js(get_bloginfo('description')); ?>",
            "url": "<?php echo esc_js(home_url()); ?>",
            "areaServed": ["涓浗", "鍏ㄧ悆"],
            "serviceType": ["鍥介檯娴疯繍", "绌鸿繍璐ц繍", "闄嗚繍杩愯緭", "鎶ュ叧浠ｇ悊", "浠撳偍閰嶉€?]
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

    // 鈹€鈹€ 鏂囩珷璇︽儏椤佃緭鍑?Article Schema 鈹€鈹€
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
// SEO 浼樺寲锛歄pen Graph / Twitter Card 鏍囩
// ==========================================
/**
 * 鍦ㄩ〉闈㈠ご閮ㄨ緭鍑?OG 鍜?Twitter Card 鍏冩爣绛? * 
 * 浣滅敤锛氬綋椤甸潰琚垎浜埌寰俊銆丩inkedIn銆乀witter銆丗acebook 鏃讹紝
 *       鑷姩鏄剧ず鏍囬銆佹弿杩板拰鍥剧墖锛屾彁鍗囧搧鐗屾洕鍏夊拰鐐瑰嚮鐜? */
add_action('wp_head', 'freight_og_tags');
function freight_og_tags() {

    // 榛樿鍊硷細棣栭〉鐢ㄧ珯鐐逛俊鎭?    $og_title       = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url         = home_url();
    $og_image       = get_site_icon_url() ?: '';
    $og_type        = 'website';

    // 鏂囩珷/椤甸潰璇︽儏椤碉細鐢ㄦ枃绔犱俊鎭鐩栭粯璁ゅ€?    if (is_single() || is_page()) {
        $og_title       = get_the_title();
        $excerpt        = get_the_excerpt() ?: get_the_content();
        $og_description = wp_trim_words($excerpt, 30);
        $og_url         = get_permalink();
        $thumbnail_id   = get_post_thumbnail_id();
        $og_image       = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'large') : $og_image;
        $og_type        = is_single() ? 'article' : 'website';
    }

    // 鍒嗙被/鏍囩椤?    if (is_category() || is_tax()) {
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
// SEO 浼樺寲锛歁eta Description 鑷姩鐢熸垚
// ==========================================
/**
 * 涓烘瘡涓〉闈㈣嚜鍔ㄧ敓鎴愬悎閫傜殑 meta description
 * 涓嶄緷璧?SEO 鎻掍欢锛岄€氳繃 wp_head 閽╁瓙杈撳嚭
 * 
 * 浼樺厛绾э細鏂囩珷鎽樿 鈫?鍐呭鎴彇 鈫?鍒嗙被鎻忚堪 鈫?绔欑偣鎻忚堪
 */
add_action('wp_head', 'freight_meta_description');
function freight_meta_description() {
    $description = '';

    if (is_single() || is_page()) {
        // 鏂囩珷/椤甸潰锛氫紭鍏堢敤鎽樿锛屾病鏈夋憳褰曞垯鎴彇姝ｆ枃鍓?30 瀛?        $excerpt = get_the_excerpt();
        $description = $excerpt
            ? wp_trim_words($excerpt, 30)
            : wp_trim_words(get_the_content(), 30);
    } elseif (is_home() || is_front_page()) {
        // 棣栭〉锛氱敤绔欑偣鎻忚堪
        $description = get_bloginfo('description');
    } elseif (is_category() || is_tax()) {
        // 鍒嗙被/鏍囩椤碉細鐢ㄥ垎绫绘弿杩?        $description = category_description();
        if (!$description) {
            $description = single_term_title('', false) . ' 鈥?鐩稿叧璐ц繍鐗╂祦璧勮';
        }
    } elseif (is_post_type_archive('shipment')) {
        // 璐ц繍杩借釜褰掓。椤碉細鍥哄畾鎻忚堪
        $description = '瀹炴椂鏌ヨ璐х墿杩愯緭鐘舵€侊紝杈撳叆杩借釜缂栧彿鑾峰彇鏈€鏂扮墿娴佷俊鎭€?;
    } elseif (is_search()) {
        // 鎼滅储缁撴灉椤?        $description = '鎼滅储"' . get_search_query() . '"鐨勭浉鍏宠揣杩愮墿娴佸唴瀹?;
    }

    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    }
}

// ==========================================
// SEO 浼樺寲锛欳anonical URL
// ==========================================
/**
 * 杈撳嚭 canonical 鏍囩锛岄槻姝㈤噸澶嶅唴瀹归棶棰? * 
 * 璐ц繍鏈嶅姟椤甸潰鍙兘閫氳繃涓嶅悓 URL 璁块棶锛堝甯︽帓搴忓弬鏁般€佽拷韪弬鏁帮級锛? * canonical 鍛婅瘔鎼滅储寮曟搸鍝釜鏄潈濞佺増鏈紝閬垮厤琚垽瀹氫负閲嶅鍐呭闄嶆潈
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
// SEO 浼樺寲锛氬欢杩熷姞杞介潪鍏抽敭 JS
// ==========================================
/**
 * 缁?Bootstrap JS 鍔犱笂 defer 灞炴€? * 璁?JS 鍦?HTML 瑙ｆ瀽瀹屾瘯鍚庡啀鎵ц锛屼笉闃诲椤甸潰娓叉煋
 * 鎻愬崌 Core Web Vitals 涓殑 LCP锛堟渶澶у唴瀹规覆鏌擄級璇勫垎
 */
add_filter('script_loader_tag', 'freight_defer_scripts', 10, 3);
function freight_defer_scripts($tag, $handle, $src) {
    // 鍙 Bootstrap JS 鐢熸晥锛屼笉褰卞搷鍏朵粬鑴氭湰
    if ('bootstrap-bundle' === $handle) {
        return '<script src="' . esc_url($src) . '" defer></script>' . "\n";
    }
    return $tag;
}

// ==========================================
// SEO 浼樺寲锛氶潰鍖呭睉瀵艰埅鍑芥暟
// ==========================================
/**
 * 鐢熸垚闈㈠寘灞戝鑸?HTML锛屾敮鎸佷互涓嬮〉闈㈢被鍨嬶細
 *   - 棣栭〉 鈫?涓嶆樉绀洪潰鍖呭睉
 *   - 鏂囩珷椤?鈫?棣栭〉 / 鍒嗙被 / 鏂囩珷鏍囬
 *   - 椤甸潰 鈫?棣栭〉 / 椤甸潰鏍囬
 *   - 鍒嗙被椤?鈫?棣栭〉 / 鍒嗙被鍚嶇О
 *   - 璐ц繍杩借釜褰掓。椤?鈫?棣栭〉 / 璐х墿杩借釜
 *   - 鑷畾涔夋枃绔犵被鍨?鈫?棣栭〉 / 褰掓。 / 鍗曠瘒鏍囬
 *   - 鎼滅储椤?鈫?棣栭〉 / 鎼滅储缁撴灉
 * 
 * 浣跨敤鏂瑰紡锛氬湪妯℃澘涓皟鐢?<?php freight_breadcrumb(); ?>
 * 閰嶅悎 BreadcrumbList Schema 缁撴瀯鍖栨暟鎹緭鍑? */
function freight_breadcrumb() {
    // 棣栭〉涓嶆樉绀洪潰鍖呭睉
    if (is_front_page()) {
        return;
    }

    $home_url  = home_url('/');
    $separator = '<span class="breadcrumb-separator mx-2 text-muted">/</span>';
    $items     = [];

    // 绗竴椤癸細棣栭〉閾炬帴
    $items[] = '<a href="' . esc_url($home_url) . '" class="text-muted text-decoration-none">'
               . esc_html__('棣栭〉', 'corporate-theme') . '</a>';

    // 鈹€鈹€ 鏂囩珷璇︽儏椤?鈹€鈹€
    if (is_single()) {
        $post_type = get_post_type();
        if ('post' === $post_type) {
            // 鏅€氭枃绔狅細鏄剧ず鍒嗙被
            $categories = get_the_category();
            if (!empty($categories)) {
                $items[] = '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '"
                           class="text-muted text-decoration-none">'
                           . esc_html($categories[0]->name) . '</a>';
            }
        } elseif ('shipment' === $post_type) {
            // 璐ц繍璁㈠崟锛氭樉绀哄綊妗ｉ摼鎺?            $items[] = '<a href="' . esc_url(get_post_type_archive_link('shipment')) . '"
                           class="text-muted text-decoration-none">'
                           . esc_html__('璐х墿杩借釜', 'corporate-theme') . '</a>';
        } elseif ('portfolio' === $post_type) {
            $items[] = '<a href="' . esc_url(get_post_type_archive_link('portfolio')) . '"
                           class="text-muted text-decoration-none">'
                           . esc_html__('瀹㈡埛妗堜緥', 'corporate-theme') . '</a>';
        }
        // 褰撳墠鏂囩珷鏍囬锛堜笉鍙偣鍑伙級
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html(get_the_title()) . '</span>';
    }

    // 鈹€鈹€ 椤甸潰 鈹€鈹€
    elseif (is_page()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html(get_the_title()) . '</span>';
    }

    // 鈹€鈹€ 鍒嗙被/鏍囩/鑷畾涔夊垎绫绘硶椤?鈹€鈹€
    elseif (is_category() || is_tax() || is_tag()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html(single_term_title('', false)) . '</span>';
    }

    // 鈹€鈹€ 鑷畾涔夋枃绔犵被鍨嬪綊妗ｉ〉 鈹€鈹€
    elseif (is_post_type_archive()) {
        $post_type = get_post_type();
        $label = 'shipment' === $post_type
            ? __('璐х墿杩借釜', 'corporate-theme')
            : post_type_archive_title('', false);
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html($label) . '</span>';
    }

    // 鈹€鈹€ 鎼滅储椤?鈹€鈹€
    elseif (is_search()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . sprintf(__('鎼滅储缁撴灉锛?s', 'corporate-theme'), get_search_query()) . '</span>';
    }

    // 鈹€鈹€ 404 椤?鈹€鈹€
    elseif (is_404()) {
        $items[] = '<span class="text-dark" aria-current="page">'
                   . esc_html__('椤甸潰鏈壘鍒?, 'corporate-theme') . '</span>';
    }

    // 杈撳嚭闈㈠寘灞?    echo '<nav class="breadcrumb-container py-2" aria-label="breadcrumb">';
    echo implode($separator, $items);
    echo '</nav>';
}

// ==========================================
// 绗竴妗ｏ細婕旂ず鏁版嵁绉嶅瓙鑴氭湰
// ==========================================
/**
 * 涓€閿～鍏呮紨绀烘暟鎹? * 
 * 鐢ㄦ硶锛氱鐞嗗憳鐧诲綍鍚庤闂?https://浣犵殑缃戠珯/?seed_demo=1
 * 浼氳嚜鍔ㄥ垱寤虹ず渚嬭揣杩愯鍗曘€佹枃绔犮€侀〉闈€乄ooCommerce 鍟嗗搧
 * 
 * 瀹夊叏淇濇姢锛? *   1. 浠呯鐞嗗憳鍙Е鍙戯紙current_user_can('manage_options')锛? *   2. 浠呮墽琛屼竴娆★紙鍒涘缓 option 鏍囪浣嶏級
 *   3. 鐢?wp_insert_post 鑰岄潪鐩存帴 SQL 鎿嶄綔
 */
add_action('init', 'freight_seed_demo_data');
function freight_seed_demo_data() {

    // 浠呭綋 URL 鍙傛暟 seed_demo=1 涓旀槸绠＄悊鍛樻椂瑙﹀彂
    if (!isset($_GET['seed_demo']) || '1' !== $_GET['seed_demo']) {
        return;
    }
    if (!current_user_can('manage_options')) {
        wp_die('浠呯鐞嗗憳鍙墽琛屾暟鎹～鍏呮搷浣溿€?);
    }

    // 闃叉閲嶅鎵ц
    if (get_option('freight_demo_seeded')) {
        wp_die('婕旂ず鏁版嵁宸插～鍏咃紝濡傞渶閲嶆柊濉厖璇峰垹闄?wp_options 琛ㄤ腑鐨?freight_demo_seeded 閫夐」銆?);
    }

    // 鈹€鈹€ 1. 鍒涘缓绀轰緥璐ц繍璁㈠崟锛圫hipment CPT锛?鈹€鈹€
    $shipments = [
        [
            'title'      => 'FRE-20260708-0001 鈥?涓婃捣鈫掓眽鍫?,
            'status'     => '杩愯緭涓?,
            'mode'       => '娴疯繍',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260708-0001',
                '_origin_port'      => '涓婃捣娓?,
                '_destination_port' => '姹夊牎娓?,
                '_cargo_weight'     => '8500',
                '_cargo_volume'     => '42.5',
                '_etd'              => '2026-07-10',
                '_eta'              => '2026-08-05',
                '_last_update_time' => current_time('mysql'),
            ],
        ],
        [
            'title'      => 'FRE-20260707-0002 鈥?娣卞湷鈫掓礇鏉夌煻',
            'status'     => '宸插埌娓?,
            'mode'       => '绌鸿繍',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260707-0002',
                '_origin_port'      => '娣卞湷瀹濆畨鏈哄満',
                '_destination_port' => '娲涙潐鐭跺浗闄呮満鍦?,
                '_cargo_weight'     => '1200',
                '_cargo_volume'     => '8.2',
                '_etd'              => '2026-07-07',
                '_eta'              => '2026-07-09',
                '_last_update_time' => '2026-07-09 08:30:00',
            ],
        ],
        [
            'title'      => 'FRE-20260706-0003 鈥?瀹佹尝鈫掗箍鐗逛腹',
            'status'     => '宸茬鏀?,
            'mode'       => '娴疯繍',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260706-0003',
                '_origin_port'      => '瀹佹尝鑸熷北娓?,
                '_destination_port' => '楣跨壒涓规腐',
                '_cargo_weight'     => '22000',
                '_cargo_volume'     => '85.0',
                '_etd'              => '2026-06-15',
                '_eta'              => '2026-07-06',
                '_last_update_time' => '2026-07-06 16:00:00',
            ],
        ],
        [
            'title'      => 'FRE-20260705-0004 鈥?骞垮窞鈫掓柊鍔犲潯',
            'status'     => '杩愯緭涓?,
            'mode'       => '闄嗚繍',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260705-0004',
                '_origin_port'      => '骞垮窞鍗楁矙娓?,
                '_destination_port' => '鏂板姞鍧℃腐',
                '_cargo_weight'     => '5600',
                '_cargo_volume'     => '28.3',
                '_etd'              => '2026-07-05',
                '_eta'              => '2026-07-12',
                '_last_update_time' => '2026-07-08 10:15:00',
            ],
        ],
        [
            'title'      => 'FRE-20260704-0005 鈥?闈掑矝鈫掗嚋灞?,
            'status'     => '寰呭鐞?,
            'mode'       => '娴疯繍',
            'meta'       => [
                '_tracking_number'  => 'FRE-20260704-0005',
                '_origin_port'      => '闈掑矝娓?,
                '_destination_port' => '閲滃北娓?,
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
            // 淇濆瓨鑷畾涔夊瓧娈?            foreach ($item['meta'] as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }
            // 璁剧疆鍒嗙被娉?            wp_set_object_terms($post_id, $item['status'], 'shipment_status');
            wp_set_object_terms($post_id, $item['mode'], 'shipping_mode');
        }
    }

    // 鈹€鈹€ 2. 鍒涘缓绀轰緥鐗╂祦璧勮鏂囩珷 鈹€鈹€
    $articles = [
        [
            'title'   => '2026 骞村叏鐞冩捣杩愬競鍦鸿秼鍔垮垎鏋?,
            'content' => '闅忕潃鍏ㄧ悆璐告槗鏍煎眬鐨勬寔缁彉鍖栵紝2026 骞存捣杩愬競鍦哄憟鐜板嚭鍑犱釜閲嶈瓒嬪娍銆傞鍏堬紝鏁板瓧鍖栧彉闈╂鍦ㄥ姞閫熸帹杩涳紝瓒婃潵瓒婂鐨勮埞鍏徃閲囩敤鍖哄潡閾炬妧鏈彁鍗囦緵搴旈摼閫忔槑搴︺€傚叾娆★紝缁胯壊鑸繍鎴愪负涓绘祦锛屽浗闄呮捣浜嬬粍缁囷紙IMO锛夌殑鏂拌鎺ㄥ姩鑸瑰叕鍙稿姞閫熸窐姹拌€佹棫鑸硅埗锛岃浆鍚?LNG 鍙岀噧鏂欏姩鍔涜埞銆傚浜庤揣浠ｄ紒涓氳€岃█锛屾彁鍓嶅竷灞€鏁板瓧鍖栧拰缁胯壊鐗╂祦灏嗘槸鍦ㄦ縺鐑堢珵浜変腑鑴遍鑰屽嚭鐨勫叧閿€?,
            'category' => '琛屼笟鍔ㄦ€?,
        ],
        [
            'title'   => '鍥介檯绌鸿繍璐х墿鍖呰瑙勮寖鎸囧崡',
            'content' => '绌鸿繍璐х墿鍖呰涓庢捣杩愭湁鏄捐憲涓嶅悓锛屾牳蹇冨師鍒欐槸"杞讳究銆佺墷鍥恒€侀槻闇?銆傛湰鏂囪缁嗕粙缁嶅浗闄呯┖杩愮殑鍖呰鏍囧噯锛氫竴銆佸鍖呰搴旈€夌敤楂樺己搴︾摝妤炵焊绠辨垨鏈ㄧ锛岀‘淇濊兘澶熸壙鍙楀爢鐮佸帇鍔涳紱浜屻€佸唴閮ㄥ～鍏呯墿搴斾娇鐢ㄦ场娌鏂欐垨姘斿灚鑶滐紝闃叉璐х墿鍦ㄨ繍杈撹繃绋嬩腑绉讳綅锛涗笁銆佹槗纰庡搧蹇呴』鍦ㄥ绠辨爣娉?鏄撶鐗╁搧"鍜?鍚戜笂"鏍囪瘑锛涘洓銆侀攤鐢垫睜绛夊嵄闄╁搧闇€閬靛惊 IATA DGR 绗?63 鐗堣瀹氾紝鎻愪緵 MSDS 鎶ュ憡銆傛纭殑鍖呰涓嶄粎鑳介檷浣庤揣鎹熺巼锛岃繕鑳介伩鍏嶅洜鍖呰涓嶅綋瀵艰嚧鐨勮繍杈撳欢璇€?,
            'category' => '璐ц繍鐭ヨ瘑',
        ],
        [
            'title'   => '涓鐝垪锛?026 骞存渶鏂拌繍钀ュ姩鎬?,
            'content' => '涓鐝垪浣滀负涓€甯︿竴璺€¤鐨勯噸瑕佽浇浣擄紝2026 骞寸户缁繚鎸佸闀挎€佸娍銆備粖骞翠笂鍗婂勾锛屼腑娆х彮鍒楃疮璁″紑琛岃秴杩?8000 鍒楋紝鍚屾瘮澧為暱 12%銆備富瑕佸彉鍖栧寘鎷細涓€銆佹柊绾胯矾鎸佺画寮€閫氾紝鎴愰兘鑷虫尝鍏伴┈鎷夎垗缁村鐨勮繍杈撴椂闂寸缉鐭嚦 12 澶╋紱浜屻€佸洖绋嬬彮鍒楄浇璐х巼鎻愬崌鑷?75%锛屾洿澶氭娲蹭紭璐ㄤ骇鍝侀€氳繃鐝垪杩涘叆涓浗甯傚満锛涗笁銆佹暟瀛楀寲鏈嶅姟鍗囩骇锛岃揣涓诲彲閫氳繃瀹炴椂杩借釜绯荤粺鏌ヨ闆嗚绠变綅缃€傚浜庤揣浠ｄ紒涓氾紝涓鐝垪鎻愪緵浜嗘瘮娴疯繍鏇村揩銆佹瘮绌鸿繍鏇寸粡娴庣殑涓棿閫夐」銆?,
            'category' => '琛屼笟鍔ㄦ€?,
        ],
        [
            'title'   => '璺ㄥ鐢靛晢鐗╂祦锛氭捣澶栦粨 vs 鐩撮偖妯″紡瀵规瘮',
            'content' => '璺ㄥ鐢靛晢鍗栧鍦ㄧ墿娴佹ā寮忛€夋嫨涓婏紝涓昏闈复娴峰浠撳拰鐩撮偖涓ょ鏂规銆傛捣澶栦粨妯″紡鐨勪紭鐐规槸閰嶉€侀€熷害蹇紙2-3 澶╁彲杈撅級锛岄€€鎹㈣揣鏂逛究锛屼絾闇€瑕佹彁鍓嶅璐э紝鍗犵敤璧勯噾杈冨ぇ銆傜洿閭ā寮忓垯鐏垫椿鎬у己锛屾棤闇€鎻愬墠澶囪揣锛岄€傚悎娴嬭瘯鏂板搧锛屼絾杩愯緭鍛ㄦ湡杈冮暱锛?-15 澶╋級锛屼笖鍙楀浗闄呯墿娴佹尝鍔ㄥ奖鍝嶈緝澶с€傚缓璁崠瀹舵牴鎹骇鍝佸搧绫诲拰閿€鍞瓥鐣ョ伒娲荤粍鍚堬細鐑攢鐖嗘鐢ㄦ捣澶栦粨锛屾柊鍝佹祴璇曠敤鐩撮偖锛屽疄鐜版晥鐜囦笌椋庨櫓鐨勬渶浣冲钩琛°€?,
            'category' => '璐ц繍鐭ヨ瘑',
        ],
        [
            'title'   => '鎶ュ叧甯歌闂瑙ｇ瓟锛欻S Code 褰掔被鎶€宸?,
            'content' => 'HS Code锛堟捣鍏崇紪鐮侊級褰掔被鏄姤鍏崇幆鑺備腑鏈€瀹规槗鍑洪敊鐨勪竴鐜€傚綊绫婚敊璇彲鑳藉鑷存煡楠岀巼涓婂崌銆佸叧绋庡缂寸敋鑷宠揣鐗╄鎵ｃ€傛湰鏂囧垎浜嚑涓疄鐢ㄦ妧宸э細涓€銆佸埄鐢?WCO 鍗忚皟鍒跺害鐨勫墠 6 浣嶈繘琛屽ぇ绫诲畾浣嶏紝鍚庣画浣嶆暟鏍规嵁浜у搧鍏蜂綋鐗瑰緛纭畾锛涗簩銆佹敞鎰忓姛鑳戒笌鐢ㄩ€旂殑鍖哄埆锛屽悓涓€浜у搧鍙兘鍥犵敤閫斾笉鍚岃€屽綊鍏ヤ笉鍚岀紪鐮侊紱涓夈€佸杽鐢ㄥ悇鍥芥捣鍏崇殑棰勮瀹氭湇鍔★紝鎻愬墠纭 HS Code 鐨勫噯纭€э紱鍥涖€佸缓璁紒涓氬缓绔嬪唴閮?HS Code 鏁版嵁搴擄紝璁板綍甯哥敤浜у搧鐨勫綊绫讳緷鎹紝闄嶄綆鍑洪敊鐜囥€?,
            'category' => '鎶ュ叧鎸囧崡',
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
            // 璁剧疆鏂囩珷鍒嗙被锛堜笉瀛樺湪鍒欒嚜鍔ㄥ垱寤猴級
            $term = term_exists($article['category'], 'category');
            if (!$term) {
                $term = wp_insert_term($article['category'], 'category');
            }
            if (!is_wp_error($term)) {
                wp_set_post_terms($post_id, $term['term_id'], 'category');
            }
        }
    }

    // 鈹€鈹€ 3. 鍒涘缓绀轰緥 WooCommerce 鍟嗗搧锛堣揣杩愭湇鍔★級 鈹€鈹€
    if (class_exists('WooCommerce')) {
        $products = [
            [
                'name'        => '涓婃捣鈫掓眽鍫?20GP 鏁存煖娴疯繍',
                'description' => '涓婃捣娓嚦姹夊牎娓?20 鑻卞昂鏍囧噯闆嗚绠辨捣杩愭湇鍔★紝鑸规湡姣忓懆涓€鐝紝杩愯緭鏃堕棿绾?28 澶┿€傚寘鍚腐鍙ｆ搷浣滆垂銆佹枃浠惰垂銆佸熀鏈繍璐广€傞€傜敤鍝佺被锛氭満姊拌澶囥€佺汉缁囧搧銆佺數瀛愪骇鍝併€?,
                'price'       => '2800',
                'weight'      => '22000',
            ],
            [
                'name'        => '娣卞湷鈫掓礇鏉夌煻 绌鸿繍鏅揣鏈嶅姟',
                'description' => '娣卞湷瀹濆畨鑷虫礇鏉夌煻鍥介檯鏈哄満绌鸿繍鏈嶅姟锛屾瘡鍛ㄤ簲鐝€傝繍杈撴椂闂?3-5 澶┿€傚寘鍚噧娌归檮鍔犺垂銆佸畨妫€璐广€傞€傜敤鍝佺被锛氶珮浠峰€肩數瀛愪骇鍝併€佹牱鍝併€佺揣鎬ヨ揣鐗┿€?,
                'price'       => '8500',
                'weight'      => '100',
            ],
            [
                'name'        => '瀹佹尝鈫掗箍鐗逛腹 40HQ 鏁存煖娴疯繍',
                'description' => '瀹佹尝鑸熷北娓嚦楣跨壒涓规腐 40 鑻卞昂楂樻煖娴疯繍鏈嶅姟锛岃埞鏈熸瘡鍛ㄤ袱鐝紝杩愯緭鏃堕棿绾?32 澶┿€傚寘鍚腐鍙ｆ搷浣滆垂銆佹枃浠惰垂銆佸熀鏈繍璐广€傞€傜敤鍝佺被锛氬鍏枫€佹満姊般€佸寲宸ュ搧銆?,
                'price'       => '4200',
                'weight'      => '26000',
            ],
            [
                'name'        => '骞垮窞鈫掓柊鍔犲潯 鎷兼煖娴疯繍鏈嶅姟',
                'description' => '骞垮窞鍗楁矙娓嚦鏂板姞鍧℃腐鎷兼煖锛圠CL锛夋捣杩愭湇鍔★紝姣忓懆涓€鐝€傛寜绔嬫柟绫宠璐癸紝鏈€浣庤捣杩?1 CBM銆傚寘鍚腐鍙ｆ搷浣滆垂銆佹枃浠惰垂銆傞€傜敤鍝佺被锛氬皬鎵归噺璐告槗璐х墿銆佹牱鍝併€?,
                'price'       => '150',
                'weight'      => '0',
            ],
            [
                'name'        => '鍏ㄥ浗涓昏娓彛鈫掍腑浜?闄嗚繍鏈嶅姟',
                'description' => '涓浗涓昏娓彛缁忛湇灏旀灉鏂?闃挎媺灞卞彛鑷充腑浜氫簲鍥斤紙鍝堣惃鍏嬫柉鍧︺€佷箤鍏瑰埆鍏嬫柉鍧︾瓑锛夌殑闄嗚繍鏈嶅姟銆傝繍杈撴椂闂?10-15 澶╋紝鍙彁渚涙暣杞︼紙FTL锛夊拰闆舵媴锛圠TL锛変袱绉嶆ā寮忋€?,
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

    // 鏍囪宸插～鍏?    update_option('freight_demo_seeded', true);

    // 璺宠浆鍥為椤靛苟鏄剧ず鎴愬姛娑堟伅
    wp_redirect(add_query_arg('seeded', '1', home_url()));
    exit;
}

// ==========================================
// 绗竴妗ｏ細鑷畾涔夎浠疯〃鍗曠煭浠ｇ爜 [inquiry_form]
// ==========================================
/**
 * 璐ц繍璇环琛ㄥ崟鐭唬鐮? * 
 * 鐢ㄦ硶锛歔inquiry_form]
 * 鍦ㄥ墠绔樉绀轰竴涓畬鏁寸殑璇环琛ㄥ崟锛屽寘鍚細
 *   鍙戣揣鍦?鐩殑鍦?璐х墿绫诲瀷/閲嶉噺/浣撶Н/鑱旂郴浜轰俊鎭? * 琛ㄥ崟鎻愪氦鍚庨€氳繃 wp_mail() 鍙戦€佸埌绠＄悊鍛橀偖绠? * 
 * 瀹夊叏鎺柦锛? *   1. wp_nonce 闃?CSRF 鏀诲嚮
 *   2. sanitize_text_field 娓呯悊鎵€鏈夋枃鏈緭鍏? *   3. esc_textarea 娓呯悊澶氳鏂囨湰
 *   4. 楠岃瘉鐮佺畝鍗曢槻鏈哄櫒浜? */
add_shortcode('inquiry_form', 'freight_inquiry_form_shortcode');
function freight_inquiry_form_shortcode() {

    ob_start();

    // 澶勭悊琛ㄥ崟鎻愪氦
    $submitted = false;
    $errors    = [];
    $success   = false;

    if (isset($_POST['freight_inquiry_submit']) && wp_verify_nonce($_POST['_wpnonce'], 'freight_inquiry_action')) {

        // 鏀堕泦骞舵竻鐞嗘暟鎹?        $company   = isset($_POST['company'])   ? sanitize_text_field($_POST['company'])   : '';
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

        // 楠岃瘉蹇呭～瀛楁
        if (empty($contact)) {
            $errors[] = __('璇疯緭鍏ヨ仈绯讳汉濮撳悕', 'corporate-theme');
        }
        if (empty($phone) && empty($email)) {
            $errors[] = __('璇疯嚦灏戝～鍐欑數璇濇垨閭', 'corporate-theme');
        }
        if (empty($origin) || empty($dest)) {
            $errors[] = __('璇峰～鍐欏彂璐у湴鍜岀洰鐨勫湴', 'corporate-theme');
        }
        if (!empty($email) && !is_email($email)) {
            $errors[] = __('閭鏍煎紡涓嶆纭?, 'corporate-theme');
        }
        // 铚滅綈楠岃瘉锛氬鏋?honeypot 琚～浜嗭紝璇存槑鏄満鍣ㄤ汉
        if (!empty($honeypot)) {
            $errors[] = __('鎻愪氦澶辫触', 'corporate-theme');
        }

        if (empty($errors)) {
            // 鏋勫缓閭欢鍐呭
            $to = get_option('admin_email');
            $subject = sprintf(__('鏂拌浠凤細%s 鈫?%s', 'corporate-theme'), $origin, $dest);
            $email_body = "=== 璐ц繍璇环淇℃伅 ===\n\n";
            $email_body .= "鍏徃鍚嶇О锛歿$company}\n";
            $email_body .= "鑱旂郴浜猴細{$contact}\n";
            $email_body .= "鐢佃瘽锛歿$phone}\n";
            $email_body .= "閭锛歿$email}\n";
            $email_body .= "鍙戣揣鍦帮細{$origin}\n";
            $email_body .= "鐩殑鍦帮細{$dest}\n";
            $email_body .= "璐х墿鍚嶇О锛歿$cargo}\n";
            $email_body .= "閲嶉噺锛歿$weight} kg\n";
            $email_body .= "浣撶Н锛歿$volume} CBM\n";
            $email_body .= "澶囨敞锛歕n{$message}\n";
            $email_body .= "\n---\n";
            $email_body .= "鏉ユ簮锛歿$_SERVER['HTTP_HOST']}\n";
            $email_body .= "鏃堕棿锛? . current_time('Y-m-d H:i:s') . "\n";

            $headers = ['Content-Type: text/plain; charset=UTF-8'];

            $mail_sent = wp_mail($to, $subject, $email_body, $headers);

            if ($mail_sent) {
                $success = true;
            } else {
                $errors[] = __('閭欢鍙戦€佸け璐ワ紝璇风◢鍚庨噸璇?, 'corporate-theme');
            }
        }

        $submitted = true;
    }

    ?>
    <div class="inquiry-form-wrapper p-4 bg-light rounded border">
        <h4 class="mb-3"><?php esc_html_e('璐ц繍璇环', 'corporate-theme'); ?></h4>
        <p class="text-muted mb-4"><?php esc_html_e('濉啓浠ヤ笅淇℃伅锛屾垜浠皢鍦?2 灏忔椂鍐呯粰鎮ㄦ姤浠?, 'corporate-theme'); ?></p>

        <?php if ($submitted && $success) : ?>
            <div class="alert alert-success">
                <strong><?php esc_html_e('鉁?鎻愪氦鎴愬姛锛?, 'corporate-theme'); ?></strong>
                <?php esc_html_e('鎴戜滑宸叉敹鍒版偍鐨勮浠凤紝灏嗗湪 2 灏忔椂鍐呬笌鎮ㄨ仈绯汇€?, 'corporate-theme'); ?>
            </div>
        <?php elseif ($submitted && !empty($errors)) : ?>
            <div class="alert alert-danger">
                <strong><?php esc_html_e('璇蜂慨姝ｄ互涓嬮敊璇細', 'corporate-theme'); ?></strong>
                <ul class="mb-0 mt-1">
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="inquiry-form row g-3">
            <?php wp_nonce_field('freight_inquiry_action', '_wpnonce'); ?>

            <!-- 铚滅綈瀛楁锛堝鐢ㄦ埛涓嶅彲瑙侊紝鏈哄櫒浜轰細鑷姩濉啓锛?-->
            <div style="display:none;">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <!-- 鍏徃淇℃伅 -->
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('鍏徃鍚嶇О', 'corporate-theme'); ?></label>
                <input type="text" name="company" class="form-control"
                       value="<?php echo isset($_POST['company']) ? esc_attr($_POST['company']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細涓婃捣璐告槗鏈夐檺鍏徃', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('鑱旂郴浜?*', 'corporate-theme'); ?></label>
                <input type="text" name="contact" class="form-control" required
                       value="<?php echo isset($_POST['contact']) ? esc_attr($_POST['contact']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濮撳悕', 'corporate-theme'); ?>">
            </div>

            <!-- 鑱旂郴鏂瑰紡 -->
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('鐢佃瘽', 'corporate-theme'); ?></label>
                <input type="tel" name="phone" class="form-control"
                       value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>"
                       placeholder="<?php esc_attr_e('鎵嬫満鎴栧骇鏈?, 'corporate-theme'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('閭', 'corporate-theme'); ?></label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>"
                       placeholder="<?php esc_attr_e('example@company.com', 'corporate-theme'); ?>">
            </div>

            <!-- 杩愯緭淇℃伅 -->
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('鍙戣揣鍦?*', 'corporate-theme'); ?></label>
                <input type="text" name="origin" class="form-control" required
                       value="<?php echo isset($_POST['origin']) ? esc_attr($_POST['origin']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細涓婃捣銆佹繁鍦?, 'corporate-theme'); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('鐩殑鍦?*', 'corporate-theme'); ?></label>
                <input type="text" name="dest" class="form-control" required
                       value="<?php echo isset($_POST['dest']) ? esc_attr($_POST['dest']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細姹夊牎銆佹礇鏉夌煻', 'corporate-theme'); ?>">
            </div>

            <!-- 璐х墿淇℃伅 -->
            <div class="col-md-4">
                <label class="form-label"><?php esc_html_e('璐х墿鍚嶇О', 'corporate-theme'); ?></label>
                <input type="text" name="cargo" class="form-control"
                       value="<?php echo isset($_POST['cargo']) ? esc_attr($_POST['cargo']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細鐢靛瓙浜у搧', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php esc_html_e('棰勪及閲嶉噺 (kg)', 'corporate-theme'); ?></label>
                <input type="number" name="weight" class="form-control" step="0.1" min="0"
                       value="<?php echo isset($_POST['weight']) ? esc_attr($_POST['weight']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細500', 'corporate-theme'); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label"><?php esc_html_e('棰勪及浣撶Н (CBM)', 'corporate-theme'); ?></label>
                <input type="number" name="volume" class="form-control" step="0.01" min="0"
                       value="<?php echo isset($_POST['volume']) ? esc_attr($_POST['volume']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細2.5', 'corporate-theme'); ?>">
            </div>

            <!-- 澶囨敞 -->
            <div class="col-12">
                <label class="form-label"><?php esc_html_e('澶囨敞璇存槑', 'corporate-theme'); ?></label>
                <textarea name="message" class="form-control" rows="3"
                          placeholder="<?php esc_attr_e('鐗规畩瑕佹眰銆佽繍杈撴椂闂磋姹傜瓑', 'corporate-theme'); ?>"><?php echo isset($_POST['message']) ? esc_textarea($_POST['message']) : ''; ?></textarea>
            </div>

            <!-- 鎻愪氦鎸夐挳 -->
            <div class="col-12">
                <button type="submit" name="freight_inquiry_submit" class="btn btn-primary btn-lg">
                    <?php esc_html_e('鎻愪氦璇环', 'corporate-theme'); ?>
                </button>
            </div>
        </form>
    </div>
    <?php

    return ob_get_clean();
}

// ==========================================
// 绗竴妗ｏ細鏇存柊 page-about.php 涓鸿揣浠ｅ満鏅?// ==========================================
/**
 * 鍦?init 閽╁瓙涓€氳繃 add_rewrite_rule 纭繚椤甸潰璺敱姝ｅ父
 * 椤甸潰妯℃澘鐢?page-about.php 鏂囦欢鏈韩澶勭悊锛圵ordPress 妯℃澘灞傜骇鑷姩鍖归厤锛? * 姝ゅ鏃犻渶棰濆浠ｇ爜
 */

// ==========================================
// 绗笁妗ｏ細FAQ 鎶樺彔闈㈡澘鐭唬鐮?[faq]
// ==========================================
/**
 * FAQ 鎶樺彔闈㈡澘鐭唬鐮? * 
 * 鐢ㄦ硶锛歔faq title="闂鏍囬" open="true"]鍥炵瓟鍐呭[/faq]
 * 鏀寔澶氫釜鐭唬鐮佺粍鍚堜娇鐢紝閫氳繃 Bootstrap 鎶樺彔闈㈡澘瀹炵幇
 * 
 * 灞炴€э細
 *   title  - 闂鏍囬锛堝繀濉級
 *   open   - 鏄惁榛樿灞曞紑锛宼rue/false锛堝彲閫夛紝榛樿 false锛? * 
 * 绀轰緥锛? *   [faq title="娴疯繍闇€瑕佸涔咃紵"]娴疯繍涓€鑸渶瑕?15-35 澶╋紝鍏蜂綋鍙栧喅浜庤埅绾裤€俒/faq]
 *   [faq title="濡備綍璁＄畻杩愯垂锛? open="true"]杩愯垂鏍规嵁閲嶉噺銆佷綋绉€佽繍杈撴柟寮忕患鍚堣绠椼€俒/faq]
 */
add_shortcode('faq', 'freight_faq_shortcode');
function freight_faq_shortcode($atts, $content = null) {

    $atts = shortcode_atts([
        'title' => __('甯歌闂', 'corporate-theme'),
        'open'  => 'false',
    ], $atts, 'faq');

    $safe_title = esc_html($atts['title']);
    $safe_content = wp_kses_post($content);
    $is_open = 'true' === $atts['open'];

    // 鐢熸垚鍞竴 ID锛岄槻姝㈠涓?FAQ 鍐茬獊
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
 * 鍖呰 FAQ 鐭唬鐮佺殑瀹瑰櫒
 * 鍦ㄩ〉闈腑鍏堣皟鐢?[faq_wrapper]锛岀劧鍚庢斁澶氫釜 [faq]锛屾渶鍚?[/faq_wrapper]
 */
add_shortcode('faq_wrapper', 'freight_faq_wrapper');
function freight_faq_wrapper($atts, $content = null) {
    return '<div class="accordion" id="faq-accordion">' . do_shortcode($content) . '</div>';
}

// ==========================================
// 绗笁妗ｏ細鍦ㄧ嚎杩愯垂浼扮畻鐭唬鐮?[quote_calculator]
// ==========================================
/**
 * 鍦ㄧ嚎杩愯垂浼扮畻鐭唬鐮? * 
 * 鐢ㄦ硶锛歔quote_calculator]
 * 鍓嶇鏄剧ず涓€涓繍璐逛及绠楄〃鍗曪紝鐢ㄦ埛閫夋嫨杩愯緭鏂瑰紡鍜屽～鍐欓噸閲忓悗
 * 鑷姩璁＄畻棰勪及杩愯垂锛堜笉鍙戦€侀偖浠讹紝浠呭墠绔睍绀轰及鍊硷級
 * 
 * 杩愪环鏍囧噯锛堟紨绀虹敤锛岄潪瀹炴椂鎶ヤ环锛夛細
 *   娴疯繍锛?8/kg锛堟渶浣庢秷璐?$100锛? *   绌鸿繍锛?25/kg锛堟渶浣庢秷璐?$50锛? *   闄嗚繍锛?5/kg锛堟渶浣庢秷璐?$80锛? */
add_shortcode('quote_calculator', 'freight_quote_calculator');
function freight_quote_calculator() {

    ob_start();

    $estimated_price = '';
    $show_result = false;

    if (isset($_POST['calc_quote']) && wp_verify_nonce($_POST['_wpnonce'], 'calc_quote_action')) {

        $mode   = isset($_POST['shipping_mode']) ? sanitize_text_field($_POST['shipping_mode']) : 'sea';
        $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
        $weight = max(0, $weight);

        // 婕旂ず杩愪环琛?        $rates = [
            'sea' => ['label' => '娴疯繍', 'rate' => 8,  'min' => 100],
            'air' => ['label' => '绌鸿繍', 'rate' => 25, 'min' => 50],
            'land' => ['label' => '闄嗚繍', 'rate' => 5,  'min' => 80],
        ];

        if (isset($rates[$mode]) && $weight > 0) {
            $rate = $rates[$mode];
            $calc = $rate['rate'] * $weight;
            $total = max($calc, $rate['min']);
            $estimated_price = sprintf(
                __('绾?楼%s锛?s锛?s 脳 %s kg锛屾渶浣庢秷璐?楼%s锛?, 'corporate-theme'),
                number_format($total, 2),
                $rate['label'],
                '楼' . number_format($rate['rate'], 2),
                $weight,
                number_format($rate['min'], 2)
            );
            $show_result = true;
        } elseif ($weight <= 0) {
            $estimated_price = __('璇疯緭鍏ユ湁鏁堥噸閲?, 'corporate-theme');
        }
    }

    ?>
    <div class="quote-calculator-wrapper p-4 bg-light rounded border">
        <h4 class="mb-3"><?php esc_html_e('鍦ㄧ嚎杩愯垂浼扮畻', 'corporate-theme'); ?></h4>
        <p class="text-muted mb-4"><?php esc_html_e('閫夋嫨杩愯緭鏂瑰紡骞惰緭鍏ラ噸閲忥紝蹇€熻幏鍙栬繍璐逛及绠楀弬鑰?, 'corporate-theme'); ?></p>

        <form method="post" class="row g-3">
            <?php wp_nonce_field('calc_quote_action', '_wpnonce'); ?>

            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('杩愯緭鏂瑰紡', 'corporate-theme'); ?></label>
                <select name="shipping_mode" class="form-select form-select-lg">
                    <option value="sea" <?php selected($mode ?? '', 'sea'); ?>>
                        <?php esc_html_e('娴疯繍 楼8/kg', 'corporate-theme'); ?>
                    </option>
                    <option value="air" <?php selected($mode ?? '', 'air'); ?>>
                        <?php esc_html_e('绌鸿繍 楼25/kg', 'corporate-theme'); ?>
                    </option>
                    <option value="land" <?php selected($mode ?? '', 'land'); ?>>
                        <?php esc_html_e('闄嗚繍 楼5/kg', 'corporate-theme'); ?>
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label"><?php esc_html_e('璐х墿閲嶉噺 (kg)', 'corporate-theme'); ?></label>
                <input type="number" name="weight" class="form-control form-control-lg" step="0.1" min="0"
                       value="<?php echo isset($_POST['weight']) ? esc_attr($_POST['weight']) : ''; ?>"
                       placeholder="<?php esc_attr_e('濡傦細500', 'corporate-theme'); ?>" required>
            </div>

            <div class="col-12">
                <button type="submit" name="calc_quote" class="btn btn-primary btn-lg w-100">
                    <?php esc_html_e('浼扮畻杩愯垂', 'corporate-theme'); ?>
                </button>
            </div>
        </form>

        <?php if ($show_result && $estimated_price) : ?>
            <div class="alert alert-success mt-4">
                <h5 class="alert-heading"><?php esc_html_e('杩愯垂浼扮畻缁撴灉', 'corporate-theme'); ?></h5>
                <p class="mb-0 fs-5 fw-bold"><?php echo esc_html($estimated_price); ?></p>
                <small class="text-muted"><?php esc_html_e('* 姝や负鍙傝€冧及绠楋紝瀹為檯杩愯垂浠ユ寮忔姤浠蜂负鍑?, 'corporate-theme'); ?></small>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

// ==========================================
// 绗笁妗ｏ細鏇存柊妗堜緥灞曠ず鏂囨
// ==========================================
/**
 * archive-portfolio.php 鐨勬枃妗堝凡缁忓湪妯℃澘鏂囦欢涓洿鎺ヤ慨鏀? * 姝ゅ鏃犻渶棰濆浠ｇ爜
 */
