<article class="<?php echo (get_post_meta(get_the_ID(), 'is_featured', true) === '1') ? 'featured-post' : ''; ?>">

    <h2>
        <?php
            $subtitle = get_post_meta(get_the_ID(),'subtitle',true);
            if (!empty($subtitle)) {
                echo '<p class="article-subtitle">'.esc_html($subtitle).'</p>';
            }
        ?>
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