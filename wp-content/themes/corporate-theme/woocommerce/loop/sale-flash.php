<?php
/**
 * 覆盖 WooCommerce 促销标签模板
 * 
 * 原文件：plugins/woocommerce/templates/loop/sale-flash.php
 * 覆盖位置：themes/corporate-theme/woocommerce/loop/sale-flash.php
 * 
 * 修改内容：标签文字改为"限时优惠！"，样式类改为自定义类
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

if ( $product->is_on_sale() ) :

	/**
	 * woocommerce_sale_flash 过滤器
	 * 这里我们直接修改 HTML 输出，不使用 apply_filters
	 * 这样可以让我们的修改更独立
	 */
    $sale_text = esc_html__( '限时优惠！', 'corporate-theme' );
	?>
	<span class="onsale corporate-sale-badge"><?php echo $sale_text; ?></span>
	<?php

endif;