<?php
if (!is_active_sidebar('sidebar-main')) {
	return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar('sidebar-main'); ?>
    <?php 
    $lastest_posts = new WP_Query(
        array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'order' => 'DESC',
            'orderby' => 'date',
        )
    );
    ?>
    <?php if ($lastest_posts->have_posts()) : ?> 
        <h3>最新文章</h3>
        <ul>
            <?php while ($lastest_posts->have_posts()) : $lastest_posts->the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
                <span><?php echo get_the_date(); ?></span>
            </li>
            <?php endwhile; ?>
        </ul>
    <?php endif;?>
    <?php wp_reset_postdata(); ?>

    
    
</aside>