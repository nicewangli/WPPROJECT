<?php
/**
 * 邮件头部模板 --企业主题自定义版
 * 
 * 覆盖 woocommerce 默认邮件头部，加入品牌 logo和企业风格
 * 
 * @package corporate-theme
 */

if (!defined('ABSPATH')) {
    exit;
}
$store_name = $store_name ?? get_bloginfo('name','display');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($store_name); ?></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <table width="100%" id="outer_wrapper" role="presentation">
        <tr>
            <td><!-- 留空：保持邮件客户端兼容 --></td>
            <td width="600">
                <div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="inner_wrapper" role="presentation">
                        <tr>
                            <td align="center" valign="top">
                                <!-- 🔥 品牌头部：用自定义 Logo 代替 WooCommerce 默认 -->
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="template_header" role="presentation" style="background-color: #2c3e50;">
                                    <tr>
                                        <td align="center" valign="middle">
                                            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">
                                                <?php echo esc_html($store_name); ?>
                                            </h1>
                                            <p style="color: #ecf0f1; font-size: 13px; margin: 5px 0 0 0;">
                                                <?php esc_html_e('专业 · 品质 · 信赖', 'corporate-theme'); ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                
                                <!-- 邮件标题（由 Woo 根据不同邮件类型传入） -->
                                <table border="0" cellpadding="10" cellspacing="0" width="100%" role="presentation">
                                    <tr>
                                        <td align="center" valign="top" style="padding: 20px 0 10px 0;">
                                            <h2 style="color: #2c3e50; font-size: 20px; margin: 0;">
                                                <?php echo esc_html($email_heading ?? ''); ?>
                                            </h2>
                                        </td>
                                    </tr>
                                </table>