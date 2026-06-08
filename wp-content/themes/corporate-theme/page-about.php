<?php
/**
 * 关于我们页面模板
 *
 * page-about.php → slug 为 "about" 的页面会自动使用此模板
 *
 * @package corporate-theme
 */

get_header();
?>
<section class="page-header bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-3">
                    <?php the_title(); ?>
                </h1>
                <p class="lead mb-0">
                    <?php esc_html_e('了解我们的故事、使命与团队', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>
<section class="about-story py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                ?>
                    <article <?php post_class('mb-5'); ?>>
                        <div class="entry-content fs-5">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
</section>
<?php
// 使用 ACF 的 repeater 字段展示团队成员
if (have_rows('team_members', 'option')) :
?>
<section class="about-team bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?php esc_html_e('核心团队', 'corporate-theme'); ?></h2>
            <p class="text-muted"><?php esc_html_e('一群热爱技术的人', 'corporate-theme'); ?></p>
        </div>
        <div class="row g-4">
            <?php while (have_rows('team_members', 'option')) : the_row(); ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <?php
                    $photo = get_sub_field('photo');
                    if ($photo) :
                    ?>
                    <img src="<?php echo esc_url($photo['url']); ?>"
                         alt="<?php echo esc_attr($photo['alt']); ?>"
                         class="rounded-circle mx-auto mb-3"
                         width="120" height="120"
                         style="object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo esc_html(get_sub_field('name')); ?></h5>
                        <p class="text-primary small mb-2"><?php echo esc_html(get_sub_field('position')); ?></p>
                        <p class="card-text text-muted small">
                            <?php echo esc_html(get_sub_field('bio')); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<section class="about-milestones py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-6">
                <div class="milestone-item">
                    <h3 class="display-5 fw-bold text-primary">2018</h3>
                    <p class="text-muted"><?php esc_html_e('成立年份', 'corporate-theme'); ?></p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="milestone-item">
                    <h3 class="display-5 fw-bold text-primary">200+</h3>
                    <p class="text-muted"><?php esc_html_e('服务客户', 'corporate-theme'); ?></p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="milestone-item">
                    <h3 class="display-5 fw-bold text-primary">50+</h3>
                    <p class="text-muted"><?php esc_html_e('团队规模', 'corporate-theme'); ?></p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="milestone-item">
                    <h3 class="display-5 fw-bold text-primary">98%</h3>
                    <p class="text-muted"><?php esc_html_e('客户满意度', 'corporate-theme'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
