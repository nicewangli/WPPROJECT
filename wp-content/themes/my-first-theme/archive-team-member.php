<?php get_header(); ?>

<div class="content-area">
    <main class="site-main">
        <header class="page-header">
            <h1><?php post_type_archive_title(); ?></h1>
            <p>我们的专业团队</p>
        </header>

        <div class="team-grid">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="team-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="team-photo">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <h2 class="team-name">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <p class="team-position">
                            <?php
                            $position = get_post_meta( get_the_ID(), 'team_position', true );
                            if ( $position ) {
                                echo esc_html( $position );
                            }
                            ?>
                        </p>

                        <div class="team-department">
                            <?php
                            $departments = get_the_terms( get_the_ID(), 'department' );
                            if ( $departments && ! is_wp_error( $departments ) ) {
                                foreach ( $departments as $dept ) {
                                    echo '<span class="dept-tag">' . esc_html( $dept->name ) . '</span>';
                                }
                            }
                            ?>
                        </div>

                        <div class="team-skills">
                            <?php
                            $skills = get_the_terms( get_the_ID(), 'skill' );
                            if ( $skills && ! is_wp_error( $skills ) ) {
                                foreach ( $skills as $skill ) {
                                    echo '<span class="skill-tag">' . esc_html( $skill->name ) . '</span>';
                                }
                            }
                            ?>
                        </div>
                        <div class="team-join-date">
                            <?php
                            $join_date = get_field( 'team_join_date' );
                            if ( $join_date ) {
                                echo '入职：' . esc_html( $join_date );
                            }
                            ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p>暂无团队成员</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>