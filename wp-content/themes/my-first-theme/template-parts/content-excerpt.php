<article>
    <h2>
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>
    <div>
        <?php the_excerpt();  ?> 
        <br>
        <span>迁移测试</span>
        <?php the_time('Y-m-d'); ?>

    </div>
</article>