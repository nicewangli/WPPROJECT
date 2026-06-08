<?php
/**
 * 文章详情模板
 *
 * 默认文章类型（post）的单篇文章展示
 * 模板层级：single.php → singular.php → index.php
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
                    <?php esc_html_e('文章详情', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="post-content-area py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        get_template_part('content', 'single');
                    endwhile;
                endif;
                ?>
            </div>
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>
<!-- 文章导航 -->
<nav class="post-navigation border-top pt-4 mt-4">
    <div class="row">
        <div class="col-6 text-start">
            <?php previous_post_link('&laquo; %link', '%title'); ?>
        </div>
        <div class="col-6 text-end">
            <?php next_post_link('%link &raquo;', '%title'); ?>
        </div>
    </div>
</nav>

<?php get_footer(); ?>