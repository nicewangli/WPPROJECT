<?php
get_header();
?>
<main>
<?php
global $wp_query;
echo '<pre>';
print_r($wp_query->request);
echo '</pre>';
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
  <?php if (have_posts()): ?>
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