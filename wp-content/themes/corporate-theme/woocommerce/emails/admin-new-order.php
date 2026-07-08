<?php
/**
 * 管理员新订单通知邮件模板
 * 
 * 覆盖 WooCommerce 默认的 admin-new-order.php
 * 在邮件顶部显示订单来源信息，用于区分客户下单渠道（网站/App）
 *
 * 以下变量由 WC_Email 类在调用 wc_get_template() 时自动传入
 * 
 * @var WC_Order $order          当前订单对象
 * @var string   $email_heading   邮件标题
 * @var bool     $sent_to_admin   是否发给管理员
 * @var bool     $plain_text      是否纯文本模式
 * @var string   $additional_content 附加内容
 * @var WC_Email $email           当前邮件类实例
 *
 * @package corporate-theme
 */

// 安全防线：防止直接访问文件
if (!defined('ABSPATH')) {
    exit;
}
/*
 * woocommerce_email_header 输出邮件头部（LOGO + 标题）
 * 默认挂载：WC_Emails::email_header()
 * 这个钩子不能删，否则邮件格式会崩
 */
do_action('woocommerce_email_header', $email_heading, $email);
?>
<!-- 新增：订单来源提示条 -->
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td style="padding: 20px 20px 0 20px;">
            <table role="presentation" border="0" cellpadding="10" cellspacing="0" width="100%" style="background-color: #f8f9fa; border-radius: 4px; border-left: 4px solid #007cba;">
                <tr>
                    <td style="font-size: 14px; color: #333;">
                        <?php
                        // 从订单 meta 中读取来源
                        $order_source = get_post_meta($order->get_id(), '_order_source', true);
                        $source_label = $order_source === 'app' ? '来自 App 端' : '来自网站';
                        ?>
                        <strong><?php esc_html_e('📋 订单来源：', 'corporate-theme'); ?></strong>
                        <?php echo esc_html($source_label); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
/*
 * 核心内容区：以下钩子是 WC 邮件的核心功能
 * 必须保留，不能删除
 *
 * woocommerce_email_order_details → 输出订单商品明细表格
 *   默认挂载：WC_Emails::order_details() (优先级10)
 *
 * woocommerce_email_order_meta    → 输出订单元数据
 *   默认挂载：WC_Emails::order_meta() (优先级10)
 *
 * woocommerce_email_customer_details → 输出客户账单/收货地址
 *   默认挂载：WC_Emails::customer_details() (优先级10)
 */
?>

<p style="margin: 0 0 16px; font-size: 14px; color: #555;">
    <?php 
    printf(
        /* translators: %s: 订单号 */
        esc_html__('您收到了一笔新订单 #%s，详情如下：', 'corporate-theme'),
        esc_html($order->get_order_number())
    ); 
    ?>
</p>

<?php
// 订单商品明细
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

// 订单元数据（如支付方式、订单 notes）
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

// 客户信息（账单地址 + 收货地址）
do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

// WC 5.5+ 的附加内容
if ($additional_content) {
    echo wp_kses_post(wpautop(wptexturize($additional_content)));
}

// 邮件底部
do_action('woocommerce_email_footer', $email);