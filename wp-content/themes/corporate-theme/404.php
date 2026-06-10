<?php
/**
 * 404 页面模板：404.php
 *
 * 当用户访问不存在的 URL 时，WordPress 返回 404 状态码并使用此模板渲染
 * 模板层级回退链：404.php → index.php
 * 功能：道歉 + 解释 + 引导（搜索框 + 分类 + 首页链接）
 *
 * @package corporate-theme
 * @since 1.0.0
 */

get_header();
?>
<section class="error-404 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="display-1 fw-bold text-primary opacity-25 mb-4">404</div>
                <h1 class="fw-bold mb-3">
                    <?php esc_html_e('哎呀！页面不见了', 'corporate-theme'); ?>
                </h1>
                <p class="lead text-muted mb-4">
                    <?php esc_html_e('您访问的页面可能已被删除、改名或暂时不可用。请检查网址是否正确，或通过以下方式找到您需要的内容。', 'corporate-theme'); ?>
                </p>
                <div class="search-form-wrapper mb-4">
                    <?php get_search_form(); ?>
                </div>
                 <div class="row mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-house-door text-primary"></i>
                                    <?php esc_html_e('回到首页', 'corporate-theme'); ?>
                                </h5>
                                <p class="card-text small text-muted">
                                    <?php esc_html_e('从起点开始浏览我们的所有内容。', 'corporate-theme'); ?>
                                </p>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-sm">
                                    <?php esc_html_e('首页', 'corporate-theme'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-folder text-primary"></i>
                                    <?php esc_html_e('浏览分类', 'corporate-theme'); ?>
                                </h5>
                                <ul class="list-unstyled small mb-3">
                                    <?php
                                    wp_list_categories([
                                        'title_li' => '',
                                        'depth'    => 1,
                                        'show_count' => true,
                                    ]);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-file-text text-primary"></i>
                                    <?php esc_html_e('最新文章', 'corporate-theme'); ?>
                                </h5>
                                <ul class="list-unstyled small mb-3">
                                    <?php
                                    $recent_posts = new WP_Query([
                                        'posts_per_page' => 5,
                                        'no_found_rows'  => true,
                                    ]);
                                    while ($recent_posts->have_posts()) : $recent_posts->the_post();
                                    ?>
                                        <li class="mb-2">
                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                <?php the_title(); ?>
                                            </a>
                                        </li>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!-- .row -->
            </div><!-- .col-lg-8 -->
        </div><!-- .row -->
    </div><!-- .container -->
</section>                
<?php
get_footer();