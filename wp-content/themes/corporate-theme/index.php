<?php get_header(); ?>

<main class="container py-5">
    <?php
        if (have_posts()) :
            while(have_posts()) :
                the_post();
    ?>
    <article <?php post_class('mb-4')?>>
        <h2 class="entry-title">
            <a href="<?php the_permalink();?>">
                <?php the_title(); ?>
            </a>
        </h2>
        <div class="entry-content">
            <?php the_excerpt();?>
        </div>
    </article>
    <?php
        endwhile;
        the_posts_pagination(['class' => 'mt-4']);
        else :
    ?>
    <p>
            <?php esc_html_e('暂无内容','corporate-theme');?>
    </p>
    <?php endif;?>

</main>
<?php get_footer(); ?>