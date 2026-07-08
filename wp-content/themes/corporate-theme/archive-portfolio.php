<?php
/**
 * 客户案例归档模板：archive-portfolio.php
 * 
 * 匹配 URL：/portfolio/
 * 展示货代公司成功案例，包括客户行业、运输方案、成果数据
 * 
 * 模板层级：archive-portfolio.php → archive.php → index.php
 */
get_header();
?>

<section class="page-header bg-primary text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="fw-bold mb-0">
                    <?php esc_html_e('客户案例', 'corporate-theme'); ?>
                </h1>
                <p class="lead mt-2 mb-0">
                    <?php esc_html_e('了解我们如何帮助客户解决全球物流挑战', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="portfolio-grid py-5">
    <div class="container">
        <?php if (have_posts()) : ?>
        <div class="row g-4">
            <?php while (have_posts()) : the_post(); ?>
            <div class="col-md-6 col-lg-4">
                <article <?php post_class('card border-0 shadow-sm h-100'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                        <?php
                        the_post_thumbnail('medium', [
                            'class' => 'card-img-top',
                            'alt'   => esc_attr(get_the_title()),
                        ]);
                        ?>
                    </a>
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="text-decoration-none text-dark">
                                <?php the_title(); ?>
                            </a>
                        </h5>
                        <?php
                        // 显示行业分类
                        $portfolio_type = get_the_term_list(get_the_ID(), 'portfolio_type', '<p class="mb-2"><span class="badge bg-primary">', '</span>', '');
                        if ($portfolio_type && !is_wp_error($portfolio_type)) {
                            echo '<div class="mb-2">' . wp_kses_post($portfolio_type) . '</div>';
                        }
                        ?>
                        <p class="card-text text-muted flex-grow-1">
                            <?php echo wp_kses_post(get_the_excerpt()); ?>
                        </p>
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-outline-primary btn-sm mt-auto align-self-start">
                            <?php esc_html_e('查看详情', 'corporate-theme'); ?>
                        </a>
                    </div>
                </article>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- 分页导航 -->
        <div class="row mt-4">
            <div class="col-12">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'class'     => 'justify-content-center',
                ]);
                ?>
            </div>
        </div>

        <?php else : ?>
        <div class="row">
            <div class="col-12 text-center py-5">
                <h3><?php esc_html_e('暂无案例', 'corporate-theme'); ?></h3>
                <p class="text-muted"><?php esc_html_e('案例内容正在建设中，请稍后再来。', 'corporate-theme'); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>