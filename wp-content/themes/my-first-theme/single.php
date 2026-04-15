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
    <h1>single.php 命中</h1>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content'); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>
<?php
get_footer();
