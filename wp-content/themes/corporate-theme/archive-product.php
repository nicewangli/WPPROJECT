<?php
/**
 * WooCommerce 商店存档页模板
 * 
 * 覆盖商店首页（/shop/）和商品分类页（/product-category/xxx/）
 * 模板层级：archive-product.php → woocommerce.php → archive.php
 *
 * @package corporate-theme
 */

get_header();
?>
<div class="container mt-4">
    <div class="row">
        <main id="primary" class="col-md-9">

            <?php
            /**
             * 钩子：woocommerce_before_main_content
             * WooCommerce 在这里输出面包屑导航 + 页面标题
             * WC 源码中默认挂载了：
             *   - woocommerce_breadcrumb()  at 优先级 20
             *   - woocommerce_shop_page_header() at 优先级 10
             */
            do_action('woocommerce_before_main_content');
            /**
             * 判断当前页面类型
             * 
             * is_shop()          → 商店首页（/shop/）
             * is_product_category() → 商品分类页（/product-category/xxx/）
             * is_product_tag()   → 商品标签页（/product-tag/xxx/）
             */
            if(is_product_category()) {
                /**
                 * woocommerce_page_title() 输出当前分类标题
                 * 直接echo 字符串，不需要自己拼接
                 */
                echo '<h1 class="page-title mb-3">'.esc_html(woocommerce_page_title(false)).'</h1>';
                /**
                 * woocommerce_output_all_notices() — 输出 WC 通知消息
                 * 比如"没有找到商品"之类的提示
                 */
                woocommerce_output_all_notices();
                /**
                 * term_description() -获取当前分类的描述
                 * get_queried_object_id() 获取当前访问的分类id
                 * 用esc_html()安全转义后输出
                 */
                $category_description = term_description(get_queried_object_id(),'product_cat');
                if($category_description) {
                    echo '<div class="category-description mb-4 p-3 bg-light rounded">';
                    echo wp_kses_post($category_description);
                    echo '</div>';
                }
                // --- ACF 分类缩略图 ---
                // 获取当前分类的 ID
                $cat_id = get_queried_object_id();
                
                // 用 get_field() 读取 ACF 图片字段
                // 注意第二个参数：{$taxonomy}_{$term_id}
                // 对 product_cat 分类法：'product_cat_' + ID
                $category_image = get_field('category_image', 'product_cat_' . $cat_id);
                
                if ($category_image) {
                    // $category_image 是数组：{url, id, alt, width, height...}
                    // 用 wp_get_attachment_image() 输出带响应式的 <img> 标签
                    echo '<div class="category-image mb-3">';
                    echo wp_get_attachment_image(
                        $category_image['id'],    // 图片附件 ID
                        'large',                  // 图片尺寸：large（大图）
                        false,                    // 不输出 icon
                        ['class' => 'img-fluid rounded', 'alt' => esc_attr($category_image['alt'])]
                    );
                    echo '</div>';
    }
            }elseif (is_shop()) {
                echo '<h1 class="page-title mb-3">' . esc_html__('全部商品', 'corporate-theme') . '</h1>';
            }
            /**
             * 钩子：woocommerce_before_shop_loop
             * 
             * 在商品列表上方输出：
             *   1. 结果计数（"显示 1-12 个，共 24 个商品"）
             *   2. 排序下拉框（默认排序 / 按价格 / 按人气等）
             *   3. 商品数量切换
             * 
             * WC 默认挂载：
             *   woocommerce_result_count()       at 优先级 20
             *   woocommerce_catalog_ordering()   at 优先级 30
             */
            do_action('woocommerce_before_shop_loop');
            /**
             * woocommerce_content() — WC 核心渲染函数
             * 
             * 自动判断页面类型，执行以下流程：
             *   1. 如果是存档页 → 执行 WC_Query 获取商品列表
             *   2. 开始 WordPress 循环（while have_posts()）
             *   3. 对每个商品加载 content-product.php 模板
             *   4. 输出分页导航
             * 
             * 在这个文件里调用它，和 woocommerce.php 里效果一样
             * 但因为我们加了自定义的分类标题和描述，展示更精细
             */
            woocommerce_content();
            /**
             * 钩子：woocommerce_after_shop_loop
             * 
             * 在商品列表下方输出分页导航
             * WC 默认挂载：
             *   woocommerce_pagination() at 优先级 10
             */
            do_action('woocommerce_after_shop_loop');
            ?>
        </main>

        <aside id="secondary" class="col-md-3">
            <?php
            /**
             * woocommerce_get_sidebar() — WC 侧边栏加载函数
             * 
             * 它会先尝试加载 woocommerce/global/sidebar.php
             * 如果没有，则加载主题的 sidebar.php（你的 sidebar-main）
             * 
             * 也可以用 do_action('woocommerce_sidebar') 效果一样
             */
            woocommerce_get_sidebar();
            ?>
        </aside>
    </div>
</div>
<?php
get_footer();