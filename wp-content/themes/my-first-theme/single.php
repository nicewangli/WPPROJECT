<?php
get_header();
?>
<main>
<?php
// global $wp_query;
// echo '<pre>';
// print_r($wp_query->request);
// echo '</pre>';
?>
    <h1>single.php 命中</h1>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content', 'single'); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>
<?php
    $current_post_id = get_the_ID();
    $current_categories = wp_get_post_categories($current_post_id);

    $related_args = array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'category__in'   => $current_categories,
        'post__not_in'   => array($current_post_id),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $related_query = new WP_Query($related_args);
    if ($related_query->have_posts()) :
    ?>
    <div class="related-posts">
        <h3>相关文章推荐</h3>
        <ul>
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <span><?php echo get_the_date(); ?></span>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php
    wp_reset_postdata();
    endif;
    ?>
<?php get_sidebar(); ?>
<?php get_sidebar('footer'); ?>

<?php
get_footer();
