<?php
/**
 * Template Name: 全宽布局
 *
 * 描述：不带侧边栏的全宽页面模板，适用于 Landing Page、联系我们等需要
 * 横向空间最大化的页面。可在后台页面编辑器的"页面属性"→"模板"中选择。
 *
 * @package corporate-theme
 */

get_header();
?>
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold mb-0">
                    <?php the_title(); ?>
                </h1>
            </div>
        </div>
    </div>
</section>
<section class="page-content-fullwidth py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                ?>
                    <article <?php post_class(); ?>>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                        <?php
                        wp_link_pages([
                            'before' => '<div class="page-links mt-4"><strong>' . esc_html__('分页：', 'corporate-theme') . '</strong>',
                            'after'  => '</div>',
                        ]);
                        ?>
                    </article>
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
</section>
<section class="cta-section bg-light py-5 text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold mb-3"><?php esc_html_e('准备开始合作？', 'corporate-theme'); ?></h2>
                <p class="lead text-muted mb-4">
                    <?php esc_html_e('联系我们获取免费咨询，让我们为您的企业打造专属数字化解决方案。', 'corporate-theme'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary btn-lg">
                    <?php esc_html_e('立即联系我们', 'corporate-theme'); ?>
                </a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>