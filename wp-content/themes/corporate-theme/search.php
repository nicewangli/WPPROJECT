<?php
/**
 * 搜索结果模板：search.php
 *
 * 模板层级回退链：search.php → index.php
 *
 * @package corporate-theme
 * @since 1.0.0
 */

get_header();
?>
<section class="page-header bg-primary text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="fw-bold mb-0">
                    <?php
                    printf(
                        esc_html__('搜索：%s', 'corporate-theme'),
                        '<span class="text-warning">' . esc_html(get_search_query()) . '</span>'
                    );
                    ?>
                </h1>
                <p class="lead mt-2 mb-0">
                    <?php
                    global $wp_query;
                    printf(
                        esc_html__('共找到 %d 条结果', 'corporate-theme'),
                        $wp_query->found_posts
                    );
                    ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="search-content py-5">
    <div class="container">
        <div class="row">
            <main class="col-lg-8">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('mb-4 pb-4 border-bottom'); ?>>
                            <h2 class="entry-title h4">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="entry-meta text-muted small mb-2">
                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    <?php echo esc_html(get_the_date()); ?>
                                </span>
                                <span class="ms-3">
                                    <i class="bi bi-folder"></i>
                                    <?php the_category('、'); ?>
                                </span>
                            </div>
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary mt-2">
                                <?php esc_html_e('查看详情', 'corporate-theme'); ?> &rarr;
                            </a>
                        </article>
                    <?php endwhile; ?>

                    <?php get_template_part('template-parts/content', 'pagination'); ?>
                                    <?php else : ?>
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">
                            <?php esc_html_e('未找到相关结果', 'corporate-theme'); ?>
                        </h4>
                        <p>
                            <?php esc_html_e('抱歉，没有找到与您搜索关键词匹配的内容。请尝试其他关键词。', 'corporate-theme'); ?>
                        </p>
                    </div>
                    <div class="search-again mt-4">
                        <h5><?php esc_html_e('重新搜索：', 'corporate-theme'); ?></h5>
                        <?php get_search_form(); ?>
                    </div>
                    <div class="suggestions mt-4">
                        <h5><?php esc_html_e('或者试试以下分类：', 'corporate-theme'); ?></h5>
                        <ul class="list-inline">
                            <?php
                            wp_list_categories([
                                'title_li' => '',
                                'style'    => 'none',
                                'separator' => ' ',
                                'show_count' => true,
                            ]);
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </main>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>
<?php
get_footer();