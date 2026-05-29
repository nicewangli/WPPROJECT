<?php
if(!is_active_sidebar('sidebar-main')) {
    return;
}
?>

<aside id="secondary" class="col-lg-4 widget-area">
    <?php dynamic_sidebar('sidebar-main'); ?>
</aside>