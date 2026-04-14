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
  <article>
    <h2>
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>
    <div>
      <?php the_excerpt(); echo '<br>'; the_time('Y-m-d'); ?> 
      <a href="<?php the_permalink(); ?>">阅读更多</a>
    </div>
  </article>
  <?php endwhile; 
  
  ?>
  <?php else: ?>
    <p>暂无内容 git测试 csjp</p>
    <?php endif; ?>
</main>
<?php
get_footer();  
?>