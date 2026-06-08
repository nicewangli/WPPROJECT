<?php
/**
 * 文章内容模板部件
 *
 * 由 get_template_part('content', 'single') 加载
 * 用于 single.php 中的文章正文区域
 *
 * @package corporate-theme
 * @since 1.0.0
 */
?>

<article <?php post_class('mb-5'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail mb-4 text-center">
            <?php
            the_post_thumbnail('large', [
                'class' => 'img-fluid rounded shadow',
                'alt'   => esc_attr(get_the_title()),
            ]);
            ?>
        </div>
    <?php endif; ?>
        <div class="post-meta text-muted mb-3">
        <span class="me-3">
            <i class="bi bi-calendar3"></i>
            <?php echo esc_html(get_the_date()); ?>
        </span>
        <span>
            <i class="bi bi-person"></i>
            <?php echo esc_html(get_the_author()); ?>
        </span>
    </div>
        <div class="post-content">
        <?php the_content(); ?>
    </div>
        <div class="post-taxonomies mt-4 pt-3 border-top">
        <?php
        $categories = get_the_category();
        if (!empty($categories)) :
        ?>
            <div class="mb-2">
                <strong><?php esc_html_e('分类：', 'corporate-theme'); ?></strong>
                <?php
                foreach ($categories as $category) {
                    printf(
                        '<a href="%s" class="badge text-bg-primary text-decoration-none me-1">%s</a>',
                        esc_url(get_category_link($category->term_id)),
                        esc_html($category->name)
                    );
                }
                ?>
            </div>
        <?php endif; ?>

        <?php
        $tags = get_the_tags();
        if (!empty($tags)) :
        ?>
            <div>
                <strong><?php esc_html_e('标签：', 'corporate-theme'); ?></strong>
                <?php
                foreach ($tags as $tag) {
                    printf(
                        '<a href="%s" class="badge text-bg-secondary text-decoration-none me-1">%s</a>',
                        esc_url(get_tag_link($tag->term_id)),
                        esc_html($tag->name)
                    );
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</article>