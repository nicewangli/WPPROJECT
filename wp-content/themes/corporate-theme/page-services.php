<?php
/**
 * 服务介绍页面模板
 *
 * Template Name: 货运服务介绍
 *
 * 展示货代公司的四大核心服务：海运、空运、陆运、报关
 * 每个服务包含详细描述、流程说明和适用场景
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
                    <?php esc_html_e('全球化物流网络，为您提供全方位货运解决方案', 'corporate-theme'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ========== 海运服务 ========== -->
<section class="service-section py-5" id="sea-freight">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="service-icon-wrapper text-center">
                    <i class="bi bi-ship display-1 text-primary"></i>
                </div>
            </div>
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3"><?php esc_html_e('海运服务 Ocean Freight', 'corporate-theme'); ?></h2>
                <p class="lead text-muted mb-4"><?php esc_html_e('全球主要港口整柜（FCL）与拼柜（LCL）海运服务', 'corporate-theme'); ?></p>
                <p class="mb-4"><?php esc_html_e('与全球 top 20 船公司建立长期战略合作，提供极具竞争力的海运运价和稳定的舱位保障。无论是整柜还是拼柜，我们都能为您提供最优的运输方案。', 'corporate-theme'); ?></p>

                <h5 class="fw-bold mb-3"><?php esc_html_e('服务优势', 'corporate-theme'); ?></h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('20GP / 40GP / 40HQ / 45HQ 全尺寸集装箱', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('覆盖全球 500+ 港口，船期每周多班', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('提供门到门（Door to Door）一站式服务', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('危险品、特种柜、冷藏柜专业操作', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('实时货物追踪，全程状态可视化', 'corporate-theme'); ?></li>
                </ul>

                <h5 class="fw-bold mt-4 mb-3"><?php esc_html_e('海运流程', 'corporate-theme'); ?></h5>
                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('订舱', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('报关', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('2-3 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('海运', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('15-35 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('清关', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('2-3 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('派送', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-3 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-primary text-white rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('总计', 'corporate-theme'); ?></small>
                            <small>21-46 天</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="my-0">

<!-- ========== 空运服务 ========== -->
<section class="service-section py-5" id="air-freight">
    <div class="container">
        <div class="row align-items-center g-5 flex-lg-row-reverse">
            <div class="col-lg-5">
                <div class="service-icon-wrapper text-center">
                    <i class="bi bi-airplane display-1 text-primary"></i>
                </div>
            </div>
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3"><?php esc_html_e('空运服务 Air Freight', 'corporate-theme'); ?></h2>
                <p class="lead text-muted mb-4"><?php esc_html_e('高效、安全、准时的国际空运货运服务', 'corporate-theme'); ?></p>
                <p class="mb-4"><?php esc_html_e('与全球主要航空公司（国航、南航、阿联酋航空、大韩航空等）签订代理协议，提供极具竞争力的空运价格和稳定的舱位保障。适合高价值、时效性强的货物。', 'corporate-theme'); ?></p>

                <h5 class="fw-bold mb-3"><?php esc_html_e('服务优势', 'corporate-theme'); ?></h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('普货 / 特货 / 危险品空运全覆盖', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('每周固定板位，旺季保舱保价', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('空运 + 派送（DDP / DDU）一站式服务', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('实时航班追踪，运输状态随时可查', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('敏感货（含电池、液体）专业操作方案', 'corporate-theme'); ?></li>
                </ul>

                <h5 class="fw-bold mt-4 mb-3"><?php esc_html_e('空运流程', 'corporate-theme'); ?></h5>
                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('订舱', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('报关', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('空运', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('2-5 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('清关', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('派送', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-primary text-white rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('总计', 'corporate-theme'); ?></small>
                            <small>6-12 天</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="my-0">

<!-- ========== 陆运服务 ========== -->
<section class="service-section py-5" id="land-freight">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div class="service-icon-wrapper text-center">
                    <i class="bi bi-truck display-1 text-primary"></i>
                </div>
            </div>
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3"><?php esc_html_e('陆运服务 Land Freight', 'corporate-theme'); ?></h2>
                <p class="lead text-muted mb-4"><?php esc_html_e('中欧班列 + 跨境公路运输 + 国内配送网络', 'corporate-theme'); ?></p>
                <p class="mb-4"><?php esc_html_e('依托中欧班列网络和跨境公路运输体系，提供中国至中亚、欧洲、东南亚的陆运服务。兼具海运的经济性和空运的时效性，是中间选项的最佳选择。', 'corporate-theme'); ?></p>

                <h5 class="fw-bold mb-3"><?php esc_html_e('服务优势', 'corporate-theme'); ?></h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('中欧班列：成都/重庆/郑州→欧洲，12-15 天达', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('跨境公路：中国→中亚五国 / 东南亚 / 俄罗斯', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('整车（FTL）与零担（LTL）灵活选择', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('冷链运输：温控整车服务，-20°C 至 15°C', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('国内配送：全国主要城市次日达 / 隔日达', 'corporate-theme'); ?></li>
                </ul>

                <h5 class="fw-bold mt-4 mb-3"><?php esc_html_e('陆运流程', 'corporate-theme'); ?></h5>
                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('订舱', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('报关', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('运输', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('10-18 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('清关', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('派送', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-primary text-white rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('总计', 'corporate-theme'); ?></small>
                            <small>14-26 天</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="my-0">

<!-- ========== 报关服务 ========== -->
<section class="service-section py-5" id="customs-brokerage">
    <div class="container">
        <div class="row align-items-center g-5 flex-lg-row-reverse">
            <div class="col-lg-5">
                <div class="service-icon-wrapper text-center">
                    <i class="bi bi-file-earmark-check display-1 text-primary"></i>
                </div>
            </div>
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3"><?php esc_html_e('报关服务 Customs Brokerage', 'corporate-theme'); ?></h2>
                <p class="lead text-muted mb-4"><?php esc_html_e('专业报关、报检、保险一站式服务', 'corporate-theme'); ?></p>
                <p class="mb-4"><?php esc_html_e('资深报关团队，精通中国海关法规和各国进口政策。提供从报关文件准备到清关放行的全流程服务，通关率 99.8%，确保您的货物安全快速通关。', 'corporate-theme'); ?></p>

                <h5 class="fw-bold mb-3"><?php esc_html_e('服务优势', 'corporate-theme'); ?></h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('进出口报关 / 转关 / 过境报关全品类覆盖', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('HS Code 归类咨询，降低查验率', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('原产地证、商检、熏蒸等配套服务', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('货运保险：平安险 / 水渍险 / 一切险', 'corporate-theme'); ?></li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <?php esc_html_e('AEO 高级认证企业，查验率低，通关快', 'corporate-theme'); ?></li>
                </ul>

                <h5 class="fw-bold mt-4 mb-3"><?php esc_html_e('报关流程', 'corporate-theme'); ?></h5>
                <div class="row text-center g-2">
                    <div class="col-3">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('资料准备', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('申报', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('0.5 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-light rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('查验', 'corporate-theme'); ?></small>
                            <small class="text-muted"><?php esc_html_e('1-2 天', 'corporate-theme'); ?></small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="bg-primary text-white rounded p-3">
                            <small class="fw-bold d-block"><?php esc_html_e('放行', 'corporate-theme'); ?></small>
                            <small>0.5 天</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== CTA 询价区 ========== -->
<section class="cta-section bg-primary text-white py-5 text-center">
    <div class="container">
        <h2 class="fw-bold mb-3"><?php esc_html_e('需要货运报价？', 'corporate-theme'); ?></h2>
        <p class="lead mb-4"><?php esc_html_e('填写询价表单，我们将在 2 小时内为您提供最优报价方案', 'corporate-theme'); ?></p>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-light btn-lg">
            <?php esc_html_e('立即询价', 'corporate-theme'); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>