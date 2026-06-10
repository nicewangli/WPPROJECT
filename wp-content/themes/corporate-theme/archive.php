<?php
/**
 * 通用归档模板：archive.php
 *
 * 模板层级回退链：
 *   category-{slug}.php → category.php → archive.php → index.php
 *   tag-{slug}.php → tag.php → archive.php → index.php
 *   author.php → archive.php → index.php
 *   date.php → archive.php → index.php
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
                    <?php the_archive_title(); ?>
                </h1>
                <?php if (get_the_archive_description()) : ?>
                <p class="lead mt-2 mb-0">
                    <?php echo wp_kses_post(get_the_archive_description()); ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<section class="archive-content py-5">
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
                                    <i class="bi bi-person"></i>
                                    <?php echo esc_html(get_the_author()); ?>
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
                                <?php esc_html_e('阅读更多', 'corporate-theme'); ?> &rarr;
                            </a>
                        </article>
                    <?php endwhile; ?>

                    <?php get_template_part('template-parts/content', 'pagination'); ?>

                <?php else : ?>
                    <div class="alert alert-info">
                        <p class="mb-0">
                            <?php esc_html_e('当前归档分类下暂无内容。', 'corporate-theme'); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </main>

            <?php get_sidebar(); ?>
        </div>
    </div>
</section>
<?php
get_footer();