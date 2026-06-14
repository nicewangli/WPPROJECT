<?php
/**
 * 商品循环中的单个商品卡片模板
 * 
 * 覆盖 WooCommerce 插件中的 templates/content-product.php
 * 用于商店页、分类页、标签页等所有商品列表页面
 * 
 * @package corporate-theme
 */

defined('ABSPATH') || exit;

/**
 * 全局变量
 * 
 * $product  — WC_Product 对象，当前商品的所有数据
 *             $product->get_id()、$product->get_price() 等方法
 * 
 * $woocommerce_loop — WC 循环配置数组
 *             ['columns'] 当前列数
 *             ['name']    当前循环名称
 */
global $product, $woocommerce_loop;

/**
 * 确保 $product 是有效的 WC_Product 对象
 * 如果不是，跳过这个循环项
 */
if (!$product || !is_a($product, 'WC_Product')) {
    return;
}
/**
 * wc_product_class() — WooCommerce 专用函数
 * 
 * 自动生成商品卡片的 CSS 类名，包含：
 *   - product（基础类）
 *   - first / last（网格位置）
 *   - product_cat-xxx（所属分类）
 *   - post-xxx（文章 ID）等
 * 
 * 第二个参数传一个数组，可以追加自定义类
 * 这里我们追加 Bootstrap 的栅格类
 * 
 * 计算公式：12 ÷ 列数 = 每列占的栅格数
 * 3列 → col-md-4 （12÷3=4）
 */
$columns = isset($woocommerce_loop['columns']) ? absint($woocommerce_loop['columns']) : 3;
$grid_class = 'col-md-' . (12 / $columns);
?>

<li <?php wc_product_class($grid_class, $product); ?>>
    <?php
    /**
     * 钩子：woocommerce_before_shop_loop_item
     * WC 默认挂载：woocommerce_template_loop_product_link_open()
     *   → 输出 <a href="商品链接" class="woocommerce-LoopProduct-link">
     */
    do_action('woocommerce_before_shop_loop_item');
    ?>
    
    <div class="card h-100 border-0 shadow-sm product-card">
                <div class="product-thumbnail position-relative overflow-hidden">
            <?php
            /**
             * 钩子：woocommerce_before_shop_loop_item_title
             * 
             * WC 默认挂载：
             *   1. woocommerce_show_product_loop_sale_flash()
             *      → 如果商品有促销价，输出 "Sale!" 标签
             *   2. woocommerce_template_loop_product_thumbnail()
             *      → 输出商品缩略图 <img>
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </div>
                <div class="card-body d-flex flex-column">
            <?php
            /**
             * 钩子：woocommerce_shop_loop_item_title
             * 
             * WC 默认挂载：woocommerce_template_loop_product_title()
             *   → 输出 <h2 class="woocommerce-loop-product__title">商品标题</h2>
             */
            do_action('woocommerce_shop_loop_item_title');

            /**
             * 钩子：woocommerce_after_shop_loop_item_title
             * 
             * WC 默认挂载：
             *   1. woocommerce_template_loop_rating()
             *      → 商品评分星级（如果有）
             *   2. woocommerce_template_loop_price()
             *      → 商品价格 <span class="price">
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>

            <div class="mt-auto">
                <?php
                /**
                 * 钩子：woocommerce_after_shop_loop_item
                 * 
                 * WC 默认挂载：woocommerce_template_loop_add_to_cart()
                 *   → 输出 "加入购物车" 按钮或 "查看详情" 链接
                 *   根据商品类型（简单/可变/分组）自动判断
                 */
                do_action('woocommerce_after_shop_loop_item');
                ?>
            </div>
        </div>
    </div>
</li>