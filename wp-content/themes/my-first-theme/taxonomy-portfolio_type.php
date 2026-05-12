<?php
get_header();
?>
    <main>
        <h1>
            <?php
            single_term_title( '📁 作品类型：' );
            ?>
        </h1>

        <?php
        $term_description = term_description();
        if ( $term_description ) {
            echo '<div class="term-description">' . $term_description . '</div>';
        }
        ?>

        <?php if ( have_posts() ) : ?>
            <div class="portfolio-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'excerpt' ); ?>
                <?php endwhile; ?>
            </div>

            <nav class="posts-pagination">
                <?php my_first_theme_posts_pagination(); ?>
            </nav>

        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </main>
<?php
get_footer();