<?php get_header(); ?>

<main class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
            ?>
            <article <?php post_class('mb-4'); ?>>
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>
            </article>
            <?php
                endwhile;
                get_template_part('template-parts/content', 'pagination');
            else :
            ?>
            <p><?php esc_html_e('暂无内容', 'corporate-theme'); ?></p>
            <?php endif; ?>
        </div><!-- .col-lg-8 -->
        <?php 
        //1 构建查询参数
        $team_args = [
            'post_type' => 'team',
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC'
        ];
        //创建 wp_query对象
        $team_query = new WP_Query($team_args);
        //循环输出团队成员
        if ($team_query->have_posts()):
            echo '<div class="latest-team mb-4"><h4>最新团队成员</h4><ul class="list-unstyled">';
            while($team_query->have_posts()) :
                $team_query->the_post();
        ?>
        <li class="mb-2">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('thumbnail', ['class' => 'rounded-circle me-2', 'style' => 'width:40px;height:40px;']); ?>
                <?php the_title(); ?>
            </a>
        </li>
        <?php
    endwhile;
    echo '</ul></div>';
    wp_reset_postdata();  // ← 🔴 最关键，不写这行会出 bug！
    else :
        echo '<p>暂无团队成员</p>';
    endif;
    ?>
        <?php get_sidebar(); ?>
    </div><!-- .row -->
</main>

<?php get_footer(); ?>