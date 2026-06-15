</main>
<?php
do_action('corporate_before_footer');
?>

<footer class="site-footer bg-dark text-white py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h4 class="text-white mb-2"><?php esc_html_e('订阅我们的通讯', 'corporate-theme'); ?></h4>
                <p class="text-muted mb-4"><?php esc_html_e('获取最新产品动态和行业趋势', 'corporate-theme'); ?></p>
                <form class="row g-2 justify-content-center" action="#" method="post">
                    <div class="col-sm-6 col-md-4">
                        <input type="email" class="form-control" placeholder="<?php esc_attr_e('输入您的邮箱', 'corporate-theme'); ?>" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary"><?php esc_html_e('订阅', 'corporate-theme'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <hr class="my-4 border-secondary">

        <div class="row">
            <?php if (is_active_sidebar('sidebar-footer')) : ?>
                <?php dynamic_sidebar('sidebar-footer'); ?>
            <?php else : ?>
                <!-- 默认第一列：品牌信息 -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-uppercase mb-3"><?php echo esc_html(get_bloginfo('name')); ?></h5>
<p class="text-muted"><?php echo esc_html(get_bloginfo('description')); ?></p>
                </div>

                <!-- 默认第二列：快速链接 -->
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-uppercase mb-3"><?php esc_html_e('快速链接', 'corporate-theme'); ?></h5>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'list-unstyled',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ]);
                    ?>
                </div>

                <!-- 默认第三列：联系我们 -->
                <div class="col-lg-4">
                    <h5 class="text-uppercase mb-3"><?php esc_html_e('联系我们', 'corporate-theme'); ?></h5>
                    <p class="text-muted">
                        <?php esc_html_e('地址和联系信息将在后续通过 ACF 选项页配置。', 'corporate-theme'); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <hr class="my-4 border-secondary">

        <div class="text-center text-muted">
            <small>
                <?php echo wp_kses_post(get_theme_mod('footer_copyright', '&copy; 2026 公司名称。保留所有权利。')); ?>
            </small>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>