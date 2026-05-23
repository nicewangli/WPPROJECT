<?php get_header(); ?>

<div class="content-area">
    <main class="site-main">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="team-single">
                    <header class="entry-header">
                        <h1><?php the_title(); ?></h1>
                    </header>

                    <div class="team-meta">
                        <?php
                        $position = get_post_meta( get_the_ID(), 'team_position', true );
                        $email    = get_post_meta( get_the_ID(), 'team_email', true );
                        ?>

                        <?php if ( $position ) : ?>
                            <p><strong>职位：</strong><?php echo esc_html( $position ); ?></p>
                        <?php endif; ?>

                        <?php if ( $email ) : ?>
                            <p><strong>邮箱：</strong>
                                <a href="mailto:<?php echo esc_attr( $email ); ?>">
                                    <?php echo esc_html( $email ); ?>
                                </a>
                            </p>
                        <?php endif; ?>

                        <p><strong>部门：</strong>
                            <?php
                            $departments = get_the_terms( get_the_ID(), 'department' );
                            if ( $departments && ! is_wp_error( $departments ) ) {
                                $dept_names = wp_list_pluck( $departments, 'name' );
                                echo esc_html( implode( ', ', $dept_names ) );
                            } else {
                                echo '未分配';
                            }
                            ?>
                        </p>

                        <p><strong>技能：</strong>
                            <?php
                            $skills = get_the_terms( get_the_ID(), 'skill' );
                            if ( $skills && ! is_wp_error( $skills ) ) {
                                $skill_names = wp_list_pluck( $skills, 'name' );
                                echo esc_html( implode( ', ', $skill_names ) );
                            } else {
                                echo '未填写';
                            }
                            ?>
                        </p>
                    </div>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="team-photo-full">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>
</div>

<?php get_footer(); ?>