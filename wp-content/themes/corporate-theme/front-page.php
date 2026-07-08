<?php
get_header();
?>

<!-- ========== Hero 区 ========== -->
<section class="hero bg-primary text-white py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">
                    <?php
                    $hero_title = get_field('hero_title', 'option');
                    $hero_title_cus = get_theme_mod('hero_title');
                    if ($hero_title) {
                        echo esc_html($hero_title);
                    }elseif($hero_title_cus){
                        echo esc_html($hero_title_cus);
                    } else {
                        esc_html_e('全球物流 · 货通天下', 'corporate-theme');
                    }
                    ?>
                </h1>
                <p class="lead mb-4">
                    <?php
                    $hero_subtitle = get_field('hero_subtitle', 'option');
                    $hero_subtitle_cus = get_theme_mod('hero_subtitle');
                    if ($hero_subtitle) {
                        echo esc_html($hero_subtitle);
                    }elseif($hero_subtitle_cus){
                        echo esc_html($hero_subtitle_cus);
                    } else {
                        esc_html_e('专业国际货运代理服务，海运、空运、陆运全球门到门一站式解决方案，实时追踪货物状态。', 'corporate-theme');
                    }
                    ?>
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <?php
                    // 主按钮
                    $cta_link = get_field('hero_cta_link', 'option');
                    $cta_text = get_field('hero_cta_text', 'option');
                    $cta_url  = $cta_link ? esc_url($cta_link['url']) : esc_url(home_url('/contact/'));
                    $cta_label = $cta_text ? esc_html($cta_text) : esc_html__('在线询价', 'corporate-theme');
                    ?>
                    <a href="<?php echo $cta_url; ?>" class="btn btn-light btn-lg">
                        <?php echo $cta_label; ?>
                    </a>

                    <?php
                    // 副按钮
                    $cta2_link = get_field('hero_cta2_link', 'option');
                    $cta2_text = get_field('hero_cta2_text', 'option');
                    $cta2_url  = $cta2_link ? esc_url($cta2_link['url']) : esc_url(home_url('/shipment/'));
                    $cta2_label = $cta2_text ? esc_html($cta2_text) : esc_html__('货物追踪', 'corporate-theme');
                    ?>
                    <a href="<?php echo $cta2_url; ?>" class="btn btn-outline-light btn-lg">
                        <?php echo $cta2_label; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 服务卡片区 ========== -->
<section class="services py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?php esc_html_e('货运服务', 'corporate-theme'); ?></h2>
            <p class="text-muted"><?php esc_html_e('全球化物流网络，为您提供全方位货运解决方案', 'corporate-theme'); ?></p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-box-seam display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('海运服务', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('整柜（FCL）与拼柜（LCL）海运服务，覆盖全球主要港口，提供门到门一站式运输。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-airplane display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('空运服务', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('高效空运货运服务，适合高价值、时效性强的货物，提供实时航班追踪与状态更新。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-truck display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('陆运服务', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('跨境陆运与国内配送网络，覆盖中欧班列、中亚跨境运输，灵活高效。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== 货物追踪查询区 ========== -->
<section class="tracking-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?php esc_html_e('货物追踪', 'corporate-theme'); ?></h2>
            <p class="text-muted"><?php esc_html_e('输入追踪编号，实时查询货物运输状态', 'corporate-theme'); ?></p>
        </div>
        <?php echo do_shortcode('[tracking_form]'); ?>
    </div>
</section>

<!-- ========== 最新动态区 ========== -->
<section class="latest-posts py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?php esc_html_e('物流资讯', 'corporate-theme'); ?></h2>
            <p class="text-muted"><?php esc_html_e('了解最新行业动态和货运政策', 'corporate-theme'); ?></p>
        </div>
        <div class="row g-4">
            <?php
            $recent_posts = new WP_Query([
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ($recent_posts->have_posts()) :
                while ($recent_posts->have_posts()) :
                    $recent_posts->the_post();
            ?>
            <div class="col-md-4">
                <article <?php post_class('card border-0 shadow-sm h-100'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium', ['class' => 'card-img-top', 'alt' => esc_attr(get_the_title())]); ?>
                        </a>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                <?php the_title(); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small">
                            <?php echo esc_html(get_the_date()); ?> · 
                            <?php esc_html_e('作者：', 'corporate-theme'); echo esc_html(get_the_author()); ?>
                        </p>
                        <p class="card-text flex-grow-1">
                            <?php echo wp_kses_post(get_the_excerpt()); ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm mt-auto align-self-start">
                            <?php esc_html_e('阅读更多', 'corporate-theme'); ?>
                        </a>
                    </div>
                </article>
            </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <div class="col-12 text-center">
                <p class="text-muted"><?php esc_html_e('暂无资讯，请稍后再来。', 'corporate-theme'); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- ========== 货运服务在线预订（WooCommerce） ========== -->
<?php if (class_exists('WooCommerce')) : ?>
<section class="latest-products py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?php esc_html_e('在线订舱', 'corporate-theme'); ?></h2>
            <p class="text-muted"><?php esc_html_e('在线选择货运服务，快速提交订舱需求', 'corporate-theme'); ?></p>
        </div>
        <?php echo do_shortcode('[products limit="4" columns="4" orderby="date" order="DESC"]'); ?>
    </div>
</section>
<?php endif; ?>
<?php get_footer(); ?>
