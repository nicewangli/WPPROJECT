<?php
get_header();
?>
<main>
    <h1>single-case-study.php 命中 —— 客户案例详情</h1>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content', 'single'); ?>
            <nav class="post-navigation">
                <?php
                the_post_navigation(array(
                    'prev_text' => '← %title',
                    'next_text' => '%title →',
                ));
                ?>
            </nav>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main>
<?php
get_footer();