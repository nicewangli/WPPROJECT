<?php
get_header();
?>
<main>
  <?php
  $get_last_three_posts = new WP_Query(array(
    'post_type' => 'post',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 3,
    'post_status' => 'publish'
  ));
  ?>
  <?php if ($get_last_three_posts->have_posts()): ?>
  <?php while ($get_last_three_posts->have_posts()) : $get_last_three_posts->the_post(); ?>
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
  
  wp_reset_postdata();
  ?>
  <?php else: ?>
    <p>暂无内容 git测试 csjp</p>
    <?php endif; ?>
</main>
<?php
get_footer();  
?>