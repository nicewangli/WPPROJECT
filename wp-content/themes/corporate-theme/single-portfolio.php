<?php
/**
 * 单篇作品详情模板：single-portfolio.php
 *
 * 匹配 URL：/portfolio/{作品名}/
 * 模板层级：single-portfolio.php → single.php → index.php
 *
 * @package corporate-theme
 * @since 1.0.0
 */

get_header();
?>
<section class="page-header bg-primary text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="fw-bold mb-0">
                    <?php the_title(); ?>
                </h1>
                <p class="lead mt-2 mb-0">
                    <?php esc_html_e('作品详情', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="portfolio-single py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('mb-5'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="portfolio-thumbnail mb-4 text-center">
                                    <?php
                                    the_post_thumbnail('large', [
                                        'class' => 'img-fluid rounded shadow',
                                        'alt'   => esc_attr(get_the_title()),
                                    ]);
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="portfolio-content">
                                <?php the_content(); ?>
                            </div>

                        </article>
                            <div class="portfolio-meta mt-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">
                                        <?php esc_html_e('作品类型', 'corporate-theme'); ?>
                                    </h6>
                                    <?php
                                    $type_terms = get_the_term_list(
                                        get_the_ID(),
                                        'portfolio_type',
                                        '<span class="badge text-bg-primary me-1">',
                                        '</span><span class="badge text-bg-primary me-1">',
                                        '</span>'
                                    );
                                    if ($type_terms && !is_wp_error($type_terms)) {
                                        echo $type_terms;
                                    } else {
                                        echo '<span class="text-muted">—</span>';
                                    }
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">
                                        <?php esc_html_e('技术标签', 'corporate-theme'); ?>
                                    </h6>
                                    <?php
                                    $tag_terms = get_the_term_list(
                                        get_the_ID(),
                                        'portfolio_tag',
                                        '<span class="badge text-bg-secondary me-1">',
                                        '</span><span class="badge text-bg-secondary me-1">',
                                        '</span>'
                                    );
                                    if ($tag_terms && !is_wp_error($tag_terms)) {
                                        echo wp_kses_post($tag_terms);
                                    } else {
                                        echo '<span class="text-muted">—</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                                                <!-- 上一篇/下一篇导航 -->
                        <nav class="post-navigation mt-5">
                            <div class="row">
                                <div class="col-6 text-start">
                                    <?php previous_post_link('%link', '&laquo; %title'); ?>
                                </div>
                                <div class="col-6 text-end">
                                    <?php next_post_link('%link', '%title &raquo;'); ?>
                                </div>
                            </div>
                        </nav>

                    <?php endwhile; ?>

                <?php else : ?>
                    <div class="text-center py-5">
                        <h3><?php esc_html_e('作品未找到', 'corporate-theme'); ?></h3>
                        <p class="text-muted">
                            <?php esc_html_e('该作品可能已被删除或不存在。', 'corporate-theme'); ?>
                        </p>
                        <a href="<?php echo esc_url(get_post_type_archive_link('portfolio')); ?>" class="btn btn-primary">
                            <?php esc_html_e('返回作品集', 'corporate-theme'); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div><!-- .col-lg-12 -->
        </div><!-- .row -->
    </div><!-- .container -->
</section>

<?php get_footer(); ?>