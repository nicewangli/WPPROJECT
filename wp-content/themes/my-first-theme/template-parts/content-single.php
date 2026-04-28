<article>
    <?php if (has_post_thumbnail()) : the_post_thumbnail('medium');
    echo '<br>';
    echo esc_html('文章作者：' . get_the_author());
endif; ?>
    <h1><?php the_title(); ?></h1>
    <div>
        <?php the_content(); ?>
        <br>
        <?php the_time('Y-m-d'); ?>
    </div>
</article>
