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
                    <?php
                    // 显示文章分类
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        $cat_names = [];
                        foreach ($categories as $cat) {
                            $cat_names[] = $cat->name;
                        }
                        echo esc_html(implode(' / ', $cat_names));
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="post-content-area py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        get_template_part('content', 'single');
                    endwhile;
                endif;
                ?>

                <!-- 文章导航 -->
                <nav class="post-navigation">
                    <div class="row">
                        <div class="col-6 text-start">
                            <?php previous_post_link(
                                '<span class="text-muted small d-block">' . __('← 上一篇', 'corporate-theme') . '</span>%link',
                                '%title'
                            ); ?>
                        </div>
                        <div class="col-6 text-end">
                            <?php next_post_link(
                                '<span class="text-muted small d-block">' . __('下一篇 →', 'corporate-theme') . '</span>%link',
                                '%title'
                            ); ?>
                        </div>
                    </div>
                </nav>

                <!-- 相关文章推荐 -->
                <div class="related-posts-section">
                    <h4 class="fw-bold mb-4"><?php esc_html_e('相关文章', 'corporate-theme'); ?></h4>
                    <div class="row g-4">
                        <?php
                        $categories = wp_get_post_categories(get_the_ID());
                        $related_args = [
                            'category__in'   => $categories,
                            'post__not_in'   => [get_the_ID()],
                            'posts_per_page' => 3,
                            'orderby'        => 'rand',
                        ];
                        $related_query = new WP_Query($related_args);
                        if ($related_query->have_posts()) :
                            while ($related_query->have_posts()) : $related_query->the_post();
                        ?>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', ['class' => 'card-img-top', 'alt' => esc_attr(get_the_title())]); ?>
                                </a>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark stretched-link">
                                            <?php the_title(); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted"><?php echo get_the_date(); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>