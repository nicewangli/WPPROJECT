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
  <!-- <?php
  $get_last_three_posts = new WP_Query(array(
    'post_type' => 'post',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 3,
    'post_status' => 'publish'
  ));
  ?> -->
  <?php
    $recent_posts = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    ?>
    <?php
    $hot_posts = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'comment_count',
        'order'          => 'DESC',
    ));
    ?>
    <?php
    $random_posts = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'orderby'        => 'rand',
    ));
    ?>
<div class="multi-loop-sections">

    <div class="section-recent">
        <h2>最新文章</h2>
        <ul>
            <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php wp_reset_postdata(); ?>

    <div class="section-hot">
        <h2>热门文章</h2>
        <ul>
            <?php while ($hot_posts->have_posts()) : $hot_posts->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> (<?php echo get_comments_number(); ?>条评论)</li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php wp_reset_postdata(); ?>

    <div class="section-random">
        <h2>随机推荐</h2>
        <ul>
            <?php while ($random_posts->have_posts()) : $random_posts->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php wp_reset_postdata(); ?>

</div>
  <?php if (have_posts()): ?>
    <?php do_action('my_theme_featured_posts'); ?>
  <?php while (have_posts()) : the_post(); ?>
  <?php if (is_search()): ?>
    <?php get_template_part('template-parts/content','excerpt'); ?>
  <?php else: ?>
  <?php get_template_part('template-parts/content'); ?>
  <?php endif; ?>
  <?php endwhile; ?>
  <?php else: ?>
    <?php get_template_part('template-parts/content','none'); ?>
    <?php endif; ?>
</main>
<?php get_sidebar(); ?>
<?php get_sidebar('footer'); ?>
<?php
get_footer();  
?>