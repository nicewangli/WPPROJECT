<?php
get_header();
?>
<main>
    <h1>404.php 命中</h1>
    <p>抱歉，页面不存在。</p>
    <p><a href="<?php echo esc_url(home_url('/')); ?>">返回首页</a></p>
</main>
<?php
get_footer();