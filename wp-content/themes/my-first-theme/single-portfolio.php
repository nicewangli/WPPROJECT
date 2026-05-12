<?php
    get_header();
?>
<main>
    <h1>
        single-portfolio.php 命中 -- 作品详情 
    </h1>
    <?php if (have_posts()):?>
        <?php while(have_posts()):the_post();?>
            <?php get_template_part('template-parts/content','single');?>
            <nav>
                <?php
                    the_post_navigation(array(
                        'prev_text' => '← %title',
                        'next_text' => '%title →',
                    ));
                ?>
            </nav>
        <?php endwhile;?>
    <?php else : ?>
        <?php
            get_template_part('template-parts/content', 'none');
        ?>    
    <?php endif;?>    
</main>
<?php
get_footer();