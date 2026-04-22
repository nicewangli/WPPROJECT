<?php
get_header();
?>
<main>
    <h1>archive.php 命中</h1>
    <h2><?php the_archive_title(); ?></h2>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content', 'excerpt'); ?>
        <?php endwhile; ?>
        <nav class="posts-pagination" aria-label="<?php esc_attr_e('Archive pagination', 'my-first-theme'); ?>">
            <?php my_first_theme_posts_pagination(); ?>
        </nav>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>
<?php
get_footer();
