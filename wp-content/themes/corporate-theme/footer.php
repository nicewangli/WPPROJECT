</main>

<footer class="site-footer bg-dark text-white py-5">
    <div class="container">
        <div class="row">

            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-3"><?php bloginfo('name'); ?></h5>
                <p class="text-muted">
                    <?php bloginfo('description'); ?>
                </p>
            </div>

            <div class="col-md-4 mb-4 mb-md-0">
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

            <div class="col-md-4">
                <h5 class="text-uppercase mb-3"><?php esc_html_e('联系我们', 'corporate-theme'); ?></h5>
                <p class="text-muted">
                    <?php esc_html_e('地址和联系信息将在后续通过 ACF 选项页配置。', 'corporate-theme'); ?>
                </p>
            </div>

        </div>

        <hr class="my-4 border-secondary">

        <div class="text-center text-muted">
            <small>
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.
                <?php esc_html_e('保留所有权利。', 'corporate-theme'); ?>
            </small>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>