<?php
    get_header();
?>
    <main>
        <h1>
            archive-portfolio.php 命中 -- 作品集列表
        </h1>
        <div class="portfolio-filters">
            <div class="filter-section">
                <span class="filter-label">📁 按作品类型筛选：</span>
                <?php 
                $type_terms = get_terms( array(
                    'taxonomy' => 'portfolio_type',
                    'hide_empty' => true,
                ));
                if (! empty($type_terms) && !is_wp_error($type_terms)) {
                    echo '<a href="' . get_post_type_archive_link( 'portfolio' ) . '" class="filter-link">全部</a>';
                    foreach ( $type_terms as $term ) {
                        echo '<a href="' . get_term_link( $term ) . '" class="filter-link">' . $term->name . '</a>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="filter-section">
                <span class="filter-label">🏷️ 按技术标签筛选：</span>
                <?php
                $tag_terms = get_terms( array(
                    'taxonomy'   => 'portfolio_tag',
                    'hide_empty' => true,
                ) );
                if ( ! empty( $tag_terms ) && ! is_wp_error( $tag_terms ) ) {
                    echo '<a href="' . get_post_type_archive_link( 'portfolio' ) . '" class="filter-link">全部</a>';
                    foreach ( $tag_terms as $term ) {
                        echo '<a href="' . get_term_link( $term ) . '" class="filter-link">' . $term->name . '</a>';
                    }
                }
                ?>
            </div>
        <?php if (have_posts()) : ?>
            <?php while(have_posts()) : the_post();?>
                <?php get_template_part('template-parts/content','excerpt')?>
            <?php endwhile?>
            <nav class="posts-pagination" aria-label="<?php esc_attr_e('作品集分页导航', 'my-first-theme');?>" >
                <?php my_first_theme_posts_pagination(); ?>
            </nav>
            <?php else : ?>
            <?php get_template_part('template-parts/content', 'none'); ?>
        <?php endif; ?>
    </main>
<?php
get_footer();