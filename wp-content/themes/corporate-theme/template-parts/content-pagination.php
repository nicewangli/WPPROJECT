<?php
/**
 * 分页导航模板部件
 *
 * 用于 archive.php / search.php / index.php 等列表页的底部分页
 *
 * @package corporate-theme
 * @since 1.0.0
 */
?>

<nav class="pagination-wrap pt-4 mt-4 border-top" aria-label="<?php esc_attr_e('文章分页导航', 'corporate-theme'); ?>">
<?php
    the_posts_pagination([
        'mid_size'  => 2,
        'prev_text' => '<i class="bi bi-chevron-left"></i> ' . esc_html__('上一页', 'corporate-theme'),
        'next_text' => esc_html__('下一页', 'corporate-theme') . ' <i class="bi bi-chevron-right"></i>',
        'screen_reader_text' => esc_html__('文章分页导航', 'corporate-theme'),
    ]);
    ?>
</nav>