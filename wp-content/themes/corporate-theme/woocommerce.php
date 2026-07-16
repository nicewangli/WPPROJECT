<?php
/**
 * WooCommerce 通用兜底模板
 * 
 * 当主题没有具体 woocommerce/ 覆盖文件时，WC 会用此文件渲染所有 WC 页面
 * 
 * @package corporate-theme
 */

get_header();
?>
<div class="container mt-4">
    <div class="row">
        <main id="primary" class="col-md-8">
            <?php
            /**
             * 钩子：woocommerce_before_main_content
             * WC 默认在这里输出面包屑导航 + 标题
             */
            do_action('woocommerce_before_main_content');

            /**
             * woocommerce_content()
             * WC 核心函数 —— 自动判断当前页面类型（商店/分类/单品/购物车等）
             * 并渲染对应内容，不需要手动写条件标签判断
             */
            woocommerce_content();

            /**
             * 钩子：woocommerce_after_main_content
             * WC 默认在这里输出一些收尾内容
             */
            do_action('woocommerce_after_main_content');
            ?>
        </main>

        <aside id="secondary" class="col-md-4">
            <?php
            // 不再使用 WC 默认侧边栏，改用我们注册的专属侧边栏
            if (is_active_sidebar('sidebar-woocommerce')) {
                dynamic_sidebar('sidebar-woocommerce');
            }
            ?>
        </aside>
    </div>
</div>
<?php
get_footer();
