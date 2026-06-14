<?php
/**
 * 订单已完成邮件模板 —— 企业主题定制版
 * 
 * 覆盖 WooCommerce 默认的 "订单已完成" 顾客通知邮件
 * 
 * 以下变量由 WooCommerce 在加载模板时通过 wc_get_template() 传入
 * 
 * @var WC_Order $order          当前订单对象
 * @var string   $email_heading   邮件标题（如"订单已完成"）
 * @var bool     $sent_to_admin   是否发给管理员（false=发给顾客）
 * @var bool     $plain_text      是否纯文本模式（false=HTML邮件）
 * @var WC_Email $email           当前邮件类实例
 * 
 * @package corporate-theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
 * @hooked WC_Emails::email_header() 输出邮件头部
 */
do_action('woocommerce_email_header', $email_heading, $email);
?>

<!-- 🔥 自定义问候语区域 -->
<div style="padding: 0 20px 20px 20px;">
    <p style="color: #2c3e50; font-size: 16px; margin: 0 0 10px 0;">
        <?php
        if (!empty($order->get_billing_first_name())) {
            printf(
                esc_html__('尊敬的 %s，您好！', 'corporate-theme'),
                esc_html($order->get_billing_first_name())
            );
        } else {
            esc_html_e('尊敬的顾客，您好！', 'corporate-theme');
        }
        ?>
    </p>
    
    <p style="color: #555; font-size: 14px; margin: 0 0 15px 0;">
        <?php esc_html_e('感谢您的购买！您的订单已完成处理，以下是订单详情：', 'corporate-theme'); ?>
    </p>
</div>

<?php
/*
 * @hooked WC_Emails::order_details() 输出订单详情表格
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() 输出订单元数据
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() 输出客户信息
 */
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

// 自定义追加内容：售后提示
?>
<div style="padding: 15px 20px; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; margin: 10px 20px;">
    <p style="color: #856404; font-size: 13px; margin: 0;">
        <strong><?php esc_html_e('💡 温馨提示：', 'corporate-theme'); ?></strong>
        <?php esc_html_e('如果您对商品有任何疑问，请在7天内联系我们的客服。', 'corporate-theme'); ?>
    </p>
</div>

<?php
/*
 * @hooked WC_Emails::email_footer() 输出邮件底部
 */
do_action('woocommerce_email_footer', $email);