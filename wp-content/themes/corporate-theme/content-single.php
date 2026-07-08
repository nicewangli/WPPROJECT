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
            <?php echo esc_html(get_the_date('Y-m-d')); ?>
        </span>
        <span class="me-3">
            <i class="bi bi-person"></i>
            <?php echo esc_html(get_the_author()); ?>
        </span>
        <span>
            <i class="bi bi-folder"></i>
            <?php
            $categories = get_the_category();
            if (!empty($categories)) {
                $cat_links = [];
                foreach ($categories as $cat) {
                    $cat_links[] = '<a href="' . esc_url(get_category_link($cat->term_id)) . '" class="text-decoration-none">' . esc_html($cat->name) . '</a>';
                }
                echo implode(', ', $cat_links);
            }
            ?>
        </span>
    </div>

    <div class="post-content">
        <?php the_content(); ?>
    </div>

    <?php
    // 文章分页（如果文章有 <!--nextpage--> 分页）
    wp_link_pages([
        'before' => '<div class="page-links mt-4"><span class="fw-bold me-2">' . __('分页：', 'corporate-theme') . '</span>',
        'after'  => '</div>',
        'link_before' => '<span class="btn btn-sm btn-outline-primary me-1">',
        'link_after'  => '</span>',
    ]);
    ?>

    <!-- 标签 -->
    <div class="post-taxonomies mt-4 pt-3 border-top">
        <?php
        $tags = get_the_tags();
        if (!empty($tags)) :
        ?>
            <div class="mb-2">
                <strong class="me-2"><?php esc_html_e('标签：', 'corporate-theme'); ?></strong>
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

    <!-- 作者信息框 -->
    <div class="author-bio-box d-flex align-items-start">
        <div class="flex-shrink-0 me-3">
            <?php echo get_avatar(get_the_author_meta('ID'), 64, '', get_the_author(), ['class' => 'author-avatar']); ?>
        </div>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1"><?php echo esc_html(get_the_author()); ?></h6>
            <p class="small text-muted mb-0">
                <?php
                $author_desc = get_the_author_meta('description');
                if ($author_desc) {
                    echo esc_html($author_desc);
                } else {
                    esc_html_e('货运物流行业专家，深耕国际物流领域多年，专注于为客户提供高效、可靠的全球货运解决方案。', 'corporate-theme');
                }
                ?>
            </p>
        </div>
    </div>
</article>