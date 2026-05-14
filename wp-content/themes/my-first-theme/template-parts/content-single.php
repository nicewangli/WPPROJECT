<article>
    <?php if (has_post_thumbnail()) : the_post_thumbnail('medium');
    echo '<br>';
    echo esc_html('文章作者：' . get_the_author());
endif; ?>
    <h1><?php the_title(); ?></h1>
    <?php 
    $subtitle = get_post_meta(get_the_ID(),'subtitle',true);
    if (!empty($subtitle)) {
        echo '<p class="article-subtitle">' . esc_html($subtitle).'</p>';
    }
    ?>
    <div class="portfolio-taxonomies">
        <?php
        $portfolio_types = get_the_term_list(
            get_the_ID(),
            'portfolio_type',
            '<span class="tax-label">📁 作品类型：</span>',
            ' / ',
            ''
        );
        if ( $portfolio_types && ! is_wp_error( $portfolio_types ) ) {
            echo '<div class="portfolio-type-list">' . $portfolio_types . '</div>';
        }

        $portfolio_tags = get_the_term_list(
            get_the_ID(),
            'portfolio_tag',
            '<span class="tax-label">🏷️ 技术标签：</span>',
            ', ',
            ''
        );
        if ( $portfolio_tags && ! is_wp_error( $portfolio_tags ) ) {
            echo '<div class="portfolio-tag-list">' . $portfolio_tags . '</div>';
        }
        ?>
    </div>
    <div>
        <?php the_content(); ?>
        <br>
        <?php the_time('Y-m-d'); ?>
    </div>
</article>
