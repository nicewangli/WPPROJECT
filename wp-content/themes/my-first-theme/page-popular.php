<?php
/*
Template Name: 热门文章排行
*/
get_header();
?>
<main>
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $popular_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'comment_count',
        'order'          => 'DESC',
        'paged'          => $paged,
    );
    $popular_query = new WP_Query($popular_args);
    ?>
    <?php if ($popular_query->have_posts()) : ?>
    <h1>热门文章排行🔥</h1>
    <ol>
        <?php while ($popular_query->have_posts()) : $popular_query->the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <span>💬 <?php echo get_comments_number(); ?> 条评论</span>
                <span>📅 <?php echo get_the_date(); ?></span>
            </li>
        <?php endwhile; ?>
    </ol>
        <?php
            wp_reset_postdata();
        endif;
        ?>
        <?php
    if ($popular_query->max_num_pages > 1) :
    ?>
        <div class="pagination">
            <?php
            echo paginate_links(array(
                'total'    => $popular_query->max_num_pages,
                'current'  => $paged,
                'mid_size' => 2,
                'prev_text' => '← 上一页',
                'next_text' => '下一页 →',
            ));
            ?>
        </div>
    <?php endif; ?>
</main>
<?php
get_footer();
?>