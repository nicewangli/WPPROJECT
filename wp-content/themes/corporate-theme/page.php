<?php
/**
 * 默认页面模板
 *
 * 所有页面的默认展示模板，对应 WP 模板层级中的 page.php
 *
 * @package corporate-theme
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
                    <?php esc_html_e('页面', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="page-content-area py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        ?>
                        <article <?php post_class(); ?>>
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                        <?php
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
<?php get_footer(); ?>