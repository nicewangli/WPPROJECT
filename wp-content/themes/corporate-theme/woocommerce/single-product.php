<?php
/**
 * 商品详情页模板
 * 
 * 覆盖 WooCommerce 默认的 single-product.php
 * 使用主题统一布局（Bootstrap容器 + 主内容区 + 侧边栏）
 *
 * @package corporate-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<div class="container mt-4">
    <div class="row">
        <main id="primary" class="col-md-9">
			<?php
            /**
             * 钩子1：woocommerce_before_single_product
             * 
             * 在单个商品循环开始前触发
             * WC 默认在这里没有任何挂载，留给开发者的扩展点
             */
            do_action( 'woocommerce_before_single_product' );
            ?>

            <?php while ( have_posts() ) : ?>
                <?php the_post(); ?>
                <?php
                /**
                 * 钩子2：woocommerce_before_single_product_summary
                 * 
                 * WC 默认挂载：
                 *   - woocommerce_show_product_images (优先级 10) → 商品图片画廊
                 *   - woocommerce_show_product_sale_flash (优先级 20) → 促销标签
                 * 
                 * 输出的是「一楼橱窗区」—— 商品大图 + 缩略图
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                ?>

                <div class="summary entry-summary">
                    <?php
                    /**
                     * 钩子3：woocommerce_single_product_summary
                     * 
                     * WC 默认挂载（按优先级排序）：
                     *   5  → woocommerce_template_single_title    商品标题
                     *  10  → woocommerce_template_single_price    商品价格
                     *  15  → woocommerce_template_single_excerpt  商品短描述
                     *  20  → woocommerce_template_single_add_to_cart  加入购物车
                     *  30  → woocommerce_template_single_meta    SKU / 分类 / 标签
                     *  40  → woocommerce_template_single_sharing  社交分享按钮
                     * 
                     * 输出的是「二楼吊牌区」—— 所有购买决策信息
                     * 我们包裹在 .summary 里，方便CSS控制
                     */
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                </div>

                <?php
                /**
                 * 钩子4：woocommerce_after_single_product_summary
                 * 
                 * WC 默认挂载：
                 *  10  → woocommerce_output_product_data_tabs  描述/评论/附加信息 Tab
                 *  20  → woocommerce_output_related_products    相关商品推荐
                 * 
                 * 输出的是「三楼沙发区」—— 长描述 + 评论 + 推荐
                 */
                do_action( 'woocommerce_after_single_product_summary' );
                ?>

            <?php endwhile; ?>

            <?php
            /**
             * 钩子5：woocommerce_after_single_product
             * 
             * 在单个商品循环结束后触发
             * WC 默认无挂载，留给开发者扩展
             */
            do_action( 'woocommerce_after_single_product' );
            ?>
		</main>

        <aside id="secondary" class="col-md-3">
            <?php
            /**
             * woocommerce_sidebar 钩子
             * WC 默认挂载了 woocommerce_get_sidebar()
             * 这个函数会去找主题里的 sidebar.php（如果有的话）
             */
            do_action( 'woocommerce_sidebar' );
            ?>
        </aside>
    </div>
</div>

<?php
get_footer();