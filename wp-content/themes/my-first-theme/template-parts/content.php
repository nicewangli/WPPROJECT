<article>

    <h2>
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>
    <div>
        <?php the_excerpt();  ?> 
        <br>
        <?php the_time('Y-m-d'); ?>
    
        <a href="<?php the_permalink(); ?>">阅读更多</a>
    </div>
</article>