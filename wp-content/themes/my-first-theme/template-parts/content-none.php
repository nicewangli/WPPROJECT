<article class="no-content">
    <?php if (is_search()): ?>
        <p>暂无搜索结果</p>
    <?php else: ?>     
        <h2>暂无内容</h2>
        <p>当前条件下没有找到文章，请稍后再试。</p>
        <?php endif; ?>

    <a href="<?php echo home_url(); ?>">返回首页</a>
</article>