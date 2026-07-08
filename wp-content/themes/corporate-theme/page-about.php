<?php
/**
 * 关于我们页面模板
 *
 * page-about.php → slug 为 "about" 的页面会自动使用此模板
 * 展示货代公司简介、核心优势、里程碑数据
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
                    <?php esc_html_e('全球物流网络 · 专业货运服务 · 客户至上', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 公司简介 -->
<section class="about-story py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
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

<!-- 核心优势 -->
<section class="about-advantages bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold"><?php esc_html_e('为什么选择我们', 'corporate-theme'); ?></h2>
            <p class="text-muted"><?php esc_html_e('专业、高效、可靠的全球货运服务', 'corporate-theme'); ?></p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-globe display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('全球网络覆盖', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('与全球 200+ 家船公司和航空公司建立长期合作，覆盖 100+ 国家、500+ 港口，提供门到门一站式物流解决方案。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-clock display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('实时追踪系统', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('自主研发的在线货物追踪系统，7×24 小时实时更新货物状态。客户可通过追踪编号随时查询货物位置和预计到达时间。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-shield-check display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('专业报关团队', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('资深报关团队，精通各国海关政策法规。提供报关、报检、保险一站式服务，通关率 99.8%，让您的货物安全快速通关。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-currency-dollar display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('竞争力价格', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('凭借长期稳定的货运量和良好的行业信誉，与船公司/航空公司签订优势合约运价，为客户提供极具竞争力的价格。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-headset display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('专属客服团队', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('每位客户配备专属客服经理，提供一对一服务。24 小时响应机制，确保任何问题都能在第一时间得到解决。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-4">
                    <div class="card-body text-center">
                        <i class="bi bi-recycle display-5 text-primary mb-3 d-block"></i>
                        <h5 class="card-title"><?php esc_html_e('绿色物流方案', 'corporate-theme'); ?></h5>
                        <p class="card-text text-muted"><?php esc_html_e('积极响应全球碳中和目标，优化运输路线，推广低碳运输方式，为客户提供绿色物流解决方案，助力企业可持续发展。', 'corporate-theme'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 里程碑数据 -->
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
