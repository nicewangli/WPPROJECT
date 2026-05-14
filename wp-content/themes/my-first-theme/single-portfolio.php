<?php
    get_header();
?>
<main>
    <h1>
        single-portfolio.php 命中 -- 作品详情 
    </h1>
    <?php if (have_posts()):?>
        <?php while(have_posts()):the_post();?>
            <?php get_template_part('template-parts/content','single');?>
            <?php if(get_field('show_home')):?>
            <div>
                <h3>成员信息</h3>
                <p>
                    <strong>成员名称：</strong>
                    <?php the_field('member_name');?>
                </p>
                <p>
                    <strong>成员头像</strong>
                    <?php 
                $avatar_id = get_field('avatar');
                if ($avatar_id):
                    echo wp_get_attachment_image($avatar_id,'large');
                endif;
                ?>
                </p>
            </div>
            <?php endif; ?>
            <div>
                <h2></h2>
                <p><strong>客户名称：</strong>
                <?php the_field('client_name') ?>
                </p>
                <p><strong>项目名称：</strong>
                <?php the_field('project_date') ?>
                </p>
                <p><strong>项目链接：</strong>
                <a href="<?php the_field('project_url') ?>" target="_blank"><?php the_field('project_url') ?></a>
                </p>
                <?php 
                $screenshot_id = get_field('project_screenshot');
                if ($screenshot_id):
                    echo wp_get_attachment_image($screenshot_id,'large');
                endif;
                ?>
            </div>
            <nav>
                <?php
                    the_post_navigation(array(
                        'prev_text' => '← %title',
                        'next_text' => '%title →',
                    ));
                ?>
            </nav>
        <?php endwhile;?>
    <?php else : ?>
        <?php
            get_template_part('template-parts/content', 'none');
        ?>    
    <?php endif;?>    
    <?php if(have_rows('project_modules')):?>
        <div class="project_modules">
            <?php while(have_rows('project_modules')):the_row(); ?>
                <?php if(get_row_layout()=='text_block'):?>
                    <div class="module-text">
                        <?php the_sub_field('text_content');?>
                    </div>
                <?php elseif (get_row_layout()=='image_block'):?>
                    <div class="module-image">
                        <?php $image_id = get_sub_field( 'image' ); ?>
                        <?php if ( $image_id ) : ?>
                            <?php echo wp_get_attachment_image( $image_id, 'large' ); ?>
                        <?php endif; ?>
                        <?php if ( get_sub_field( 'caption' ) ) : ?>
                            <p class="image-caption"><?php echo esc_html( get_sub_field( 'caption' ) ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php elseif (get_row_layout()=='code_block'):?>
                    <div class="module-code">
                    <pre><code class="language-<?php echo esc_attr( get_sub_field( 'language' ) ); ?>"><?php echo esc_html( get_sub_field( 'code_content' ) ); ?></code></pre>
                </div>
                <?php endif;?>
            <?php endwhile;?>
        </div>
    <?php endif;?>
    <?php 
        $related_services = get_field('related_services');
        if($related_services) :?>
    <div class="related-services">
        <h3>相关服务</h3>
        <ul class="services-list">
            <?php foreach($related_services as $service):?>
                <li>
                    <a href="<?php echo esc_url( get_permalink( $service->ID ) ); ?>">
                        <?php echo esc_html( get_the_title( $service->ID ) ); ?>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
</main>
<?php
get_footer();