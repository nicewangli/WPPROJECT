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
    <?php if(have_rows('opportunities_challenges')):?>
        <div class="opportunities_challenges">
            <?php while(have_rows('opportunities_challenges')):the_row(); ?>
            <?php if(get_row_layout() == 'challenge_description'):?>
            <div>
                <?php the_sub_field('challenger')?>
                <?php the_sub_field('challenge_content')?>
            </div>
            
            <?php elseif(get_row_layout()=='solution'):?>
                <div>
                <?php the_sub_field('case_name')?>
                <?php the_sub_field('solution_content')?>
                </div>
            <?php endif;?>
            <?php endwhile;?>
        </div>

    <?endif;?>
</main>
<?php
get_footer();